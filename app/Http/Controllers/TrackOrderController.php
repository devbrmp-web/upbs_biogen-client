<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackOrderController extends Controller
{
    public function index(Request $request)
    {
        $method = $request->query('method', 'tracking');
        $value  = $request->query('search');
        $order  = null;

        if (!$value) {
            return view('track-order', [
                'order'  => null,
                'search' => null,
                'method' => $method
            ]);
        }

        $baseUrl = config('app.url_dev_admin');

        try {
            // 1. Coba endpoint tracking khusus
            if ($method === 'order_code') {
                $endpoint = "$baseUrl/api/orders/track?order_code=" . urlencode($value);
            } elseif ($method === 'phone') {
                $endpoint = "$baseUrl/api/orders/track?phone=" . urlencode($value);
            } else {
                $endpoint = "$baseUrl/api/orders/track/" . urlencode($value);
            }

            $response = Http::timeout(8)->get($endpoint);

            if ($response->successful()) {
                $json = $response->json();
                // Support structure: { order: {...} } OR { data: {...} }
                $orderData = $json['order'] ?? $json['data'] ?? [];
                if (!empty($orderData)) {
                    $order = (object) $orderData;
                }
            }

            // 2. Fallback: Jika gagal dan bukan pencarian via HP, coba direct GET /api/orders/{code}
            if (!$order && $method !== 'phone') {
                 $fallbackEndpoint = "$baseUrl/api/orders/" . urlencode($value);
                 $fallbackResponse = Http::timeout(8)->get($fallbackEndpoint);
                 
                 if ($fallbackResponse->successful()) {
                     $json = $fallbackResponse->json();
                     $orderData = $json['data'] ?? $json['order'] ?? [];
                     if (!empty($orderData)) {
                         $order = (object) $orderData;
                     }
                 }
            }

        } catch (\Throwable $e) {
            $order = null;
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.track-order-result', ['order' => $order])->render(),
                'order' => $order
            ]);
        }

        return view('track-order', [
            'order'  => $order,
            'search' => $value,
            'method' => $method
        ]);
    }

    public function detail(string $orderCode)
    {
        $baseUrl = config('app.url_dev_admin');

        try {
            $res = Http::timeout(8)->get(
                "$baseUrl/api/orders/" . urlencode($orderCode)
            );

            if (!$res->successful()) {
                abort(404);
            }

            $data = $res->json('data') ?? [];

            // ==========================================
            // 🔗 MATCH ORDER ITEMS → MASTER VARIETAS
            // ==========================================
            if (!empty($data['items']) && is_array($data['items'])) {
                $data['items'] = $this->mapOrderItemsWithVarieties($data['items']);
            }

            return view('order-detail', [
                'data' => (object) $data
            ]);

        } catch (\Throwable $e) {
            abort(404);
        }
    }

    public function signature(string $orderCode)
    {
        $baseUrl = config('app.url_dev_admin');

        try {
            $res = Http::timeout(8)->get(
                "$baseUrl/api/orders/" . urlencode($orderCode)
            );

            if (!$res->successful()) {
                abort(404);
            }

            $data = $res->json('data') ?? [];

            if (!empty($data['items']) && is_array($data['items'])) {
                $data['items'] = $this->mapOrderItemsWithVarieties($data['items']);
            }

            return view('signature', [
                'data' => (object) $data
            ]);

        } catch (\Throwable $e) {
            abort(404);
        }
    }

    /**
     * Display payment instruction page for manual transfer
     */
    public function instruction(string $orderCode)
    {
        $baseUrl = config('app.url_dev_admin');

        try {
            $res = Http::timeout(8)->get(
                "$baseUrl/api/orders/" . urlencode($orderCode) . "/payment-info"
            );

            if (!$res->successful()) {
                abort(404, 'Order tidak ditemukan');
            }

            $data = $res->json('data') ?? [];

            return view('orders.instruction', [
                'order' => (object) $data,
            ]);

        } catch (\Throwable $e) {
            abort(404, 'Gagal memuat data pesanan');
        }
    }

    /**
     * Upload payment proof and proxy to admin API
     */
    public function uploadProof(Request $request, string $orderCode)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB max
        ]);

        $baseUrl = config('app.url_dev_admin');

        try {
            $res = Http::timeout(30)
                ->attach(
                    'payment_proof',
                    file_get_contents($request->file('payment_proof')->getRealPath()),
                    $request->file('payment_proof')->getClientOriginalName()
                )
                ->post("$baseUrl/api/orders/" . urlencode($orderCode) . "/confirm-payment");

            $json = $res->json();

            // Log for debugging
            \Log::info('Payment proof upload response', [
                'order_code' => $orderCode,
                'status_code' => $res->status(),
                'response' => $json,
            ]);

            // Check both HTTP status and JSON success field
            if ($res->successful() && ($json['success'] ?? false)) {
                return redirect()
                    ->route('order.detail', ['order_code' => $orderCode])
                    ->with('success', $json['message'] ?? 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
            }

            $errorMsg = $json['message'] ?? 'Gagal mengunggah bukti pembayaran.';
            return back()->with('error', $errorMsg);

        } catch (\Throwable $e) {
            \Log::error('Payment proof upload exception', [
                'order_code' => $orderCode,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }




    // ======================================================
    // 🧠 HELPER: MATCH ORDER ITEMS → MASTER VARIETAS
    // ======================================================
    private function mapOrderItemsWithVarieties(array $items): array
    {
        $baseUrl = config('app.url_dev_admin');

        $varieties = Cache::remember('varieties_all_for_receipt', 3600, function () use ($baseUrl) {
            return Http::timeout(5)
                ->get($baseUrl . '/api/varieties')
                ->json('data') ?? [];
        });

        $byId   = [];
        $bySlug = [];

        foreach ($varieties as $v) {
            if (!empty($v['id'])) {
                $byId[$v['id']] = $v;
            }
            if (!empty($v['slug'])) {
                $bySlug[strtolower($v['slug'])] = $v;
            }
        }

        return array_map(function ($item) use ($byId, $bySlug) {

            $resolvedName = null;

            // 1️⃣ Paling akurat: variety_id
            if (!empty($item['variety_id']) && isset($byId[$item['variety_id']])) {
                $resolvedName = $byId[$item['variety_id']]['name'];
            }

            // 2️⃣ Fallback: slug
            if (!$resolvedName && !empty($item['variety_slug'])) {
                $slug = strtolower($item['variety_slug']);
                $resolvedName = $bySlug[$slug]['name'] ?? null;
            }

            // 3️⃣ Fallback terakhir: data dari order
            if (!$resolvedName) {
                $resolvedName =
                    $item['name']
                    ?? $item['variety_name']
                    ?? $item['product_name']
                    ?? 'Varietas Tidak Diketahui';
            }

            $item['resolved_variety_name'] = $resolvedName;

            return $item;
        }, $items);
    }

    public function print(string $orderCode)
    {
        $baseUrl = config('app.url_dev_admin');
        $order   = null;
        $payment = null;

        try {
            // ==============================
            // PAYMENT STATUS
            // ==============================
            $statusRes = Http::timeout(8)->get(
                "$baseUrl/api/orders/" . urlencode($orderCode) . "/payment/status"
            );

            if ($statusRes->successful()) {
                $payload = $statusRes->json();
                $order   = $payload['order'] ?? null;
                $payment = $order['payment'] ?? null;
            }

            // ==============================
            // FALLBACK ORDER DETAIL / FETCH ITEMS
            // ==============================
            // Jika order belum ada atau items kosong, ambil data lengkap
            if (!$order || empty($order['items'])) {
                $orderRes = Http::timeout(8)->get(
                    "$baseUrl/api/orders/" . urlencode($orderCode)
                );

                if ($orderRes->successful()) {
                    $fullData = $orderRes->json('data');

                    if (!$order) {
                        // Jika order awal null, pakai full data
                        $order = $fullData;
                    } else {
                        // Jika order ada (dari payment/status) tapi items kosong, lengkapi
                        $order['items'] = $fullData['items'] ?? [];
                        
                        // Lengkapi field lain jika kosong
                        $order['customer_name']    = $order['customer_name']    ?? ($fullData['customer_name'] ?? '-');
                        $order['customer_address'] = $order['customer_address'] ?? ($fullData['customer_address'] ?? '-');
                        $order['customer_phone']   = $order['customer_phone']   ?? ($fullData['customer_phone'] ?? '-');
                        $order['total_amount']     = $order['total_amount']     ?? ($fullData['total_amount'] ?? 0);
                    }
                } elseif (!$order) {
                    // Jika fetch detail gagal dan order awal juga null => 404
                    abort(404);
                }
            }

            // ==============================
            // 🔗 MATCH ITEMS → VARIETAS
            // ==============================
            if (!empty($order['items'])) {
                $order['items'] = $this->mapOrderItemsWithVarieties($order['items']);
            }

            // ==============================
            // PAID CHECK
            // ==============================
            // Status-status yang dianggap sudah lunas (atau dalam proses verifikasi)
            // pending_verification dimasukkan agar user bisa lihat "Receipt" dengan status "Menunggu Verifikasi"
            $paidStatuses = [
                'paid', 
                'processing', 
                'pickup_ready', 
                'completed', 
                'shipped',
                'picked_up',
                'pending_verification' // NEW: Allow receipt view for pending verification
            ];
            $isPaid = in_array($order['status'] ?? null, $paidStatuses);

            // Fallback: jika payment status adalah paid, anggap lunas juga
            if (!$isPaid && $payment) {
                $isPaid = ($payment['status'] ?? null) === 'paid';
            }

            if ($isPaid) {
                return view('receipt', [
                    'order'   => $order,
                    'payment' => $payment
                ]);
            }

            return view('invoice', ['order' => $order]);

        } catch (\Throwable $e) {
            abort(404);
        }
    }

    /**
     * API Proxy: Get fresh order data from admin API for JavaScript AJAX calls.
     * This is used by the history component to refresh stale localStorage data.
     */
    public function getOrderJson(string $orderCode)
    {
        $baseUrl = config('app.url_dev_admin');

        try {
            $res = Http::timeout(5)->get(
                "$baseUrl/api/orders/" . urlencode($orderCode)
            );

            if (!$res->successful()) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $data = $res->json('data') ?? [];

            return response()->json(['data' => $data]);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to fetch order'], 500);
        }
    }
}

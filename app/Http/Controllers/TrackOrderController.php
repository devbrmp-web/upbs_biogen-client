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
                $orderData = $json['order'] ?? [];
                $order = !empty($orderData) ? (object) $orderData : null;
            }
        } catch (\Throwable $e) {
            $order = null;
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
            // FALLBACK ORDER DETAIL
            // ==============================
            if (!$order) {
                $orderRes = Http::timeout(8)->get(
                    "$baseUrl/api/orders/" . urlencode($orderCode)
                );

                if (!$orderRes->successful()) {
                    abort(404);
                }

                $order = $orderRes->json('data');
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
            $isPaid = in_array($order['status'] ?? null, [
                'paid', 'completed', 'picked_up', 'shipped'
            ]);

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
}

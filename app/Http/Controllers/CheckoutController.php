<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Data penerima dari cookies (jika ada) akan dihandle oleh JS,
        // tapi kita bisa kirim view saja.
        // User instruction: "Implementasikan method index() untuk menampilkan halaman checkout dengan data penerima dari cookies"
        // Cookies are accessible in request, so we could pass them to view,
        // but JS reading document.cookie is often easier for dynamic forms.
        // Let's just return the view.
        return view('checkout');
    }

    public function process(Request $request)
    {
        $url = config('app.url_dev_admin');

        // Proxy request to Admin API
        try {
            $response = Http::timeout(15)->post($url.'/api/orders/checkout', $request->all());

            if ($response->successful()) {
                // Backup cart data for Shadow Cache Restoration
                $data = $response->json();
                $orderCode = $data['data']['order']['order_code'] ?? null;
                
                if ($orderCode && $request->has('items')) {
                    // Backup for 70 minutes (assuming order timeout is 60m)
                    Cache::put('backup_cart_' . $orderCode, $request->input('items'), now()->addMinutes(70));
                }

                return response()->json($response->json());
            }

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghubungi server pembayaran.',
            ], 500);
        }
    }

    public function syncPaymentStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string', 'max:191'],
        ]);

        $url = config('app.url_dev_admin');

        try {
            $response = Http::timeout(15)->post($url.'/api/orders/payment/sync', [
                'order_id' => (string) $validated['order_id'],
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungi server pembayaran.',
            ], 500);
        }
    }

    public function getSnapToken(Request $request)
    {
        $validated = $request->validate([
            'order_code' => ['required', 'string', 'max:191'],
        ]);

        $url = config('app.url_dev_admin');
        $orderCode = (string) $validated['order_code'];

        try {
            $response = Http::timeout(15)->get($url.'/api/orders/'.urlencode($orderCode).'/payment/snap-token');

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungi server pembayaran.',
            ], 500);
        }
    }

    public function paymentFinish(Request $request)
    {
        $orderCode = $request->query('order_id') ?? $request->query('order_code');

        // Call Admin API to verify status immediately
        if ($orderCode) {
            $url = config('app.url_dev_admin');
            try {
                Http::timeout(5)->get($url."/api/orders/{$orderCode}/payment/status");
            } catch (\Exception $e) {
                // Ignore error, page will still load and user can check status manually
            }
        }

        return view('payment-success', [
            'order_code' => $orderCode,
        ]);
    }

    public function paymentError(Request $request)
    {
        $orderCode = $request->query('order_id') ?? $request->query('order_code');
        $message = $request->query('message') ?? 'Pembayaran tidak berhasil atau dibatalkan.';

        return view('payment-error', [
            'order_code' => $orderCode,
            'message' => $message,
        ]);
    }
}

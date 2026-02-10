<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ManualCheckoutController extends Controller
{
    /**
     * Show the manual checkout page with bank details.
     */
    public function index()
    {
        // Fetch bank details from Admin API
        $url = config('app.url_dev_admin');
        $banks = [];

        try {
            // Using timeout to prevent hanging if admin server is slow
            $response = Http::timeout(5)->get($url . '/api/manual-payment/banks');
            if ($response->successful()) {
                $banks = $response->json()['data'] ?? [];
            } else {
                Log::warning('Failed to fetch bank details from Admin API. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception fetching bank details: ' . $e->getMessage());
        }

        // If no banks fetched (error or empty), we could provide fallback or show empty state
        // The view handles empty banks with a message to contact admin.

        return view('checkout-manual', ['banks' => $banks]);
    }

    /**
     * Process the checkout order creation.
     */
    public function process(Request $request)
    {
        $url = config('app.url_dev_admin');

        try {
            // Proxy request to Admin API /api/orders/checkout
            $response = Http::timeout(15)->post($url . '/api/orders/checkout', $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            Log::error('Manual checkout process failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghubungi server pembayaran.',
            ], 500);
        }
    }

    /**
     * Handle payment proof upload.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'proof' => 'required|file|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $url = config('app.url_dev_admin');
        
        try {
            // Send file to Admin API /api/manual-payment/upload
            // attach() requires file content and filename
            $file = $request->file('proof');
            
            $response = Http::timeout(30)
                ->attach(
                    'proof', 
                    file_get_contents($file->getPathname()), 
                    $file->getClientOriginalName()
                )
                ->post($url . '/api/manual-payment/upload', $request->except('proof'));

            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            Log::error('Manual payment upload failed: ' . $e->getMessage());
             return response()->json([
                'message' => 'Terjadi kesalahan saat mengunggah bukti pembayaran.',
            ], 500);
        }
    }
}

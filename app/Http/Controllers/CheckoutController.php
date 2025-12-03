<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

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
            $response = Http::timeout(15)->post($url . '/api/orders/checkout', $request->all());
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json($response->json(), $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghubungi server pembayaran.'
            ], 500);
        }
    }
}

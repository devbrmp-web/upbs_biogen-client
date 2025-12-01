<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $order = null;

        // Jika belum ada pencarian → langsung tampilkan halaman
        if (!$search) {
            return view('track-order', compact('order'));
        }

        $baseUrl = config('app.url_dev_admin'); // http://localhost:8000 (backend)

        try {
            $response = Http::get("$baseUrl/api/orders/track/$search");

            if ($response->successful()) {
                // AMBIL HANYA BAGIAN "order", supaya tidak array bersarang
                $orderData = $response->json()['order'];

                // Convert array ke object supaya Blade bisa pakai ->property
                $order = (object) $orderData;
            }

        } catch (\Exception $e) {
            $order = null;
        }

        return view('track-order', compact('order', 'search'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index(Request $request)
    {
        $method = $request->query('method', 'tracking');
        $value = $request->query('search');
        $order = null;

        if (!$value) {
            return view('track-order', ['order' => null, 'search' => null, 'method' => $method]);
        }

        $baseUrl = config('app.url_dev_admin');

        try {
            $endpoint = null;
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
                $orderData = $json['order'] ?? [
                    'order_code' => $json['order_code'] ?? null,
                    'status' => $json['status'] ?? null,
                    'shipment_status' => $json['shipment_status'] ?? null,
                    'tracking_number' => $json['tracking_number'] ?? null,
                    'courier_name' => ($json['courier']['name'] ?? null),
                ];
                $order = (object) $orderData;
            }
        } catch (\Throwable $e) {
            $order = null;
        }

        return view('track-order', ['order' => $order, 'search' => $value, 'method' => $method]);
    }

    public function detail(string $orderCode)
    {
        $baseUrl = config('app.url_dev_admin');
        try {
            $res = Http::timeout(8)->get("$baseUrl/api/orders/" . urlencode($orderCode));
            if (!$res->successful()) {
                abort(404);
            }
            $data = $res->json('data') ?? [];
            return view('order-detail', ['data' => (object) $data]);
        } catch (\Throwable $e) {
            abort(404);
        }
    }
}

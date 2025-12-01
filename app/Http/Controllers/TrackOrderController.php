<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
   public function track($keyword)
{
    $order = Order::where('order_code', $keyword)
        ->orWhere('customer_phone', $keyword)
        ->orWhere('tracking_number', $keyword)
        ->first();

    if (!$order) {
        return response()->json([
            'message' => 'Order not found',
            'order' => null
        ], 404);
    }

    return response()->json([
        'message' => 'success',
        'order' => $order
    ]);
}
public function page(Request $request)
{
    $search = $request->query('search'); // ambil keyword
    $order = null;                       // default

    if (!empty($search)) {
        $order = Order::where('order_code', $search)
            ->orWhere('customer_phone', $search)
            ->orWhere('tracking_number', $search)
            ->first();
    }

    return view('views.track-order', [
        'order' => $order,
        'search' => $search
    ]);
}

}
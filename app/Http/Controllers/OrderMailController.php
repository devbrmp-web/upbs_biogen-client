<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderMailController extends Controller
{
    public function sendInvoice(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'order.order_code' => 'required|string',
            'order.status' => 'required|string',
            'order.total_amount' => 'required|numeric',
            'order.items' => 'required|array',
        ]);

        Mail::to($validated['email'])
            ->queue(new InvoiceMail($validated['order']));

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dikirim'
        ]);
    }
}

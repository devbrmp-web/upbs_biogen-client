<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $cartItems = [
            [
                'nama' => 'Benih Padi Inpari 32',
                'jumlah' => 2,
                'harga' => 45000,
                'gambar' => Vite::asset('resources/img/sample-product.jpg'),
            ],
            [
                'nama' => 'Benih Jagung Hibrida Bisi 2',
                'jumlah' => 1,
                'harga' => 60000,
                'gambar' => Vite::asset('resources/img/sample-product.jpg'),
            ],
        ];

        return view('components.cart-modal', compact('cartItems'));
    }
}

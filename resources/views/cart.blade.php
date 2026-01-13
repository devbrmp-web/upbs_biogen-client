@extends('layouts.app')

@section('title', 'Keranjang Belanja • UPBS BRMP Biogen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-28 page-animate-slideRight">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column: Cart Items -->
        <div class="flex-1">
            <div class="bg-white shadow rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Item</h2>
                    <button onclick="window.cart.clearCart()" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        Hapus Semua
                    </button>
                </div>

                <div id="cart-list-container" class="divide-y divide-gray-100">
                    <!-- Cart items rendered by JS -->
                    <div class="p-8 text-center text-gray-500">
                        Memuat keranjang...
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="/katalog" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    ← Kembali Belanja
                </a>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <div class="w-full lg:w-96 flex-shrink-0">
            <div class="bg-white shadow rounded-xl p-6 border border-gray-200 sticky top-24">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>
                
                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Total Item</span>
                        <span id="summary-count" class="font-medium text-gray-900">0 Varietas</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Total Berat</span>
                        <span id="summary-weight" class="font-medium text-gray-900">0 kg</span>
                    </div>
                    <div class="border-t border-gray-100 my-2 pt-2 flex justify-between text-lg font-bold">
                        <span class="text-gray-900">Total Harga</span>
                        <span id="summary-total" class="text-blue-600">Rp 0</span>
                    </div>
                </div>

                <button id="btn-checkout" onclick="window.location.href='/checkout'" 
                    class="w-full bg-green-600 text-white py-3 rounded-lg font-bold shadow-lg shadow-green-200 hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Buat Pesanan
                </button>
                
                <p id="checkout-error-msg" class="hidden mt-3 text-xs text-red-600 text-center">
                    Harap penuhi minimal pembelian per kelas benih.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- Script Cart Logic inline or loaded via Vite --}}
@endpush
@endsection

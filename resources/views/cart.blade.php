@extends('layouts.app')

@section('title', 'Keranjang Belanja • UPBS BRMP Biogen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-20 page-animate-rise relative z-10">
    
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-96 bg-emerald-400/20 rounded-full blur-3xl -z-10 opacity-40 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl -z-10 opacity-30 pointer-events-none"></div>

    <div class="flex items-center justify-between mb-8">
        @if(session('flash_message'))
        <div id="restoration-alert" class="w-full mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            <div>
                <p class="font-bold">Waktu Pembayaran Habis</p>
                <p class="text-sm">{{ session('flash_message') }}</p>
            </div>
        </div>
        @endif

        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Keranjang Belanja</h1>
        <a href="/katalog" class="hidden md:inline-flex items-center gap-2 text-slate-600 hover:text-emerald-600 font-medium transition-colors group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Lanjut Belanja
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column: Cart Items -->
        <div class="flex-1">
            <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl overflow-hidden">
                <div class="p-6 border-b border-white/40 flex justify-between items-center bg-white/30">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-emerald-600"></i> Daftar Item
                    </h2>
                    <button onclick="window.cart.clearCart()" class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1 transition-colors hover:bg-red-50 px-3 py-1.5 rounded-lg">
                        <i class="fa-regular fa-trash-can"></i> Hapus Semua
                    </button>
                </div>

                <div id="cart-list-container" class="divide-y divide-white/40">
                    <!-- Cart items rendered by JS -->
                    <div class="p-12 text-center text-slate-500">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-3 text-emerald-500"></i>
                        <p class="font-medium">Memuat keranjang...</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 md:hidden">
                <a href="/katalog" class="inline-flex items-center text-slate-600 hover:text-emerald-600 font-medium gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
                </a>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <div class="w-full lg:w-96 flex-shrink-0">
            <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-6 sticky top-28 transition-all hover:shadow-2xl hover:shadow-emerald-900/5">
                <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-200/50">
                    <i class="fa-solid fa-receipt text-blue-600"></i> Ringkasan Belanja
                </h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-slate-600 text-sm">
                        <span class="font-medium">Total Item</span>
                        <span id="summary-count" class="font-bold text-slate-800 bg-white/50 px-2 py-1 rounded-md border border-white/50">0 Varietas</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600 text-sm">
                        <span class="font-medium">Total Berat</span>
                        <span id="summary-weight" class="font-bold text-slate-800 bg-white/50 px-2 py-1 rounded-md border border-white/50">0 kg</span>
                    </div>
                    <div class="border-t border-dashed border-slate-300 my-2 pt-4 flex justify-between items-center">
                        <span class="text-slate-800 font-bold text-lg">Total Harga</span>
                        <span id="summary-total" class="text-emerald-600 font-extrabold text-xl">Rp 0</span>
                    </div>
                </div>

                <button id="btn-checkout" onclick="window.location.href='/checkout'" 
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white py-4 rounded-xl font-bold shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span>Buat Pesanan</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
                
                <p id="checkout-error-msg" class="hidden mt-4 text-xs text-red-500 text-center bg-red-50 p-3 rounded-xl border border-red-100 font-medium">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Harap penuhi minimal pembelian per kelas benih.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @if(session('restored_cart'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            try {
                const restoredItems = @json(session('restored_cart'));
                
                if (restoredItems && Array.isArray(restoredItems) && restoredItems.length > 0) {
                    // Wait for window.cart to be available
                    const restoreInterval = setInterval(() => {
                        if (window.cart) {
                            clearInterval(restoreInterval);
                            window.cart.data.items = restoredItems;
                            window.cart.save();
                            console.log('Cart restored from shadow backup.');
                        }
                    }, 100);
                    
                    // Fallback if cart not loaded in 5s
                    setTimeout(() => clearInterval(restoreInterval), 5000);
                }
            } catch (e) {
                console.error('Failed to restore cart:', e);
            }
        });
    </script>
    @endif
    {{-- Script Cart Logic inline or loaded via Vite --}}
@endpush
@endsection

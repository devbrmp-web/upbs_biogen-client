@extends('layouts.app')

@section('title', 'Cek Pesanan • UPBS BRMP Biogen')

@section('content')
@if(isset($order))
    <script>
        console.log("Order:", @json($order));
    </script>
@endif

@if(isset($search))
    <script>
        console.log("Search Query:", @json($search));
    </script>
@endif


<div class="max-w-xl mx-auto py-10 mt-16">

    <h1 class="text-2xl font-bold text-center mb-6">Cek Pesanan</h1>

    <form method="GET" action="/cek-pesanan" class="bg-white p-5 rounded-xl shadow mb-6">
        <div class="flex items-center gap-3 mb-3">
            <label class="font-medium">Metode Pencarian</label>
            <select name="method" id="search-method" class="border rounded-lg p-2">
                <option value="tracking" {{ (request('method','tracking')=='tracking') ? 'selected' : '' }}>Tracking Number</option>
                <option value="order_code" {{ (request('method')=='order_code') ? 'selected' : '' }}>Kode Pesanan</option>
                <option value="phone" {{ (request('method')=='phone') ? 'selected' : '' }}>Nomor HP</option>
            </select>
        </div>

        <label class="block mb-2 font-medium">Masukkan nilai sesuai metode yang dipilih</label>

        <input 
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="w-full border rounded-lg p-3 mb-4"
            placeholder="{{ request('method','tracking')=='order_code' ? 'Contoh: UPBS123456' : (request('method')=='phone' ? 'Contoh: 08123456789' : 'Contoh: TRK123') }}"
            required
        >

        <p class="text-xs text-gray-500 mb-3">Tips: gunakan Tracking Number dari kurir untuk status pengiriman, Kode Pesanan untuk status pembayaran, atau Nomor HP untuk riwayat terbaru.</p>

        <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold"
        >
            Cari Pesanan
        </button>
    </form>

    @if(request()->has('search'))
        @if(!$order)
            <div class="bg-white p-5 rounded-xl shadow text-center">
                <p class="text-red-500 font-semibold">Pesanan tidak ditemukan.</p>
            </div>
        @else
            <div class="bg-white p-5 rounded-xl shadow">

                <div class="flex justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Kode Pesanan</p>
                        <p class="font-semibold text-lg">{{ $order->order_code }}</p>
                    </div>

                    <span class="px-3 py-1 text-sm rounded-full 
                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 
                           ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           'bg-blue-100 text-blue-700') }}">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="mt-4">
                    <p class="text-gray-600 text-sm">Status Pengiriman</p>
                    <p class="font-medium">{{ $order->shipment_status ?? '-' }}</p>
                </div>

                <div class="mt-4">
                    <p class="text-gray-600 text-sm">Tracking Number</p>
                    <p class="font-medium">{{ $order->tracking_number ?? '-' }}</p>
                </div>

                <div class="mt-4">
                    <p class="text-gray-600 text-sm">Kurir</p>
                    <p class="font-medium">{{ $order->courier_name ?? '-' }}</p>
                </div>

                <div class="mt-6">
                    <a href="/pesanan/{{ $order->order_code }}"
                        class="block text-center bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-medium">
                        Lihat Detail
                    </a>
                </div>

            </div>
        @endif
    @endif

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodSelect = document.getElementById('search-method');
    const input = document.querySelector('input[name="search"]');
    const placeholders = {
        tracking: 'Contoh: TRK123',
        order_code: 'Contoh: UPBS123456',
        phone: 'Contoh: 08123456789'
    };
    methodSelect.addEventListener('change', function() {
        input.placeholder = placeholders[this.value] || placeholders.tracking;
    });
});
</script>
@endpush
@endsection

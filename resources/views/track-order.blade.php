@extends('layouts.app')

@section('title', 'Cek Pesanan • UPBS BRMP Biogen')

@section('content')

<div class="max-w-xl mx-auto py-10 mt-16">

    <h1 class="text-2xl font-bold text-center mb-6">Cek Pesanan</h1>

    <form method="GET" action="/cek-pesanan" class="bg-white p-5 rounded-xl shadow mb-6">
        <label class="block mb-2 font-medium">Masukkan Kode Pesanan, Nomor Telepon, atau Tracking Number</label>

        <input 
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="w-full border rounded-lg p-3 mb-4"
            placeholder="Contoh: UPBS123456 / 08123456789 / TRK123"
            required
        >

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

@endsection

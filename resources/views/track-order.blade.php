@extends('layouts.app')

@section('title', 'Cek Pesanan • UPBS BRMP Biogen')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 mt-16 page-animate-fadeIn">
  <h1 class="text-2xl font-bold mb-6 text-center">Cek Pesanan</h1>

  @if(!request()->has('search'))
    <div class="max-w-md mx-auto">
      <form method="GET" action="/cek-pesanan" id="track-form" class="bg-white p-6 rounded-xl shadow animate-fadeIn">
        <div class="mb-4">
          <label class="block mb-2 font-medium">Metode Pencarian</label>
          <select name="method" class="w-full border rounded-lg p-3">
            <option value="tracking" {{ (request('method','tracking')=='tracking') ? 'selected' : '' }}>Tracking Number</option>
            <option value="order_code" {{ (request('method')=='order_code') ? 'selected' : '' }}>Kode Pesanan</option>
            <option value="phone" {{ (request('method')=='phone') ? 'selected' : '' }}>Nomor HP</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block mb-2 font-medium">Masukkan nilai pencarian</label>
          <input type="text" name="search" value="{{ request('search') }}" class="w-full border rounded-lg p-3" placeholder="{{ request('method','tracking')=='order_code' ? 'Contoh: UPBS123456' : (request('method')=='phone' ? 'Contoh: 08123456789' : 'Contoh: TRK123') }}" required>
          <p class="text-xs text-gray-500 mt-2">Gunakan Tracking untuk status pengiriman, Kode Pesanan untuk status pembayaran, atau Nomor HP untuk pesanan terakhir.</p>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold">Cari Pesanan</button>

        <div id="loading" class="hidden mt-4 flex items-center gap-2 text-gray-600">
          <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
          <span>Mencari pesanan…</span>
        </div>
      </form>
    </div>
  @else
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fadeIn">
    <div class="lg:col-span-1">
      <form method="GET" action="/cek-pesanan" id="track-form" class="bg-white p-5 rounded-xl shadow">
        <div class="mb-4">
          <label class="block mb-2 font-medium">Metode Pencarian</label>
          <select name="method" class="w-full border rounded-lg p-3">
            <option value="tracking" {{ (request('method','tracking')=='tracking') ? 'selected' : '' }}>Tracking Number</option>
            <option value="order_code" {{ (request('method')=='order_code') ? 'selected' : '' }}>Kode Pesanan</option>
            <option value="phone" {{ (request('method')=='phone') ? 'selected' : '' }}>Nomor HP</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block mb-2 font-medium">Masukkan nilai pencarian</label>
          <input type="text" name="search" value="{{ request('search') }}" class="w-full border rounded-lg p-3" placeholder="{{ request('method','tracking')=='order_code' ? 'Contoh: UPBS123456' : (request('method')=='phone' ? 'Contoh: 08123456789' : 'Contoh: TRK123') }}" required>
          <p class="text-xs text-gray-500 mt-2">Gunakan Tracking untuk status pengiriman, Kode Pesanan untuk status pembayaran, atau Nomor HP untuk pesanan terakhir.</p>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold">Cari Pesanan</button>

        <div id="loading" class="hidden mt-4 flex items-center gap-2 text-gray-600">
          <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
          <span>Mencari pesanan…</span>
        </div>
      </form>
    </div>

    <div class="lg:col-span-2">
      @if(request()->has('search'))
        @if(!$order)
          <div class="bg-white p-6 rounded-xl shadow text-center animate-fadeIn">
            <p class="text-red-600 font-semibold">Pesanan tidak ditemukan.</p>
          </div>
        @else
          <div class="bg-white p-6 rounded-xl shadow animate-fadeIn">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm text-gray-600">Kode Pesanan</p>
                <p class="text-lg font-semibold">{{ $order->order_code }}</p>
              </div>
              @php
                $map = [
                  'completed' => 'bg-green-100 text-green-700',
                  'paid' => 'bg-green-100 text-green-700',
                  'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
                  'processing' => 'bg-blue-100 text-blue-700',
                  'delivery_coordination' => 'bg-blue-100 text-blue-700',
                  'shipped' => 'bg-blue-100 text-blue-700',
                  'pickup_ready' => 'bg-blue-100 text-blue-700'
                ];
                $cls = $map[$order->status] ?? 'bg-gray-100 text-gray-800';
              @endphp
              <span class="px-3 py-1 text-sm rounded-full {{ $cls }}">{{ $order->status }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
              <div class="p-4 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">Kurir</p>
                <p class="font-medium">{{ $order->courier_name ?? '-' }}</p>
              </div>
              <div class="p-4 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">Tracking Number</p>
                <p class="font-medium">{{ $order->tracking_number ?? '-' }}</p>
              </div>
              <div class="p-4 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">Status Pengiriman</p>
                <p class="font-medium">{{ $order->shipment_status ?? '-' }}</p>
              </div>
            </div>

            <div class="mt-6">
              <a href="/pesanan/{{ $order->order_code }}" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">Lihat Detail</a>
            </div>
          </div>
        @endif
      @endif
    </div>
  </div>
  @endif
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('track-form');
    var loading = document.getElementById('loading');
    if (form) {
      form.addEventListener('submit', function() { loading.classList.remove('hidden'); });
    }
  });
</script>

@endsection

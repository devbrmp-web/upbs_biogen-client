@extends('layouts.app')

@section('title', 'Detail Pesanan • UPBS BRMP Biogen')

@section('content')
<div class="max-w-4xl mx-auto py-10 mt-16">
  <h1 class="text-2xl font-bold mb-6">Detail Pesanan</h1>

  @if(empty($data))
    <div class="bg-white p-6 rounded-xl shadow">
      <p class="text-red-600">Pesanan tidak ditemukan.</p>
    </div>
  @else
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm text-gray-600">Kode Pesanan</p>
          <p class="text-lg font-semibold">{{ $data->order_code ?? '-' }}</p>
          <p class="text-sm text-gray-600 mt-2">Status</p>
          <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">{{ $data->status ?? '-' }}</span>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-600">Kurir</p>
          <p class="font-medium">{{ $data->courier_name ?? '-' }}</p>
          <p class="text-sm text-gray-600 mt-2">Tracking Number</p>
          <p class="font-medium">{{ $data->tracking_number ?? '-' }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h3 class="font-semibold mb-2">Data Pelanggan</h3>
          <p>{{ $data->customer_name ?? '-' }}</p>
          <p>{{ $data->customer_phone ?? '-' }}</p>
          <p class="text-sm text-gray-600">{{ $data->customer_address ?? '-' }}</p>
        </div>
        <div>
          <h3 class="font-semibold mb-2">Ringkasan Pembayaran</h3>
          <p>Subtotal: Rp {{ number_format((int)($data->subtotal ?? 0), 0, ',', '.') }}</p>
          <p>Total: Rp {{ number_format((int)($data->total_amount ?? 0), 0, ',', '.') }}</p>
        </div>
      </div>

      <div>
        <h3 class="font-semibold mb-2">Item Pesanan</h3>
        <div class="divide-y">
          @foreach(($data->items ?? []) as $it)
            <div class="py-2 flex justify-between">
              <div>
                <p class="font-medium">{{ $it['name'] ?? ('Variety #' . ($it['variety_id'] ?? '?')) }}</p>
                <p class="text-sm text-gray-600">Kelas: {{ $it['seed_class_code'] ?? '-' }} • Lot: {{ $it['seed_lot_id'] ?? '-' }}</p>
              </div>
              <div class="text-right">
                <p>{{ $it['quantity'] ?? 0 }} kg</p>
                <p class="text-sm text-gray-600">Rp {{ number_format((int)($it['unit_price'] ?? 0), 0, ',', '.') }} / kg</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="flex items-center gap-3 mt-4">
        @if(($data->status ?? '') === 'awaiting_payment')
          <a href="/checkout" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Bayar</a>
        @endif
        @if(!empty($data->invoice_url))
          <a href="{{ $data->invoice_url }}" class="px-4 py-2 rounded-lg bg-green-600 text-white" target="_blank">Unduh Invoice</a>
        @endif
        <button onclick="window.print()" class="px-4 py-2 rounded-lg bg-gray-200">Cetak</button>
      </div>
    </div>
  @endif

  <div class="mt-6">
    <a href="/cek-pesanan" class="text-blue-600">← Kembali ke Cek Pesanan</a>
  </div>
</div>
@endsection

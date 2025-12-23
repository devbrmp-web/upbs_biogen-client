@extends('layouts.app')

@section('title', 'Detail Pesanan • UPBS BRMP Biogen')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 mt-16 page-animate-rise">
  @if(empty($data))
    <div class="bg-white p-6 rounded-xl shadow">
      <p class="text-red-600">Pesanan tidak ditemukan.</p>
    </div>
  @else
    @php
      $statusMap = [
        'completed' => 'bg-green-100 text-green-700',
        'paid' => 'bg-green-100 text-green-700',
        'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
        'processing' => 'bg-blue-100 text-blue-700',
        'pickup_ready' => 'bg-blue-100 text-blue-700',
        'cancelled' => 'bg-red-100 text-red-700'
      ];
      $statusClass = $statusMap[$data->status ?? ''] ?? 'bg-gray-100 text-gray-800';
    @endphp

    <div class="bg-white p-6 rounded-xl shadow">
      <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
          <h2 class="text-xl font-semibold">Order #{{ $data->order_code ?? '-' }}</h2>
          <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm {{ $statusClass }}">{{ $data->status ?? '-' }}</div>
        </div>
        <div class="flex gap-2">
          <a href="/cek-pesanan" class="px-3 py-2 rounded-lg border border-gray-200">Kembali</a>
          @if(($data->status ?? '') === 'awaiting_payment')
            <a href="/checkout?resume_order={{ $data->order_code }}" class="px-3 py-2 rounded-lg bg-blue-600 text-white">Bayar</a>
          @endif
          <button id="btn-print" class="px-3 py-2 rounded-lg bg-gray-900 text-white" data-order-code="{{ $data->order_code }}" data-order-status="{{ $data->status }}">Cetak</button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
      <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold mb-4">Data Pelanggan</h3>
        <div class="space-y-2 text-sm">
          <div><span class="text-gray-600">Nama</span><div class="font-medium">{{ $data->customer_name ?? '-' }}</div></div>
          <div><span class="text-gray-600">Nomor HP</span><div>{{ $data->customer_phone ?? '-' }}</div></div>
          <div><span class="text-gray-600">Alamat</span><div>{{ $data->customer_address ?? '-' }}</div></div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold mb-4">Ringkasan Pembayaran</h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-medium">Rp {{ number_format((int)($data->subtotal ?? 0), 0, ',', '.') }}</span></div>
          <div class="flex justify-between"><span class="text-gray-600">Total</span><span class="font-semibold text-blue-700">Rp {{ number_format((int)($data->total_amount ?? 0), 0, ',', '.') }}</span></div>
          <div class="mt-2 text-xs text-gray-500">Status pembayaran mengikuti pembaruan dari gateway.</div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold mb-4">Informasi Pengiriman</h3>
        <div class="space-y-2 text-sm">
          <div><span class="text-gray-600">Kurir</span><div class="font-medium">{{ $data->courier_name ?? '-' }}</div></div>
          <div><span class="text-gray-600">Tracking</span><div class="font-mono">{{ $data->tracking_number ?? '-' }}</div></div>
          <div><span class="text-gray-600">Status</span><div>{{ $data->shipment_status ?? '-' }}</div></div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mt-6">
      <h3 class="font-semibold mb-4">Item Pesanan</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left px-4 py-2">Produk</th>
              <th class="text-center px-4 py-2">Jumlah</th>
              <th class="text-right px-4 py-2">Harga Satuan</th>
              <th class="text-right px-4 py-2">Total</th>
            </tr>
          </thead>
          <tbody class="divide-y">
          @foreach(($data->items ?? []) as $it)
          <tr>
            <td class="px-4 py-2">
              <div class="font-medium">
                {{ $it['resolved_variety_name'] ?? 'Varietas Tidak Diketahui' }}
              </div>
              <div class="text-xs text-gray-600">
                Kelas {{ $it['seed_class_code'] ?? '-' }}
                • Lot {{ $it['seed_lot_id'] ?? '-' }}
              </div>
            </td>

            <td class="text-center px-4 py-2">
              {{ (int)($it['quantity'] ?? 0) }} kg
            </td>

            <td class="text-right px-4 py-2">
              Rp {{ number_format((int)($it['unit_price'] ?? 0), 0, ',', '.') }}
            </td>

            <td class="text-right px-4 py-2 font-semibold">
              Rp {{ number_format(
                ((int)($it['unit_price'] ?? 0)) * ((int)($it['quantity'] ?? 0)),
                0, ',', '.'
              ) }}
            </td>
          </tr>
          @endforeach
          </tbody>

          <tfoot class="bg-gray-50">
            <tr>
              <th colspan="3" class="text-right px-4 py-2">Subtotal</th>
              <th class="text-right px-4 py-2">Rp {{ number_format((int)($data->subtotal ?? 0), 0, ',', '.') }}</th>
            </tr>
            <tr>
              <th colspan="3" class="text-right px-4 py-2">Total</th>
              <th class="text-right px-4 py-2">Rp {{ number_format((int)($data->total_amount ?? 0), 0, ',', '.') }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  @endif
</div>

@vite('resources/js/print.js')
@endsection

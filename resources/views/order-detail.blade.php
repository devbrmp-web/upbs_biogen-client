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
        'pending_verification' => 'bg-orange-100 text-orange-700',
        'processing' => 'bg-blue-100 text-blue-700',
        'delivery_coordination' => 'bg-blue-100 text-blue-700',
        'shipped' => 'bg-blue-100 text-blue-700',
        'pickup_ready' => 'bg-blue-100 text-blue-700'
      ];
      $statusClass = $statusMap[$data->status ?? ''] ?? 'bg-gray-100 text-gray-800';
    @endphp

    <div class="bg-white p-6 rounded-xl shadow relative z-10">
      <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
          <h2 class="text-xl font-semibold">Order #{{ $data->order_code ?? '-' }}</h2>
          <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm {{ $statusClass }}">{{ $data->status ?? '-' }}</div>
        </div>
        <div class="flex gap-2 relative z-20">
          <a href="/cek-pesanan" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">Kembali</a>
          @if(($data->status ?? '') === 'awaiting_payment')
            <a href="/pesanan/{{ $data->order_code }}/payment" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">Bayar</a>
          @endif
          <button id="btn-print" class="px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition-colors cursor-pointer" data-order-code="{{ $data->order_code }}" data-order-status="{{ $data->status }}">Cetak</button>
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
        @if(($data->shipping_method ?? '') === 'pickup')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                <p class="font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Ambil di Tempat (Pickup)
                </p>
                <div class="mt-2 text-sm text-blue-900">
                    <p class="font-medium">Lokasi Pengambilan:</p>
                    <p>Kantor UPBS BRMP Biogen</p>
                    <p>Jl. Tentara Pelajar No. 3A, Bogor</p>
                    <p class="mt-1 text-xs opacity-75">Tunjukkan kode order saat pengambilan.</p>
                </div>
            </div>
        @else
            <div class="space-y-2 text-sm">
              <div><span class="text-gray-600">Kurir</span><div class="font-medium">{{ $data->courier_name ?? '-' }}</div></div>
              <div><span class="text-gray-600">Layanan</span><div class="font-medium">{{ $data->courier_service ?? '-' }}</div></div>
              <div><span class="text-gray-600">Tracking</span><div class="font-mono">{{ $data->tracking_number ?? '-' }}</div></div>
              <div><span class="text-gray-600">Status</span><div>{{ $data->shipment_status ?? '-' }}</div></div>
            </div>
        @endif
      </div>
    </div>

    {{-- Dynamic Status Information Card --}}
    <div class="bg-white rounded-xl shadow p-6 mt-6">
      <h3 class="font-semibold mb-4">Informasi Status Pesanan</h3>
      
      @if(($data->shipping_method ?? '') === 'delivery')
        {{-- DELIVERY METHOD --}}
        @if(($data->status ?? '') === 'paid')
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-green-800 mb-2">Pembayaran Berhasil</h4>
                <p class="text-green-700 text-sm mb-3">
                  Pembayaran berhasil. Silakan cetak resi/kuitansi Anda dan kirimkan ke Admin melalui WhatsApp untuk koordinasi pengiriman.
                </p>
                <div class="flex gap-2">
                  <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    📄 Cetak Resi
                  </a>
                  <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya telah menyelesaikan pembayaran untuk pesanan #'.$data->order_code.'. Berikut saya lampirkan bukti resi untuk koordinasi pengiriman lebih lanjut. Terima kasih!') }}" 
                     target="_blank" 
                     class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                    </svg>
                    WhatsApp Admin
                  </a>
                </div>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'processing')
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-blue-800 mb-2">Sedang Disiapkan</h4>
                <p class="text-blue-700 text-sm mb-3">
                  Pesanan Anda sedang disiapkan oleh staf UPBS. Mohon tunggu informasi selanjutnya.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan progres persiapan pesanan #'.$data->order_code.'. Terima kasih!') }}" 
                   target="_blank" 
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                  </svg>
                  WhatsApp Admin
                </a>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'shipped')
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-blue-800 mb-2">Dalam Pengiriman</h4>
                <p class="text-blue-700 text-sm mb-3">
                  Pesanan Anda sedang dalam perjalanan. Silakan hubungi WhatsApp berikut untuk informasi lebih lanjut.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan status pengiriman pesanan #'.$data->order_code.'. Terima kasih!') }}" 
                   target="_blank" 
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                  </svg>
                  WhatsApp Admin
                </a>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'completed')
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-green-800 mb-2">Pesanan Siap Dikirim</h4>
                <p class="text-green-700 text-sm mb-3">
                  Pesanan siap dikirim. Silakan hubungi WhatsApp berikut untuk meminta nomor resi pengiriman dari ekspedisi.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, pesanan #'.$data->order_code.' saya sudah siap dikirim. Saya ingin meminta nomor resi pengiriman dari ekspedisi. Terima kasih!') }}" 
                   target="_blank" 
                   class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                  </svg>
                  WhatsApp Admin
                </a>
              </div>
            </div>
          </div>
          
        @endif
        
      @elseif(($data->shipping_method ?? '') === 'pickup')
        {{-- PICKUP METHOD --}}
        @if(($data->status ?? '') === 'paid')
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-green-800 mb-2">Pembayaran Berhasil</h4>
                <p class="text-green-700 text-sm mb-3">
                  Pembayaran berhasil. Silakan cetak kuitansi Anda dan bawa saat pengambilan benih di kantor UPBS BRMP Biogen.
                </p>
                <div class="flex gap-2">
                  <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    📄 Cetak Kuitansi
                  </a>
                  <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya telah menyelesaikan pembayaran untuk pesanan #'.$data->order_code.'. Saya ingin mengkoordinasikan waktu pengambilan benih. Terima kasih!') }}" 
                     target="_blank" 
                     class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                    </svg>
                    WhatsApp Admin
                  </a>
                </div>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'processing')
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-blue-800 mb-2">Sedang Dalam Proses</h4>
                <p class="text-blue-700 text-sm mb-3">
                  Benih Anda sedang dalam proses pengemasan/penyiapan oleh tim gudang kami.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan progres penyiapan pesanan #'.$data->order_code.'. Terima kasih!') }}" 
                   target="_blank" 
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                  </svg>
                  WhatsApp Admin
                </a>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'pickup_ready')
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-green-800 mb-2">Siap Diambil</h4>
                <p class="text-green-700 text-sm mb-3">
                  Pesanan Anda sudah siap diambil. Silakan datang ke kantor UPBS BRMP Biogen pada jam operasional dengan menunjukkan bukti kuitansi.
                </p>
                <div class="flex gap-2">
                  <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    📄 Lihat Kuitansi
                  </a>
                  <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin konfirmasi kedatangan untuk pengambilan pesanan #'.$data->order_code.'. Terima kasih!') }}" 
                     target="_blank" 
                     class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767 1.157-1.05 1.388-.283.232-.567.174-.86-.066-.297-.24-1.247-.918-2.37-1.904-1.123-.985-1.878-2.19-2.095-2.567-.217-.377-.02-.58.13-.768.11-.13.297-.347.446-.52.15-.174.248-.297.297-.471.05-.174.025-.323-.05-.471-.075-.148-.67-1.61-.917-2.206-.242-.58-.487-.5-.67-.51-.18-.01-.388-.02-.597-.02-.21 0-.45.02-.67.06-.22.04-.43.1-.597.21-.17.11-.32.28-.45.52-.13.24-.5 1.02-.5 1.02s.13.28.32.67c.19.39.42.86.42.86s-.1.15-.25.37c-.15.22-.28.4-.35.52-.07.12-.18.3-.35.58-.17.28-.02.53.13.72.15.19 1.4 1.63 1.4 1.63s.32.42.67.67c.35.25.86.58 1.37.91.51.33 1.02.65 1.02.65s.28.1.67.25c.39.15.86.32 1.37.5.51.18 1.02.35 1.02.35s.42.08.86.17c.44.09.91.17 1.37.25.46.08.92.15 1.37.21.45.06.88.11 1.27.15.39.04.72.06.97.08.25.02.42.03.42.03l.11-.03c.07-.01.16-.03.27-.06.11-.03.24-.07.39-.13.15-.06.32-.14.51-.25.19-.11.4-.25.63-.42.23-.17.48-.37.75-.6.27-.23.56-.5.87-.81.31-.31.63-.67.97-1.06.34-.39.69-.82 1.06-1.28.37-.46.75-.96 1.14-1.5.39-.54.79-1.12 1.2-1.74.41-.62.82-1.28 1.23-1.97.41-.69.81-1.42 1.19-2.18.38-.76.74-1.56 1.07-2.4.33-.84.63-1.72.88-2.64.25-.92.45-1.88.58-2.88.13-.99.19-2.02.19-3.08 0-1.06-.06-2.09-.19-3.08-.13-1-.33-1.96-.58-2.88-.25-.92-.55-1.8-.88-2.64-.33-.84-.69-1.64-1.07-2.4-.38-.76-.78-1.49-1.19-2.18-.41-.69-.82-1.35-1.23-1.97-.41-.62-.81-1.2-1.2-1.74-.39-.54-.77-1.04-1.14-1.5-.37-.46-.72-.89-1.06-1.28-.34-.39-.66-.75-.97-1.06-.31-.31-.6-.58-.87-.81-.27-.23-.52-.43-.75-.6-.23-.17-.44-.31-.63-.42-.19-.11-.36-.19-.51-.25-.15-.06-.28-.1-.39-.13-.11-.03-.2-.05-.27-.06l-.42-.03z"/>
                    </svg>
                    WhatsApp Admin
                  </a>
                </div>
              </div>
            </div>
          </div>
          
        @endif
        
      @else
        {{-- DEFAULT MESSAGE FOR OTHER STATUSES --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
          <p class="text-gray-600 text-sm">
            Status pesanan: <span class="font-medium">{{ $data->status ?? '-' }}</span>
          </p>
        </div>
      @endif
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
@extends('layouts.app')

@section('title', 'Detail Pesanan • UPBS BRMP Biogen')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 mt-20 page-animate-rise relative z-10">

    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-96 bg-emerald-400/20 rounded-full blur-3xl -z-10 opacity-40 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl -z-10 opacity-30 pointer-events-none"></div>

  @if(empty($data))
    <div class="bg-white/60 backdrop-blur-xl border border-red-200/50 shadow-xl rounded-3xl p-10 text-center max-w-2xl mx-auto">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-3xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Pesanan Tidak Ditemukan</h3>
        <p class="text-slate-600 mb-8">Maaf, data pesanan yang Anda cari tidak tersedia. Silakan periksa kembali kode pesanan Anda.</p>
        <a href="/cek-pesanan" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Pencarian
        </a>
    </div>
  @else
    @php
      $statusMap = [
        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'awaiting_payment' => 'bg-amber-100 text-amber-700 border-amber-200',
        'pending_verification' => 'bg-orange-100 text-orange-700 border-orange-200',
        'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
        'delivery_coordination' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
        'shipped' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
        'pickup_ready' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
      ];
      $statusClass = $statusMap[$data->status ?? ''] ?? 'bg-slate-100 text-slate-800 border-slate-200';
    @endphp

    {{-- Header Section --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 relative z-10 mb-8">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <div class="flex items-center gap-4 mb-2">
              <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Order #{{ $data->order_code ?? '-' }}</h2>
              <span class="px-4 py-1.5 rounded-full text-sm font-bold border {{ $statusClass }} uppercase tracking-wide shadow-sm">
                {{ str_replace('_', ' ', $data->status ?? '-') }}
              </span>
          </div>
          <p class="text-slate-500 font-medium flex items-center gap-2">
            <i class="fa-regular fa-clock"></i>
            Dibuat pada {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i') : '-' }} WIB
          </p>
        </div>

        <div class="flex flex-wrap gap-3 w-full md:w-auto">
          <a href="/cek-pesanan" 
             class="flex-1 md:flex-none justify-center px-5 py-3 rounded-xl border border-slate-200 bg-white/60 hover:bg-white text-slate-600 font-bold transition-all shadow-sm hover:shadow-md flex items-center gap-2 group">
             <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i> Kembali
          </a>
          
          @if(($data->status ?? '') === 'awaiting_payment')
            <a href="/pesanan/{{ $data->order_code }}/payment" 
               class="flex-1 md:flex-none justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold shadow-lg shadow-blue-600/20 transition-all transform hover:-translate-y-1 hover:shadow-xl flex items-center gap-2">
               <i class="fa-regular fa-credit-card"></i> Bayar Sekarang
            </a>
          @endif
          
          <button id="btn-print" 
                  class="flex-1 md:flex-none justify-center px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold shadow-lg transition-all transform hover:-translate-y-1 hover:shadow-xl flex items-center gap-2" 
                  data-order-code="{{ $data->order_code }}" 
                  data-order-status="{{ $data->status }}">
              <i class="fa-solid fa-print"></i> Cetak
          </button>
        </div>
      </div>
    </div>

    {{-- Item Pesanan --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 mb-8 relative z-10">
        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-3 border-b border-slate-200/60 pb-4 text-lg">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
            Item Pesanan
        </h3>
        
        <div class="space-y-4">
            @if(!empty($data->items) && is_array($data->items))
                @foreach($data->items as $item)
                <div class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-white/50 rounded-2xl border border-white/60 hover:bg-white/80 transition-colors">
                    {{-- Product Icon/Image Placeholder --}}
                    <div class="w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="fa-solid fa-seedling text-2xl"></i>
                    </div>
                    
                    {{-- Item Details --}}
                    <div class="flex-1 text-center sm:text-left">
                        <h4 class="font-bold text-slate-800 text-lg">{{ $item['resolved_variety_name'] ?? 'Varietas Tidak Diketahui' }}</h4>
                        <p class="text-sm text-slate-500">{{ $item['product_name'] ?? 'Benih' }}</p>
                    </div>

                    {{-- Quantity & Price --}}
                    <div class="flex flex-col items-center sm:items-end gap-1">
                        <span class="text-slate-600 font-medium bg-slate-100 px-3 py-1 rounded-lg text-sm">
                            {{ $item['quantity'] }} x Rp {{ number_format((int)($item['price'] ?? 0), 0, ',', '.') }}
                        </span>
                        <span class="font-bold text-emerald-600 text-lg">
                            Rp {{ number_format((int)($item['subtotal'] ?? 0), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 text-slate-500">
                    <i class="fa-regular fa-folder-open text-4xl mb-3 opacity-50"></i>
                    <p>Data item tidak tersedia.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Grid Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      {{-- Informasi Pengiriman (Combined with Recipient Name) --}}
      <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-300">
        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-3 border-b border-slate-200/60 pb-4 text-lg">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            Informasi Pengiriman
        </h3>

        {{-- Recipient Name (Moved from Data Pelanggan) --}}
        <div class="mb-6 pb-6 border-b border-dashed border-slate-200">
            <div class="group">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1.5 ml-1">Nama Penerima</span>
                <div class="font-semibold text-slate-800 bg-white/60 px-4 py-3 rounded-xl border border-white/60 shadow-sm flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    {{ $data->customer_name ?? '-' }}
                </div>
            </div>
        </div>

        @if(($data->shipping_method ?? '') === 'pickup')
            <div class="bg-blue-50/80 border border-blue-200 rounded-2xl p-5 shadow-sm">
                <p class="font-bold text-blue-800 flex items-center gap-2 mb-3 text-lg">
                    <i class="fa-solid fa-store"></i>
                    Ambil di Tempat (Pickup)
                </p>
                <div class="text-sm text-blue-900 space-y-2 ml-7">
                    <p class="font-bold">Lokasi Pengambilan:</p>
                    <p>Kantor UPBS BRMP Biogen</p>
                    <p>Jl. Tentara Pelajar No. 3A, Bogor</p>
                    <p class="mt-3 text-xs opacity-90 bg-white/50 p-2.5 rounded-lg border border-blue-100 inline-block font-medium">
                        <i class="fa-solid fa-ticket mr-1"></i> Tunjukkan kode order saat pengambilan.
                    </p>
                </div>
            </div>
        @else
            <div class="space-y-5">
              <div class="group">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1.5 ml-1">Kurir</span>
                  <div class="font-semibold text-slate-800 bg-white/60 px-4 py-3 rounded-xl border border-white/60 shadow-sm">{{ $data->courier_name ?? '-' }}</div>
              </div>
              <div class="group">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1.5 ml-1">Layanan</span>
                  <div class="font-semibold text-slate-800 bg-white/60 px-4 py-3 rounded-xl border border-white/60 shadow-sm">{{ $data->courier_service ?? '-' }}</div>
              </div>
              <div class="group">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1.5 ml-1">Nomor Resi (Tracking)</span>
                  <div class="font-mono font-bold text-slate-800 bg-white/60 px-4 py-3 rounded-xl border border-white/60 shadow-sm flex justify-between items-center group-hover:border-indigo-200 transition-colors">
                      {{ $data->tracking_number ?? '-' }}
                      @if($data->tracking_number)
                        <button onclick="navigator.clipboard.writeText('{{ $data->tracking_number }}')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-400 hover:text-emerald-600 transition-all flex items-center justify-center" title="Salin Resi">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                      @endif
                  </div>
              </div>
              <div class="group">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1.5 ml-1">Status Pengiriman</span>
                  <div class="font-semibold text-slate-800 bg-white/60 px-4 py-3 rounded-xl border border-white/60 shadow-sm">{{ $data->shipment_status ?? '-' }}</div>
              </div>
            </div>
        @endif
      </div>

      {{-- Ringkasan Pembayaran --}}
      <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-300">
        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-3 border-b border-slate-200/60 pb-4 text-lg">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-receipt"></i>
            </div>
            Ringkasan Pembayaran
        </h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50/50 border border-slate-100">
              <span class="text-slate-600 font-medium">Subtotal</span>
              <span class="font-bold text-slate-800">Rp {{ number_format((int)($data->subtotal ?? 0), 0, ',', '.') }}</span>
          </div>
          
          <div class="border-t border-dashed border-slate-300 my-2"></div>
          
          <div class="flex justify-between items-center bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm">
              <span class="text-slate-700 font-bold">Total</span>
              <span class="font-extrabold text-emerald-700 text-xl">Rp {{ number_format((int)($data->total_amount ?? 0), 0, ',', '.') }}</span>
          </div>
          
          <div class="mt-4 flex items-start gap-3 text-xs text-slate-500 bg-white/60 p-4 rounded-xl border border-slate-100 leading-relaxed">
              <i class="fa-solid fa-circle-info mt-0.5 text-slate-400 text-sm"></i>
              <span>Status pembayaran akan diperbarui secara otomatis setelah verifikasi dari gateway pembayaran selesai.</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Dynamic Status Information Card --}}
    <div class="mt-10">
      
      @if(($data->shipping_method ?? '') === 'delivery')
        {{-- DELIVERY METHOD --}}
        @if(($data->status ?? '') === 'paid')
          <div class="bg-gradient-to-br from-green-50 to-emerald-50/50 backdrop-blur-md border border-green-200 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-start gap-6">
              <div class="w-16 h-16 rounded-2xl bg-white text-green-600 flex items-center justify-center shrink-0 shadow-md border border-green-100">
                  <i class="fa-solid fa-check text-2xl"></i>
              </div>
              <div class="flex-1">
                <h4 class="text-xl font-bold text-green-800 mb-3">Pembayaran Berhasil</h4>
                <p class="text-green-800/80 text-base mb-6 leading-relaxed max-w-3xl">
                  Terima kasih! Pembayaran Anda telah kami terima. Silakan cetak resi/kuitansi Anda dan kirimkan ke Admin melalui WhatsApp untuk mempercepat proses koordinasi pengiriman.
                </p>
                <div class="flex flex-wrap gap-4">
                  <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" 
                     class="px-6 py-3 bg-white text-green-700 border border-green-200 rounded-xl text-sm font-bold hover:bg-green-50 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak Resi
                  </a>
                  <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya telah menyelesaikan pembayaran untuk pesanan #'.$data->order_code.'. Berikut saya lampirkan bukti resi untuk koordinasi pengiriman lebih lanjut. Terima kasih!') }}" 
                     target="_blank" 
                     class="px-6 py-3 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition-all shadow-lg hover:shadow-xl shadow-green-600/20 flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp Admin
                  </a>
                </div>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'processing')
          <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 backdrop-blur-md border border-blue-200 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-start gap-6">
              <div class="w-16 h-16 rounded-2xl bg-white text-blue-600 flex items-center justify-center shrink-0 shadow-md border border-blue-100">
                  <i class="fa-solid fa-box-open text-2xl"></i>
              </div>
              <div class="flex-1">
                <h4 class="text-xl font-bold text-blue-800 mb-3">Pesanan Sedang Disiapkan</h4>
                <p class="text-blue-800/80 text-base mb-6 leading-relaxed max-w-3xl">
                  Pesanan Anda sedang dalam proses penyiapan oleh tim UPBS. Kami memastikan kualitas terbaik untuk Anda. Mohon tunggu informasi selanjutnya.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan progres persiapan pesanan #'.$data->order_code.'. Terima kasih!') }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl shadow-blue-600/20">
                  <i class="fa-brands fa-whatsapp text-lg"></i> Tanya Progres via WA
                </a>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'shipped')
          <div class="bg-gradient-to-br from-indigo-50 to-purple-50/50 backdrop-blur-md border border-indigo-200 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-start gap-6">
              <div class="w-16 h-16 rounded-2xl bg-white text-indigo-600 flex items-center justify-center shrink-0 shadow-md border border-indigo-100">
                  <i class="fa-solid fa-truck-fast text-2xl"></i>
              </div>
              <div class="flex-1">
                <h4 class="text-xl font-bold text-indigo-800 mb-3">Pesanan Dalam Pengiriman</h4>
                <p class="text-indigo-800/80 text-base mb-6 leading-relaxed max-w-3xl">
                  Kabar baik! Pesanan Anda sedang dalam perjalanan menuju alamat tujuan. Anda dapat melacak posisi paket menggunakan nomor resi yang tersedia.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan status pengiriman pesanan #'.$data->order_code.'. Terima kasih!') }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl shadow-indigo-600/20">
                  <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi Admin
                </a>
              </div>
            </div>
          </div>
          
        @elseif(($data->status ?? '') === 'completed')
          <div class="bg-gradient-to-br from-emerald-50 to-teal-50/50 backdrop-blur-md border border-emerald-200 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-start gap-6">
              <div class="w-16 h-16 rounded-2xl bg-white text-emerald-600 flex items-center justify-center shrink-0 shadow-md border border-emerald-100">
                  <i class="fa-solid fa-check-double text-2xl"></i>
              </div>
              <div class="flex-1">
                <h4 class="text-xl font-bold text-emerald-800 mb-3">Pesanan Selesai</h4>
                <p class="text-emerald-800/80 text-base mb-6 leading-relaxed max-w-3xl">
                  Pesanan telah selesai. Terima kasih telah berbelanja di UPBS BRMP Biogen. Jika Anda membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami.
                </p>
                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, pesanan #'.$data->order_code.' saya sudah selesai. Terima kasih!') }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-lg hover:shadow-xl shadow-emerald-600/20">
                  <i class="fa-brands fa-whatsapp text-lg"></i> Kirim Testimoni
                </a>
              </div>
            </div>
          </div>
          
        @endif
        
      @elseif(($data->shipping_method ?? '') === 'pickup')
        {{-- PICKUP METHOD --}}
        @if(($data->status ?? '') === 'paid')
          <div class="bg-gradient-to-br from-green-50 to-emerald-50/50 backdrop-blur-md border border-green-200 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-start gap-6">
              <div class="w-16 h-16 rounded-2xl bg-white text-green-600 flex items-center justify-center shrink-0 shadow-md border border-green-100">
                  <i class="fa-solid fa-store text-2xl"></i>
              </div>
              <div class="flex-1">
                 <h4 class="text-xl font-bold text-green-800 mb-3">Siap untuk Pengambilan</h4>
                 <p class="text-green-800/80 text-base mb-6 leading-relaxed max-w-3xl">
                    Pembayaran diterima. Silakan datang ke kantor UPBS BRMP Biogen untuk mengambil pesanan Anda. Jangan lupa membawa bukti pesanan ini.
                 </p>
                 <a href="https://maps.google.com/?q=Jl.+Tentara+Pelajar+No.+3A,+Bogor" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition-all shadow-lg hover:shadow-xl shadow-green-600/20">
                    <i class="fa-solid fa-map-location-dot"></i> Lihat Lokasi
                 </a>
              </div>
            </div>
          </div>
        @endif
      @endif

    </div>

  @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnPrint = document.getElementById('btn-print');
        if (btnPrint) {
            btnPrint.addEventListener('click', function() {
                const orderCode = this.getAttribute('data-order-code');
                if (orderCode) {
                    // Gunakan route yang sesuai dengan web.php: /pesanan/{code}/receipt
                    const url = `/pesanan/${orderCode}/receipt`;
                    window.open(url, '_blank');
                }
            });
        }
    });
</script>
@endpush
@endsection
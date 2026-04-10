@extends('layouts.app')

@section('title', 'Detail Pesanan • UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-screen pt-24 pb-12">
    {{-- Background Blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-300/20 rounded-full blur-3xl mix-blend-multiply animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-300/20 rounded-full blur-3xl mix-blend-multiply animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-yellow-100/40 rounded-full blur-3xl mix-blend-multiply animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(empty($data))
            <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-12 text-center shadow-xl">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Pesanan Tidak Ditemukan</h3>
                <p class="text-slate-600 mb-6">Maaf, kami tidak dapat menemukan data pesanan yang Anda cari.</p>
                <a href="/cek-pesanan" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-emerald-600/20">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        @else
            @php
                $statusMap = [
                    'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'icon' => 'fa-check-circle'],
                    'paid' => ['label' => 'Sudah Dibayar', 'class' => 'bg-blue-100 text-blue-700 border-blue-200', 'icon' => 'fa-wallet'],
                    'awaiting_payment' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-100 text-amber-700 border-amber-200', 'icon' => 'fa-clock'],
                    'pending_verification' => ['label' => 'Verifikasi Pembayaran', 'class' => 'bg-orange-100 text-orange-700 border-orange-200', 'icon' => 'fa-magnifying-glass-dollar'],
                    'processing' => ['label' => 'Sedang Diproses', 'class' => 'bg-indigo-100 text-indigo-700 border-indigo-200', 'icon' => 'fa-box-open'],
                    'delivery_coordination' => ['label' => 'Koordinasi Pengiriman', 'class' => 'bg-cyan-100 text-cyan-700 border-cyan-200', 'icon' => 'fa-truck-fast'],
                    'shipped' => ['label' => 'Dalam Pengiriman', 'class' => 'bg-sky-100 text-sky-700 border-sky-200', 'icon' => 'fa-truck'],
                    'pickup_ready' => ['label' => 'Siap Diambil', 'class' => 'bg-teal-100 text-teal-700 border-teal-200', 'icon' => 'fa-store']
                ];
                
                $statusKey = $data->status ?? '';
                $statusInfo = $statusMap[$statusKey] ?? ['label' => $statusKey, 'class' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => 'fa-circle-info'];
            @endphp

            {{-- Header Section --}}
            <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6 lg:p-8 shadow-xl mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                Order ID
                            </span>
                            @if($data->paid_at ?? $data->settlement_time ?? $data->created_at ?? null)
                            <span class="text-slate-400 text-sm"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($data->paid_at ?? $data->settlement_time ?? $data->created_at)->setTimezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') }} WIB</span>
                            @endif
                        </div>
                        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">#{{ $data->order_code ?? '-' }}</h1>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border {{ $statusInfo['class'] }} text-sm font-medium">
                                <i class="fa-solid {{ $statusInfo['icon'] }}"></i>
                                {{ $statusInfo['label'] }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="/cek-pesanan" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800 transition-all">
                            Kembali
                        </a>
                        
                        @if(($data->status ?? '') === 'awaiting_payment')
                            <a href="/pesanan/{{ $data->order_code }}/payment" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                                <i class="fa-regular fa-credit-card"></i> Bayar Sekarang
                            </a>
                        @endif

                        <button id="btn-print" data-order-code="{{ $data->order_code }}" data-order-status="{{ $data->status }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-medium transition-all shadow-lg shadow-slate-800/20 flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-print"></i> Cetak Invoice
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Order Details --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Status Information Card (Dynamic) --}}
                    @if(in_array(($data->status ?? ''), ['paid', 'processing', 'shipped', 'pickup_ready', 'completed']))
                        <div class="bg-gradient-to-br from-white/90 to-white/70 backdrop-blur-xl border border-white/40 rounded-3xl p-6 lg:p-8 shadow-lg relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/50 rounded-full blur-2xl -mr-16 -mt-16 transition-all group-hover:bg-emerald-200/50"></div>
                            
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-emerald-600"></i> Informasi Status
                            </h3>

                            <div class="bg-slate-50/80 border border-slate-100 rounded-2xl p-5">
                                @if(($data->shipping_method ?? '') === 'delivery')
                                    @if(($data->status ?? '') === 'paid')
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                                <i class="fa-solid fa-check text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800">Pembayaran Berhasil</h4>
                                                <p class="text-slate-600 text-sm mt-1 mb-4">Pembayaran berhasil. Silakan cetak resi/kuitansi Anda dan kirimkan ke Admin melalui WhatsApp untuk koordinasi pengiriman.</p>
                                                <div class="flex flex-wrap gap-3">
                                                    <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all">
                                                        <i class="fa-regular fa-file-pdf text-red-500"></i> Cetak Resi
                                                    </a>
                                                    <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya telah menyelesaikan pembayaran untuk pesanan #'.$data->order_code.'. Berikut saya lampirkan bukti resi untuk koordinasi pengiriman lebih lanjut. Terima kasih!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition-all shadow-sm">
                                                        <i class="fa-brands fa-whatsapp"></i> WhatsApp Admin
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(($data->status ?? '') === 'processing')
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 text-indigo-600">
                                                <i class="fa-solid fa-box-open text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800">Sedang Disiapkan</h4>
                                                <p class="text-slate-600 text-sm mt-1 mb-4">Pesanan Anda sedang disiapkan oleh staf UPBS. Mohon tunggu informasi selanjutnya.</p>
                                                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan progres persiapan pesanan #'.$data->order_code.'. Terima kasih!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm font-medium hover:bg-indigo-600 transition-all shadow-sm">
                                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp Admin
                                                </a>
                                            </div>
                                        </div>
                                    @elseif(($data->status ?? '') === 'shipped')
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0 text-sky-600">
                                                <i class="fa-solid fa-truck text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800">Dalam Pengiriman</h4>
                                                <p class="text-slate-600 text-sm mt-1 mb-4">Pesanan Anda sedang dalam perjalanan. Silakan hubungi WhatsApp berikut untuk informasi lebih lanjut.</p>
                                                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin menanyakan status pengiriman pesanan #'.$data->order_code.'. Terima kasih!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg text-sm font-medium hover:bg-sky-600 transition-all shadow-sm">
                                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp Admin
                                                </a>
                                            </div>
                                        </div>
                                    @elseif(($data->status ?? '') === 'completed')
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                                <i class="fa-solid fa-flag-checkered text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800">Pesanan Siap Dikirim</h4>
                                                <p class="text-slate-600 text-sm mt-1 mb-4">Pesanan siap dikirim. Silakan hubungi WhatsApp berikut untuk meminta nomor resi pengiriman dari ekspedisi.</p>
                                                <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, pesanan #'.$data->order_code.' saya sudah siap dikirim. Saya ingin meminta nomor resi pengiriman dari ekspedisi. Terima kasih!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition-all shadow-sm">
                                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp Admin
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @elseif(($data->shipping_method ?? '') === 'pickup')
                                    @if(($data->status ?? '') === 'paid')
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                                <i class="fa-solid fa-check text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800">Pembayaran Berhasil</h4>
                                                <p class="text-slate-600 text-sm mt-1 mb-4">Pembayaran berhasil. Silakan cetak kuitansi Anda dan bawa saat pengambilan benih di kantor UPBS BRMP Biogen.</p>
                                                <div class="flex flex-wrap gap-3">
                                                    <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all">
                                                        <i class="fa-regular fa-file-pdf text-red-500"></i> Cetak Kuitansi
                                                    </a>
                                                    <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya telah menyelesaikan pembayaran untuk pesanan #'.$data->order_code.'. Saya ingin mengkoordinasikan waktu pengambilan benih. Terima kasih!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition-all shadow-sm">
                                                        <i class="fa-brands fa-whatsapp"></i> WhatsApp Admin
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(($data->status ?? '') === 'pickup_ready')
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0 text-teal-600">
                                                <i class="fa-solid fa-store text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800">Siap Diambil</h4>
                                                <p class="text-slate-600 text-sm mt-1 mb-4">Pesanan Anda sudah siap diambil. Silakan datang ke kantor UPBS BRMP Biogen pada jam operasional dengan menunjukkan bukti kuitansi.</p>
                                                <div class="flex flex-wrap gap-3">
                                                    <a href="{{ route('order.print', ['order_code' => $data->order_code]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all">
                                                        <i class="fa-regular fa-file-pdf text-red-500"></i> Lihat Kuitansi
                                                    </a>
                                                    <a href="https://wa.me/6285155238654?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin konfirmasi kedatangan untuk pengambilan pesanan #'.$data->order_code.'. Terima kasih!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-all shadow-sm">
                                                        <i class="fa-brands fa-whatsapp"></i> WhatsApp Admin
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Items List (Card Style) --}}
                    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6 lg:p-8 shadow-xl">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-basket-shopping text-emerald-600"></i> Rincian Produk
                        </h3>
                        <div class="space-y-4">
                            @foreach(($data->items ?? []) as $it)
                            <div class="bg-white border border-slate-100 rounded-2xl p-5 hover:shadow-md transition-all group">
                                <div class="flex flex-col sm:flex-row gap-4 justify-between sm:items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                                <i class="fa-solid fa-seedling text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-lg">{{ $it['resolved_variety_name'] ?? 'Varietas Tidak Diketahui' }}</h4>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2 text-xs text-slate-500 pl-[3.25rem]">
                                            <span class="px-2 py-1 rounded-md bg-slate-50 border border-slate-100">
                                                <i class="fa-solid fa-tag text-slate-300 mr-1"></i> Kelas {{ $it['seed_class_code'] ?? '-' }}
                                            </span>
                                            <span class="px-2 py-1 rounded-md bg-slate-50 border border-slate-100">
                                                <i class="fa-solid fa-layer-group text-slate-300 mr-1"></i> Lot {{ $it['seed_lot_id'] ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-6 pl-[3.25rem] sm:pl-0">
                                        <div class="text-center">
                                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Jumlah</p>
                                            <p class="font-bold text-slate-700">{{ (int)($it['quantity'] ?? 0) }} kg</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Total</p>
                                            <p class="font-bold text-emerald-600 text-lg">Rp {{ number_format(((int)($it['unit_price'] ?? 0)) * ((int)($it['quantity'] ?? 0)), 0, ',', '.') }}</p>
                                            <p class="text-xs text-slate-400">@ Rp {{ number_format((int)($it['unit_price'] ?? 0), 0, ',', '.') }} / kg</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Customer & Shipping Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Customer Data --}}
                        <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6 lg:p-8 shadow-xl">
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <i class="fa-regular fa-id-card text-emerald-600"></i> Data Pemesan
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Nama Penerima</p>
                                    <p class="font-medium text-slate-700">{{ $data->buyer_name ?? $data->customer_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Nomor Telepon</p>
                                    <p class="font-medium text-slate-700">{{ $data->buyer_phone ?? $data->customer_phone ?? '-' }}</p>
                                </div>
                                @if(($data->shipping_method ?? '') !== 'pickup')
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Alamat Pengiriman</p>
                                    <p class="font-medium text-slate-700 leading-relaxed">{{ $data->buyer_address ?? $data->customer_address ?? '-' }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Shipping Info --}}
                        <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6 lg:p-8 shadow-xl">
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-truck-fast text-emerald-600"></i> Pengiriman
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Metode</p>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-sm font-medium">
                                        @if(($data->shipping_method ?? '') === 'pickup')
                                            <i class="fa-solid fa-store"></i> Ambil Sendiri (Pickup)
                                        @else
                                            <i class="fa-solid fa-truck"></i> Jasa Ekspedisi
                                        @endif
                                    </div>
                                </div>
                                
                                @if(($data->shipping_method ?? '') !== 'pickup')
                                    <div>
                                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Kurir & Layanan</p>
                                        <p class="font-medium text-slate-700 uppercase">
                                            {{ $data->shipping_courier ?? '-' }} <span class="text-slate-400 mx-1">•</span> {{ $data->shipping_service ?? '-' }}
                                        </p>
                                    </div>
                                    @if(!empty($data->shipping_tracking_number))
                                    <div>
                                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Nomor Resi</p>
                                        <div class="flex items-center gap-2">
                                            <p class="font-mono font-medium text-slate-700 bg-slate-50 px-2 py-1 rounded border border-slate-100 select-all">
                                                {{ $data->shipping_tracking_number }}
                                            </p>
                                            <button onclick="navigator.clipboard.writeText('{{ $data->shipping_tracking_number }}')" class="text-slate-400 hover:text-emerald-600 transition-colors" title="Salin Resi">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div>
                                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Lokasi Pengambilan</p>
                                        <p class="font-medium text-slate-700 leading-relaxed">
                                            Kantor UPBS BRMP Biogen<br>
                                            <span class="text-sm text-slate-500 font-normal">Jl. Tentara Pelajar No.3A, Menteng, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16111</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Summary --}}
                <div class="space-y-8 sticky top-24">

                    {{-- Payment Summary --}}
                    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-emerald-600"></i> Ringkasan
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal Produk</span>
                                <span class="font-medium">Rp {{ number_format((int)($data->subtotal ?? 0), 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- Additional Fees --}}
                            @php
                                $subtotal = (int)($data->subtotal ?? 0);
                                $serviceFee = floor($subtotal * 0.01);
                                $appFee = 4000;
                                $shippingCost = (int)($data->shipping_cost ?? 0);
                                $finalTotal = $subtotal + $serviceFee + $appFee + $shippingCost;
                            @endphp

                            <div class="flex justify-between text-slate-600">
                                <span>Biaya Layanan (1%)</span>
                                <span class="font-medium">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Biaya Aplikasi</span>
                                <span class="font-medium">Rp {{ number_format($appFee, 0, ',', '.') }}</span>
                            </div>

                            @if($shippingCost > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                                <span class="font-bold text-slate-800 text-base">Total Bayar</span>
                                <span class="font-bold text-emerald-600 text-xl">Rp {{ number_format($finalTotal, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-2 text-center bg-slate-50 py-2 rounded-lg">
                                <i class="fa-solid fa-shield-halved mr-1"></i> Transaksi Aman & Terverifikasi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@vite('resources/js/print.js')
@endsection

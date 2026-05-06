@extends('layouts.app')

@section('content')

<div class="page-animate-zoomIn relative min-h-screen">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-400/5 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    
    {{-- Leaf SVG Pattern --}}
    <div class="hidden md:block absolute top-1/4 -left-20 w-80 h-80 opacity-[0.03] pointer-events-none -rotate-12">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#059669" d="M65.7,-21.2C71.8,-3.8,54.1,19.3,32.4,32.3C10.7,45.3,-15,48.1,-33.5,36.5C-51.9,24.8,-63.1,-1.2,-56.3,-19.8C-49.5,-38.4,-24.8,-49.5,1.1,-49.9C27,-50.2,59.6,-38.7,65.7,-21.2Z" transform="translate(100 100)" />
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 pt-20 md:pt-28 pb-32 page-animate-rise relative z-10">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-slate-500">
            <a href="/katalog" class="hover:text-emerald-600 transition-colors">Katalog</a>
            <span class="mx-2">/</span>
            <span class="text-emerald-700 font-medium">{{ $variety['name'] }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- Left Column: Image Track -->
            <div class="lg:col-span-5 relative">
                <div class="lg:sticky lg:top-28 z-30 bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/40 transition-all duration-500" data-aos="fade-right">
                    <div class="bg-slate-50/50 flex flex-col items-center justify-center p-6 border-b border-slate-100">

        @php
            $imagePath = $variety['image_path'] ?? null;
            $adminUrl = rtrim(config('app.url_dev_admin'), '/');

            if ($imagePath) {
                // Hapus prefix jika ada, lalu bangun URL lengkap
                $cleanPath = str_replace(['public/', 'storage/'], '', $imagePath);
                $imageUrl = $adminUrl . '/storage/' . ltrim($cleanPath, '/');
            } else {
                $imageUrl = 'https://placehold.co/400x300?text=No+Image';
            }
        @endphp

        <!-- Image Section -->

            
            <!-- Swiper CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

            <!-- Main Swiper -->
            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper main-swiper w-full aspect-[4/3] rounded-lg border border-gray-100 mb-4 bg-white">
                <div class="swiper-wrapper">
                    @php
                        $images = collect($variety['images'] ?? []);
                        if ($images->isEmpty()) {
                            $images = collect([['image_url' => $imageUrl, 'is_primary' => true]]);
                        }
                        $images = $images->sortBy(function ($img) {
                            $p = !empty($img['is_primary']) ? 0 : 1;
                            $id = (int) ($img['id'] ?? 0);
                            return ($p * 1000000) + $id;
                        });
                        $primaryImageUrlNormalized = (function() use ($variety, $adminUrl, $imageUrl) {
                            $primary = collect($variety['images'] ?? [])->firstWhere('is_primary', true);
                            if ($primary && !empty($primary['image_url'])) {
                                $p = parse_url($primary['image_url'], PHP_URL_PATH);
                                $c = str_replace(['public/', 'storage/'], '', $p ?: '');
                                return $adminUrl . '/storage/' . ltrim($c, '/');
                            }
                            return $imageUrl;
                        })();
                    @endphp

                     @foreach($images as $img)
                         @php
                             $rawUrl = $img['image_url'] ?? null;
                             if ($rawUrl && (str_starts_with($rawUrl, 'http') || str_starts_with($rawUrl, 'https'))) {
                                 $finalUrl = $rawUrl;
                             } elseif ($rawUrl) {
                                 $pathOnly = parse_url($rawUrl, PHP_URL_PATH);
                                 $cleanP = str_replace(['public/', 'storage/'], '', $pathOnly ?: '');
                                 $finalUrl = $adminUrl . '/storage/' . ltrim($cleanP, '/');
                             } elseif (!empty($img['image_path'])) {
                                 $cleanPath = str_replace(['public/', 'storage/'], '', $img['image_path']);
                                 $finalUrl = $adminUrl . '/storage/' . ltrim($cleanPath, '/');
                             } else {
                                 $finalUrl = $imageUrl;
                             }
                         @endphp
                         <div class="swiper-slide bg-white flex items-center justify-center">
                             <img src="{{ $finalUrl }}" class="w-full h-full object-cover" loading="lazy" 
                                  onerror="this.onerror=null;this.src='https://placehold.co/800x600?text=Image+Not+Found';" />
                         </div>
                     @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <!-- Thumbs Swiper -->
            <div class="swiper thumbs-swiper w-full h-24 box-border py-2">
                <div class="swiper-wrapper">
                    @foreach($images as $img)
                         @php
                              // Same logic for thumbnails
                             $rawUrl = $img['image_url'] ?? null;
                             if ($rawUrl && (str_starts_with($rawUrl, 'http') || str_starts_with($rawUrl, 'https'))) {
                                 $finalUrl = $rawUrl;
                             } elseif ($rawUrl) {
                                 $pathOnly = parse_url($rawUrl, PHP_URL_PATH);
                                 $cleanP = str_replace(['public/', 'storage/'], '', $pathOnly ?: '');
                                 $finalUrl = $adminUrl . '/storage/' . ltrim($cleanP, '/');
                             } elseif (!empty($img['image_path'])) {
                                 $cleanPath = str_replace(['public/', 'storage/'], '', $img['image_path']);
                                 $finalUrl = $adminUrl . '/storage/' . ltrim($cleanPath, '/');
                             } else {
                                 $finalUrl = $imageUrl;
                             }
                         @endphp
                        <div class="swiper-slide w-20 h-20 rounded-md overflow-hidden border border-gray-200 cursor-pointer opacity-60 hover:opacity-100 transition-opacity relative">
                            <img src="{{ $finalUrl }}" class="w-full h-full object-cover" 
                                 onerror="this.onerror=null;this.src='https://placehold.co/160x160?text=Error';" />
                            
                        </div>
                     @endforeach
                </div>
            </div>

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

            <!-- Initialize Swiper -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var thumbsSwiper = new Swiper(".thumbs-swiper", {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                        breakpoints: {
                            640: { slidesPerView: 5 },
                            768: { slidesPerView: 6 }
                        }
                    });
                    var mainSwiper = new Swiper(".main-swiper", {
                        spaceBetween: 10,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        thumbs: {
                            swiper: thumbsSwiper,
                        },
                    });
                });
            </script>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Content -->
            <div class="lg:col-span-7" data-aos="fade-left">
                <div class="bg-white/40 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/40 p-6 md:p-10 flex flex-col glass-premium">

            <div class="mb-6">
                <span class="text-xs text-emerald-600 font-bold uppercase tracking-widest bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                    {{ $variety['commodity']['name'] }}
                </span>

                <h1 class="text-3xl font-extrabold text-slate-800 mt-3 tracking-tight">
                    {{ $variety['name'] }}
                </h1>
            </div>

                <!-- Price -->
                <div class="mt-2">
                    <div class="prose prose-emerald text-slate-600 mb-8 max-w-none leading-relaxed">
                        {!! nl2br(e($variety['description'])) !!}
                    </div>

                    <!-- Karakteristik Varietas -->
                    <div class="bg-white/50 backdrop-blur-md rounded-2xl shadow-md p-6 mb-8 border border-white/40 glass-premium">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Karakteristik Varietas</h3>
                        </div>

                        @php
                            $commodityName = strtolower($variety['commodity']['name'] ?? '');
                            $primaryTraitLabel = 'Karakteristik Utama';
                            if ($commodityName === 'padi') {
                                $primaryTraitLabel = 'Tekstur Nasi';
                            } elseif ($commodityName === 'kedelai') {
                                $primaryTraitLabel = 'Ukuran Biji';
                            }
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-6">
                            <div class="border-b border-gray-100 pb-3">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Asal / Silsilah</p>
                                <p class="text-gray-900 font-medium break-words">{{ $variety['origin'] ?? '-' }}</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Umur Panen</p>
                                <p class="text-gray-900 font-medium break-words">{{ $variety['planting_age'] ?? '-' }}</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Potensi Hasil</p>
                                <p class="text-gray-900 font-medium break-words">{{ $variety['yield_potential'] ?? '-' }}</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Rata-rata Hasil</p>
                                <p class="text-gray-900 font-medium break-words">{{ $variety['average_yield'] ?? '-' }}</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3 md:col-span-2">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">{{ $primaryTraitLabel }}</p>
                                <p class="text-gray-900 font-medium break-words">{{ $variety['primary_trait'] ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Info Pelepasan -->
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 bg-slate-50/50 rounded-xl p-4 border border-slate-100 mb-6">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Nomor SK Pelepasan</p>
                                <p class="font-semibold text-gray-900">{{ $variety['decree_number'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Tahun Pelepasan</p>
                                <p class="font-semibold text-gray-900">{{ $variety['decree_date'] ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Deskripsi & Ketahanan -->
                        <div class="space-y-4">
                            @if(!empty($variety['pest_resistance']))
                            <div class="bg-red-50/50 border border-red-100 rounded-xl p-4">
                                <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Ketahanan Hama
                                </p>
                                <p class="text-sm text-gray-800 whitespace-pre-line break-words leading-relaxed">{{ $variety['pest_resistance'] }}</p>
                            </div>
                            @endif

                            @if(!empty($variety['disease_resistance']))
                            <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4">
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Ketahanan Penyakit
                                </p>
                                <p class="text-sm text-gray-800 whitespace-pre-line break-words leading-relaxed">{{ $variety['disease_resistance'] }}</p>
                            </div>
                            @endif

                            @if(!empty($variety['description_summary']))
                            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4">
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Ringkasan Keunggulan
                                </p>
                                <p class="text-sm text-gray-800 whitespace-pre-line break-words leading-relaxed">{{ $variety['description_summary'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Seed Class Cards Section (Dynamic Design) -->
                    <div class="mb-8" id="seed-selection-container">
                        <h3 class="font-bold text-slate-800 mb-4 text-lg">Pilih Kelas Benih & Stok</h3>
                        
                        @php
                            $stockDetails = collect($variety['stock']['details'] ?? []);
                            // Filter: hanya tampilkan kelas yang benar-benar punya stok
                            $stockDetailsWithStock = $stockDetails->filter(fn($c) => ($c['quantity'] ?? 0) > 0);
                            $isOutOfStock = $stockDetailsWithStock->count() <= 0;
                            $seedLots = collect($variety['seed_lots'] ?? []);
                        @endphp
 
                        <div id="class-cards-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($stockDetailsWithStock as $class)
                                <div class="seed-class-card border border-slate-100 rounded-2xl p-4 relative group hover:border-emerald-300 transition-all bg-white shadow-sm hover:shadow-md cursor-pointer" 
                                     data-seed-class-id="{{ $class['class_id'] }}" 
                                     data-seed-class-code="{{ $class['code'] }}"
                                     data-seed-class-name="{{ $class['name'] }}"
                                     data-price="{{ $class['price'] ?? 0 }}"
                                     data-min-order="{{ $class['min_order_qty'] ?? 0 }}"
                                     data-step-increment="{{ $class['step_increment'] ?? 0 }}"
                                     data-stock="{{ $class['quantity'] ?? 0 }}"
                                     data-unit="{{ $class['default_unit'] ?? 'kg' }}"
                                     onclick="selectClass('{{ $class['code'] }}')">
                                     
                                     <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                @php
                                                    $badgeClass = 'bg-gray-50 text-gray-700 border-gray-200'; // fallback
                                                    if ($class['category'] == 'unit') {
                                                        $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                                    } elseif ($class['code'] === 'BS') {
                                                        $badgeClass = 'bg-yellow-400/20 text-yellow-800 border-yellow-300';
                                                    } elseif ($class['code'] === 'FS') {
                                                        $badgeClass = 'bg-white text-gray-800 border-gray-400';
                                                    } elseif ($class['code'] === 'SS') {
                                                        $badgeClass = 'bg-purple-600 text-white border-purple-700';
                                                    }
                                                @endphp
                                                <span class="px-2 py-0.5 rounded text-xs font-bold border {{ $badgeClass }}">
                                                    {{ $class['code'] }}
                                                </span>
                                                <h4 class="font-bold text-slate-800 text-lg">{{ $class['name'] }}</h4>
                                            </div>
                                            
                                            <p class="mb-2 font-bold text-emerald-600">
                                                @if(!empty($class['price']))
                                                    Rp {{ number_format($class['price'], 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">per {{ $class['default_unit'] ?? 'kg' }}</span>
                                                @else
                                                    <span class="text-sm font-normal text-gray-400 italic">Harga belum tersedia</span>
                                                @endif
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                Tersedia: <span class="font-semibold text-gray-900">{{ number_format($class['quantity'], 0, ',', '.') }} {{ $class['default_unit'] ?? 'kg' }}</span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Min. Pembelian: {{ $class['min_order_qty'] }} {{ $class['default_unit'] ?? 'kg' }}
                                                @if(($class['step_increment'] ?? 1) > 1)
                                                    (Kelipatan {{ $class['step_increment'] }})
                                                @endif
                                            </p>
                                        </div>
                                     </div>
                        
                                    <div class="quantity-controls hidden absolute right-5 bottom-5 bg-white shadow-lg border rounded-lg p-1 items-center gap-2 z-10" onclick="event.stopPropagation()"> 
                                        <button type="button" class="decrease w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">-</button> 
                                        <input type="number" class="quantity w-16 text-center border-slate-200 rounded-lg text-sm focus:ring-emerald-500/30 focus:border-emerald-500" 
                                               value="{{ $class['min_order_qty'] }}" 
                                               min="{{ $class['min_order_qty'] }}" 
                                               step="{{ $class['step_increment'] }}" 
                                               inputmode="numeric" pattern="[0-9]*"
                                               oninput="this.value = !!this.value && Math.abs(this.value) >= 1 ? Math.abs(Math.floor(this.value)) : null; handleQtyInput(this)"> 
                                        <button type="button" class="increase w-8 h-8 flex items-center justify-center bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-600/20">+</button> 
                                    </div> 
                                    <p class="qty-error text-xs text-red-600 mt-1 hidden">
                                        @if(($class['step_increment'] ?? 1) > 1)
                                            Jumlah harus kelipatan {{ $class['step_increment'] }} {{ $class['default_unit'] ?? 'kg' }}
                                        @else
                                            Jumlah minimal {{ $class['min_order_qty'] }} {{ $class['default_unit'] ?? 'kg' }}
                                        @endif
                                    </p>
                                    
                                    @php
                                        // Seed class ID kini disertakan di API response seed_lots
                                        $lotsForClass = $seedLots->filter(function($lot) use ($class) {
                                            $lotScId = $lot['seed_class']['id'] ?? null;
                                            return $lotScId == $class['class_id']
                                                && ($lot['is_sellable'] ?? false)
                                                && (int)($lot['quantity'] ?? 0) > 0;
                                        });
                                    @endphp

                                    @if($lotsForClass->count() > 0)
                                    <div class="lot-options mt-4 space-y-2" data-seed-class-code="{{ $class['code'] }}">
                                        @foreach($lotsForClass as $lot)
                                            <label class="flex items-center justify-between border rounded-lg p-3 cursor-pointer hover:border-blue-300">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="lot-{{ $class['code'] }}" value="{{ $lot['id'] }}" data-price="{{ (float) ($lot['price_per_unit'] ?? 0) }}">
                                                    <span class="text-sm text-gray-900">Lot {{ $lot['id'] }}</span>
                                                    <span class="text-xs text-gray-500">Stok {{ number_format($lot['quantity'], 0, ',', '.') }} {{ $lot['unit'] ?? 'kg' }}</span>
                                                </div>
                                                <div class="text-sm font-semibold text-blue-600">Rp {{ number_format((float) ($lot['price_per_unit'] ?? 0), 0, ',', '.') }}</div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-sm text-gray-500 mt-3 italic">Pilih kelas ini untuk melihat detail ketersediaan.</div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-6 bg-gray-50 rounded-xl text-center text-gray-500 italic border border-dashed border-gray-300">Stok belum tersedia untuk varietas ini.</div>
                            @endforelse
                        </div>

                        <p id="loading-error" class="text-red-500 text-sm mt-2 hidden">Gagal memuat data stok.</p>
                    </div>

                    <!-- Hidden Inputs for Checkout/Cart -->
                    <input type="hidden" id="selected-lot-id">
                    <input type="hidden" id="selected-qty">
                    
                    @if($isOutOfStock)
                        <div class="mt-auto sticky bottom-0 bg-white/80 backdrop-blur-md py-4 border-t border-slate-100">
                            <a href="https://wa.me/628111756776?text={{ urlencode('Halo Admin UPBS Biogen, saya ingin tanya stok untuk varietas ' . $variety['name']) }}" target="_blank" class="flex w-full items-center justify-center gap-2 bg-emerald-600 text-white py-3.5 rounded-2xl font-bold shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                <i class="bx bxl-whatsapp text-xl"></i> Tanyakan Ketersediaan via WhatsApp
                            </a>
                        </div>
                    @else
                        <!-- Action Buttons (Initially Hidden or Disabled until selection) -->
                        <div class="mt-auto grid grid-cols-1 sm:grid-cols-2 gap-3 sticky bottom-0 bg-white/90 backdrop-blur-md py-4 px-2 border-t border-slate-100 z-50">
                            <button id="btn-add-cart" onclick="addToCartAction(false)" disabled class="flex items-center justify-center gap-2 bg-white border-2 border-emerald-600 text-emerald-600 py-3 rounded-xl font-bold hover:bg-emerald-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm text-sm btn-premium-feedback">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>+ Keranjang</span>
                            </button>
                            <button id="btn-buy-now" onclick="addToCartAction(true)" disabled class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 hover:shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed text-sm btn-premium-feedback">
                                Beli Sekarang
                            </button>
                        </div>
                        
                        <!-- Helper text for selection -->
                        <p id="selection-helper" class="text-center text-sm text-gray-500 mt-2">Silakan pilih kelas benih dan jumlah di atas.</p>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Data for JS -->
    <script>
        const _basePrice = {{ (int)(collect($variety['seed_lots'] ?? [])->min('price_per_unit') ?: 0) }};
        window.varietyData = {
            id: "{{ $variety['id'] }}",
            slug: "{{ $variety['slug'] }}",
            name: "{{ addslashes($variety['name']) }}",
            image: "{{ $primaryImageUrlNormalized }}",
            base_price: _basePrice,
            seed_lots: @json($variety['seed_lots'] ?? [])
        };
    </script>

    @vite(['resources/js/produk.js', 'resources/css/produk.css', 'resources/js/produk-gallery.js'])
@endsection

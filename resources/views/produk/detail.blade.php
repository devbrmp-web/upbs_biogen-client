@extends('layouts.app')

@section('content')
<!-- <pre class="bg-gray-100 text-xs p-4 rounded overflow-auto max-h-[400px]">
DEBUG VARIETY:
{{ json_encode($variety ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}

DEBUG SEED CLASSES:
{{ json_encode($seedClasses ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}

DEBUG VARIETY INFO:
{{ json_encode($varietyInfo ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}

DEBUG VARIETY AUDIENCE:
{{ json_encode($varietyAudience ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
</pre> -->


<div class="page-animate-zoomIn">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-28">

        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500">
            <a href="/katalog" class="hover:text-gray-900">Katalog</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">{{ $variety['name'] }}</span>
        </nav>

        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
    <div class="grid grid-cols-1 lg:grid-cols-2">

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
        <div class="bg-gray-50 flex flex-col items-center justify-center p-4">
            
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
                             if ($rawUrl) {
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
                              // Logic yang sama untuk thumbnail
                             $rawUrl = $img['image_url'] ?? null;
                             if ($rawUrl) {
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

        <!-- Content Section -->
        <div class="p-6 flex flex-col justify-center">

            <div class="mb-4">
                <span class="text-xs text-blue-600 font-medium uppercase tracking-wide">
                    {{ $variety['commodity']['name'] }}
                </span>

                <h1 class="text-xl font-semibold text-gray-900 mt-1">
                    {{ $variety['name'] }}
                </h1>

                <!-- Price -->
                <div class="mt-2">
                    <div class="prose prose-blue text-gray-600 mb-8 max-w-none">
                        {!! nl2br(e($variety['description'])) !!}
                    </div>

                    @if(!empty($varietyInfo))
                    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Varietas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-medium text-gray-700">Asal</p>
                                <p class="mt-1 text-gray-900">{{ $varietyInfo['asal'] }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Umur Tanaman</p>
                                <p class="mt-1 text-gray-900">{{ is_numeric($varietyInfo['umur_tanaman_hari']) ? ((int) $varietyInfo['umur_tanaman_hari']) . ' hari' : $varietyInfo['umur_tanaman_hari'] }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Rata-rata Hasil</p>
                                <p class="mt-1 text-gray-900">{{ $varietyInfo['rata_rata_hasil'] }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Tekstur Nasi</p>
                                <p class="mt-1 text-gray-900">{{ $varietyInfo['tekstur_nasi'] }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="font-medium text-gray-700">Ketahanan</p>
                                <p class="mt-1 text-gray-900">
                                    Hama: {{ $varietyInfo['ketahanan_hama'] }}<br>
                                    Penyakit: {{ $varietyInfo['ketahanan_penyakit'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($varietyAudience))
                    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Ketahanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="font-medium text-gray-700 mb-2">Versi Masyarakat Umum</p>
                                <p class="text-gray-900">{{ $varietyAudience['public'] }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700 mb-2">Versi Petani</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Ketahanan terhadap hama</p>
                                        <ul class="list-disc list-inside text-gray-900 text-sm">
                                            @forelse(($varietyAudience['farmer']['hama'] ?? []) as $it)
                                                <li>{{ $it }}</li>
                                            @empty
                                                <li>-</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Ketahanan terhadap penyakit</p>
                                        <ul class="list-disc list-inside text-gray-900 text-sm">
                                            @forelse(($varietyAudience['farmer']['penyakit'] ?? []) as $it)
                                                <li>{{ $it }}</li>
                                            @empty
                                                <li>-</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Seed Class Cards Section (New Design) -->
                    <div class="mb-8" id="seed-selection-container">
                        <h3 class="font-semibold text-gray-900 mb-4 text-lg">Pilih Kelas Benih & Stok</h3>
                        
                        @php
                            $seedLots = collect($variety['seed_lots'] ?? []);
                            $allSeedClasses = collect($seedClasses ?? []);
                            
                            // Filter lots yang memiliki data seed_class valid (code harus ada)
                            $classes = $seedLots->filter(function($lot) {
                                return !empty($lot['seed_class']) && isset($lot['seed_class']['code']);
                            })->groupBy('seed_class.code')->map(function($lots, $code) use ($allSeedClasses) {
                                $first = $lots->first();
                                $seedClass = $first['seed_class'] ?? [];
                                
                                // Cari ID dari referensi seedClasses jika tidak ada di response variety
                                $classRef = $allSeedClasses->firstWhere('code', $code);
                                $realId = $classRef['id'] ?? ($seedClass['id'] ?? 0);

                                return [
                                    'id' => $realId,
                                    'code' => $code,
                                    'name' => $seedClass['name'] ?? ($classRef['name'] ?? $code),
                                    'total_stock' => $lots->where('is_sellable', true)->sum('quantity'),
                                    'min_order' => $code === 'FS' ? 5 : 1,
                                    'unit' => $first['unit'] ?? 'kg',
                                    'price' => $first['price_per_unit'] ?? null
                                ];
                            })->filter(function($c) {
                                return $c['id'] !== 0;
                            });
                        @endphp
 
                        <div id="class-cards-container" class="space-y-4">
                            @forelse($classes as $class)
                                <div class="seed-class-card border rounded-xl p-5 relative group hover:border-blue-300 transition bg-white cursor-pointer" 
                                     data-seed-class-id="{{ $class['id'] }}" 
                                     data-seed-class-code="{{ $class['code'] }}"
                                     data-seed-class-name="{{ $class['name'] }}"
                                     data-price="{{ $class['price'] ?? 0 }}"
                                     data-minimum-limit="{{ $class['min_order'] }}"
                                     onclick="selectClass('{{ $class['code'] }}')">
                                     
                                     <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-xs font-bold border {{ $class['code'] == 'BS' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-purple-50 text-purple-700 border-purple-200' }}">{{ $class['code'] }}</span>
                                                <h4 class="font-bold text-gray-900 text-lg">{{ $class['name'] }}</h4>
                                            </div>
                                            
                                            <p class="mb-2 font-semibold text-blue-600">
                                                @if(!empty($class['price']))
                                                    Rp {{ number_format($class['price'], 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">per kg</span>
                                                @else
                                                    <span class="text-sm font-normal text-gray-400 italic">Harga belum tersedia</span>
                                                @endif
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                Total Stok: <span class="font-semibold text-gray-900">{{ $class['total_stock'] }} {{ $class['unit'] }}</span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Min. Pembelian: {{ $class['min_order'] }} {{ $class['unit'] }}</p>
                                        </div>
                                     </div>
                        
                                    <div class="quantity-controls hidden absolute right-5 bottom-5 bg-white shadow-lg border rounded-lg p-1 items-center gap-2 z-10" onclick="event.stopPropagation()"> 
                                        <button type="button" class="decrease w-8 h-8 flex items-center justify-center bg-gray-100 rounded hover:bg-gray-200">-</button> 
                                        <input type="number" class="quantity w-16 text-center border-gray-200 rounded text-sm" value="{{ $class['min_order'] }}" min="{{ $class['min_order'] }}" step="{{ $class['code'] == 'FS' ? 5 : 1 }}" oninput="handleQtyInput(this)"> 
                                        <button type="button" class="increase w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded hover:bg-blue-700">+</button> 
                                    </div> 
                                    <p class="qty-error text-xs text-red-600 mt-1 hidden">Jumlah untuk Foundation Seed (FS) harus kelipatan 5 kg</p>
                                    
                                    @php
                                        $lotsForClass = $seedLots->filter(function($lot) use ($class) {
                                            return !empty($lot['seed_class']) && ($lot['seed_class']['code'] ?? null) === $class['code'];
                                        })->filter(function($lot) {
                                            return ($lot['is_sellable'] ?? false) && (int)($lot['quantity'] ?? 0) > 0;
                                        });
                                    @endphp
                                    @if($lotsForClass->count() > 0)
                                    <div class="lot-options mt-4 space-y-2" data-seed-class-code="{{ $class['code'] }}">
                                        @foreach($lotsForClass as $lot)
                                            <label class="flex items-center justify-between border rounded-lg p-3 cursor-pointer hover:border-blue-300">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="lot-{{ $class['code'] }}" value="{{ $lot['id'] }}" data-price="{{ (int) ($lot['price_per_unit'] ?? 0) }}">
                                                    <span class="text-sm text-gray-900">Lot {{ $lot['id'] }}</span>
                                                    <span class="text-xs text-gray-500">Stok {{ (int) ($lot['quantity'] ?? 0) }} {{ $lot['unit'] ?? 'kg' }}</span>
                                                </div>
                                                <div class="text-sm font-semibold text-blue-600">Rp {{ number_format((int) ($lot['price_per_unit'] ?? 0), 0, ',', '.') }}</div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-sm text-gray-500 mt-3">Tidak ada lot tersedia untuk kelas ini.</div>
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
                    
                    <!-- Action Buttons (Initially Hidden or Disabled until selection) -->
                    <div class="mt-auto grid grid-cols-2 gap-4 sticky bottom-0 bg-white py-4 border-t border-gray-100">
                        <button id="btn-add-cart" onclick="addToCartAction(false)" disabled class="flex items-center justify-center gap-2 bg-white border-2 border-blue-600 text-blue-600 py-3.5 rounded-xl font-bold hover:bg-blue-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            + Keranjang
                        </button>
                        <button id="btn-buy-now" onclick="addToCartAction(true)" disabled class="bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Beli Sekarang
                        </button>
                    </div>
                    
                    <!-- Helper text for selection -->
                    <p id="selection-helper" class="text-center text-sm text-gray-500 mt-2">Silakan pilih kelas benih dan jumlah di atas.</p>


                </div>
            </div>
        </div>
    </div>

    <!-- Data for JS -->
    <script>
        window.varietyData = {
            id: "{{ $variety['id'] }}",
            slug: "{{ $variety['slug'] }}",
            name: "{{ $variety['name'] }}",
            image: "{{ $primaryImageUrlNormalized }}",
            base_price: {{ ($variety['price_cents'] ?? 0) / 100 }},
            // Pass initial seed lots to extract classes
            // Note: Admin API returns 'seed_lots' array. We use it to list classes.
            seed_lots: @json($variety['seed_lots'] ?? [])
        };
    </script>

    @vite(['resources/js/produk.js', 'resources/css/produk.css', 'resources/js/produk-gallery.js'])
</div>
@endsection

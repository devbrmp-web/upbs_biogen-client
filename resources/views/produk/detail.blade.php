@extends('layouts.app')

@section('content')

<div class="page-animate-zoomIn">

<body class="bg-gray-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-18">

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
            $imageUrl = $imagePath
                ? rtrim(config('app.url_dev_admin'), '/') . '/storage/' . ltrim($imagePath, '/')
                : 'https://placehold.co/400x300?text=No+Image';
        @endphp

        <!-- Image Section -->
        <div class="bg-gray-50 flex items-center justify-center aspect-[1/1]">
            <img
                src="{{ $imageUrl }}"
                alt="{{ $variety['name'] }}"
                class="w-full h-full object-contain rounded-md"
                loading="lazy"
            >
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
                    <p id="display-price" class="text-lg font-semibold text-gray-900">
                        {{ $variety['price_idr'] }}
                        <span class="text-sm text-gray-500 font-normal">/ kg</span>
                    </p>
                    <div class="prose prose-blue text-gray-600 mb-8 max-w-none">
                        {!! nl2br(e($variety['description'])) !!}
                    </div>

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
                                    'min_order' => $code === 'BS' ? 5 : ($code === 'FS' ? 1 : 1),
                                    'unit' => $first['unit'] ?? 'kg'
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
                                     data-minimum-limit="{{ $class['min_order'] }}"
                                     onclick="selectClass('{{ $class['code'] }}')">
                                     
                                     <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-xs font-bold border {{ $class['code'] == 'BS' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-purple-50 text-purple-700 border-purple-200' }}">{{ $class['code'] }}</span>
                                                <h4 class="font-bold text-gray-900 text-lg">{{ $class['name'] }}</h4>
                                            </div>
                                            <p class="text-sm text-gray-500">
                                                Total Stok: <span class="font-semibold text-gray-900">{{ $class['total_stock'] }} {{ $class['unit'] }}</span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Min. Pembelian: {{ $class['min_order'] }} {{ $class['unit'] }}</p>
                                        </div>
                                     </div>
                        
                                    <div class="quantity-controls hidden absolute right-5 bottom-5 bg-white shadow-lg border rounded-lg p-1 items-center gap-2 z-10" onclick="event.stopPropagation()"> 
                                        <button type="button" class="decrease w-8 h-8 flex items-center justify-center bg-gray-100 rounded hover:bg-gray-200">-</button> 
                                        <input type="number" class="quantity w-16 text-center border-gray-200 rounded text-sm" value="{{ $class['min_order'] }}" min="{{ $class['min_order'] }}" step="{{ $class['code'] == 'BS' ? 5 : 1 }}" oninput="handleQtyInput(this)"> 
                                        <button type="button" class="increase w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded hover:bg-blue-700">+</button> 
                                    </div> 
                                    <p class="qty-error text-xs text-red-600 mt-1 hidden">Jumlah untuk Breeder Seed (BS) harus kelipatan 5 kg</p>
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
            image: "{{ ($variety['image_url'] ?? null) ?: (config('app.url_dev_admin').'/storage/'.($variety['image_path'] ?? '')) }}",
            base_price: {{ $variety['price_cents'] / 100 }},
            // Pass initial seed lots to extract classes
            // Note: Admin API returns 'seed_lots' array. We use it to list classes.
            seed_lots: @json($variety['seed_lots'] ?? [])
        };
    </script>

    @vite(['resources/js/produk.js', 'resources/css/produk.css'])
</div>
</body>
@endsection

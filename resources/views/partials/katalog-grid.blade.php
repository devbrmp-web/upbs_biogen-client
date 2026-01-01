<div id="product-grid-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
    @if(empty($varieties))
         <div class="col-span-2 md:col-span-4 p-8 text-center bg-white rounded-xl shadow-sm border border-gray-100">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-lg font-medium text-gray-900">Tidak ada varietas ditemukan</h3>
            <p class="text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
            <a href="/katalog" class="reset-filter-btn mt-4 inline-block text-blue-600 hover:underline">Reset Filter</a>
         </div>
    @endif

    @forelse ($varieties as $variety)
        @php
            $priceRaw = $variety['price_idr'] ?? 0;
            $priceClean = (float) preg_replace('/[^\d.]/', '', $priceRaw);

            // --- START FIX URL GAMBAR ---
            $imagePath = $variety['image_path'] ?? null;
            $adminUrl = rtrim(config('app.url_dev_admin'), '/');

            if ($imagePath) {
                // Hapus prefix jika ada, lalu bangun URL lengkap
                $cleanPath = str_replace(['public/', 'storage/'], '', $imagePath);
                $imageUrl = $adminUrl . '/storage/' . ltrim($cleanPath, '/');
            } else {
                $imageUrl = 'https://placehold.co/400x300?text=No+Image';
            }
            // --- END FIX URL GAMBAR ---

            // Prepare Stock Data
            // Prioritize data from Controller (stock_by_class)
            $stockData = collect($variety['stock_by_class'] ?? []);
            $totalStock = $variety['stock']['total_stock_kg'] ?? 0;
            
            // Fallback to calculation if empty (legacy support or if seed_lots exists)
            if ($stockData->isEmpty() && !empty($variety['seed_lots'])) {
                $seedLots = collect($variety['seed_lots']);
                $stockData = $seedLots
                    ->filter(fn ($lot) =>
                        ($lot['is_sellable'] ?? false) &&
                        !empty($lot['seed_class']['code']) &&
                        ($lot['quantity'] ?? 0) > 0
                    )
                    ->groupBy(fn ($lot) => $lot['seed_class']['code'])
                    ->map(fn ($lots) => [
                        'stock' => $lots->sum('quantity')
                    ]);
            }
        @endphp

        <div class="bg-white border border-gray-100 shadow-md
                    hover:shadow-lg transition-all duration-300 rounded-lg overflow-hidden group/card">

            <a href="{{ route('product.detail', $variety['slug']) }}" class="block">

                <div class="h-40 bg-gray-100 overflow-hidden relative">
                    @php
                        $imagesArr = $variety['images'] ?? [];
                        $primaryImg = null;
                        foreach ($imagesArr as $img) {
                            if (!empty($img['is_primary'])) { $primaryImg = $img; break; }
                        }
                        if ($primaryImg && !empty($primaryImg['image_url'])) {
                            $pathOnly = parse_url($primaryImg['image_url'], PHP_URL_PATH);
                            $cleanP = str_replace(['public/', 'storage/'], '', $pathOnly ?: '');
                            $cardImg = $adminUrl . '/storage/' . ltrim($cleanP, '/');
                        } else {
                            $cardImg = $imageUrl;
                        }
                    @endphp
                    <img src="{{ $cardImg }}"
                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                </div>

                <div class="p-3">
                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2">
                        {{ $variety['name'] }}
                    </h3>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ $variety['commodity']['name'] ?? '-' }}
                    </p>
                    
                    <p class="text-sm text-green-700 font-semibold mt-2">
                        @if(!empty($variety['price_range_text']))
                            {{ $variety['price_range_text'] }}
                        @else
                            <span class="text-gray-400">Harga belum tersedia</span>
                        @endif
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Minimum: {{ $variety['minimum_limit'] ?? 0 }} kg
                    </p>

                    @if ($stockData->isNotEmpty())
                        <p class="text-xs text-gray-600 mt-2 flex flex-wrap">
                            @foreach ($stockData as $code => $data)
                                @php
                                    $qty = is_array($data) ? ($data['stock'] ?? 0) : $data;
                                @endphp
                                <span class="mr-2">
                                    <b>{{ $code }}</b>: {{ $qty }}
                                </span>
                            @endforeach
                        </p>
                    @else
                        <p class="text-xs text-gray-500 mt-2">
                            Total Stok: {{ $totalStock }} kg
                        </p>
                    @endif
                </div>
            </a>
        </div>
    @empty
        <p class="col-span-4 text-center text-gray-600">
            Tidak ada data varietas tersedia.
        </p>
    @endforelse
</div>

<div id="product-grid-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-8">
    @if(empty($varieties))
    <div class="col-span-2 md:col-span-4 p-8 text-center bg-white rounded-xl shadow-sm border border-gray-100">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900">Tidak ada varietas ditemukan</h3>
        <p class="text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
        <a href="/katalog" class="reset-filter-btn mt-4 inline-block text-emerald-600 hover:text-emerald-700 font-semibold hover:underline transition-colors">Reset Filter</a>
    </div>
    @endif

    @forelse ($varieties as $index => $variety)
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
    $stockDetails = collect($variety['stock']['details'] ?? []);
    $totalKg = $variety['stock']['total_weight_kg'] ?? 0;
    $totalUnit = $variety['stock']['total_unit_qty'] ?? 0;

    // Stok kosong jika total berat dan total unit keduanya 0
    $isOutOfStock = ($totalKg <= 0 && $totalUnit <=0);
        @endphp

        <div class="bg-white border border-slate-200/60 shadow-sm
                    hover:shadow-2xl hover:shadow-emerald-900/10 hover:-translate-y-2 hover:scale-[1.03] hover:ring-4 hover:ring-emerald-500/10 transition-all duration-500 rounded-3xl overflow-hidden group/card relative card-premium-feedback animate-fade-up">

        <a href="{{ route('product.detail', $variety['slug']) }}" class="block">

            <div class="h-40 bg-gray-100 overflow-hidden relative">
                @php
                $imagesArr = $variety['images'] ?? [];
                $primaryImg = null;
                foreach ($imagesArr as $img) {
                if (!empty($img['is_primary'])) { $primaryImg = $img; break; }
                }
                if ($primaryImg && !empty($primaryImg['image_url'])) {
                $rawUrl = $primaryImg['image_url'];
                $pathOnly = parse_url($rawUrl, PHP_URL_PATH);
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

                @if($isOutOfStock)
                <div class="absolute inset-0 bg-slate-900/40 z-10 flex items-center justify-center backdrop-blur-[2px]">
                    <span class="bg-white/90 border border-white text-slate-900 px-3 py-1.5 rounded-full text-xs font-bold shadow-xl text-center uppercase tracking-wider">
                        <i class="bx bx-info-circle mr-1"></i>Stok Kosong
                    </span>
                </div>
                @endif
            </div>

            <div class="p-3">
                <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 break-words">
                    {{ $variety['name'] }}
                </h3>

                <p class="text-xs text-gray-500 mt-1">
                    {{ $variety['commodity']['name'] ?? '-' }}
                </p>

                <p class="text-sm text-emerald-600 font-bold mt-2">
                    @if(!empty($variety['price_range_text']))
                    {{ $variety['price_range_text'] }}
                    @else
                    <span class="text-gray-400">Harga belum tersedia</span>
                    @endif
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    Limit: {{ $variety['minimum_limit'] ?? 0 }} kg
                </p>

                @if ($stockDetails->isNotEmpty())
                <p class="text-xs text-gray-600 mt-2 flex flex-wrap gap-y-1">
                    @foreach ($stockDetails as $detail)
                    @if(($detail['quantity'] ?? 0) > 0)
                    @php
                        $badgeClass = 'bg-gray-50 border-gray-100 text-gray-900';
                        if ($detail['code'] === 'BS') {
                            $badgeClass = 'bg-yellow-400/20 text-yellow-800 border-yellow-300';
                        } elseif ($detail['code'] === 'FS') {
                            $badgeClass = 'bg-white text-gray-800 border-gray-400';
                        } elseif ($detail['code'] === 'SS') {
                            $badgeClass = 'bg-purple-600 text-white border-purple-700';
                        }
                    @endphp
                    <span class="mr-2 border px-1.5 py-0.5 rounded {{ $badgeClass }}">
                        <b>{{ $detail['code'] }}</b>: {{ number_format($detail['quantity'], 0, ',', '.') }} {{ $detail['default_unit'] ?? 'kg' }}
                    </span>
                    @endif
                    @endforeach
                </p>
                @else
                <p class="text-xs text-gray-500 mt-2">
                    Total Stok: {{ number_format($totalKg, 0, ',', '.') }} kg
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
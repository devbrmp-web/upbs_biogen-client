<section class="relative py-16 bg-white/60 backdrop-blur-sm animate-fadeIn">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <h2 class="text-2xl font-bold text-gray-900 mb-10 text-center">Produk Tersedia</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse(($varieties ?? []) as $v)
                <div class="group relative bg-white border border-gray-100 rounded-2xl shadow hover:shadow-lg overflow-hidden transition-all">
                    <div class="h-44 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @php
                            $img = ($v['image_url'] ?? null) ?: (config('app.url_dev_admin').'/storage/'.($v['image_path'] ?? ''));
                        @endphp
                        <img src="{{ $img }}" alt="{{ $v['name'] }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" loading="lazy" onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700">{{ strtoupper($v['commodity']['name'] ?? 'Produk') }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm mb-3">{{ $v['name'] }}</h3>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('product.detail', $v['slug']) }}" class="inline-flex items-center bg-gray-900 text-white text-xs px-3 py-2 rounded-lg hover:bg-gray-800 transition">Lihat Detail</a>
                            <a href="/katalog?commodity={{ strtolower($v['commodity']['slug'] ?? '') }}" class="text-xs text-gray-600 hover:text-gray-900">Lihat Katalog</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 md:col-span-4 text-center text-gray-600">Belum ada produk tersedia.</div>
            @endforelse
        </div>

    </div>
</section>

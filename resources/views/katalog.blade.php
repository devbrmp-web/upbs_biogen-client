@extends('layouts.app')
@section('title', 'Katalog Produk - UPBS BRMP Biogen')

@section('content')

{{-- =======================
     PAGE ANIMATION HANDLER
======================== --}}
@if(request()->has('commodity') || request()->has('seed_class') || request()->has('search'))
    <script>
        document.body.classList.add('page-animated');
    </script>
@else
    <script>
        try {
            const ref = document.referrer ? new URL(document.referrer) : null;
            if (ref && ref.pathname === '/katalog') {
                document.body.classList.add('page-animated');
            }
        } catch (e) {}
    </script>
@endif

<div class="page-animate-rise">
<section class="pt-28 pb-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        {{-- =======================
             JUDUL
        ======================== --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Katalog Produk</h1>
            <p class="text-gray-700 text-lg">
                Temukan berbagai varietas unggul hasil inovasi UPBS BRMP Biogen 🌾
            </p>
        </div>

        {{-- =======================
             FILTER KOMODITAS
        ======================== --}}
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pilih Komoditas</h2>

            <div class="flex flex-nowrap gap-4 overflow-x-auto pb-2 scrollbar-hide">

                @if (!$activeCommodity)
                    <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                        bg-[#B4DEBD] text-black border-[#B4DEBD] cursor-not-allowed opacity-70">
                        Tampilkan Semua
                    </span>
                @else
                    <a href="/katalog"
                       class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                       bg-[#B4DEBD]/40 hover:bg-[#B4DEBD]/60 transition">
                        Tampilkan Semua
                    </a>
                @endif

                @foreach ($commodities as $commodity)
                    @php
                        $slug = strtolower($commodity['slug']);
                        $isActive = $activeCommodity === $slug;
                        $seedParam = $activeSeedClass ? "&seed_class={$activeSeedClass}" : '';
                    @endphp

                    @if ($isActive)
                        <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                            bg-[#B4DEBD] cursor-not-allowed opacity-70">
                            {{ $commodity['name'] }}
                        </span>
                    @else
                        <a href="/katalog?commodity={{ $slug }}{{ $seedParam }}"
                           class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                           bg-[#B4DEBD]/40 hover:bg-[#B4DEBD]/60 transition">
                            {{ $commodity['name'] }}
                        </a>
                    @endif
                @endforeach

            </div>
        </div>

        h2 class="text-lg font-semibold text-gray-800 mb-2">Filter Kelas Benih</h2>

    <select
        onchange="
            const seed = this.value;
            const params = new URLSearchParams(window.location.search);

            if (seed) {
                params.set('seed_class', seed);
            } else {
                params.delete('seed_class');
            }

            window.location.href = '/katalog?' + params.toString();
        "
        class="block w-full max-w-xs rounded-lg border-gray-300 p-2.5 bg-white"
    >
        <option value="">Semua Kelas</option>
        @foreach ($seedClasses as $sc)
            <option value="{{ $sc['code'] }}"
                {{ $activeSeedClass === $sc['code'] ? 'selected' : '' }}>
                {{ $sc['name'] }} ({{ $sc['code'] }})
            </option>
        @endforeach
    </select>
</div>

        {{-- =======================
             GRID PRODUK (UPDATED)
        ======================== --}}
        <div id="catalog-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">

        @forelse ($varieties as $variety)
            @php
                $priceRaw = $variety['price_idr'] ?? 0;
                $priceClean = (float) preg_replace('/[^\d.]/', '', $priceRaw);

                $imagePath = $variety['image_path'] ?? null;
                $imageUrl = $imagePath
                    ? rtrim(config('app.url_dev_admin'), '/') . '/storage/' . ltrim($imagePath, '/')
                    : 'https://placehold.co/400x300?text=No+Image';
            @endphp

            <div class="backdrop-blur-md bg-white/30 border border-white/20 shadow-md
                        hover:shadow-lg transition rounded-lg overflow-hidden">

                <a href="{{ route('product.detail', $variety['slug']) }}" class="block">

                    {{-- IMAGE --}}
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        <img src="{{ $imageUrl }}"
                             class="w-full h-full object-cover hover:scale-110 transition"
                             loading="lazy">
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-3 space-y-2">

                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2">
                            {{ $variety['name'] }}
                        </h3>

                        <p class="text-xs text-gray-500">
                            {{ $variety['commodity']['name'] ?? '-' }}
                        </p>

                        <p class="text-sm font-semibold text-green-700">
                            Rp {{ number_format($priceClean, 0, ',', '.') }}
                        </p>

                        {{-- 🔥 STOCK BY CLASS (FROM CONTROLLER) --}}
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach ($variety['stock_by_class'] as $code => $sc)
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                                    {{ $sc['stock'] > 0
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-200 text-gray-500'
                                    }}">
                                    {{ $code }} :
                                    {{ $sc['stock'] > 0 ? number_format($sc['stock'],0,',','.') . ' kg' : 'Habis' }}
                                </span>
                            @endforeach
                        </div>

                    </div>
                </a>
            </div>

        @empty
            <p class="col-span-4 text-center text-gray-600">
                Tidak ada data varietas tersedia untuk filter ini.
            </p>
        @endforelse

        </div>

    </div>
</section>
</div>

@endsection

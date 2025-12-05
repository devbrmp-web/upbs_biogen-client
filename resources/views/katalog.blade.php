@extends('layouts.app')
@section('title', 'Katalog Produk - UPBS BRMP Biogen')

@section('content')
@if(request()->has('commodity') || request()->has('seed_class') || request()->has('search'))
<script>document.body.classList.add('page-animated');</script>
@else
<script>
try{var r=document.referrer?new URL(document.referrer):null;if(r&&r.pathname==='/katalog'){document.body.classList.add('page-animated');}}catch(e){}
</script>
@endif
<div class="page-animate-rise">
<section class="pt-28 pb-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <!-- Judul -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Katalog Produk</h1>
            <p class="text-gray-700 text-lg">Temukan berbagai varietas unggul hasil inovasi UPBS BRMP Biogen 🌾</p>
        </div>

        <!-- =======================
             FILTER KOMODITAS
        ======================== -->
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pilih Komoditas</h2>

            <div class="flex flex-nowrap gap-4 overflow-x-auto pb-2 scrollbar-hide">

                <!-- Tampilkan Semua -->
                @if (!$activeCommodity)
                    <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md whitespace-nowrap
                        bg-[#B4DEBD] text-black border-[#B4DEBD] cursor-not-allowed opacity-70">
                        Tampilkan Semua
                    </span>
                @else
                    <a href="/katalog"
                       class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md whitespace-nowrap
                       bg-[#B4DEBD]/40 text-gray-800 hover:bg-[#B4DEBD]/60 transition">
                        Tampilkan Semua
                    </a>
                @endif

                <!-- Loop Komoditas -->
                @foreach ($commodities as $commodity)
                    @php 
                        $slug = strtolower($commodity['slug']);
                        $isActive = $activeCommodity === $slug;
                        $seedParam = $activeSeedClass ? "&seed_class={$activeSeedClass}" : '';
                    @endphp

                    @if ($isActive)
                        <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md whitespace-nowrap
                            bg-[#B4DEBD] text-black border-[#B4DEBD] cursor-not-allowed opacity-70">
                            {{ $commodity['name'] }}
                        </span>
                    @else
                        <a href="/katalog?commodity={{ $slug }}{{ $seedParam }}"
                           class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md whitespace-nowrap
                           bg-[#B4DEBD]/40 text-gray-800 hover:bg-[#B4DEBD]/60 transition">
                            {{ $commodity['name'] }}
                        </a>
                    @endif
                @endforeach

            </div>
        </div>

        <!-- =======================
             FILTER SEED CLASS (Dropdown + SSR)
        ======================== -->
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Filter Kelas Benih</h2>
            <div class="flex items-center gap-3">
                <select id="seed-class-select"
                    onchange="window.location.href = '/katalog?seed_class=' + this.value + '&commodity={{ $activeCommodity ?? '' }}';"
                    class="block w-full max-w-xs rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 bg-white border">
                    <option value="">Semua Kelas</option>
                    @foreach ($seedClasses as $sc)
                        <option value="{{ $sc['code'] }}" {{ ($activeSeedClass == $sc['code']) ? 'selected' : '' }}>
                            {{ $sc['name'] }} ({{ $sc['code'] }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- =======================
             GRID PRODUK
        ======================== -->
        <div id="catalog-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @forelse ($varieties as $variety)

                @php
                    $priceRaw = $variety['price_idr'] ?? 0;
                    $priceClean = (float) preg_replace('/[^\d.]/', '', $priceRaw);
                @endphp

               <div class="backdrop-blur-md bg-white/30 border border-white/20 shadow-md 
                hover:shadow-lg transition-all duration-300 rounded-lg overflow-hidden">

                    <!-- BAGIAN YANG KLIKABLE -->
                    <a href="{{ route('product.detail', $variety['slug']) }}" class="block">

                        <div class="h-40 bg-gray-100 overflow-hidden">
                            <img src="{{ ($variety['image_url'] ?? null) ?: (config('app.url_dev_admin').'/storage/'.($variety['image_path'] ?? '')) }}"
                                alt="{{ $variety['name'] }}"
                                class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                        </div>



                        <!-- Konten produk -->
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2">
                                {{ $variety['name'] }}
                            </h3>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $variety['commodity']['name'] ?? '-' }}
                            </p>

                            <p class="text-sm text-green-700 font-semibold mt-2">
                                Rp {{ number_format($priceClean, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Minimum: {{ $variety['minimum_limit'] ?? 0 }} kg
                            </p>
                        </div>

                    </a>

                    <!-- TOMBOL PESAN – DI LUAR <a> -->
                    
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

@extends('layouts.app')
@section('title', 'Katalog Produk - UPBS BRMP Biogen')

@section('content')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

{{-- Animasi page --}}
@if(request()->has('commodity') || request()->has('seed_class') || request()->has('search'))
<script>document.body.classList.add('page-animated');</script>
@else
<script>
try{
    var r=document.referrer?new URL(document.referrer):null;
    if(r&&r.pathname==='/katalog'){
        document.body.classList.add('page-animated');
    }
}catch(e){}
</script>
@endif

<div class="page-animate-rise">
<section class="pt-28 pb-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <!-- =======================
             JUDUL
        ======================== -->
        <div id="tutorial-title" class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Katalog Produk</h1>
            <p class="text-gray-700 text-lg">
                Temukan berbagai varietas unggul hasil inovasi UPBS BRMP Biogen 🌾
            </p>
        </div>

        <!-- =======================
             FILTER KOMODITAS
        ======================== -->
        <div id="tutorial-commodity-filter" class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pilih Komoditas</h2>

            <div class="flex flex-nowrap gap-4 overflow-x-auto pb-2 scrollbar-hide">

                {{-- Tampilkan Semua --}}
                @if (!$activeCommodity)
                    <a href="/katalog{{ $activeSeedClass ? '?seed_class='.$activeSeedClass : '' }}"
                       class="commodity-filter-link px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                        bg-[#B4DEBD] text-black border-[#B4DEBD]
                        cursor-not-allowed opacity-70 whitespace-nowrap pointer-events-none">
                        Tampilkan Semua
                    </a>
                @else
                    <a href="/katalog{{ $activeSeedClass ? '?seed_class='.$activeSeedClass : '' }}"
                       class="commodity-filter-link px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                       bg-[#B4DEBD]/40 text-gray-800 hover:bg-[#B4DEBD]/60 transition whitespace-nowrap">
                        Tampilkan Semua
                    </a>
                @endif

                {{-- Loop Komoditas --}}
                @foreach ($commodities as $commodity)
                    @php
                        $slug = strtolower($commodity['slug']);
                        $isActive = $activeCommodity === $slug;
                        $seedParam = $activeSeedClass ? "&seed_class={$activeSeedClass}" : '';
                    @endphp

                    @if ($isActive)
                        <a href="/katalog?commodity={{ $slug }}{{ $seedParam }}"
                           class="commodity-filter-link px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                            bg-[#B4DEBD] text-black border-[#B4DEBD]
                            cursor-not-allowed opacity-70 whitespace-nowrap pointer-events-none">
                            {{ $commodity['name'] }}
                        </a>
                    @else
                        <a href="/katalog?commodity={{ $slug }}{{ $seedParam }}"
                           class="commodity-filter-link px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                           bg-[#B4DEBD]/40 text-gray-800 hover:bg-[#B4DEBD]/60 transition whitespace-nowrap">
                            {{ $commodity['name'] }}
                        </a>
                    @endif
                @endforeach

            </div>
        </div>

        <!-- =======================
             FILTER SEED CLASS
        ======================== -->
        <div id="tutorial-seed-filter" class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Filter Kelas Benih</h2>

            <select
                id="seed-class-select"
                class="block w-full max-w-xs rounded-lg border-gray-300 shadow-sm
                       focus:border-indigo-500 focus:ring-indigo-500
                       sm:text-sm p-2.5 bg-white border">
                <option value="">Semua Kelas</option>
                @foreach ($seedClasses as $sc)
                    <option value="{{ $sc['code'] }}" {{ ($activeSeedClass == $sc['code']) ? 'selected' : '' }}>
                        {{ $sc['name'] }} ({{ $sc['code'] }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- =======================
             GRID PRODUK
        ======================== -->
        {{-- Debug: Jumlah varietas yang diterima: {{ count($varieties) }} --}}
        {{-- Debug: Active Commodity: {{ $activeCommodity ?? 'None' }} --}}
        {{-- Debug: Active Seed Class: {{ $activeSeedClass ?? 'None' }} --}}

        <div class="flex items-center justify-end mb-4">
            @php
                $refreshUrl = '/katalog' . ($activeCommodity ? '?commodity='.$activeCommodity : '');
                $refreshUrl .= $activeSeedClass ? (($activeCommodity ? '&' : '?').'seed_class='.$activeSeedClass) : '';
                $refreshUrl .= ($activeCommodity || $activeSeedClass) ? '&refresh=1' : '?refresh=1';
            @endphp
            <a href="{{ $refreshUrl }}" id="refreshDataBtn" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-blue-600 text-blue-600 hover:bg-blue-50 transition" aria-label="Muat Ulang Data" title="Muat Ulang Data">
                <svg id="refreshIcon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M5 19a9 9 0 0014-7M19 5a9 9 0 00-14 7" />
                </svg>
            </a>
        </div>
 
        @include('partials.katalog-grid')

    </div>
</section>
</div>

{{-- =======================
     TUTORIAL OVERLAY
======================== --}}
<div id="tutorial-overlay" class="fixed inset-0 bg-black/50 z-[9999] hidden">
    <div id="tutorial-tooltip"
         class="absolute bg-white rounded-xl shadow-xl p-5 max-w-xs text-sm animate-fade-in">
        <p id="tutorial-text" class="text-gray-700 mb-4"></p>
        <div class="flex justify-between">
            <button id="tutorial-skip" class="text-xs text-gray-400 hover:text-gray-600">
                Lewati
            </button>
            <button id="tutorial-next"
                class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                Lanjut
            </button>
        </div>
    </div>
</div>

{{-- =======================
     SCRIPT TUTORIAL
======================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const KEY = 'upbs_katalog_tutorial_seen';
    if (localStorage.getItem(KEY)) return;

    const steps = [
        { el:'#tutorial-title', text:'Ini halaman katalog. Semua varietas benih tersedia ada di sini.' },
        { el:'#tutorial-commodity-filter', text:'Gunakan filter ini untuk memilih komoditas benih.' },
        { el:'#tutorial-seed-filter', text:'Filter kelas benih sesuai kebutuhanmu.' },
    ];

    let i = 0;
    const overlay = document.getElementById('tutorial-overlay');
    const tooltip = document.getElementById('tutorial-tooltip');
    const text = document.getElementById('tutorial-text');

    function showStep() {
        const step = steps[i];
        const el = document.querySelector(step.el);
        if (!el) return;

        el.scrollIntoView({ behavior:'smooth', block:'center' });

        const r = el.getBoundingClientRect();
        text.innerText = step.text;

        tooltip.style.top = `${r.bottom + window.scrollY + 10}px`;
        tooltip.style.left = `${r.left + window.scrollX}px`;

        el.classList.add('ring-4','ring-green-400','ring-offset-4');
        overlay.classList.remove('hidden');
    }

    function clear() {
        document.querySelectorAll('.ring-green-400')
            .forEach(e => e.classList.remove('ring-4','ring-green-400','ring-offset-4'));
    }

    document.getElementById('tutorial-next').onclick = () => {
        clear();
        i++;
        if (i >= steps.length) {
            localStorage.setItem(KEY,'1');
            overlay.classList.add('hidden');
        } else showStep();
    };

    document.getElementById('tutorial-skip').onclick = () => {
        clear();
        localStorage.setItem(KEY,'1');
        overlay.classList.add('hidden');
    };

    showStep();
});
</script>

<style>
@keyframes fade-in {
    from { opacity:0; transform:translateY(10px); }
    to { opacity:1; transform:none; }
}
.animate-fade-in {
    animation: fade-in .3s ease-out both;
}
</style>

@endsection

@push('scripts')
<script>
    try {
        const TTL_15_MIN = 15 * 60 * 1000;
        const TTL_1_HOUR = 60 * 60 * 1000;
        if (window.UpbsCache) {
            window.UpbsCache.set('katalog:varieties', @json($varieties));
            window.UpbsCache.set('katalog:commodities', @json($commodities));
            window.UpbsCache.set('katalog:seedClasses', @json($seedClasses));
        }
    } catch (e) {}
    </script>
@vite('resources/js/catalog.js')
@endpush

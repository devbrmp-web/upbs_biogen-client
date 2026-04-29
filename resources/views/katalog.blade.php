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

<div class="page-animate-rise relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-100/40 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-[500px] h-[500px] bg-emerald-50/50 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    
    {{-- Organic SVG Blobs --}}
    <div class="absolute top-20 right-10 w-64 h-64 opacity-[0.04] pointer-events-none -z-10 animate-pulse">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#10B981" d="M44.7,-76.4C58.1,-69.2,70,-58,79.1,-44.6C88.2,-31.2,94.5,-15.6,93.4,-0.6C92.4,14.4,84,28.8,74.5,41.5C65,54.2,54.4,65.2,41.4,73.1C28.4,81,14.2,85.8,-0.9,87.4C-16.1,89,-32.1,87.5,-45.6,80C-59.1,72.6,-70.1,59.3,-78,44.7C-85.9,30.1,-90.7,14.1,-89.7,-1.7C-88.7,-17.5,-82,-33.1,-71.5,-45.5C-61,-57.9,-46.8,-67.2,-33,-74C-19.2,-80.8,-5.8,-85,8.8,-83.1C23.4,-81.2,31.3,-83.5,44.7,-76.4Z" transform="translate(100 100)" />
        </svg>
    </div>

<section class="pt-16 md:pt-28 pb-16 min-h-screen bg-transparent relative">
    {{-- Subtle Texture Overlay --}}
    <div class="absolute inset-0 opacity-[0.015] pointer-events-none -z-10" style="background-image: url('https://www.transparenttextures.com/patterns/natural-paper.png');"></div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12">

        <!-- =======================
             JUDUL
        ======================== -->
        <div id="tutorial-title" class="text-center mb-12">
            <h1 class="text-4xl font-bold text-slate-800 mb-2 tracking-tight">Katalog Produk</h1>
            <p class="text-slate-600 text-lg">
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
        <style>
            #seed-class-select {
                appearance: none !important;
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2310b981' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important;
                background-repeat: no-repeat !important;
                background-position: right 1rem center !important;
                background-size: 1.25rem !important;
                padding-right: 2.5rem !important;
                border-radius: 0.75rem !important;
                border: 1px solid #10b981 !important;
                background-color: #ffffff !important;
                transition: all 0.2s ease !important;
            }
            #seed-class-select:focus {
                border-color: #059669 !important;
                box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
                outline: none !important;
            }
        </style>
        <div id="tutorial-seed-filter" class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Filter Kelas Benih</h2>

            <div class="w-full max-w-xs"
                 x-data="{ 
                    open: false, 
                    selected: '{{ $activeSeedClass ?? '' }}', 
                    label: '{{ $activeSeedClass ? ($seedClasses[array_search($activeSeedClass, array_column($seedClasses, 'code'))]['name'] ?? 'Semua Kelas') : 'Semua Kelas' }}',
                    options: [
                        { value: '', label: 'Semua Kelas' },
                        @foreach ($seedClasses as $sc)
                            { value: '{{ $sc['code'] }}', label: '{{ $sc['name'] }} ({{ $sc['code'] }})' },
                        @endforeach
                    ],
                    selectOption(opt) {
                        this.selected = opt.value;
                        this.label = opt.label;
                        this.open = false;
                        
                        const filterEl = document.getElementById('seed-class-select-hidden');
                        if (filterEl) {
                            filterEl.value = opt.value;
                            filterEl.dispatchEvent(new Event('change'));
                        }
                    }
                 }">
                
                {{-- Hidden input for catalog.js --}}
                <input type="hidden" id="seed-class-select-hidden" value="{{ $activeSeedClass ?? '' }}">

                {{-- Trigger Button --}}
                <button @click="open = !open" 
                        @click.away="open = false"
                        class="relative w-full flex items-center justify-between bg-white border border-emerald-100 rounded-xl p-3 shadow-sm hover:border-emerald-500 transition-all duration-200">
                    <span class="text-slate-700 font-medium" x-text="label"></span>
                    <i class="fa-solid fa-chevron-down text-emerald-500 text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute mt-2 w-full max-w-xs bg-white border border-slate-100 rounded-xl shadow-xl z-50 overflow-hidden"
                     style="display: none;">
                    
                    <template x-for="opt in options" :key="opt.value">
                        <button @click="selectOption(opt)"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors flex items-center justify-between"
                                :class="selected === opt.value ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50'">
                            <span x-text="opt.label"></span>
                            <i x-show="selected === opt.value" class="fa-solid fa-check text-emerald-500 text-xs"></i>
                        </button>
                    </template>
                </div>
            </div>
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
            <div class="flex items-center gap-3">
                <span id="autoRefreshIndicator" class="text-xs text-emerald-600 font-bold bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100 shadow-sm uppercase tracking-wider">
                    Auto refresh: 5m
                </span>
                <a href="{{ $refreshUrl }}" id="refreshDataBtn" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-emerald-200 text-emerald-600 bg-white hover:bg-emerald-50 hover:border-emerald-500 transition-all shadow-sm hover:shadow-emerald-900/5" aria-label="Muat Ulang Data" title="Muat Ulang Sekarang">
                    <svg id="refreshIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M5 19a9 9 0 0014-7M19 5a9 9 0 00-14 7" />
                    </svg>
                </a>
            </div>
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

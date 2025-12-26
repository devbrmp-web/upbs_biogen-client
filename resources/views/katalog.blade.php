@extends('layouts.app')
@section('title', 'Katalog Produk - UPBS BRMP Biogen')

@section('content')

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
                    <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                        bg-[#B4DEBD] text-black border-[#B4DEBD]
                        cursor-not-allowed opacity-70 whitespace-nowrap">
                        Tampilkan Semua
                    </span>
                @else
                    <a href="/katalog{{ $activeSeedClass ? '?seed_class='.$activeSeedClass : '' }}"
                       class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
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
                        <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
                            bg-[#B4DEBD] text-black border-[#B4DEBD]
                            cursor-not-allowed opacity-70 whitespace-nowrap">
                            {{ $commodity['name'] }}
                        </span>
                    @else
                        <a href="/katalog?commodity={{ $slug }}{{ $seedParam }}"
                           class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md
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
        <div  class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">

            @if(empty($varieties))
                 <div class="col-span-2 md:col-span-4 p-8 text-center bg-white rounded-xl shadow-sm border border-gray-100">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada varietas ditemukan</h3>
                    <p class="text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <a href="/katalog" class="mt-4 inline-block text-blue-600 hover:underline">Reset Filter</a>
                 </div>
            @endif

            @forelse ($varieties as $variety)
                @php
                    $priceRaw = $variety['price_idr'] ?? 0;
                    $priceClean = (float) preg_replace('/[^\d.]/', '', $priceRaw);

                    $imagePath = $variety['image_path'] ?? null;
                    if ($imagePath) {
                        $cleanPath = str_replace(['public/', 'storage/'], '', $imagePath);
                        $imageUrl = rtrim(config('app.url_dev_admin'), '/') . '/storage/' . ltrim($cleanPath, '/');
                    } else {
                        $imageUrl = 'https://placehold.co/400x300?text=No+Image';
                    }

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

                <div class="backdrop-blur-md bg-white/30 border border-white/20 shadow-md
                            hover:shadow-lg transition-all duration-300 rounded-lg overflow-hidden">

                    <a href="{{ route('product.detail', $variety['slug']) }}" class="block">

                        <div class="h-40 bg-gray-100 overflow-hidden">
                            <img src="{{ $imageUrl }}"
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
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
                                Rp {{ number_format($priceClean, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Minimum: {{ $variety['minimum_limit'] ?? 0 }} kg
                            </p>

                            @if ($stockData->isNotEmpty())
                                <p class="text-xs text-gray-600 mt-2 flex flex-wrap">
                                    @foreach ($stockData as $code => $data)
                                        @php
                                            // Handle both structures:
                                            // 1. Controller normalized: ['name' => ..., 'stock' => ...]
                                            // 2. View calculated: ['stock' => ...] (if I map it like that above)
                                            // 3. Old structure: just $qty (if I didn't change map above)
                                            
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
@vite('resources/js/catalog.js')
@endpush

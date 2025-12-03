@extends('layouts.app')
@section('title', 'Katalog Produk - UPBS BRMP Biogen')

@section('content')
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
                    @endphp

                    @if ($isActive)
                        <span class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md whitespace-nowrap
                            bg-[#B4DEBD] text-black border-[#B4DEBD] cursor-not-allowed opacity-70">
                            {{ $commodity['name'] }}
                        </span>
                    @else
                        <a href="/katalog?commodity={{ $slug }}"
                           class="px-5 py-3 rounded-2xl text-sm font-medium border shadow-md whitespace-nowrap
                           bg-[#B4DEBD]/40 text-gray-800 hover:bg-[#B4DEBD]/60 transition">
                            {{ $commodity['name'] }}
                        </a>
                    @endif
                @endforeach

            </div>
        </div>

        <!-- =======================
             FILTER SEED CLASS (Dropdown + JS)
        ======================== -->
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Filter Kelas Benih</h2>
            <div class="flex items-center gap-3">
                <select id="seed-class-select" class="border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 bg-white">
                    <option value="">Semua Kelas</option>
                </select>
                <span id="seed-class-loading" class="hidden text-sm text-gray-500">Memuat...</span>
                <span id="seed-class-error" class="hidden text-sm text-red-600">Gagal memuat data</span>
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
                            <img src="{{ 'http:127.0.0.1:8000//storage/' . $variety['image_url'] }}"
                                alt="{{ $variety['name'] }}"
                                class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
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
                    Tidak ada data varietas tersedia.
                </p>
            @endforelse
        </div>

    </div>
</section>
<script>
    // Populate seed class options
    const selectEl = document.getElementById('seed-class-select');
    const loadingEl = document.getElementById('seed-class-loading');
    const errorEl = document.getElementById('seed-class-error');
    const gridEl = document.getElementById('catalog-grid');

    const ADMIN_BASE = "{{ config('app.url_dev_admin') }}";

    async function fetchSeedClasses() {
        loadingEl.classList.remove('hidden');
        try {
            const res = await window.axios.get(`${ADMIN_BASE}/api/seed-classes`);
            const classes = res.data.data || [];
            classes.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = `${c.name} (${c.code})`;
                selectEl.appendChild(opt);
            });
        } catch (e) {
            errorEl.classList.remove('hidden');
        } finally {
            loadingEl.classList.add('hidden');
        }
    }

    async function fetchVarietiesByClass(classId) {
        loadingEl.classList.remove('hidden');
        try {
            if (!classId) {
                // Reload initial SSR varieties via current URL
                window.location.href = '/katalog';
                return;
            }
            const res = await window.axios.get(`${ADMIN_BASE}/api/seed-classes/${classId}/varieties`);
            const list = res.data.data || [];
            // Render grid
            gridEl.innerHTML = list.map(v => {
                const priceClean = v.price_cents/100;
                const img = v.image_url || '/img/placeholder.jpg';
                return `
                <div class="backdrop-blur-md bg-white/30 border border-white/20 shadow-md hover:shadow-lg transition-all duration-300 rounded-lg overflow-hidden">
                    <a href="/produk/${v.slug}" class="block">
                        <div class="h-40 bg-gray-100 overflow-hidden">
                            <img src="${img}" alt="${v.name}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2">${v.name}</h3>
                            <p class="text-xs text-gray-500 mt-1">${(v.commodity && v.commodity.name) || '-'}</p>
                            <p class="text-sm text-green-700 font-semibold mt-2">Rp ${new Intl.NumberFormat('id-ID').format(priceClean)}</p>
                            <p class="text-xs text-gray-500 mt-1">Minimum: ${(v.minimum_limit||0)} kg</p>
                        </div>
                    </a>
                </div>`;
            }).join('');
        } catch (e) {
            errorEl.textContent = 'Gagal memuat varietas untuk kelas ini';
            errorEl.classList.remove('hidden');
        } finally {
            loadingEl.classList.add('hidden');
        }
    }

    selectEl.addEventListener('change', (e) => {
        const id = e.target.value;
        if (!id) {
            // fetch all varieties
            loadingEl.classList.remove('hidden');
            window.axios.get(`${ADMIN_BASE}/api/varieties`).then(res => {
                const list = res.data.data || [];
                gridEl.innerHTML = list.map(v => {
                    const priceClean = v.price_cents/100;
                    const img = v.image_url || '/img/placeholder.jpg';
                    return `
                    <div class=\"backdrop-blur-md bg-white/30 border border-white/20 shadow-md hover:shadow-lg transition-all duration-300 rounded-lg overflow-hidden\">
                        <a href=\"/produk/${v.slug}\" class=\"block\">
                            <div class=\"h-40 bg-gray-100 overflow-hidden\">
                                <img src=\"${img}\" alt=\"${v.name}\" class=\"w-full h-full object-cover hover:scale-110 transition-transform duration-500\">
                            </div>
                            <div class=\"p-3\">
                                <h3 class=\"font-semibold text-gray-900 text-sm leading-tight line-clamp-2\">${v.name}</h3>
                                <p class=\"text-xs text-gray-500 mt-1\">${(v.commodity && v.commodity.name) || '-'}<\/p>
                                <p class=\"text-sm text-green-700 font-semibold mt-2\">Rp ${new Intl.NumberFormat('id-ID').format(priceClean)}<\/p>
                                <p class=\"text-xs text-gray-500 mt-1\">Minimum: ${(v.minimum_limit||0)} kg<\/p>
                            </div>
                        </a>
                    </div>`;
                }).join('');
            }).catch(() => {
                errorEl.classList.remove('hidden');
            }).finally(() => {
                loadingEl.classList.add('hidden');
            });
        } else {
            fetchVarietiesByClass(id);
        }
    });

    // Init
    fetchSeedClasses();
</script>
@endsection

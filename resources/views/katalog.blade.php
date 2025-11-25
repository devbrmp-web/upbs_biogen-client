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

       <div class="mb-10 relative">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Pilih Komoditas</h2>
        <button id="filterToggle"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 bg-white shadow-sm hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4.5h18M6 12h12m-9 7.5h6" />
            </svg>
        </button>

        <!-- 🔽 Dropdown filter -->
        <div id="filterDropdown"
            class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
            <button class="dropdown-item w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition"
                data-filter="BS">BS</button>
            <button class="dropdown-item w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition"
                data-filter="FS">FS</button>
        </div>
    </div>

    <!-- 🌿 Scroll Horizontal Container -->
    <div id="filterKategori" class="flex flex-nowrap gap-4 overflow-x-auto pb-2 scrollbar-hide">
        
        <!-- Tombol Tampilkan Semua -->
        <button
            class="kategori-btn active bg-[#B4DEBD] text-black border border-[#B4DEBD]/60 rounded-2xl shadow-md px-5 py-3 text-sm font-medium hover:bg-[#B4DEBD]/90 transition whitespace-nowrap"
            data-kategori="all">
            Tampilkan Semua
        </button>

                <!-- Loop Komoditas -->
                @foreach ($commodities as $commodity)
                <button
                    class="kategori-btn bg-[#B4DEBD]/40 backdrop-blur-md border border-[#B4DEBD]/50 rounded-xl shadow-md px-5 py-3 text-sm font-medium text-gray-800 hover:bg-[#B4DEBD]/60 transition whitespace-nowrap"
                    data-kategori="{{ strtolower($commodity['slug']) }}">
                    {{ $commodity['name'] }}
                </button>
                @endforeach

            </div>
        </div>

        <!-- 🔹 Grid Produk -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @forelse ($varieties as $variety)

                @php
                    $priceRaw = $variety['price_idr'] ?? 0;
                    $priceClean = (float) preg_replace('/[^\d.]/', '', $priceRaw);
                @endphp

                <div class="backdrop-blur-md bg-white/30 border border-lg border-white/20 shadow-lg overflow-hidden hover:shadow-lg transition-all duration-300 rounded-lg">

                    <!-- Gambar -->
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        <img src="{{ $variety['image'] ?? asset('resources/img/sample-product.jpg') }}"
                            alt="{{ $variety['name'] }}"
                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    </div>

                    <!-- Konten -->
                    <div class="p-3">

                        <h3 class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2">
                            {{ $variety['name'] }}
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $variety['commodity']['name'] ?? '-' }}
                        </p>

                        <!-- Harga -->
                        <p class="text-sm text-green-700 font-semibold mt-2">
                            Rp {{ number_format($priceClean, 0, ',', '.') }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Minimum: {{ $variety['minimum_limit'] ?? 0 }} kg
                        </p>

                        <!-- Tombol Aksi -->
                        <div class="mt-3 grid grid-cols-3 gap-2">

                            <!-- Tombol Beli Langsung (GET atau route detail) -->
                            <a href="#" class="col-span-2 bg-gray-900 text-white text-center text-xs py-2 rounded-md hover:bg-black transition"
                            class="bg-gray-900 text-white text-center text-xs py-2 rounded-md hover:bg-black transition">
                            Beli Langsung
                            </a>

                            <!-- Tombol Keranjang -->
                            <button 
                                class="add-to-cart col-span-1 border border-gray-900 text-gray-900 text-xs py-2 rounded-md hover:bg-gray-900 hover:text-white transition"
                                data-id="{{ $variety['id'] }}"
                                data-nama="{{ $variety['name'] }}"
                                data-harga="{{ $priceClean }}"
                                data-gambar="{{ $variety['image'] ?? asset('resources/img/sample-product.jpg') }}"
                                data-minimum="{{ $variety['min_buy'] ?? 1 }}"
                            >
                                <i class="fa fa-cart-plus"></i>
                            </button>


                        </div>


                    </div>
                </div>

            @empty
                <p class="col-span-4 text-center text-gray-600">Tidak ada data varietas tersedia.</p>
            @endforelse
        </div>



    </div>
</section>

<script>
    const filterToggle = document.getElementById('filterToggle');
    const filterDropdown = document.getElementById('filterDropdown');

    filterToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        filterDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!filterDropdown.contains(e.target) && !filterToggle.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });

    document.querySelectorAll('.dropdown-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const filterValue = e.target.dataset.filter;
            alert(`Filter dipilih: ${filterValue}`);
            filterDropdown.classList.add('hidden');
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('content')

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto px-6 py-8 mt-18">

        <!-- CARD GLASS -->
        <div class="backdrop-blur-xl bg-white/40 border border-white/20 shadow-xl rounded-2xl p-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                <!-- ============================= -->
                <!--     LEFT: PRODUCT CONTENT    -->
                <!-- ============================= -->
                <div>

                    <!-- Breadcrumb -->
                    <div class="text-sm text-gray-600 mb-4">
                        <a href="/katalog" class="hover:text-gray-800">Katalog</a> /
                        <span class="text-gray-800">{{ $variety['commodity']['name'] }}</span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-3">
                        {{ $variety['name'] }}
                    </h1>

                    <!-- Harga -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl font-semibold text-gray-900">
                            {{ $variety['price_idr'] }}
                        </span>

                        <span class="ml-2 px-3 py-1 text-sm rounded-full 
                            {{ $variety['stock']['status'] === 'ready' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($variety['stock']['status']) }}
                        </span>
                    </div>

                    <p class="text-gray-700 leading-relaxed mb-4">
                        {!! nl2br(e($variety['description'])) !!}
                    </p>

                    <!-- Stok total -->
                    <div class="flex items-center text-green-700 mb-6 font-semibold">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Total Stok: {{ $variety['stock']['total_stock_kg'] }} kg
                    </div>

                    <!-- ============================= -->
                    <!--          STOCK TYPE          -->
                    <!-- ============================= -->
                    <div class="mb-6">
                        <div class="text-sm font-semibold text-gray-900 mb-2">
                            Pilih Jenis Stok
                        </div>

                        <div class="grid grid-cols-3 gap-4">

                            <!-- BS -->
                            <label class="group border rounded-lg p-4 cursor-pointer hover:border-green-600 peer-checked:border-green-700 bg-white/50 backdrop-blur-sm">
                                <input type="radio" name="stockType" value="BS" class="peer hidden">
                                <div class="font-semibold text-gray-900">BS</div>
                                <div class="text-gray-600 text-sm">Benih Sehat</div>
                                <div class="mt-2 text-green-700 font-semibold text-sm">
                                    {{ $variety['stock_bs_kg'] ?? 0 }} kg tersedia
                                </div>
                            </label>

                            <!-- FS -->
                            <label class="group border rounded-lg p-4 cursor-pointer hover:border-green-600 peer-checked:border-green-700 bg-white/50 backdrop-blur-sm">
                                <input type="radio" name="stockType" value="FS" class="peer hidden">
                                <div class="font-semibold text-gray-900">FS</div>
                                <div class="text-gray-600 text-sm">Foundation Stock</div>
                                <div class="mt-2 text-green-700 font-semibold text-sm">
                                    {{ $variety['stock']['FS'] ?? 0 }} kg tersedia
                                </div>
                            </label>

                            <!-- PLANT -->
                            <label class="group border rounded-lg p-4 cursor-pointer hover:border-green-600 peer-checked:border-green-700 bg-white/50 backdrop-blur-sm">
                                <input type="radio" name="stockType" value="PLANT" class="peer hidden">
                                <div class="font-semibold text-gray-900">PLANT</div>
                                <div class="text-gray-600 text-sm">Tanaman Hidup</div>
                                <div class="mt-2 text-green-700 font-semibold text-sm">
                                    {{ $variety['stock']['PLANT'] ?? 0 }} tersedia
                                </div>
                            </label>

                        </div>
                    </div>

                    <!-- BUTTON -->
                    <button
                        class="w-full bg-green-600 text-white py-3 rounded-xl text-lg font-semibold hover:bg-green-700 transition">
                        Tambah ke keranjang
                    </button>

                    <div class="flex justify-center mt-4 text-gray-600 text-sm">
                        🔒 Bergaransi & Terverifikasi BRIN Biogen
                    </div>

                </div>

                <!-- ============================= -->
                <!--      RIGHT: PRODUCT IMAGE    -->
                <!-- ============================= -->
                <div class="flex justify-center">
                    <img src="{{ $variety['image_url'] }}"
                         class="rounded-xl w-full max-w-md shadow-lg object-cover">
                </div>

            </div>
        </div>

    </div>

</body>

@endsection

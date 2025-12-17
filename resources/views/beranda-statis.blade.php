@extends('layouts.app')
@section('title', 'UPBS BRMP Biogen – Benih Unggul Nasional')

@section('content')

<!-- HERO SECTION -->
<section class="relative overflow-hidden bg-gradient-to-br from-green-600 via-emerald-500 to-teal-500">
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-28 text-center text-white">
        <span class="inline-block mb-4 px-4 py-1 text-sm rounded-full bg-white/20 backdrop-blur">
            🌱 Benih Unggul • Terjamin • Resmi
        </span>

        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 animate-fade-in">
            UPBS BRMP Biogen
        </h1>

        <p class="max-w-2xl mx-auto text-lg md:text-xl text-white/90 mb-10">
            Platform resmi penyedia benih unggul hasil riset Biogen.
            Cari, pesan, dan pantau benih dengan cara yang simpel & transparan.
        </p>

        <!-- Search -->
        <form action="#" method="GET"
            class="max-w-xl mx-auto flex items-center gap-3 bg-white/90 backdrop-blur rounded-2xl p-3 shadow-lg transition hover:scale-[1.02]">
            <input type="text" name="search"
                placeholder="Cari varietas benih (contoh: Biosoy, Inpari...)"
                class="flex-1 px-4 py-3 rounded-xl outline-none text-gray-700"
            />
            <button
                class="px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                Cari
            </button>
        </form>
    </div>
</section>

<!-- STATS SECTION -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Total Produksi Benih per Tahun
        </h2>
        <p class="text-gray-600 mb-12">
            Produksi benih dikelola secara terstandar dan berkelanjutan
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- BS -->
            <div class="bg-white rounded-3xl p-8 shadow hover:shadow-xl transition">
                <div class="text-5xl mb-4">🌾</div>
                <h3 class="text-xl font-semibold mb-2">Benih Sebar (BS)</h3>
                <p class="text-4xl font-bold text-green-600 mb-2">±120 Ton</p>
                <p class="text-gray-500 text-sm">
                    Benih siap tanam untuk kebutuhan produksi luas
                </p>
            </div>

            <!-- FS -->
            <div class="bg-white rounded-3xl p-8 shadow hover:shadow-xl transition">
                <div class="text-5xl mb-4">🌱</div>
                <h3 class="text-xl font-semibold mb-2">Foundation Seed (FS)</h3>
                <p class="text-4xl font-bold text-green-600 mb-2">±75 Ton</p>
                <p class="text-gray-500 text-sm">
                    Benih dasar dengan kemurnian genetik tinggi
                </p>
            </div>

            <!-- PL -->
            <div class="bg-white rounded-3xl p-8 shadow hover:shadow-xl transition">
                <div class="text-5xl mb-4">🧬</div>
                <h3 class="text-xl font-semibold mb-2">Penjenis Lanjut (PL)</h3>
                <p class="text-4xl font-bold text-green-600 mb-2">±30 Ton</p>
                <p class="text-gray-500 text-sm">
                    Benih sumber untuk perbanyakan lanjutan
                </p>
            </div>
        </div>
    </div>
</section>

<!-- PRODUCT SECTION -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Varietas Unggulan Biogen
            </h2>
            <p class="text-gray-600">
                Ringkasan karakter penting yang perlu diketahui publik
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Card 1 -->
            <div class="group bg-gray-50 rounded-3xl p-6 hover:shadow-xl transition">
                <span class="text-xs px-3 py-1 rounded-full bg-green-100 text-green-700">
                    Padi Inbrida
                </span>
                <h3 class="mt-4 text-xl font-bold">Bioprima Agritan</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Adaptif & hasil tinggi
                </p>

                <ul class="mt-4 text-sm text-gray-600 space-y-1">
                    <li>🌾 Tinggi tanaman ±107 cm</li>
                    <li>🌿 Anakan produktif ±24</li>
                    <li>⚖️ Rata-rata hasil ±8,6 t/ha</li>
                    <li>🍚 Tekstur nasi: sedang</li>
                </ul>
            </div>

            <!-- Card 2 -->
            <div class="group bg-gray-50 rounded-3xl p-6 hover:shadow-xl transition">
                <span class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">
                    Padi Inbrida
                </span>
                <h3 class="mt-4 text-xl font-bold">Bioryza Agritan</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Gen tahan penyakit
                </p>

                <ul class="mt-4 text-sm text-gray-600 space-y-1">
                    <li>🌱 Tinggi ±84 cm</li>
                    <li>🌾 Anakan ±26</li>
                    <li>⚖️ Hasil ±5,8 t/ha</li>
                    <li>🧬 Tahan HDB & blas</li>
                </ul>
            </div>

            <!-- Card 3 -->
            <div class="group bg-gray-50 rounded-3xl p-6 hover:shadow-xl transition">
                <span class="text-xs px-3 py-1 rounded-full bg-lime-100 text-lime-700">
                    Padi Inbrida
                </span>
                <h3 class="mt-4 text-xl font-bold">Biomonas Agritan</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Cocok lahan <600 mdpl
                </p>

                <ul class="mt-4 text-sm text-gray-600 space-y-1">
                    <li>🌿 Tinggi ±92 cm</li>
                    <li>🌾 Anakan ±22</li>
                    <li>⚖️ Hasil ±5,3 t/ha</li>
                    <li>💧 Moderat tahan kekeringan</li>
                </ul>
            </div>

            <!-- Card 4 -->
            <div class="group bg-gray-50 rounded-3xl p-6 hover:shadow-xl transition">
                <span class="text-xs px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">
                    Kedelai
                </span>
                <h3 class="mt-4 text-xl font-bold">Biosoy 1</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Protein tinggi
                </p>

                <ul class="mt-4 text-sm text-gray-600 space-y-1">
                    <li>🌱 Tinggi ±41 cm</li>
                    <li>⚖️ Hasil ±2,7 t/ha</li>
                    <li>🥜 Protein ±39%</li>
                    <li>🛡️ Tahan karat daun</li>
                </ul>
            </div>

            <!-- Card 5 -->
            <div class="group bg-gray-50 rounded-3xl p-6 hover:shadow-xl transition">
                <span class="text-xs px-3 py-1 rounded-full bg-orange-100 text-orange-700">
                    Kedelai
                </span>
                <h3 class="mt-4 text-xl font-bold">Biosoy 2</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Produksi stabil
                </p>

                <ul class="mt-4 text-sm text-gray-600 space-y-1">
                    <li>🌾 Hasil ±2,6 t/ha</li>
                    <li>🥜 Protein ±40%</li>
                    <li>⚖️ Bobot 100 biji ±22 g</li>
                </ul>
            </div>

            <!-- Card 6 -->
            <div class="group bg-gray-50 rounded-3xl p-6 hover:shadow-xl transition">
                <span class="text-xs px-3 py-1 rounded-full bg-red-100 text-red-700">
                    Cabai Merah
                </span>
                <h3 class="mt-4 text-xl font-bold">Agrihorti Carvi</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Produksi tinggi
                </p>

                <ul class="mt-4 text-sm text-gray-600 space-y-1">
                    <li>🌶️ Panjang buah ±12 cm</li>
                    <li>⚖️ Hasil ±21 t/ha</li>
                    <li>🛡️ Tahan virus belang</li>
                </ul>
            </div>

        </div>
    </div>
</section>

@endsection

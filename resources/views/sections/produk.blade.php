<section class="relative py-16 bg-white/60 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <h2 class="text-2xl font-bold text-gray-900 mb-10 text-center">Produk Tersedia</h2>

        <!-- Grid Produk -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach (range(1, 8) as $i)
                <div
                    class="group relative bg-white/20 backdrop-blur-lg border border-white/40 rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl">

                    <!-- Gambar -->
                    <div class="h-40 bg-gray-200 flex items-center justify-center overflow-hidden">
                        <img src="{{ Vite::asset('resources/img/sample-product.jpg') }}" alt="Produk {{ $i }}"
                            class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
                    </div>

                    <!-- Konten -->
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-gray-900 text-base mb-2">Produk Contoh {{ $i }}</h3>
                        <p class="text-sm text-gray-700 mb-3">
                            Stok:
                            <span class="font-semibold text-green-700">BS: {{ rand(10, 50) }}</span> |
                            <span class="font-semibold text-blue-700">FS: {{ rand(5, 30) }}</span>
                        </p>

                        <a href="#"
                            class="inline-block bg-gray-900/80 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-900/90 backdrop-blur-md transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

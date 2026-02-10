<section class="relative py-16 bg-gradient-to-b from-[#F3F9F5] to-white overflow-hidden animate-fadeIn">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">

        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center mt-4">Komoditas</h2>

        <!-- Tombol scroll kiri -->
        <button id="scrollLeft"
            class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 bg-white/60 backdrop-blur-md border border-gray-200 shadow-md p-3 rounded-full hover:bg-gray-100 transition z-20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-5 h-5 text-gray-800">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>

        <!-- Scroll Container -->
        <div id="categoryScroll" class="mt-4 flex gap-6 overflow-x-auto scroll-smooth no-scrollbar pb-4 relative z-10">
            @forelse ($commodities as $commodity)
                <div
                    class="group relative min-w-[180px] flex-shrink-0 rounded-2xl border border-gray-100 bg-white shadow-md p-6 text-center hover:scale-105 transition-all duration-300 cursor-pointer">
                    
                    <div class="h-24 w-24 mx-auto mb-3 flex items-center justify-center overflow-hidden rounded-full bg-gray-50">
                        @php
                            $imagePath = $commodity['image'] ?? null;
                            $imageUrl = $imagePath
                                ? rtrim(config('app.url_dev_admin'), '/') . '/storage/' . ltrim($imagePath, '/')
                                : 'https://placehold.co/400x300?text=No+Image';
                        @endphp
                        <img src="{{ $imageUrl}}"
                             alt="{{ $commodity['name'] }}"
                             class="object-contain w-full h-full group-hover:scale-110 transition-transform duration-300"
                             loading="lazy"
                             onerror="this.src='https://placehold.co/96x96?text=No+Img'">
                    </div>
                    <!-- Nama Komoditas -->
                    <p class="font-semibold text-gray-800">{{ $commodity['name'] }}</p>

                    <!-- Tombol muncul saat hover -->
                    <div
                        class="absolute inset-0 bg-white/60 backdrop-blur-md rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <a href="{{ url('/katalog?commodity=' . strtolower($commodity['slug'])) }}"
                            class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-gray-800 transition-all duration-200">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600 w-full">Belum ada kategori produk tersedia.</p>
            @endforelse
        </div>

        <!-- Tombol scroll kanan -->
        <button id="scrollRight"
            class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 bg-white/60 backdrop-blur-md border border-gray-200 shadow-md p-3 rounded-full hover:bg-gray-100 transition z-20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-5 h-5 text-gray-800">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5" />
            </svg>
        </button>

    </div>
</section>

<script>
    const scrollContainer = document.getElementById('categoryScroll');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    scrollLeftBtn.addEventListener('click', () => {
        scrollContainer.scrollBy({ left: -300, behavior: 'smooth' });
    });

    scrollRightBtn.addEventListener('click', () => {
        scrollContainer.scrollBy({ left: 300, behavior: 'smooth' });
    });

    // Tampilkan tombol scroll hanya jika ada overflow
    const toggleScrollButtons = () => {
        if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
            scrollLeftBtn.classList.remove('hidden');
            scrollRightBtn.classList.remove('hidden');
        } else {
            scrollLeftBtn.classList.add('hidden');
            scrollRightBtn.classList.add('hidden');
        }
    };
    toggleScrollButtons();
    window.addEventListener('resize', toggleScrollButtons);
</script>

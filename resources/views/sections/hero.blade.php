<section class="relative min-h-[90vh] flex items-center overflow-hidden">
    <!-- 🌿 Background Hijau Gradasi -->
    <div class="absolute inset-0">

        <!-- Gradasi hijau dari kiri memudar ke tengah -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#B4DEBD] via-[#B4DEBD]/70 to-transparent z-10"></div>

        <!-- 🌾 Gambar padi, tampil hanya di layar besar -->
         <img src="{{ Vite::asset('resources/img/herolp.jpeg') }}"
            alt="Hero Padi"
            class="hidden md:block absolute inset-0 w-full h-full object-cover object-right lg:object-right-top opacity-90"
            onerror="this.src='https://images.unsplash.com/photo-1503899036084-c55cdd92da26?q=80&w=1920&auto=format&fit=crop'">

    </div>

    <!-- 🌾 Konten -->
    <div class="relative z-20 max-w-7xl mx-auto mt-8 px-6 lg:px-8 
                grid grid-cols-1 lg:grid-cols-2 gap-12 items-center text-gray-900">

        <!-- 🧬 Kiri: Teks & Form -->
        <div class="bg-white/20 backdrop-blur-lg border border-white/40 
                    rounded-3xl shadow-2xl p-8 sm:p-10 text-center lg:text-left">

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight">
                Temukan Benih Unggul & Inovasi Pertanian Terbaik
            </h1>

            <p class="mt-5 text-base sm:text-lg text-gray-800 leading-relaxed">
                UPBS BRMP Biogen menghadirkan solusi berkualitas untuk pertanian masa depan.
                Jelajahi katalog produk kami dan temukan apa yang kamu butuhkan.
            </p>

            <!-- 🔍 Search Bar -->
            <form action="{{ route('search') }}" method="GET" class="mt-8">
                <div class="relative max-w-lg mx-auto lg:mx-0">

                    <input type="text"
                           name="search"
                           placeholder="Cari produk, varietas, atau katalog..."
                           autocomplete="off"
                           class="w-full rounded-3xl border border-white/50 bg-white/40 
                                  backdrop-blur-md py-3 pl-5 pr-12 text-gray-800 
                                  placeholder-gray-600 shadow-inner focus:outline-none 
                                  focus:ring-2 focus:ring-gray-900/30 transition" />

                    <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 
                                   bg-gray-900 text-white rounded-3xl p-2.5 
                                   hover:bg-gray-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 4.5 4.5a7.5 7.5 0 0 0 12.15 12.15Z" />
                        </svg>
                    </button>

                    <!-- 📌 Container untuk autocomplete suggestions -->
                    <div id="suggestions"
                         class="absolute left-0 right-0 mt-2 bg-white rounded-xl 
                                shadow-lg border hidden z-50 max-h-60 overflow-y-auto">
                    </div>
                </div>
            </form>

            <!-- 🎯 Tombol Aksi -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">

                <a href="{{ route('katalog') }}"
                   class="rounded-lg bg-gray-900/90 px-6 py-3 text-sm font-semibold 
                          text-white shadow-lg hover:bg-gray-800 transition-all duration-300 
                          backdrop-blur-md">
                    Lihat Katalog
                </a>

                <a href="#"
                   class="text-sm font-semibold text-gray-800 hover:text-gray-900 transition">
                    Selengkapnya <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        <!-- 🖼️ Kolom kanan dikosongkan agar gambar hero tetap dominan -->
        <div class="hidden lg:block"></div>

    </div>
</section>
<script>
    const searchInput = document.querySelector('input[name="search"]');
    const box = document.querySelector('#suggestions');

    let timer = null;

    searchInput.addEventListener('keyup', function () {
        const q = this.value.trim();

        clearTimeout(timer);

        if (q.length < 2) {
            box.classList.add('hidden');
            return;
        }

        timer = setTimeout(() => {
            fetch(`/search-suggest?q=${q}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        box.innerHTML = `
                            <div class="p-3 text-gray-600">Tidak ada hasil...</div>
                        `;
                        box.classList.remove('hidden');
                        return;
                    }

                    let html = '';

                    data.forEach(item => {
                        html += `
                            <a href="/katalog?search=${encodeURIComponent(item.name)}"
                                class="block px-4 py-3 hover:bg-gray-100 cursor-pointer">
                                <div class="font-medium text-gray-900">${item.name}</div>
                                <div class="text-xs text-gray-500">${item.type}</div>
                            </a>
                        `;
                    });

                    box.innerHTML = html;
                    box.classList.remove('hidden');
                });
        }, 250); // debounce 250ms
    });

    // klik di luar → sembunyikan box
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target)) {
            box.classList.add('hidden');
        }
    });
</script>

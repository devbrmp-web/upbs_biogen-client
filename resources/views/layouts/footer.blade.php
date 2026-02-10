<footer class="relative bg-[#B4DEBD]/60 backdrop-blur-lg border-t border-white/40 mt-20 z-10">
  <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-10 text-gray-800">

    <!-- 🧬 Logo dan Deskripsi -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="UPBS BRMP Biogen" class="h-10 w-auto object-contain">
        <h2 class="text-lg font-bold">UPBS BRMP Biogen</h2>
      </div>
      <p class="text-sm leading-relaxed text-gray-700">
        Unit Pengelola Benih Sumber (UPBS) Balai Penelitian Bioteknologi dan Sumber Daya Genetik Pertanian.
        Menyediakan benih unggul berkualitas untuk mendukung ketahanan pangan Indonesia.
      </p>
    </div>

    <!-- 🧭 Navigasi -->
    <div>
      <h3 class="font-semibold mb-4 text-gray-900">Navigasi</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/" class="hover:text-gray-900 transition">Beranda</a></li>
        <li><a href="/katalog" class="hover:text-gray-900 transition">Katalog</a></li>
        <li><a href="/tentang-kami" class="hover:text-gray-900 transition">Tentang Kami</a></li>
        <li><a href="/kontak" class="hover:text-gray-900 transition">Kontak</a></li>
      </ul>
    </div>

    <!-- 🪴 Bantuan -->
    <div>
      <h3 class="font-semibold mb-4 text-gray-900">Bantuan</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="#" class="hover:text-gray-900 transition">FAQ</a></li>
        <li><a href="#" class="hover:text-gray-900 transition">Kebijakan Privasi</a></li>
        <li><a href="#" class="hover:text-gray-900 transition">Syarat & Ketentuan</a></li>
        <li><a href="#" class="hover:text-gray-900 transition">Dukungan Teknis</a></li>
      </ul>
    </div>

    <!-- 📬 Kontak -->
    <div>
      <h3 class="font-semibold mb-4 text-gray-900">Hubungi Kami</h3>
      <ul class="space-y-2 text-sm">
        <li>📍 Jl. Tentara Pelajar No.3A, Bogor</li>
        <li>📞 (0251) 8337975</li>
        <li>✉️ upbs.biogen@pertanian.go.id</li>
      </ul>

      <div class="flex gap-3 mt-4">
        <a href="#" class="w-8 h-8 flex items-center justify-center bg-white/30 rounded-full hover:bg-white/50 transition">
          <i class="fab fa-facebook-f text-gray-900"></i>
        </a>
        <a href="#" class="w-8 h-8 flex items-center justify-center bg-white/30 rounded-full hover:bg-white/50 transition">
          <i class="fab fa-instagram text-gray-900"></i>
        </a>
        <a href="#" class="w-8 h-8 flex items-center justify-center bg-white/30 rounded-full hover:bg-white/50 transition">
          <i class="fab fa-twitter text-gray-900"></i>
        </a>
      </div>
    </div>

  </div>

  <!-- 🔸 Garis bawah -->
  <div class="border-t border-white/40 text-center py-6 text-sm text-gray-700 bg-white/10 backdrop-blur-md">
    © {{ date('Y') }} UPBS BRMP Biogen. Semua hak cipta dilindungi.
  </div>
</footer>

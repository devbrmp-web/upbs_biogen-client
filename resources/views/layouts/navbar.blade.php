<nav class="fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-white/30 backdrop-blur-xl border border-white/20 shadow-lg rounded-3xl px-6 py-3 w-[90%] md:w-[80%] flex items-center justify-between">
  <!-- 🧬 Logo -->
  <div class="flex items-center space-x-2">
    <div class="w-10 h-10 overflow-hidden rounded-full flex items-center justify-center bg-white/50">
      <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo" class="object-contain w-8 h-8">
    </div>
    <span class="font-semibold text-gray-900 text-lg hidden sm:block">UPBS BRMP BIOGEN</span>
  </div>

  <!-- 🌿 Menu Tengah (desktop) -->
  <ul class="hidden md:flex space-x-8 text-gray-800 font-medium">
    <li>
      <a href="{{ route('home') }}" 
         class="{{ request()->routeIs('home') ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }} transition">
         Beranda
      </a>
    </li>
    <li>
      <a href="{{ route('katalog') }}" 
         class="{{ request()->routeIs('katalog') ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }} transition">
         Katalog
      </a>
    </li>
    <li>
      <a href="{{ route('about') }}" 
         class="{{ request()->routeIs('about') ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }} transition">
         Tentang Kami
      </a>
    </li>
    <li>
      <a href="{{ route('cek-pesanan') }}"
         class="hover:text-indigo-600 transition">
         Cek Pesanan
      </a>
    </li>
  </ul>

  <!-- 🌾 Ikon kanan -->
  <div class="flex items-center space-x-4">
    <!-- 🛒 Keranjang (FA Icon) -->
     <a href="{{ route('cart.show') }}">
      <div id="cartIcon" class="relative cursor-pointer" >
        <i class="fa fa-shopping-cart text-gray-800 text-xl hover:text-indigo-600 transition"></i>
        <span id="cartBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full px-[5px] py-[1px]" style="display:none">0</span>
      </div>
    </a>
    <!-- 🍔 Menu mobile -->
    <button id="menuToggle" class="md:hidden focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke-width="2" stroke="currentColor" class="w-7 h-7 text-gray-800">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- 📱 Dropdown Mobile -->
  <div id="mobileMenu"
       class="hidden absolute top-full right-0 mt-3 w-56 bg-white/95 backdrop-blur-xl  rounded-2xl p-4 md:hidden">
    <ul class="space-y-3 text-gray-800 font-medium">
      <li>
        <a href="{{ route('home') }}" 
           class="block px-3 py-2 rounded-lg hover:bg-[#B4DEBD]/40 transition {{ request()->routeIs('home') ? 'text-indigo-600 font-semibold' : '' }}">
           Beranda
        </a>
      </li>
      <li>
        <a href="{{ route('katalog') }}" 
           class="block px-3 py-2 rounded-lg hover:bg-[#B4DEBD]/40 transition {{ request()->routeIs('katalog') ? 'text-indigo-600 font-semibold' : '' }}">
           Katalog
        </a>
      </li>
      <li>
        <a href="{{ route('about') }}"
           class="block px-3 py-2 rounded-lg hover:bg-[#B4DEBD]/40 transition">
           Tentang Kami
        </a>
      </li>
      <li>
        <a href="{{ route('cek-pesanan') }}"
           class="block px-3 py-2 rounded-lg hover:bg-[#B4DEBD]/40 transition">
           Cek Pesanan
        </a>
      </li>

      <!-- 🛒 Keranjang di Mobile -->
      <li>
        <a href="{{ route('cart.show') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-[#B4DEBD]/40 transition">
          <i class="fa fa-shopping-cart text-gray-800"></i>
          <span>Keranjang</span>
        </a>
      </li>
    </ul>
  </div>
</nav>
<script>
  (function(){
    function readCartCount() {
      try {
        const raw = localStorage.getItem('upbs_cart_v2');
        if (!raw) return 0;
        const obj = JSON.parse(raw);
        const items = Array.isArray(obj?.items) ? obj.items : [];
        return items.length;
      } catch { return 0; }
    }
    function renderCartBadge() {
      const el = document.getElementById('cartBadge');
      if (!el) return;
      const count = readCartCount();
      if (count > 0) {
        el.textContent = count;
        el.style.display = 'inline';
      } else {
        el.style.display = 'none';
      }
    }
    document.addEventListener('DOMContentLoaded', renderCartBadge);
    window.addEventListener('cart-updated', renderCartBadge);
    window.addEventListener('storage', (e) => {
      if (e.key === 'upbs_cart_v2') renderCartBadge();
    });
  })();
</script>

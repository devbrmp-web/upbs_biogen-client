<nav class="hidden md:flex fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-white/30 backdrop-blur-xl border border-white/20 shadow-lg rounded-3xl px-6 py-3 w-[90%] md:w-[80%] items-center justify-between">
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
  <div class="hidden md:flex items-center space-x-4">
    <!-- 🛒 Keranjang (FA Icon) -->
     <a href="{{ route('cart.show') }}">
      <div id="cartIcon" class="relative cursor-pointer" >
        <i class="fa fa-shopping-cart text-gray-800 text-xl hover:text-indigo-600 transition"></i>
        <span id="cartBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full px-[5px] py-[1px]" style="display:none">0</span>
      </div>
    </a>
  </div>
</nav>

<!-- 📱 Mobile Logo Only (Simple Header) -->
<div class="fixed top-4 left-4 z-40 md:hidden">
    <div class="w-10 h-10 overflow-hidden rounded-full flex items-center justify-center bg-white/50 backdrop-blur-md shadow-sm border border-white/30">
        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo" class="object-contain w-8 h-8">
    </div>
</div>

<!-- 📱 Mobile Bottom Navigation (Floating Curved Glass) -->
<nav class="fixed bottom-6 left-4 right-4 z-50 md:hidden h-20">
    <!-- Glass Background with Concave Cutout -->
    <div class="absolute inset-0 bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/40"
         style="-webkit-mask-image: radial-gradient(circle 42px at 50% -10px, transparent 99%, black 100%); mask-image: radial-gradient(circle 42px at 50% -10px, transparent 99%, black 100%);">
    </div>

    <!-- Menu Items Container -->
    <div class="relative h-full grid grid-cols-5 items-center text-[10px] font-bold text-slate-500">
        
        <!-- 1. Beranda -->
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 group {{ request()->routeIs('home') ? 'text-emerald-700' : 'hover:text-emerald-600' }}">
            <div class="p-1.5 rounded-xl group-hover:bg-white/40 transition-colors {{ request()->routeIs('home') ? 'bg-white/50' : '' }}">
                <i class="fa-solid fa-house text-lg mb-0.5 {{ request()->routeIs('home') ? 'scale-110' : '' }} transition-transform"></i>
            </div>
            <span class="scale-90 tracking-wide">Beranda</span>
        </a>

        <!-- 2. Katalog -->
        <a href="{{ route('katalog') }}" class="flex flex-col items-center gap-0.5 group {{ request()->routeIs('katalog') ? 'text-emerald-700' : 'hover:text-emerald-600' }}">
            <div class="p-1.5 rounded-xl group-hover:bg-white/40 transition-colors {{ request()->routeIs('katalog') ? 'bg-white/50' : '' }}">
                <i class="fa-solid fa-box-open text-lg mb-0.5 {{ request()->routeIs('katalog') ? 'scale-110' : '' }} transition-transform"></i>
            </div>
            <span class="scale-90 tracking-wide">Katalog</span>
        </a>

        <!-- 3. Center Cart Button (Floating) -->
        <div class="relative flex justify-center h-full pointer-events-none">
            <a href="{{ route('cart.show') }}" class="absolute -top-8 w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full shadow-lg shadow-emerald-500/40 flex items-center justify-center text-white border-4 border-white/20 backdrop-blur-sm transition-transform hover:-translate-y-1 hover:scale-105 pointer-events-auto">
                <div class="relative">
                    <i class="fa-solid fa-cart-shopping text-2xl"></i>
                    <span id="mobileCartBadge" class="absolute -top-2 -right-2 bg-rose-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white shadow-sm" style="display:none">0</span>
                </div>
            </a>
            <!-- Label for center item -->
        </div>

        <!-- 4. Cek Pesanan -->
        <a href="{{ route('cek-pesanan') }}" class="flex flex-col items-center gap-0.5 group {{ request()->routeIs('cek-pesanan') ? 'text-emerald-700' : 'hover:text-emerald-600' }}">
            <div class="p-1.5 rounded-xl group-hover:bg-white/40 transition-colors {{ request()->routeIs('cek-pesanan') ? 'bg-white/50' : '' }}">
                <i class="fa-solid fa-truck-fast text-lg mb-0.5 {{ request()->routeIs('cek-pesanan') ? 'scale-110' : '' }} transition-transform"></i>
            </div>
            <span class="scale-90 tracking-wide whitespace-nowrap">Pesanan</span>
        </a>

        <!-- 5. Tentang Kami -->
        <a href="{{ route('about') }}" class="flex flex-col items-center gap-0.5 group {{ request()->routeIs('about') ? 'text-emerald-700' : 'hover:text-emerald-600' }}">
            <div class="p-1.5 rounded-xl group-hover:bg-white/40 transition-colors {{ request()->routeIs('about') ? 'bg-white/50' : '' }}">
                <i class="fa-solid fa-circle-info text-lg mb-0.5 {{ request()->routeIs('about') ? 'scale-110' : '' }} transition-transform"></i>
            </div>
            <span class="scale-90 tracking-wide whitespace-nowrap">Tentang</span>
        </a>
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
      const count = readCartCount();
      
      // Desktop Badge
      const el = document.getElementById('cartBadge');
      if (el) {
        if (count > 0) {
          el.textContent = count;
          el.style.display = 'inline';
        } else {
          el.style.display = 'none';
        }
      }

      // Mobile Badge
      const elMobile = document.getElementById('mobileCartBadge');
      if (elMobile) {
        if (count > 0) {
            elMobile.textContent = count;
            elMobile.style.display = 'flex';
        } else {
            elMobile.style.display = 'none';
        }
      }
    }
    document.addEventListener('DOMContentLoaded', renderCartBadge);
    window.addEventListener('cart-updated', renderCartBadge);
    window.addEventListener('storage', (e) => {
      if (e.key === 'upbs_cart_v2') renderCartBadge();
    });
  })();
</script>

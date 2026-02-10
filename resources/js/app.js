import './bootstrap';
import './categoryScroll.js';
import './cart.js';
import './render-cart.js';
import './services/cache.js';
import '@fortawesome/fontawesome-free/css/all.min.css';

// Animasi halaman: jalankan sekali per navigasi
document.addEventListener('DOMContentLoaded', () => {
  try {
    document.body.classList.remove('page-animated');
    // Beri waktu animasi halaman selesai (>=0.5s)
    setTimeout(() => {
      document.body.classList.add('page-animated');
    }, 700);
  } catch (e) {}
});


// Category Scroll

// ===== Scroll Kategori Produk =====
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("categoryScroll");
  const btnLeft = document.getElementById("scrollLeft");
  const btnRight = document.getElementById("scrollRight");

  if (!container || !btnLeft || !btnRight) return;

  const scrollAmount = 300; // seberapa jauh bergeser per klik
  const scrollSpeed = 10;   // jeda animasi (ms)
  const scrollStep = 20;    // langkah per frame

  function smoothScroll(direction) {
    let scrolled = 0;
    const slide = setInterval(() => {
      container.scrollLeft += direction * scrollStep;
      scrolled += scrollStep;
      if (scrolled >= scrollAmount) clearInterval(slide);
    }, scrollSpeed);
  }

  btnLeft.addEventListener("click", () => smoothScroll(-1));
  btnRight.addEventListener("click", () => smoothScroll(1));
});


// cart
document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cartIcon');
    const cartModal = document.getElementById('cartModal');
    const closeCart = document.getElementById('closeCart');

    if (cartIcon && cartModal && closeCart) {
        cartIcon.addEventListener('click', () => {
            cartModal.classList.remove('hidden');
            cartModal.classList.add('flex');
        });

        closeCart.addEventListener('click', () => {
            cartModal.classList.add('hidden');
            cartModal.classList.remove('flex');
        });

        cartModal.addEventListener('click', (e) => {
            if (e.target === cartModal) {
                cartModal.classList.add('hidden');
                cartModal.classList.remove('flex');
            }
        });
  }
});

// Service Worker registration (image caching)
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  });
}
// Dev toggle: hide katalog query string after navigation based on env
document.addEventListener('DOMContentLoaded', () => {
  const hide = (import.meta.env.VITE_HIDE_FILTER_QUERY === 'true') || (import.meta.env.VITE_HIDE_FILTER_QUERY === '1');
  if (hide && window.location.pathname === '/katalog' && window.location.search) {
    history.replaceState(null, '', '/katalog');
  }

  // Lightweight loading state on seed class change (no heavy DOM updates)
  document.addEventListener('change', (e) => {
    if (e.target && e.target.id === 'seed-class-select') {
      document.body.classList.add('cursor-wait');
      setTimeout(() => document.body.classList.remove('cursor-wait'), 1500);
    }
  });
});
//navbar
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById("menuToggle");
  const mobileMenu = document.getElementById("mobileMenu");

  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
      mobileMenu.classList.toggle("animate-fadeIn");
    });

    // Klik di luar menu menutup dropdown
    document.addEventListener("click", (e) => {
      if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.classList.add("hidden");
      }
    });
  }
});

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ Vite::asset('resources/img/logo.png') }}">
    <title>@yield('title', 'UPBS BRMP Biogen')</title>
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js', 'resources/js/cart.js'])

    {{-- Premium UI Libraries --}}
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        #nprogress .bar { background: #10b981 !important; height: 3px !important; }
        #nprogress .spinner-icon { border-top-color: #10b981 !important; border-left-color: #10b981 !important; }
        
        /* Custom Scrollbar for Premium Feel */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #059669; }

        /* SweetAlert2 Emerald Customization */
        .swal2-styled.swal2-confirm { background-color: #10b981 !important; border-radius: 1rem !important; }
        .swal2-popup { border-radius: 2rem !important; font-family: inherit !important; }
        
        /* AOS Tweaks */
        [data-aos] { pointer-events: none; }
        [data-aos].aos-animate { pointer-events: auto; }
    </style>
  </head>
   

   
    
  <body class="bg-[#fdfcfb] text-slate-800 font-sans min-h-screen flex flex-col antialiased">
    {{-- Global Background Elements --}}
    <div class="fixed top-0 left-0 w-full h-full -z-20 opacity-[0.03] pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/natural-paper.png');"></div>
    <div class="fixed -top-24 -left-24 w-96 h-96 bg-emerald-200/20 rounded-full blur-[120px] -z-20 pointer-events-none"></div>
    <div class="fixed -bottom-24 -right-24 w-[500px] h-[500px] bg-teal-200/20 rounded-full blur-[120px] -z-20 pointer-events-none"></div>

    {{-- Navbar --}}
    @include('layouts.navbar')
    {{-- Konten Halaman --}}
    <main class="no-overscroll flex-grow w-full relative z-0 pb-32">
      @yield('content')
      @include('components.cart-modal')
      @include('components.checkout-popup')

      @include('components.buy-modal')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
    
    {{-- Premium UI Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
            easing: 'ease-out-quad'
        });

        // Initialize NProgress
        NProgress.configure({ showSpinner: false, trickleSpeed: 200 });
        
        // Handle navigation start
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.startsWith('#') && !link.href.includes('whatsapp') && !link.target) {
                NProgress.start();
            }
        });

        window.addEventListener('beforeunload', function() {
            NProgress.start();
        });
        
        window.addEventListener('load', function() {
            NProgress.done();
        });
        
        // Ensure NProgress finishes on DOMContentLoaded as a fallback
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => NProgress.done(), 500);
        });

        // Global Alert Helper
        window.toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Replace native confirm with a promise-based swal
        window.confirmAction = function(title, text, icon = 'warning') {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
        };
    </script>
    
    @stack('scripts')
  </body>
</html>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ Vite::asset('resources/img/logo.png') }}">
    <title>@yield('title', 'UPBS BRMP Biogen')</title>
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js', 'resources/js/cart.js'])
  </head>
   

   
    
  <body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">
    {{-- Navbar --}}
    @include('layouts.navbar')
    {{-- Konten Halaman --}}
    <main class="no-overscroll flex-grow w-full relative z-0">
      @yield('content')
      @include('components.cart-modal')
      @include('components.checkout-popup')

      @include('components.buy-modal')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
    
    @stack('scripts')
  </body>
</html>

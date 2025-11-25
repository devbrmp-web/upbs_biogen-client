<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'UPBS BRMP Biogen')</title>
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js', 'resources/js/cart.js'])
  </head>
   

   
    
  <body class="bg-gray-50 text-gray-800 font-sans">
    {{-- Navbar --}}
    @include('layouts.navbar')
    {{-- Konten Halaman --}}
    <main class="no-overscroll">
      @yield('content')
      @include('components.cart-modal')
      @include('components.checkout-popup')

      @include('components.buy-modal')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
  </body>
</html>

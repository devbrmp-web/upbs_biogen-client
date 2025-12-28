@extends('layouts.app')

@section('title', 'Beranda - UPBS BRMP Biogen')

@section('content')
  @include('components.tutorial-overlay', ['steps' => [
      ['title' => 'Selamat Datang!', 'content' => 'Selamat datang di aplikasi UPBS Biogen. Ini adalah tempat terbaik untuk mencari benih sumber berkualitas.'],
      ['title' => 'Cari Benih', 'content' => 'Gunakan fitur pencarian atau jelajahi katalog kami untuk menemukan varietas yang Anda butuhkan.'],
      ['title' => 'Pemesanan Mudah', 'content' => 'Pesan langsung lewat aplikasi dan pantau status pesanan Anda kapan saja.']
  ]])

  <div class="page-animate-fadeIn">
  {{-- Hero --}}
  @include('sections.hero')

  {{-- Kategori Produk --}}
  @include('sections.kategori')

  {{-- Informasi Benih Unggul (Buku Saku) --}}
  @include('sections.informasi-benih', ['infoVarietas' => $infoVarietas])
  </div>
@endsection

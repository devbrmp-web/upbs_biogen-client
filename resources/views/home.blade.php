@extends('layouts.app')

@section('title', 'Beranda - UPBS BRMP Biogen')

@section('content')
  <div class="page-animate-fadeIn">
  {{-- Hero --}}
  @include('sections.hero')

  {{-- Kategori Produk --}}
  @include('sections.kategori')

  {{-- Produk --}}
  @include('sections.produk')
  </div>
@endsection

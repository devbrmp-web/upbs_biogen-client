@extends('layouts.app')

@section('title', 'Beranda - UPBS BRMP Biogen')

@section('content')
  {{-- Hero --}}
  @include('sections.hero')

  {{-- Kategori Produk --}}
  @include('sections.kategori')

  {{-- Produk --}}
  @include('sections.produk')
@endsection

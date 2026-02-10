@extends('layouts.app')
@section('title', 'Pembayaran Gagal')
@section('content')
<section class="pt-28 pb-16 bg-gray-50 min-h-screen">
  <div class="max-w-3xl mx-auto px-6">
    <div class="bg-white shadow-lg rounded-xl p-6">
      <h1 class="text-2xl font-bold text-red-700">Pembayaran Gagal</h1>
      <p class="mt-2 text-gray-700">Maaf, pembayaran Anda tidak berhasil.</p>
      @if(!empty($order_code))
        <p class="mt-3 text-sm text-gray-600">Kode Pesanan: <span class="font-semibold">{{ $order_code }}</span></p>
      @endif
      <p class="mt-3 text-sm text-gray-600">{{ $message ?? 'Silakan coba kembali atau hubungi kami.' }}</p>
      <div class="mt-6">
        <a href="/checkout" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kembali ke Checkout</a>
        <a href="/katalog" class="ml-3 inline-block bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800">Belanja Produk</a>
      </div>
    </div>
  </div>
</section>
@endsection

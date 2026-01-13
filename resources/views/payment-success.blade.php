@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')
@section('content')
<section class="pt-28 pb-16 bg-gray-50 min-h-screen page-animate-zoomIn">
  <div class="max-w-3xl mx-auto px-6">
    <div class="bg-white shadow-lg rounded-xl p-6">
      <h1 class="text-2xl font-bold text-green-700">Pembayaran Berhasil</h1>
      <p class="mt-2 text-gray-700">Terima kasih. Pesanan Anda telah diterima.</p>
      @if(!empty($order_code))
        <p class="mt-3 text-sm text-gray-600">Kode Pesanan: <span class="font-semibold">{{ $order_code }}</span></p>
      @endif
      <div class="mt-6">
        <a href="/cek-pesanan" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Lihat Status Pesanan</a>
        <a href="/katalog" class="ml-3 inline-block bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800">Kembali Belanja</a>
      </div>
    </div>
  </div>
</section>
<script>
  (function(){
    var code = "{{ $order_code }}";
    if (!code) return;
    var temp = localStorage.getItem('signature_temp');
    if (temp) {
      localStorage.setItem('signature_' + code, temp);
    }
  })();
</script>
@endsection

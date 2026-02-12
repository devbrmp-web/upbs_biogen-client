@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 overflow-hidden">
    <div class="relative z-10 max-w-4xl mx-auto bg-white/70 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Syarat & Ketentuan</h1>
        
        <div class="prose prose-emerald max-w-none text-gray-600">
            <p>Terakhir diperbarui: {{ date('d F Y') }}</p>

            <h3>1. Ketentuan Umum</h3>
            <p>Dengan mengakses dan menggunakan situs web UPBS BRMP Biogen, Anda menyetujui untuk mematuhi syarat dan ketentuan ini.</p>

            <h3>2. Pemesanan Benih</h3>
            <p>Pemesanan benih sumber tunduk pada ketersediaan stok. Kami berhak membatalkan pesanan jika stok tidak tersedia atau terjadi kesalahan sistem.</p>

            <h3>3. Pembayaran</h3>
            <p>Pembayaran dilakukan melalui saluran resmi PNBP yang tersedia. Bukti pembayaran harus valid dan dapat diverifikasi.</p>

            <h3>4. Pengiriman</h3>
            <p>Pengiriman benih dilakukan menggunakan jasa ekspedisi pihak ketiga. Risiko kerusakan atau kehilangan selama pengiriman tunduk pada ketentuan pihak ekspedisi.</p>

            <h3>5. Perubahan Syarat</h3>
            <p>Kami dapat mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Penggunaan berkelanjutan atas situs ini dianggap sebagai persetujuan terhadap perubahan tersebut.</p>
        </div>
    </div>
</div>
@endsection
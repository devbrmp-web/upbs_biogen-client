@extends('layouts.app')

@section('title', 'Kebijakan Privasi - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 overflow-hidden">
    <div class="relative z-10 max-w-4xl mx-auto bg-white/70 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Kebijakan Privasi</h1>
        
        <div class="prose prose-emerald max-w-none text-gray-600">
            <p>Terakhir diperbarui: {{ date('d F Y') }}</p>

            <h3>1. Pendahuluan</h3>
            <p>Kami di UPBS BRMP Biogen berkomitmen untuk melindungi privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.</p>

            <h3>2. Informasi yang Kami Kumpulkan</h3>
            <p>Kami hanya mengumpulkan informasi yang diperlukan untuk memproses pesanan benih sumber, termasuk:</p>
            <ul>
                <li>Nama lengkap</li>
                <li>Alamat pengiriman</li>
                <li>Nomor telepon</li>
                <li>Alamat email (opsional)</li>
            </ul>

            <h3>3. Penggunaan Informasi</h3>
            <p>Informasi yang kami kumpulkan digunakan semata-mata untuk:</p>
            <ul>
                <li>Memproses dan mengirimkan pesanan Anda.</li>
                <li>Mengirimkan konfirmasi pesanan dan pembaruan status pengiriman.</li>
                <li>Keperluan audit internal dan pelaporan PNBP sesuai peraturan pemerintah.</li>
            </ul>

            <h3>4. Keamanan Data</h3>
            <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasional yang sesuai untuk melindungi data pribadi Anda dari akses yang tidak sah atau penyalahgunaan.</p>

            <h3>5. Hubungi Kami</h3>
            <p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami melalui halaman <a href="{{ url('/kontak') }}">Hubungi Kami</a>.</p>
        </div>
    </div>
</div>
@endsection
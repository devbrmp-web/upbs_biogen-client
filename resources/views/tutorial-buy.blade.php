@extends('layouts.app')
@section('title', 'Panduan Penggunaan - UPBS BRMP Biogen')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 mt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in-down">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl mb-4">
                Panduan Belanja Benih
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Ikuti langkah mudah berikut untuk memesan benih sumber berkualitas dari UPBS Biogen.
            </p>
        </div>

        <!-- Steps Container -->
        <div class="space-y-12 md:space-y-24 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-green-200 before:to-transparent">
            
            @php
                $steps = [
                    [
                        'title' => 'Pilih Produk',
                        'desc' => 'Buka halaman Katalog, gunakan fitur pencarian atau filter untuk menemukan varietas benih yang Anda butuhkan. Klik gambar untuk melihat detail.',
                        'image' => 'https://placehold.co/600x400/e2e8f0/475569?text=Langkah+1:+Pilih+Produk',
                        'icon' => '1'
                    ],
                    [
                        'title' => 'Detail & Masukkan Keranjang',
                        'desc' => 'Pelajari deskripsi varietas, pilih kelas benih, dan tentukan jumlah. Klik "Beli Langsung" untuk checkout cepat atau "Keranjang" untuk belanja lainnya.',
                        'image' => 'https://placehold.co/600x400/e2e8f0/475569?text=Langkah+2:+Detail+%26+Keranjang',
                        'icon' => '2'
                    ],
                    [
                        'title' => 'Checkout & Data Diri',
                        'desc' => 'Isi formulir pengiriman dengan lengkap. Pastikan alamat dan nomor telepon benar agar pengiriman lancar.',
                        'image' => 'https://placehold.co/600x400/e2e8f0/475569?text=Langkah+3:+Isi+Data+Diri',
                        'icon' => '3'
                    ],
                    [
                        'title' => 'Pembayaran Aman',
                        'desc' => 'Pilih metode pembayaran yang tersedia (Transfer Bank, E-Wallet, QRIS). Selesaikan pembayaran sesuai instruksi.',
                        'image' => 'https://placehold.co/600x400/e2e8f0/475569?text=Langkah+4:+Pembayaran',
                        'icon' => '4'
                    ],
                    [
                        'title' => 'Konfirmasi & Pantau',
                        'desc' => 'Setelah bayar, status pesanan akan otomatis terupdate. Anda bisa memantau prosesnya di menu "Cek Pesanan".',
                        'image' => 'https://placehold.co/600x400/e2e8f0/475569?text=Langkah+5:+Selesai',
                        'icon' => '5'
                    ]
                ];
            @endphp

            @foreach($steps as $index => $step)
                <div class="relative flex flex-col md:flex-row items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    
                    <!-- Icon -->
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-green-600 text-white font-bold text-lg shadow-lg shrink-0 md:order-1 md:absolute md:left-1/2 md:-translate-x-1/2 z-10 mb-4 md:mb-0">
                        {{ $step['icon'] }}
                    </div>
                    
                    <!-- Content -->
                    <div class="w-full md:w-[calc(50%-2.5rem)] bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="mb-4 overflow-hidden rounded-lg bg-gray-100 border border-gray-200 aspect-video">
                            <img src="{{ $step['image'] }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500" loading="lazy">
                        </div>
                        <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed text-sm">{{ $step['desc'] }}</p>
                    </div>

                    <!-- Spacer for layout balance -->
                    <div class="hidden md:block md:w-[calc(50%-2.5rem)]"></div>

                </div>
            @endforeach

        </div>

        <!-- CTA -->
        <div class="text-center mt-20 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Sudah siap memesan?</h2>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('katalog') }}" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-full hover:bg-green-700 transition shadow-lg hover:shadow-green-500/30 flex items-center justify-center gap-2">
                    <span>Mulai Belanja</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="px-8 py-3 bg-white text-gray-700 font-semibold rounded-full border border-gray-300 hover:bg-gray-50 transition flex items-center justify-center">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

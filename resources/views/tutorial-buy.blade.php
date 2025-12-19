@extends('layouts.app')
@section('title', 'Katalog Produk - UPBS BRMP Biogen')
@section('content')
<div class="bg-gradient-to-b from-green-50 via-white to-green-100 min-h-screen py-14 overflow-hidden mt-16">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold text-green-700 mb-5 tracking-tight">
                🌱 Bingung Pakai Aplikasi? Santai, Kita Pandu 😉
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                Aplikasi UPBS BRMP Biogen dibuat supaya kamu bisa pesan benih unggul
                dengan cara yang simpel, transparan, dan nggak ribet.
                Yuk, kenalan dulu sama fiturnya ✨
            </p>
        </div>

        {{-- Section: Informasi Halaman --}}
        <section class="mb-24">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">
                📍 Kenalan Sama Halaman-Halamannya
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Card --}}
                @php
                    $pages = [
                        ['icon'=>'🏠','title'=>'Beranda','desc'=>'Ini adalah halaman pembuka. Kamu bisa lihat gambaran umum aplikasi, info singkat, dan akses cepat ke fitur utama. Cocok buat mulai eksplor.'],
                        ['icon'=>'🧺','title'=>'Katalog','desc'=>'Di sinilah semua varietas benih ditampilkan. Kamu bisa cek detail produk, stok yang tersedia, dan harga sebelum memutuskan beli.'],
                        ['icon'=>'🏢','title'=>'Tentang Kami','desc'=>'Penasaran siapa di balik aplikasi ini? Halaman ini berisi profil UPBS BRMP Biogen, peran kami, dan tujuan layanan ini dibuat.'],
                        ['icon'=>'📦','title'=>'Cek Pesanan','desc'=>'Halaman favorit setelah checkout 😄 Kamu bisa cek status transaksi dengan kode pesanan. Pesananmu juga akan otomatis tampil karena disimpan sementara lewat cookies.']
                    ];
                @endphp

                @foreach($pages as $page)
                <div class="bg-white rounded-3xl p-7 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                    <div class="text-4xl mb-4">{{ $page['icon'] }}</div>
                    <h3 class="font-semibold text-xl mb-3 text-green-700">
                        {{ $page['title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $page['desc'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- Section: Tutorial Checkout --}}
        <section class="mb-24">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">
                🛒 Cara Checkout (Nggak Sampai 5 Menit!)
            </h2>

            <div class="bg-white rounded-[2rem] shadow-xl p-8 md:p-12">
                <ol class="space-y-8">
                    @php
                        $steps = [
                            'Buka halaman <b>Katalog</b>, lalu klik varietas benih yang kamu butuhkan.',
                            'Pilih <b>jenis benih</b> sesuai kebutuhanmu.',
                            'Klik <b>Tambah ke Keranjang</b> kalau mau belanja lagi, atau <b>Beli Langsung</b> kalau sudah yakin.',
                            'Isi data pada <b>halaman checkout</b> dengan lengkap dan benar.',
                            'Setelah itu, akan muncul <b>pop up pembayaran Midtrans</b>.',
                            'Selesaikan pembayaran sesuai metode yang kamu pilih.',
                            'Kalau pembayaran berhasil, kamu akan otomatis diarahkan ke halaman <b>Cek Pesanan</b>. Beres 🎉'
                        ];
                    @endphp

                    @foreach($steps as $index => $step)
                    <li class="flex items-start gap-5 group">
                        <span class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-gray-700 leading-relaxed">
                            {!! $step !!}
                        </p>
                    </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- Section: Informasi Pengiriman --}}
        <section class="mb-24">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">
                🚚 Soal Pengiriman, Perlu Tahu Ini
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Ambil --}}
                <div class="bg-white rounded-3xl p-8 shadow hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-green-700 mb-3">📍 Ambil Langsung</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kamu bisa mengambil pesanan secara langsung sesuai ketentuan
                        yang berlaku di UPBS BRMP Biogen.
                    </p>
                </div>

                {{-- Kirim --}}
                <div class="bg-white rounded-3xl p-8 shadow hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-green-700 mb-3">📦 Kirim ke Alamat</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Untuk pengiriman, pembeli perlu <b>menghubungi call center</b>
                        dan menyertakan <b>kwitansi transaksi</b>.
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 text-sm">
                        <li>Kwitansi bisa diunduh dari detail transaksi</li>
                        <li>Klik tombol pada card transaksi untuk lihat detail</li>
                        <li>Di halaman detail, klik tombol <b>Cetak</b></li>
                        <li>Kwitansi akan tampil dan bisa diunduh</li>
                        <li><b>Biaya ongkir belum termasuk</b> pembayaran di aplikasi</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- Section: Cek Pesanan --}}
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">
                🔍 Cara Cek Pesanan Kamu
            </h2>

            <div class="bg-gradient-to-r from-green-600 to-green-500 text-white rounded-[2rem] p-10 shadow-2xl">
                <ol class="space-y-4 text-lg">
                    <li>📧 Buka email, lalu <b>salin kode pesanan</b> yang kamu terima</li>
                    <li>📝 Tempelkan kode tersebut ke <b>form Cek Pesanan</b></li>
                    <li>📊 Detail transaksi akan langsung muncul di <b>card hasil</b></li>
                </ol>
            </div>
        </section>

    </div>
</div>

{{-- Animasi tambahan --}}
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.8s ease-out both;
}
</style>
@endsection

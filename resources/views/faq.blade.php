@extends('layouts.app')

@section('title', 'FAQ - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-blue-50 overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-green-200/30 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-200/30 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 mb-4">
                Pertanyaan Umum
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan jawaban atas pertanyaan yang sering diajukan mengenai layanan dan produk benih sumber kami.
            </p>
        </div>

        <div class="space-y-6">
            <!-- FAQ Item 1 -->
            <div class="group relative bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <details class="group/details [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer text-gray-800 font-semibold select-none">
                        <span>Bagaimana cara melakukan pemesanan benih?</span>
                        <span class="relative ml-4 flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white/50 rounded-full transition-all duration-300 group-open/details:bg-emerald-100 group-open/details:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-white/30 pt-4">
                        <p>Anda dapat memesan benih melalui halaman <a href="{{ route('katalog') }}" class="text-emerald-600 hover:underline">Katalog</a>. Pilih varietas yang diinginkan, masukkan ke keranjang, dan ikuti proses checkout. Anda tidak perlu membuat akun untuk berbelanja.</p>
                    </div>
                </details>
            </div>

            <!-- FAQ Item 2 -->
            <div class="group relative bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <details class="group/details [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer text-gray-800 font-semibold select-none">
                        <span>Metode pembayaran apa saja yang tersedia?</span>
                        <span class="relative ml-4 flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white/50 rounded-full transition-all duration-300 group-open/details:bg-emerald-100 group-open/details:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-white/30 pt-4">
                        <p>Kami mendukung pembayaran melalui transfer bank (Virtual Account) dan QRIS. Semua pembayaran diproses secara otomatis melalui payment gateway resmi untuk menjamin keamanan transaksi PNBP.</p>
                    </div>
                </details>
            </div>

            <!-- FAQ Item 3 -->
            <div class="group relative bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <details class="group/details [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer text-gray-800 font-semibold select-none">
                        <span>Bagaimana cara melacak pesanan saya?</span>
                        <span class="relative ml-4 flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white/50 rounded-full transition-all duration-300 group-open/details:bg-emerald-100 group-open/details:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-white/30 pt-4">
                        <p>Silakan kunjungi halaman <a href="{{ route('cek-pesanan') }}" class="text-emerald-600 hover:underline">Lacak Pesanan</a>. Masukkan kode pesanan atau nomor HP yang Anda gunakan saat checkout untuk melihat status terkini pesanan Anda.</p>
                    </div>
                </details>
            </div>

            <!-- FAQ Item 4 -->
            <div class="group relative bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <details class="group/details [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer text-gray-800 font-semibold select-none">
                        <span>Berapa lama waktu pengiriman benih?</span>
                        <span class="relative ml-4 flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white/50 rounded-full transition-all duration-300 group-open/details:bg-emerald-100 group-open/details:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-white/30 pt-4">
                        <p>Waktu pengiriman bergantung pada lokasi tujuan dan layanan ekspedisi yang dipilih. Pesanan biasanya diproses dalam 1-2 hari kerja setelah pembayaran dikonfirmasi.</p>
                    </div>
                </details>
            </div>

            <!-- FAQ Item 5 -->
            <div class="group relative bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <details class="group/details [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer text-gray-800 font-semibold select-none">
                        <span>Apakah saya bisa membatalkan pesanan?</span>
                        <span class="relative ml-4 flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white/50 rounded-full transition-all duration-300 group-open/details:bg-emerald-100 group-open/details:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-white/30 pt-4">
                        <p>Pesanan yang belum dibayar akan otomatis dibatalkan oleh sistem setelah batas waktu pembayaran habis. Untuk pesanan yang sudah dibayar, silakan hubungi layanan pelanggan kami untuk bantuan lebih lanjut.</p>
                    </div>
                </details>
            </div>
        </div>

        <div class="mt-16 text-center">
            <p class="text-gray-600 mb-4">Masih punya pertanyaan?</p>
            <a href="{{ url('/kontak') }}" class="inline-flex items-center justify-center px-8 py-3 text-sm font-medium text-white transition-all duration-200 bg-emerald-600 rounded-full hover:bg-emerald-700 hover:shadow-lg hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>
@endsection
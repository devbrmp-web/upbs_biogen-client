@extends('layouts.app')

@section('title', 'Akses Ditolak - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 to-amber-50 px-4">
    <!-- Background Decoration -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-yellow-200/20 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="relative z-10 max-w-lg w-full text-center">
        <!-- Glass Card -->
        <div class="bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-8 md:p-12 shadow-xl shadow-amber-900/5">
            <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400/80 to-yellow-400/80 mb-2 select-none">
                403
            </h1>
            <div class="space-y-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Akses Ditolak
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                </p>
                <div class="pt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-amber-600 to-yellow-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-amber-600/30 transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

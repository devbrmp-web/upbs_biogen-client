@extends('layouts.app')

@section('title', 'Layanan Tidak Tersedia - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 to-purple-50 px-4">
    <!-- Background Decoration -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-pink-200/20 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="relative z-10 max-w-lg w-full text-center">
        <!-- Glass Card -->
        <div class="bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-8 md:p-12 shadow-xl shadow-purple-900/5">
            <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400/80 to-pink-400/80 mb-2 select-none">
                503
            </h1>
            <div class="space-y-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Sedang Dalam Pemeliharaan
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    Sistem kami sedang dalam pemeliharaan terjadwal untuk meningkatkan layanan. Silakan kembali lagi beberapa saat lagi.
                </p>
                <div class="pt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-purple-600/30 transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Coba Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

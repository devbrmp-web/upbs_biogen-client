@extends('layouts.app')

@section('title', 'Kesalahan Server - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 to-red-50 px-4">
    <!-- Background Decoration -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-orange-200/20 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="relative z-10 max-w-lg w-full text-center">
        <!-- Glass Card -->
        <div class="bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-8 md:p-12 shadow-xl shadow-red-900/5">
            <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-400/80 to-orange-400/80 mb-2 select-none">
                500
            </h1>
            <div class="space-y-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Terjadi Kesalahan Server
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    Maaf, terjadi kesalahan internal pada server kami. Tim kami sedang bekerja untuk memperbaikinya.
                </p>
                <div class="pt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-red-600/30 transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

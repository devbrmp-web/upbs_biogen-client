@extends('layouts.app')

@section('content')

<section class="min-h-screen bg-gradient-to-br from-[#B4DEBD] via-white to-[#DFF0E3] px-6 py-20">

    <div class="max-w-5xl mx-auto">

        <!-- Glass Card -->
        <div class="relative bg-white/25 backdrop-blur-xl border border-white/40 
                    shadow-2xl rounded-3xl p-10 flex flex-col lg:flex-row gap-10">

            <!-- Gambar Produk -->
            <div class="flex-1 flex justify-center items-center">
                <img src="{{ $variety['image'] ?? asset('default.png') }}"
                     class="rounded-2xl shadow-lg w-full max-w-sm backdrop-blur-xl bg-white/40 p-4">
            </div>

            <!-- Informasi Produk -->
            <div class="flex-1 text-gray-900">

                <h1 class="text-4xl font-bold mb-4">{{ $variety['name'] }}</h1>

                <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                    {{ $variety['description'] ?? 'Belum ada deskripsi.' }}
                </p>

                <div class="space-y-3 text-sm">

                    <p><strong>Komoditas:</strong> 
                        {{ $variety['commodity']['name'] ?? '-' }}
                    </p>

                    <p><strong>Kode Varietas:</strong> 
                        {{ $variety['code'] ?? '-' }}
                    </p>

                    <p><strong>Tahun Rilis:</strong> 
                        {{ $variety['year'] ?? '-' }}
                    </p>
                </div>

                <div class="mt-8">
                    <a href="{{ route('katalog') }}"
                       class="px-6 py-3 bg-gray-900 text-white rounded-xl shadow-lg hover:bg-gray-800 transition">
                        ← Kembali ke Katalog
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>

@endsection

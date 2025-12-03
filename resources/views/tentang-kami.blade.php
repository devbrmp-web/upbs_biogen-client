@extends('layouts.app')
@section('title', 'Tentang Kami - UPBS BRMP Biogen')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-green-900 py-24 sm:py-32">
        <div class="absolute inset-0 overflow-hidden">
            <img 
                src="https://images.unsplash.com/photo-1595839085880-a972b916303d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                alt="Lahan pertanian dan laboratorium bioteknologi" 
                class="h-full w-full object-cover object-center opacity-20"
            >
            <div class="absolute inset-0 bg-gradient-to-b from-green-900/80 to-green-950"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                Tentang Kami
            </h1>
            <p class="mt-6 text-lg leading-8 text-green-100 max-w-2xl mx-auto">
                Mengenal lebih dekat Balai Besar Pengujian Standar Instrumen Bioteknologi dan Sumber Daya Genetik Pertanian.
            </p>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16 sm:py-24">
        <div class="mx-auto max-w-3xl text-base leading-7 text-gray-700">
            <p class="mb-8 text-xl font-semibold leading-8 text-gray-900">
                Balai Besar Pengujian Standar Instrumen Bioteknologi dan Sumber Daya Genetik Pertanian (BRMP) Biogen adalah unit kerja di bawah Badan Penelitian dan Pengembangan Pertanian.
            </p>

            <!-- Quote Box – updated color -->
            <div class="bg-green-50 border-l-4 border-green-600 p-6 sm:p-8 my-10 rounded-r-lg shadow-sm">
                <p class="italic text-gray-800 text-lg font-medium">
                    “Menjadi lembaga unggulan dalam pengujian standar instrumen bioteknologi dan pengembangan sumber daya genetik pertanian.”
                </p>
                <p class="text-sm text-green-700 mt-2 font-bold">
                    — Visi BRMP Biogen
                </p>
            </div>

            <p class="mb-6">
                BRMP Biogen bertugas melaksanakan pengujian standar instrumen bioteknologi, perbanyakan benih, serta pengembangan dan pelestarian sumber daya genetik pertanian. Kami berkomitmen untuk mendukung ketahanan pangan nasional melalui inovasi teknologi pertanian yang berkelanjutan.
            </p>
            <p class="mb-6">
                Unit Pengelola Benih Sumber (UPBS) BRMP Biogen menyediakan benih varietas unggul kepada petani dan masyarakat umum melalui sistem e-commerce yang terintegrasi. Layanan ini bertujuan untuk mempermudah akses terhadap benih berkualitas tinggi yang telah teruji dan bersertifikat.
            </p>

            <h3 class="mt-12 text-2xl font-bold tracking-tight text-gray-900">Tugas & Fungsi Utama</h3>
            <ul role="list" class="mt-8 space-y-5">
                <li class="flex items-start gap-x-4">
                    <svg class="mt-0.5 flex-shrink-0 h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Pengujian standar instrumen bioteknologi pertanian.</span>
                </li>
                <li class="flex items-start gap-x-4">
                    <svg class="mt-0.5 flex-shrink-0 h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Perbanyakan dan produksi benih sumber varietas unggul.</span>
                </li>
                <li class="flex items-start gap-x-4">
                    <svg class="mt-0.5 flex-shrink-0 h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Pengembangan dan pelestarian sumber daya genetik pertanian.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
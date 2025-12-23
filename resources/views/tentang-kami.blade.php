@extends('layouts.app')
@section('title', 'Tentang Kami - UPBS BRMP Biogen')

@section('content')
<div class="bg-gradient-to-b from-[#B4DEBD]/40 to-white page-animate-fadeIn">
    <div class="relative bg-gradient-to-r from-[#B4DEBD] via-[#B4DEBD]/70 to-transparent py-24 sm:py-32">
        <div class="absolute inset-0 overflow-hidden">
            <img 
                src="https://images.unsplash.com/photo-1595839085880-a972b916303d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                alt="Lahan pertanian dan laboratorium bioteknologi" 
                class="h-full w-full object-cover object-center opacity-20"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-[#B4DEBD]/70 to-transparent"></div>
        </div>
        <div class="relative z-20 mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <div class="bg-white/20 backdrop-blur-lg border border-white/40 rounded-3xl p-8 sm:p-10 inline-block">
            <h1 class="text-4xl font-extrabold tracking-tight text-black sm:text-5xl md:text-6xl">
                Tentang Kami
            </h1>
            <p class="mt-6 text-lg leading-8 text-blsck/80 max-w-2xl mx-auto">
                Mengenal lebih dekat Balai Besar Pengujian Standar Instrumen Bioteknologi dan Sumber Daya Genetik Pertanian.
            </p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="/katalog" class="px-5 py-3 rounded-lg bg-gray-900 text-white font-semibold shadow hover:bg-gray-800">Lihat Katalog</a>
                <a href="/cek-pesanan" class="px-5 py-3 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-700">Cek Pesanan</a>
            </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white border border-gray-100 rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Visi</h3>
                <p class="text-gray-700">Menjadi pusat unggulan bioteknologi dan benih sumber untuk pertanian berkelanjutan Indonesia.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Misi</h3>
                <p class="text-gray-700">Menyediakan benih sumber berkualitas, riset bioteknologi, dan pelestarian sumber daya genetik.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Nilai</h3>
                <p class="text-gray-700">Integritas, inovasi, kolaborasi, dan pelayanan masyarakat.</p>
            </div>
        </div>
        <div class="mx-auto max-w-3xl text-base leading-7 text-gray-700">
            <p class="mb-8 text-xl font-semibold leading-8 text-gray-900">
                Balai Besar Pengujian Standar Instrumen Bioteknologi dan Sumber Daya Genetik Pertanian (BRMP) Biogen adalah unit kerja di bawah Badan Penelitian dan Pengembangan Pertanian.
            </p>

            <!-- Quote Box – updated color -->
            <div class="bg-[#B4DEBD]/40 border-l-4 border-green-600 p-6 sm:p-8 my-10 rounded-r-lg shadow-sm">
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

        <!-- Immersive Section: Image + Content -->
        <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div class="relative rounded-2xl overflow-hidden shadow-lg">
                <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop" alt="Laboratorium dan lahan percobaan" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>
            <div class="bg-white/70 backdrop-blur-md border border-white/50 rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Komitmen Kami</h3>
                <p class="text-gray-700">
                    Kami menggabungkan riset bioteknologi mutakhir dan produksi benih sumber berkualitas untuk mendukung produktivitas pertanian nasional. Kolaborasi dengan mitra dan komunitas petani menjadi kunci keberhasilan kami.
                </p>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                        <div class="text-xs text-gray-500">Benih Tersertifikasi</div>
                        <div class="text-lg font-bold text-gray-900">≥ 98%</div>
                    </div>
                    <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                        <div class="text-xs text-gray-500">Jangkauan Provinsi</div>
                        <div class="text-lg font-bold text-gray-900">30+</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="mt-16">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Perjalanan Singkat</h3>
            <div class="relative">
                <div class="absolute left-4 md:left-1/2 w-1 bg-green-200 h-full rounded"></div>
                <div class="space-y-8">
                    <div class="relative md:flex md:items-center md:justify-between gap-8">
                        <div class="md:w-1/2 bg-white rounded-xl shadow p-6 border border-gray-100 md:mr-10">
                            <h4 class="font-semibold text-gray-900">2005</h4>
                            <p class="text-sm text-gray-700 mt-2">Pembentukan unit pengujian bioteknologi dan pengelolaan benih sumber.</p>
                        </div>
                        <div class="hidden md:block w-3 h-3 bg-green-600 rounded-full"></div>
                        <div class="md:w-1/2"></div>
                    </div>
                    <div class="relative md:flex md:items-center md:justify-between gap-8">
                        <div class="md:w-1/2"></div>
                        <div class="hidden md:block w-3 h-3 bg-green-600 rounded-full"></div>
                        <div class="md:w-1/2 bg-white rounded-xl shadow p-6 border border-gray-100 md:ml-10">
                            <h4 class="font-semibold text-gray-900">2015</h4>
                            <p class="text-sm text-gray-700 mt-2">Peluncuran program UPBS untuk distribusi benih unggul ke publik.</p>
                        </div>
                    </div>
                    <div class="relative md:flex md:items-center md:justify-between gap-8">
                        <div class="md:w-1/2 bg-white rounded-xl shadow p-6 border border-gray-100 md:mr-10">
                            <h4 class="font-semibold text-gray-900">2024</h4>
                            <p class="text-sm text-gray-700 mt-2">Digitalisasi layanan dan e-commerce benih sumber terintegrasi.</p>
                        </div>
                        <div class="hidden md:block w-3 h-3 bg-green-600 rounded-full"></div>
                        <div class="md:w-1/2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

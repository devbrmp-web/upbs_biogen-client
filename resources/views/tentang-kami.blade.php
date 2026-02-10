@extends('layouts.app')
@section('title', 'Tentang Kami - UPBS BRMP Biogen')

@section('content')
<div class="page-animate-fadeIn overflow-hidden">
    <!-- Hero Section -->
    <div class="relative pt-24 pb-16 sm:pt-32 sm:pb-24 overflow-hidden">
        <!-- Background Hijau Gradasi (Sama dengan Home) -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-[#B4DEBD] via-[#B4DEBD]/70 to-transparent z-10"></div>
            <img src="{{ Vite::asset('resources/img/herolp.jpeg') }}"
                alt="Hero Padi"
                class="hidden md:block absolute inset-0 w-full h-full object-cover object-right lg:object-right-top opacity-90"
                onerror="this.src='https://images.unsplash.com/photo-1503899036084-c55cdd92da26?q=80&w=1920&auto=format&fit=crop'">
        </div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="z-10">
                    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl mb-6">
                        Tentang Kami
                    </h1>
                    <p class="text-lg leading-8 text-gray-600 mb-8">
                        Mengenal lebih dekat Balai Besar Perakitan dan Modernisasi Bioteknologi dan Sumber Daya Genetik Pertanian (BRMP Biogen).
                    </p>
                    <div class="flex gap-4">
                        <a href="/katalog" class="px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                            Lihat Katalog
                        </a>
                        <a href="/cek-pesanan" class="px-6 py-3 rounded-xl bg-white text-gray-900 border border-gray-200 font-semibold hover:bg-gray-50 transition shadow-lg shadow-gray-100">
                            Cek Pesanan
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <!-- Backdrop Floating Effect -->
                    <div class="absolute top-12 bottom-[-2rem] left-8 -right-4 bg-black/20 rounded-3xl -z-10 transform translate-x-2 translate-y-2"></div>

                    <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-gray-100 aspect-[4/5] hover:scale-[1.01] transition duration-500">
                        <img src="https://biogen.brmp.pertanian.go.id/storage/assets/uploads/images/satker/thumbnail/vbASO2ymhtZ3oJjlIkLXlQhJdPOX7O7NJJbQfqaG.jpg" 
                             alt="Fasilitas BRMP Biogen" 
                             class="w-full h-full object-cover">

                        <!-- Info Card Overlay -->
                        <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white/50 max-w-xs">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Fasilitas</p>
                                    <p class="text-sm font-bold text-gray-900">Modern & Terstandar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative blob -->
                    <div class="absolute -z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-green-200/50 to-blue-200/50 rounded-full blur-3xl opacity-60"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16 space-y-24">
        
        <!-- Visi Misi Presiden -->
        <section>
            <div class="text-center mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-gray-100 text-gray-600 text-sm font-medium mb-4">Republik Indonesia</span>
                <h2 class="text-3xl font-bold text-gray-900">Visi & Misi Presiden dan Wakil Presiden</h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Visi Card -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-3xl p-8 shadow-xl flex flex-col justify-center">
                    <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <span class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </span>
                        Visi
                    </h3>
                    <p class="text-xl leading-relaxed font-light">
                        "Terwujudnya Indonesia Maju yang Berdaulat, Mandiri, dan Berkepribadian Berdasarkan Gotong Royong"
                    </p>
                </div>
                <!-- Misi List -->
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="bg-green-100 text-green-700 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        </span>
                        Misi
                    </h3>
                    <ul class="space-y-4">
                        @foreach([
                            'Peningkatan kualitas manusia Indonesia',
                            'Struktur ekonomi yang produktif, merata dan berdaya saing',
                            'Pembangunan yang merata dan berkeadilan',
                            'Mencapai lingkungan hidup yang berkelanjutan',
                            'Kemajuan budaya yang mencerminkan kepribadian bangsa',
                            'Penegakan sistem hukum yang bebas korupsi, bermartabat dan terpercaya',
                            'Perlindungan bagi segenap bangsa dan memberikan rasa aman pada seluruh warga',
                            'Pengelolaan pemerintah yang bersih, efektif, dan terpercaya',
                            'Sinergi pemerintah daerah dalam kerangka Negara Kesatuan'
                        ] as $index => $misi)
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xs font-bold mt-0.5">{{ $index + 1 }}</span>
                            <span class="text-gray-700 text-sm leading-relaxed">{{ $misi }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <!-- Visi Misi Kementan -->
        <section>
            <div class="text-center mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-700 text-sm font-medium mb-4">Kementerian Pertanian</span>
                <h2 class="text-3xl font-bold text-gray-900">Visi & Misi Kementerian Pertanian</h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                 <!-- Visi Kementan -->
                 <div class="bg-white border border-green-100 rounded-3xl p-8 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-bl-full -mr-10 -mt-10 z-0"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Visi</h3>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            "Pertanian yang maju, mandiri dan modern untuk terwujudnya Indonesia maju yang berdaulat, mandiri, dan berkepribadian berlandaskan gotong royong"
                        </p>
                    </div>
                </div>
                <!-- Misi Kementan -->
                <div class="bg-white border border-green-100 rounded-3xl p-8 shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Misi</h3>
                    <ul class="space-y-4">
                        @foreach([
                            'Mewujudkan ketahanan pangan.',
                            'Meningkatkan nilai tambah dan daya saing pertanian.',
                            'Meningkatkan kualitas SDM dan prasarana Kementerian Pertanian.'
                        ] as $index => $misi)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span class="text-gray-700">{{ $misi }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <!-- Pimpinan Profile -->
        <section class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Pimpinan Kami</h2>
            </div>
            
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-5">
                    <!-- Photo Column -->
                    <div class="md:col-span-2 bg-gray-100 relative min-h-[320px]">
                        <img src="https://biogen.brmp.pertanian.go.id/storage/assets/uploads/images/sdm/square/w0H5q91jbNAXa26iA0wQ8COtIMF40H793iB4hRjc.png" 
                             alt="Arif Surahman" 
                             class="absolute inset-0 w-full h-full object-cover object-top">
                    </div>
                    <!-- Details Column -->
                    <div class="md:col-span-3 p-8 md:p-10 flex flex-col justify-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">ARIF SURAHMAN S.PI, M.SC PH.D</h3>
                            <p class="text-green-600 font-semibold text-sm uppercase tracking-wide mb-6">Kepala Balai Besar</p>
                            
                            <div class="space-y-4 text-sm text-gray-600 mb-8">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span>Jl. Tentara Pelajar No.3A, RT.02/RW.7, Menteng, Kec. Bogor Barat, Kota Bogor, Jawa Barat 16111</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    <span>(0251) 8337975</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    <span>arifsurahman@pertanian.go.id</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-6">
                                <h4 class="font-bold text-gray-900 mb-3 text-sm">Pendidikan</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-gray-900 w-12 shrink-0">S3</span>
                                        <span>Asian Institute of Technology (AIT) Thailand (2017)</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-gray-900 w-12 shrink-0">S2</span>
                                        <span>Asian Institute of Technology (AIT) Thailand (2002)</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-gray-900 w-12 shrink-0">S1</span>
                                        <span>Universitas Diponegoro (1995)</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="border-t border-gray-100 pt-6 mt-6">
                                <h4 class="font-bold text-gray-900 mb-2 text-sm">Penghargaan</h4>
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-50 text-yellow-800 rounded-full text-xs font-medium border border-yellow-100">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    Satyalancana Karya Satya XX Tahun 2020
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Struktur Organisasi -->
        <section>
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Struktur Organisasi</h2>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="flex flex-col items-center">
                    <!-- Level 1: Kepala -->
                    <div class="w-64 p-4 bg-green-600 text-white rounded-xl shadow-lg text-center font-bold z-10 relative">
                        Kepala
                    </div>
                    
                    <!-- Connector Vertical -->
                    <div class="h-12 w-1 bg-gray-300"></div>
                    
                    <!-- Connector Horizontal -->
                    <div class="w-[50%] h-1 bg-gray-300 relative">
                        <!-- Down lines -->
                        <div class="absolute left-0 top-0 h-8 w-1 bg-gray-300"></div>
                        <div class="absolute right-0 top-0 h-8 w-1 bg-gray-300"></div>
                    </div>

                    <!-- Level 2 -->
                    <div class="flex justify-between w-full md:w-[80%] mt-7 gap-4">
                        <div class="flex-1 p-4 bg-white border-2 border-green-100 text-green-800 rounded-xl shadow-md text-center font-semibold text-sm flex items-center justify-center min-h-[80px]">
                            Bagian Tata Usaha
                        </div>
                        <div class="flex-1 p-4 bg-white border-2 border-green-100 text-green-800 rounded-xl shadow-md text-center font-semibold text-sm flex items-center justify-center min-h-[80px]">
                            Jabatan Fungsional & Jabatan Pelaksana
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tugas & Fungsi -->
        <section>
            <div class="bg-gray-50 rounded-3xl p-8 sm:p-12 border border-gray-100">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Tugas & Fungsi</h2>
                <p class="text-gray-700 leading-relaxed mb-8">
                    Balai Besar Perakitan dan Modernisasi Bioteknologi dan Sumber Daya Genetik Pertanian (BRMP Biogen) merupakan unit pelaksana teknis di bawah Badan Perakitan dan Modernisasi Pertanian (BRMP) Kementerian Pertanian Republik Indonesia yang mempunyai tugas untuk melaksanakan perakitan dan modernisasi bioteknologi dan sumber daya genetik pertanian sebagaimana tertuang dalam Peraturan Menteri Pertanian Nomor 10 Tahun 2025.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                        'Penyusunan rencana program dan anggaran di bidang perakitan dan modernisasi bioteknologi dan sumber daya genetik pertanian',
                        'Pelaksanaan perekayasaan dan perakitan teknologi, pengembangan kapasitas produksi, dan modernisasi bioteknologi dan sumber daya genetik pertanian',
                        'Pelaksanaan analisis dan pengujian teknologi di bidang perakitan dan modernisasi bioteknologi dan sumber daya genetik pertanian',
                        'Pengelolaan produksi benih sumber, hasil perakitan dan modernisasi bioteknologi dan sumber daya genetik pertanian',
                        'Pengelolaan sumber daya genetik pertanian dan bank genetik pertanian',
                        'Pelaksanaan perencanaan, perumusan, pemeliharaan dan penilaian kesesuaian Standar Nasional Indonesia di bidang bioteknologi dan sumber daya genetik pertanian',
                        'Pelaksanaan pendayagunaan dan kerja sama hasil perakitan dan modernisasi bioteknologi dan sumber daya genetik pertanian',
                        'Pelaksanaan pemantauan, evaluasi dan pelaporan di bidang perakitan dan modernisasi bioteknologi dan sumber daya genetik pertanian',
                        'Pelaksanaan urusan tata usaha dan rumah tangga Balai Besar Pengujian dan Perakitan Bioteknologi dan Sumber Daya Genetik Pertanian'
                    ] as $index => $fungsi)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-sm mb-4">
                            {{ $index + 1 }}
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $fungsi }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
@endsection

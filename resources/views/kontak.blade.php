@extends('layouts.app')

@section('title', 'Hubungi Kami - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-blue-50 overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-200/30 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-teal-200/30 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto">
        <div class="text-center mb-16">
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 mb-4">
                Hubungi Kami
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Kami siap membantu Anda. Jangan ragu untuk menghubungi kami jika ada pertanyaan atau butuh bantuan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Contact Card 1: Address -->
            <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 p-8 text-center shadow-sm hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Alamat Kantor</h3>
                <p class="text-gray-600 leading-relaxed">
                    Jl. Tentara Pelajar No.3A,<br>Menteng, Bogor Barat,<br>Kota Bogor, Jawa Barat 16111
                </p>
            </div>

            <!-- Contact Card 2: Phone -->
            <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 p-8 text-center shadow-sm hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Telepon</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Senin - Jumat, 08:00 - 16:00
                </p>
                <a href="tel:+622518337975" class="text-xl font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                    (0251) 8337975
                </a>
            </div>

            <!-- Contact Card 3: Email -->
            <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 p-8 text-center shadow-sm hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Email</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kirim pertanyaan kapan saja
                </p>
                <a href="mailto:upbs.biogen@pertanian.go.id" class="text-emerald-600 hover:text-emerald-700 font-medium break-all transition-colors">
                    upbs.biogen@pertanian.go.id
                </a>
            </div>
        </div>

        <!-- Optional Map Section -->
        <div class="mt-16 bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 p-4 shadow-sm h-96 overflow-hidden">
             <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.428574167389!2d106.7865223147728!3d-6.593539995228587!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5c777085777%3A0x6739665335198904!2sBalai%20Besar%20Penelitian%20dan%20Pengembangan%20Bioteknologi%20dan%20Sumberdaya%20Genetik%20Pertanian!5e0!3m2!1sid!2sid!4v1626926342611!5m2!1sid!2sid" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy"
                class="rounded-xl grayscale hover:grayscale-0 transition-all duration-500">
            </iframe>
        </div>
    </div>
</div>
@endsection
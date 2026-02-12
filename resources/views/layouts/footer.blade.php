<footer class="relative mt-20 border-t border-white/20 bg-white/10 backdrop-blur-xl shadow-[0_-4px_30px_rgba(0,0,0,0.05)] overflow-hidden z-10">
    <!-- Decorative Elements for Glass Effect Depth -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-green-400/10 rounded-full blur-3xl -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl translate-y-1/2 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            
            <!-- 🧬 Brand Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="UPBS BRMP Biogen" class="relative h-12 w-auto object-contain drop-shadow-sm transform transition hover:scale-105 duration-300">
                    </div>
                    <div class="flex flex-col">
                        
                        <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-600">UPBS BRMP</span>
                        <span class="text-xs font-semibold tracking-wider text-emerald-600 uppercase">Biogen</span>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-gray-600 text-justify">
                    Unit Pengelola Benih Sumber (UPBS) Balai Penelitian Bioteknologi dan Sumber Daya Genetik Pertanian.
                    Berdedikasi menyediakan benih sumber varietas unggul untuk mendukung kemandirian dan ketahanan pangan nasional.
                </p>
            </div>

            <!-- 🧭 Navigation -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-gray-900 uppercase mb-6 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-emerald-500 rounded-full"></span>
                    Navigasi
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('katalog') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Katalog Benih
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cek-pesanan') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Lacak Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Tentang Kami
                        </a>
                    </li>
                </ul>
            </div>

            <!-- 🪴 Support -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-gray-900 uppercase mb-6 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-emerald-500 rounded-full"></span>
                    Bantuan
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('faq') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Hubungi Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Kebijakan Privasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors"></span>
                            Syarat & Ketentuan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- 📬 Contact -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-gray-900 uppercase mb-6 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-emerald-500 rounded-full"></span>
                    Info Kontak
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-sm text-gray-600 group">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="leading-relaxed">Jl. Tentara Pelajar No.3A,<br>Menteng, Bogor Barat,<br>Kota Bogor, Jawa Barat 16111</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-600 group">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span class="font-medium hover:text-emerald-600 transition-colors cursor-pointer">(0251) 8337975</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-600 group">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <a href="mailto:upbs.biogen@pertanian.go.id" class="font-medium hover:text-emerald-600 transition-colors">upbs.biogen@pertanian.go.id</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 🔸 Bottom Bar -->
        <div class="mt-16 pt-8 border-t border-gray-200/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 text-center md:text-left">
                &copy; {{ date('Y') }} <span class="font-semibold text-emerald-700">UPBS BRMP Biogen</span>. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.488 2h-.173zM12.314 3.824c-2.27 0-2.583.008-3.483.05-.902.04-1.393.181-1.72.308a2.927 2.927 0 00-1.064.693 2.927 2.927 0 00-.693 1.064c-.127.327-.268.818-.308 1.72-.042.9-.05 1.213-.05 3.483 0 2.27.008 2.583.05 3.483.04.902.181 1.393.308 1.72.22.571.49.992.932 1.434.441.441.862.712 1.434.932.327.127.818.268 1.72.308.9.042 1.213.05 3.483.05 2.27 0 2.583-.008 3.483-.05.902-.04 1.393-.181 1.72-.308a2.927 2.927 0 001.064-.693 2.927 2.927 0 00.693-1.064c.127-.327.268-.818.308-1.72.042-.9.05-1.213.05-3.483 0-2.27-.008-2.583-.05-3.483-.04-.902-.181-1.393-.308-1.72a2.927 2.927 0 00-.693-1.064 2.927 2.927 0 00-1.064-.693c-.327-.127-.818-.268-1.72-.308-.9-.042-1.213-.05-3.483-.05zm0 3.125a5.05 5.05 0 110 10.1 5.05 5.05 0 010-10.1zm0 1.838a3.212 3.212 0 100 6.424 3.212 3.212 0 000-6.424z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">Twitter</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                </a>
            </div>
        </div>
    </div>
</footer>
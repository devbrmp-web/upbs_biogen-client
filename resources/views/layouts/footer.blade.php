<footer class="relative mt-20 pb-32 border-t border-slate-200 bg-white/40 backdrop-blur-xl shadow-[0_-4px_30px_rgba(0,0,0,0.03)] overflow-hidden z-10">
    {{-- Leaf Decoration Footer --}}
    <div class="absolute -top-10 -right-10 w-40 h-40 opacity-[0.03] pointer-events-none rotate-45">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#064E3B" d="M40,-62.1C53.3,-54.5,66.7,-47.1,75.2,-35.6C83.7,-24.1,87.4,-8.6,85.2,6.1C83,20.8,74.9,34.7,64.8,46C54.7,57.3,42.6,66,29.4,72.4C16.1,78.8,1.8,82.9,-13.4,81.1C-28.7,79.2,-44.8,71.5,-57.4,60.1C-70.1,48.7,-79.3,33.5,-82.5,17.4C-85.7,1.4,-82.9,-15.5,-75.4,-30.3C-68,-45.1,-55.9,-57.7,-41.8,-65C-27.7,-72.3,-11.5,-74.3,2.4,-78.3C16.3,-82.3,32.7,-88.2,40,-62.1Z" transform="translate(100 100)" />
        </svg>
    </div>
    <!-- Decorative Elements for Glass Effect Depth -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-green-400/10 rounded-full blur-3xl -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl translate-y-1/2 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 lg:gap-8">
            
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
                        <span class="font-medium hover:text-emerald-600 transition-colors cursor-pointer">(0251) 8333975</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-600 group">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <a href="mailto:brmp.biogen@pertanian.go.id" class="font-medium hover:text-emerald-600 transition-colors">brmp.biogen@pertanian.go.id</a>
                    </li>
                </ul>
            </div>

            <!-- 🗺️ Map -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-gray-900 uppercase mb-6 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-emerald-500 rounded-full"></span>
                    Lokasi Kami
                </h3>
                <div class="w-full h-48 rounded-xl overflow-hidden shadow-sm border border-slate-200">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.5740770169386!2d106.78634729999999!3d-6.575313200000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c50067ce74b5%3A0x191e27fcc8713db!2sBalai%20Besar%20Perakitan%20dan%20Modernisasi%20Bioteknologi%20dan%20Sumber%20Daya%20Genetik%20Pertanian%20(BRMP%20BIOGEN)!5e0!3m2!1sen!2sid!4v1777797566481!5m2!1sen!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="grayscale hover:grayscale-0 transition-all duration-500">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- 🔸 Bottom Bar -->
        <div class="mt-16 pt-8 border-t border-gray-200/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 text-center md:text-left">
                &copy; {{ date('Y') }} <span class="font-semibold text-emerald-700">UPBS BRMP Biogen</span>. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="https://www.instagram.com/brmp_biogen" target="_blank" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.488 2h-.173zM12.314 3.824c-2.27 0-2.583.008-3.483.05-.902.04-1.393.181-1.72.308a2.927 2.927 0 00-1.064.693 2.927 2.927 0 00-.693 1.064c-.127.327-.268.818-.308 1.72-.042.9-.05 1.213-.05 3.483 0 2.27.008 2.583.05 3.483.04.902.181 1.393.308 1.72.22.571.49.992.932 1.434.441.441.862.712 1.434.932.327.127.818.268 1.72.308.9.042 1.213.05 3.483.05 2.27 0 2.583-.008 3.483-.05.902-.04 1.393-.181 1.72-.308a2.927 2.927 0 001.064-.693 2.927 2.927 0 00.693-1.064c.127-.327.268-.818.308-1.72.042-.9.05-1.213.05-3.483 0-2.27-.008-2.583-.05-3.483-.04-.902-.181-1.393-.308-1.72a2.927 2.927 0 00-.693-1.064 2.927 2.927 0 00-1.064-.693c-.327-.127-.818-.268-1.72-.308-.9-.042-1.213-.05-3.483-.05zm0 3.125a5.05 5.05 0 110 10.1 5.05 5.05 0 010-10.1zm0 1.838a3.212 3.212 0 100 6.424 3.212 3.212 0 000-6.424z" clip-rule="evenodd" /></svg>
                </a>
                <a href="https://www.tiktok.com/@brmp_biogen" target="_blank" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">TikTok</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 448 512"><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg>
                </a>
                <a href="https://x.com/brmp_biogen" target="_blank" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">X (Twitter)</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
                </a>
                <a href="https://youtube.com/@brmpbiogen" target="_blank" class="text-gray-400 hover:text-emerald-600 transition-colors transform hover:-translate-y-1 duration-300">
                    <span class="sr-only">YouTube</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.781 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
                </a>
            </div>
        </div>
    </div>
</footer>

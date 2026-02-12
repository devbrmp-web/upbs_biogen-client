<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ Vite::asset('resources/img/logo.png') }}">
    <title>Terlalu Banyak Permintaan - UPBS BRMP Biogen</title>
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex items-center justify-center">

    <div class="relative w-full min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 to-orange-50 px-4">
        <!-- Background Decoration -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-orange-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-red-200/20 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

        <div class="relative z-10 max-w-lg w-full text-center">
            <!-- Glass Card -->
            <div class="bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-8 md:p-12 shadow-xl shadow-orange-900/5">
                <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400/80 to-red-400/80 mb-2 select-none">
                    429
                </h1>
                <div class="space-y-4">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                        Terlalu Banyak Permintaan
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Maaf, Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.
                    </p>
                    <div class="pt-6">
                        <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-orange-600/30 transform hover:-translate-y-0.5 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

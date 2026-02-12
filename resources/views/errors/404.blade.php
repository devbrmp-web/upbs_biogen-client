

@section('title', 'Halaman Tidak Ditemukan - UPBS BRMP Biogen')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 to-green-50 px-4">
    <!-- Background Decoration -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-200/20 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="relative z-10 max-w-lg w-full text-center">
        <!-- Glass Card -->
        <div class="bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-8 md:p-12 shadow-xl shadow-emerald-900/5">
            <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400/80 to-teal-400/80 mb-2 select-none">
                404
            </h1>
            <div class="space-y-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Halaman Tidak Ditemukan
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    Maaf, halaman yang Anda cari mungkin telah dihapus, dipindahkan, atau alamatnya salah.
                </p>
                <div class="pt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-emerald-600/30 transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

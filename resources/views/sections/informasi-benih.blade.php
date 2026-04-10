@php
    $list = $infoVarietas ?? [];
    $uniqueKomoditas = array_values(array_unique(array_map(fn($i)=>$i['komoditas'] ?? '', $list)));
    $limit = 6;
@endphp

<section class="relative py-24 overflow-hidden bg-[#f0f9ff]">
    {{-- Animated Background Blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-emerald-300/30 rounded-full blur-[80px] mix-blend-multiply"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-300/30 rounded-full blur-[80px] mix-blend-multiply"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-[600px] h-[600px] bg-teal-300/30 rounded-full blur-[80px] mix-blend-multiply"></div>
        <div class="absolute bottom-[-10%] right-[10%] w-[500px] h-[500px] bg-lime-300/30 rounded-full blur-[80px] mix-blend-multiply"></div>
    </div>

    {{-- Texture Overlay --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-8">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block py-1 px-3 rounded-full bg-emerald-100/80 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                    Inovasi Benih Unggul
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight mb-4">
                    Buku Saku <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Varietas</span>
                </h2>
                <p class="text-lg text-slate-600 leading-relaxed bg-white/80 rounded-2xl p-4 border border-white/40 shadow-sm inline-block">
                    Kumpulan informasi lengkap karakteristik benih unggul hasil riset UPBS BRMP Biogen untuk produktivitas pertanian masa depan.
                </p>
            </div>
            
            {{-- Filter Glass --}}
            <div class="relative z-10 group min-w-[250px]">
                <div class="absolute -inset-1 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative flex items-center bg-white border border-white/60 rounded-xl p-1.5 shadow-lg shadow-emerald-900/5">
                    <div class="pl-4 pr-3 text-emerald-600">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                    <select id="vs-filter" class="w-full bg-transparent border-none text-slate-700 font-semibold focus:ring-0 cursor-pointer py-2 pl-0 pr-8">
                        <option value="">Semua Komoditas</option>
                        @foreach($uniqueKomoditas as $kom)
                            @if($kom !== '')
                                <option value="{{ $kom }}">{{ ucfirst($kom) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pb-10">
            @forelse($list as $v)
                @php
                    $kom = strtolower($v['komoditas'] ?? '');
                    
                    // Theme Configurations
                    $theme = match(true) {
                        str_contains($kom, 'padi') => [
                            'gradient' => 'from-amber-100/80 to-yellow-50/50',
                            'border' => 'border-amber-200/60',
                            'text_title' => 'text-amber-900',
                            'badge_bg' => 'bg-amber-100',
                            'badge_text' => 'text-amber-700',
                            'icon_bg' => 'bg-amber-500',
                            'icon' => 'fa-wheat-awn'
                        ],
                        str_contains($kom, 'cabai') => [
                            'gradient' => 'from-rose-100/80 to-red-50/50',
                            'border' => 'border-rose-200/60',
                            'text_title' => 'text-rose-900',
                            'badge_bg' => 'bg-rose-100',
                            'badge_text' => 'text-rose-700',
                            'icon_bg' => 'bg-rose-500',
                            'icon' => 'fa-pepper-hot'
                        ],
                        str_contains($kom, 'kedelai') => [
                            'gradient' => 'from-yellow-100/80 to-orange-50/50',
                            'border' => 'border-yellow-200/60',
                            'text_title' => 'text-yellow-900',
                            'badge_bg' => 'bg-yellow-100',
                            'badge_text' => 'text-yellow-700',
                            'icon_bg' => 'bg-yellow-500',
                            'icon' => 'fa-leaf'
                        ],
                        str_contains($kom, 'jeruk') => [
                            'gradient' => 'from-orange-100/80 to-orange-50/50',
                            'border' => 'border-orange-200/60',
                            'text_title' => 'text-orange-900',
                            'badge_bg' => 'bg-orange-100',
                            'badge_text' => 'text-orange-700',
                            'icon_bg' => 'bg-orange-500',
                            'icon' => 'fa-lemon'
                        ],
                        default => [
                            'gradient' => 'from-emerald-100/80 to-teal-50/50',
                            'border' => 'border-emerald-200/60',
                            'text_title' => 'text-emerald-900',
                            'badge_bg' => 'bg-emerald-100',
                            'badge_text' => 'text-emerald-700',
                            'icon_bg' => 'bg-emerald-500',
                            'icon' => 'fa-seedling'
                        ],
                    };

                    // Fallback icons if needed
                    // if(str_contains($kom, 'kedelai')) $theme['icon'] = 'fa-leaf';
                    
                    $extraClass = $loop->index >= $limit ? 'hidden vs-extra' : '';
                @endphp

                {{-- Card Item --}}
                <div class="group relative h-full flex flex-col {{ $extraClass }}" data-kom="{{ $v['komoditas'] ?? '' }}">
                    
                    {{-- Card Glow Effect --}}
                    <div class="absolute -inset-0.5 bg-gradient-to-b from-white/80 to-transparent rounded-[2rem] blur-sm opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    
                    <div class="relative flex flex-col h-full bg-white border {{ $theme['border'] }} rounded-[2rem] p-6 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-emerald-900/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        
                        {{-- Top Decoration --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl {{ $theme['gradient'] }} rounded-bl-[4rem] opacity-50 -z-10 transition-transform group-hover:scale-110"></div>

                        {{-- Card Header --}}
                        <div class="flex items-start justify-between mb-5">
                            <div class="flex-1 pr-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $theme['badge_bg'] }} {{ $theme['badge_text'] }} mb-2">
                                    {{ $v['komoditas'] ?: 'Varietas' }}
                                </span>
                                <h3 class="text-xl font-bold {{ $theme['text_title'] }} leading-tight">
                                    {{ $v['nama_varietas'] ?? '-' }}
                                </h3>
                                <div class="flex items-center gap-1.5 mt-2 text-xs font-medium text-slate-500">
                                    <i class="fa-solid fa-calendar-check text-slate-400"></i>
                                    <span>{{ $v['tanggal_keputusan'] ?? 'Tahun -' }}</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 rounded-2xl {{ $theme['icon_bg'] }} shadow-lg shadow-gray-200 flex items-center justify-center text-white transform group-hover:rotate-12 transition-transform duration-300">
                                <i class="fa-solid {{ $theme['icon'] }} text-xl"></i>
                            </div>
                        </div>

                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            @php
                                $isPadi = str_contains($kom, 'padi');
                                $isCabai = str_contains($kom, 'cabai');
                                $isKedelai = str_contains($kom, 'kedelai');
                                $isJeruk = str_contains($kom, 'jeruk');
                                $isKentang = str_contains($kom, 'kentang');
                                
                                // Dynamic Labels
                                $lblTekstur = match(true) {
                                    $isPadi => 'Tekstur',
                                    $isCabai => 'Tipe',
                                    $isKedelai => 'Tipe',
                                    default => 'Golongan'
                                };
                                
                                $valTekstur = $v['tekstur_nasi'] ?? $v['golongan'] ?? '-';
                                
                                // Fallback logic is now handled in Service, but we can refine here if needed
                                $valHasil = $v['rata_rata_hasil'] !== '-' ? $v['rata_rata_hasil'] : ($v['potensi_hasil'] ?? '-');

                                $stats = [
                                    ['label' => 'Umur', 'val' => $v['umur_tanaman_hari'] ?? '-', 'icon' => 'fa-clock'],
                                    ['label' => 'Potensi', 'val' => $v['potensi_hasil'] ?? '-', 'icon' => 'fa-chart-line'],
                                    ['label' => 'Hasil', 'val' => $valHasil, 'icon' => 'fa-scale-balanced'],
                                    ['label' => $lblTekstur, 'val' => $valTekstur, 'icon' => 'fa-utensils'],
                                ];
                            @endphp
                            @foreach($stats as $stat)
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 hover:bg-white transition-colors">
                                    <p class="text-[10px] uppercase text-slate-400 font-bold tracking-wider mb-0.5 flex items-center gap-1">
                                        <i class="fa-solid {{ $stat['icon'] }} opacity-50"></i> {{ $stat['label'] }}
                                    </p>
                                    <p class="text-xs font-bold text-slate-700 truncate" title="{{ $stat['val'] }}">
                                        {{ Str::limit($stat['val'] ?? '-', 15) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Divider --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-5"></div>

                        {{-- Detail Info --}}
                        <div class="space-y-4 flex-grow">
                            @if(!empty($v['ketahanan_hama']) && $v['ketahanan_hama'] !== '-')
                                <div class="relative">
                                    <h4 class="text-xs font-bold text-slate-700 mb-1 flex items-center gap-2">
                                        <i class="fa-solid fa-shield-cat text-rose-500"></i> Ketahanan Hama
                                    </h4>
                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all duration-300 bg-white/40 rounded-lg p-2 border border-white/40">
                                        {{ $v['ketahanan_hama'] }}
                                    </p>
                                </div>
                            @endif
                            
                            @if(!empty($v['ketahanan_penyakit']) && $v['ketahanan_penyakit'] !== '-')
                                <div class="relative">
                                    <h4 class="text-xs font-bold text-slate-700 mb-1 flex items-center gap-2">
                                        <i class="fa-solid fa-notes-medical text-teal-500"></i> Ketahanan Penyakit
                                    </h4>
                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all duration-300 bg-white/40 rounded-lg p-2 border border-white/40">
                                        {{ $v['ketahanan_penyakit'] }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer Highlight --}}
                        @if(!empty($v['keunggulan']))
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <div class="bg-emerald-50/50 rounded-xl p-3 border border-emerald-100/50">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Keunggulan Utama</p>
                                <p class="text-xs font-medium text-emerald-800 italic leading-snug">
                                    "{{ Str::limit($v['keunggulan'], 80) }}"
                                </p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100/50 backdrop-blur-md mb-6 shadow-inner">
                        <i class="fa-solid fa-box-open text-slate-300 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-600">Belum ada data varietas</h3>
                    <p class="text-slate-400 text-sm">Silakan cek kembali nanti.</p>
                </div>
            @endforelse
        </div>

        {{-- Load More Button --}}
        @if(count($list) > $limit)
        <div class="text-center mt-8">
            <button id="vs-expand-btn" data-expanded="false" class="group relative inline-flex items-center gap-3 px-8 py-3.5 bg-white/70 backdrop-blur-xl border border-white/80 rounded-full shadow-lg shadow-emerald-900/5 hover:shadow-emerald-900/10 hover:scale-105 transition-all duration-300 z-20">
                <span class="text-sm font-bold text-emerald-700 uppercase tracking-wider group-hover:text-emerald-600">Lihat Semua Varietas</span>
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition-colors">
                    <i class="fa-solid fa-arrow-down text-xs transition-transform duration-300 group-hover:translate-y-0.5"></i>
                </span>
            </button>
        </div>
        @endif
        
    </div>
</section>

<style>
    /* Custom Animations */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    /* Smooth Scrollbar for Detail Areas if needed */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-none {
        -webkit-line-clamp: unset;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const btn = document.getElementById('vs-expand-btn');
        if (btn) {
            const extras = document.querySelectorAll('.vs-extra');
            const icon = btn.querySelector('.fa-arrow-down, .fa-arrow-up');
            const label = btn.querySelector('span:first-child');
            
            btn.addEventListener('click', function(){
                const isExpanded = btn.getAttribute('data-expanded') === 'true';
                
                extras.forEach(el => {
                    if (isExpanded) {
                        el.classList.add('hidden');
                    } else {
                        el.classList.remove('hidden');
                        // Simple entry animation
                        el.animate([
                            { opacity: 0, transform: 'translateY(20px)' },
                            { opacity: 1, transform: 'translateY(0)' }
                        ], {
                            duration: 400,
                            easing: 'cubic-bezier(0.4, 0, 0.2, 1)'
                        });
                    }
                });

                btn.setAttribute('data-expanded', !isExpanded);
                
                if (!isExpanded) {
                    label.textContent = 'Tutup';
                    if(icon) {
                        icon.classList.remove('fa-arrow-down');
                        icon.classList.add('fa-arrow-up');
                    }
                } else {
                    label.textContent = 'Lihat Semua Varietas';
                    if(icon) {
                        icon.classList.remove('fa-arrow-up');
                        icon.classList.add('fa-arrow-down');
                    }
                    // Optional: Scroll to top of grid
                    // document.getElementById('vs-filter').scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        // Filter Logic
        const filter = document.getElementById('vs-filter');
        if (filter) {
            filter.addEventListener('change', function(){
                const val = this.value;
                const cards = document.querySelectorAll('[data-kom]');
                let visibleCount = 0;
                
                cards.forEach(card => {
                    const kom = card.getAttribute('data-kom') || '';
                    // Reset hidden class if it was hidden by "Load More"
                    card.classList.remove('hidden'); 
                    
                    if (val === '' || kom === val) {
                        card.style.display = '';
                        card.animate([
                            { opacity: 0, transform: 'scale(0.95)' },
                            { opacity: 1, transform: 'scale(1)' }
                        ], { duration: 300 });
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Hide Load More button if filtering (or adjust logic to handle pagination within filter)
                // For simplicity, we hide the load more button when filtering specific categories
                if (btn) {
                    if (val !== '') {
                        btn.style.display = 'none';
                    } else {
                        btn.style.display = '';
                        // Reset to initial state (show only limit) if needed, or keep all shown
                        // To be user-friendly, if they select "All", we might want to reset to "Show Less" state or keep "Show All"
                    }
                }
            });
        }
    });
</script>

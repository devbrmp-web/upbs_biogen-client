@php
    $list = $infoVarietas ?? [];
@endphp
<section class="relative py-16">
    <div class="absolute inset-0 -z-10 immersive-bg"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $uniqueKomoditas = array_values(array_unique(array_map(fn($i)=>$i['komoditas'] ?? '', $list)));
        @endphp
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Varietas</h2>
                <p class="mt-1 text-sm md:text-base text-gray-600">Informasi benih unggul dari Buku Saku</p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white backdrop-blur border border-white/40 shadow-sm">
                <span class="text-xs font-medium text-emerald-700">Komoditas</span>
                <select id="vs-filter" class="text-sm bg-transparent focus:outline-none">
                    <option value="">Semua</option>
                    @foreach($uniqueKomoditas as $kom)
                        @if($kom !== '')
                            <option value="{{ $kom }}">{{ ucfirst($kom) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        @php $limit = 6; @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($list as $v)
                @php
                    $kom = strtolower($v['komoditas'] ?? '');
                    $chip = match(true) {
                        str_contains($kom,'padi') => 'bg-yellow-100 text-yellow-800',
                        str_contains($kom,'cabai') => 'bg-red-100 text-red-800',
                        str_contains($kom,'kedelai') => 'bg-amber-100 text-amber-800',
                        default => 'bg-emerald-100 text-emerald-800',
                    };
                    $extraClass = $loop->index >= $limit ? 'hidden vs-extra' : '';
                @endphp
                <div class="glass-card {{ $extraClass }} rounded-2xl border border-white/30 shadow-sm transition-all duration-300" data-kom="{{ $v['komoditas'] ?? '' }}">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-sm px-3 py-1 rounded-full bg-white/60 border border-white/40">{{ $v['nama_varietas'] }}</div>
                            <span class="text-xs px-2 py-1 rounded {{ $chip }}">{{ $v['komoditas'] ?: 'Varietas' }}</span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="rounded-xl bg-white/40 backdrop-blur p-3 border border-white/30 flex items-center gap-3">
                                <i class="fa-solid fa-leaf text-emerald-700 w-8 h-8 rounded-full flex items-center justify-center bg-emerald-100" aria-hidden="true"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500">Asal</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <span class="block w-[280px] md:w-[320px] lg:w-[360px] break-words">{{ $v['asal'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl bg-white/40 backdrop-blur p-3 border border-white/30 flex items-center gap-3">
                                <i class="fa-solid fa-clock text-blue-700 w-8 h-8 rounded-full flex items-center justify-center bg-blue-100" aria-hidden="true"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500">Umur Tanaman</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <span class="block w-[280px] md:w-[320px] lg:w-[360px] break-words">{{ $v['umur_tanaman_hari'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl bg-white/40 backdrop-blur p-3 border border-white/30 flex items-center gap-3">
                                <i class="fa-solid fa-chart-line text-amber-700 w-8 h-8 rounded-full flex items-center justify-center bg-amber-100" aria-hidden="true"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500">Rata-rata Hasil</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <span class="block w-[280px] md:w-[320px] lg:w-[360px] break-words">{{ $v['rata_rata_hasil'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl bg-white/40 backdrop-blur p-3 border border-white/30 flex items-center gap-3">
                                <i class="fa-solid fa-grip-lines text-purple-700 w-8 h-8 rounded-full flex items-center justify-center bg-purple-100" aria-hidden="true"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500">Tekstur</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <span class="block w-[280px] md:w-[320px] lg:w-[360px] break-words">{{ $v['tekstur_nasi'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 rounded-xl bg-white/40 backdrop-blur p-3 border border-white/30">
                            <p class="text-xs text-gray-500 mb-2">Ketahanan</p>
                            <div class="space-y-2 text-sm text-gray-900">
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-shield-halved text-emerald-700 mt-0.5" aria-hidden="true"></i>
                                    <div>
                                        <span class="font-medium">Hama:</span>
                                        <span class="inline-block align-middle" title="{{ $v['ketahanan_hama'] ?? '-' }}">{{ $v['ketahanan_hama'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-seedling text-blue-700 mt-0.5" aria-hidden="true"></i>
                                    <div>
                                        <span class="font-medium">Penyakit:</span>
                                        <span class="inline-block align-middle" title="{{ $v['ketahanan_penyakit'] ?? '-' }}">{{ $v['ketahanan_penyakit'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-600">Data belum tersedia.</div>
            @endforelse
        </div>

        @if(count($list) > $limit)
        <div class="mt-8 flex items-center justify-center">
            <button id="vs-expand-btn" data-expanded="false" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 backdrop-blur border border-white/30 shadow-sm hover:bg-white/70 transition">
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                <span>Lihat semua varietas</span>
            </button>
        </div>
        @endif
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        var btn = document.getElementById('vs-expand-btn');
        if (btn) {
            var extras = document.querySelectorAll('.vs-extra');
            btn.addEventListener('click', function(){
                var expanded = btn.getAttribute('data-expanded') === 'true';
                extras.forEach(function(el){ el.classList.toggle('hidden'); });
                btn.setAttribute('data-expanded', expanded ? 'false' : 'true');
                var icon = btn.querySelector('i');
                var label = btn.querySelector('span');
                if (!expanded) {
                    label.textContent = 'Tutup';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    label.textContent = 'Lihat semua varietas';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        }
        var filter = document.getElementById('vs-filter');
        if (filter) {
            filter.addEventListener('change', function(){
                var v = this.value;
                var cards = document.querySelectorAll('[data-kom]');
                cards.forEach(function(c){
                    var kom = c.getAttribute('data-kom') || '';
                    c.style.display = (v === '' || kom === v) ? '' : 'none';
                });
            });
        }
    });
</script>

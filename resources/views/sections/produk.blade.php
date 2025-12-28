<section class="relative py-20 bg-gradient-to-b from-green-50 to-white animate-fadeIn">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        @php
            try {
                $csvItems = \App\Services\VarietyInfoService::getAllVarieties(6);
            } catch (\Throwable $e) {
                $csvItems = [];
            }
            $infos = array_map(function($v){
                $name = $v['nama_varietas'] ?? 'Varietas Unggul';
                $komo = $v['komoditas'] ?? '';
                $umur = $v['umur_tanaman_hari'] ?? '-';
                $hasil = $v['rata_rata_hasil'] ?? '-';
                $tekstur = $v['tekstur_nasi'] ?? '-';
                $ketH = $v['ketahanan_hama'] ?? '';
                $ketP = $v['ketahanan_penyakit'] ?? '';
                $ket = trim(implode(' • ', array_filter([$ketH, $ketP])));
                return [
                    'name' => $name,
                    'image' => 'https://placehold.co/800x600?text=' . urlencode($name),
                    'specs' => [
                        ['label' => 'Asal', 'value' => $v['asal'] ?? '-'],
                        ['label' => 'Umur Tanaman', 'value' => $umur],
                        ['label' => 'Rata-rata Hasil', 'value' => $hasil],
                        ['label' => 'Tekstur', 'value' => $tekstur],
                        ['label' => 'Ketahanan', 'value' => $ket !== '' ? $ket : '-'],
                        ['label' => 'Komoditas', 'value' => $komo !== '' ? ucfirst($komo) : '—'],
                    ],
                    'tags' => array_filter([ $komo !== '' ? ucfirst($komo) : null, 'Buku Saku' ])
                ];
            }, $csvItems);
        @endphp
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">Informasi Benih</h2>
            <p class="mt-3 text-gray-700 max-w-2xl mx-auto">Gambaran karakteristik benih unggul dari UPBS BRMP Biogen untuk membantu Anda memilih varietas yang sesuai.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($infos as $info)
                <div class="group relative rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="h-40 sm:h-44 md:h-48 overflow-hidden">
                        <img src="{{ $info['image'] }}" alt="{{ $info['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" onerror="this.src='https://placehold.co/800x600?text=No+Image'">
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">{{ $info['name'] }}</h3>
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">Unggul</span>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                            @foreach ($info['specs'] as $s)
                                <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2">
                                    <div class="text-xs text-gray-500">{{ $s['label'] }}</div>
                                    <div class="font-medium text-gray-900">{{ $s['value'] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($info['tags'] as $t)
                                <span class="px-2.5 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700">{{ $t }}</span>
                            @endforeach
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800">
                                Lihat Katalog
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </a>
                            <span class="text-xs text-gray-500">Dummy data</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-green-600 text-white font-semibold shadow-lg hover:bg-green-700 transition">
                Telusuri Varietas
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </a>
        </div>
    </div>
</section>

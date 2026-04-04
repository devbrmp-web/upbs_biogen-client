@if(!$order)
    <div class="bg-white/60 backdrop-blur-xl border border-red-200/50 shadow-xl rounded-3xl p-8 text-center animate-fadeIn">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-magnifying-glass-minus text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Pesanan Tidak Ditemukan</h3>
        <p class="text-slate-500">Maaf, kami tidak dapat menemukan pesanan dengan kode tersebut. Mohon periksa kembali kode pesanan Anda.</p>
    </div>
@else
    <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-6 md:p-8 animate-fadeIn hover:shadow-2xl transition-all duration-300 group">
        @php
            $map = [
                'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'awaiting_payment' => 'bg-amber-100 text-amber-700 border-amber-200',
                'pending_verification' => 'bg-orange-100 text-orange-700 border-orange-200',
                'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                'delivery_coordination' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                'shipped' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                'pickup_ready' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                'cancelled' => 'bg-red-100 text-red-700 border-red-200'
            ];
            $cls = $map[$order->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';

            $statusLabelMap = [
                'awaiting_payment' => 'Menunggu Pembayaran',
                'pending_verification' => 'Menunggu Verifikasi',
                'paid' => 'Lunas',
                'processing' => 'Diproses',
                'pickup_ready' => 'Siap Diambil',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'shipped' => 'Dikirim',
                'delivery_coordination' => 'Koordinasi Pengiriman'
            ];
            $statusLabel = $statusLabelMap[$order->status] ?? ucwords(str_replace('_', ' ', $order->status));
        @endphp

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-8 border-b border-slate-200/60 pb-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fa-solid fa-box-open text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mb-1">Kode Pesanan</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight">{{ $order->order_code }}</p>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Items</span>
                    <span class="font-semibold">{{ count($order->items ?? []) }} Produk</span>
                </div>
            </div>
            <span class="px-4 py-2 rounded-xl text-sm font-bold border {{ $cls }} shadow-sm">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white/50 p-4 rounded-2xl border border-white/60">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block mb-2">
                    <i class="fa-solid fa-truck-fast mr-1 text-slate-400"></i> Kurir
                </span>
                <div class="font-semibold text-slate-800 text-lg">{{ $order->courier_name ?? '-' }}</div>
            </div>
            <div class="bg-white/50 p-4 rounded-2xl border border-white/60">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block mb-2">
                    <i class="fa-solid fa-barcode mr-1 text-slate-400"></i> No. Resi
                </span>
                <div class="font-mono font-semibold text-slate-800 text-lg tracking-wide">{{ $order->tracking_number ?? '-' }}</div>
            </div>
            <div class="bg-white/50 p-4 rounded-2xl border border-white/60">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block mb-2">
                    <i class="fa-solid fa-location-dot mr-1 text-slate-400"></i> Status Pengiriman
                </span>
                <div class="font-semibold text-slate-800 text-lg">{{ $order->shipment_status ?? '-' }}</div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="/pesanan/{{ $order->order_code }}" 
               class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-bold transition-all transform hover:-translate-y-1 hover:shadow-lg">
                <span>Lihat Detail Lengkap</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endif

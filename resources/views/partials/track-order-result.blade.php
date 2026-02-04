@if(!$order)
    <div class="bg-white p-6 rounded-xl shadow text-center animate-fadeIn">
        <p class="text-red-600 font-semibold">Pesanan tidak ditemukan.</p>
    </div>
@else
    <div class="bg-white p-6 rounded-xl shadow animate-fadeIn">
        @php
            $map = [
                'completed' => 'bg-green-100 text-green-700',
                'paid' => 'bg-green-100 text-green-700',
                'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
                'pending_verification' => 'bg-orange-100 text-orange-700', // Added
                'processing' => 'bg-blue-100 text-blue-700',
                'delivery_coordination' => 'bg-blue-100 text-blue-700',
                'shipped' => 'bg-blue-100 text-blue-700',
                'pickup_ready' => 'bg-blue-100 text-blue-700'
            ];
            $cls = $map[$order->status] ?? 'bg-gray-100 text-gray-800';

            $statusLabelMap = [
                'awaiting_payment' => 'Menunggu Pembayaran',
                'pending_verification' => 'Menunggu Verifikasi', // Added
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
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-xs text-gray-600">Kode Pesanan</p>
                    <p class="text-lg font-semibold">{{ $order->order_code }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full {{ $cls }}">{{ $statusLabel }}</span>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="rounded-lg bg-gray-50 px-4 py-2">
                    <span class="text-xs text-gray-600">Kurir</span>
                    <div class="font-medium">{{ $order->courier_name ?? '-' }}</div>
                </div>
                <div class="rounded-lg bg-gray-50 px-4 py-2">
                    <span class="text-xs text-gray-600">Tracking</span>
                    <div class="font-medium">{{ $order->tracking_number ?? '-' }}</div>
                </div>
                <div class="rounded-lg bg-gray-50 px-4 py-2">
                    <span class="text-xs text-gray-600">Pengiriman</span>
                    <div class="font-medium">{{ $order->shipment_status ?? '-' }}</div>
                </div>
            </div>
            <div>
                <a href="/pesanan/{{ $order->order_code }}" class="inline-flex bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
@endif

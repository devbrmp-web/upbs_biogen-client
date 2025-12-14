@extends('layouts.app')

@section('title', 'Cek Pesanan • UPBS BRMP Biogen')

@section('content')

@php
    $hasSearch = request()->has('search');
@endphp

<div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 mt-16 page-animate-fadeIn">

    <h1 class="text-2xl font-bold mb-6 text-center">Cek Pesanan</h1>

    {{-- =============================================================== --}}
    {{--   GRID SEBELAH KIRI FORM – SEBELAH KANAN HISTORY               --}}
    {{-- =============================================================== --}}
    <div id="main-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- =========================================================== --}}
        {{--                            FORM                             --}}
        {{-- =========================================================== --}}
        <div
            id="form-col"
            class="
                {{ $hasSearch ? 'lg:col-span-1' : 'lg:col-span-3' }}
                flex justify-center
            "
        >
            <div class="bg-white p-6 rounded-xl shadow w-full max-w-md transition-all duration-300">

                <form method="GET" action="/cek-pesanan" id="track-form">

                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Metode Pencarian</label>
                        <select name="method" class="w-full border rounded-lg p-3">
                            <option value="tracking" {{ request('method')=='tracking'?'selected':'' }}>Tracking Number</option>
                            <option value="order_code" {{ request('method')=='order_code'?'selected':'' }}>Kode Pesanan</option>
                            <option value="phone" {{ request('method')=='phone'?'selected':'' }}>Nomor HP</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Masukkan nilai pencarian</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="w-full border rounded-lg p-3"
                               placeholder="Masukkan nilai pencarian"
                               required>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold">
                        Cari Pesanan
                    </button>

                </form>

            </div>
        </div>

        {{-- =========================================================== --}}
        {{--      KANAN = HISTORY LIST / HASIL PENCARIAN                --}}
        {{-- =========================================================== --}}
        <div id="result-col" class="lg:col-span-2 {{ !$hasSearch ? 'hidden' : '' }}">

            {{-- ====================== HISTORY CONTAINER ====================== --}}
            <div id="history-section" class="mb-6 hidden">

                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold">Riwayat Pesanan</h2>
                    <button id="clear-history"
                        class="text-red-600 hover:underline text-sm">Hapus Semua</button>
                </div>

                <div id="history-list"
                     class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>

            </div>

            {{-- ========================= HASIL SEARCH ========================= --}}
            @if($hasSearch)

                @if(!$order)
                    <div class="bg-white p-6 rounded-xl shadow text-center animate-fadeIn">
                        <p class="text-red-600 font-semibold">Pesanan tidak ditemukan.</p>
                    </div>
                @else

                    {{-- SAVE TO LOCALSTORAGE --}}
                    <script>
                    document.addEventListener("DOMContentLoaded", function(){
                        let data = @json($order);
                        let list = JSON.parse(localStorage.getItem("lastOrderData") || "[]");

                        list = list.filter(x => x.order_code !== data.order_code);
                        list.push(data);

                        localStorage.setItem("lastOrderData", JSON.stringify(list));
                    });
                    </script>

                    <div class="bg-white p-6 rounded-xl shadow animate-fadeIn">

                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Kode Pesanan</p>
                                <p class="text-lg font-semibold">{{ $order->order_code }}</p>
                            </div>

                            @php
                                $map = [
                                    'completed' => 'bg-green-100 text-green-700',
                                    'paid' => 'bg-green-100 text-green-700',
                                    'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'delivery_coordination' => 'bg-blue-100 text-blue-700',
                                    'shipped' => 'bg-blue-100 text-blue-700',
                                    'pickup_ready' => 'bg-blue-100 text-blue-700'
                                ];
                                $cls = $map[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp

                            <span class="px-3 py-1 text-sm rounded-full {{ $cls }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="p-4 rounded-lg bg-gray-50">
                                <p class="text-sm text-gray-600">Kurir</p>
                                <p class="font-medium">{{ $order->courier_name ?? '-' }}</p>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50">
                                <p class="text-sm text-gray-600">Tracking Number</p>
                                <p class="font-medium">{{ $order->tracking_number ?? '-' }}</p>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50">
                                <p class="text-sm text-gray-600">Status Pengiriman</p>
                                <p class="font-medium">{{ $order->shipment_status ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="/pesanan/{{ $order->order_code }}"
                                class="inline-flex bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                @endif
            @endif

        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{--                  SCRIPT HISTORY + AUTO LAYOUT                     --}}
{{-- ================================================================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const historySection = document.getElementById("history-section");
    const historyList = document.getElementById("history-list");
    const clearBtn = document.getElementById("clear-history");

    const formCol = document.getElementById("form-col");
    const resultCol = document.getElementById("result-col");

    let lastOrderData = JSON.parse(localStorage.getItem("lastOrderData") || "[]");

    // ⭐ Jika history ada → tampilkan layout 2 kolom
    if (lastOrderData.length > 0) {
        historySection.classList.remove("hidden");

        formCol.classList.remove("lg:col-span-3");
        formCol.classList.add("lg:col-span-1");

        resultCol.classList.remove("hidden");
    }

    function formatCurrency(num) {
        if (!num) return "-";
        return "Rp " + Number(num).toLocaleString("id-ID");
    }

    function renderHistory() {
        historyList.innerHTML = "";

        lastOrderData.slice().reverse().forEach((o, idxRev) => {
            const originalIndex = lastOrderData.length - 1 - idxRev;

            const card = document.createElement("div");
            card.className = "bg-white p-4 rounded-xl shadow relative";

            const statusClass =
                (o.status === 'completed' || o.status === 'paid') ? 'bg-green-100 text-green-700'
                : (o.status === 'awaiting_payment') ? 'bg-yellow-100 text-yellow-700'
                : 'bg-blue-100 text-blue-700';

            card.innerHTML = `
                <button class="absolute top-2 right-2 text-red-500 remove-history"
                        data-index="${originalIndex}">✕</button>

                <p class="text-sm text-gray-600">Kode Pesanan</p>
                <p class="font-semibold text-lg">${o.order_code}</p>

                <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full ${statusClass}">
                    ${o.status}
                </span>

                <p class="mt-3 text-sm">
                    <b>${o.items?.length ?? 0}</b> item • Total <b>${formatCurrency(o.total_amount)}</b>
                </p>

                <a href="/pesanan/${o.order_code}"
                   class="mt-3 inline-block bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                    Lihat Detail
                </a>
            `;
            historyList.appendChild(card);
        });
    }

    renderHistory();
});
</script>

@endsection

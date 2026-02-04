@extends('layouts.app')

@section('title', 'Cek Pesanan • UPBS BRMP Biogen')

@section('content')

@php
    $hasSearch = request()->has('search');
@endphp

<div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 mt-28 page-animate-fadeIn">

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

                    <input type="hidden" name="method" id="methodField" value="{{ request('method','order_code') }}">

                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Masukkan nilai pencarian</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="w-full border rounded-lg p-3"
                               placeholder="Masukkan kode pesanan atau nomor HP"
                               required>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold">
                        <span id="btn-text">Cari Pesanan</span>
                        <span id="btn-loading" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i> Memuat...
                        </span>
                    </button>

                </form>

            </div>
        </div>

        {{-- =========================================================== --}}
        {{--      KANAN = HISTORY LIST / HASIL PENCARIAN                --}}
        {{-- =========================================================== --}}
        <div id="result-col" class="lg:col-span-2 hidden">

            {{-- ====================== HISTORY CONTAINER ====================== --}}
            <div id="history-section" class="mb-6 hidden">

                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold">Riwayat Pesanan</h2>
                    <button id="clear-history"
                        class="text-red-600 hover:underline text-sm">Hapus Semua</button>
                </div>

                <div id="history-list"
                     class="space-y-4"></div>

            </div>

            {{-- ========================= HASIL SEARCH ========================= --}}
            <div id="search-result-container">
                @if($hasSearch)
                    @include('partials.track-order-result', ['order' => $order])
                @endif
            </div>

        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{--                  SCRIPT COMBINED                                  --}}
{{-- ================================================================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const historySection = document.getElementById("history-section");
    const historyList = document.getElementById("history-list");
    const clearBtn = document.getElementById("clear-history");
    const formCol = document.getElementById("form-col");
    const resultCol = document.getElementById("result-col");
    const form = document.getElementById('track-form');
    const resultContainer = document.getElementById("search-result-container");
    const btnText = document.getElementById("btn-text");
    const btnLoading = document.getElementById("btn-loading");

    // Helper: Get Data
    function getData() {
        return JSON.parse(localStorage.getItem("lastOrderData") || "[]");
    }
    function setData(data) {
        localStorage.setItem("lastOrderData", JSON.stringify(data));
    }
    function formatCurrency(num) {
        if (!num) return "-";
        return "Rp " + Number(num).toLocaleString("id-ID");
    }

    // 1. Render History
    function renderHistory() {
        const data = getData();
        historyList.innerHTML = "";
        
        if (data.length === 0) {
            historySection.classList.add("hidden");
            // Only hide result col if there is no search result either
            if (!resultContainer.innerHTML.trim()) {
                 resultCol.classList.add("hidden");
                 formCol.classList.remove("lg:col-span-1");
                 formCol.classList.add("lg:col-span-3");
            }
            return;
        }

        historySection.classList.remove("hidden");
        resultCol.classList.remove("hidden");
        formCol.classList.remove("lg:col-span-3");
        formCol.classList.add("lg:col-span-1");

        // Fetch fresh status for each order and update localStorage
        const refreshPromises = data.map(async (order, index) => {
            try {
                const response = await fetch(`/api/orders/${order.order_code}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const result = await response.json();
                    if (result.data && result.data.status) {
                        data[index].status = result.data.status;
                    }
                }
            } catch (e) {
                console.warn('Could not refresh status for', order.order_code);
            }
        });

        // Wait for all refreshes, then render
        Promise.all(refreshPromises).then(() => {
            setData(data); // Save updated statuses to localStorage

            data.slice().reverse().forEach((o, idxRev) => {
                const originalIndex = data.length - 1 - idxRev;
                const card = document.createElement("div");
                card.className = "bg-white p-4 pl-10 rounded-xl shadow relative flex flex-col md:flex-row md:items-center md:justify-between gap-4";
                
                const statusClass = (o.status === 'completed' || o.status === 'paid') ? 'bg-green-100 text-green-700'
                    : (o.status === 'awaiting_payment') ? 'bg-yellow-100 text-yellow-700'
                    : (o.status === 'pending_verification') ? 'bg-orange-100 text-orange-700'
                    : (o.status === 'cancelled') ? 'bg-red-100 text-red-700'
                    : (o.status === 'processing' || o.status === 'pickup_ready') ? 'bg-blue-100 text-blue-700'
                    : 'bg-gray-100 text-gray-800';

                const statusMap = {
                    'awaiting_payment': 'Menunggu Pembayaran',
                    'pending_verification': 'Menunggu Verifikasi',
                    'paid': 'Lunas',
                    'processing': 'Diproses',
                    'pickup_ready': 'Siap Diambil',
                    'completed': 'Selesai',
                    'cancelled': 'Dibatalkan',
                    'shipped': 'Dikirim',
                    'delivery_coordination': 'Koordinasi Pengiriman'
                };

                const statusLabel = statusMap[o.status] || o.status;

                card.innerHTML = `
                    <button class="absolute top-3 left-3 text-red-600 font-extrabold text-xl hover:scale-110 transition remove-history" data-index="${originalIndex}">✕</button>
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Kode Pesanan</p>
                            <p class="font-semibold text-lg">${o.order_code}</p>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full ${statusClass}">${statusLabel}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm"><b>${o.items?.length ?? 0}</b> item</span>
                        <span class="text-sm">Total <b>${formatCurrency(o.total_amount)}</b></span>
                    </div>
                    <div>
                        <a href="/pesanan/${o.order_code}" class="inline-block bg-green-600 text-white px-3 py-1 rounded-lg text-sm">Lihat Detail</a>
                    </div>
                `;
                historyList.appendChild(card);
            });
        });
    }

    // Initial Render
    renderHistory();
    // Also check if PHP rendered a result (on first load with params)
    if (resultContainer.innerHTML.trim().length > 0) {
        resultCol.classList.remove("hidden");
        formCol.classList.remove("lg:col-span-3");
        formCol.classList.add("lg:col-span-1");
        
        // Also save to history if PHP passed it
        // We can check if there's a script block that did it, or just rely on the AJAX flow from now on.
        // The existing blade had a script block to save orderJson. I will keep it implicitly by checking the PHP variable $order in blade if needed, 
        // but since I'm rewriting the file, I'll rely on the user to use the form for new searches, 
        // OR I can parse the PHP order if present.
        // For now, let's just let the PHP render happen.
    }

    // 2. Handle Form Submit (AJAX)
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            
            // Auto detect method
            var input = form.querySelector('input[name="search"]');
            var methodEl = document.getElementById('methodField');
            var v = (input?.value || '').trim();
            var digits = v.replace(/\D/g, '');
            var isPhone = false;
            if (digits.length >= 10) isPhone = true;
            if (/^\+?62/.test(v) || /^08/.test(v)) isPhone = true;
            methodEl.value = isPhone ? 'phone' : 'order_code';

            // Loading state
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            try {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const url = form.action + '?' + params.toString();

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Update URL
                    window.history.pushState({}, '', url);

                    // Render Result
                    resultContainer.innerHTML = data.html;
                    
                    // Update Layout
                    resultCol.classList.remove("hidden");
                    formCol.classList.remove("lg:col-span-3");
                    formCol.classList.add("lg:col-span-1");

                    // Save to History if order found
                    if (data.order) {
                        let list = getData();
                        // Remove existing same order
                        list = list.filter(x => x.order_code !== data.order.order_code);
                        list.push(data.order);
                        setData(list);
                        renderHistory();
                    }
                } else {
                    console.error('Error fetching order');
                }
            } catch (error) {
                console.error(error);
            } finally {
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        });
    }

    // 3. Handle Delete History
    historyList.addEventListener("click", function (e) {
        if (!e.target.classList.contains("remove-history")) return;
        const index = e.target.dataset.index;
        if (index === undefined) return;

        let data = getData();
        data.splice(index, 1);
        setData(data);
        renderHistory();
    });

    // 4. Handle Clear All
    if (clearBtn) {
        clearBtn.addEventListener("click", function () {
            if (confirm("Hapus semua riwayat?")) {
                setData([]);
                renderHistory();
                // If no result currently shown, hide result col
                if (!resultContainer.innerHTML.trim()) {
                     resultCol.classList.add("hidden");
                     formCol.classList.remove("lg:col-span-1");
                     formCol.classList.add("lg:col-span-3");
                }
            }
        });
    }

    // Handle existing PHP-rendered order for history (on page load)
    @if(isset($order) && $order)
        const currentOrder = @json($order);
        let list = getData();
        if (!list.find(x => x.order_code === currentOrder.order_code)) {
            list.push(currentOrder);
            setData(list);
            renderHistory();
        }
    @endif
});
</script>
@endsection

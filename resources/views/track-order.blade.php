@extends('layouts.app')

@section('title', 'Cek Pesanan • UPBS BRMP Biogen')

@section('content')

@php
    $hasSearch = request()->has('search');
@endphp

<div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 mt-28 page-animate-fadeIn relative z-10">

    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-emerald-400/20 rounded-full blur-3xl -z-10 opacity-50 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl -z-10 opacity-30 pointer-events-none"></div>

    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2 tracking-tight">Cek Status Pesanan</h1>
        <p class="text-slate-500">Pantau proses pesanan Anda dengan mudah dan cepat</p>
    </div>

    {{-- =============================================================== --}}
    {{--   GRID SEBELAH KIRI FORM – SEBELAH KANAN HISTORY               --}}
    {{-- =============================================================== --}}
    <div id="main-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- =========================================================== --}}
        {{--                            FORM                             --}}
        {{-- =========================================================== --}}
        <div
            id="form-col"
            class="
                {{ $hasSearch ? 'lg:col-span-1' : 'lg:col-span-3' }}
                flex justify-center transition-all duration-500 ease-in-out
            "
        >
            <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-2xl rounded-3xl p-8 w-full max-w-md transition-all duration-300 hover:shadow-emerald-900/5">

                <form method="GET" action="/cek-pesanan" id="track-form">

                    <input type="hidden" name="method" id="methodField" value="{{ request('method','order_code') }}">

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-semibold text-slate-700 ml-1">Kode Pesanan / No. HP</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                            </div>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="w-full bg-white/50 border border-white/50 rounded-xl py-3.5 pl-11 pr-4 text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none transition-all shadow-sm group-hover:bg-white/80"
                                   placeholder="Contoh: ORD-12345 atau 0812..."
                                   required>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 ml-1">
                            Masukkan Kode Pesanan yang Anda terima atau Nomor HP saat pemesanan.
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white py-3.5 px-6 rounded-xl font-bold shadow-lg shadow-emerald-600/20 transition-all transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-md flex justify-center items-center gap-2">
                        <span id="btn-text">Lacak Sekarang</span>
                        <i class="fa-solid fa-arrow-right text-sm"></i>
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
            <div id="history-section" class="mb-8 hidden">

                <div class="flex justify-between items-end mb-4 px-2">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Riwayat Pesanan</h2>
                        <p class="text-xs text-slate-500">Pencarian terakhir Anda di perangkat ini</p>
                    </div>
                    <button id="clear-history"
                        class="text-red-500 hover:text-red-700 text-sm font-medium hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus Semua
                    </button>
                </div>

                <div id="history-list"
                     class="grid gap-4"></div>

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
        
        // Helper: Render Cards
        const renderCards = (items) => {
            historyList.innerHTML = "";
            items.slice().reverse().forEach((o, idxRev) => {
                const originalIndex = items.length - 1 - idxRev;
                const card = document.createElement("div");
                card.className = "bg-white/60 backdrop-blur-xl border border-white/40 shadow-sm rounded-2xl p-6 pl-10 hover:shadow-emerald-900/5 transition-all relative flex flex-col md:flex-row md:items-center md:justify-between gap-4 group";
                
                const statusClass = (o.status === 'completed' || o.status === 'paid') ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                    : (o.status === 'awaiting_payment') ? 'bg-amber-100 text-amber-700 border border-amber-200'
                    : (o.status === 'pending_verification') ? 'bg-orange-100 text-orange-700 border border-orange-200'
                    : (o.status === 'cancelled') ? 'bg-red-100 text-red-700 border border-red-200'
                    : (o.status === 'processing' || o.status === 'pickup_ready') ? 'bg-blue-100 text-blue-700 border border-blue-200'
                    : 'bg-slate-100 text-slate-800 border border-slate-200';

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
                    <button class="absolute top-1/2 -translate-y-1/2 left-3 w-6 h-6 rounded-full bg-red-50 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all remove-history" data-index="${originalIndex}" title="Hapus dari riwayat">
                        <i class="fa-solid fa-xmark text-xs pointer-events-none"></i>
                    </button>
                    
                    <div class="flex items-center gap-5">
                        <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-white/80 shadow-sm items-center justify-center text-emerald-600 border border-emerald-100">
                             <i class="fa-solid fa-receipt text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kode Pesanan</p>
                            <p class="font-bold text-lg text-slate-800 font-mono tracking-tight">${o.order_code}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                         <span class="px-3 py-1 text-xs font-bold rounded-full ${statusClass} uppercase tracking-wide shadow-sm">${statusLabel}</span>
                    </div>

                    <div class="flex items-center gap-6 text-sm text-slate-600 border-l border-slate-200 pl-6 border-dashed hidden lg:flex">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Items</span>
                            <span class="font-semibold">${o.items?.length ?? 0} Produk</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Total</span>
                            <span class="font-semibold text-emerald-700">${formatCurrency(o.total_amount)}</span>
                        </div>
                    </div>

                    <div>
                        <a href="/pesanan/${o.order_code}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-emerald-500 text-slate-600 hover:text-emerald-600 px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-sm hover:shadow-md">
                            Lihat Detail <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                `;
                historyList.appendChild(card);
            });
        };

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

        // Initial render with existing data
        renderCards(data);

        // Fetch fresh status for each order and update localStorage
        const refreshPromises = data.map(async (order, index) => {
            try {
                const response = await fetch(`/api/orders/${order.order_code}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const result = await response.json();
                    if (result.data) {
                        // Update status
                        if (result.data.status) {
                            data[index].status = result.data.status;
                        }
                        // Update total amount if missing or updated
                        if (result.data.total_amount !== undefined) {
                            data[index].total_amount = result.data.total_amount;
                        }
                        // Update items count if available
                        if (result.data.items) {
                            data[index].items = result.data.items;
                        }
                    }
                }
            } catch (e) {
                console.warn('Could not refresh status for', order.order_code);
            }
        });

        // Wait for all refreshes, then render again
        Promise.all(refreshPromises).then(() => {
            setData(data); // Save updated statuses to localStorage
            renderCards(data);
        });
    }

    // Handle existing PHP-rendered order for history (on page load)
    @if(isset($order) && $order)
        const currentOrder = @json($order);
        let list = getData();
        if (!list.find(x => x.order_code === currentOrder.order_code)) {
            list.push(currentOrder);
            setData(list);
        }
    @endif

    // Initial Render
    renderHistory();
    // Also check if PHP rendered a result (on first load with params)
    if (resultContainer.innerHTML.trim().length > 0) {
        resultCol.classList.remove("hidden");
        formCol.classList.remove("lg:col-span-3");
        formCol.classList.add("lg:col-span-1");
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
});
</script>
@endsection
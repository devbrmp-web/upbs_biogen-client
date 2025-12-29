@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-28 py-8 max-w-4xl">
    <div class="bg-white shadow-lg rounded-lg p-8 print:shadow-none print:p-0" id="documentArea">
        <!-- Header Dokumen -->
        <div class="text-center mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold uppercase tracking-wider mb-2">Dokumen Kerjasama</h1>
            <h2 class="text-lg font-semibold text-gray-600">UPBS Balai Besar Biogen</h2>
            <p class="text-sm text-gray-500">Jl. Tentara Pelajar No. 3A, Bogor 16111 - Jawa Barat</p>
        </div>

        <!-- Info Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="font-bold text-gray-700 mb-2 border-b w-max">Data Pesanan</h3>
                <table class="w-full text-sm">
                    <tr>
                        <td class="py-1 text-gray-600 w-32">No. Transaksi</td>
                        <td class="font-medium">: {{ $data->order_code }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">Status</td>
                        <td class="font-medium uppercase">: {{ $data->status }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <h3 class="font-bold text-gray-700 mb-2 border-b w-max">Data Pelanggan</h3>
                <table class="w-full text-sm">
                    <tr>
                        <td class="py-1 text-gray-600 w-32">Nama</td>
                        <td class="font-medium">: {{ $data->customer_name }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">No. HP</td>
                        <td class="font-medium">: {{ $data->customer_phone }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">Alamat</td>
                        <td class="font-medium">: {{ $data->customer_address }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tabel Item -->
        <div class="mb-8">
            <h3 class="font-bold text-gray-700 mb-4">Rincian Item</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-4 py-2 text-left">No</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Varietas</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Kelas</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Qty</th>
                            <th class="border border-gray-200 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="border border-gray-200 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->items as $index => $item)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-200 px-4 py-2">
                                {{ $item['resolved_variety_name'] ?? ($item['name'] ?? 'Unknown') }}
                            </td>
                            <td class="border border-gray-200 px-4 py-2 text-center">{{ $item['seed_class_code'] ?? '-' }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-center">{{ (int) ($item['quantity'] ?? 0) }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">Rp {{ number_format((float) ($item['unit_price'] ?? 0), 0, ',', '.') }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">Rp {{ number_format(((float) ($item['unit_price'] ?? 0)) * ((int) ($item['quantity'] ?? 0)), 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold bg-gray-50">
                            <td colspan="5" class="border border-gray-200 px-4 py-2 text-right">Total</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">Rp {{ number_format((float) $data->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 mt-12 mb-4 break-inside-avoid">
            <div class="text-center">
                <p class="mb-16 font-medium">Pihak UPBS Biogen</p>
                <div class="border-b border-gray-400 w-2/3 mx-auto"></div>
                <p class="mt-2 text-sm text-gray-500">(Admin/Petugas)</p>
            </div>
            
            <div class="text-center flex flex-col items-center">
                <p class="mb-4 font-medium">Pihak Pembeli</p>
                
                <!-- Signature Area -->
                <div id="signatureContainer" class="mb-2 relative">
                    <!-- Canvas for drawing -->
                    <canvas id="signatureCanvas" class="border-2 border-dashed border-gray-300 rounded bg-gray-50 cursor-crosshair touch-none" width="300" height="150"></canvas>
                    
                    <!-- Display Image for saved signature -->
                    <img id="signatureImage" class="hidden border border-gray-300 rounded" width="300" height="150" alt="Tanda Tangan Pembeli">
                    
                    <!-- Controls -->
                    <div class="mt-2 flex gap-2 justify-center print:hidden" id="signatureControls">
                        <button type="button" onclick="clearSignature()" class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">
                            Hapus
                        </button>
                        <button type="button" onclick="saveSignature()" class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </div>

                <div class="border-b border-gray-400 w-2/3 mx-auto mt-2"></div>
                <p class="mt-2 text-sm text-gray-500">({{ $data->customer_name }})</p>
            </div>
        </div>

        <div class="text-center mt-12 text-xs text-gray-400 italic">
            Dokumen ini dicetak secara otomatis oleh sistem UPBS Biogen pada {{ date('d/m/Y H:i') }}
        </div>
    </div>

    <!-- Action Buttons (Hidden when printing) -->
    <div class="mt-8 flex justify-center gap-4 print:hidden">
        <a href="{{ route('order.detail', $data->order_code) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
        <button onclick="window.print()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak / PDF
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)'
        });
        const orderCode = "{{ $data->order_code }}";
        const storageKey = 'signature_' + orderCode;
        
        const imgDisplay = document.getElementById('signatureImage');
        const controls = document.getElementById('signatureControls');

        const savedSignature = localStorage.getItem(storageKey);

        if (savedSignature) {
            showSavedSignature(savedSignature);
        } else {
            const temp = localStorage.getItem('signature_temp');
            if (temp) {
                localStorage.setItem(storageKey, temp);
                showSavedSignature(temp);
            }
        }

        // Resize canvas for high DPI
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            // Reload signature if exists (because resize clears canvas)
            if (!savedSignature) {
                signaturePad.clear();
            }
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        window.saveSignature = function() {
            if (signaturePad.isEmpty()) {
                alert("Silakan tanda tangan terlebih dahulu.");
                return;
            }
            const dataUrl = signaturePad.toDataURL();
            localStorage.setItem(storageKey, dataUrl);
            showSavedSignature(dataUrl);
            alert("Tanda tangan berhasil disimpan!");
        };

        window.clearSignature = function() {
            localStorage.removeItem(storageKey);
            signaturePad.clear();
            
            // Show canvas, hide image
            canvas.classList.remove('hidden');
            imgDisplay.classList.add('hidden');
            controls.classList.remove('hidden');
            
            // Re-enable writing
            signaturePad.on();
        };

        function showSavedSignature(dataUrl) {
            // Show image, hide canvas
            imgDisplay.src = dataUrl;
            imgDisplay.classList.remove('hidden');
            canvas.classList.add('hidden');
            
            // Hide save button, keep clear button but maybe style differently?
            // Actually user might want to re-sign, so we keep controls but maybe change text
            // For now let's keep it simple: "Hapus" allows re-signing.
        }
    });
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #documentArea, #documentArea * {
            visibility: visible;
        }
        #documentArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }
        /* Hide signature controls in print */
        #signatureControls, canvas {
            display: none !important;
        }
        /* Always show signature image in print if it exists */
        #signatureImage {
            display: block !important;
            border: none;
        }
    }
</style>
@endsection

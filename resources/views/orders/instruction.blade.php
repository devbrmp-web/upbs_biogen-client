@extends('layouts.app')

@section('title', 'Instruksi Pembayaran • UPBS BRMP Biogen')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-28 page-animate-slideUp">
    
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
        <p class="text-gray-600">Silakan selesaikan pembayaran Anda</p>
    </div>

    {{-- Order Info Card --}}
    <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 mb-6">
        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
            <div>
                <p class="text-sm text-gray-500">Kode Pesanan</p>
                <p class="font-bold text-lg text-gray-900">{{ $order->order_code ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    @if(($order->status ?? '') === 'pending_verification') bg-orange-100 text-orange-800
                    @elseif(($order->status ?? '') === 'awaiting_payment') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    @if(($order->status ?? '') === 'pending_verification')
                        Menunggu Verifikasi
                    @elseif(($order->status ?? '') === 'awaiting_payment')
                        Menunggu Pembayaran
                    @else
                        {{ ucfirst(str_replace('_', ' ', $order->status ?? 'Unknown')) }}
                    @endif
                </span>
            </div>
        </div>
        
        <div class="text-center py-4">
            <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
            <p class="text-3xl font-bold text-blue-600">
                Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
            </p>
            @if($order->payment_deadline ?? false)
                <p class="text-sm text-red-500 mt-2">
                    Batas waktu: {{ \Carbon\Carbon::parse($order->payment_deadline)->format('d M Y, H:i') }} WIB
                </p>
                <div id="payment-countdown" class="mt-2 text-lg font-bold text-red-600 bg-red-50 inline-block px-3 py-1 rounded-lg border border-red-100">
                    Loading...
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Ensure correct timezone parsing
                        const deadlineStr = "{{ \Carbon\Carbon::parse($order->payment_deadline)->toIso8601String() }}";
                        const deadline = new Date(deadlineStr).getTime();
                        
                        function updateTimer() {
                            const now = new Date().getTime();
                            const distance = deadline - now;
                            
                            if (distance < 0) {
                                clearInterval(timer);
                                document.getElementById("payment-countdown").innerHTML = "Waktu Habis!";
                                // Reload page to trigger server-side check (and Shadow Cache Restoration if deleted)
                                window.location.reload();
                                return;
                            }
                            
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            document.getElementById("payment-countdown").innerHTML = 
                                `<i class="fa-regular fa-clock mr-1"></i> ${hours}j ${minutes}m ${seconds}d`;
                        }

                        const timer = setInterval(updateTimer, 1000);
                        updateTimer(); // Initial call
                    });
                </script>
            @endif
        </div>
    </div>

    {{-- Bank Accounts --}}
    <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Transfer ke Rekening
        </h2>
        
        <div class="space-y-4">
            @foreach($order->banks ?? [] as $bank)
            <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200">
                <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg shadow-sm flex items-center justify-center mr-4">
                    <span class="text-lg font-bold text-blue-600">{{ substr($bank['bank'] ?? 'B', 0, 3) }}</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ $bank['bank'] ?? 'Bank' }}</p>
                    <p class="text-lg font-mono text-gray-800 select-all">{{ $bank['account_number'] ?? '-' }}</p>
                    <p class="text-sm text-gray-600">a.n. {{ $bank['account_name'] ?? '-' }}</p>
                </div>
                <button onclick="copyToClipboard('{{ $bank['account_number'] ?? '' }}')" 
                        class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                        title="Salin nomor rekening">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Instructions --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6">
        <h3 class="font-semibold text-amber-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Petunjuk Pembayaran
        </h3>
        <ul class="space-y-2 text-amber-700 text-sm">
            @foreach($order->instructions ?? [] as $instruction)
            <li class="flex items-start">
                <span class="inline-flex items-center justify-center w-5 h-5 bg-amber-200 text-amber-800 rounded-full text-xs font-bold mr-2 flex-shrink-0 mt-0.5">{{ $loop->iteration }}</span>
                {{ $instruction }}
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Upload Payment Proof --}}
    @if(!($order->has_proof ?? false))
    <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Upload Bukti Transfer
        </h2>
        
        <form action="{{ route('order.upload-proof', ['order_code' => $order->order_code]) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf
            
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-all duration-200" 
                 id="dropzone">
                <input type="file" 
                       name="payment_proof" 
                       id="payment_proof" 
                       accept=".jpg,.jpeg,.png,.pdf"
                       class="hidden"
                       required>
                <label for="payment_proof" class="cursor-pointer">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-gray-600 mb-1">Klik untuk pilih file atau drag & drop</p>
                    <p class="text-sm text-gray-500">JPG, PNG, atau PDF (maks. 10MB)</p>
                </label>
                <p id="file-name" class="mt-2 text-sm text-blue-600 font-medium hidden"></p>
            </div>
            
            @error('payment_proof')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
            
            <button type="submit" 
                    class="w-full py-3 px-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-[1.02] shadow-lg">
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6 text-center">
        <svg class="w-12 h-12 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-green-700 font-semibold">Bukti pembayaran sudah diunggah</p>
        <p class="text-green-600 text-sm mt-1">Menunggu verifikasi dari admin</p>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('order.detail', ['order_code' => $order->order_code]) }}" 
           class="flex-1 py-3 px-6 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-center">
            Lihat Detail Pesanan
        </a>
        <a href="{{ route('katalog') }}" 
           class="flex-1 py-3 px-6 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors text-center">
            Kembali ke Katalog
        </a>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show toast or feedback
        alert('Nomor rekening disalin!');
    });
}

// File input preview
const fileInput = document.getElementById('payment_proof');
const dropzone = document.getElementById('dropzone');
const fileNameEl = document.getElementById('file-name');

if (fileInput && dropzone) {
    // Click handler - already works via label
    fileInput.addEventListener('change', function(e) {
        updateFilePreview(e.target.files[0]);
    });

    // ========================================
    // DRAG AND DROP HANDLERS
    // ========================================
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight dropzone on dragover
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropzone.classList.remove('border-gray-300');
        dropzone.classList.add('border-blue-500', 'bg-blue-50', 'scale-[1.02]');
    }

    function unhighlight(e) {
        dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'scale-[1.02]');
        dropzone.classList.add('border-gray-300');
    }

    // Handle dropped files
    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                return;
            }

            // Validate file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 10MB.');
                return;
            }

            // Transfer file to hidden input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            // Update preview
            updateFilePreview(file);
        }
    }

    function updateFilePreview(file) {
        if (file && fileNameEl) {
            fileNameEl.textContent = '📎 ' + file.name;
            fileNameEl.classList.remove('hidden');
            
            // Add success styling to dropzone
            dropzone.classList.remove('border-gray-300');
            dropzone.classList.add('border-green-400', 'bg-green-50');
        } else if (fileNameEl) {
            fileNameEl.classList.add('hidden');
        }
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Checkout • UPBS BRMP Biogen')

@section('content')
 <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="Mid-client-0HeQjinK75x-iLk0"></script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-18 page-animate-slideUp">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout Pesanan</h1>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Left Column (60%) -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Section A: Cart Items -->
            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center justify-between">
                    <span>Item Keranjang</span>
                    <button onclick="window.location.href='/katalog'" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        + Tambah Varietas
                    </button>
                </h2>
                
                <div id="checkout-cart-items" class="space-y-4">
                    <!-- Items will be rendered here by JS -->
                    <div class="text-center py-8 text-gray-500">Memuat keranjang...</div>
                </div>
            </div>

            <!-- Section B: Receiver Information -->
            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Penerima</h2>
                
                <!-- Collapsed Summary State -->
                <div id="receiver-summary" class="cursor-pointer p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300 hover:bg-gray-100 transition" onclick="toggleReceiverForm()">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900" id="summary-name">Belum diisi</p>
                            <p class="text-sm text-gray-600 truncate max-w-md" id="summary-address">Klik untuk melengkapi data penerima</p>
                        </div>
                        <span class="text-blue-600 text-sm font-medium">Ubah</span>
                    </div>
                </div>

                <!-- Expanded Form State -->
                <div id="receiver-form-container" class="hidden mt-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="form-name" class="w-full rounded-lg border px-2 py-1  border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Penerima">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" id="form-phone" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                        <input type="email" id="form-email" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com">
                        <p class="text-xs text-gray-500 mt-1">Untuk pengiriman invoice dan notifikasi pesanan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea id="form-address" rows="3" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" id="form-province" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <input type="text" id="form-city" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" id="form-district" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" id="form-postal" class="w-full rounded-lg border px-2 py-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500" maxlength="5">
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-medium text-gray-900 mb-3">Metode Pengiriman</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Opsi 1: Ambil di Tempat -->
                            <label class="cursor-pointer border rounded-lg p-4 flex items-start gap-3 hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="shipping_method" value="pickup" class="mt-1 text-blue-600 focus:ring-blue-500" checked onchange="toggleShippingInfo()">
                                <div>
                                    <span class="font-medium text-gray-900 block">Ambil di Tempat (Pickup)</span>
                                    <p class="text-xs text-gray-500 mt-1">Ambil pesanan langsung di kantor UPBS BRMP Biogen.</p>
                                </div>
                            </label>

                            <!-- Opsi 2: Dikirim -->
                            <label class="cursor-pointer border rounded-lg p-4 flex items-start gap-3 hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="shipping_method" value="delivery" class="mt-1 text-blue-600 focus:ring-blue-500" onchange="toggleShippingInfo()">
                                <div>
                                    <span class="font-medium text-gray-900 block">Dikirim (Kurir)</span>
                                    <p class="text-xs text-gray-500 mt-1">Ongkos kirim akan dihitung terpisah.</p>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Info Tambahan Delivery -->
                        <div id="delivery-info" class="hidden mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                            <p class="font-semibold mb-1">Catatan Pengiriman:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Kurir otomatis ditentukan berdasarkan berat (Pos Indonesia / Indah Cargo).</li>
                                <li>Biaya ongkir belum termasuk dalam total pembayaran saat ini.</li>
                                <li>Admin akan menghubungi Anda untuk konfirmasi biaya pengiriman.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t mt-6">
                        <button onclick="saveReceiverForm()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                            Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (40%) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 sticky top-24 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-blue-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan Pembayaran</h2>
                
                <div class="space-y-3 text-sm mb-6 pb-6 border-b border-gray-100">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="summary-subtotal" class="font-medium text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Layanan (1%)</span>
                        <span id="summary-service-fee" class="font-medium text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2">
                        <span class="text-gray-900">Total Pembayaran</span>
                        <span id="summary-total" class="text-blue-600">Rp 0</span>
                    </div>
                </div>

                <!-- Validation Error Message -->
                <div id="checkout-error" class="hidden mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200"></div>

                <!-- Terms & Conditions -->
                <div class="mb-4 flex items-start gap-2">
                    <input type="checkbox" id="terms-checkbox" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded" onchange="togglePayButtonByTerms()">
                    <label for="terms-checkbox" class="text-sm text-gray-700">Saya setuju dengan 
                        <button type="button" id="terms-modal-trigger" class="text-blue-600 hover:underline">Syarat dan Ketentuan</button>
                    </label>
                </div>

                <!-- Pay Button -->
                <button id="btn-pay" onclick="processCheckout()" disabled class="w-full bg-green-600 text-white py-3.5 rounded-xl font-bold text-lg shadow-lg shadow-green-200 hover:bg-green-700 hover:shadow-xl transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Lanjutkan ke Pembayaran
                </button>

                <div id="loading-state" class="hidden mt-4 text-center text-gray-500 text-sm">
                    <svg class="animate-spin h-5 w-5 text-blue-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sedang memproses pesanan...
                </div>

                
            </div>
        </div>
    </div>
</div>

 

<!-- Load Checkout Logic -->
@vite('resources/js/checkout.js')

<!-- Terms & Conditions Modal -->
<div id="terms-modal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/50" onclick="closeTermsModal()"></div>
  <div class="relative mx-auto my-10 w-[92%] max-w-xl bg-white rounded-xl shadow-xl p-6">
    <div class="flex justify-between items-center mb-3">
      <h3 class="text-lg font-semibold text-gray-900">Syarat dan Ketentuan</h3>
      <button type="button" class="text-gray-500 hover:text-gray-800" onclick="closeTermsModal()">✕</button>
    </div>
    <div class="prose prose-sm max-w-none text-gray-700 space-y-3">
      <p>Dengan melanjutkan, Anda menyetujui ketentuan pembelian benih UPBS BRMP Biogen, termasuk:</p>
      <ul class="list-disc list-inside">
        <li>Pembayaran melalui PNBP atau Midtrans sesuai instruksi.</li>
        <li>Stok dan harga dapat berubah sesuai ketersediaan lot benih.</li>
        <li>Pengambilan di tempat (pickup) atau pengiriman kurir ditentukan oleh admin.</li>
        <li>Keluhan layanan diproses melalui kontak resmi BRMP Biogen.</li>
      </ul>
    </div>
    <div class="mt-6 flex justify-end gap-3">
      <button type="button" class="px-4 py-2 rounded-lg border" onclick="closeTermsModal()">Tutup</button>
      <button type="button" class="px-4 py-2 rounded-lg bg-blue-600 text-white" onclick="acceptTermsAndClose()">Setuju</button>
    </div>
  </div>
  </div>

<script>
  document.getElementById('terms-modal-trigger')?.addEventListener('click', () => {
    document.getElementById('terms-modal').classList.remove('hidden');
  });
  function closeTermsModal() {
    document.getElementById('terms-modal').classList.add('hidden');
  }
  function acceptTermsAndClose() {
    document.getElementById('terms-checkbox').checked = true;
    togglePayButtonByTerms();
    closeTermsModal();
  }
  function togglePayButtonByTerms() {
    const checked = document.getElementById('terms-checkbox').checked;
    document.getElementById('btn-pay').disabled = !checked;
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeTermsModal();
  });
</script>

<script>
    function toggleReceiverForm() {
        const summary = document.getElementById('receiver-summary');
        const form = document.getElementById('receiver-form-container');
        
        summary.classList.add('hidden');
        form.classList.remove('hidden');
    }

    function toggleShippingInfo() {
        const method = document.querySelector('input[name="shipping_method"]:checked').value;
        const info = document.getElementById('delivery-info');
        if (method === 'delivery') {
            info.classList.remove('hidden');
        } else {
            info.classList.add('hidden');
        }
    }

    function saveReceiverForm() {
        // Basic validation for required fields
        const required = ['form-name', 'form-phone', 'form-address', 'form-province', 'form-city', 'form-district', 'form-postal'];
        let isValid = true;
        
        required.forEach(id => {
            const el = document.getElementById(id);
            if (!el.value.trim()) {
                el.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                isValid = false;
            } else {
                el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            }
        });

        if (!isValid) return;

        // Update summary
        const name = document.getElementById('form-name').value;
        const address = document.getElementById('form-address').value;
        const city = document.getElementById('form-city').value;
        const method = document.querySelector('input[name="shipping_method"]:checked').value;
        const methodLabel = method === 'pickup' ? 'Ambil di Tempat' : 'Dikirim';
        
        document.getElementById('summary-name').textContent = name;
        document.getElementById('summary-address').innerHTML = `${address}, ${city}<br><span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded mt-1 inline-block">${methodLabel}</span>`;
        
        // Toggle back
        document.getElementById('receiver-summary').classList.remove('hidden');
        document.getElementById('receiver-form-container').classList.add('hidden');
    }
</script>
@endsection

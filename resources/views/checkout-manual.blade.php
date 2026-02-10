@extends('layouts.app')

@section('title', 'Checkout Manual • UPBS BRMP Biogen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-28 page-animate-slideUp">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout Pesanan</h1>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center justify-between">
                    <span>Item Keranjang</span>
                    <button onclick="window.location.href='/katalog'" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        + Tambah Varietas
                    </button>
                </h2>
                
                <div id="manual-checkout-cart-items" class="space-y-4">
                    <div class="text-center py-8 text-gray-500">Memuat keranjang...</div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Metode Pengiriman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="cursor-pointer border rounded-lg p-4 flex items-start gap-3 hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                        <input type="radio" name="shipping_method" value="pickup" class="mt-1 text-blue-600 focus:ring-blue-500" checked onchange="toggleShippingInfo()">
                        <div>
                            <span class="font-medium text-gray-900 block">Ambil di Tempat (Pickup)</span>
                            <p class="text-xs text-gray-500 mt-1">Ambil pesanan langsung di kantor UPBS BRMP Biogen.</p>
                        </div>
                    </label>
                    <label class="cursor-pointer border rounded-lg p-4 flex items-start gap-3 hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                        <input type="radio" name="shipping_method" value="delivery" class="mt-1 text-blue-600 focus:ring-blue-500" onchange="toggleShippingInfo()">
                        <div>
                            <span class="font-medium text-gray-900 block">Dikirim (Kurir)</span>
                            <p class="text-xs text-gray-500 mt-1">Ongkos kirim akan dihitung terpisah.</p>
                        </div>
                    </label>
                </div>
                <div id="delivery-info" class="hidden mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    <p class="font-semibold mb-1">Catatan Pengiriman:</p>
                    <div class="space-y-2 text-xs">
                        <p>Pembeli wajib menghubungi Call Center untuk mengkonfirmasi pembelian dan membayar biaya pengiriman.</p>
                        <p>Biaya ongkir dibayarkan terpisah setelah konfirmasi dari admin.</p>
                        <p>Siapkan bukti transaksi pembayaran benih saat menghubungi.</p>
                    </div>
                    <div class="mt-3">
                        <button type="button" id="btn-call-center-wa" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Hubungi Call Center
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Penerima</h2>
                
                <div id="receiver-summary" class="cursor-pointer p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300 hover:bg-gray-100 transition" onclick="toggleReceiverForm()">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900" id="summary-name">Belum diisi</p>
                            <p class="text-sm text-gray-600 truncate max-w-md" id="summary-address">Klik untuk melengkapi data penerima</p>
                        </div>
                        <span class="text-blue-600 text-sm font-medium">Ubah</span>
                    </div>
                </div>

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

                    <div class="mt-6 hidden">
                        <h3 class="font-medium text-gray-900 mb-3">Metode Pengiriman</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="cursor-pointer border rounded-lg p-4 flex items-start gap-3 hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="shipping_method" value="pickup" class="mt-1 text-blue-600 focus:ring-blue-500" checked onchange="toggleShippingInfo()">
                                <div>
                                    <span class="font-medium text-gray-900 block">Ambil di Tempat (Pickup)</span>
                                    <p class="text-xs text-gray-500 mt-1">Ambil pesanan langsung di kantor UPBS BRMP Biogen.</p>
                                </div>
                            </label>

                            <label class="cursor-pointer border rounded-lg p-4 flex items-start gap-3 hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="shipping_method" value="delivery" class="mt-1 text-blue-600 focus:ring-blue-500" onchange="toggleShippingInfo()">
                                <div>
                                    <span class="font-medium text-gray-900 block">Dikirim (Kurir)</span>
                                    <p class="text-xs text-gray-500 mt-1">Ongkos kirim akan dihitung terpisah.</p>
                                </div>
                            </label>
                        </div>
                        
                        <div id="delivery-info" class="hidden mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                            <p class="font-semibold mb-1">Catatan Pengiriman:</p>
                            <div class="space-y-2 text-xs">
                                <p>Pembeli wajib menghubungi Call Center untuk mengkonfirmasi pembelian dan membayar biaya pengiriman.</p>
                                <p>Biaya ongkir dibayarkan terpisah setelah konfirmasi dari admin.</p>
                                <p>Siapkan bukti transaksi pembayaran benih saat menghubungi.</p>
                            </div>
                            <div class="mt-3">
                                <button type="button"
                                    id="btn-call-center-wa"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    Hubungi Call Center
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t mt-6">
                        <button onclick="saveReceiverForm()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                            Simpan Data
                        </button>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Tanda Tangan Pembeli</h2>
                    <div class="flex flex-col items-center">
                        <canvas id="checkoutSignatureCanvas" class="border-2 border-dashed border-gray-300 rounded bg-gray-50 cursor-crosshair" width="320" height="160"></canvas>
                        <div class="mt-3 flex gap-2">
                            <button type="button" id="btnClearCheckoutSig" class="text-sm bg-red-100 text-red-600 px-3 py-1 rounded">Hapus</button>
                            <button type="button" id="btnSaveCheckoutSig" class="text-sm bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Tanda tangan akan disalin ke dokumen kerja sama setelah pembayaran.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-xl p-6 border border-white/50 sticky top-24 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-blue-400 opacity-50"></div>
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan Pembayaran</h2>
                
                <div class="space-y-3 text-sm mb-6 pb-6 border-b border-gray-100">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="manual-summary-subtotal" class="font-medium text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Layanan (1%)</span>
                        <span id="summary-service-fee" class="font-medium text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Aplikasi</span>
                        <span id="summary-app-fee" class="font-medium text-gray-900">Rp 4.000</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Kode Unik</span>
                        <span id="manual-summary-unique-code" class="font-medium text-blue-600 font-bold" data-code="">-</span>
                    </div>
                    <span id="manual-summary-total-base" class="hidden" data-base-total="0"></span>

                    <div class="flex justify-between text-lg font-bold pt-2">
                        <span class="text-gray-900">Total Pembayaran</span>
                        <span id="manual-summary-total-final" class="text-blue-600">Rp 0</span>
                    </div>
                </div>

                <div id="checkout-error" class="hidden mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200"></div>

                <div class="mb-4 flex items-start gap-2">
                    <input type="checkbox" id="terms-checkbox" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded" onchange="togglePayButtonByTerms()">
                    <label for="terms-checkbox" class="text-sm text-gray-700">Saya setuju dengan 
                        <button type="button" id="terms-modal-trigger" class="text-blue-600 hover:underline">Syarat dan Ketentuan</button>
                    </label>
                </div>

                <button id="btn-pay" onclick="window.manualStartCheckout && window.manualStartCheckout()" disabled class="w-full bg-green-600 text-white py-3.5 rounded-xl font-bold text-lg shadow-lg shadow-green-200 hover:bg-green-700 hover:shadow-xl transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Checkout
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

<script src="/js/manual-checkout.js"></script>

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
        <li>Pembayaran dilakukan melalui transfer manual ke rekening resmi yang tertera.</li>
        <li>Stok dan harga dapat berubah sesuai ketersediaan lot benih.</li>
        <li>Keluhan layanan diproses melalui kontak resmi BRMP Biogen.</li>
        <li>Biaya pengiriman dibebankan di luar dari aplikasi dan dibayarkan terpisah setelah konfirmasi admin.</li>
        <li>Refund dikenakan denda sesuai kebijakan UPBS BRMP Biogen.</li>
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
    try { localStorage.setItem('checkout_terms_ack', 'yes'); } catch(e) {}
    closeTermsModal();
  }
  function togglePayButtonByTerms() {
    const checked = document.getElementById('terms-checkbox').checked;
    document.getElementById('btn-pay').disabled = !checked;
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeTermsModal();
  });
  document.addEventListener('DOMContentLoaded', () => {
    try {
      const ack = localStorage.getItem('checkout_terms_ack');
      if (ack !== 'yes') {
        document.getElementById('terms-modal').classList.remove('hidden');
      }
    } catch(e) {
      document.getElementById('terms-modal').classList.remove('hidden');
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  (function(){
    const c = document.getElementById('checkoutSignatureCanvas');
    if (!c) return;
    const pad = new SignaturePad(c);
    const KEY = 'signature_temp';
    const saved = localStorage.getItem(KEY);
    if (saved) {
      try {
        const img = new Image();
        img.onload = () => c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
        img.src = saved;
      } catch {}
    }
    document.getElementById('btnSaveCheckoutSig')?.addEventListener('click', () => {
      if (pad.isEmpty()) return alert('Silakan tanda tangan terlebih dahulu.');
      const dataUrl = pad.toDataURL();
      localStorage.setItem(KEY, dataUrl);
      alert('Tanda tangan disimpan.');
    });
    document.getElementById('btnClearCheckoutSig')?.addEventListener('click', () => {
      pad.clear();
      localStorage.removeItem(KEY);
    });
  })();
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
        try { localStorage.setItem('shipping_method', method); } catch(e){}
        if (method === 'delivery') {
            info.classList.remove('hidden');
        } else {
            info.classList.add('hidden');
        }
    }

    function saveReceiverForm() {
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

        const name = document.getElementById('form-name').value;
        const address = document.getElementById('form-address').value;
        const city = document.getElementById('form-city').value;
        const method = document.querySelector('input[name="shipping_method"]:checked').value;
        const methodLabel = method === 'pickup' ? 'Ambil di Tempat' : 'Dikirim';
        try { localStorage.setItem('shipping_method', method); } catch(e){}
        
        document.getElementById('summary-name').textContent = name;
        document.getElementById('summary-address').innerHTML = `${address}, ${city}<br><span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded mt-1 inline-block">${methodLabel}</span>`;
        
        document.getElementById('receiver-summary').classList.remove('hidden');
        document.getElementById('receiver-form-container').classList.add('hidden');
    }
</script>
@endsection

<!-- Manual Payment Modal -->
<div x-data x-show="$store.manualCheckout.on" 
     class="fixed inset-0 z-[60] overflow-y-auto" 
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="fixed inset-0 bg-black/50 transition-opacity" @click="$store.manualCheckout.close()"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Pembayaran Manual</h3>
                <button @click="$store.manualCheckout.close()" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Amount Section -->
                <div class="text-center bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <p class="text-sm text-blue-600 font-medium mb-1">Total yang harus ditransfer</p>
                    <div class="text-3xl font-bold text-blue-900" x-text="$store.manualCheckout.totalAmount">Rp 0</div>
                    <p class="text-xs text-blue-500 mt-2">*Pastikan nominal sesuai hingga 3 digit terakhir</p>
                </div>

                <!-- Bank Details -->
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-900">Rekening Tujuan</h4>
                    @if(isset($banks) && count($banks) > 0)
                        <div class="space-y-3">
                            <div>
                                <label for="manual-bank-select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bank Tujuan</label>
                                <select id="manual-bank-select"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                        onchange="window.manualSelectBank && window.manualSelectBank(this)">
                                    @foreach($banks as $i => $bank)
                                        <option value="{{ $i }}"
                                                data-bank-name="{{ $bank['bank_name'] ?? '' }}"
                                                data-account-number="{{ $bank['account_number'] ?? '' }}"
                                                data-account-holder="{{ $bank['account_holder'] ?? '' }}">
                                            {{ strtoupper($bank['bank_name'] ?? 'BANK') }} • {{ $bank['account_number'] ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-4 p-3 border rounded-lg bg-white">
                                <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center text-xs font-bold text-gray-700" id="manual-bank-badge">
                                    {{ strtoupper($banks[0]['bank_name'] ?? 'BANK') }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate" id="manual-bank-account-number">{{ $banks[0]['account_number'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-600 truncate" id="manual-bank-account-holder">{{ $banks[0]['account_holder'] ?? '-' }}</p>
                                </div>
                                <button type="button"
                                        class="ml-auto inline-flex items-center justify-center rounded-lg bg-blue-600 text-white px-3 py-2 text-sm font-semibold hover:bg-blue-700 transition"
                                        onclick="window.manualCopySelectedBank && window.manualCopySelectedBank()">
                                    Salin
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="p-3 border border-yellow-200 bg-yellow-50 rounded-lg text-sm text-yellow-800">
                            Silakan hubungi admin untuk informasi rekening.
                        </div>
                    @endif
                </div>

                <!-- Upload Form -->
                <form onsubmit="event.preventDefault(); window.manualUploadProof(this);" class="space-y-4">
                    <input type="hidden" name="order_code" :value="$store.manualCheckout.orderCode">
                    <input type="hidden" id="manual-bank-name-input" name="bank_name" value="{{ isset($banks[0]) ? $banks[0]['bank_name'] : 'Manual' }}">
                    <input type="hidden" id="manual-account-number-input" name="account_number" value="{{ isset($banks[0]) ? $banks[0]['account_number'] : 'Manual' }}">
                    <input type="hidden" id="manual-account-holder-input" name="account_holder" value="{{ isset($banks[0]) ? $banks[0]['account_holder'] : 'Manual' }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Transfer</label>
                        <input type="file" name="proof" accept="image/jpeg,image/png,application/pdf" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" required>
                        <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG/PDF. Maksimum 2MB.</p>
                    </div>

                    <div class="hidden" data-role="manual-loading">
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengunggah bukti...
                        </div>
                    </div>
                    
                    <div class="hidden p-3 bg-red-50 text-red-700 text-sm rounded-lg" data-role="manual-error"></div>
                    <div class="hidden p-3 bg-green-50 text-green-700 text-sm rounded-lg" data-role="manual-success"></div>

                    <button type="submit" data-role="manual-submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="manual-copy-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 hidden">
    <div class="bg-gray-900 text-white text-sm px-4 py-2 rounded-full shadow-lg">
        Nomor rekening berhasil disalin.
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('manualCheckout', {
            on: false,
            orderCode: null,
            totalAmount: 'Rp 0',
            
            show(code) {
                this.orderCode = code;
                this.on = true;
                const totalEl = document.getElementById('manual-summary-total-final');
                if (totalEl) {
                    this.totalAmount = totalEl.textContent;
                }
            },
            
            close() {
                // Forgiving Design: Notifikasi pesanan disimpan
                if (!document.querySelector('[data-role="manual-success"]')?.classList.contains('hidden')) {
                     // Jika sudah upload sukses, langsung redirect
                     this.on = false;
                     window.location.href = '/pesanan/' + this.orderCode;
                     return;
                }

                alert("Pesanan disimpan. Silakan upload bukti bayar maksimal 1x24 jam melalui halaman Riwayat Pesanan.");
                this.on = false;
                window.location.href = '/pesanan/' + this.orderCode;
            }
        });
    });

    window.manualSelectBank = function (selectEl) {
        if (!selectEl) return;
        const opt = selectEl.selectedOptions && selectEl.selectedOptions[0];
        if (!opt) return;

        const bankName = opt.dataset.bankName || '';
        const accountNumber = opt.dataset.accountNumber || '';
        const accountHolder = opt.dataset.accountHolder || '';

        const badge = document.getElementById('manual-bank-badge');
        const numEl = document.getElementById('manual-bank-account-number');
        const holderEl = document.getElementById('manual-bank-account-holder');
        const bankNameInput = document.getElementById('manual-bank-name-input');
        const accountNumberInput = document.getElementById('manual-account-number-input');
        const accountHolderInput = document.getElementById('manual-account-holder-input');

        if (badge) badge.textContent = (bankName || 'BANK').toUpperCase();
        if (numEl) numEl.textContent = accountNumber || '-';
        if (holderEl) holderEl.textContent = accountHolder || '-';
        if (bankNameInput) bankNameInput.value = bankName || 'Manual';
        if (accountNumberInput) accountNumberInput.value = accountNumber || 'Manual';
        if (accountHolderInput) accountHolderInput.value = accountHolder || 'Manual';
    };

    window.manualCopySelectedBank = async function () {
        const accountNumberInput = document.getElementById('manual-account-number-input');
        const text = accountNumberInput ? String(accountNumberInput.value || '') : '';
        if (!text) return;

        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(text);
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.left = '-9999px';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
        } catch (e) {
            return;
        }

        const toast = document.getElementById('manual-copy-toast');
        if (!toast) return;
        toast.classList.remove('hidden');
        window.clearTimeout(window.__manualCopyToastTimer);
        window.__manualCopyToastTimer = window.setTimeout(() => {
            toast.classList.add('hidden');
        }, 2000);
    };

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('manual-bank-select');
        if (select && window.manualSelectBank) {
            window.manualSelectBank(select);
        }
    });
</script>
@endpush

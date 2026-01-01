
<!-- resources/views/components/buy-modal.blade.php -->
<div id="buyModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

  <div class="relative mx-auto mt-10 w-[92%] max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
    <!-- Header -->
    <div class="px-5 py-4 border-b">
      <div class="flex items-center justify-between">
        <div>
          <h3 id="buyModalName" class="text-lg font-semibold text-gray-900"></h3>
          <p id="buyModalMin" class="text-sm text-gray-500 mt-1"></p>
        </div>
        <button id="buyModalClose" class="text-gray-600 hover:text-gray-900">
          <i class="fa fa-times"></i>
        </button>
      </div>
    </div>

    <!-- Body -->
    <div class="px-5 py-4">
      <div class="flex items-center justify-between gap-4">
        <!-- Qty control -->
        <div class="flex items-center gap-3">
          <button id="qtyMinus" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-xl">−</button>
          <input id="qtyInput" type="number" min="1" class="w-20 text-center border rounded-lg p-2" value="1">
          <button id="qtyPlus" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-xl">+</button>
        </div>

        <!-- Price per kg -->
        <div class="text-right">
          <div class="text-sm text-gray-500">Harga / kg</div>
          <div id="buyModalPrice" class="text-lg font-semibold text-green-700">Rp 0</div>
        </div>
      </div>

      <!-- Total -->
      <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">Total</div>
        <div id="buyModalTotal" class="text-xl font-bold text-gray-900">Rp 0</div>
      </div>

      <!-- Note minimal -->
      <p id="buyModalNote" class="mt-2 text-xs text-red-600 hidden"></p>
    </div>

    <!-- Footer -->
    <div class="px-5 py-4 border-t flex items-center justify-between">
      <button id="buyModalCancel" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">Batal</button>

      <div class="flex items-center gap-3">
        <button id="buyModalAdd" class="px-4 py-2 rounded-lg bg-gray-900 text-white disabled:opacity-50" disabled>Tambah ke Keranjang</button>

        <button id="buyModalBuyNow" class="px-4 py-2 rounded-lg bg-indigo-600 text-white disabled:opacity-50" disabled>Beli Langsung</button>
      </div>
    </div>
  </div>
</div>

<!-- resources/views/components/cart-modal.blade.php -->
<div id="cartModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

  <div class="relative mx-auto my-8 w-[92%] max-w-lg bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-5">
    <div class="flex items-center justify-between mb-4 border-b pb-2">
      <h3 class="text-lg font-semibold flex items-center gap-2"><i class="fa fa-shopping-cart"></i> Keranjang</h3>
      <div class="flex items-center gap-3">
        <button id="clearCartBtn" class="text-red-600 hover:text-red-800 flex items-center gap-2">
          <i class="fa fa-trash"></i> Hapus Semua
        </button>
        <button id="closeCartBtn" class="text-gray-600 hover:text-gray-900"><i class="fa fa-times"></i></button>
      </div>
    </div>

    <div id="cartItems" class="space-y-4 max-h-[55vh] overflow-y-auto pr-2">
      <!-- JS inject -->
    </div>

    <div class="mt-4 border-t pt-4">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-600">Total Harga</div>
        <div id="cartGrandTotal" class="text-lg font-bold text-green-700">Rp 0</div>
      </div>

      <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">Total Item: <span id="cartTotalItem">0</span></div>
        <div class="flex items-center gap-3">
         
              <button id="cartCheckoutBtn"
                      class="bg-gray-900 text-white px-4 py-2 rounded-lg w-full">
                  Checkout
              </button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

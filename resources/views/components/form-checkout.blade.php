<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout • UPBS BRMP Biogen</title>

    @vite('resources/css/app.css')
    @vite(['resources/js/app.js', 'resources/js/cart.js'])
  </head>

  <body class="bg-gray-100 text-gray-800 font-sans">

    <div class="max-w-3xl mx-auto py-10 px-4">

      <!-- HEADER -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Checkout Pemesanan</h1>
        <p class="text-sm text-gray-600 mt-1">
          Pastikan data diri & pengiriman sudah benar sebelum membuat pesanan.
        </p>
      </div>

      <div class="space-y-10">

        <!-- CARD STEP 1 -->
        <div id="step1" class="bg-white shadow rounded-xl p-6 space-y-6 border border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full text-xs">1</span>
            Informasi Data Diri
          </h2>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
              <input id="customer_name" type="text" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
              <input id="customer_phone" type="text" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Email (Opsional)</label>
              <input id="customer_email" type="email" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
            </div>
          </div>

          <button id="goStep2"
            class="w-full mt-4 bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition">
            Lanjut ke Informasi Pengiriman →
          </button>
        </div>

        <!-- CARD STEP 2 -->
        <div id="step2" class="hidden bg-white shadow rounded-xl p-6 space-y-6 border border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full text-xs">2</span>
            Informasi Pengiriman
          </h2>

          <div class="space-y-4">

            <div>
              <label class="block text-sm font-medium mb-1">Metode Pengiriman</label>
              <select id="shipping_method" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
                <option value="pickup">Ambil di Tempat</option>
                <option value="delivery">Pengiriman</option>
              </select>
            </div>

            <!-- DELIVERY SECTION -->
            <div id="deliveryInfo" class="hidden space-y-4">

              <p class="text-sm text-gray-700 bg-yellow-50 p-3 border border-yellow-200 rounded-lg">
                Pemesanan kurir dilakukan secara mandiri. Silakan hubungi kurir yang Anda pilih, lalu konfirmasi ke staff UPBS.
              </p>

              <div>
                <label class="block text-sm font-medium mb-1">Alamat Pengiriman</label>
                <textarea id="customer_address" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Nama Kurir</label>
                <input id="courier_name" type="text" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
              </div>

            </div>
          </div>

          <!-- BUTTONS -->
          <div class="flex items-center justify-between mt-6">
            <button id="backToStep1"
              class="text-gray-700 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
              ← Kembali
            </button>

            <button id="submitOrder"
              class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition">
              Buat Pesanan
            </button>
          </div>
        </div>

      </div>

    </div>

    <!-- ----------------------------
        SCRIPT
    ----------------------------- -->
<script>
/* Navigasi Step */
document.getElementById("goStep2").onclick = () => {
    document.getElementById("step1").classList.add("hidden");
    document.getElementById("step2").classList.remove("hidden");
};

document.getElementById("backToStep1").onclick = () => {
    document.getElementById("step2").classList.add("hidden");
    document.getElementById("step1").classList.remove("hidden");
};

/* Tampilkan Field Delivery */
document.getElementById("shipping_method").onchange = (e) => {
    const value = e.target.value;
    document.getElementById("deliveryInfo").classList.toggle("hidden", value !== "delivery");
};

/* Submit Order */
document.getElementById("submitOrder").onclick = async () => {
    let cart = JSON.parse(localStorage.getItem("cart") || "[]");

    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    const items = cart.map(item => ({
        variety_id: item.variety_id,
        quantity: item.quantity,
        seed_lot_id: item.seed_lot_id || null
    }));

    const payload = {
        customer_name: document.getElementById("customer_name").value,
        customer_phone: document.getElementById("customer_phone").value,
        customer_email: document.getElementById("customer_email").value || null,
        customer_address: document.getElementById("customer_address").value || null,
        shipping_method: document.getElementById("shipping_method").value,
        courier_name: document.getElementById("courier_name").value || null,
        payment_method: "bank_transfer",
        items: items
    };

    try {
        const response = await fetch("http://localhost:8000/api/orders", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (!response.ok) {
            alert("Gagal membuat pesanan, periksa kembali data!");
            return;
        }

        alert("Pesanan berhasil dibuat!\nKode: " + result.data.order_code);

        localStorage.removeItem("cart");

        window.location.href = "/order-success?order=" + result.data.order_code;

    } catch (err) {
        alert("Terjadi kesalahan koneksi.");
    }
};
</script>

  </body>
</html>

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
              <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
              <textarea id="customer_address" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required></textarea>
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
                <option value="delivery">Kirim</option>
              </select>
            </div>

            <!-- DELIVERY SECTION (Auto Courier Info) -->
            <div id="deliveryInfo" class="hidden space-y-4">
              <p id="autoCourierInfo" class="text-sm text-gray-700 bg-blue-50 p-3 border border-blue-200 rounded-lg"></p>
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
          <div class="mt-4 flex items-center gap-2">
            <input id="terms_accepted" type="checkbox" class="rounded">
            <label for="terms_accepted" class="text-sm text-gray-700">Saya menyetujui syarat dan ketentuan</label>
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
    const info = document.getElementById("deliveryInfo");
    info.classList.toggle("hidden", value !== "delivery");
    if (value === "delivery") {
        try {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]");
            const totalWeight = cart.reduce((sum, it) => sum + (parseInt(it.qty || 0) || 0), 0);
            const courier = totalWeight > 10 ? "Indah Cargo" : "Pos Indonesia";
            document.getElementById("autoCourierInfo").textContent = `Kurir otomatis: ${courier} (berdasarkan total berat ${totalWeight} kg)`;
        } catch (_) {
            document.getElementById("autoCourierInfo").textContent = "Kurir otomatis akan ditentukan saat pemesanan.";
        }
    }
};

/* Submit Order */
document.getElementById("submitOrder").onclick = async () => {
    let cart = JSON.parse(localStorage.getItem("cart") || "[]");

    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    const items = cart.map(item => ({
        variety_id: item.id,
        quantity: item.qty,
        seed_lot_id: item.seed_lot_id || null
    }));

    const payload = {
        customer_name: document.getElementById("customer_name").value,
        customer_address: document.getElementById("customer_address").value,
        customer_phone: document.getElementById("customer_phone").value,
        customer_email: document.getElementById("customer_email").value || null,
        shipping_method: document.getElementById("shipping_method").value,
        // courier_name ditentukan otomatis oleh backend
        payment_method: "bank_transfer",
        items: items,
        terms_accepted: document.getElementById("terms_accepted").checked ? true : false,
        // tidak perlu acknowledgement khusus
    };

    try {
        const res = await window.axios.post('/orders/checkout', payload);
        const result = res.data;

        alert("Pesanan berhasil dibuat!\nKode: " + result.data.order_code);

        localStorage.removeItem("cart");
        window.location.href = "/";

    } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
            const errs = err.response.data.errors;
            const firstKey = Object.keys(errs)[0];
            alert(String(errs[firstKey]));
        } else {
            alert("Terjadi kesalahan koneksi.");
        }
    }
};
</script>

  </body>
</html>

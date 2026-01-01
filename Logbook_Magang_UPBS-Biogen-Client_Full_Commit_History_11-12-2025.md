### [25-11-2025] : Add .gitignore file

---

### [25-11-2025] : Initial commit. From previous repo to current repo

---

### [26-11-2025] : keranjang, checkout

---

### [26-11-2025] : - folder trae backup docum

---

### [28-11-2025] : [fix] Payload checkout & header API (SKPL-WUB-IN-020)
- Menetapkan default header Accept: application/json dan axios.defaults.baseURL ke backend admin agar request dikenali sebagai API dan tidak ter-redirect ke /login
- Mengirim checkout via POST /api/orders/checkout menggunakan axios
- Menyesuaikan payload items agar sesuai backend: variety_id , quantity , seed_lot_id
- Menambahkan field wajib di form: customer_address dan terms_accepted ; serta courier_name (select: Pos Indonesia/Indah Cargo) dan delivery_coordination_acknowledged untuk metode delivery
- Menangani error validasi dari backend dan menampilkan pesan yang relevan ke pengguna
- Konfigurasi dev: set APP_URL ke http://localhost:8001 di .env.example , tambah script composer serve pada port 8001
File terkait:

- resources/js/bootstrap.js — set header Accept dan baseURL
- resources/views/components/form-checkout.blade.php — update form dan payload, error handling
- .env.example — APP_URL ke http://localhost:8001
- composer.json — menambah script serve untuk port 8001
Alasan perubahan:

- Menjaga konsistensi dengan CheckoutRequest di backend dan SKPL-WUB-IN-020 (guest checkout)
- Mencegah kendala CORS/redirect dan memastikan payload dikirim sesuai format yang divalidasi backend

---

### [28-11-2025] : [chore] Set APP_URL 8001, tambah script serve
Mengubah .env.example untuk menetapkan APP_URL menjadi http://localhost:8001 agar konsisten dengan port pengembangan aplikasi client.

Menambahkan script serve pada composer.json ( php artisan serve --port=8001 ) untuk memudahkan menjalankan server dev client pada port yang sesuai.

Tujuan perubahan: memastikan origin frontend konsisten (8001) sehingga integrasi dengan backend admin (8002) berjalan baik dan mengurangi potensi isu CORS serta URL asset/link yang tidak tepat.

SKPL: SKPL-WUB-IN-020

---

### [28-11-2025] : [chore] Set APP_URL 8001 & tambah script serve
Mengubah  untuk menetapkan  menjadi  agar konsisten dengan port pengembangan aplikasi client.

Menambahkan script  pada  untuk menjalankan , memudahkan start server dev di port yang sesuai.

Tujuan: memastikan origin frontend konsisten sehingga integrasi dengan backend admin berjalan baik dan mengurangi potensi isu CORS serta URL asset yang tidak tepat.

SKPL: SKPL-WUB-IN-020

---

### [28-11-2025] : Update filter katalog & controller

---

### [28-11-2025] : perbaikan bug pada tombol filter komoditas

---

### [28-11-2025] : perbaikan bug pada tombol filter komoditas dan update tombol pada card varietas

---

### [28-11-2025] : caching untuk mengurangi request ke server

---

### [29-11-2025] : Fitur search bar

---

### [29-11-2025] : [chore] Konfigurasi dev client ke port 8001
- Menetapkan APP_URL menjadi http://localhost:8001 agar origin frontend konsisten selama pengembangan
- Menambahkan script serve ( php artisan serve --port=8001 ) untuk memudahkan menjalankan server dev di port yang sesuai
- Dampak: URL asset dan link sesuai dengan origin, integrasi API backend admin lebih stabil dan mengurangi potensi masalah CORS
- SKPL: SKPL-WUB-IN-020

---

### [01-12-2025] : detail produk page

---

### [01-12-2025] : perbaikan bug pada card varities di catalog

---

### [01-12-2025] : tracking order

---

### [02-12-2025] : Fix Katalog without modal pop up

---

### [03-12-2025] : feat(checkout): implement checkout page with cart functionality and midtrans integration
refactor(cart): move cart logic to dedicated page and update navbar links

style(produk): add new CSS for product detail page and seed class cards

docs(env): remove unused DB config from .env.example

feat(midtrans): update config keys to snake_case and add client_key

feat(routes): add checkout routes and move cart show to dedicated page

feat(controllers): add CheckoutController with process method for midtrans

test(views): add about page and update product detail with seed class selection

chore(vite): add produk.js and produk.css to vite config

refactor(catalog): add seed class filter and update product detail logic

style(navbar): update mobile menu background and cart link

---

### [03-12-2025] : feat(checkout): add payment success and error pages with routes
Add new payment finish and error routes with corresponding controller methods
Create payment-success and payment-error blade templates
Remove home link from product detail breadcrumb

---

### [04-12-2025] : feat(track-order): add order detail page and improve search functionality
- Add new route and controller method for order detail page
- Implement search method selection in track order form
- Add lazy loading for images and improve error handling
- Remove unused checkout form component
- Refactor catalog controller to support seed class filtering

---

### [04-12-2025] : feat(order): add print functionality for order invoices and receipts
refactor(views): implement page transition animations across all views
style(css): add and optimize animation styles for better UX
fix(navbar): update broken links and routing
feat(home): display product varieties on homepage
refactor(catalog): improve product display and filtering

---

### [05-12-2025] : refactor(checkout): migrate from direct payment url to midtrans snap integration
Remove midtrans-php dependency as it's no longer needed for client-side integration
Add snap.js script to checkout page and update checkout.js to handle snap popup

---

### [10-12-2025] : docs: add comprehensive project analysis report for WUB project
Add detailed technical documentation covering both admin and client repositories, including architecture, API endpoints, validation rules, and identified issues. The report serves as a reference for current implementation and future improvements.

---

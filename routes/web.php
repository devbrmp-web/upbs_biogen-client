<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderMailController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// })->name('home');

Route::get('/', [CatalogController::class, 'homeindex'])->name('home');
Route::get('/katalog', [CatalogController::class, 'catalogindex'])->name('katalog');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/orders/payment/sync', [CheckoutController::class, 'syncPaymentStatus'])->name('orders.payment.sync');
Route::post('/orders/payment/snap-token', [CheckoutController::class, 'getSnapToken'])->name('orders.payment.snap-token');
Route::get('/payment/finish', [CheckoutController::class, 'paymentFinish'])->name('payment.finish');
Route::get('/payment/error', [CheckoutController::class, 'paymentError'])->name('payment.error');

// AJAX
Route::get('/products/seed-lots/{variety}/{seed_class}', [CatalogController::class, 'getSeedLots']);
Route::get('/api/varieties/{variety}/seed-classes/{seed_class}/seed-lots', [CatalogController::class, 'getSeedLots']); // Alias for Checkout JS

// routes/web.php
Route::get('/search-suggest', [CatalogController::class, 'searchSuggest']);
Route::get('/search', [CatalogController::class, 'search'])->name('search');
Route::get('/produk/{slug}', [CatalogController::class, 'productDetail'])->name('product.detail');

Route::view('/tentang-kami', 'tentang-kami')->name('about');
Route::view('/kontak', 'kontak')->name('contact');
Route::view('/faq', 'faq')->name('faq');
Route::view('/kebijakan-privasi', 'kebijakan-privasi')->name('privacy');
Route::view('/syarat-ketentuan', 'syarat-ketentuan')->name('terms');

use App\Http\Controllers\TrackOrderController;

Route::get('/cek-pesanan', [TrackOrderController::class, 'index'])->name('cek-pesanan');
Route::get('/pesanan/signature/{order_code}', [TrackOrderController::class, 'signature'])->name('order.signature');
Route::get('/pesanan/{order_code}', [TrackOrderController::class, 'detail'])->name('order.detail');
Route::get('/pesanan/{order_code}/payment', [TrackOrderController::class, 'instruction'])->name('order.payment');
Route::post('/pesanan/{order_code}/upload-bukti', [TrackOrderController::class, 'uploadProof'])->name('order.upload-proof');
Route::get('/pesanan/{order_code}/receipt', [TrackOrderController::class, 'print'])->name('order.print');
Route::get('/tutorial', function () {
    return view('tutorial-buy');
})->name('tutorial');

Route::get('/home', function () {
    return view('beranda-statis');
})->name('home.static');

// Cache Management Routes (Admin only - bisa ditambahkan middleware nanti)
Route::get('/cache/price/clear/{slug}', [CatalogController::class, 'clearPriceCache'])->name('cache.price.clear');
Route::get('/cache/price/clear-all', [CatalogController::class, 'clearAllPriceCaches'])->name('cache.price.clear-all');

// Email resi sender
Route::post('/orders/send-invoice', [OrderMailController::class, 'sendInvoice']);

// API Proxy Routes (for JavaScript AJAX calls)
Route::get('/api/orders/{order_code}', [TrackOrderController::class, 'getOrderJson'])->name('api.order.show');

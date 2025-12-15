<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderMailController;

use App\Http\Controllers\CheckoutController;

// Route::get('/', function () {
//     return view('home');
// })->name('home');



Route::get('/', [CatalogController::class, 'homeindex'])->name('home');
Route::get('/katalog', [CatalogController::class, 'catalogindex'])->name('katalog');



Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
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

use App\Http\Controllers\TrackOrderController;

Route::get('/cek-pesanan', [TrackOrderController::class, 'index'])->name('cek-pesanan');
Route::get('/pesanan/{order_code}', [TrackOrderController::class, 'detail'])->name('order.detail');
Route::get('/pesanan/{order_code}/cetak', [TrackOrderController::class, 'print'])->name('order.print');


// Email resi sender
Route::post('/orders/send-invoice', [OrderMailController::class, 'sendInvoice']);




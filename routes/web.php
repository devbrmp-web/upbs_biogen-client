<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;

// Route::get('/', function () {
//     return view('home');
// })->name('home');



Route::get('/', [CatalogController::class, 'homeindex'])->name('home');
Route::get('/katalog', [CatalogController::class, 'catalogindex'])->name('katalog');



Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

Route::get('/checkout', function () {
    return view('/components/form-checkout');
})->name('checkout.form');

// routes/web.php
Route::get('/search-suggest', [CatalogController::class, 'searchSuggest']);
Route::get('/search', [CatalogController::class, 'search'])->name('search');
Route::get('/produk/{slug}', [CatalogController::class, 'productDetail'])->name('product.detail');
use App\Http\Controllers\TrackOrderController;

Route::get('/cek-pesanan', [TrackOrderController::class, 'index'])->name('cek-pesanan');





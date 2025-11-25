<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// })->name('home');



use App\Http\Controllers\CatalogController;

Route::get('/', [CatalogController::class, 'homeindex'])->name('home');
Route::get('/katalog', [CatalogController::class, 'catalogindex'])->name('katalog');


use App\Http\Controllers\CartController;

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');


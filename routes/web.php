<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaukinController;


// Halaman Mode Pembeli
Route::get('/', [LaukinController::class, 'index'])->name('buyer.index');
Route::post('/checkout', [LaukinController::class, 'checkout'])->name('buyer.checkout');
Route::get('/order-status/{orderNumber}', [LaukinController::class, 'checkStatus'])->name('buyer.checkStatus');

// Halaman Mode Pedagang
Route::get('/pedagang', [LaukinController::class, 'pedagang'])->name('pedagang.index');
Route::post('/pedagang/order-status/{id}', [LaukinController::class, 'updateStatus'])->name('pedagang.updateStatus');
Route::post('/pedagang/reset', [LaukinController::class, 'resetOrders'])->name('pedagang.reset');
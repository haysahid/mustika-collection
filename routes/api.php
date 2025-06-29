<?php

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::post('/sync-cart', [OrderController::class, 'syncCart'])->name('sync_cart');
    Route::get('/provinces', [OrderController::class, 'provinces'])->name('provinces');
    Route::get('/cities', [OrderController::class, 'cities'])->name('cities');
    Route::get('/shipping-cost', [OrderController::class, 'shippingCost'])->name('shipping_cost');
    Route::post('/confirm-payment', [OrderController::class, 'confirmPayment'])->name('confirm-payment');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    });
});

Route::name('api.admin.')->prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('product-image', ProductImageController::class);
});

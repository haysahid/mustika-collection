<?php

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductImageController;
use App\Http\Controllers\API\ProductVariantController;
use App\Http\Controllers\API\ProductVariantImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::post('/sync-cart', [OrderController::class, 'syncCart'])->name('sync-cart');
    Route::get('/provinces', [OrderController::class, 'provinces'])->name('provinces');
    Route::get('/cities', [OrderController::class, 'cities'])->name('cities');
    Route::get('/shipping-cost', [OrderController::class, 'shippingCost'])->name('shipping-cost');
    Route::post('/confirm-payment', [OrderController::class, 'confirmPayment'])->name('confirm-payment');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/cancel-order', [OrderController::class, 'cancelOrder'])->name('cancel-order');
    });
});

Route::name('api.admin.')->prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('product', ProductController::class);
    Route::apiResource('product-image', ProductImageController::class);
    Route::apiResource('product-variant', ProductVariantController::class);
    Route::apiResource('product-variant-image', ProductVariantImageController::class);
});

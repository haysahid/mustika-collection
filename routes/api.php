<?php

use App\Http\Controllers\API\ProductImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->name('api.admin.')->group(function () {
        Route::apiResource('product-image', ProductImageController::class);
    });
});

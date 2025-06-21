<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StoreCertificateController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PublicController::class, 'home'])->name('home');

Route::get('/catalog', [PublicController::class, 'catalog'])->name('catalog');

Route::get('/product/{slug}', [PublicController::class, 'productDetail'])->name('product.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'loginProcess']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Store
        Route::get('/store-info', [StoreController::class, 'edit'])->name('store.edit');
        Route::post('/store-info', [StoreController::class, 'update'])->name('store.update');

        // Certificate
        Route::get('/certificate', [StoreCertificateController::class, 'index'])->name('certificate');
        Route::get('/certificate/create', [StoreCertificateController::class, 'create'])->name('certificate.create');
        Route::post('/certificate', [StoreCertificateController::class, 'store'])->name('certificate.store');
        Route::get('/certificate/{storeCertificate}', [StoreCertificateController::class, 'edit'])->name('certificate.edit');
        Route::post('/certificate/{storeCertificate}', [StoreCertificateController::class, 'update'])->name('certificate.update');
        Route::delete('/certificate/{storeCertificate}', [StoreCertificateController::class, 'destroy'])->name('certificate.destroy');

        // Product
        Route::get('/product', [ProductController::class, 'index'])->name('product');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/{product}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/product/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    });
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

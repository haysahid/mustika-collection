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

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'loginProcess']);

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/store-info', [StoreController::class, 'edit'])->name('admin.store.edit');
        Route::post('/store-info', [StoreController::class, 'update'])->name('admin.store.update');

        Route::get('/certificate', [StoreCertificateController::class, 'index'])->name('admin.certificate');
        Route::get('/certificate/create', [StoreCertificateController::class, 'create'])->name('admin.certificate.create');
        Route::post('/certificate', [StoreCertificateController::class, 'store'])->name('admin.certificate.store');
        Route::get('/certificate/{storeCertificate}', [StoreCertificateController::class, 'edit'])->name('admin.certificate.edit');
        Route::post('/certificate/{storeCertificate}', [StoreCertificateController::class, 'update'])->name('admin.certificate.update');
        Route::delete('/certificate/{storeCertificate}', [StoreCertificateController::class, 'destroy'])->name('admin.certificate.destroy');

        Route::get('/product', [ProductController::class, 'index'])->name('admin.product');
        Route::get('/product/create', [ProductController::class, 'create'])->name('admin.product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('admin.product.store');
        Route::post('/product/{product}', [ProductController::class, 'update'])->name('admin.product.update');

        Route::get('/product/{product}', [ProductController::class, 'edit'])->name('admin.product.edit');
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

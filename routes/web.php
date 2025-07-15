<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreCertificateController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PublicController::class, 'home'])->name('home');

Route::get('/catalog', [PublicController::class, 'catalog'])->name('catalog');
Route::get('/product/{slug}', [PublicController::class, 'productDetail'])->name('product.show');
Route::get('/my-cart', [PublicController::class, 'myCart'])->name('my-cart');

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'loginProcess'])->name('login.process');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'registerProcess'])->name('register.process');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/order-success', [OrderController::class, 'orderSuccess'])->name('order.success');
    Route::get('/my-order', [OrderController::class, 'myOrder'])->name('my-order');
    Route::get('/my-order/{transaction_code}', [OrderController::class, 'myOrderDetail'])->name('my-order.detail');
});

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

        // Brand
        Route::get('/brand', [BrandController::class, 'index'])->name('brand');
        Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/brand', [BrandController::class, 'store'])->name('brand.store');
        Route::get('/brand/{brand}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('/brand/{brand}', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('/brand/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');

        // Product
        Route::get('/product', [ProductController::class, 'index'])->name('product');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/{product}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/product/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

        // Transaction
        Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction');
        Route::get('/transaction/{transaction}', [TransactionController::class, 'edit'])->name('transaction.edit');

        // Report
        Route::get('/report', [ReportController::class, 'index'])->name('report');
        Route::get('/report/preview', [ReportController::class, 'reportPreview'])->name('report.preview');
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

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreCertificateController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);

    return Inertia::render('Home');
})->name('home');

Route::get('/catalog',  fn() => Inertia::render('Catalog'))->name('catalog');

Route::get('/product/{slug}', fn() => Inertia::render('ProductDetail'))->name('product.show');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])
        ->middleware('guest')
        ->name('admin.login');

    Route::post('/login', [AdminController::class, 'loginProcess']);

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');

        Route::get('/store-info', function () {
            return Inertia::render('Admin/StoreInfo');
        })->name('admin.store.info');

        Route::get('/certificate', [StoreCertificateController::class, 'index'])
            ->name('admin.certificate');

        Route::get('/certificate/add', [StoreCertificateController::class, 'create'])
            ->name('admin.certificate.add');

        Route::get('/certificate/{id}', [StoreCertificateController::class, 'show'])
            ->name('admin.certificate.edit');

        Route::get('/product', function () {
            return Inertia::render('Admin/Product');
        })->name('admin.product');

        Route::get('/product/add', function () {
            return Inertia::render('Admin/Product/AddProduct');
        })->name('admin.product.add');

        Route::get('/product/{id}', function () {
            return Inertia::render('Admin/Product/EditProduct');
        })->name('admin.product.edit');
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

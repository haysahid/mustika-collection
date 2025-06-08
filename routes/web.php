<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
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

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/admin/login', [AdminController::class, 'login'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/admin/login', [AdminController::class, 'loginProcess']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

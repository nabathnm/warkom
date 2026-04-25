<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;

// Root route
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('products.index');
    }
    return redirect()->route('login');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (auth only)
Route::middleware('auth')->group(function () {
    // User-facing product routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Admin product management routes
    Route::resource('admin/products', ProductController::class)->except(['index', 'show']);

    // Cart routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cart}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Checkout Routes
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [App\Http\Controllers\OrderController::class, 'success'])->name('checkout.success');

    // Reviews Route
    Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});
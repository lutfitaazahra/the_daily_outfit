<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// Public routes
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/terbaru', [ProductController::class, 'newArrivals'])->name('new-arrivals');
Route::get('/product/{id}', [ProductController::class, 'detail'])->name('product.detail');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer routes (must login)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/buy-now', [CheckoutController::class, 'buyNow'])->name('buy.now');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/payment/{order}', [OrderController::class, 'payment'])->name('payment');
    Route::post('/payment/{order}', [OrderController::class, 'confirmPayment'])->name('payment.confirm');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'detail'])->name('orders.detail');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'detail'])->name('orders.detail');
    Route::post('/orders/{order}', [AdminOrderController::class, 'updateStatus'])->name('orders.update');
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::post('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});
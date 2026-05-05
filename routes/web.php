<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductAdminController;

Route::get('/', [HomeController::class, 'viewHome']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::get('/cart/{user_id}', [CartController::class, 'viewCart'])->name('viewCart');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/remove-item', [CartController::class, 'removeItem'])->name('cart.removeItem');

Route::get('/product-detail/{slug}', [ProductDetailController::class, "viewProductDetail"])->name('viewProductDetail');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function() {
    Route::get('/', [DashboardController::class, 'viewAdminDashboard'])->name('viewAdminDashboard');
    Route::get('/products', [ProductAdminController::class, 'viewProductAdmin'])->name('admin.products');
});
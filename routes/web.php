<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductAdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'viewHome'])->name('viewHome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/cart/add-item', [CartController::class, 'addItem'])->name('cart.addItem');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/remove-item', [CartController::class, 'removeItem'])->name('cart.removeItem');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('viewCart');

    Route::get('/checkout/{ma_bien_the?}', [PaymentController::class, 'viewPayment'])->name('viewPayment');
    Route::post('/prepare-payment', [PaymentController::class, 'preparePayment'])->name('preparePayment');

    Route::post('/order/create', [OrderController::class, 'storeCreateOrder'])->name('storeCreateOrder');
    Route::get('/orders', [OrderController::class, 'viewOrder'])->name('viewOrder');
    Route::get('/orders/{ma_don_hang}', [OrderController::class, 'viewOrderDetail'])->name('viewOrderDetail');

    Route::get('/momo/create/{ma_don_hang}', [PaymentController::class, 'createMomoPayment'])->name('momo.create');
    Route::get('/momo/return', [PaymentController::class, 'momoReturn'])->name('momo.return');
});

Route::post('/momo/ipn', [PaymentController::class, 'momoIpn'])->name('momo.ipn');

Route::get('/product-detail/{ma_don_hang}', [ProductDetailController::class, "viewProductDetail"])->name('viewProductDetail');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'viewAdminDashboard'])->name('admin.dashboard.index');
    Route::get('/products', [ProductAdminController::class, 'viewProductAdmin'])->name('admin.products.index');
    Route::get('/products/create', [ProductAdminController::class, 'viewCreateProductAdmin'])->name('admin.products.create');

    Route::post('/products', [ProductAdminController::class, 'storeCreateProductAdmin'])->name('admin.products.store');

    Route::get('/products/{product}/edit', [ProductAdminController::class, 'viewEditProductAdmin'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductAdminController::class, 'updateEditProductAdmin'])->name('admin.products.update');

    Route::get('/order', [OrderController::class, 'viewAdminOrder'])->name('admin.order.index');
    Route::get('/order/{ma_don_hang}', [OrderController::class, 'viewAdminOrderDetail'])->name('admin.order.view');
    Route::post('/order/{ma_don_hang}/status', [OrderController::class, 'updateAdminOrderStatus'])->name('admin.order.updateStatus');
});

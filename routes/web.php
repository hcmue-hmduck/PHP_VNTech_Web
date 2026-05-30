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
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\Brands_CategoriesAdminController;
use App\Http\Controllers\FlashSalesController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AiController;

Route::get('/', [HomeController::class, 'viewHome'])->name('viewHome');

Route::post('/momo/ipn', [PaymentController::class, 'momoIpn'])->name('momo.ipn');

Route::get('/product-detail/{ma_don_hang}', [ProductDetailController::class, "viewProductDetail"])->name('viewProductDetail');

// chatbot
Route::get('/chat/history', [AiController::class, 'history'])->name('chat.history');
Route::post('/chat', [AiController::class, 'chat'])->name('chat');
Route::post('/chat/clear', [AiController::class, 'clear'])->name('chat.clear');


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
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');

    Route::get('/user', [UserController::class, 'viewUserInfo'])->name('user.view');
    
    Route::post('/user-address', [UserAddressController::class, 'storeAddress'])->name('user-address.store');

    Route::get('/checkout/{ma_bien_the?}', [PaymentController::class, 'viewPayment'])->name('payment.view');
    Route::post('/prepare-payment', [PaymentController::class, 'preparePayment'])->name('payment.prepare');

    Route::post('/order/create', [OrderController::class, 'storeCreateOrder'])->name('order.store');
    Route::get('/orders', [OrderController::class, 'viewOrder'])->name('order.view');
    Route::get('/orders/{ma_don_hang}', [OrderController::class, 'viewOrderDetail'])->name('order_detail.view');

    Route::get('/momo/create/{ma_don_hang}', [PaymentController::class, 'createMomoPayment'])->name('momo.create');
    Route::get('/momo/return', [PaymentController::class, 'momoReturn'])->name('momo.return');
});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'viewAdminDashboard'])->name('admin.dashboard.index');

    Route::get('/products', [ProductAdminController::class, 'viewProductAdmin'])->name('admin.products.index');
    Route::get('/products/create', [ProductAdminController::class, 'viewCreateProductAdmin'])->name('admin.products.create');
    Route::post('/products', [ProductAdminController::class, 'storeCreateProductAdmin'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductAdminController::class, 'viewEditProductAdmin'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductAdminController::class, 'updateEditProductAdmin'])->name('admin.products.update');
    Route::put('/product/{product}/delete', [ProductAdminController::class, 'deleteProductAdmin'])->name('admin.product.delete');

    Route::get('/order', [OrderController::class, 'viewAdminOrder'])->name('admin.order.index');
    Route::get('/order/{ma_don_hang}', [OrderController::class, 'viewAdminOrderDetail'])->name('admin.order.view');
    Route::post('/order/{ma_don_hang}/status', [OrderController::class, 'updateAdminOrderStatus'])->name('admin.order.updateStatus');

    Route::get('/brands_categories', [Brands_CategoriesAdminController::class, 'viewBrandsCategories'])->name('admin.brandscategories.index');
    
    Route::post('/brands_categories/brand', [Brands_CategoriesAdminController::class, 'storeCreateBrand'])->name('admin.brandscategories.brand.store');
    Route::put('/brands_categories/brand/{brand}', [Brands_CategoriesAdminController::class, 'updateEditBrand'])->name('admin.brandscategories.brand.update');
    
    Route::post('/brands_categories/category', [Brands_CategoriesAdminController::class, 'storeCreateCategory'])->name('admin.brandscategories.category.store');
    Route::put('/brands_categories/category/{category}', [Brands_CategoriesAdminController::class, 'updateEditCategory'])->name('admin.brandscategories.category.update');

    Route::get('/flash-sales', [FlashSalesController::class, 'viewFlashSalesAdmin'])->name('admin.flashsales.index');

    Route::get('/flash-sales/create', [FlashSalesController::class, 'viewCreateFlashSalesAdmin'])->name('admin.flashsales.create');
    Route::post('/flash-sales', [FlashSalesController::class, 'storeCreateFlashSalesAdmin'])->name('admin.flashsales.store');

    Route::get('/flash-sales/{flash_sales}/edit', [FlashSalesController::class, 'viewEditFlashSalesAdmin'])->name('admin.flashsales.edit');
    Route::put('/flash-sales/{flash_sales}', [FlashSalesController::class, 'updateEditFlashSalesAdmin'])->name('admin.flashsales.update');

    // Route::get('/vouchers', [VoucherController::class, 'viewVoucherAdmin'])->name('admin.voucher.view');
    // Route::get('/voucher/create', [VoucherController::class, 'viewCreateVoucherAdmin'])->name('admin.voucher.create');
    // Route::post('/vouchers', [VoucherController::class, 'storeCreateVoucherAdmin'])->name('admin.voucher.store');
    // Route::get('/voucher/{voucher}/edit', [VoucherController::class, 'viewEditVoucherAdmin'])->name('admin.voucher.edit');
    // Route::put('/voucher/{voucher}', [VoucherController::class, 'updateEditVoucherAdmin'])->name('admin.voucher.update');
    // Route::put('/voucher/{voucher}/delete', [VoucherController::class, 'deleteVoucherAdmin'])->name('admin.voucher.delete');
});

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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BannerImagesController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewReplyController;
use App\Http\Controllers\CompareController;

Route::get('/', [HomeController::class, 'viewHome'])->name('home.index');
Route::get('/products', [HomeController::class, 'viewHomeProducts'])->name('home.products');
Route::get('/news', [HomeController::class, 'viewHomeNews'])->name('home.news');
Route::view('/support', 'homeUI.support')->name('support');
Route::view('/contact', 'homeUI.contact')->name('contact');

Route::view('/compare', 'homeUI.compare')->name('compare.view');
Route::post('/compare/variants', [CompareController::class, 'variants'])->name('compare.variants');
Route::post('/compare/ai', [CompareController::class, 'aiCompare'])->name('compare.ai');

Route::get('/products/{ma_san_pham}/product-detail/{ma_bien_the?}', [ProductDetailController::class, 'viewProductDetail'])->name('home.product_detail');

Route::get('/products/{ma_san_pham}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

Route::get('/products/search-sugggestion', [HomeController::class, 'searchSuggest'])->name('home.product.search');

Route::post('/momo/ipn', [PaymentController::class, 'momoIpn'])->name('momo.ipn');

// chatbot
Route::get('/chat/history', [AiController::class, 'history'])->name('chat.history');
Route::post('/chat', [AiController::class, 'chat'])->name('chat');
Route::post('/chat/clear', [AiController::class, 'clear'])->name('chat.clear');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

    Route::prefix('otp/{flow}')->group(function () {
    Route::get('/', [AuthController::class, 'showOtpForm'])->name('otp.show');
    Route::post('/send', [AuthController::class, 'sendOtp'])->name('otp.send');
    Route::post('/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
    Route::post('/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/cart/add-item', [CartController::class, 'addItem'])->name('cart.addItem');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/remove-item', [CartController::class, 'removeItem'])->name('cart.removeItem');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');

    Route::get('/user', [UserController::class, 'viewUserInfo'])->name('user.view');
    Route::put('/user/{user}/edit', [UserController::class, 'editUserInfo'])->name('user.update');
    Route::post('/user/email/change/request', [UserController::class, 'requestEmailChange'])->name('user.email.change.request');
    Route::get('/user/email/change/verify', [UserController::class, 'showVerifyChangeEmailOtp'])->name('user.email.change.verify.show');
    Route::post('/user/email/change/verify', [UserController::class, 'verifyChangeEmailOtp'])->name('user.email.change.verify');
    Route::post('/user/email/change/resend', [UserController::class, 'resendChangeEmailOtp'])->name('user.email.change.resend');

    Route::post('/user-address', [UserAddressController::class, 'storeAddress'])->name('user-address.store');
    Route::put('/user-address/{user_address}/edit', [UserAddressController::class, 'updateAddress'])->name('user-address.update');
    Route::post('user-address/{user_address}/destroy', [UserAddressController::class, 'destroyAddress'])->name('user-address.destroy');
    Route::post('/user-address/{user_address}/set-default', [UserAddressController::class, 'setDefaultAddress'])->name('user-address.set-default');
    Route::get('/user-address/{ma_dia_chi}/select', [UserAddressController::class, 'selectAddressGet'])->name('user-address.select');

    Route::get('/checkout/{ma_bien_the?}', [PaymentController::class, 'viewPayment'])->name('payment.view');
    Route::post('/prepare-payment', [PaymentController::class, 'preparePayment'])->name('payment.prepare');

    Route::post('/order/create', [OrderController::class, 'storeCreateOrder'])->name('order.store');
    Route::get('/orders', [OrderController::class, 'viewOrder'])->name('order.view');
    Route::get('/orders/{ma_don_hang}/reviews', [ReviewController::class, 'byOrder'])->name('reviews.by-order');
    Route::get('/orders/{ma_don_hang}', [OrderController::class, 'viewOrderDetail'])->name('order_detail.view');
    Route::post('/orders/{ma_don_hang}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    Route::get('/momo/create/{ma_don_hang}', [PaymentController::class, 'createMomoPayment'])->name('momo.create');
    Route::get('/momo/return', [PaymentController::class, 'momoReturn'])->name('momo.return');

    Route::get('/notifications/read/{ma_thong_bao?}', [NotificationController::class, 'readNotification'])->name('notifications.read');
    Route::post('/notifications/create', [NotificationController::class, 'createNotification'])->name('notifications.create');
});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'viewAdminDashboard'])->name('admin.dashboard.index');

    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/replies', [ReviewReplyController::class, 'store'])->name('admin.review-replies.store');
    Route::put('/reviews/{review}/replies/{reviewReply}', [ReviewReplyController::class, 'update'])->name('admin.review-replies.update');

    Route::get('/products', [ProductAdminController::class, 'viewProductAdmin'])->name('admin.products.index');
    Route::get('/products/create', [ProductAdminController::class, 'viewCreateProductAdmin'])->name('admin.products.create');
    Route::post('/products', [ProductAdminController::class, 'storeCreateProductAdmin'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductAdminController::class, 'viewEditProductAdmin'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductAdminController::class, 'updateEditProductAdmin'])->name('admin.products.update');
    Route::put('/product/{product}/delete', [ProductAdminController::class, 'deleteProductAdmin'])->name('admin.product.delete');

    Route::get('/order', [OrderController::class, 'viewAdminOrder'])->name('admin.order.index');
    Route::get('/order/{ma_don_hang}', [OrderController::class, 'viewAdminOrderDetail'])->name('admin.order.view');
    Route::post('/order/{ma_don_hang}/status', [OrderController::class, 'updateAdminOrderStatus'])->name('admin.order.updateStatus');
    Route::get('/order/{ma_don_hang}/print', [OrderController::class, 'printInvoice'])->name('admin.order.print');

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
    Route::put('/flash-sales/{flash_sales}/delete', [FlashSalesController::class, 'deleteFlashSalesAdmin'])->name('admin.flashsales.delete');

    Route::get('/vouchers', [VoucherController::class, 'viewVoucherAdmin'])->name('admin.voucher.view');
    Route::get('/voucher/create', [VoucherController::class, 'viewCreateVoucherAdmin'])->name('admin.voucher.create');
    Route::post('/vouchers', [VoucherController::class, 'storeCreateVoucherAdmin'])->name('admin.voucher.store');
    Route::get('/voucher/{voucher}/edit', [VoucherController::class, 'viewEditVoucherAdmin'])->name('admin.voucher.edit');
    Route::put('/voucher/{voucher}', [VoucherController::class, 'updateEditVoucherAdmin'])->name('admin.voucher.update');
    Route::put('/voucher/{voucher}/delete', [VoucherController::class, 'deleteVoucherAdmin'])->name('admin.voucher.delete');

    Route::get('/users', [UserController::class, 'viewUsersAdmin'])->name('admin.user.view');
    Route::put('/users/{user}/status', [UserController::class, 'updateUserStatus'])->name('admin.user.update');

    Route::get('/banner-image', [BannerImagesController::class, 'viewBanner'])->name('admin.banner.index');
    Route::get('/banner-image/create', [BannerImagesController::class, 'viewCreateBanner'])->name('admin.banner.create');
    Route::post('/banner-image', [BannerImagesController::class, 'storeCreateBanner'])->name('admin.banner.store');
    Route::get('/banner-image/{bannerImage}/edit', [BannerImagesController::class, 'viewUpdateBanner'])->name('admin.banner.edit');
    Route::put('/banner-image/{bannerImage}', [BannerImagesController::class, 'updateBanner'])->name('admin.banner.update');
    Route::put('/banner-image/{bannerImage}/delete', [BannerImagesController::class, 'deleteBanner'])->name('admin.banner.delete');
});

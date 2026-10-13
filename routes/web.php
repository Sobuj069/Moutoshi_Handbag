<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Web Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog & Collections (covers all category handles)
Route::get('/collections/{categorySlug?}', [ShopController::class, 'index'])->name('shop.index');
Route::get('/search', [ShopController::class, 'index'])->name('shop.search');

// Single Product
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/order', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/quick-checkout', [CheckoutController::class, 'quickOrder'])->name('checkout.quick');

// Order Confirmation & Tracking
Route::get('/order-success/{orderNumber}', [OrderController::class, 'success'])->name('order.success');
Route::get('/pages/track-my-order', [OrderController::class, 'track'])->name('order.track');
Route::get('/track-order', [OrderController::class, 'track']);

// Content Pages
Route::get('/pages/about-us', [PageController::class, 'about'])->name('page.about');
Route::get('/pages/contact', [PageController::class, 'contact'])->name('page.contact');
Route::post('/pages/contact', [PageController::class, 'contactSubmit'])->name('page.contact.submit');
Route::get('/pages/exchange-return-policy', [PageController::class, 'exchangePolicy'])->name('page.exchange-policy');
Route::get('/pages/faqs', [PageController::class, 'faqs'])->name('page.faqs');
Route::get('/pages/privacy-policy', [PageController::class, 'privacyPolicy'])->name('page.privacy-policy');
Route::get('/pages/terms-conditions', [PageController::class, 'terms'])->name('page.terms');
Route::get('/pages/b2b-queries', [PageController::class, 'b2bQueries'])->name('page.b2b');
Route::post('/pages/b2b-queries', [PageController::class, 'b2bSubmit'])->name('page.b2b.submit');
Route::get('/pages/complaint-portal', [PageController::class, 'complaintPortal'])->name('page.complaint');
Route::get('/pages/i-have-a-complaint', [PageController::class, 'complaintPortal']);
Route::post('/pages/complaint-portal', [PageController::class, 'complaintSubmit'])->name('page.complaint.submit');
Route::get('/pages/meet-our-founder-ceo', [PageController::class, 'founder'])->name('page.founder');

// Blog (News & Insights)
Route::get('/blogs/news-insights', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blogs/news-insights/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Customer Authentication & Account
Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::get('/login', [CustomerAuthController::class, 'showLogin']);
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::get('/register', [CustomerAuthController::class, 'showRegister']);
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::get('/customer/account', [CustomerAuthController::class, 'account'])->name('customer.account');
Route::get('/account', [CustomerAuthController::class, 'account']);
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::resource('products', AdminProductController::class);

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Banners
    Route::resource('banners', AdminBannerController::class);

    // Web Orders & Courier Dispatch
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/send-pathao', [AdminOrderController::class, 'sendToPathao'])->name('orders.send-pathao');
    Route::post('/orders/{order}/track-pathao', [AdminOrderController::class, 'trackPathao'])->name('orders.track-pathao');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/pathao/zones', [AdminOrderController::class, 'getPathaoZones'])->name('pathao.zones');
    Route::get('/pathao/areas', [AdminOrderController::class, 'getPathaoAreas'])->name('pathao.areas');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-pathao', [AdminSettingController::class, 'testPathao'])->name('settings.test-pathao');
});

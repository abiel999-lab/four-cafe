<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\GuideController;

use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\OptionController;
use App\Http\Controllers\Seller\ReportController;
use App\Http\Controllers\Seller\SettingController;
use App\Http\Controllers\Auth\GoogleAuthController;
    // routes/web.php
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect()->route('customer.menu');
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

/**
 * Breeze default expects route name "dashboard".
 * Kita arahkan ke seller dashboard.
 */
Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('seller.dashboard');
})->name('dashboard');


// ===================== CUSTOMER (NO LOGIN) =====================
Route::prefix('customer')->name('customer.')->group(function () {

    // MENU
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    // GUIDE
    Route::get('/guide', [GuideController::class, 'index'])->name('guide');

    // CART
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // INVOICE
    Route::get('/invoice/{order:code}', [CheckoutController::class, 'invoice'])->name('invoice');
});


// ===================== SELLER (LOGIN) =====================
Route::middleware(['auth', 'seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/toggle-paid', [SellerOrderController::class, 'markPaid'])->name('orders.togglePaid');

        Route::post('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('options', OptionController::class)->except(['show']);

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportXlsx'])->name('reports.export');

        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/orders/{order}/invoice', [SellerOrderController::class, 'invoice'])->name('orders.invoice');


        // Seller help
        Route::get('/help', function () {
            return view('seller.help.index');
        })->name('help');
    });



    Route::get('/storage/{path}', function ($path) {
        $full = 'public/' . $path; // storage/app/public/...

        abort_unless(Storage::exists($full), 404);

        return Response::make(Storage::get($full), 200, [
            'Content-Type' => Storage::mimeType($full) ?? 'application/octet-stream',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    })->where('path', '.*');


require __DIR__ . '/auth.php';

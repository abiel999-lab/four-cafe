<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;

use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\OptionController;
use App\Http\Controllers\Seller\ReportController;
use App\Http\Controllers\Seller\SettingController;

Route::get('/', function () {
    return redirect()->route('customer.menu');
});

/**
 * Breeze default expects route name "dashboard".
 * Kita arahkan ke seller dashboard.
 */
Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('seller.dashboard');
})->name('dashboard');


// ===================== CUSTOMER (NO LOGIN) =====================
Route::prefix('c')->name('customer.')->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/cart', [CartController::class, 'show'])->name('cart.show'); // optional bisa tetap
    Route::post('/checkout/bulk', [CheckoutController::class, 'bulk'])->name('checkout.bulk');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/invoice/{order:code}', [CheckoutController::class, 'invoice'])->name('invoice');
});



// ===================== SELLER (LOGIN) =====================
Route::middleware(['auth', 'seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');

        // Toggle Paid (sesuai view: route('seller.orders.togglePaid', $order))
        Route::post('/orders/{order}/toggle-paid', [SellerOrderController::class, 'togglePaid'])
            ->name('orders.togglePaid');

        // Update status (sesuai view: route('seller.orders.updateStatus', $order))
        Route::post('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        // Invoice seller (sesuai view: route('seller.orders.invoice', $order))
        Route::get('/orders/{order}/invoice', [SellerOrderController::class, 'invoice'])
            ->name('orders.invoice');

        // Master data
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('options', OptionController::class)->except(['show']);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Settings
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

require __DIR__ . '/auth.php';

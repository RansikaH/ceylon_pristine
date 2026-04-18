<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// Customer-facing shop routes
Route::get('/', [App\Http\Controllers\ProductController::class, 'shopIndex'])->name('shop.home');
Route::get('/shop/full', [App\Http\Controllers\ProductController::class, 'shopFull'])->name('shop.full');
Route::get('/product/{product}', [App\Http\Controllers\ProductController::class, 'shopShow'])->name('shop.product');

Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

// Customer order history (requires auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Admin login routes (outside auth middleware)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', 'App\Http\Controllers\Auth\AdminLoginController@showLoginForm')
        ->name('admin.login');

    Route::post('/admin/login', 'App\Http\Controllers\Auth\AdminLoginController@login')
        ->name('admin.login.submit');
});

// Admin logout
Route::post('/admin/logout', 'App\Http\Controllers\Auth\AdminLoginController@logout')
    ->name('admin.logout');

// Include notifications routes
require __DIR__.'/notifications.php';

Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    // Dashboard
    Route::get('dashboard', 'App\Http\Controllers\Admin\DashboardController@index')->name('dashboard');
    
    // Profile Management
    Route::get('profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('profile/avatar', [App\Http\Controllers\Admin\AdminProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');
    Route::get('profile/api', [App\Http\Controllers\Admin\AdminProfileController::class, 'getProfile'])->name('profile.api');
    
    // Chat Routes
    Route::get('chat', [MessageController::class, 'adminIndex'])->name('chat.index');
    Route::get('chat/{user}', [MessageController::class, 'adminChatWith'])->name('chat.with');
    Route::post('chat/{user}/send', [MessageController::class, 'adminSend'])->name('chat.send');
    Route::get('chat/{user}/messages', [MessageController::class, 'adminGetMessages'])->name('chat.messages');
    Route::get('conversations', [MessageController::class, 'adminGetConversations'])->name('chat.conversations');
    
    // Products
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    
    // Categories
    Route::resource('categories', 'App\Http\Controllers\Admin\CategoryController');
    
    // Discounts
    Route::get('discounts', [App\Http\Controllers\Admin\DiscountController::class, 'index'])->name('discounts.index');
    Route::post('discounts/update-bulk', [App\Http\Controllers\Admin\DiscountController::class, 'updateBulk'])->name('discounts.update-bulk');
    
    // Customers
    Route::get('customers', 'App\Http\Controllers\Admin\AdminController@customers')->name('customers');
    Route::get('customers/{user}', 'App\Http\Controllers\Admin\AdminController@showCustomer')->name('customers.show');
    Route::post('customers/{user}/message', 'App\Http\Controllers\Admin\AdminController@sendMessage')->name('customers.message');
    Route::post('customers/{user}/send-message', 'App\Http\Controllers\Admin\AdminController@sendMessage')->name('customers.send-message');
    
    // Reports
    Route::get('reports', 'App\Http\Controllers\Admin\AdminController@reports')->name('reports');
    Route::get('reports/sales', 'App\Http\Controllers\Admin\ReportController@salesReport')->name('reports.sales');
    Route::get('reports/item-wise-sales', 'App\Http\Controllers\Admin\ReportController@itemWiseSalesReport')->name('reports.item-wise-sales');
    Route::get('reports/district-wise-sales', 'App\Http\Controllers\Admin\ReportController@districtWiseSalesReport')->name('reports.district-wise-sales');
    Route::get('reports/monthly-item-demand', 'App\Http\Controllers\Admin\ReportController@monthlyItemDemandReport')->name('reports.monthly-item-demand');
    
    // Activity Logs
    Route::get('activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{activityLog}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('activity-logs/json', [App\Http\Controllers\Admin\ActivityLogController::class, 'json'])->name('activity-logs.json');
    Route::post('activity-logs/clear', [App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-logs.clear');
    Route::get('activity-logs/export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
    
    // Orders
    Route::get('orders', 'App\Http\Controllers\Admin\OrderController@index')->name('orders.index');
    Route::get('orders/{order}', 'App\Http\Controllers\Admin\OrderController@show')->name('orders.show');
    Route::put('orders/{order}/status', 'App\Http\Controllers\Admin\OrderController@updateStatus')->name('orders.update-status');
    Route::get('orders/{order}/status', 'App\Http\Controllers\Admin\OrderController@getStatus')->name('orders.get-status');
    Route::get('orders/{order}/details', 'App\Http\Controllers\Admin\OrderController@getDetails')->name('orders.get-details');
    Route::put('orders/{order}/update-quantities', 'App\Http\Controllers\Admin\OrderController@updateQuantities')->name('orders.update-quantities');
    Route::post('orders/export', 'App\Http\Controllers\Admin\OrderController@export')->name('orders.export');
    Route::get('orders/{order}/print', 'App\Http\Controllers\Admin\OrderController@print')->name('orders.print');
    Route::post('orders/bulk-update-status', 'App\Http\Controllers\Admin\OrderController@bulkUpdateStatus')->name('orders.bulk-update-status');
});

// Regular user auth routes
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)
        ->name('dashboard');
        
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User orders - using 'my.' prefix to avoid conflicts with admin routes
    Route::prefix('my')->name('my.')->group(function () {
        Route::get('orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    });

    // Notification Routes
    Route::middleware('auth')->group(function () {
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/latest', [NotificationController::class, 'latest'])->name('latest');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        });
    });

    // Chat Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::post('/send', [MessageController::class, 'send'])->name('send');
        Route::get('/messages', [MessageController::class, 'getMessages'])->name('messages');
        Route::get('/unread-count', [MessageController::class, 'getUnreadCount'])->name('unread-count');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', 'App\Http\Controllers\Auth\AdminLoginController@showLoginForm')
            ->name('login');
        Route::post('login', 'App\Http\Controllers\Auth\AdminLoginController@login')
            ->name('login.submit');
    });
});

require __DIR__.'/auth.php';

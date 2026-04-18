<?php

use Illuminate\Support\Facades\Route;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use App\Http\Controllers\Admin\NotificationController;

// Test notification route (can be removed in production)
Route::get('/test-notification', function () {
    $admin = Admin::first();
    
    if (!$admin) {
        return 'No admin user found. Please create an admin user first.';
    }
    
    $admin->notify(new AdminNotification(
        'Test Notification',
        'This is a test notification to verify the notification system is working.',
        url('/admin/dashboard'),
        'info'
    ));
    
    return 'Test notification sent to admin!';
})->middleware('auth:admin');

// Admin notification routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Main notifications route
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    
    // API routes
    Route::prefix('notifications')->group(function () {
        Route::get('/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });
});

Route::get('/test-notification-all', function () {
    $admins = Admin::all();
    
    if ($admins->isEmpty()) {
        return 'No admin users found. Please create admin users first.';
    }
    
    foreach ($admins as $admin) {
        $admin->notify(new AdminNotification(
            'Test Notification',
            'This is a test notification sent to all admins.',
            url('/admin/dashboard'),
            'info'
        ));
    }
    
    return 'Test notifications sent to ' . $admins->count() . ' admin(s).';
})->middleware('auth:admin');

// Test user notification route - Send to specific user
Route::get('/test-user-notification/{userId}', function ($userId) {
    $user = User::find($userId);
    
    if (!$user) {
        return 'User not found with ID: ' . $userId;
    }
    
    $user->notify(new UserNotification(
        'Welcome Message',
        'This is a test message from the admin. Your order has been processed successfully!',
        url('/dashboard'),
        'success'
    ));
    
    return 'Test notification sent to user: ' . $user->name . ' (ID: ' . $user->id . ')';
})->middleware('auth:admin');

// Test notification to all users
Route::get('/test-user-notification-all', function () {
    $users = User::where('role', 'user')->get();
    
    if ($users->isEmpty()) {
        return 'No regular users found.';
    }
    
    foreach ($users as $user) {
        $user->notify(new UserNotification(
            'Important Announcement',
            'We have exciting new products available in our shop. Check them out now!',
            url('/shop'),
            'info'
        ));
    }
    
    return 'Test notifications sent to ' . $users->count() . ' user(s).';
})->middleware('auth:admin');

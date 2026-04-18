<?php
/**
 * Temporary script to migrate admin session from web guard to admin guard
 * Access this file once, then delete it
 */

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->handle($request);

use Illuminate\Support\Facades\Auth;

// Check if user is logged in on web guard
if (Auth::guard('web')->check()) {
    $user = Auth::guard('web')->user();
    
    // Check if user is admin
    if ($user->role === 'admin') {
        // Log in to admin guard
        Auth::guard('admin')->login($user, true);
        
        echo "✅ Success! Your session has been migrated to the admin guard.<br>";
        echo "User: {$user->name} ({$user->email})<br>";
        echo "Role: {$user->role}<br><br>";
        echo "You can now access admin routes.<br>";
        echo "<a href='/admin/dashboard'>Go to Admin Dashboard</a><br><br>";
        echo "<strong>IMPORTANT: Delete this file (public/migrate-admin-session.php) after use!</strong>";
    } else {
        echo "❌ Error: You are not an admin user.<br>";
        echo "Current role: {$user->role}<br>";
        echo "Only users with role 'admin' can access the admin panel.";
    }
} else {
    echo "❌ Error: You are not logged in.<br>";
    echo "Please <a href='/login'>login first</a>, then run this script again.";
}

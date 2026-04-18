<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Checking Admin Users ===\n";
$adminUsers = User::where('role', 'admin')->get();

if ($adminUsers->count() == 0) {
    echo "No admin users found in database!\n";
    
    echo "\n=== All Users ===\n";
    $allUsers = User::all(['id', 'name', 'email', 'role', 'created_at']);
    foreach ($allUsers as $user) {
        echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
    }
} else {
    echo "Found {$adminUsers->count()} admin user(s):\n";
    foreach ($adminUsers as $admin) {
        echo "ID: {$admin->id}, Name: {$admin->name}, Email: {$admin->email}, Role: {$admin->role}\n";
    }
}

<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Test login attempt
$credentials = [
    'email' => 'admin@ceylonmoms.com',
    'password' => 'password', // Try common default
];

echo "=== Testing Admin Login ===\n";
echo "Attempting login with: admin@ceylonmoms.com / password\n";

if (Auth::attempt($credentials)) {
    echo "✓ Login successful!\n";
    echo "User ID: " . Auth::id() . "\n";
    echo "User Email: " . Auth::user()->email . "\n";
    echo "User Role: " . Auth::user()->role . "\n";
    echo "Session ID: " . session()->getId() . "\n";
} else {
    echo "✗ Login failed with password 'password'\n";
    
    // Try other common passwords
    $passwords = ['admin', '123456', '12345678', 'admin123'];
    foreach ($passwords as $pwd) {
        $credentials['password'] = $pwd;
        echo "Trying password: $pwd\n";
        if (Auth::attempt($credentials)) {
            echo "✓ Login successful with password: $pwd\n";
            break;
        }
    }
}

<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test current authentication status
use Illuminate\Support\Facades\Auth;

echo "=== Authentication Status ===\n";
echo "Default guard auth check: " . (Auth::check() ? 'TRUE' : 'FALSE') . "\n";
echo "Admin guard auth check: " . (Auth::guard('admin')->check() ? 'TRUE' : 'FALSE') . "\n";

if (Auth::check()) {
    echo "Current user ID: " . Auth::id() . "\n";
    echo "Current user email: " . Auth::user()->email . "\n";
    echo "Current user role: " . (Auth::user()->role ?? 'no_role') . "\n";
}

echo "\n=== Session Data ===\n";
echo "Session ID: " . session()->getId() . "\n";
echo "Session has user: " . (session()->has('user') ? 'TRUE' : 'FALSE') . "\n";

// Test middleware
echo "\n=== Testing AdminMiddleware ===\n";
$middleware = new App\Http\Middleware\AdminMiddleware();
$request = Illuminate\Http\Request::create('/products');
$response = $middleware->handle($request, function() {
    return "Middleware passed";
});
echo "Middleware result: " . $response . "\n";

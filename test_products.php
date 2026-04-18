<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test if we can access the products view without auth middleware
try {
    $controller = new App\Http\Controllers\Admin\ProductController();
    $response = $controller->index();
    echo "Controller method executed successfully\n";
    echo "Response type: " . get_class($response) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

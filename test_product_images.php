<?php

// Test script to verify product image functionality
require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\ProductImage;

echo "=== Product Image Management Test ===\n\n";

// Test 1: Check if Product model has the required relationships
echo "1. Testing Product model relationships...\n";
$product = new Product();
if (method_exists($product, 'images')) {
    echo "   ✓ Product has images() relationship\n";
} else {
    echo "   ✗ Product missing images() relationship\n";
}

if (method_exists($product, 'primaryImage')) {
    echo "   ✓ Product has primaryImage() relationship\n";
} else {
    echo "   ✗ Product missing primaryImage() relationship\n";
}

if (method_exists($product, 'getAllImagesAttribute')) {
    echo "   ✓ Product has getAllImagesAttribute accessor\n";
} else {
    echo "   ✗ Product missing getAllImagesAttribute accessor\n";
}

// Test 2: Check if ProductImage model exists and has required methods
echo "\n2. Testing ProductImage model...\n";
if (class_exists('App\Models\ProductImage')) {
    echo "   ✓ ProductImage class exists\n";
    
    $productImage = new ProductImage();
    if (method_exists($productImage, 'product')) {
        echo "   ✓ ProductImage has product() relationship\n";
    } else {
        echo "   ✗ ProductImage missing product() relationship\n";
    }
    
    if (method_exists($productImage, 'getImageUrlAttribute')) {
        echo "   ✓ ProductImage has getImageUrlAttribute accessor\n";
    } else {
        echo "   ✗ ProductImage missing getImageUrlAttribute accessor\n";
    }
} else {
    echo "   ✗ ProductImage class does not exist\n";
}

// Test 3: Check if the product-images directory exists
echo "\n3. Testing file system...\n";
$productImagesDir = public_path('product-images');
if (is_dir($productImagesDir)) {
    echo "   ✓ product-images directory exists\n";
    if (is_writable($productImagesDir)) {
        echo "   ✓ product-images directory is writable\n";
    } else {
        echo "   ✗ product-images directory is not writable\n";
    }
} else {
    echo "   ✗ product-images directory does not exist\n";
}

// Test 4: Check if routes exist
echo "\n4. Testing routes (check web.php)...\n";
$webRoutes = file_get_contents('routes/web.php');
if (strpos($webRoutes, 'products/images/{image}') !== false) {
    echo "   ✓ DELETE route for product images exists\n";
} else {
    echo "   ✗ DELETE route for product images missing\n";
}

if (strpos($webRoutes, 'ProductController') !== false) {
    echo "   ✓ ProductController is referenced in routes\n";
} else {
    echo "   ✗ ProductController not found in routes\n";
}

echo "\n=== Test Complete ===\n";
echo "If all tests pass, the image management functionality should work correctly.\n";
echo "To test manually:\n";
echo "1. Go to admin product edit page\n";
echo "2. Try clicking 'Add More Images' button\n";
echo "3. Upload additional images\n";
echo "4. Try removing existing images\n";
echo "5. Submit the form and verify images are saved\n";

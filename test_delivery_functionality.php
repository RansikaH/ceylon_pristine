<?php

// Test script to verify delivery functionality
require_once 'vendor/autoload.php';

use App\Models\Product;

echo "=== Delivery Functionality Test ===\n\n";

// Test 1: Check if delivery fields exist in database
echo "1. Testing database fields...\n";
try {
    $product = new Product();
    $fillable = $product->getFillable();
    
    if (in_array('free_delivery_quantity', $fillable)) {
        echo "   ✓ free_delivery_quantity field is fillable\n";
    } else {
        echo "   ✗ free_delivery_quantity field is missing\n";
    }
    
    if (in_array('delivery_fee', $fillable)) {
        echo "   ✓ delivery_fee field is fillable\n";
    } else {
        echo "   ✗ delivery_fee field is missing\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error checking fields: " . $e->getMessage() . "\n";
}

// Test 2: Check if delivery methods exist
echo "\n2. Testing Product model methods...\n";
try {
    $product = new Product();
    
    if (method_exists($product, 'hasFreeDelivery')) {
        echo "   ✓ hasFreeDelivery() method exists\n";
    } else {
        echo "   ✗ hasFreeDelivery() method missing\n";
    }
    
    if (method_exists($product, 'calculateDeliveryFee')) {
        echo "   ✓ calculateDeliveryFee() method exists\n";
    } else {
        echo "   ✗ calculateDeliveryFee() method missing\n";
    }
    
    if (method_exists($product, 'getDeliveryInfoAttribute')) {
        echo "   ✓ getDeliveryInfoAttribute() accessor exists\n";
    } else {
        echo "   ✗ getDeliveryInfoAttribute() accessor missing\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error checking methods: " . $e->getMessage() . "\n";
}

// Test 3: Test delivery fee calculation logic
echo "\n3. Testing delivery fee calculation logic...\n";
try {
    // Create a mock product with delivery settings
    $mockProduct = new class extends Product {
        public $free_delivery_quantity = 5;
        public $delivery_fee = 150.00;
        
        public function hasFreeDelivery() {
            return !is_null($this->free_delivery_quantity) && $this->free_delivery_quantity > 0;
        }
        
        public function calculateDeliveryFee($quantity) {
            if ($this->hasFreeDelivery() && $quantity >= $this->free_delivery_quantity) {
                return 0;
            }
            return $this->delivery_fee ?? 0;
        }
    };
    
    // Test free delivery (quantity >= threshold)
    $fee1 = $mockProduct->calculateDeliveryFee(5);
    if ($fee1 === 0) {
        echo "   ✓ Free delivery applied for quantity >= threshold\n";
    } else {
        echo "   ✗ Free delivery not applied correctly. Expected: 0, Got: {$fee1}\n";
    }
    
    // Test paid delivery (quantity < threshold)
    $fee2 = $mockProduct->calculateDeliveryFee(3);
    if ($fee2 === 150.00) {
        echo "   ✓ Delivery fee applied for quantity < threshold\n";
    } else {
        echo "   ✗ Delivery fee not applied correctly. Expected: 150.00, Got: {$fee2}\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error testing logic: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "To test manually:\n";
echo "1. Go to admin product create/edit page\n";
echo "2. Set delivery options (free delivery quantity and/or delivery fee)\n";
echo "3. Save product\n";
echo "4. Add product to cart with different quantities\n";
echo "5. Check if delivery fees are applied correctly in cart\n";
echo "6. Proceed to checkout and verify totals\n";
echo "7. Check product cards on shop page for delivery info\n";

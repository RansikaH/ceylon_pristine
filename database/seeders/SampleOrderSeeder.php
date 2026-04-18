<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class SampleOrderSeeder extends Seeder
{
    public function run()
    {
        // Get existing users and products
        $users = User::where('role', 'user')->get();
        $products = Product::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please create users first.');
            return;
        }
        
        if ($products->isEmpty()) {
            $this->command->info('No products found. Please create products first.');
            return;
        }
        
        // Generate sample orders for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $ordersPerMonth = rand(30, 60);
            
            for ($j = 0; $j < $ordersPerMonth; $j++) {
                $orderDate = Carbon::now()
                    ->subMonths($i)
                    ->startOfMonth()
                    ->addDays(rand(0, 29))
                    ->addHours(rand(0, 23))
                    ->addMinutes(rand(0, 59));
                
                $user = $users->random();
                $orderItems = [];
                $total = 0;
                
                // Add 1-4 random products to each order
                $numItems = rand(1, 4);
                for ($k = 0; $k < $numItems; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                    
                    $orderItems[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ];
                }
                
                // Random order status
                $statuses = ['pending', 'processing', 'completed', 'completed', 'completed']; // More completed orders
                $status = $statuses[array_rand($statuses)];
                
                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => $total,
                    'status' => $status,
                    'payment_method' => ['cash_on_delivery', 'credit_card'][array_rand(['cash_on_delivery', 'credit_card'])],
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? '+9477' . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                    'customer_address' => $user->address ?? 'Sample Address, Colombo, Sri Lanka',
                    'items' => json_encode($orderItems),
                    'status_note' => null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate->copy()->addHours(rand(1, 72))
                ]);
                
                $this->command->info("Created order #{$order->id} for {$orderDate->format('M Y')} - Total: LKR {$total}");
            }
        }
        
        $this->command->info('Sample orders created successfully!');
    }
}

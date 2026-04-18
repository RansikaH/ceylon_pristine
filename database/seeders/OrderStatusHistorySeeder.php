<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderStatusHistory;

class OrderStatusHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing orders
        $orders = Order::all();
        
        foreach ($orders as $order) {
            // Check if history already exists for this order
            $existingHistory = OrderStatusHistory::where('order_id', $order->id)->first();
            
            if (!$existingHistory) {
                // Create initial status history record
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'old_status' => null, // Initial order creation
                    'new_status' => $order->status,
                    'changed_by' => $order->user_id, // The customer who placed the order
                    'notes' => 'Order placed and initial status set',
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at
                ]);
            }
        }
        
        $this->command->info('Order status history seeded successfully!');
    }
}

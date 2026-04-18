<?php

// Simple script to check existing order data
require_once __DIR__ . '/bootstrap/app.php';
$app = make(Illuminate\Contracts\Console\Kernel::class);
$app->bootstrap();

use App\Models\Order;
use Carbon\Carbon;

echo "=== Checking Order Data for Revenue Chart ===\n";

// Check total orders
$totalOrders = Order::count();
echo "Total orders in database: $totalOrders\n";

if ($totalOrders > 0) {
    echo "\n=== Order Status Breakdown ===\n";
    $statuses = Order::selectRaw('status, COUNT(*) as count, SUM(total) as total_revenue')
        ->groupBy('status')
        ->get();
    
    foreach ($statuses as $status) {
        echo "Status: {$status->status} - Count: {$status->count} - Revenue: LKR " . number_format($status->total_revenue) . "\n";
    }
    
    echo "\n=== Monthly Revenue (Last 6 Months) ===\n";
    for ($i = 5; $i >= 0; $i--) {
        $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
        $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
        
        $monthRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $monthStart)
            ->where('created_at', '<=', $monthEnd)
            ->sum('total');
        
        echo $monthStart->format('M Y') . ": LKR " . number_format($monthRevenue) . "\n";
    }
    
    echo "\n=== Recent Orders ===\n";
    $recentOrders = Order::latest()->take(5)->get(['id', 'total', 'status', 'created_at']);
    foreach ($recentOrders as $order) {
        echo "Order #{$order->id}: LKR {$order->total} ({$order->status}) on {$order->created_at->format('Y-m-d H:i')}\n";
    }
} else {
    echo "No orders found. The chart will use sample data.\n";
}

unlink(__FILE__);
echo "\nDone!\n";

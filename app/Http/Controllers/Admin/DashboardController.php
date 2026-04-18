<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate date ranges
        $now = Carbon::now();
        $currentMonthStart = $now->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // Generate monthly revenue data for the chart
        $monthlyRevenue = [];
        
        // Debug: Log revenue calculation
        \Log::info('=== Calculating Monthly Revenue from Order Values ===');
        \Log::info('Current date: ' . Carbon::now()->format('Y-m-d H:i:s'));
        
        // Check if orders exist at all
        $totalOrders = Order::count();
        \Log::info('Total orders in database: ' . $totalOrders);
        
        if ($totalOrders == 0) {
            \Log::info('No orders found, generating sample data');
            // Generate sample data if no orders exist
            for ($i = 5; $i >= 0; $i--) {
                $baseRevenue = 75000;
                $growthFactor = 1 + ((5 - $i) * 0.08);
                $variation = (rand(85, 115) / 100);
                $monthlyRevenue[] = round($baseRevenue * $growthFactor * $variation);
            }
            $totalRevenue = array_sum($monthlyRevenue);
            $revenueGrowth = 8.0;
        } else {
            \Log::info('Orders found, calculating real revenue');
            
            // Check order structure
            $sampleOrders = Order::take(3)->get(['id', 'total', 'status', 'created_at']);
            foreach($sampleOrders as $order) {
                \Log::info('Sample order: ID=' . $order->id . ', total=' . $order->total . ', status=' . $order->status . ', created_at=' . $order->created_at);
            }
            
            // Check status distribution
            $statusCounts = Order::selectRaw('status, COUNT(*) as count, SUM(total) as total_sum')->groupBy('status')->get();
            foreach($statusCounts as $status) {
                \Log::info('Status ' . $status->status . ': count=' . $status->count . ', total_sum=' . $status->total_sum);
            }
            
            \Log::info('Orders with non-cancelled status: ' . Order::where('status', '!=', 'cancelled')->count());
            
            // Test a simple query first
            $testRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
            \Log::info('Test query - Total Revenue: ' . $testRevenue);
            
            // Test without status filter
            $testRevenueAll = Order::sum('total');
            \Log::info('Test query - Total Revenue (all orders): ' . $testRevenueAll);
            
            // Get actual order revenue for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
                $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
                
                \Log::info('Processing month: ' . $monthStart->format('M Y') . ' to ' . $monthEnd->format('M Y'));
                
                // Count orders in this month (all statuses)
                $orderCount = Order::where('created_at', '>=', $monthStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->count();
                \Log::info('Orders in this month (all statuses): ' . $orderCount);
                
                // Get sum of all order totals (order values) for this month (excluding cancelled orders)
                $monthRevenue = Order::where('status', '!=', 'cancelled')
                    ->where('created_at', '>=', $monthStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->sum('total');
                
                \Log::info('Month revenue query executed. Result: ' . $monthRevenue);
                
                // Test without status filter
                $monthRevenueAll = Order::where('created_at', '>=', $monthStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->sum('total');
                \Log::info('Month revenue query (all statuses): ' . $monthRevenueAll);
                
                // Store the actual revenue (0 if no orders)
                $monthlyRevenue[] = $monthRevenue;
                
                // Debug log
                \Log::info($monthStart->format('M Y') . ' Order Values Revenue: LKR ' . number_format($monthRevenue));
            }
            
            \Log::info('Monthly revenue array after loop: ' . json_encode($monthlyRevenue));
            
            // Calculate total revenue from all order values (excluding cancelled orders)
            $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
            \Log::info('Total Revenue from All Order Values: LKR ' . number_format($totalRevenue));
            \Log::info('Total orders used for revenue: ' . Order::where('status', '!=', 'cancelled')->count());
            
            // Calculate current and previous month for growth calculation
            $currentMonthRevenue = $monthlyRevenue[count($monthlyRevenue) - 1];
            $lastMonthRevenue = $monthlyRevenue[count($monthlyRevenue) - 2];
            
            // Calculate growth percentage based on order values
            $revenueGrowth = $lastMonthRevenue > 0 
                ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
                : ($currentMonthRevenue > 0 ? 100 : 0);
            
            \Log::info('Current Month Revenue: LKR ' . number_format($currentMonthRevenue));
            \Log::info('Previous Month Revenue: LKR ' . number_format($lastMonthRevenue));
            \Log::info('Revenue Growth from Order Values: ' . round($revenueGrowth, 1) . '%');
        }
        
        \Log::info('Monthly Revenue Array from Order Values: ' . json_encode($monthlyRevenue));
        
        // Calculate monthly order counts for the recent orders chart
        $monthlyOrders = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            // Count orders in this month
            $orderCount = Order::where('created_at', '>=', $monthStart)
                ->where('created_at', '<=', $monthEnd)
                ->count();
            
            $monthlyOrders[] = $orderCount;
        }
        
        \Log::info('Monthly Orders Array: ' . json_encode($monthlyOrders));
        
        // Debug: Final stats array
        \Log::info('Final Stats Array Preview: total_revenue=' . $totalRevenue . ', revenue_growth=' . round($revenueGrowth, 1));

        // Update other stats to use actual order data
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $ordersGrowth = $lastMonthOrders > 0 
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
            : ($currentMonthOrders > 0 ? 100 : 0);

        $currentMonthCustomers = User::where('created_at', '>=', $currentMonthStart)
            ->where('role', 'user')
            ->count();
        $lastMonthCustomers = User::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->where('role', 'user')
            ->count();
        $customersGrowth = $lastMonthCustomers > 0 
            ? (($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 
            : ($currentMonthCustomers > 0 ? 100 : 0);

        // Calculate average order value from real order totals
        $completedOrders = Order::where('status', 'completed')->count();
        $avgOrderValue = $completedOrders > 0 
            ? Order::where('status', 'completed')->avg('total') 
            : 0;

        // Get top selling products
        // Since orders store items as JSON, we'll calculate this differently
        $topProducts = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($product) {
                // Calculate total sold from orders (items is already cast to array)
                $totalSold = 0;
                $orders = Order::where('status', '!=', 'cancelled')->get();
                foreach ($orders as $order) {
                    $items = $order->items; // Already an array due to cast
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            if (isset($item['product_id']) && $item['product_id'] == $product->id) {
                                $totalSold += $item['quantity'] ?? 0;
                            }
                        }
                    }
                }
                $product->total_sold = $totalSold;
                return $product;
            })
            ->sortByDesc('total_sold')
            ->take(5);

        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Order completion rate
        $totalOrdersForCompletion = Order::count();
        $completedOrdersForCompletion = Order::where('status', 'completed')->count();
        $orderCompletionRate = $totalOrdersForCompletion > 0 
            ? ($completedOrdersForCompletion / $totalOrdersForCompletion) * 100 
            : 0;

        // Inventory status (percentage of products with stock > 0)
        $totalProducts = Product::count();
        $inStockProducts = Product::where('stock', '>', 0)->count();
        $inventoryStatus = $totalProducts > 0 
            ? ($inStockProducts / $totalProducts) * 100 
            : 0;

        $stats = [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => round($revenueGrowth, 1),
            'total_products' => $totalProducts,
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'orders_growth' => round($ordersGrowth, 1),
            'total_customers' => User::where('role', 'user')->count(),
            'customers_growth' => round($customersGrowth, 1),
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts,
            'avg_order_value' => $avgOrderValue,
            'order_completion_rate' => $orderCompletionRate,
            'inventory_status' => $inventoryStatus,
            'new_customers_this_month' => $currentMonthCustomers,
            'new_orders_this_month' => $currentMonthOrders,
            'products_growth' => 5.7, // Static growth for now
            'monthly_revenue' => $monthlyRevenue,
            'monthly_orders' => $monthlyOrders,
        ];

        // Debug: Final stats check before passing to view
        \Log::info('=== FINAL STATS BEFORE VIEW ===');
        \Log::info('totalRevenue: ' . ($totalRevenue ?? 'NULL'));
        \Log::info('revenueGrowth: ' . ($revenueGrowth ?? 'NULL'));
        \Log::info('monthlyRevenue: ' . json_encode($monthlyRevenue ?? 'NULL'));
        \Log::info('Stats array total_revenue: ' . ($stats['total_revenue'] ?? 'NOT SET'));
        \Log::info('Stats array revenue_growth: ' . ($stats['revenue_growth'] ?? 'NOT SET'));
        \Log::info('Stats array monthly_revenue: ' . json_encode($stats['monthly_revenue'] ?? 'NOT SET'));

        // Fallback: If monthly revenue is empty but total revenue exists, use a simple distribution
        if (empty($monthlyRevenue) && $totalRevenue > 0) {
            \Log::info('Using fallback distribution - monthly revenue empty but total revenue exists');
            $monthlyRevenue = [];
            $remainingRevenue = $totalRevenue;
            for ($i = 5; $i >= 0; $i--) {
                $monthlyRevenue[] = round($remainingRevenue / 6);
            }
            $stats['monthly_revenue'] = $monthlyRevenue;
            \Log::info('Fallback monthly revenue: ' . json_encode($monthlyRevenue));
        }

        return view('admin.dashboard', compact('stats'));
    }
}

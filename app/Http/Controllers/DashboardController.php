<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin dashboard data
            $stats = [
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'total_orders' => Order::count(),
                'total_customers' => User::where('role', 'user')->count(),
                'recent_orders' => Order::with('user')
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
            
            return view('admin.dashboard', compact('stats'));
        }
        
        // Regular user dashboard
        $orders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        
        // Get unread notifications for the user
        $unreadNotifications = $user->unreadNotifications()->latest()->get();
            
        return view('dashboard', compact('user', 'orders', 'unreadNotifications'));
    }
}

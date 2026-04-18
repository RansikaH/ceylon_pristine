<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Customer: order history
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    // Customer: order detail
    public function show(Order $order)
    {
        $this->authorize('view', $order); // Only allow owner
        $order->load(['statusHistory' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        return view('orders.show', compact('order'));
    }

    // Admin: all orders
    public function adminIndex(Request $request)
    {
        $query = Order::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                  ->orWhere('customer_email', 'like', "%$search%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    // Admin: order detail
    public function adminShow(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    // Admin: update order status
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string|max:50']);
        $order->status = $request->status;
        $order->save();
        return redirect()->back()->with('success', 'Order status updated.');
    }
}

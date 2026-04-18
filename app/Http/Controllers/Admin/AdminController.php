<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function products()
    {
        $products = \App\Models\Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    
    public function createProduct()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }
    
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
        
        \App\Models\Product::create($validated);
        
        return redirect()->route('products')
            ->with('success', 'Product created successfully!');
    }

    public function orders()
    {
        return view('admin.orders.index');
    }

    public function customers()
    {
        $query = \App\Models\User::where('role', 'user');
        
        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Apply status filter
        if (request('status')) {
            if (request('status') == 'active') {
                $query->whereHas('orders');
            } elseif (request('status') == 'inactive') {
                $query->whereDoesntHave('orders');
            }
        }
        
        // Apply date range filter
        if (request('date_range')) {
            switch (request('date_range')) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'quarter':
                    $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        $customers = $query->latest()->paginate(15);
        
        return view('admin.customers.index', compact('customers'));
    }
    
    public function showCustomer(\App\Models\User $user)
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('admin.customers.show', compact('user'));
    }

    public function editProduct($product)
    {
        $product = \App\Models\Product::findOrFail($product);
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    
    public function updateProduct(Request $request, $product)
    {
        $product = \App\Models\Product::findOrFail($product);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
        
        $product->update($validated);
        
        return redirect()->route('products')
            ->with('success', 'Product updated successfully!');
    }
    
    public function deleteProduct($product)
    {
        $product = \App\Models\Product::findOrFail($product);
        
        // Delete image if exists
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products')
            ->with('success', 'Product deleted successfully!');
    }

    public function sendMessage(Request $request, \App\Models\User $user)
    {
        \Log::info('Send message request received', [
            'user_id' => $user->id,
            'request_data' => $request->all()
        ]);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|string|in:info,success,warning,error',
            'url' => 'nullable|url'
        ]);
        
        \Log::info('Validation passed', ['validated' => $validated]);
        
        try {
            // Send notification to the user
            $user->notify(new \App\Notifications\UserNotification(
                $validated['title'],
                $validated['message'],
                $validated['url'] ?? url('/dashboard'),
                $validated['type']
            ));
            
            \Log::info('Notification sent successfully', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully to ' . $user->name
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification to customer', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reports()
    {
        return view('admin.reports.index');
    }
}

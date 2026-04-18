<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('admin.discounts.index', compact('products', 'categories'));
    }

    public function updateBulk(Request $request)
    {
        $validated = $request->validate([
            'discounts' => 'required|array',
            'discounts.*' => 'nullable|numeric|min:0|max:100'
        ]);

        foreach ($validated['discounts'] as $productId => $discount) {
            Product::where('id', $productId)->update([
                'discount_percentage' => $discount ?? 0
            ]);
        }

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discounts updated successfully!');
    }
}

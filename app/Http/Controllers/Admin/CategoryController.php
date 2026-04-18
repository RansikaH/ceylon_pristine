<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
        
        $categories = $query->latest()->paginate(10);
        
        // Calculate stats
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'active_categories' => Category::active()->count(),
        ];
        
        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' created successfully!");
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Load category with product count
        $category->loadCount('products');
        
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' updated successfully!");
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $productName = $category->name;
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Cannot delete category '{$productName}' because it has associated products. Please reassign or delete the products first.");
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$productName}' deleted successfully!");
    }
}

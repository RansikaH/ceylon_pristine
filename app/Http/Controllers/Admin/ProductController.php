<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::all();
        
        // Calculate product statistics
        $stats = [
            'in_stock' => Product::where('stock', '>', 10)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
        ];
        
        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'unit_value' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        
        $product = Product::create($validated);

        // Log product creation
        ActivityLogger::productCreated($product);

        // Handle multiple images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = ['image_path' => $image->store('products/gallery', 'public')];
            }
            $product->images()->createMany($images);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'unit_value' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        
        // Track changes for logging
        $changes = [];
        foreach ($validated as $key => $value) {
            if ($product->$key != $value) {
                $changes[$key] = [
                    'old' => $product->$key,
                    'new' => $value
                ];
            }
        }
        
        $product->update($validated);

        // Log product update if there were changes
        if (!empty($changes)) {
            ActivityLogger::productUpdated($product, $changes);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete gallery images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->images()->delete();
        
        $product->delete();

        // Log product deletion
        ActivityLogger::productDeleted($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}

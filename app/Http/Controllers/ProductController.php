<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Customer-facing: product listing
    public function shopIndex(Request $request)
    {
        $categoryId = $request->query('category');
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::with(['category', 'images', 'primaryImage'])
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('id', 'desc')
            ->take(4)->get();
        $totalProducts = \App\Models\Product::when($categoryId, fn($q) => $q->where('category_id', $categoryId))->count();
        $showViewMore = $totalProducts > 4;

        // Get active sliders for homepage
        $sliders = \App\Models\Slider::active()->ordered()->get();

        return view('shop.index', compact('products', 'categories', 'categoryId', 'showViewMore', 'sliders'));
    }

    // Customer-facing: product detail
    public function shopShow(\App\Models\Product $product)
    {
        // Load product with images for gallery
        $product->load(['images', 'primaryImage', 'category']);
        return view('shop.show', compact('product'));
    }

    // Customer-facing: full product list
    public function shopFull(Request $request)
    {
        $categoryId = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::with(['category', 'images', 'primaryImage'])
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($minPrice !== null && $minPrice !== '', fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice !== null && $maxPrice !== '', fn($q) => $q->where('price', '<=', $maxPrice))
            ->orderBy('id', 'desc')
            ->paginate(12);
        return view('shop.full', compact('products', 'categories', 'categoryId'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::with('category')->orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        \App\Models\Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}

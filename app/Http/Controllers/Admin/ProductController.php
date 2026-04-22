<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
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
            'main_image' => 'required|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'main_image.required' => 'The main product image is required.',
            'main_image.image' => 'The main image must be a valid image file (jpeg, jpg, png, gif, webp).',
            'main_image.max' => 'The main image may not be greater than 2MB.',
            'additional_images.*.image' => 'Additional images must be valid image files (jpeg, jpg, png, gif, webp).',
            'additional_images.*.max' => 'Additional images may not be greater than 2MB each.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Debug: Check if file is uploaded
        if (!$request->hasFile('main_image')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['main_image' => 'No file was uploaded. Please select an image file.']);
        }

        $product = Product::create($validated);

        // Handle main image (store as primary image)
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $filename = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();

            // Ensure directory exists
            if (!is_dir(public_path('product-images'))) {
                mkdir(public_path('product-images'), 0755, true);
            }

            // Move file to public directory
            $mainImage->move(public_path('product-images'), $filename);

            // Store in product_images table as primary
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'sort_order' => 0,
                'is_primary' => true
            ]);

            // Also update legacy image field for compatibility
            $product->update(['image' => $filename]);
        }

        // Handle additional images
        if ($request->hasFile('additional_images')) {
            $sortOrder = 1;
            foreach ($request->file('additional_images') as $index => $image) {
                $filename = time() . '_' . uniqid() . '_' . $index . '.' . $image->getClientOriginalExtension();

                // Ensure directory exists
                if (!is_dir(public_path('product-images'))) {
                    mkdir(public_path('product-images'), 0755, true);
                }

                // Move file to public directory
                $image->move(public_path('product-images'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'sort_order' => $sortOrder++,
                    'is_primary' => false
                ]);
            }
        }

        // Log product creation
        ActivityLogger::productCreated($product);

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
            'main_image' => 'nullable|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'main_image.image' => 'The main image must be a valid image file (jpeg, jpg, png, gif, webp).',
            'main_image.max' => 'The main image may not be greater than 2MB.',
            'additional_images.*.image' => 'Additional images must be valid image files (jpeg, jpg, png, gif, webp).',
            'additional_images.*.max' => 'Additional images may not be greater than 2MB each.',
        ]);

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

        // Handle main image update
        if ($request->hasFile('main_image')) {
            // Delete old primary image if exists
            $oldPrimaryImage = $product->primaryImage;
            if ($oldPrimaryImage) {
                $oldImagePath = public_path('product-images/' . $oldPrimaryImage->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $oldPrimaryImage->delete();
            }

            // Upload new main image
            $mainImage = $request->file('main_image');
            $filename = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();

            // Ensure directory exists
            if (!is_dir(public_path('product-images'))) {
                mkdir(public_path('product-images'), 0755, true);
            }

            // Move file to public directory
            $mainImage->move(public_path('product-images'), $filename);

            // Store as primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'sort_order' => 0,
                'is_primary' => true
            ]);

            // Also update legacy image field for compatibility
            $product->update(['image' => $filename]);
        }

        // Handle additional images
        if ($request->hasFile('additional_images')) {
            $sortOrder = $product->all_images->count() + 1;
            foreach ($request->file('additional_images') as $index => $image) {
                $filename = time() . '_' . uniqid() . '_' . $index . '.' . $image->getClientOriginalExtension();

                // Ensure directory exists
                if (!is_dir(public_path('product-images'))) {
                    mkdir(public_path('product-images'), 0755, true);
                }

                // Move file to public directory
                $image->move(public_path('product-images'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'sort_order' => $sortOrder++,
                    'is_primary' => false
                ]);
            }
        }

        // Log product update
        ActivityLogger::productUpdated($product, $changes);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a specific product image.
     */
    public function deleteImage(ProductImage $image)
    {
        // Delete the image file
        $imagePath = public_path('product-images/' . $image->image_path);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the database record
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product images
        foreach ($product->images as $image) {
            $imagePath = public_path('product-images/' . $image->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image->delete();
        }

        $product->delete();

        // Log product deletion
        ActivityLogger::productDeleted($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}

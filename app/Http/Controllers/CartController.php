<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        // Refresh delivery information and image for all cart items
        foreach ($cart as $productId => &$item) {
            $product = Product::find($productId);
            if ($product) {
                // Update delivery information with latest product data
                $item['delivery_info'] = $product->delivery_info;
                $item['delivery_fee'] = $product->calculateDeliveryFee($item['quantity']);
                // Update main image to reflect any changes
                $item['image'] = $product->main_image;
            }
        }

        // Update session with refreshed cart data
        session(['cart' => $cart]);

        return view('shop.cart', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.1'
        ]);

        $cart = session()->get('cart', []);
        $qty = (float) $request->input('quantity', 1);
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'discounted_price' => $product->discounted_price,
            'discount_percentage' => $product->discount_percentage,
            'image' => $product->main_image,
            'quantity' => ($cart[$product->id]['quantity'] ?? 0) + $qty,
            'delivery_info' => $product->delivery_info,
            'delivery_fee' => $product->calculateDeliveryFee(($cart[$product->id]['quantity'] ?? 0) + $qty),
        ];
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Added to cart!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.1'
        ]);

        $cart = session()->get('cart', []);
        $qty = (float) $request->input('quantity', 1);

        // Debug logging
        \Log::info('Cart update request', [
            'product_id' => $product->id,
            'requested_quantity' => $request->input('quantity'),
            'converted_quantity' => $qty,
            'cart_before' => $cart
        ]);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $qty;
            $cart[$product->id]['delivery_fee'] = $product->calculateDeliveryFee($qty);
            session(['cart' => $cart]);

            \Log::info('Cart updated successfully', [
                'product_id' => $product->id,
                'new_quantity' => $qty,
                'cart_after' => $cart
            ]);

            return redirect()->route('cart.index')->with('success', 'Cart updated!');
        }

        \Log::warning('Product not found in cart', [
            'product_id' => $product->id,
            'cart_contents' => array_keys($cart)
        ]);

        return redirect()->route('cart.index')->with('error', 'Product not found in cart!');
    }

    public function remove(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Removed from cart!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('shop.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checkout.');
        }
        
        $validationRules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
        ];
        
        // Get payment method without strict validation for now
        $payment_method = $request->input('payment_method', 'cod');
        
        // Check if using saved address
        $useSavedAddress = $request->has('use_saved_address');
        
        // Only require address fields if not using saved address
        if (!$useSavedAddress) {
            $validationRules = array_merge($validationRules, [
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'postal_code' => 'nullable|string|max:20|regex:/^[0-9]+$/',
            ]);
        }
        
        $data = $request->validate($validationRules, [
            'customer_name.required' => 'Your name is required.',
            'customer_email.required' => 'Your email address is required.',
            'customer_email.email' => 'Please provide a valid email address.',
            'address_line_1.required' => 'Address line 1 is required.',
            'city.required' => 'Please select a city.',
            'district.required' => 'Please select a district.',
            'postal_code.regex' => 'Postal code should contain only numbers.',
            'customer_phone.regex' => 'Phone number format is invalid.',
        ]);
        
        // If using saved address, get data from user profile
        if ($useSavedAddress && auth()->check()) {
            $user = auth()->user();
            \Log::info('Using saved address for user: ' . $user->id);
            \Log::info('User address data: ' . json_encode([
                'address_line_1' => $user->address_line_1,
                'city' => $user->city,
                'district' => $user->district
            ]));
            
            $data['address_line_1'] = $user->address_line_1 ?? '';
            $data['address_line_2'] = $user->address_line_2 ?? '';
            $data['city'] = $user->city ?? '';
            $data['district'] = $user->district ?? '';
            $data['postal_code'] = $user->postal_code ?? '';
            $data['customer_name'] = $user->name ?? '';
            $data['customer_email'] = $user->email ?? '';
            $data['customer_phone'] = $user->phone ?? '';
        }
        
        // Add payment method to data
        $data['payment_method'] = $payment_method;
        
        // Calculate total using discounted prices
        $total = collect($cart)->sum(function($item) {
            $price = $item['discounted_price'] ?? $item['price'];
            return $price * $item['quantity'];
        });
        
        // Prepare address data for order
        $addressData = [
            'address_line_1' => $data['address_line_1'] ?? '',
            'address_line_2' => $data['address_line_2'] ?? '',
            'city' => $data['city'] ?? '',
            'district' => $data['district'] ?? '',
            'postal_code' => $data['postal_code'] ?? '',
        ];
        
        $orderData = [
            'user_id' => auth()->check() ? auth()->id() : null,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'] ?? '',
            'customer_address' => trim(($data['address_line_1'] ?? '') . ', ' . 
                                   ($data['address_line_2'] ?? '') . ', ' . 
                                   ($data['city'] ?? '') . ', ' . 
                                   ($data['district'] ?? '') . ' ' . 
                                   ($data['postal_code'] ?? ''), ', '),
            'address_line_1' => $addressData['address_line_1'],
            'address_line_2' => $addressData['address_line_2'],
            'city' => $addressData['city'],
            'district' => $addressData['district'],
            'postal_code' => $addressData['postal_code'],
            'payment_method' => $data['payment_method'],
            'total' => $total,
            'items' => array_values($cart),
            'status' => 'pending',
        ];
        
        // Log the incoming data for debugging
        \Log::info('Checkout request data: ', $request->all());
        \Log::info('Validated data: ', $data);
        \Log::info('Order data to be created: ', $orderData);
        
        $order = \App\Models\Order::create($orderData);
        
        // Log order creation as activity
        ActivityLogger::orderCreated($order);
        
        // Log successful order creation
        \Log::info('Order created successfully: #' . $order->id);
        
        // Save address to user profile if requested and user is authenticated
        if (auth()->check() && $request->has('save_address') && !$request->has('use_saved_address')) {
            auth()->user()->update([
                'phone' => $data['customer_phone'],
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'],
                'city' => $data['city'],
                'district' => $data['district'],
                'postal_code' => $data['postal_code'],
            ]);
        }
        
        session()->forget('cart');
        return redirect()->route('shop.home')->with('success', 'Order placed successfully! Your order #' . $order->id . ' has been received.');
    }
}

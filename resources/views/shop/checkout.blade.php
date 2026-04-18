@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            @if(empty($cart))
                <div class="card shadow border-0 mb-4">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-cart-x" style="font-size: 4rem; color: #6c757d;"></i>
                        </div>
                        <h3 class="h5 mb-4 text-secondary">{{ __('Your cart is empty') }}</h3>
                        <a href="{{ route('shop.home') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-shop me-2"></i>{{ __('Continue Shopping') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                        <i class="bi bi-credit-card me-2" style="font-size: 1.5rem; color: #50946c;"></i>
                        <h2 class="h4 fw-bold mb-0" style="color: #50946c;">{{ __('Checkout') }}</h2>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-header bg-light border-0 pb-0">
                                <h5 class="mb-0">{{ __('Order Summary') }}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush mb-3">
                                    @php $total = 0; $totalSavings = 0; @endphp
                                    @foreach($cart as $item)
                                        @php 
                                            $itemPrice = $item['discounted_price'] ?? $item['price'];
                                            $subtotal = $itemPrice * $item['quantity']; 
                                            $total += $subtotal;
                                            if (isset($item['discount_percentage']) && $item['discount_percentage'] > 0) {
                                                $itemSavings = ($item['price'] - $itemPrice) * $item['quantity'];
                                                $totalSavings += $itemSavings;
                                            }
                                            $imgPath = $item['image'] ? 'storage/' . $item['image'] : null;
                                            $defaultImage = asset('product-images/default_product.png');
                                            $imageSrc = $defaultImage;
                                            if ($imgPath && file_exists(public_path($imgPath))) {
                                                $imageSrc = asset($imgPath);
                                            }
                                        @endphp
                                        <li class="list-group-item bg-light border-0 border-bottom px-0 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $imageSrc }}" width="50" height="50" class="me-3 rounded-3 border shadow-sm checkout-img-hover" alt="{{ $item['name'] }}" style="object-fit:cover;">
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium">
                                                        {{ $item['name'] }}
                                                        @if(isset($item['discount_percentage']) && $item['discount_percentage'] > 0)
                                                            <span class="badge bg-danger small ms-1">{{ number_format($item['discount_percentage'], 0) }}% OFF</span>
                                                        @endif
                                                    </div>
                                                    <div class="small text-secondary">
                                                        @if(isset($item['discount_percentage']) && $item['discount_percentage'] > 0)
                                                            <span class="text-muted text-decoration-line-through">LKR {{ number_format($item['price'], 2) }}</span>
                                                            <span class="text-danger fw-semibold ms-1">LKR {{ number_format($itemPrice, 2) }}</span>
                                                            × {{ $item['quantity'] }}
                                                        @else
                                                            {{ __('Qty') }}: {{ $item['quantity'] }} × LKR {{ number_format($item['price'], 2) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="fw-medium text-end" style="min-width:80px;">LKR {{ number_format($subtotal,2) }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                @if($totalSavings > 0)
                                    <div class="d-flex justify-content-between align-items-center text-success mb-2">
                                        <span class="small">{{ __('Total Savings') }}:</span>
                                        <span class="fw-semibold">LKR {{ number_format($totalSavings, 2) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center fw-bold border-top pt-3">
                                    <span class="h5 mb-0">{{ __('Total') }}:</span>
                                    <span class="h4 mb-0" style="color:#50946c;">LKR {{ number_format($total,2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @guest
                            <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>
                                    {{ __('You need to have an account as a customer to move forward with the order placement.') }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Login') }}
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-person-plus me-2"></i>{{ __('Register') }}
                                </a>
                            </div>
                        @else
                            <div class="card mb-4 border-0">
                                <div class="card-header bg-white border-0 pb-0">
                                    <h5 class="mb-0">{{ __('Delivery Address') }}</h5>
                                </div>
                                <div class="card-body pt-3">
                                    @if(auth()->user()->hasCompleteAddress())
                                        <div class="mb-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="address_option" id="use_saved_address" value="saved" checked>
                                                <label class="form-check-label" for="use_saved_address">
                                                    <strong>{{ __('Use my saved address') }}</strong>
                                                </label>
                                            </div>
                                            <div class="card border-light bg-light ms-4" id="saved_address_display">
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <strong>{{ auth()->user()->name }}</strong>
                                                    </div>
                                                    <div class="text-secondary">
                                                        {{ auth()->user()->full_address }}
                                                    </div>
                                                    @if(auth()->user()->phone)
                                                        <div class="text-secondary small mt-1">
                                                            <i class="bi bi-telephone"></i> {{ auth()->user()->phone }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_option" id="use_new_address" value="new">
                                                <label class="form-check-label" for="use_new_address">
                                                    <strong>{{ __('Use a different address') }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <form action="{{ route('checkout.process') }}" method="POST" class="mb-0" id="checkout_form">
                                        @csrf
                                        <div id="new_address_fields" @if(auth()->user()->hasCompleteAddress()) style="display: none;" @endif>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="customer_name" class="form-label">{{ __('Full Name') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                        <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label for="customer_phone" class="form-label">{{ __('Phone Number') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="customer_email" class="form-label">{{ __('Email Address') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                    <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="address_line_1" class="form-label">{{ __('Address Line 1') }} *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-house"></i></span>
                                                    <input type="text" name="address_line_1" id="address_line_1" class="form-control" value="{{ old('address_line_1') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="address_line_2" class="form-label">{{ __('Address Line 2') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-house-add"></i></span>
                                                    <input type="text" name="address_line_2" id="address_line_2" class="form-control" value="{{ old('address_line_2') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="district" class="form-label">{{ __('District') }} *</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-map"></i></span>
                                                        <select name="district" id="district" class="form-select">
                                                            <option value="">{{ __('Select District') }} *</option>
                                                            @foreach(config('sri_lanka_districts.districts') as $district => $cities)
                                                                <option value="{{ $district }}" {{ old('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label for="city" class="form-label">{{ __('City') }} *</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                                                        <select name="city" id="city" class="form-select">
                                                            <option value="">{{ __('Select City') }} *</option>
                                                            @if(old('district'))
                                                                @foreach(config('sri_lanka_districts.districts.' . old('district')) as $city)
                                                                    <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="postal_code" class="form-label">{{ __('Postal Code') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-envelope-paper"></i></span>
                                                    <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code') }}" pattern="[0-9]{5}" maxlength="5">
                                                </div>
                                            </div>
                                            
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="save_address" id="save_address" value="1">
                                                <label class="form-check-label" for="save_address">
                                                    {{ __('Save this address for future orders') }}
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="card border-0 bg-light mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-3">
                                                    <i class="bi bi-credit-card me-2" style="color: #50946c;"></i>{{ __('Payment Method') }}
                                                </h6>
                                                <div class="mb-3">
                                                    @foreach([
                                                        'cod' => 'Cash on Delivery',
                                                        'card' => 'Credit/Debit Card', 
                                                        'bank' => 'Bank Transfer',
                                                        'mobile' => 'Mobile Payment'
                                                    ] as $value => $label)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                                   id="payment_{{ $value }}" value="{{ $value }}" 
                                                                   @if($loop->first) checked @endif
                                                                   required>
                                                            <label class="form-check-label" for="payment_{{ $value }}">
                                                                <span class="fw-medium">{{ $label }}</span>
                                                                @if($value === 'cod')
                                                                    <span class="text-muted small ms-2">{{ __('(Pay when you receive)') }}</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-semibold">
                                            <i class="bi bi-bag-check me-2"></i>{{ __('Place Order') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .checkout-img-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .checkout-img-hover:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 32px 0 rgba(80,148,108,0.15);
    }
    
    .btn-success {
        background-color: #50946c;
        border-color: #50946c;
    }
    
    .btn-success:hover {
        background-color: #3d7254;
        border-color: #3d7254;
    }
    
    .btn-outline-success {
        color: #50946c;
        border-color: #50946c;
    }
    
    .btn-outline-success:hover {
        background-color: #50946c;
        border-color: #50946c;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const useSavedAddress = document.getElementById('use_saved_address');
    const useNewAddress = document.getElementById('use_new_address');
    const newAddressFields = document.getElementById('new_address_fields');
    const checkoutForm = document.getElementById('checkout_form');
    
    if (useSavedAddress && useNewAddress) {
        useSavedAddress.addEventListener('change', function() {
            if (this.checked) {
                newAddressFields.style.display = 'none';
            }
        });
        
        useNewAddress.addEventListener('change', function() {
            if (this.checked) {
                newAddressFields.style.display = 'block';
            }
        });
    }
    
    // Handle form submission
    checkoutForm.addEventListener('submit', function(e) {
        if (useSavedAddress && useSavedAddress.checked) {
            // If using saved address, add hidden field to indicate this
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'use_saved_address';
            hidden.value = '1';
            checkoutForm.appendChild(hidden);
        }
    });
});
</script>@endsection

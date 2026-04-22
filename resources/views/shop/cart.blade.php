@extends('layouts.app')
@section('title', 'Your Cart')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                    <i class="bi bi-cart3 me-2" style="font-size: 1.5rem; color: #50946c;"></i>
                    <h2 class="h4 fw-bold mb-0" style="color: #50946c;">{{ __('Shopping Cart') }}</h2>
                </div>

                <div class="card-body p-4">
                    @if(empty($cart))
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-cart-x" style="font-size: 4rem; color: #6c757d;"></i>
                            </div>
                            <h3 class="h5 mb-4 text-secondary">{{ __('Your cart is empty') }}</h3>
                            <a href="{{ route('shop.home') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-shop me-2"></i>{{ __('Continue Shopping') }}
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{ __('Product') }}</th>
                                            <th scope="col">{{ __('Price') }}</th>
                                            <th scope="col">{{ __('Qty') }}</th>
                                            <th scope="col">{{ __('Subtotal') }}</th>
                                            <th scope="col">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; $totalSavings = 0; $totalDelivery = 0; @endphp
                                        @foreach($cart as $item)
                                        @php
                                            $itemPrice = $item['discounted_price'] ?? $item['price'];
                                            $subtotal = $itemPrice * $item['quantity'];
                                            $deliveryFee = $item['delivery_fee'] ?? 0;
                                            $total += $subtotal;
                                            $totalDelivery += $deliveryFee;
                                            if (isset($item['discount_percentage']) && $item['discount_percentage'] > 0) {
                                                $itemSavings = ($item['price'] - $itemPrice) * $item['quantity'];
                                                $totalSavings += $itemSavings;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['image'] }}" width="60" height="60" class="me-3 rounded-3 border shadow-sm cart-img-hover" alt="{{ $item['name'] }}" style="object-fit:cover;" onerror="this.onerror=null; this.src='{{ asset('product-images/default_product.png') }}'">
                                                    <div>
                                                        <span class="fw-medium d-block">{{ $item['name'] }}</span>
                                                        @if(isset($item['discount_percentage']) && $item['discount_percentage'] > 0)
                                                            <span class="badge bg-danger small">{{ number_format($item['discount_percentage'], 0) }}% OFF</span>
                                                        @endif
                                                        @if(isset($item['delivery_info']) && ($item['delivery_info']['has_free_delivery'] || $item['delivery_info']['delivery_fee'] > 0))
                                                            <div class="small text-muted mt-1">
                                                                @if($item['delivery_info']['has_free_delivery'])
                                                                    <i class="bi bi-truck text-success"></i>
                                                                    @if($item['quantity'] >= $item['delivery_info']['free_delivery_quantity'])
                                                                        <span class="text-success">Free delivery</span>
                                                                    @else
                                                                        <span>Free delivery from {{ $item['delivery_info']['free_delivery_quantity'] }} units</span>
                                                                    @endif
                                                                @else
                                                                    <i class="bi bi-truck text-warning"></i>
                                                                    <span>Delivery: LKR {{ number_format($item['delivery_fee'], 2) }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">
                                                @if(isset($item['discount_percentage']) && $item['discount_percentage'] > 0)
                                                    <div class="text-muted text-decoration-line-through small">
                                                        LKR {{ number_format($item['price'], 2) }}
                                                    </div>
                                                    <div class="fw-bold text-danger">
                                                        LKR {{ number_format($itemPrice, 2) }}
                                                    </div>
                                                @else
                                                    LKR {{ number_format($item['price'], 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="w-100">
                                                    @csrf
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="input-group input-group-sm" style="width:110px;">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0.1" step="0.1" class="form-control text-center" id="qty-{{ $item['id'] }}">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted ms-2">Current: {{ $item['quantity'] }}</small>
                                                    </div>
                                                    <button type="submit" class="btn btn-outline-success btn-sm rounded-pill w-100">
                                                        <i class="bi bi-arrow-repeat me-1"></i>{{ __('Update') }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-nowrap fw-medium">LKR {{ number_format($subtotal,2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                        <i class="bi bi-trash me-1"></i>{{ __('Remove') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    @if($totalSavings > 0)
                                        <div class="d-flex justify-content-between align-items-center mb-2 text-success">
                                            <span class="small">{{ __('Total Savings') }}:</span>
                                            <span class="fw-semibold">LKR {{ number_format($totalSavings, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($totalDelivery > 0)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small">{{ __('Delivery Fees') }}:</span>
                                            <span class="fw-semibold">LKR {{ number_format($totalDelivery, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0 text-secondary">{{ __('Subtotal') }}:</span>
                                        <span class="h5 mb-0 fw-medium">LKR {{ number_format($total,2) }}</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0 text-secondary">{{ __('Total') }}:</span>
                                        <span class="h4 mb-0 fw-bold" style="color:#50946c;">LKR {{ number_format($total + $totalDelivery,2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mt-4">
                                <a href="{{ route('shop.home') }}" class="btn btn-secondary rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-arrow-left me-2"></i>{{ __('Continue Shopping') }}
                                </a>
                                <form action="{{ route('checkout.index') }}" method="get" style="display: inline;">
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                        {{ __('Proceed to Checkout') }} <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </form>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cart-img-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .cart-img-hover:hover {
        transform: scale(1.04) rotate(-1deg);
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

    .btn-secondary:hover {
        background-color: #c52b36;
        border-color: #c52b36;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add form validation for cart update forms
    const cartForms = document.querySelectorAll('form[action*="cart.update"]');

    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const quantityInput = this.querySelector('input[name="quantity"]');
            const quantity = parseFloat(quantityInput.value);

            console.log('Form submitting with quantity:', quantity);

            if (isNaN(quantity) || quantity < 0.1) {
                e.preventDefault();
                alert('Please enter a valid quantity (minimum 0.1)');
                return false;
            }

            // Log for debugging
            console.log('Cart update form submitted successfully');
        });

        // Add input validation
        const quantityInput = form.querySelector('input[name="quantity"]');
        quantityInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value) && value >= 0.1) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>
@endsection

@extends('admin.layout')

@section('title', 'Discount Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-eye me-2"></i>Discount Details: {{ $discount->name }}
        </h1>
        <div>
            <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Discounts
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Discount Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $discount->name }}</p>
                            <p><strong>Type:</strong> 
                                <span class="badge bg-{{ $discount->type == 'percentage' ? 'info' : 'success' }}">
                                    {{ $discount->type == 'percentage' ? 'Percentage' : 'Fixed Amount' }}
                                </span>
                            </p>
                            <p><strong>Value:</strong> 
                                @if($discount->type == 'percentage')
                                    {{ $discount->value }}%
                                @else
                                    ${{ number_format($discount->value, 2) }}
                                @endif
                            </p>
                            <p><strong>Applicable To:</strong> 
                                @if($discount->applicable_to == 'all')
                                    <span class="badge bg-primary">All Products</span>
                                @elseif($discount->applicable_to == 'specific_products')
                                    <span class="badge bg-warning">{{ count($discount->product_ids ?? []) }} Specific Products</span>
                                @else
                                    <span class="badge bg-secondary">{{ count($discount->category_ids ?? []) }} Specific Categories</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                @if($discount->isActive())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    @if(!$discount->is_active)
                                        <span class="badge bg-danger">Disabled</span>
                                    @elseif($discount->starts_at && $discount->starts_at->isFuture())
                                        <span class="badge bg-warning">Upcoming</span>
                                    @elseif($discount->expires_at && $discount->expires_at->isPast())
                                        <span class="badge bg-secondary">Expired</span>
                                    @else
                                        <span class="badge bg-danger">Limit Reached</span>
                                    @endif
                                @endif
                            </p>
                            <p><strong>Usage:</strong> 
                                {{ $discount->used_count }} used
                                @if($discount->usage_limit)
                                    / {{ $discount->usage_limit }} limit
                                @else
                                    (unlimited)
                                @endif
                            </p>
                            <p><strong>Duration:</strong><br>
                                @if($discount->starts_at)
                                    <small>From: {{ $discount->starts_at->format('M j, Y g:i A') }}</small><br>
                                @endif
                                @if($discount->expires_at)
                                    <small>Until: {{ $discount->expires_at->format('M j, Y g:i A') }}</small>
                                @else
                                    <small>No expiry date</small>
                                @endif
                            </p>
                            @if($discount->minimum_order_amount)
                                <p><strong>Minimum Order:</strong> ${{ number_format($discount->minimum_order_amount, 2) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($discount->description)
                        <div class="mt-3">
                            <strong>Description:</strong>
                            <p class="mt-1">{{ $discount->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($discount->applicable_to === 'specific_products')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Applicable Products</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Original Price</th>
                                        <th>Discounted Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($discount->getApplicableProducts() as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td class="fw-bold text-success">
                                                ${{ number_format($discount->applyDiscount($product->price), 2) }}
                                                <small class="text-muted">
                                                    ({{ $discount->type == 'percentage' ? $discount->value . '%' : '$' . $discount->value }} off)
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($discount->applicable_to === 'specific_categories')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Applicable Categories</h6>
                    </div>
                    <div class="card-body">
                        @foreach($categories->whereIn('id', $discount->category_ids ?? []) as $category)
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                                <small class="text-muted ms-2">
                                    {{ $category->products()->count() }} products in this category
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Edit Discount
                        </a>
                        <form action="{{ route('admin.discounts.toggle', $discount) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-{{ $discount->is_active ? 'danger' : 'success' }} w-100">
                                <i class="bi bi-{{ $discount->is_active ? 'pause' : 'play' }} me-1"></i>
                                {{ $discount->is_active ? 'Deactivate' : 'Activate' }} Discount
                            </button>
                        </form>
                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this discount? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-1"></i> Delete Discount
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Uses</small>
                        <h4 class="mb-0">{{ $discount->used_count }}</h4>
                    </div>
                    @if($discount->usage_limit)
                        <div class="mb-3">
                            <small class="text-muted">Usage Limit</small>
                            <h4 class="mb-0">{{ $discount->usage_limit }}</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Remaining Uses</small>
                            <h4 class="mb-0 text-{{ $discount->usage_limit - $discount->used_count > 0 ? 'success' : 'danger' }}">
                                {{ max(0, $discount->usage_limit - $discount->used_count) }}
                            </h4>
                        </div>
                    @endif
                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <p class="mb-0">{{ $discount->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Last Updated</small>
                        <p class="mb-0">{{ $discount->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

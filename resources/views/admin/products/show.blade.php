@extends('admin.layout')

@section('title', 'Product Details')

@push('styles')
<style>
    .product-image {
        width: 100%;
        max-width: 400px;
        height: 300px;
        object-fit: cover;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .stat-card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stock-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .stock-high { background-color: #d1f2eb; color: #0e5f4e; }
    .stock-medium { background-color: #fff3cd; color: #856404; }
    .stock-low { background-color: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="container px-4 py-4">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Details</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Product Image and Basic Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $product->name }}</h3>
                            <p class="text-muted mb-4">{{ $product->description }}</p>
                            
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Product ID</small>
                                    <strong>#{{ $product->id }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">SKU</small>
                                    <strong>{{ $product->sku ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Category</small>
                                    <strong>{{ $product->category->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Price</small>
                                    <strong class="text-success">LKR {{ number_format($product->price, 2) }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Stock Status</small>
                                    @php
                                        $stockClass = $product->stock > 50 ? 'stock-high' : 
                                                     ($product->stock > 10 ? 'stock-medium' : 'stock-low');
                                        $stockText = $product->stock > 50 ? 'In Stock' : 
                                                    ($product->stock > 10 ? 'Low Stock' : 'Very Low Stock');
                                    @endphp
                                    <span class="stock-badge {{ $stockClass }}">
                                        {{ $stockText }} ({{ $product->stock }} units)
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Created</small>
                                    <strong>{{ $product->created_at->format('M d, Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="stat-card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-2">Current Stock</h6>
                                    <h3 class="mb-0">{{ $product->stock }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-2">Unit Price</h6>
                                    <h3 class="mb-0">LKR {{ number_format($product->price, 0) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-2">Stock Value</h6>
                                    <h3 class="mb-0">LKR {{ number_format($product->stock * $product->price, 0) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Product
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="viewSalesHistory({{ $product->id }})">
                            <i class="bi bi-graph-up me-2"></i>View Sales History
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="adjustStock({{ $product->id }})">
                            <i class="bi bi-box-seam me-2"></i>Adjust Stock
                        </button>
                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-2"></i>Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewSalesHistory(productId) {
    window.open('/admin/reports/item-wise-sales?product_id=' + productId, '_blank');
}

function adjustStock(productId) {
    // Implementation for stock adjustment modal
    alert('Stock adjustment functionality would be implemented here for product ID: ' + productId);
}
</script>
@endpush

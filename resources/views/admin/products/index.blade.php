@extends('admin.layout')
@section('title', 'Products Management')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Products Management</h1>
            <p class="text-muted mb-0">Manage your product catalog and inventory</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-success btn-modern">
                <i class="bi bi-percent me-2"></i>Manage Discounts
            </a>
            <button type="button" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-download me-2"></i>Export
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-modern">
                <i class="bi bi-plus-circle me-2"></i>Add Product
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4 search-card">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search Products</label>
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control search-input" 
                               placeholder="Search by product name or SKU..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select form-select-modern">
                        <option value="">All Categories</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Stock Status</label>
                    <select name="stock_status" class="form-select form-select-modern">
                        <option value="">All Stock</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-modern w-100">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-modern w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow modern-card">
        <div class="card-header modern-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="modern-card-title">
                    <i class="bi bi-box-seam me-2"></i>
                    All Products ({{ $products->count() }})
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-success modern-badge">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ isset($stats['in_stock']) ? $stats['in_stock'] : 0 }} In Stock
                    </span>
                    <span class="badge bg-warning modern-badge">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ isset($stats['low_stock']) ? $stats['low_stock'] : 0 }} Low Stock
                    </span>
                    <span class="badge bg-danger modern-badge">
                        <i class="bi bi-x-circle me-1"></i>
                        {{ isset($stats['out_of_stock']) ? $stats['out_of_stock'] : 0 }} Out of Stock
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="modern-th">Product</th>
                            <th class="modern-th">SKU</th>
                            <th class="modern-th">Category</th>
                            <th class="modern-th">Price</th>
                            <th class="modern-th">Stock</th>
                            <th class="modern-th">Status</th>
                            <th class="modern-th text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->count() > 0)
                            @foreach($products as $product)
                            <tr class="modern-row">
                                <td class="modern-td">
                                    <div class="product-info">
                                        <div class="product-image-wrapper">
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" 
                                                     class="product-image" 
                                                     alt="{{ $product->name }}"
                                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0yMCAxMEMyMCAxNSAxNSAyMCAxNSAyMEMxNSAyNSAyMCAzMCAyMCAzMEMyNSAzMCAzMCAyNSAzIDIwQzMwIDIwIDI1IDE1IDIwIDEwWiIgZmlsbD0iI0QxRDVEQiIvPgo8L3N2Zz4K'">
                                            @else
                                                <div class="product-image-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="product-details">
                                            <div class="product-name">{{ $product->name }}</div>
                                            <div class="product-description">{{ Str::limit($product->description ?? '', 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <span class="sku-badge">{{ $product->sku ?? 'PROD-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="modern-td">
                                    @if($product->category)
                                        <span class="category-badge">
                                            <i class="bi bi-tag me-1"></i>
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="modern-td">
                                    <div class="price-wrapper">
                                        <span class="currency">LKR</span>
                                        <span class="price-amount">{{ number_format($product->price, 2) }}</span>
                                        <span class="unit-badge">/ {{ $product->unit_display }}</span>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <div class="stock-info">
                                        <div class="stock-quantity">{{ $product->stock }}</div>
                                        <div class="stock-label">
                                            @if($product->stock > 10)
                                                In Stock
                                            @elseif($product->stock > 0)
                                                Low Stock
                                            @else
                                                Out of Stock
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <span class="status-badge status-{{ $product->stock > 0 ? ($product->stock > 10 ? 'active' : 'warning') : 'inactive' }}">
                                        <i class="bi bi-circle-fill me-1 status-dot"></i>
                                        {{ $product->stock > 0 ? ($product->stock > 10 ? 'Active' : 'Low Stock') : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="modern-td text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-sm btn-primary btn-action"
                                           title="Edit Product">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-action"
                                                title="Quick View"
                                                onclick="quickView({{ $product->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="{{ route('admin.products.destroy', $product) }}" 
                                              method="POST" 
                                              style="display:inline-block"
                                              onsubmit="return confirmDelete(event, '{{ $product->name }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger btn-action"
                                                    title="Delete Product">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-box empty-icon"></i>
                                        <h5 class="empty-title">No Products Found</h5>
                                        <p class="empty-text">Start by adding your first product to the catalog</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-modern">
                                            <i class="bi bi-plus-circle me-2"></i>Add First Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function quickView(productId) {
    // Quick view product details
    fetch(`/admin/products/${productId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: data.name,
                html: `
                    <div class="text-start">
                        <p><strong>SKU:</strong> ${data.sku}</p>
                        <p><strong>Category:</strong> ${data.category}</p>
                        <p><strong>Price:</strong> LKR ${data.price}</p>
                        <p><strong>Stock:</strong> ${data.stock}</p>
                        <p><strong>Description:</strong> ${data.description || 'No description'}</p>
                    </div>
                `,
                imageUrl: data.image || null,
                imageWidth: 400,
                imageHeight: 200,
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Close'
            });
        })
        .catch(error => {
            Swal.fire('Error', 'Failed to load product details', 'error');
        });
}

function confirmDelete(event, productName) {
    event.preventDefault();
    Swal.fire({
        title: 'Delete Product?',
        html: `Are you sure you want to delete <strong>${productName}</strong>?<br><br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
    return false;
}
</script>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<style>
/* Modern Button Styles */
.btn-modern {
    border-radius: 25px;
    font-weight: 600;
    padding: 10px 20px;
    border: none;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 13px;
}

.btn-primary.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Search Card */
.search-card {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 10;
}

.search-input {
    padding-left: 45px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-select-modern {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

/* Modern Card */
.modern-card {
    border-radius: 15px;
    border: none;
    overflow: hidden;
}

.modern-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.25rem 1.5rem;
}

.modern-card-title {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
    margin: 0;
}

.modern-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Modern Table */
.modern-table {
    background: white;
}

.modern-table thead th {
    background: #f8f9fa;
    border: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
    color: #495057;
    padding: 1rem;
}

.modern-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f5;
}

.modern-row:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    transform: scale(1.01);
}

.modern-row:last-child {
    border-bottom: none;
}

.modern-td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

/* Product Info */
.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-image-wrapper {
    flex-shrink: 0;
}

.product-image {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.product-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.product-image-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 20px;
    border: 2px dashed #dee2e6;
}

.product-details {
    flex-grow: 1;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 15px;
    margin-bottom: 2px;
}

.product-description {
    font-size: 12px;
    color: #6c757d;
    line-height: 1.4;
}

/* SKU Badge */
.sku-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #e9ecef;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    color: #495057;
}

/* Category Badge */
.category-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    color: #495057;
}

/* Price Wrapper */
.price-wrapper {
    display: flex;
    align-items: baseline;
    gap: 2px;
}

.currency {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

.price-amount {
    font-weight: 700;
    font-size: 16px;
    color: #2c3e50;
}

.unit-badge {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
    margin-left: 4px;
}

/* Stock Info */
.stock-info {
    text-align: center;
}

.stock-quantity {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.stock-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: #d1e7dd;
    color: #0f5132;
}

.status-warning {
    background: #fff3cd;
    color: #856404;
}

.status-inactive {
    background: #f8d7da;
    color: #842029;
}

.status-dot {
    font-size: 8px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-title {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .product-description {
        display: none;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>
@endsection

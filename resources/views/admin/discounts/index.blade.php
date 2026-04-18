@extends('admin.layout')

@section('title', 'Manage Discounts')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-percent me-2"></i>Manage Product Discounts
        </h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Set Discount Percentage for Each Product</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.discounts.update-bulk') }}" method="POST" id="discountForm">
                @csrf
                
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                        </div>
                        <div class="col-md-3">
                            <select id="categoryFilter" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="discountFilter" class="form-select">
                                <option value="">All Products</option>
                                <option value="with">With Discount</option>
                                <option value="without">Without Discount</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="productsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Product Name</th>
                                <th width="15%">Category</th>
                                <th width="12%">Price</th>
                                <th width="15%">Discount %</th>
                                <th width="18%">Discounted Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                            <tr data-category="{{ $product->category_id }}" data-discount="{{ $product->discount_percentage }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="product-name">
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                    @else
                                        <span class="badge bg-warning">No Category</span>
                                    @endif
                                </td>
                                <td class="fw-bold">${{ number_format($product->price, 2) }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center">
                                        <input type="number" 
                                               name="discounts[{{ $product->id }}]" 
                                               class="form-control form-control-sm discount-input" 
                                               value="{{ $product->discount_percentage }}" 
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               data-price="{{ $product->price }}"
                                               data-product-id="{{ $product->id }}"
                                               style="width: 70px; display: inline-block;">
                                        <span class="ms-1">%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="discounted-price fw-bold text-success" id="price-{{ $product->id }}">
                                        ${{ number_format($product->discounted_price, 2) }}
                                    </span>
                                    @if($product->discount_percentage > 0)
                                        <br><small class="text-muted">Save ${{ number_format($product->price - $product->discounted_price, 2) }}</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearAllDiscounts()">
                            <i class="bi bi-x-circle me-1"></i> Clear All Discounts
                        </button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-1"></i> Save All Discounts
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .discount-input {
        text-align: center !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 0.375rem 0.5rem !important;
        border: 1px solid #ced4da !important;
        background-color: #ffffff !important;
        color: #212529 !important;
        transition: all 0.2s ease;
    }
    .discount-input:focus {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
        background-color: #f8fff9 !important;
        outline: none !important;
    }
    .discount-input:hover {
        border-color: #adb5bd !important;
    }
    .table td {
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
    }
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .discounted-price {
        font-size: 1em;
        display: inline-block;
    }
    .product-name strong {
        color: #2c3e50;
        font-size: 0.9rem;
    }
    .product-name small {
        font-size: 0.8rem;
    }
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
    }
    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .form-control, .form-select {
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Real-time discount calculation
        $('.discount-input').on('input', function() {
            const input = $(this);
            const price = parseFloat(input.data('price'));
            const discount = parseFloat(input.val()) || 0;
            const productId = input.data('product-id');
            
            const discountedPrice = price * (1 - discount / 100);
            const savings = price - discountedPrice;
            
            const priceSpan = $('#price-' + productId);
            if (discount > 0) {
                priceSpan.html('$' + discountedPrice.toFixed(2) + '<br><small class="text-muted">Save $' + savings.toFixed(2) + '</small>');
            } else {
                priceSpan.html('$' + price.toFixed(2));
            }
        });

        // Search functionality
        $('#searchInput').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#productsTable tbody tr').filter(function() {
                $(this).toggle($(this).find('.product-name').text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Category filter
        $('#categoryFilter').on('change', function() {
            const categoryId = $(this).val();
            if (categoryId === '') {
                $('#productsTable tbody tr').show();
            } else {
                $('#productsTable tbody tr').each(function() {
                    $(this).toggle($(this).data('category') == categoryId);
                });
            }
        });

        // Discount filter
        $('#discountFilter').on('change', function() {
            const filter = $(this).val();
            if (filter === '') {
                $('#productsTable tbody tr').show();
            } else if (filter === 'with') {
                $('#productsTable tbody tr').each(function() {
                    $(this).toggle(parseFloat($(this).data('discount')) > 0);
                });
            } else if (filter === 'without') {
                $('#productsTable tbody tr').each(function() {
                    $(this).toggle(parseFloat($(this).data('discount')) === 0);
                });
            }
        });
    });

    function clearAllDiscounts() {
        if (confirm('Are you sure you want to clear all discounts?')) {
            $('.discount-input').val(0).trigger('input');
        }
    }
</script>
@endpush

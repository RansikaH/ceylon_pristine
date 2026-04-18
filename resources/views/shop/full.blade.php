@extends('layouts.app')
@section('title', 'Shop - All Products')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h3 fw-bold mb-0" style="color: #50946c;">{{ __('Shop All Products') }}</h1>
                <a href="{{ route('shop.home') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('Back to Shop') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                    <i class="bi bi-funnel me-2" style="font-size: 1.2rem; color: #50946c;"></i>
                    <h2 class="h5 fw-bold mb-0" style="color: #50946c;">{{ __('Filter Products') }}</h2>
                </div>

                <div class="card-body p-4">
                    <form method="GET" action="{{ route('shop.full') }}">
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">{{ __('Category') }}</label>
                            <div class="ps-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input category-filter" type="radio" name="category" id="category_all" value="" {{ empty(request('category')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category_all">{{ __('All Categories') }}</label>
                                </div>
                                @foreach($categories as $cat)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input category-filter" type="radio" name="category" id="category_{{ $cat->id }}" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category_{{ $cat->id }}">{{ $cat->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- <!-- Price Range Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">{{ __('Price Range') }}</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                <input type="number" step="0.01" min="0" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                <input type="number" step="0.01" min="0" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div> --}}

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success rounded-pill">
                                <i class="bi bi-funnel-fill me-2"></i>{{ __('Apply Filters') }}
                            </button>
                            <a href="{{ route('shop.full') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-x-circle me-2"></i>{{ __('Clear Filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Product Grid -->
        <div class="col-md-9">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                    <i class="bi bi-grid me-2" style="font-size: 1.2rem; color: #50946c;"></i>
                    <h2 class="h5 fw-bold mb-0" style="color: #50946c;">{{ __('Products') }}</h2>
                </div>

                <div class="card-body p-4">
                    @if($products->count())
                        <div class="row g-4">
                            @foreach($products as $product)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    @include('components.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>


                        <div class="d-flex justify-content-center mt-5">
                            {{ $products->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <div>{{ __('No products found matching your criteria.') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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

    .form-check-input:checked {
        background-color: #50946c;
        border-color: #50946c;
    }

    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: #50946c;
        border-color: #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 2px;
        border-radius: 0.375rem;
    }

    .pagination .page-link:hover {
        color: #3d7254;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .pagination .page-item.active .page-link {
        background-color: #50946c;
        border-color: #50946c;
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-filter when category is selected
    const categoryFilters = document.querySelectorAll('.category-filter');

    categoryFilters.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
            // Get the form and submit it
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });
});
</script>
@endsection

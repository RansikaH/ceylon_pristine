@extends('layouts.app')
@section('title', 'Shop Preview')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box-seam me-2" style="font-size: 1.5rem; color: #50946c;"></i>
                        <h2 class="h4 fw-bold mb-0" style="color: #50946c;">{{ __('Product Preview') }}</h2>
                    </div>
                    <a href="{{ route('shop.full') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>{{ __('Back to Shop') }}
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-5 text-center">
                            <div class="bg-light rounded p-3 d-flex align-items-center justify-content-center mb-3" style="height:260px; position:relative;">
                                {{-- Use $product->display_image_url, set in controller/model for robust fallback --}}
                                <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}" class="img-fluid preview-img-hover" style="max-height:220px; max-width:100%; object-fit:contain;">
                                
                                @if(!empty($product->is_new))
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2 rounded-pill px-3 py-2">{{ __('New') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-light text-dark me-2 rounded-pill px-3 py-2">
                                    <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                <span class="h4 fw-bold mb-0" style="color:#50946c;">
                                    LKR {{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            
                            <div class="bg-light p-3 rounded mb-4">
                                <h6 class="fw-bold mb-2">{{ __('Description') }}</h6>
                                <p class="mb-0">{{ $product->description ?? __('No description available.') }}</p>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold">
                                        <i class="bi bi-cart-plus me-2"></i>{{ __('Add to Cart') }}
                                    </button>
                                </form>
                                
                                <a href="{{ route('shop.show', $product) }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-eye me-2"></i>{{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .preview-img-hover {
        transition: transform 0.3s ease;
    }
    
    .preview-img-hover:hover {
        transform: scale(1.05);
    }
    
    .btn-success {
        background-color: #50946c;
        border-color: #50946c;
    }
    
    .btn-success:hover {
        background-color: #3d7254;
        border-color: #3d7254;
    }
    
    .btn-danger {
        background-color: #c52b36;
        border-color: #c52b36;
    }
    
    .btn-danger:hover {
        background-color: #a12530;
        border-color: #a12530;
    }
</style>
@endsection

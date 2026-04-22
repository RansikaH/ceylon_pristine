@extends('layouts.app')
@section('title', $product->name)
@section('content')

{{-- Product Detail Section --}}
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                    <i class="bi bi-box2-heart me-2" style="font-size: 1.5rem; color: #50946c;"></i>
                    <h2 class="h4 fw-bold mb-0" style="color: #50946c;">{{ __('Product Details') }}</h2>
                </div>
                <div class="card-body">
                    <div class="row align-items-center g-0">
                        {{-- Product Image Gallery --}}
                        <div class="col-md-6 text-center p-4 p-md-5 d-flex flex-column align-items-center justify-content-center">
                            <div class="position-relative w-100" style="max-width:400px;">
                                <!-- Main Product Image -->
                                <div id="mainImageContainer" class="mb-3">
                                    <img id="mainProductImage"
                                         src="{{ $product->main_image }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid rounded-3 shadow border border-2 border-light product-img-hover"
                                         style="max-height:400px; object-fit:cover; background:#f8f9fa; padding: 8px;">
                                    @if($product->hasDiscount())
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2 px-3 py-2 fs-6 fw-bold">
                                            {{ number_format($product->discount_percentage, 0) }}% OFF
                                        </span>
                                    @endif
                                </div>

                                <!-- Image Thumbnails -->
                                @if($product->all_images->count() > 1)
                                    <div class="thumbnail-gallery d-flex justify-content-center gap-2 flex-wrap">
                                        @foreach($product->all_images as $index => $image)
                                            <div class="thumbnail-item {{ $image->is_primary ? 'active' : '' }}"
                                                 onclick="switchMainImage('{{ $image->image_url }}', this)"
                                                 data-image-url="{{ $image->image_url }}">
                                                <img src="{{ $image->image_url }}"
                                                     alt="Product Image {{ $index + 1 }}"
                                                     class="img-thumbnail rounded-2"
                                                     style="width:60px; height:60px; object-fit:cover; cursor:pointer; transition:all 0.3s ease;">
                                                @if($image->is_primary)
                                                    <small class="text-primary d-block text-center mt-1" style="font-size:0.7rem;">Main</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Product Info --}}
                        <div class="col-md-6 p-4 p-md-5 border-start border-2 border-light h-100 d-flex flex-column justify-content-center">
                            <h1 class="fw-bold mb-2" style="color:#50946c;">{{ $product->name }}</h1>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? '-' }}
                                </span>
                                <div class="unit-tag">
                                    {{ $product->unit_display }}
                                </div>
                            </div>

                            <hr class="my-3 opacity-50">

                            <div class="mb-3">
                                @if($product->hasDiscount())
                                    <div class="mb-2">
                                        <span class="text-muted text-decoration-line-through fs-5">
                                            LKR {{ number_format($product->price, 2) }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="fs-3 fw-bold" style="color:#c52b36;">
                                            LKR {{ number_format($product->discounted_price, 2) }}
                                        </span>
                                        <span class="badge bg-success px-3 py-2 fs-6">
                                            Save LKR {{ number_format($product->price - $product->discounted_price, 2) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="fs-3 fw-bold" style="color:#c52b36;">
                                        LKR {{ number_format($product->price, 2) }}
                                    </span>
                                @endif
                                <span class="badge rounded-pill px-3 py-2 ms-3" style="background-color: #50946c; color: white;">
                                    <i class="bi bi-box-seam me-1"></i>{{ __('In Stock') }}: {{ $product->stock }}
                                </span>
                            </div>

                            <div class="mb-4 p-3 bg-light rounded-3">
                                <i class="bi bi-info-circle me-2 text-muted"></i>
                                <span class="text-secondary">{{ $product->description }}</span>
                            </div>

                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="d-flex gap-2">
                                    <div class="input-group" style="width: 120px;">
                                        <span class="input-group-text bg-light border-secondary">
                                            <i class="bi bi-123"></i>
                                        </span>
                                        <input type="number" name="quantity" min="0.1" step="0.1" max="{{ $product->stock }}" value="1" class="form-control border-secondary">
                                    </div>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                        <i class="bi bi-cart-plus me-2"></i>{{ __('Add to Cart') }}
                                    </button>
                                </div>
                            </form>

                            <a href="{{ url('shop/full') }}" class="btn btn-secondary rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('Back to Shop') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Suggestion Slider --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                    <i class="bi bi-lightning-charge me-2" style="font-size: 1.5rem; color: #50946c;"></i>
                    <h2 class="h4 fw-bold mb-0" style="color: #50946c;">{{ __('You May Also Like') }}</h2>
                </div>
                <div class="card-body">
                    @php
                        $suggestedProducts = \App\Models\Product::where('id', '!=', $product->id)
                            ->inRandomOrder()
                            ->take(8)
                            ->get();
                    @endphp
                    @include('components.product-suggestion-slider', ['suggestedProducts' => $suggestedProducts])
                </div>
            </div>
        </div>
    </div>

    {{-- Similar Products Section --}}
    @php
        $similarProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();
    @endphp
    @if($similarProducts->count())
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0">
                    <div class="card-header bg-white border-bottom-0 pb-0 d-flex align-items-center">
                        <i class="bi bi-grid me-2" style="font-size: 1.5rem; color: #50946c;"></i>
                        <h2 class="h4 fw-bold mb-0" style="color: #50946c;">{{ __('Similar Products') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach($similarProducts as $similar)
                                <div class="col-12 col-sm-6 col-md-3">
                                    @include('components.product-card', ['product' => $similar])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .product-img-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-img-hover:hover {
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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #c52b36;
        border-color: #c52b36;
    }

    /* Unit Tag Styling */
    .unit-tag {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        line-height: 1;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(102, 126, 234, 0.25);
        transition: all 0.2s ease;
    }

    .unit-tag:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.35);
    }

    /* Thumbnail Gallery Styling */
    .thumbnail-gallery {
        margin-top: 1rem;
        padding: 0.5rem;
        background: rgba(248, 249, 250, 0.8);
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    .thumbnail-item {
        position: relative;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        border-radius: 8px;
        padding: 2px;
    }

    .thumbnail-item:hover {
        transform: scale(1.1);
        border-color: #50946c;
        box-shadow: 0 4px 12px rgba(80, 148, 108, 0.3);
    }

    .thumbnail-item.active {
        border-color: #50946c;
        box-shadow: 0 0 0 3px rgba(80, 148, 108, 0.2);
    }

    .thumbnail-item img {
        transition: all 0.3s ease;
    }

    .thumbnail-item:hover img {
        filter: brightness(1.1);
    }

    .thumbnail-item.active img {
        filter: brightness(1.1) contrast(1.05);
    }

    /* Main image transition */
    #mainProductImage {
        transition: opacity 0.3s ease;
    }

    .image-loading {
        opacity: 0.5;
    }
</style>

<script>
function switchMainImage(imageUrl, thumbnailElement) {
    const mainImage = document.getElementById('mainProductImage');
    const allThumbnails = document.querySelectorAll('.thumbnail-item');

    // Add loading state
    mainImage.classList.add('image-loading');

    // Remove active class from all thumbnails
    allThumbnails.forEach(thumb => thumb.classList.remove('active'));

    // Add active class to clicked thumbnail
    thumbnailElement.classList.add('active');

    // Change main image with fade effect
    setTimeout(() => {
        mainImage.src = imageUrl;
        mainImage.onload = function() {
            mainImage.classList.remove('image-loading');
        };
    }, 150);
}

// Initialize gallery on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add keyboard navigation for images
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    let currentIndex = 0;

    // Find active thumbnail
    thumbnails.forEach((thumb, index) => {
        if (thumb.classList.contains('active')) {
            currentIndex = index;
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' && currentIndex > 0) {
            currentIndex--;
            thumbnails[currentIndex].click();
        } else if (e.key === 'ArrowRight' && currentIndex < thumbnails.length - 1) {
            currentIndex++;
            thumbnails[currentIndex].click();
        }
    });

    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    const mainImageContainer = document.getElementById('mainImageContainer');

    mainImageContainer.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    mainImageContainer.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0 && currentIndex < thumbnails.length - 1) {
                // Swipe left - next image
                currentIndex++;
                thumbnails[currentIndex].click();
            } else if (diff < 0 && currentIndex > 0) {
                // Swipe right - previous image
                currentIndex--;
                thumbnails[currentIndex].click();
            }
        }
    }
});
</script>
@endsection

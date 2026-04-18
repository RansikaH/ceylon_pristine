<div class="card h-100 border-0 shadow-sm product-card overflow-hidden">
    @php
        $imgPath = $product->image_url ? 'product-images/' . $product->image_url : null;
        $imageSrc = asset('product-images/default_product.png');
        if ($imgPath && file_exists(public_path($imgPath))) {
            $imageSrc = asset($imgPath);
        }
    @endphp
    
    <!-- Product Image Container -->
    <div class="position-relative product-image-container">
        <div class="product-image-inner">
            <img 
                src="{{ $imageSrc }}" 
                alt="{{ $product->name }}" 
                class="product-img"
                loading="lazy"
                onerror="this.onerror=null; this.src='{{ asset('product-images/default_product.png') }}'"
                style="max-height: 240px;"
            >
        </div>
        
        @if(!empty($product->is_new))
            <span class="badge bg-secondary position-absolute top-2 start-2 px-2 py-1 fw-normal">
                <i class="bi bi-star-fill me-1"></i> New
            </span>
        @endif
        
        @if($product->hasDiscount())
            <span class="badge bg-danger position-absolute top-2 end-2 px-2 py-1 fw-bold">
                {{ number_format($product->discount_percentage, 0) }}% OFF
            </span>
        @endif
        
        <!-- Quick View Button (shown on hover) -->
        <div class="quick-view-overlay d-flex align-items-center justify-content-center">
            <a href="{{ route('shop.product', $product) }}" class="btn btn-light btn-sm rounded-pill px-3">
                Quick View <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Product Details -->
    <div class="card-body p-3 d-flex flex-column h-100" style="padding: 1.25rem 1.25rem 1.5rem !important;">
        <!-- Category -->
        @if($product->category)
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="text-uppercase text-muted small fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                    {{ $product->category->name }}
                </div>
                <div class="unit-tag">
                    {{ $product->unit_display }}
                </div>
            </div>
        @else
            <div class="mb-2">
                <div class="unit-tag">
                    {{ $product->unit_display }}
                </div>
            </div>
        @endif
        
        <!-- Product Name -->
        <h3 class="h6 mb-1 product-title">
            <a href="{{ route('shop.product', $product) }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h3>
        
        <!-- Product Description -->
        @if(!empty($product->description))
            <p class="small text-muted mb-2 product-description">
                {{ Str::limit(strip_tags($product->description), 80) }}
            </p>
        @endif
        
        <!-- Price -->
        <div class="mt-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                    @if($product->hasDiscount())
                        <div class="mb-1">
                            <span class="text-muted text-decoration-line-through small">
                                LKR {{ number_format($product->price, 2) }}
                            </span>
                        </div>
                        <span class="fw-bold text-danger fs-5">
                            LKR {{ number_format($product->discounted_price, 2) }}
                        </span>
                        <span class="badge bg-success ms-1 small">
                            Save LKR {{ number_format($product->price - $product->discounted_price, 2) }}
                        </span>
                    @else
                        <span class="fw-bold text-dark fs-5">
                            LKR {{ number_format($product->price, 2) }}
                        </span>
                    @endif
                </div>
                
                <!-- Add to Cart Button -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1 add-to-cart-btn" 
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                </form>
            </div>
            
            <!-- View Details Button -->
            <a href="{{ route('shop.product', $product) }}" class="btn btn-danger w-100 btn-hover-scale" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                View Details <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

<style>
.product-card {
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #f0f0f0;
    overflow: hidden;
    background: #fff;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border-color: #e0e0e0;
}

/* Product Image Styles */
.product-image-container {
    height: 280px;
    background: #f9fafb;
    position: relative;
    overflow: hidden;
    border-radius: 10px 10px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image-inner {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    transition: transform 0.3s ease;
    position: relative;
}

.product-card:hover .product-image-inner {
    transform: scale(1.05);
}

.product-img {
    max-height: 100%;
    max-width: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: all 0.3s ease;
    transform-origin: center;
}

/* Quick View Overlay */
.quick-view-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(2px);
}

.product-card:hover .quick-view-overlay {
    opacity: 1;
}

/* Product Title */
.product-title {
    margin-bottom: 0.5rem;
    transition: color 0.2s ease;
    font-weight: 500;
    line-height: 1.4;
    font-size: 1rem;
    min-height: 2.8em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Product Description */
.product-description {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.5rem;
    line-height: 1.3;
    min-height: 2.2em;
    font-size: 0.85rem;
}

.product-title a:hover {
    color: #374151 !important;
    text-decoration: underline !important;
}

/* Buttons */
.btn-hover-scale {
    transition: all 0.2s ease;
}

.btn-hover-scale:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
}

.add-to-cart-btn {
    transition: all 0.2s ease;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.add-to-cart-btn:hover {
    background-color: #dc3545 !important;
    color: white !important;
    transform: scale(1.1);
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .product-card {
        margin-bottom: 1.5rem;
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .product-image-container {
        height: 200px;
    }
    
    .product-title {
        font-size: 0.95rem;
    }
}

/* Animation for add to cart */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-10px);}
    60% {transform: translateY(-5px);}
}

.add-to-cart-btn:active i {
    animation: bounce 0.6s ease;
}

/* Unit Tag Styling */
.unit-tag {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    line-height: 1;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
    transition: all 0.2s ease;
}

.unit-tag:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(102, 126, 234, 0.3);
}
</style>

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add to cart animation
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.remove('bi-cart-plus');
            icon.classList.add('bi-check-lg');
            
            setTimeout(() => {
                icon.classList.remove('bi-check-lg');
                icon.classList.add('bi-cart-plus');
            }, 2000);
        });
    });
});
</script>
@endpush

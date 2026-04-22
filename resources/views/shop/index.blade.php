@extends('layouts.app')

@section('title', 'Shop | ' . config('app.name'))

@push('styles')
<style>
    .product-grid {
        padding: 1rem 0;
    }

    .cta-banner {
        background: linear-gradient(135deg, #50946c 0%, #3a6e51 100%);
        border-radius: 12px;
        padding: 3rem 2rem;
        margin: 4rem 0;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.6;
    }

    .btn-cta {
        background: #fff;
        color: #50946c;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: 2px solid #fff;
    }

    .btn-cta:hover {
        background: transparent;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
        }
    }

    .category-card .hover-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .category-card:hover .hover-card {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(80, 148, 108, 0.15);
        border-color: #50946c;
    }

    .category-card:hover .category-icon i {
        color: #3d7254;
        transform: scale(1.1);
        transition: all 0.3s ease;
    }

    .category-card:hover .card-title {
        color: #50946c;
    }
</style>
@endpush

@section('content')
@include('layouts.partials.banner-slider-owl')

<!-- Categories Section -->
<div class="py-4 bg-light">
    <div class="container py-3">
        <div class="text-center mb-4">
            <span class="text-uppercase text-muted d-block mb-2" style="letter-spacing: 2px; font-size: 0.9rem;">Shop by Category</span>
            <h2 class="display-6 fw-bold mb-3" style="color: #2c3e50;">Browse Our Categories</h2>
        </div>

        <div class="row g-3">
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('shop.full', ['category' => $category->id]) }}" class="text-decoration-none category-card">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-3">
                                    <div class="category-icon mb-2">
                                    @php
                                        $icon = 'bi-basket2';
                                        $categoryName = strtolower($category->name);

                                        // Assign icons based on category names
                                        if (strpos($categoryName, 'vegetabl') !== false) {
                                            $icon = 'bi-flower1';
                                        } elseif (strpos($categoryName, 'fruit') !== false) {
                                            $icon = 'bi-apple';
                                        } elseif (strpos($categoryName, 'dairy') !== false || strpos($categoryName, 'milk') !== false) {
                                            $icon = 'bi-cup-straw';
                                        } elseif (strpos($categoryName, 'meat') !== false || strpos($categoryName, 'chicken') !== false) {
                                            $icon = 'bi-egg-fried';
                                        } elseif (strpos($categoryName, 'rice') !== false) {
                                            $icon = 'bi-cup-hot';
                                        } elseif (strpos($categoryName, 'bread') !== false || strpos($categoryName, 'bakery') !== false) {
                                            $icon = 'bi-bread-slice';
                                        } elseif (strpos($categoryName, 'spice') !== false) {
                                            $icon = 'bi-droplet';
                                        } elseif (strpos($categoryName, 'oil') !== false) {
                                            $icon = 'bi-droplet-half';
                                        } elseif (strpos($categoryName, 'fish') !== false) {
                                            $icon = 'bi-fish';
                                        } elseif (strpos($categoryName, 'herb') !== false) {
                                            $icon = 'bi-flower2';
                                        } elseif (strpos($categoryName, 'nut') !== false) {
                                            $icon = 'bi-circle-square';
                                        }
                                    @endphp
                                    <i class="bi {{ $icon }}" style="font-size: 2rem; color: #50946c;"></i>
                                </div>
                                    <h5 class="card-title fw-semibold mb-1" style="color: #2c3e50; font-size: 0.9rem;">{{ $category->name }}</h5>
                                    <p class="card-text text-muted small mb-0">{{ $category->products_count ?? 0 }} items</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">No categories available at the moment.</p>
                </div>
            @endif
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('shop.full') }}" class="btn btn-outline-success rounded-pill px-4">
                View All Products <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="py-5">
    <div class="container py-4">
    <div class="text-center mb-5 pt-4">
        <span class="text-uppercase text-muted d-block mb-2" style="letter-spacing: 2px; font-size: 0.9rem;">Fresh From Our Farm</span>
        <h1 class="display-5 fw-bold mb-3" style="color: #2c3e50;">Discover Our Premium Selection</h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">Handpicked, sustainably grown, and delivered with care to bring nature's finest to your table.</p>
    </div>

    <div class="row g-3 g-md-4 g-lg-5 mt-2 mb-4">
        @foreach($products as $product)
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="h-100">
                    @include('components.product-card', ['product' => $product])
                </div>
            </div>
        @endforeach
    </div>

    @if($showViewMore)
    <div class="text-center mt-5">
        <a href="{{ route('shop.full') }}" class="btn btn-danger btn-lg px-5 py-3 fw-semibold" style="background: linear-gradient(135deg, #c52b36 0%, #9e1f29 100%); border: none; border-radius: 50px; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(197, 43, 54, 0.3);">
            {{ __('Explore More Products') }} <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
    @endif
    </div>
</div>

<div class="py-5 bg-light">
    <div class="container py-4">
    @include('components.about-section')
    </div>
</div>

@include('components.review-slider')


@push('scripts')
<script>
    // Lazy loading for product images
    document.addEventListener('DOMContentLoaded', function() {
        const lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

        if ('IntersectionObserver' in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.classList.remove('lazy');
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        }

        // Create floating buttons
        const container = document.createElement('div');
        container.style.cssText = 'position: fixed !important; bottom: 20px !important; right: 20px !important; z-index: 99999 !important; display: flex !important; flex-direction: column !important; gap: 10px !important;';

        // Back to top button
        const backBtn = document.createElement('button');
        backBtn.innerHTML = '↑';
        backBtn.style.cssText = 'width: 60px !important; height: 60px !important; background: #1f2937 !important; color: white !important; border: 3px solid white !important; border-radius: 50% !important; font-size: 24px !important; font-weight: bold !important; cursor: pointer !important; box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important; transition: all 0.3s ease !important;';
        backBtn.onclick = function() { window.scrollTo({top: 0, behavior: 'smooth'}); };
        backBtn.title = 'Back to top';

        // Facebook button
        const fbBtn = document.createElement('a');
        fbBtn.innerHTML = 'f';
        fbBtn.href = 'https://facebook.com';
        fbBtn.target = '_blank';
        fbBtn.style.cssText = 'width: 60px !important; height: 60px !important; background: #1877f2 !important; color: white !important; border: 3px solid white !important; border-radius: 50% !important; font-size: 24px !important; font-weight: bold !important; cursor: pointer !important; box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important; transition: all 0.3s ease !important; display: flex !important; align-items: center !important; justify-content: center !important; text-decoration: none !important;';
        fbBtn.title = 'Facebook';

        // Email button
        const emailBtn = document.createElement('a');
        emailBtn.innerHTML = '✉';
        emailBtn.href = 'mailto:info@ceylonmoms.com';
        emailBtn.style.cssText = 'width: 60px !important; height: 60px !important; background: #dc2626 !important; color: white !important; border: 3px solid white !important; border-radius: 50% !important; font-size: 24px !important; font-weight: bold !important; cursor: pointer !important; box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important; transition: all 0.3s ease !important; display: flex !important; align-items: center !important; justify-content: center !important; text-decoration: none !important;';
        emailBtn.title = 'Email';

        // Call button
        const callBtn = document.createElement('a');
        callBtn.innerHTML = '📞';
        callBtn.href = 'tel:+94123456789';
        callBtn.style.cssText = 'width: 60px !important; height: 60px !important; background: #16a34a !important; color: white !important; border: 3px solid white !important; border-radius: 50% !important; font-size: 20px !important; font-weight: bold !important; cursor: pointer !important; box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important; transition: all 0.3s ease !important; display: flex !important; align-items: center !important; justify-content: center !important; text-decoration: none !important;';
        callBtn.title = 'Call us';

        // Add hover effects
        [backBtn, fbBtn, emailBtn, callBtn].forEach(btn => {
            btn.onmouseover = function() { this.style.transform = 'scale(1.1)'; };
            btn.onmouseout = function() { this.style.transform = 'scale(1)'; };
        });

        // Add buttons to container
        container.appendChild(backBtn);
        container.appendChild(fbBtn);
        container.appendChild(emailBtn);
        container.appendChild(callBtn);

        // Add to document
        document.body.appendChild(container);

        console.log('Floating buttons added successfully!');
    });
</script>
@endpush
@endsection

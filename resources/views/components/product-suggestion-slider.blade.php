{{-- Product Suggestion Slider --}}
@push('swiper-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
@endpush

<div class="product-suggestion-slider-container py-5">
    <h2 class="text-center mb-4 fw-bold" style="color: #50946c;">You May Also Like</h2>
    <div class="swiper product-suggestion-swiper">
        <div class="swiper-wrapper">
            @foreach($suggestedProducts as $suggested)
                <div class="swiper-slide">
                    @include('components.product-card', ['product' => $suggested])
                </div>
            @endforeach
        </div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

@push('swiper-js')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.product-suggestion-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                576: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                992: { slidesPerView: 4 }
            }
        });
    });
</script>
@endpush

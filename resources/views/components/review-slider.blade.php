<div class="container">
    <div class="review-slider-container py-5">
        <h2 class="text-center mb-4 fw-bold">What Our Customers Say</h2>
        <div class="swiper review-swiper">
        <div class="swiper-wrapper">
            <!-- Example static reviews, replace with dynamic if available -->
            <div class="swiper-slide">
                <div class="card shadow-sm p-4 border-0">
                    <div class="mb-2"><strong>Priya Sharma</strong></div>
                    <div class="mb-2 text-warning">★★★★★</div>
                    <p>"Fresh veggies delivered on time every week! Highly recommended."</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="card shadow-sm p-4 border-0">
                    <div class="mb-2"><strong>Rahul Verma</strong></div>
                    <div class="mb-2 text-warning">★★★★★</div>
                    <p>"Great quality and variety. My family loves shopping here."</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="card shadow-sm p-4 border-0">
                    <div class="mb-2"><strong>Anjali Patel</strong></div>
                    <div class="mb-2 text-warning">★★★★★</div>
                    <p>"Easy to order, fast delivery, and super fresh products!"</p>
                </div>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination mt-3"></div>
    </div>
    </div>
</div>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.review-swiper', {
            loop: true,
            autoplay: { delay: 4000 },
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                576: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
            }
        });
    });
</script>

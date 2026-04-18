<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner shadow-lg border" style="border-color: #e0e0e0;">
        <div class="carousel-item active">
    <section class="pt-5 pb-5" style="background: linear-gradient(90deg, #e6f4ee 0%, #fff 100%); border-bottom: 1px solid #e0e0e0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                    <h1 class="display-4 fw-bold mb-3" style="color: #50946c;">Experience the Freshness of Nature</h1>
                    <p class="lead mb-4" style="color: #222;">Discover a vibrant selection of hand-picked, locally sourced vegetables delivered straight to your door. Taste the difference in every bite and nourish your family with the best nature has to offer.<br><span style='color:#c52b36; font-weight:600;'>Order today for same-day delivery!</span></p>
                    <a href="/" class="btn px-4 py-2 btn-lg fw-bold" style="background-color: #c52b36; color: #fff; border: none; box-shadow: 0 2px 8px rgba(197,43,54,0.08); transition: background 0.2s;" onmouseover="this.style.backgroundColor='#50946c'" onmouseout="this.style.backgroundColor='#c52b36'">Shop Fresh Vegetables <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('banner-images/1.png') }}" alt="Basket of Fresh Vegetables" class="img-fluid" style="max-width: 95%;">
                </div>
            </div>
        </div>
    </section>
</div>
        <div class="carousel-item">
    <section class="pt-5 pb-5" style="background: linear-gradient(90deg, #fff 0%, #e6f4ee 100%); border-bottom: 1px solid #e0e0e0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                            <h1 class="display-4 fw-bold mb-3" style="color: #c52b36;">Seasonal Specials</h1>
                            <p class="lead mb-2" style="color: #222;">Enjoy exclusive discounts on this season's best produce.<br><span style="color: #50946c; font-weight: 600;">Limited time only!</span></p>
<ul class="mb-4 ps-4" style="color: #222; font-size: 1.1rem;">
    <li>Hand-selected, peak-freshness vegetables every week</li>
    <li>Unique varieties you won't find in supermarkets</li>
    <li>Support local farmers and sustainable agriculture</li>
    <li style="color:#c52b36;">Perfect for healthy family meals</li>
</ul>
<p class="mb-4" style="color:#222; font-size:1rem;">Don't miss out—stock is limited and changes with the season. Shop now to get the freshest picks delivered to your door!</p>
                            <a href="/shop" class="btn px-4 py-2 btn-lg fw-bold" style="background-color: #50946c; color: #fff; border: none; box-shadow: 0 2px 8px rgba(80,148,108,0.08); transition: background 0.2s;" onmouseover="this.style.backgroundColor='#c52b36'" onmouseout="this.style.backgroundColor='#50946c'">Shop Specials <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('banner-images/2.png') }}" alt="Seasonal Vegetables" class="img-fluid" style="max-width: 95%;">
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="carousel-item">
            <section class="pt-5 pb-5" style="background: linear-gradient(90deg, #fffbe7 0%, #e6f4ee 100%); border-bottom: 1px solid #e0e0e0;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                            <h1 class="display-4 fw-bold mb-3" style="color: #50946c;">Pure Goodness in Every Box</h1>
                            <p class="lead mb-4" style="color: #222;">We carefully select and pack only the freshest, highest-quality vegetables—straight from the farm to your kitchen. Enjoy a variety of greens, roots, and seasonal picks that help you eat healthy every day.<br><span style='color:#c52b36; font-weight:600;'>Zero compromise on taste and nutrition.</span></p>
                            <a href="/about" class="btn px-4 py-2 btn-lg fw-bold" style="background-color: #c52b36; color: #fff; border: none; box-shadow: 0 2px 8px rgba(197,43,54,0.08); transition: background 0.2s;" onmouseover="this.style.backgroundColor='#50946c'" onmouseout="this.style.backgroundColor='#c52b36'">Learn More <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('banner-images/3.png') }}" alt="Assorted Fresh Vegetables" class="img-fluid" style="max-width: 95%;">
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

{{-- Owl Carousel Banner Slider --}}
@push('owl-carousel-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
/* Custom slider navigation styles */
.owl-carousel .owl-nav {
    position: absolute;
    bottom: 20px;
    top: auto;
    width: 100%;
    display: flex;
    justify-content: center;
    pointer-events: none;
}

.owl-carousel .owl-nav button {
    width: 40px;
    height: 40px;
    background: #e0e0e0;
    border-radius: 8px;
    margin: 0 5px;
    pointer-events: all;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.owl-carousel .owl-nav button:hover {
    background: #d0d0d0;
}

.owl-carousel .owl-nav button span {
    font-size: 16px;
    color: #666;
    font-weight: bold;
}

.owl-carousel .owl-dots {
    position: absolute;
    bottom: 5px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
}

.owl-carousel .owl-dots button {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ccc;
    margin: 0 4px;
    transition: all 0.3s ease;
}

.owl-carousel .owl-dots button.active {
    background: #888;
    width: 8px;
    height: 8px;
}

@media (max-width: 768px) {
    .owl-carousel .owl-nav {
        padding-right: 20px;
    }
}
</style>
@endpush

<div class="owl-carousel owl-theme">
    @if($sliders->count() > 0)
        @foreach($sliders as $slider)
            <div class="item">
                <div class="position-relative" style="background: white; min-height: 500px;">

                    <div class="container h-100">
                        <div class="row align-items-center h-100">
                            <div class="col-md-6 text-center text-md-start">
                                <h1 class="display-4 fw-bold mb-4" style="color: #50946c; font-size: 3rem;">{{ $slider->main_topic }}</h1>
                                <div class="lead mb-4" style="color: #222; font-size: 1.2rem;">{!! $slider->description !!}</div>
                                @if($slider->subtopic)
                                    <div class="mb-4" style="color: #c52b36; font-weight: 600; font-size: 1.1rem;">{{ $slider->subtopic }}</div>
                                @endif
                                @if($slider->button_text && $slider->button_url)
                                    <a href="{{ $slider->button_url }}" class="btn px-5 py-3 btn-lg fw-bold" style="background-color: #c52b36; color: #fff; border: none; border-radius: 8px; font-size: 1.1rem; transition: all 0.3s ease;">{{ $slider->button_text }} <i class="bi bi-arrow-right ms-2"></i></a>
                                @endif
                            </div>
                            <div class="col-md-6 text-center">
                                <img src="{{ $slider->image_url }}" alt="{{ $slider->main_topic }}" class="img-fluid" style="max-width: 90%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        {{-- Fallback to default sliders if no sliders exist --}}
        <div class="item">
            <div class="position-relative" style="background: white; min-height: 500px;">

                <div class="container h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-md-6 text-center text-md-start">
                            <h1 class="display-4 fw-bold mb-4" style="color: #50946c; font-size: 3rem;">Experience the Freshness of Nature</h1>
                            <p class="lead mb-4" style="color: #222; font-size: 1.2rem;">Discover a vibrant selection of hand-picked, locally sourced vegetables delivered straight to your door. Taste the difference in every bite and nourish your family with the best nature has to offer.</p>
                            <p class="mb-4" style="color: #c52b36; font-weight: 600; font-size: 1.1rem;">Order today for same-day delivery!</p>
                            <a href="/shop" class="btn px-5 py-3 btn-lg fw-bold" style="background-color: #c52b36; color: #fff; border: none; border-radius: 8px; font-size: 1.1rem; transition: all 0.3s ease;">Shop Fresh Vegetables <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('banner-images/1.png') }}" alt="Basket of Fresh Vegetables" class="img-fluid" style="max-width: 90%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('owl-carousel-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function(){
  $('.owl-carousel').owlCarousel({
      loop:true,
      margin:24,
      nav:true,
      dots:true,
      autoplay:true,
      autoplayTimeout:5000,
      autoplayHoverPause:true,
      animateOut: 'fadeOut',
      animateIn: 'fadeIn',
      smartSpeed: 700,
      responsive:{
          0:{ items:1 },
          768:{ items:1 }
      },
      navText: [
        '<span class="owl-nav-prev">&#8592;</span>',
        '<span class="owl-nav-next">&#8594;</span>'
      ]
  });
});
</script>
@endpush

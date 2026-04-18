This folder is for storing banner images used in the homepage/shop slider.

- Place your vegetable-related banner images here (e.g., banner1.jpg, banner2.jpg, banner3.jpg).
- To use them in your Blade templates, reference with:
  <img src="/banner-images/banner1.jpg" ... >
- Recommended size: 1200x500px or larger, landscape orientation for best results.

Example usage in Blade:
<img src="{{ asset('banner-images/banner1.jpg') }}" alt="Banner Image" class="img-fluid">

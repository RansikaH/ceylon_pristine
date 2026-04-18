<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'main_topic' => 'Experience the Freshness of Nature',
                'description' => 'Discover a vibrant selection of hand-picked, locally sourced vegetables delivered straight to your door. Taste the difference in every bite and nourish your family with the best nature has to offer.',
                'subtopic' => 'Farm Fresh Vegetables',
                'button_text' => 'Shop Fresh Vegetables',
                'button_url' => '/shop/full?category=1',
                'image' => 'vegetables-slider.jpg',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'main_topic' => 'Premium Quality Fruits',
                'description' => 'Indulge in nature\'s sweetest offerings with our premium selection of fresh fruits. Picked at peak ripeness for maximum flavor and nutrition.',
                'subtopic' => 'Sweet & Juicy',
                'button_text' => 'Explore Fruits',
                'button_url' => '/shop/full?category=2',
                'image' => 'fruits-slider.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'main_topic' => 'Organic Dairy Products',
                'description' => 'Start your day with our farm-fresh dairy products. From creamy milk to artisanal cheeses, we bring you the best of local dairy farms.',
                'subtopic' => 'Farm to Table',
                'button_text' => 'Shop Dairy',
                'button_url' => '/shop/full?category=3',
                'image' => 'dairy-slider.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'main_topic' => 'Artisanal Bakery Goods',
                'description' => 'Freshly baked breads, pastries, and more made with love and the finest ingredients. Perfect for every meal of the day.',
                'subtopic' => 'Freshly Baked Daily',
                'button_text' => 'Browse Bakery',
                'button_url' => '/shop/full?category=4',
                'image' => 'bakery-slider.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $sliderData) {
            Slider::create($sliderData);
        }
    }
}

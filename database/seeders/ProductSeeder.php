<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::insert([
            // Leafy Greens
            [
                'name' => 'Spinach',
                'description' => 'Fresh and organic spinach leaves, rich in iron and vitamins.',
                'price' => 250.00,
                'image' => 'spinach.jpg',
                'stock' => 100,
                'category_id' => 1
            ],
            [
                'name' => 'Gotukola',
                'description' => 'Traditional Sri Lankan gotukola, packed with nutrients.',
                'price' => 300.00,
                'image' => 'gotukola.jpg',
                'stock' => 75,
                'category_id' => 1
            ],
            [
                'name' => 'Mukunuwenna',
                'description' => 'Fresh mukunuwenna leaves, perfect for traditional dishes.',
                'price' => 280.00,
                'image' => 'mukunuwenna.jpg',
                'stock' => 60,
                'category_id' => 1
            ],
            
            // Root Vegetables
            [
                'name' => 'Carrot',
                'description' => 'Crunchy and sweet carrots, great for salads and cooking.',
                'price' => 350.00,
                'image' => 'carrot.jpg',
                'stock' => 80,
                'category_id' => 2
            ],
            [
                'name' => 'Beetroot',
                'description' => 'Organic beetroot, perfect for juices and salads.',
                'price' => 400.00,
                'image' => 'beetroot.jpg',
                'stock' => 45,
                'category_id' => 2
            ],
            [
                'name' => 'Radish',
                'description' => 'Fresh white radish, ideal for sambols and curries.',
                'price' => 280.00,
                'image' => 'radish.jpg',
                'stock' => 65,
                'category_id' => 2
            ],
            
            // Fruits
            [
                'name' => 'Tomato',
                'description' => 'Juicy red tomatoes, perfect for salads and cooking.',
                'price' => 320.00,
                'image' => 'tomato.jpg',
                'stock' => 120,
                'category_id' => 3
            ],
            [
                'name' => 'Cucumber',
                'description' => 'Fresh cucumbers, great for salads and pickles.',
                'price' => 180.00,
                'image' => 'cucumber.jpg',
                'stock' => 90,
                'category_id' => 3
            ],
            [
                'name' => 'Bitter Gourd',
                'description' => 'Fresh bitter gourd, known for its health benefits.',
                'price' => 380.00,
                'image' => 'bitter-gourd.jpg',
                'stock' => 40,
                'category_id' => 3
            ],
            
            // Legumes
            [
                'name' => 'Green Peas',
                'description' => 'Sweet green peas, perfect for curries and rice dishes.',
                'price' => 420.00,
                'image' => 'peas.jpg',
                'stock' => 60,
                'category_id' => 4
            ],
            [
                'name' => 'Long Beans',
                'description' => 'Tender long beans, great for stir-fries and curries.',
                'price' => 350.00,
                'image' => 'long-beans.jpg',
                'stock' => 55,
                'category_id' => 4
            ],
            
            // Gourds
            [
                'name' => 'Bottle Gourd',
                'description' => 'Tender bottle gourds, perfect for curries and soups.',
                'price' => 380.00,
                'image' => 'bottle-gourd.jpg',
                'stock' => 70,
                'category_id' => 5
            ],
            [
                'name' => 'Snake Gourd',
                'description' => 'Fresh snake gourd, great for stir-fries and curries.',
                'price' => 350.00,
                'image' => 'snake-gourd.jpg',
                'stock' => 50,
                'category_id' => 5
            ],
            
            // Herbs and Spices
            [
                'name' => 'Curry Leaves',
                'description' => 'Aromatic curry leaves, essential for Sri Lankan cooking.',
                'price' => 150.00,
                'image' => 'curry-leaves.jpg',
                'stock' => 200,
                'category_id' => 6
            ],
            [
                'name' => 'Rampe',
                'description' => 'Pandan leaves, adds wonderful aroma to rice and curries.',
                'price' => 100.00,
                'image' => 'rampe.jpg',
                'stock' => 150,
                'category_id' => 6
            ],
            
            // Exotic Vegetables
            [
                'name' => 'Kangkung',
                'description' => 'Water spinach, great for stir-fries and curries.',
                'price' => 280.00,
                'image' => 'kangkung.jpg',
                'stock' => 40,
                'category_id' => 7
            ],
            [
                'name' => 'Brinjal (Eggplant)',
                'description' => 'Fresh purple brinjals, perfect for curries and sambols.',
                'price' => 320.00,
                'image' => 'brinjal.jpg',
                'stock' => 60,
                'category_id' => 7
            ],
            
            // Local Specialties
            [
                'name' => 'Kohila',
                'description' => 'Kohila leaves, a traditional Sri Lankan green.',
                'price' => 270.00,
                'image' => 'kohila.jpg',
                'stock' => 35,
                'category_id' => 8
            ],
            [
                'name' => 'Manioc (Cassava)',
                'description' => 'Fresh cassava, great for boiling or making chips.',
                'price' => 200.00,
                'image' => 'cassava.jpg',
                'stock' => 45,
                'category_id' => 8
            ]
        ]);
    }
}

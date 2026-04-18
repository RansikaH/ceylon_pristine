<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::insert([
            ['name' => 'Leafy Greens', 'slug' => 'leafy-greens'],
            ['name' => 'Root Vegetables', 'slug' => 'root-vegetables'],
            ['name' => 'Fruit Vegetables', 'slug' => 'fruit-vegetables'],
            ['name' => 'Legumes', 'slug' => 'legumes'],
            ['name' => 'Gourds', 'slug' => 'gourds'],
        ]);
    }
}

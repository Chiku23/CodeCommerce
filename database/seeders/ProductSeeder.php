<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have some categories and brands first
        if (Category::count() === 0) {
            Category::factory()->count(5)->create();
        }

        if (Brand::count() === 0) {
            Brand::factory()->count(5)->create();
        }

        // Create 20 products linked to random categories and brands
        Product::factory()->count(20)->create();
    }
}

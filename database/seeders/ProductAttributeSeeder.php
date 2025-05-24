<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\Product;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        // Ensure products exist before seeding attributes
        if (Product::count() === 0) {
            $this->command->info('No products found, creating some first...');
            \App\Models\Product::factory()->count(10)->create();
        }

        // Create attributes for existing products
        ProductAttribute::factory()->count(50)->create();
    }
}

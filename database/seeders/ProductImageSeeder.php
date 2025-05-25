<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        Product::all()->each(function ($product) {
            ProductImage::factory()->count(1)->create([
                'product_id' => $product->id,
            ]);
        });
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductAttributeFactory extends Factory
{
    protected $model = \App\Models\ProductAttribute::class;

    public function definition()
    {
        // Make sure to get an existing product ID to satisfy foreign key constraint
        $productId = Product::inRandomOrder()->first()?->id;

        return [
            'product_id' => $productId,
            'key' => $this->faker->randomElement(['color', 'size', 'material', 'weight', 'brand', 'model']),
            'value' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

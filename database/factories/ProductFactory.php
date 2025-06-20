<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Category;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->words(3, true); // e.g. "Awesome Steel Chair"
        $price = $this->faker->randomFloat(2, 10, 50); // Generate a price between 10.00 and 1000.00

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? null,
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'compare_at_price' => $this->faker->boolean(30) ? $this->faker->randomFloat(2, $price + 10, $price + 100) : null, // 30% chance to have a compare_at_price higher than price
            'cost_price' => $this->faker->boolean(50) ? $this->faker->randomFloat(2, $price * 0.5, $price * 0.8) : null, // 50% chance to have a cost price, less than actual price
            'sku' => Str::upper(Str::random(3)) . '-' . $this->faker->unique()->randomNumber(4), // Unique SKU like 'ABC-12345'
            'quantity' => $this->faker->numberBetween(0, 500), // Quantity in stock
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']), // Random status
            'is_featured' => $this->faker->boolean(20), // 20% chance to be featured
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

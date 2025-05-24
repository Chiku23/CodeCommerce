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
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? null,
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

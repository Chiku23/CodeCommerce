<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;

class ProductReviewFactory extends Factory
{
    protected $model = \App\Models\ProductReview::class;

    public function definition()
    {
        $productId = Product::inRandomOrder()->first()?->id;
        $userId = User::inRandomOrder()->first()?->id;

        return [
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional()->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

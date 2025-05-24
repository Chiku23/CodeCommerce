<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;

class ProductReviewSeeder extends Seeder
{
    public function run()
    {
        if (Product::count() === 0) {
            $this->command->info('No products found, creating some...');
            Product::factory()->count(10)->create();
        }

        if (User::count() === 0) {
            $this->command->info('No users found, creating some...');
            User::factory()->count(10)->create();
        }

        ProductReview::factory()->count(50)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        // Create 10 dummy brands
        Brand::factory()->count(10)->create();
    }
}

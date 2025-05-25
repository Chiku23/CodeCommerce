<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Support\Facades\Storage; // Required for saving the downloaded image
use Illuminate\Support\Str; // For generating unique filenames

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        // 1. Generate the URL for the external image service
        //    Using Picsum Photos as an example.
        //    Faker's imageUrl() will try to build a URL.
        //    The '{width}' and '{height}' placeholders are specific to some services like Picsum.
        $externalImageUrl = 'https://picsum.photos/100/100';

        // 2. Define the target directory within your storage disk
        $targetDirectory = 'shop/product-images';

        // 3. Generate a unique filename for the downloaded image
        $imageName = Str::uuid()->toString() . '.jpg'; // Using UUID for guaranteed uniqueness

        // 4. Full path where the image will be saved on your server
        $localPath = Storage::disk('public')->path($targetDirectory . '/' . $imageName);

        // Ensure the local directory exists
        // Storage::putFileAs() typically handles directory creation, but explicit mkdir for imageUrl is safer.
        if (!is_dir(dirname($localPath))) {
            mkdir(dirname($localPath), 0755, true);
        }

        // 5. Download the image from the external URL
        //    This is the part that will fail if you have network/DNS/cURL issues.
        //    Using file_get_contents is simpler but requires allow_url_fopen=On.
        //    Using cURL is more robust but requires the cURL extension.
        $imageData = @file_get_contents($externalImageUrl); // @ suppresses warnings, but errors will still happen

        $path = null; // Initialize path

        if ($imageData === false) {
            $fallbackImageName = 'fallback_' . Str::uuid()->toString() . '.jpg';
            $fakeImage = \Illuminate\Http\UploadedFile::fake()->image($fallbackImageName, 100, 100);
            $path = Storage::disk('public')->putFileAs($targetDirectory, $fakeImage, $fallbackImageName);
        } else {
            // If download was successful, save the image data to the local file
            if (file_put_contents($localPath, $imageData)) {
                $path = $targetDirectory . '/' . $imageName; // Path relative to the disk root
            } else {
                // Fallback again if saving downloaded data fails (e.g., permissions)
                $fallbackImageName = 'fallback_save_fail_' . Str::uuid()->toString() . '.jpg';
                $fakeImage = \Illuminate\Http\UploadedFile::fake()->image($fallbackImageName, 100, 100);
                $path = Storage::disk('public')->putFileAs($targetDirectory, $fakeImage, $fallbackImageName);
            }
        }

        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'path' => $path,
            'is_main' => true, // Set true - All are main image
        ];
    }
}
<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductListing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()
            ->count(10)
            ->create()
            ->each(function (Product $product) {
                $images = ProductImage::factory()->count(3)->make()->toArray();
                $images[0]['is_primary'] = true;
                $product->images()->createMany($images);

                $listings = ProductListing::factory()->count(2)->make();
                $product->listings()->createMany($listings->toArray());
            });
    }
}

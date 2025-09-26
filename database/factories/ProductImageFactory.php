<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(800, 800, 'fashion', true),
            'position' => $this->faker->numberBetween(0, 4),
            'is_primary' => false,
        ];
    }
}

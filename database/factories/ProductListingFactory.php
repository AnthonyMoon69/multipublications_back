<?php

namespace Database\Factories;

use App\Enums\ListingPlatform;
use App\Models\ProductListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductListing>
 */
class ProductListingFactory extends Factory
{
    protected $model = ProductListing::class;

    public function definition(): array
    {
        $platform = $this->faker->randomElement(ListingPlatform::cases());

        return [
            'platform' => $platform->value,
            'external_id' => $this->faker->uuid(),
            'url' => $this->faker->url(),
            'listed_at' => $this->faker->optional()->dateTimeBetween('-1 year'),
            'sold_at' => $this->faker->optional()->dateTimeBetween('-6 months'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

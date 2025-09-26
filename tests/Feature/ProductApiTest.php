<?php

namespace Tests\Feature;

use App\Enums\ConditionType;
use App\Enums\GenderType;
use App\Enums\ListingPlatform;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()->assertJsonStructure(['data', 'meta']);
    }

    public function test_it_creates_a_product(): void
    {
        $payload = [
            'title' => 'Vintage Jacket',
            'description' => 'A unique denim jacket.',
            'brand' => 'Levi\'s',
            'gender' => GenderType::UNISEX->value,
            'condition' => ConditionType::GOOD->value,
            'price' => 79.99,
            'currency' => 'EUR',
            'size' => 'M',
            'is_sold' => false,
            'images' => [
                ['path' => 'images/jacket-front.jpg', 'position' => 0, 'is_primary' => true],
                ['path' => 'images/jacket-back.jpg', 'position' => 1],
            ],
            'listings' => [
                ['platform' => ListingPlatform::WALLAPOP->value, 'url' => 'https://wallapop.com/item/1'],
                ['platform' => ListingPlatform::DEPOP->value, 'url' => 'https://depop.com/item/1'],
            ],
        ];

        $response = $this->postJson('/api/v1/products', $payload);

        $response->assertCreated();
        $response->assertHeader('Location');
        $this->assertDatabaseHas('products', ['title' => 'Vintage Jacket']);
        $this->assertDatabaseCount('product_images', 2);
        $this->assertDatabaseCount('product_listings', 2);
    }
}

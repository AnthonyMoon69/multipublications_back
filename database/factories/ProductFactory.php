<?php

namespace Database\Factories;

use App\Enums\ConditionType;
use App\Enums\GenderType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'brand' => $this->faker->randomElement(['Nike', 'Adidas', 'Zara', 'H&M']),
            'gender' => $this->faker->randomElement(GenderType::cases())->value,
            'condition' => $this->faker->randomElement(ConditionType::cases())->value,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'currency' => 'EUR',
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'is_sold' => $this->faker->boolean(20),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

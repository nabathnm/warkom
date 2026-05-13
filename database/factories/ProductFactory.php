<?php

namespace Database\Factories;

use App\Models\Product;
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
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['cpu', 'motherboard', 'vga', 'ram', 'storage', 'psu', 'casing', 'cooling', 'aksesoris']),
            'price' => fake()->numberBetween(100000, 5000000),
            'stock' => fake()->numberBetween(1, 100),
            'image' => null,
            'images' => null,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}

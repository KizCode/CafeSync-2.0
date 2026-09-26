<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'price' => fake()->randomFloat(2, 5000, 50000),
            'category_id' => Category::factory(),
            'stock' => fake()->numberBetween(1, 100),
            'description' => fake()->sentence(),
            'image' => null,
        ];
    }
}

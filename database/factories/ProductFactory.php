<?php

namespace Database\Factories;

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
            'category_id' => \App\Models\Category::factory(), // لربطها بـ Category factory
            'name_en' => fake()->word(),
            'name_ar' => fake()->word(),
            'slug' => fake()->unique()->slug(), // مهم جداً
            'description_en' => fake()->text(),
            'description_ar' => fake()->text(),
        ];
    }
}

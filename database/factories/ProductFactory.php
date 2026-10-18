<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name_en' => ucfirst($name),
            'name_ar' => 'منتج ' . $name,
            'slug' => Str::slug($name),
            'description_en' => $this->faker->paragraph(),
            'description_ar' => $this->faker->paragraph(),
        ];
    }
}

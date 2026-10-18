<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->word();
        return [
            'name_en' => ucfirst($name),
            'name_ar' => 'تصنيف ' . $name,
            'slug' => Str::slug($name),
            'description_en' => $this->faker->sentence(),
            'description_ar' => $this->faker->sentence(),
            'image_path' => 'categories/default.jpg',
        ];
    }
}

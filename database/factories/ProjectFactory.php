<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $nameEn = $this->faker->words(3, true) . ' Project';

        return [
            'name_en' => $nameEn,
            'name_ar' => 'مشروع ' . $this->faker->word(),
            'slug' => Str::slug($nameEn), // 👈 أضف هذا السطر لتوليد السلوج تلقائياً وتجنب خطأ السيرفر
            'description_en' => $this->faker->paragraph(),
            'description_ar' => 'وصف تجريبي للمشروع باللغة العربية.',
            'video_url' => $this->faker->url(),
        ];
    }
}

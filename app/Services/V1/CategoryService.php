<?php

namespace App\Services\V1;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * إنشاء تصنيف جديد وتوليد الـ Slug له
     */
    public function createCategory(array $data): Category
    {
        // توليد الـ Slug تلقائياً من الاسم الإنجليزي
        $data['slug'] = Str::slug($data['name_en']);

        return Category::create($data);
    }

    /**
     * تحديث تصنيف موجود
     */
    public function updateCategory(Category $category, array $data): Category
    {
        // إعادة توليد الـ Slug إذا تغير الاسم الإنجليزي
        $data['slug'] = Str::slug($data['name_en']);

        $category->update($data);

        return $category;
    }
}

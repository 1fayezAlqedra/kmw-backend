<?php

namespace App\Services\V1;

use App\Models\ProjectCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectCategoryService
{
    /**
     * جلب كافة التصنيفات مع عدد المشاريع
     */
    public function getAllCategories()
    {
        return ProjectCategory::withCount('projects')->latest()->get();
    }

    /**
     * إنشاء تصنيف جديد
     */
    public function createCategory(array $data): ProjectCategory
    {
        $nameForSlug = $data['name_en'] ?? $data['name_ar'];
        $originalSlug = Str::slug($nameForSlug);
        $slug = $originalSlug;
        $count = 1;

        while (ProjectCategory::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $data['slug'] = $slug;

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('project_categories', 'public');
        } else {
            unset($data['image']);
        }

        return ProjectCategory::create($data);
    }

    /**
     * تحديث بيانات التصنيف
     */
public function updateCategory(ProjectCategory $category, array $data): ProjectCategory
{
    // 1. إعادة حساب الـ slug عند تغيير أي اسم
    if (!empty($data['name_en']) || !empty($data['name_ar'])) {
        $nameForSlug = $data['name_en'] ?? $data['name_ar'];
        $originalSlug = Str::slug($nameForSlug);
        $slug = $originalSlug;
        $count = 1;

        while (ProjectCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $data['slug'] = $slug;
    }

    // 2. معالجة رفع الصورة إذا تم إرفاق ملف جديد
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $data['image'] = $data['image']->store('project_categories', 'public');
    } else {
        unset($data['image']);
    }

    // 3. التنفيذ المباشر للاستعلام UPDATE في DB
    $category->update($data);

    return $category;
}

    /**
     * حذف التصنيف مع صورته
     */
    public function deleteCategory(ProjectCategory $category): bool
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        return $category->delete();
    }
}

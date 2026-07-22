<?php

namespace App\Services\V1;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CategoryService
{
    /**
     * Create a new category, generate slug, and handle image upload.
     */
    public function createCategory(array $data, ?UploadedFile $image = null): Category
    {
        // Generate SEO-friendly slug from English name
        $data['slug'] = Str::slug($data['name_en']);

        // Upload the image file if present, fallback to default category cover
        if ($image) {
            $path = $image->store('categories', 'public');
            $data['image_path'] = $path;
        } else {
            $data['image_path'] = 'categories/default.png';
        }

        return Category::create($data);
    }

    /**
     * Update an existing category, regenerate slug, and update the uploaded image.
     */
    public function updateCategory(Category $category, array $data, ?UploadedFile $image = null): Category
    {
        // Regenerate SEO-friendly slug if provided or from name_en
        if (!empty($data['name_en'])) {
            $data['slug'] = Str::slug($data['name_en']);
        }

        // Handle image update and storage cleanup
        if ($image) {
            // Delete old custom image from disk if it exists and is not default
            if ($category->image_path && $category->image_path !== 'categories/default.png') {
                Storage::disk('public')->delete($category->image_path);
            }

            $path = $image->store('categories', 'public');
            $data['image_path'] = $path;
        }

        $category->update($data);

        return $category;
    }
}

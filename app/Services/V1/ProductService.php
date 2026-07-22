<?php

namespace App\Services\V1;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product instance with its associated gallery images.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name_en']);

            $product = Product::create([
                'category_id' => $data['category_id'],
                'name_en' => $data['name_en'],
                'name_ar' => $data['name_ar'],
                'slug' => $slug,
                'description_en' => $data['description_en'] ?? null,
                'description_ar' => $data['description_ar'] ?? null,
            ]);

            if (isset($data['images']) && is_array($data['images'])) {
                $this->uploadImages($product, $data['images']);
            }

            return $product->load(['images', 'category']);
        });
    }

    /**
     * Update an existing product and attach newly uploaded images if provided.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            if (isset($data['name_en']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name_en']);
            }

            $product->update(array_filter([
                'category_id' => $data['category_id'] ?? $product->category_id,
                'name_en' => $data['name_en'] ?? $product->name_en,
                'name_ar' => $data['name_ar'] ?? $product->name_ar,
                'slug' => $data['slug'] ?? $product->slug,
                'description_en' => $data['description_en'] ?? $product->description_en,
                'description_ar' => $data['description_ar'] ?? $product->description_ar,
            ]));

            // دمج new_images أو images إن وجدتا
            $uploadedImages = $data['new_images'] ?? $data['images'] ?? null;

            if (is_array($uploadedImages)) {
                $this->uploadImages($product, $uploadedImages);
            }

            return $product->load(['images', 'category']);
        });
    }

    /**
     * Upload images to storage disk and persist file metadata in database.
     *
     * @param Product $product
     * @param array $images
     * @return void
     */
    private function uploadImages(Product $product, array $images): void
    {
        $hasMainImage = $product->images()->where('is_main', true)->exists();

        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_main' => !$hasMainImage && ($index === 0),
            ]);
        }
    }

    /**
     * Delete product record along with deleting physical files from the storage disk.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $product = Product::with('images')->findOrFail($id);

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            return (bool) $product->delete();
        });
    }
    public function deleteImage(int $imageId): bool
    {
        $image = ProductImage::find($imageId);

        if (!$image) {
            return false;
        }

        // 1. حذف الملف الفيزيائي من القرص (public disk)
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // 2. حذف السجل من قاعدة البيانات
        return $image->delete();
    }
}

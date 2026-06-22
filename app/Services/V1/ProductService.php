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
     * إنشاء منتج جديد مع صوره
     */
    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. توليد الـ slug تلقائياً إذا لم يتم تمريره
            $slug = $data['slug'] ?? Str::slug($data['name_en']);

            // 2. إنشاء المنتج
            $product = Product::create([
                'category_id'    => $data['category_id'],
                'name_en'        => $data['name_en'],
                'name_ar'        => $data['name_ar'],
                'slug'           => $slug,
                'description_en' => $data['description_en'],
                'description_ar' => $data['description_ar'],
            ]);

            // 3. معالجة الصور إذا وجدت
            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main'    => ($index === 0), // الصورة الأولى تعتبر أساسية
                    ]);
                }
            }

            return $product;
        });
    }

    /**
     * حذف المنتج وكل الصور المرتبطة به
     */
    public function deleteProduct(int $id)
    {
        return DB::transaction(function () use ($id) {
            $product = Product::findOrFail($id);

            // حذف ملفات الصور من التخزين (Storage)
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // حذف المنتج (بما أنك استخدمت onDelete('cascade') في المايجريشن،
            // سيتم حذف سجلات الصور من قاعدة البيانات تلقائياً)
            return $product->delete();
        });
    }
}

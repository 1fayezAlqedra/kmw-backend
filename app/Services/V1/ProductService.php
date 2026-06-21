<?php

namespace App\Services\V1;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * منطق العمل الخاص بإنشاء منتج جديد وحفظ صوره
     */
    public function createProduct(array $data): Product
    {
        // 1. توليد الـ Slug تلقائياً بناءً على الاسم الإنجليزي
        $data['slug'] = Str::slug($data['name_en']);

        // 2. فصل الصور عن البيانات الأساسية للمنتج حتى لا تسبب خطأ أثناء الحفظ
        $images = $data['images'] ?? [];
        unset($data['images']);

        // 3. حفظ المنتج في جدول products
        $product = Product::create($data);

        // 4. المرور على مصفوفة الصور وحفظها في جدول product_images المرتبط
        if (!empty($images)) {
            foreach ($images as $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $imageUrl
                ]);
            }
        }

        // إرجاع المنتج كاملاً مع الصور المرتبطة به
        return $product->load('images');
    }
}

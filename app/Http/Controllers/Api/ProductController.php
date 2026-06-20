<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Public: جلب جميع المنتجات مع صورها لموقع الزوار
     */
    public function index(): JsonResponse
    {
        $products = Product::with('images')->latest()->get();
        return response()->json(['success' => true, 'data' => $products], 200);
    }

    /**
     * Admin CMS: إضافة منتج جديد مع ألبوم الصور الخاص به
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id'  => 'required|exists:categories,id',
            'name_en'     => 'required|string|max:255',
            'name_ar'     => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'images'       => 'nullable|array', // مصفوفة تحتوي على روابط أو ملفات الصور
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // توليد الـ slug تلقائياً من الاسم الإنجليزي للمنتج
        $slug = Str::slug($request->name_en);

        // 1. إنشاء المنتج في جدول products
        $product = Product::create(array_merge(
            $request->except('images'),
            ['slug' => $slug]
        ));

        // 2. إذا كان هناك صور مرفوعة، يتم حفظها في جدول product_images ربطاً بالمنتج
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $imageUrl // سنطور نظام الـ Upload الفعلي للملفات لاحقاً
                ]);
            }
        }

        // جلب المنتج كاملاً مع صوره بعد الحفظ لإرجاعه للـ Vue.js
        $product->load('images');

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully with its gallery.',
            'data'    => $product
        ], 201);
    }

    /**
     * Public: جلب تفاصيل منتج واحد عبر الـ slug
     */
    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)->with('images')->firstOrFail();
        return response()->json(['success' => true, 'data' => $product], 200);
    }

    /**
     * Admin CMS: تعديل بيانات المنتج وتحديث ألبومه
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id'  => 'required|exists:categories,id',
            'name_en'     => 'required|string|max:255',
            'name_ar'     => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'images'       => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $slug = Str::slug($request->name_en);

        // تحديث البيانات الأساسية للمنتج
        $product->update(array_merge(
            $request->except('images'),
            ['slug' => $slug]
        ));

        // في حال أرسل الأدمن مصفوفة صور جديدة، نقوم بتحديث الألبوم
        if ($request->has('images') && is_array($request->images)) {
            // حذف الصور القديمة المرتبطة بهذا المنتج أولاً لإعادة بنائها
            $product->images()->delete();

            foreach ($request->images as $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $imageUrl
                ]);
            }
        }

        $product->load('images');

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data'    => $product
        ], 200);
    }

    /**
     * Admin CMS: حذف منتج (يدعم الـ SoftDeletes تلقائياً لو تم تفعيلها)
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        // ملاحظة: بما أن المنتج سيحذف، يُفضل حذف صوره المرتبطة به أيضاً
        $product->images()->delete();
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product and its gallery deleted successfully.'
        ], 200);
    }
}

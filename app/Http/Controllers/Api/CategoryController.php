<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Public: جلب جميع التصنيفات لموقع الزوار
     */
    public function index(): JsonResponse
    {
        $categories = Category::latest()->get();
        return response()->json(['success' => true, 'data' => $categories], 200);
    }

    /**
     * Admin CMS: إضافة تصنيف جديد
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_en'     => 'required|string|max:255',
            'name_ar'     => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image'       => 'nullable|string', // سنقوم لاحقاً بتطوير نظام رفع الصور الفعلي
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // توليد الـ slug تلقائياً من الاسم الإنجليزي ليكون الرابط صديقاً للسيو
        $slug = Str::slug($request->name_en);

        $category = Category::create(array_merge(
            $request->all(),
            ['slug' => $slug]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data'    => $category
        ], 201);
    }

    /**
     * Public: جلب تفاصيل تصنيف واحد
     */
    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->with(['products' => function($query) {
                $query->with('images');
            }])
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $category], 200);
    }

    /**
     * Admin CMS: تعديل تصنيف حالي
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name_en'     => 'required|string|max:255',
            'name_ar'     => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // تحديث الـ slug بناءً على الاسم الإنجليزية الجديد
        $slug = Str::slug($request->name_en);

        $category->update(array_merge(
            $request->all(),
            ['slug' => $slug]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data'    => $category
        ], 200);
    }

    /**
     * Admin CMS: حذف تصنيف
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.'
        ], 200);
    }
}

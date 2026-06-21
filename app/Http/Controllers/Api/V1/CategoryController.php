<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest; // استدعاء كلاس التحقق
use App\Services\V1\CategoryService;             // استدعاء كلاس الخدمة
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    /**
     * حقن كلاس الخدمة عبر الـ Constructor
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

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
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        // نمرر البيانات المفحوصة والمضمونة للخدمة مباشرة
        $category = $this->categoryService->createCategory($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data'    => $category
        ], 201);
    }

    /**
     * Public: جلب تفاصيل تصنيف واحد مع منتجاته وصورها
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
    public function update(StoreCategoryRequest $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        // تمرير الموديل مع البيانات الجديدة للخدمة
        $updatedCategory = $this->categoryService->updateCategory($category, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data'    => $updatedCategory
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

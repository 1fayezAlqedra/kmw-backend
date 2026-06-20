<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * جلب جميع التصنيفات
     */
    public function index(): JsonResponse
    {
        $categories = Category::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * جلب تصنيف معين مع المنتجات التابعة له وصورها
     */
    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->with(['products' => function($query) {
                $query->with('images');
            }])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $category
        ], 200);
    }
}

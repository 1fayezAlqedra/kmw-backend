<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * جلب جميع المنتجات مع صورها وتصنيفاتها
     */
    public function index(): JsonResponse
    {
        // جلب المنتجات مع عمل Eager Loading للصور والتصنيف لتسريع الاستعلام
        $products = Product::with(['images', 'category'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    /**
     * جلب تفاصيل منتج واحد محدد بكافة صوره
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with(['images', 'category'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }
}

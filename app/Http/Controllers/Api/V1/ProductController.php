<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProductRequest; // استدعاء كلاس التحقق الجديد
use App\Models\Product;
use App\Services\V1\ProductService;             // استدعاء كلاس الخدمة الجديد
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    protected $productService;

    /**
     * حقن كلاس الخدمة (ProductService) تلقائياً عبر الـ Constructor
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * جلب كافة المنتجات (عرض عام)
     */
    public function index(): JsonResponse
    {
        $products = Product::with('images')->latest()->get();
        return response()->json(['success' => true, 'data' => $products], 200);
    }

    /**
     * إنشاء منتج جديد (باستخدام الـ Request والـ Service)
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        // 1. البيانات هنا تصل مفحوصة وجاهزة تماماً بفضل StoreProductRequest
        // 2. نمرر البيانات الجاهزة للخدمة لتنفيذ منطق الحفظ ورفع الصور
        $product = $this->productService->createProduct($request->validated());

        // 3. الـ Controller يعيد الاستجابة فقط في سطر واحد
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully via Enterprise Service Pattern.',
            'data' => $product
        ], 201);
    }

    /**
     * عرض منتج معين عبر الـ Slug
     */
    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)->with('images', 'category')->firstOrFail();
        return response()->json(['success' => true, 'data' => $product], 200);
    }
    public function update(Request $request, $id)
    {
        // 1. جلب المنتج أو إرجاع 404 إن لم يكن موجوداً
        $product = \App\Models\Product::findOrFail($id);

        // 2. تحديث الحقول مباشرة من الـ request
        $product->update($request->only([
            'category_id',
            'name_en',
            'name_ar',
            'slug',
            'description_en',
            'description_ar'
        ]));

        // 3. إرجاع استجابة نجاح متوافقة مع الـ API 구조 الخاص بك
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    /**
     * حذف المنتج
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        // إذا أردت حذفاً مؤقتاً (Soft Delete):
        $product->delete();

        // إذا أردت حذفاً نهائياً (مثلاً إذا أردت حذف ملفات الصور فعلياً):
        // $product->forceDelete();

        return response()->json(['success' => true, 'message' => 'Product moved to trash.'], 200);
    }
}

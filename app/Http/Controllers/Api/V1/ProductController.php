<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Models\Product;
use App\Services\V1\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * ProductController constructor.
     * Injecting the ProductService class into the controller.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of all products with associated category and images.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['images', 'category'])->latest()->get();
        return response()->json(['success' => true, 'data' => $products], 200);
    }

    /**
     * Store a newly created product using the ProductService.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified product by either numeric ID or URL Slug.
     * Guarantees compatibility across client web pages and admin panel calls.
     *
     * @param string $idOrSlug
     * @return JsonResponse
     */
    public function show(string $idOrSlug): JsonResponse
    {
        $product = Product::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->with(['images', 'category'])
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $product], 200);
    }

    /**
     * Update the specified product in storage.
     *
     * @param UpdateProductRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $updatedProduct = $this->productService->updateProduct($product, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $updatedProduct
        ], 200);
    }

    /**
     * Remove the specified product and delete image attachments via service layer.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->productService->deleteProduct((int) $id);

        return response()->json([
            'success' => true,
            'message' => 'Product and associated files deleted successfully.'
        ], 200);
    }

    public function deleteImage($id)
    {
        // استدعاء دالة الحذف من طبقة الـ Service
        $deleted = $this->productService->deleteImage($id);

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Image not found or could not be deleted.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product image deleted successfully.'
        ], 200);
    }
}

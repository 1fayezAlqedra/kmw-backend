<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Services\V1\CategoryService;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    /**
     * Inject CategoryService via Constructor.
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Public API: Retrieve all categories for the client-side website.
     */
    public function index(): JsonResponse
    {
        $categories = Category::latest()->get();
        return response()->json(['success' => true, 'data' => $categories], 200);
    }

    /**
     * Admin CMS API: Create a new category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory(
            $request->validated(),
            $request->file('image_path')
        );

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    /**
     * Admin CMS API: Update an existing category.
     */
    public function update(StoreCategoryRequest $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $updatedCategory = $this->categoryService->updateCategory(
            $category,
            $request->validated(),
            $request->file('image_path')
        );

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => $updatedCategory
        ], 200);
    }

    /**
     * Dual Purpose API: Get category details by numeric ID (for CMS) or Slug (for public frontend).
     */
    public function show(string $idOrSlug): JsonResponse
    {
        // Support finding category by primary key ID or unique URL slug
        $category = Category::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->with([
                'products' => function ($query) {
                    $query->with('images');
                }
            ])
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $category], 200);
    }

    /**
     * Admin CMS API: Delete a category and clean up stored assets.
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

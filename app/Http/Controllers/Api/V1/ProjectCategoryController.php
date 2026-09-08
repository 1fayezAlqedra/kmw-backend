<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProjectCategoryRequest;
use App\Http\Requests\Api\V1\UpdateProjectCategoryRequest;
use App\Models\ProjectCategory;
use App\Services\V1\ProjectCategoryService;
use Illuminate\Http\JsonResponse;

class ProjectCategoryController extends Controller
{
    protected ProjectCategoryService $categoryService;

    public function __construct(ProjectCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->categoryService->getAllCategories()
        ]);
    }

    public function store(StoreProjectCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!$request->hasFile('image')) {
            unset($validated['image']);
        }

        $category = $this->categoryService->createCategory($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project category created successfully.',
            'data'    => $category
        ], 201);
    }

    public function show(ProjectCategory $projectCategory): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $projectCategory
        ]);
    }

    public function update(UpdateProjectCategoryRequest $request, $id): JsonResponse
{
    // جلب العنصر يدويًا بـ ID لتفادي أي فشل في الـ Route Model Binding
    $projectCategory = ProjectCategory::findOrFail($id);

    $validated = $request->validated();

    if (!$request->hasFile('image')) {
        unset($validated['image']);
    }

    $category = $this->categoryService->updateCategory($projectCategory, $validated);

    return response()->json([
        'success' => true,
        'message' => 'Project category updated successfully.',
        'data'    => $category->fresh()
    ]);
}

    public function destroy(ProjectCategory $projectCategory): JsonResponse
    {
        $this->categoryService->deleteCategory($projectCategory);

        return response()->json([
            'success' => true,
            'message' => 'Project category deleted successfully.'
        ]);
    }
}

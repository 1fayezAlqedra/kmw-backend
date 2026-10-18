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

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        } else {
            unset($validated['image']);
        }

        $category = $this->categoryService->createCategory($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project category created successfully.',
            'data'    => $category
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $projectCategory = ProjectCategory::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $projectCategory
        ]);
    }

    public function update(UpdateProjectCategoryRequest $request, $id): JsonResponse
    {
        $projectCategory = ProjectCategory::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        } else {
            unset($validated['image']);
        }

        $category = $this->categoryService->updateCategory($projectCategory, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Project category updated successfully.',
            'data'    => $category->fresh()
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $projectCategory = ProjectCategory::findOrFail($id);

        $deleted = $this->categoryService->deleteCategory($projectCategory);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Project category deleted successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete project category.'
        ], 500);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProjectRequest; // استدعاء الـ Request الجديد
use App\Services\V1\ProjectService;             // استدعاء الـ Service الجديد
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected $projectService;

    // حقن خدمة المشاريع تلقائياً
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): JsonResponse
    {
        $projects = Project::with('images')->latest()->get();
        return response()->json(['success' => true, 'data' => $projects], 200);
    }

    /**
     * إنشاء مشروع جديد بنمط احترافي ومختصر
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        // تمرير البيانات المفحوصة مباشرة للخدمة
        $project = $this->projectService->createProject($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully via Service Pattern.',
            'data' => $project
        ], 201);
    }

    public function show(string $slug): JsonResponse
    {
        $project = Project::where('slug', $slug)->with('images')->firstOrFail();
        return response()->json(['success' => true, 'data' => $project], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->images()->delete();
        $project->delete();

        return response()->json(['success' => true, 'message' => 'Project removed completely.'], 200);
    }
}

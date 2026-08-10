<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProjectRequest;
use App\Http\Requests\Api\V1\UpdateProjectRequest;
use App\Services\V1\ProjectService;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): JsonResponse
    {
        $projects = Project::with(['category', 'images'])->latest()->get();
        return response()->json(['success' => true, 'data' => $projects], 200);
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $project = $this->projectService->createProject($request->validated());
            return response()->json($project, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

public function show(string $identifier): JsonResponse
{
    $project = Project::where('id', $identifier)
        ->orWhere('slug', $identifier)
        ->with(['category', 'images'])
        ->firstOrFail();

    return response()->json(['success' => true, 'data' => $project], 200);
}
    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $updatedProject = $this->projectService->updateProject($project, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data' => $updatedProject
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $this->projectService->deleteProject($project);

        return response()->json(['success' => true, 'message' => 'Project removed completely.'], 200);
    }
}

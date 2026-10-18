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

    /**
     * عرض جميع المشاريع مع التصنيفات والصور
     */
    public function index(): JsonResponse
    {
        try {
            $projects = $this->projectService->getAllProjects();
            return response()->json([
                'success' => true,
                'data'    => $projects
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة مشروع جديد
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            // دمج البيانات المعتمدة مع ملفات الصور لضمان وصولها إلى الـ Service
            $data = array_merge($request->validated(), [
                'images' => $request->file('images', [])
            ]);

            $project = $this->projectService->createProject($data);

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully.',
                'data'    => $project
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    /**
     * عرض مشروع محدد بناءً على الـ ID أو الـ Slug
     */
    public function show(string $identifier): JsonResponse
    {
        try {
            $project = Project::where('id', $identifier)
                ->orWhere('slug', $identifier)
                ->with(['category', 'images'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data'    => $project
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'المشروع غير موجود.'
            ], 404);
        }
    }

    /**
     * تحديث بيانات مشروع قائم
     */
    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);

            // دمج البيانات المعتمدة مع الملفات المرفوعة لضمان قراءة الصور
            $data = array_merge($request->validated(), [
                'images' => $request->file('images', [])
            ]);

            $updatedProject = $this->projectService->updateProject($project, $data);

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully.',
                'data'    => $updatedProject
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    /**
     * حذف مشروع
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $this->projectService->deleteProject($project);

            return response()->json([
                'success' => true,
                'message' => 'Project removed completely.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

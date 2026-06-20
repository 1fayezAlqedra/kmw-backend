<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * جلب جميع المشاريع السابقة مع صورها
     */
    public function index(): JsonResponse
    {
        $projects = Project::with('images')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ], 200);
    }

    /**
     * جلب تفاصيل مشروع معين بكافة صوره
     */
    public function show(string $id): JsonResponse
    {
        $project = Project::with('images')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $project
        ], 200);
    }
}

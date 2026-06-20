<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Public: جلب جميع المشاريع لموقع الزوار
     */
    public function index(): JsonResponse
    {
        $projects = Project::with('images')->latest()->get();
        return response()->json(['success' => true, 'data' => $projects], 200);
    }

    /**
     * Admin CMS: إضافة مشروع جديد مع معرض صوره
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'client_en' => 'nullable|string|max:255',
            'client_ar' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'location_ar' => 'nullable|string|max:255',
            'completed_at' => 'nullable|date',
            'images' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $slug = Str::slug($request->title_en);

        // 1. حفظ بيانات المشروع الأساسية
        $project = Project::create(array_merge(
            $request->except('images'),
            ['slug' => $slug]
        ));

        // 2. حفظ ألبوم صور المشروع إن وجدت
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $imageUrl) {
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image' => $imageUrl
                ]);
            }
        }

        $project->load('images');

        return response()->json([
            'success' => true,
            'message' => 'Project showcase created successfully.',
            'data' => $project
        ], 201);
    }

    /**
     * Public: جلب تفاصيل مشروع واحد
     */
    public function show(string $slug): JsonResponse
    {
        $project = Project::where('slug', $slug)->with('images')->firstOrFail();
        return response()->json(['success' => true, 'data' => $project], 200);
    }

    /**
     * Admin CMS: تعديل بيانات المشروع وصوره
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'client_en' => 'nullable|string|max:255',
            'client_ar' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'location_ar' => 'nullable|string|max:255',
            'completed_at' => 'nullable|date',
            'images' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $slug = Str::slug($request->title_en);

        $project->update(array_merge(
            $request->except('images'),
            ['slug' => $slug]
        ));

        if ($request->has('images') && is_array($request->images)) {
            $project->images()->delete(); // تصفير الألبوم القديم وإعادة بنائه بالجديد
            foreach ($request->images as $imageUrl) {
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image' => $imageUrl
                ]);
            }
        }

        $project->load('images');

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data' => $project
        ], 200);
    }

    /**
     * Admin CMS: حذف مشروع بالكامل مع ألبومه
     */
    public function destroy(string $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->images()->delete();
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project and its gallery assets deleted successfully.'
        ], 200);
    }
}

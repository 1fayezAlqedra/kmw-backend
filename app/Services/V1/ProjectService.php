<?php

namespace App\Services\V1;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    /**
     * Get all projects with relationships.
     */
    public function getAllProjects()
    {
        return Project::with(['category', 'images'])->latest()->paginate(10);
    }

    /**
     * Create a new project.
     */
    public function createProject(array $data): Project
    {
        // 🟢 آمن ضد الأخطاء مع توليد تلقائي للـ Slug
        $project = Project::create([
            'category_id'    => $data['category_id'] ?? null,
            'name_en'        => $data['name_en'],
            'name_ar'        => $data['name_ar'],
            'slug'           => Str::slug($data['name_en']) . '-' . time(),
            'description_en' => $data['description_en'],
            'description_ar' => $data['description_ar'],
        ]);

        // رفع الصور إن وجدت وبشكل آمن
        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $file) {
                if ($file) {
                    $path = $file->store('projects', 'public');
                    $project->images()->create([
                        'image_path' => $path
                    ]);
                }
            }
        }

        return $project->load(['category', 'images']);
    }

    /**
     * Update an existing project.
     */
    public function updateProject(Project $project, array $data): Project
    {
        $project->update([
            'category_id'    => array_key_exists('category_id', $data) ? $data['category_id'] : $project->category_id,
            'name_en'        => $data['name_en'] ?? $project->name_en,
            'name_ar'        => $data['name_ar'] ?? $project->name_ar,
            'slug'           => isset($data['name_en']) ? Str::slug($data['name_en']) . '-' . $project->id : $project->slug,
            'description_en' => $data['description_en'] ?? $project->description_en,
            'description_ar' => $data['description_ar'] ?? $project->description_ar,
        ]);

        // حذف الصور المحددة من الفرونت إند
        if (!empty($data['deleted_images'])) {
            $imagesToDelete = ProjectImage::whereIn('id', $data['deleted_images'])
                ->where('project_id', $project->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        }

        // إضافة الصور الجديدة
        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $file) {
                if ($file) {
                    $path = $file->store('projects', 'public');
                    $project->images()->create([
                        'image_path' => $path
                    ]);
                }
            }
        }

        return $project->load(['category', 'images']);
    }

    /**
     * حذف المشروع كاملاً مع تنظيف صوره
     */
    public function deleteProject(Project $project): void
    {
        foreach ($project->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $project->images()->delete();
        $project->delete();
    }
}

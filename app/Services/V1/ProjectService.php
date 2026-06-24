<?php

namespace App\Services\V1;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Str;

class ProjectService
{
    /**
     * منطق العمل الخاص بإنشاء مشروع جديد وحفظ صوره
     */
    public function createProject(array $data): Project
    {
        // 1. توليد الـ Slug تلقائياً بناءً على العنوان الإنجليزي
        $data['slug'] = Str::slug($data['name_en']);

        // 2. فصل الصور عن البيانات الأساسية للمشروع
        $images = $data['images'] ?? [];
        unset($data['images']);

        // 3. حفظ المشروع في قاعدة البيانات
        $project = Project::create($data);

        // 4. حفظ ألبوم الصور المرتبط بالمشروع
        if (!empty($images)) {
            foreach ($images as $imageUrl) {
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $imageUrl
                ]);
            }
        }

        return $project->load('images');
    }
}

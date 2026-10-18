<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_category_id', 
        'name_en',
        'name_ar',
        'slug',
        'description_en',
        'description_ar',
        'video_url',
    ];

    /**
     * علاقة المشروع بالتصنيف
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    /**
     * علاقة المشروع بمعرض الصور
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class, 'project_id');
    }
}

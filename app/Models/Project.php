<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
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
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    /**
     * علاقة المشروع بمعرض الصور
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }
}

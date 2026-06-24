<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name_en', 'name_ar', 'slug', 'description_en', 'description_ar', 'video_url'];

    // علاقة: المشروع يملك معرض صور
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['name_en', 'name_ar', 'description_en', 'description_ar', 'video_url'];

    // علاقة: المشروع يملك معرض صور
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }
}

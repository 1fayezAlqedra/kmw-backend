<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'youtube_url'
    ];
}

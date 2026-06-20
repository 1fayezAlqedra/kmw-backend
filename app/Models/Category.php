<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // الحقول المسموح بتعبئتها
    protected $fillable = ['name_en', 'name_ar', 'description_en', 'description_ar', 'image_path', 'slug'];

    // علاقة: التصنيف يملك العديد من المنتجات
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

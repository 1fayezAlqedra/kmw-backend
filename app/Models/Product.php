<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // لتفعيل الحذف المؤقت كما صممته بالـ SQL
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes, HasFactory;

protected $fillable = ['category_id', 'name_en', 'name_ar', 'description_en', 'description_ar', 'slug']; // تأكد من وجود 'slug' هنا
    // علاقة: المنتج ينتمي إلى تصنيف محدد
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة: المنتج يملك صوراً متعددة
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

}

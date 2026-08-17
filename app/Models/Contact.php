<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // تحديد اسم الجدول في قاعدة البيانات بدقة
    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}

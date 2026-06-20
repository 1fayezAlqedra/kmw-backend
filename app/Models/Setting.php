<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false; // لأن جدول الإعدادات لا يحتوي على timestamps
    protected $fillable = ['key', 'value', 'type'];
}

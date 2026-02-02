<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomSmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'template',
        'description',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}

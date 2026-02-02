<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'name',
        'section',
        'students',
        'teachers',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'students' => 'integer',
        'teachers' => 'integer'
    ];

    /**
     * Get the full class name with section
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' - ' . $this->section;
    }

    /**
     * Scope to get only active classes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by class name and section
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name')->orderBy('section');
    }
}
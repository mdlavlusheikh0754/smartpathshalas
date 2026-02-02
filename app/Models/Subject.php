<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'code',
        'type',
        'class_id',
        'description',
        'classes',
        'total_marks',
        'pass_marks',
        'is_active'
    ];

    protected $casts = [
        'classes' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function schoolClass()
    {
        return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_subjects')
                    ->withPivot(['exam_date', 'start_time', 'end_time', 'total_marks', 'pass_marks', 'instructions'])
                    ->withTimestamps();
    }

    public function examSubjects()
    {
        return $this->hasMany(ExamSubject::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForClass($query, $className)
    {
        return $query->whereJsonContains('classes', $className);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->name . ($this->name_en ? " ({$this->name_en})" : '');
    }

    public function getClassesListAttribute()
    {
        return is_array($this->classes) ? implode(', ', $this->classes) : '';
    }

    // Methods
    public function isForClass($className)
    {
        return is_array($this->classes) && in_array($className, $this->classes);
    }

    public function activate()
    {
        $this->is_active = true;
        $this->save();
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
    }
}
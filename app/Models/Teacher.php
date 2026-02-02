<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'name',
        'name_bangla',
        'designation',
        'subject',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'joining_date',
        'qualification',
        'experience',
        'salary',
        'status',
        'photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the homework assigned by this teacher
     */
    public function homework()
    {
        return $this->hasMany(Homework::class, 'teacher_id');
    }

    /**
     * Get the attendance records taken by this teacher
     */
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }

    /**
     * Scope for active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for teachers by subject
     */
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', 'like', "%{$subject}%");
    }

    /**
     * Scope for teachers by designation
     */
    public function scopeByDesignation($query, $designation)
    {
        return $query->where('designation', $designation);
    }

    /**
     * Get years of service
     */
    public function getYearsOfServiceAttribute()
    {
        return $this->joining_date ? now()->diffInYears($this->joining_date) : 0;
    }

    /**
     * Get full name with bangla
     */
    public function getFullNameAttribute()
    {
        return $this->name_bangla ? "{$this->name} ({$this->name_bangla})" : $this->name;
    }
}

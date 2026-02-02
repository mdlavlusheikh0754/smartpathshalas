<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name_bn',
        'school_name_en',
        'logo',
        'eiin',
        'short_code',
        'established_year',
        'school_type',
        'education_level',
        'board',
        'mpo_number',
        'phone',
        'mobile',
        'email',
        'website',
        'address',
        'postal_code',
        'district',
        'upazila',
        'principal_name',
        'principal_mobile',
        'principal_email',
        'principal_joining_date',
        'principal_photo',
        'principal_qualification',
        'school_start_time',
        'school_end_time',
        'weekly_holiday',
        'class_duration',
        'break_start_time',
        'break_end_time',
        'current_session',
        'session_start_date',
        'session_end_date',
        'total_students',
        'total_teachers',
        'total_staff',
        'total_classrooms',
        'monthly_fee',
        'admission_fee',
        'bank_name',
        'bank_account_number',
        'bank_routing_number'
    ];

    protected $casts = [
        'session_start_date' => 'date',
        'session_end_date' => 'date',
        'principal_joining_date' => 'date',
        'school_start_time' => 'datetime:H:i',
        'school_end_time' => 'datetime:H:i',
        'break_start_time' => 'datetime:H:i',
        'break_end_time' => 'datetime:H:i',
        'monthly_fee' => 'decimal:2',
        'admission_fee' => 'decimal:2'
    ];

    /**
     * Get the school settings (singleton pattern)
     */
    public static function getSettings()
    {
        return self::first() ?? new self();
    }

    /**
     * Get or create default short code
     */
    public function getShortCodeAttribute($value)
    {
        return $value ?? '101';
    }
}
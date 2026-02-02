<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name_bn', 'school_name_en', 'logo', 'logo_position', 'eiin', 'short_code', 'school_initials', 'established_year',
        'school_type', 'education_level', 'board', 'mpo_number',
        'phone', 'mobile', 'email', 'website', 'address', 'postal_code',
        'district', 'upazila', 'principal_name', 'principal_mobile',
        'principal_email', 'principal_joining_date', 'principal_photo',
        'principal_qualification', 'school_start_time', 'school_end_time',
        'weekly_holiday', 'class_duration', 'break_start_time', 'break_end_time',
        'current_session', 'session_start_date', 'session_end_date',
        'total_students', 'total_teachers', 'total_staff', 'total_classrooms',
        'monthly_fee', 'admission_fee', 'bank_name', 'bank_account_number',
        'bank_routing_number'
    ];

    protected $casts = [
        'principal_joining_date' => 'date',
        'session_start_date' => 'date',
        'session_end_date' => 'date',
        'school_start_time' => 'datetime:H:i',
        'school_end_time' => 'datetime:H:i',
        'break_start_time' => 'datetime:H:i',
        'break_end_time' => 'datetime:H:i',
        'monthly_fee' => 'decimal:2',
        'admission_fee' => 'decimal:2',
    ];

    /**
     * Get the school settings for the current tenant
     */
    public static function getSettings()
    {
        try {
            return static::first() ?? new static();
        } catch (\Illuminate\Database\QueryException $e) {
            // If table doesn't exist, return a new instance with default values
            if (str_contains($e->getMessage(), "doesn't exist")) {
                return new static([
                    'school_name_bn' => 'ইকরা নূরানিয়া একাডেমি',
                    'school_name_en' => 'Iqra Noorani Academy',
                    'eiin' => '123456',
                    'school_type' => 'Secondary',
                    'education_level' => 'Secondary School',
                    'board' => 'Dhaka',
                    'phone' => '01711-123456',
                    'mobile' => '01711-123456',
                    'email' => 'info@school.com',
                    'website' => 'http://school.com',
                    'address' => 'ঢাকা, বাংলাদেশ',
                    'district' => 'ঢাকা',
                    'upazila' => 'সাভার',
                    'principal_name' => 'প্রধান শিক্ষক',
                    'principal_mobile' => '01711-123456',
                    'principal_email' => 'principal@school.com',
                    'school_start_time' => '08:00',
                    'school_end_time' => '14:00',
                    'weekly_holiday' => 'Friday',
                    'class_duration' => 45,
                    'current_session' => '2025-2026',
                    'session_start_date' => '2025-01-01',
                    'session_end_date' => '2025-12-31',
                ]);
            }
            throw $e;
        }
    }

    /**
     * Update or create school settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            $settings = static::create($data);
        }
        
        return $settings;
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrl($field, $default = null)
    {
        if ($this->$field) {
            // Use /files/ route for tenant storage files
            return url('/files/' . $this->$field);
        }
        return $default;
    }

    /**
     * Get short code with default fallback
     */
    public function getShortCodeAttribute($value)
    {
        return $value ?? '101';
    }
}

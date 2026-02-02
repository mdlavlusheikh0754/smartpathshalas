<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'school_name', 'slogan', 'logo', 'hero_bg', 'hero_images', 'principal_name', 'principal_photo',
        'chairman_name', 'chairman_photo', 'about_text', 'established', 'eiin', 'board',
        'type', 'mpo', 'shift', 'total_students', 'total_teachers', 'male_students',
        'female_students', 'vice_principal_name', 'vice_principal_photo', 'classes',
        'shifts', 'academic_year', 'exam_system', 'facilities', 'phone', 'email',
        'address', 'fax', 'facebook', 'youtube', 'twitter', 'instagram',
        'notice_1', 'notice_2', 'notice_3', 'notice_4', 'primary_color',
        'secondary_color', 'accent_color', 'gallery_images', 'gallery_audio',
        // About Section
        'history_text', 'mission_text', 'vision_text', 'infrastructure_text',
        // Administration
        'managing_committee', 'teachers_staff',
        // Academic
        'class_routine_pdf', 'syllabus_files', 'holiday_list', 'academic_calendar_pdf',
        // Admission
        'admission_start_date', 'admission_end_date', 'admission_exam_date', 'class_start_date',
        'admission_rules', 'admission_requirements', 'admission_fees', 'admission_form_pdf',
        'admission_process', 'admission_features',
        // Contact
        'phone_2', 'phone_3', 'google_map_embed',
        // Gallery
        'video_links',
        // Footer
        'copyright_text', 'quick_links'
    ];

    protected $casts = [
        'facilities' => 'array',
        'gallery_images' => 'array',
        'gallery_audio' => 'array',
        'managing_committee' => 'array',
        'teachers_staff' => 'array',
        'syllabus_files' => 'array',
        'holiday_list' => 'array',
        'admission_fees' => 'array',
        'video_links' => 'array',
        'quick_links' => 'array',
        // 'hero_images' => 'array', // Temporarily disabled
    ];

    /**
     * Get the website settings for the current tenant
     */
    public static function getSettings()
    {
        try {
            return static::first() ?? new static();
        } catch (\Illuminate\Database\QueryException $e) {
            // If table doesn't exist, return a new instance with default values
            if (str_contains($e->getMessage(), "doesn't exist")) {
                return new static([
                    'school_name' => 'ইকরা নূরানিয়া একাডেমি',
                    'slogan' => 'শিক্ষার আলোয় আলোকিত হোন',
                    'about_text' => 'আমাদের প্রতিষ্ঠানের গৌরবময় ইতিহাস...',
                    'phone' => '০১৭১১-১২৩৪৫৬',
                    'email' => 'info@school.com',
                    'address' => 'ঢাকা, বাংলাদেশ',
                    'primary_color' => '#3B82F6',
                    'secondary_color' => '#8B5CF6',
                    'accent_color' => '#EC4899',
                ]);
            }
            throw $e;
        }
    }

    /**
     * Update or create website settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();
        
        if ($settings) {
            // Remove any fields that aren't in the fillable array
            $fillable = (new static())->getFillable();
            $data = array_intersect_key($data, array_flip($fillable));
            
            $settings->update($data);
        } else {
            // Remove any fields that aren't in the fillable array
            $fillable = (new static())->getFillable();
            $data = array_intersect_key($data, array_flip($fillable));
            
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
     * Get hero images as array
     */
    public function getHeroImagesArray()
    {
        if (!$this->hero_images) {
            return [];
        }
        
        if (is_string($this->hero_images)) {
            return json_decode($this->hero_images, true) ?: [];
        }
        
        if (is_array($this->hero_images)) {
            return $this->hero_images;
        }
        
        return [];
    }
}

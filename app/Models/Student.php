<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'student';

    protected $fillable = [
        'student_id',
        'password',
        'guardian_id',
        'registration_number',
        'eiin_number',
        'board',
        'name',
        'name_bangla',
        'name_bn',
        'name_en',
        'father_name',
        'mother_name',
        'date_of_birth',
        'birth_certificate_no',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'address',
        'present_address',
        'permanent_address',
        'phone',
        'parent_phone',
        'guardian_phone',
        'email',
        'class',
        'section',
        'roll',
        'group',
        'admission_date',
        'admission_type',
        'previous_school',
        'transfer_certificate_no',
        'photo',
        'qr_code',
        'rfid_card',
        'status',
        'monthly_fee',
        'admission_fee',
        'monthly_fee_status',
        'last_monthly_payment',
        'academic_year',
        'remarks',
        // Document fields
        'birth_certificate_file',
        'vaccination_card',
        'father_nid_file',
        'mother_nid_file',
        'previous_school_certificate',
        'other_documents',
        // Additional parent information
        'father_mobile',
        'father_occupation',
        'father_nid',
        'father_email',
        'father_income',
        'mother_mobile',
        'mother_occupation',
        'mother_nid',
        'mother_email',
        // Guardian information
        'guardian_name',
        'guardian_mobile',
        'guardian_relation',
        'guardian_address',
        // Additional student information
        'special_needs',
        'health_condition',
        'emergency_contact',
        'transport',
        'hostel'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'admission_fee' => 'decimal:2'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the default password based on date of birth
     * Format: ddmmyyyy (e.g., 15032010)
     */
    public function getDefaultPassword()
    {
        if ($this->date_of_birth) {
            return \Carbon\Carbon::parse($this->date_of_birth)->format('dmY');
        }
        return null;
    }

    /**
     * Set password to default (date of birth)
     */
    public function setDefaultPassword()
    {
        $defaultPassword = $this->getDefaultPassword();
        if ($defaultPassword) {
            $this->password = \Hash::make($defaultPassword);
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Check if student has a password set
     */
    public function hasPassword()
    {
        return !empty($this->password);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    // Generate Student ID using School Initials
    public static function generateStudentId($academicYear = null)
    {
        // Get school settings
        try {
            $schoolSettings = \App\Models\SchoolSetting::getSettings();
        } catch (\Exception $e) {
            // If no school settings, use defaults
            $schoolSettings = (object) ['school_initials' => 'SCH', 'school_name_en' => 'School'];
        }
        
        // Use custom initials if set, otherwise generate from school name
        $initials = $schoolSettings->school_initials ?? 'SCH';
        if (empty($initials)) {
            $schoolNameEn = $schoolSettings->school_name_en ?? 'School';
            $initials = self::generateInitials($schoolNameEn);
        }
        
        // Convert Bengali year to English if needed
        $academicYear = self::convertBengaliToEnglish($academicYear ?? date('Y'));
        
        // Get count for sequential number - check if academic_year column exists
        try {
            $count = self::where('academic_year', $academicYear)->count();
        } catch (\Exception $e) {
            // If academic_year column doesn't exist, just count all students
            $count = self::count();
        }
        
        // Format: INA-26-0003 (ensure English characters only)
        $shortYear = substr($academicYear, -2); // 2026 -> 26
        return sprintf('%s-%s-%04d', strtoupper(trim($initials)), $shortYear, $count + 1);
    }

    /**
     * Generate initials from school name
     * Example: "Iqra Noorani Academy" -> "INA"
     */
    private static function generateInitials($schoolName)
    {
        // Split by spaces and get first letter of each word
        $words = explode(' ', trim($schoolName));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        // If no initials generated, use default
        return !empty($initials) ? $initials : 'SCH';
    }

    /**
     * Generate Registration Number using Short Code method
     * Format: Year + School Short Code + Sequential Number
     * Example: 2026 + 101 + 0001 = 20261010001
     */
    public static function generateRegistrationNumber($academicYear = null)
    {
        if (!$academicYear) {
            $academicYear = date('Y');
        }

        // Convert Bengali year to English if needed
        $academicYear = self::convertBengaliToEnglish($academicYear);

        // Get school short code from settings
        try {
            $schoolSettings = \App\Models\SchoolSetting::getSettings();
            $shortCode = $schoolSettings->short_code ?? '101'; // Default to 101 if not set
        } catch (\Exception $e) {
            $shortCode = '101';
        }

        // Get the next sequential number for this year - check if academic_year column exists
        try {
            $count = self::where('academic_year', $academicYear)->count();
        } catch (\Exception $e) {
            // If academic_year column doesn't exist, just count all students
            $count = self::count();
        }
        
        $sequentialNumber = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return $academicYear . $shortCode . $sequentialNumber;
    }

    /**
     * Convert Bengali numbers to English
     */
    private static function convertBengaliToEnglish($text)
    {
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($bengaliNumbers, $englishNumbers, $text);
    }

    /**
     * Auto-generate student_id and registration_number before creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->student_id)) {
                $student->student_id = self::generateStudentId($student->academic_year ?? date('Y'));
            }
            
            if (empty($student->registration_number)) {
                $student->registration_number = self::generateRegistrationNumber($student->academic_year ?? date('Y'));
            }
        });
    }

    // Get Age
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    // Scope for active students
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for class
    public function scopeClass($query, $class)
    {
        return $query->where('class', $class);
    }

    // Get full name
    public function getFullNameAttribute()
    {
        return $this->name_bn . ' (' . $this->name_en . ')';
    }

    // Get name (primary name field)
    public function getNameAttribute()
    {
        return $this->name_bn ?? $this->name_en ?? 'Unknown';
    }

    // Get photo URL
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Check if file exists and use tenant_asset
            $photoPath = storage_path('app/public/' . $this->photo);
            if (file_exists($photoPath)) {
                try {
                    return route('tenant.files', ['path' => $this->photo]);
                } catch (\Exception $e) {
                    return asset('storage/' . $this->photo);
                }
            }
        }
        
        // Generate avatar with first letter - use name_bn first, then fallback
        $name = $this->name_bn ?? $this->name_en ?? $this->name ?? 'Student';
        $initial = mb_substr($name, 0, 1); // Use mb_substr for Bengali characters
        return "https://ui-avatars.com/api/?name=" . urlencode($initial) . "&size=128&background=4F46E5&color=fff";
    }

    // Get document URL helper
    public function getDocumentUrl($field)
    {
        if ($this->$field) {
            try {
                return route('tenant.files', ['path' => $this->$field]);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    // Get document file extension
    public function getDocumentExtension($field)
    {
        if ($this->$field) {
            return strtolower(pathinfo($this->$field, PATHINFO_EXTENSION));
        }
        return null;
    }

    // Check if document is image
    public function isDocumentImage($field)
    {
        $extension = $this->getDocumentExtension($field);
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    // Check if document is PDF
    public function isDocumentPdf($field)
    {
        return $this->getDocumentExtension($field) === 'pdf';
    }

    // Get document file size (if file exists)
    public function getDocumentSize($field)
    {
        if ($this->$field && file_exists(storage_path('app/public/' . $this->$field))) {
            $bytes = filesize(storage_path('app/public/' . $this->$field));
            return $this->formatBytes($bytes);
        }
        return null;
    }

    // Format bytes to human readable format
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    // Get all document URLs
    public function getDocumentUrlsAttribute()
    {
        return [
            'birth_certificate_file' => $this->getDocumentUrl('birth_certificate_file'),
            'vaccination_card' => $this->getDocumentUrl('vaccination_card'),
            'father_nid_file' => $this->getDocumentUrl('father_nid_file'),
            'mother_nid_file' => $this->getDocumentUrl('mother_nid_file'),
            'previous_school_certificate' => $this->getDocumentUrl('previous_school_certificate'),
            'other_documents' => $this->getDocumentUrl('other_documents'),
        ];
    }

    // Get document info for a field
    public function getDocumentInfo($field)
    {
        if (!$this->$field) {
            return null;
        }

        return [
            'url' => $this->getDocumentUrl($field),
            'extension' => $this->getDocumentExtension($field),
            'is_image' => $this->isDocumentImage($field),
            'is_pdf' => $this->isDocumentPdf($field),
            'size' => $this->getDocumentSize($field),
            'filename' => basename($this->$field)
        ];
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    // Relations
    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function hostelAllocation()
    {
        return $this->hasOne(HostelAllocation::class);
    }

    public function transportAllocation()
    {
        return $this->hasOne(TransportAllocation::class);
    }

    public function results()
    {
        return $this->hasMany(StudentResult::class);
    }

    // Exam relationships
    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function promotions()
    {
        return $this->hasMany(StudentPromotion::class);
    }

    public function getExamResultsForExam($examId)
    {
        return $this->examResults()->where('exam_id', $examId)->with('subject')->get();
    }

    public function fees()
    {
        return $this->hasMany(FeeCollection::class, 'student_id');
    }
}

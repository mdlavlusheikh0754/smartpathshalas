<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'fee_type',
        'fee_name',
        'class_name',
        'amount',
        'description',
        'is_active',
        'is_mandatory',
        'frequency',
        'applicable_months',
        'academic_session_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_mandatory' => 'boolean',
        'applicable_months' => 'array'
    ];

    /**
     * Get the academic session
     */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Get fee structures by class
     */
    public static function getByClass($className, $sessionId = null)
    {
        $query = static::where('class_name', $className)
                      ->where('is_active', true);
        
        if ($sessionId) {
            $query->where('academic_session_id', $sessionId);
        }
        
        return $query->orderBy('fee_type')->get();
    }

    /**
     * Get fee structures by type
     */
    public static function getByType($feeType, $sessionId = null)
    {
        $query = static::where('fee_type', $feeType)
                      ->where('is_active', true);
        
        if ($sessionId) {
            $query->where('academic_session_id', $sessionId);
        }
        
        return $query->orderBy('class_name')->get();
    }

    /**
     * Get all active fee types
     */
    public static function getFeeTypes()
    {
        return [
            'admission' => 'ভর্তি/সেশন ফি',
            'monthly' => 'মাসিক বেতন',
            'exam' => 'পরীক্ষার ফি',
            'annual' => 'বার্ষিক ফি',
            'transport' => 'পরিবহন ফি',
            'library' => 'লাইব্রেরি ফি',
            'sports' => 'খেলাধুলা ফি',
            'development' => 'উন্নয়ন ফি',
            'computer' => 'কম্পিউটার ফি',
            'science_lab' => 'বিজ্ঞানাগার ফি',
            'other' => 'অন্যান্য ফি'
        ];
    }

    /**
     * Get all class names
     */
    public static function getClassNames()
    {
        return [
            '1' => 'প্রথম শ্রেণী',
            '2' => 'দ্বিতীয় শ্রেণী',
            '3' => 'তৃতীয় শ্রেণী',
            '4' => 'চতুর্থ শ্রেণী',
            '5' => 'পঞ্চম শ্রেণী',
            '6' => 'ষষ্ঠ শ্রেণী',
            '7' => 'সপ্তম শ্রেণী',
            '8' => 'অষ্টম শ্রেণী',
            '9' => 'নবম শ্রেণী',
            '10' => 'দশম শ্রেণী'
        ];
    }

    /**
     * Get frequency options
     */
    public static function getFrequencyOptions()
    {
        return [
            'one_time' => 'একবার',
            'monthly' => 'মাসিক',
            'quarterly' => 'ত্রৈমাসিক',
            'half_yearly' => 'অর্ধবার্ষিক',
            'yearly' => 'বার্ষিক'
        ];
    }

    /**
     * Get month options for applicable months
     */
    public static function getMonthOptions()
    {
        return [
            'january' => 'জানুয়ারি',
            'february' => 'ফেব্রুয়ারি',
            'march' => 'মার্চ',
            'april' => 'এপ্রিল',
            'may' => 'মে',
            'june' => 'জুন',
            'july' => 'জুলাই',
            'august' => 'আগস্ট',
            'september' => 'সেপ্টেম্বর',
            'october' => 'অক্টোবর',
            'november' => 'নভেম্বর',
            'december' => 'ডিসেম্বর'
        ];
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        $formattedAmount = number_format($this->amount, 0);
        
        // Convert English numbers to Bengali
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        $bengaliAmount = str_replace($englishNumbers, $bengaliNumbers, $formattedAmount);
        
        return '৳ ' . $bengaliAmount;
    }

    /**
     * Get fee type name in Bengali
     */
    public function getFeeTypeNameAttribute()
    {
        $types = static::getFeeTypes();
        return $types[$this->fee_type] ?? $this->fee_type;
    }

    /**
     * Get class name in Bengali
     */
    public function getClassNameBengaliAttribute()
    {
        $classes = static::getClassNames();
        return $classes[$this->class_name] ?? $this->class_name;
    }
}
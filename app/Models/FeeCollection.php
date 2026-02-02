<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_name',
        'student_class',
        'student_section',
        'fee_type',
        'fee_type_name',
        'month',
        'month_count',
        'year',
        'total_amount',
        'discount_amount',
        'zakat_amount',
        'grant_amount',
        'paid_amount',
        'due_amount',
        'inventory_cost',
        'payment_method',
        'inventory_items',
        'remarks',
        'collected_by',
        'collected_at',
        'academic_year',
        'receipt_number',
        'voucher_no',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'zakat_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'inventory_cost' => 'decimal:2',
        'month_count' => 'integer',
        'inventory_items' => 'array',
        'collected_at' => 'datetime'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feeCollection) {
            if (empty($feeCollection->receipt_number)) {
                $feeCollection->receipt_number = static::generateReceiptNumber();
            }
        });
    }

    /**
     * Generate unique receipt number
     */
    public static function generateReceiptNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return "RC{$year}{$month}" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the student that owns the fee collection
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return '৳ ' . number_format($this->total_amount, 0);
    }

    /**
     * Get formatted paid amount
     */
    public function getFormattedPaidAmountAttribute()
    {
        return '৳ ' . number_format($this->paid_amount, 0);
    }

    /**
     * Get formatted due amount
     */
    public function getFormattedDueAmountAttribute()
    {
        return '৳ ' . number_format($this->due_amount, 0);
    }

    /**
     * Get status name in Bengali
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'completed' => 'সম্পূর্ণ',
            'partial' => 'আংশিক',
            'cancelled' => 'বাতিল'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get fee type name in Bengali
     */
    public function getFeeTypeNameAttribute()
    {
        $types = [
            'admission' => 'ভর্তি ফি',
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

        return $types[$this->fee_type] ?? $this->fee_type;
    }

    /**
     * Scope for current academic year
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('academic_year', date('Y'));
    }

    /**
     * Scope for specific fee type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('fee_type', $type);
    }

    /**
     * Scope for specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}

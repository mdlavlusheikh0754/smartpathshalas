<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_class',
        'to_class',
        'from_section',
        'to_section',
        'academic_year',
        'promotion_type',
        'remarks',
        'promotion_date',
        'promoted_by'
    ];

    protected $casts = [
        'promotion_date' => 'date'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function promotedBy()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }

    // Scopes
    public function scopePromoted($query)
    {
        return $query->where('promotion_type', 'promoted');
    }

    public function scopeRepeated($query)
    {
        return $query->where('promotion_type', 'repeated');
    }

    public function scopeTransferred($query)
    {
        return $query->where('promotion_type', 'transferred');
    }

    public function scopeForAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    // Accessors
    public function getPromotionTypeBadgeAttribute()
    {
        $types = [
            'promoted' => ['class' => 'bg-green-100 text-green-800', 'text' => 'প্রমোটেড', 'icon' => '⬆️'],
            'repeated' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'পুনরাবৃত্তি', 'icon' => '🔄'],
            'transferred' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'স্থানান্তরিত', 'icon' => '↔️']
        ];

        return $types[$this->promotion_type] ?? $types['promoted'];
    }

    public function getFromClassFullAttribute()
    {
        return $this->from_class . ($this->from_section ? " - {$this->from_section}" : '');
    }

    public function getToClassFullAttribute()
    {
        return $this->to_class . ($this->to_section ? " - {$this->to_section}" : '');
    }
}
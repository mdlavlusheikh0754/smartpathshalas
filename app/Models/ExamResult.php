<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'obtained_marks',
        'original_marks',
        'monthly_average',  // Changed from monthly_marks to monthly_average
        'total_marks',
        'percentage',
        'grade',
        'status',
        'remarks',
        'entered_at',
        'entered_by',
        'pass_marks',
        'gpa',
        'is_absent',
        'monthly_marks_1',
        'monthly_marks_2',
        'monthly_marks_3'
    ];

    protected $casts = [
        'obtained_marks' => 'decimal:2',
        'original_marks' => 'decimal:2',
        'monthly_average' => 'decimal:2',  // Changed from monthly_marks
        'monthly_marks_1' => 'decimal:2',
        'monthly_marks_2' => 'decimal:2',
        'monthly_marks_3' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'gpa' => 'decimal:2',
        'is_absent' => 'boolean',
        'entered_at' => 'datetime'
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    // Scopes
    public function scopePassed($query)
    {
        return $query->where('status', 'pass');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'fail');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    // Accessors
    public function getGradeBadgeAttribute()
    {
        $grades = [
            'A+' => ['class' => 'bg-green-100 text-green-800', 'text' => 'A+'],
            'A' => ['class' => 'bg-green-100 text-green-800', 'text' => 'A'],
            'A-' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'A-'],
            'B+' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'B+'],
            'B' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'B'],
            'C+' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'C+'],
            'C' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'C'],
            'D' => ['class' => 'bg-red-100 text-red-800', 'text' => 'D'],
            'F' => ['class' => 'bg-red-100 text-red-800', 'text' => 'F']
        ];

        return $grades[$this->grade] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'N/A'];
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pass' => ['class' => 'bg-green-100 text-green-800', 'text' => 'পাস', 'icon' => '✅'],
            'fail' => ['class' => 'bg-red-100 text-red-800', 'text' => 'ফেইল', 'icon' => '❌'],
            'absent' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'অনুপস্থিত', 'icon' => '❓']
        ];

        return $statuses[$this->status] ?? $statuses['fail'];
    }

    // Methods
    public function calculateGrade()
    {
        if ($this->status === 'absent') {
            $this->grade = 'F';
            return;
        }

        $percentage = $this->percentage;
        
        if ($percentage >= 80) {
            $this->grade = 'A+';
        } elseif ($percentage >= 70) {
            $this->grade = 'A';
        } elseif ($percentage >= 60) {
            $this->grade = 'A-';
        } elseif ($percentage >= 50) {
            $this->grade = 'B+';
        } elseif ($percentage >= 40) {
            $this->grade = 'B';
        } elseif ($percentage >= 33) {
            $this->grade = 'C+';
        } elseif ($percentage >= 25) {
            $this->grade = 'C';
        } elseif ($percentage >= 10) {
            $this->grade = 'D';
        } else {
            $this->grade = 'F';
        }
    }

    public function calculatePercentage()
    {
        if ($this->total_marks > 0) {
            $this->percentage = ($this->obtained_marks / $this->total_marks) * 100;
        } else {
            $this->percentage = 0;
        }
    }

    public function calculateStatus()
    {
        if ($this->status === 'absent') {
            return;
        }

        $examSubject = ExamSubject::where('exam_id', $this->exam_id)
                                 ->where('subject_id', $this->subject_id)
                                 ->first();
        
        $passMarks = $examSubject ? $examSubject->pass_marks : 33;
        
        $this->status = $this->percentage >= $passMarks ? 'pass' : 'fail';
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($result) {
            $result->calculatePercentage();
            $result->calculateStatus();
            $result->calculateGrade();
        });
    }
}
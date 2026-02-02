<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'exam_type',
        'month',
        'description',
        'start_date',
        'end_date',
        'status',
        'total_marks',
        'pass_marks',
        'classes',
        'subjects',
        'is_published',
        'published_at',
        'term_exam_id',
        'semester_exam_id',
        'academic_year'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'classes' => 'array',
        'subjects' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    // Relationships
    public function examSubjects()
    {
        return $this->hasMany(ExamSubject::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'exam_subjects')
                    ->withPivot(['exam_date', 'start_time', 'end_time', 'total_marks', 'pass_marks', 'instructions'])
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'upcoming' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'আসন্ন', 'icon' => '📅'],
            'ongoing' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'চলমান', 'icon' => '⏳'],
            'completed' => ['class' => 'bg-green-100 text-green-800', 'text' => 'সম্পন্ন', 'icon' => '✅']
        ];

        return $badges[$this->status] ?? $badges['upcoming'];
    }

    public function getParticipantsCountAttribute()
    {
        return $this->results()->distinct('student_id')->count();
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // Methods
    public function updateStatus()
    {
        $now = Carbon::now()->toDateString();
        
        if ($now < $this->start_date) {
            $this->status = 'upcoming';
        } elseif ($now >= $this->start_date && $now <= $this->end_date) {
            $this->status = 'ongoing';
        } else {
            $this->status = 'completed';
        }
        
        $this->save();
    }

    public function canEdit()
    {
        return $this->status === 'upcoming';
    }

    public function canDelete()
    {
        // Allow deletion of all exams for now
        return true;
    }

    public function publish()
    {
        $this->is_published = true;
        $this->published_at = now();
        $this->save();
    }

    public function unpublish()
    {
        $this->is_published = false;
        $this->published_at = null;
        $this->save();
    }

    /**
     * Generate a unique term exam ID
     */
    public function generateTermExamId()
    {
        $prefix = '';
        
        switch ($this->exam_type) {
            case 'first_semester':
                $prefix = 'FS';
                break;
            case 'second_semester':
                $prefix = 'SS';
                break;
            case 'half_yearly':
                $prefix = 'HE';
                break;
            case 'annual':
                $prefix = 'AE';
                break;
            case 'weekly':
                $prefix = 'WE';
                break;
            case 'monthly':
                $prefix = 'ME';
                break;
            case 'test':
                $prefix = 'TE';
                break;
            default:
                $prefix = 'EX';
        }
        
        // Add year and a unique number
        $year = date('Y');
        $uniqueNumber = str_pad($this->id, 3, '0', STR_PAD_LEFT);
        
        return $prefix . $year . $uniqueNumber;
    }

    /**
     * Generate a unique semester exam ID
     */
    public function generateSemesterExamId()
    {
        $prefix = '';
        
        switch ($this->exam_type) {
            case 'first_semester':
                $prefix = 'SEM-FS';
                break;
            case 'second_semester':
                $prefix = 'SEM-SS';
                break;
            case 'half_yearly':
                $prefix = 'SEM-HE';
                break;
            case 'annual':
                $prefix = 'SEM-AE';
                break;
            case 'weekly':
                $prefix = 'SEM-WE';
                break;
            case 'monthly':
                $prefix = 'SEM-ME';
                break;
            case 'test':
                $prefix = 'SEM-TE';
                break;
            default:
                $prefix = 'SEM-EX';
        }
        
        // Add year and a unique number
        $year = date('Y');
        $uniqueNumber = str_pad($this->id, 3, '0', STR_PAD_LEFT);
        
        return $prefix . $year . $uniqueNumber;
    }

    /**
     * Boot method to auto-generate term_exam_id and semester_exam_id
     */
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($exam) {
            $updated = false;
            
            if (empty($exam->term_exam_id)) {
                $exam->term_exam_id = $exam->generateTermExamId();
                $updated = true;
            }
            
            if (empty($exam->semester_exam_id)) {
                $exam->semester_exam_id = $exam->generateSemesterExamId();
                $updated = true;
            }
            
            if ($updated) {
                $exam->save();
            }
        });
    }
}
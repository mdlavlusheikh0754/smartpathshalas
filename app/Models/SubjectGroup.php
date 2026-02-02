<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'exam_id',
        'class_id',
        'total_marks',
        'pass_marks',
        'subject_ids',
        'description',
        'is_active'
    ];

    protected $casts = [
        'subject_ids' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subjects()
    {
        return Subject::whereIn('id', $this->subject_ids ?? [])->get();
    }

    // Helper methods
    public function getSubjectsAttribute()
    {
        return Subject::whereIn('id', $this->subject_ids ?? [])->get();
    }

    public function addSubject($subjectId)
    {
        $subjectIds = $this->subject_ids ?? [];
        if (!in_array($subjectId, $subjectIds)) {
            $subjectIds[] = $subjectId;
            $this->subject_ids = $subjectIds;
            $this->save();
        }
    }

    public function removeSubject($subjectId)
    {
        $subjectIds = $this->subject_ids ?? [];
        $this->subject_ids = array_values(array_filter($subjectIds, function($id) use ($subjectId) {
            return $id != $subjectId;
        }));
        $this->save();
    }
}
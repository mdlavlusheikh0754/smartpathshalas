<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRfidMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'rfid_number',
        'is_active',
        'assigned_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the student that owns this RFID mapping
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope for active mappings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find student by RFID number
     */
    public static function findStudentByRfid(string $rfidNumber): ?Student
    {
        $mapping = static::active()
            ->where('rfid_number', $rfidNumber)
            ->first();

        return $mapping?->student;
    }

    /**
     * Assign RFID to student
     */
    public static function assignRfidToStudent(int $studentId, string $rfidNumber): self
    {
        // Deactivate any existing mapping for this RFID
        static::where('rfid_number', $rfidNumber)->update(['is_active' => false]);
        
        // Deactivate any existing mapping for this student
        static::where('student_id', $studentId)->update(['is_active' => false]);

        // Create new mapping
        return static::create([
            'student_id' => $studentId,
            'rfid_number' => $rfidNumber,
            'is_active' => true,
            'assigned_at' => now(),
        ]);
    }

    /**
     * Deactivate RFID mapping
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'student_id',
        'rfid_number',
        'punch_timestamp',
        'punch_type',
        'device_timestamp',
        'sync_timestamp',
        'is_duplicate',
    ];

    protected $casts = [
        'punch_timestamp' => 'datetime',
        'device_timestamp' => 'datetime',
        'sync_timestamp' => 'datetime',
        'is_duplicate' => 'boolean',
    ];

    /**
     * Get the student that owns the attendance record
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the device that recorded this attendance
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(ZktecoDevice::class, 'device_id', 'device_id');
    }

    /**
     * Scope for today's attendance
     */
    public function scopeToday($query)
    {
        return $query->whereDate('punch_timestamp', today());
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('punch_timestamp', [$startDate, $endDate]);
    }

    /**
     * Scope for specific punch type
     */
    public function scopePunchType($query, $type)
    {
        return $query->where('punch_type', $type);
    }

    /**
     * Scope for non-duplicate records
     */
    public function scopeValid($query)
    {
        return $query->where('is_duplicate', false);
    }

    /**
     * Check if this record is a potential duplicate
     */
    public function isPotentialDuplicate(): bool
    {
        return static::where('student_id', $this->student_id)
            ->where('punch_type', $this->punch_type)
            ->where('device_timestamp', '>=', $this->device_timestamp->subSeconds(60))
            ->where('device_timestamp', '<=', $this->device_timestamp->addSeconds(60))
            ->where('id', '!=', $this->id)
            ->exists();
    }

    /**
     * Mark as duplicate
     */
    public function markAsDuplicate(): void
    {
        $this->update(['is_duplicate' => true]);
    }
}
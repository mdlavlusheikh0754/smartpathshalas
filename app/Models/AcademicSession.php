<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AcademicSession extends Model
{
    protected $fillable = [
        'session_name',
        'start_date',
        'end_date',
        'is_current',
        'is_active',
        'description',
        'total_students',
        'total_teachers',
        'total_staff',
        'total_classrooms'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the current active session
     */
    public static function getCurrentSession()
    {
        return static::where('is_current', true)->first();
    }

    /**
     * Get all active sessions
     */
    public static function getActiveSessions()
    {
        return static::where('is_active', true)->orderBy('start_date', 'desc')->get();
    }

    /**
     * Set this session as current (and unset others)
     */
    public function setAsCurrent()
    {
        // First, unset all other sessions as current
        static::where('is_current', true)->update(['is_current' => false]);
        
        // Then set this session as current
        $this->update(['is_current' => true]);
    }

    /**
     * Check if session is ongoing
     */
    public function isOngoing()
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Get formatted session duration
     */
    public function getFormattedDuration()
    {
        return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
    }
}
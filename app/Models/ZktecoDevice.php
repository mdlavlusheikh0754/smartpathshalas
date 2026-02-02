<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZktecoDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'name',
        'ip_address',
        'port',
        'location',
        'is_active',
        'last_heartbeat',
        'firmware_version',
        'total_capacity',
        'current_users',
        'current_records',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_heartbeat' => 'datetime',
        'port' => 'integer',
        'total_capacity' => 'integer',
        'current_users' => 'integer',
        'current_records' => 'integer',
    ];

    /**
     * Get attendance records for this device
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'device_id', 'device_id');
    }

    /**
     * Check if device is online (heartbeat within last 5 minutes)
     */
    public function isOnline(): bool
    {
        if (!$this->last_heartbeat) {
            return false;
        }
        
        return $this->last_heartbeat->diffInMinutes(now()) <= 5;
    }

    /**
     * Update device heartbeat
     */
    public function updateHeartbeat(): void
    {
        $this->update(['last_heartbeat' => now()]);
    }

    /**
     * Scope for active devices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for online devices
     */
    public function scopeOnline($query)
    {
        return $query->where('last_heartbeat', '>=', now()->subMinutes(5));
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'priority',
        'status',
        'attachment',
        'publish_date',
        'expire_date',
        'author_id',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expire_date' => 'date',
    ];

    /**
     * Get the author of the notice
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Check if notice is published
     */
    public function isPublished()
    {
        return !$this->publish_date || $this->publish_date->isPast();
    }

    /**
     * Check if notice is expired
     */
    public function isExpired()
    {
        return $this->expire_date && $this->expire_date->isPast();
    }

    /**
     * Scope for active notices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for published notices
     */
    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('publish_date')
              ->orWhere('publish_date', '<=', now());
        });
    }

    /**
     * Scope for non-expired notices
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expire_date')
              ->orWhere('expire_date', '>=', now());
        });
    }
}

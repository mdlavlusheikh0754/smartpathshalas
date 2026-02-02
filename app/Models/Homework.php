<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homework';

    protected $fillable = [
        'title',
        'description',
        'subject',
        'class',
        'section',
        'assigned_date',
        'due_date',
        'status',
        'attachment',
        'instructions',
        'teacher_id'
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Get homework by class and section
     */
    public static function getByClass($class, $section = null)
    {
        $query = static::where('class', $class);
        
        if ($section) {
            $query->where('section', $section);
        }
        
        return $query->where('status', 'active')
                    ->orderBy('due_date', 'asc')
                    ->get();
    }

    /**
     * Get recent homework
     */
    public static function getRecent($limit = 10)
    {
        return static::where('status', 'active')
                    ->orderBy('assigned_date', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get upcoming homework (due soon)
     */
    public static function getUpcoming($days = 7)
    {
        return static::where('status', 'active')
                    ->whereBetween('due_date', [now(), now()->addDays($days)])
                    ->orderBy('due_date', 'asc')
                    ->get();
    }

    /**
     * Check if homework is overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now()->toDateString() && $this->status === 'active';
    }

    /**
     * Get attachment URL
     */
    public function getAttachmentUrl()
    {
        if ($this->attachment) {
            $tenantDomain = tenant('id') . '.smartpathshala.test';
            return 'http://' . $tenantDomain . '/storage/' . $this->attachment;
        }
        return null;
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        switch ($this->status) {
            case 'completed':
                return 'green';
            case 'overdue':
                return 'red';
            default:
                return $this->isOverdue() ? 'red' : 'blue';
        }
    }

    /**
     * Get status text in Bangla
     */
    public function getStatusText()
    {
        switch ($this->status) {
            case 'completed':
                return 'সম্পন্ন';
            case 'overdue':
                return 'সময় শেষ';
            default:
                return $this->isOverdue() ? 'সময় শেষ' : 'চলমান';
        }
    }
}

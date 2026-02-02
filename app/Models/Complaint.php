<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'complainant_name',
        'complainant_type',
        'complainant_id',
        'contact_number',
        'email',
        'complaint_type',
        'subject',
        'description',
        'expected_solution',
        'priority',
        'status',
        'is_anonymous',
        'attachments',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'attachments' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}

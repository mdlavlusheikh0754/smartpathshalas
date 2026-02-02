<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'room_id',
        'allocation_date',
        'release_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'release_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

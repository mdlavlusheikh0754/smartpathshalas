<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'student_attendances';

    protected $fillable = [
        'student_id',
        'attendance_date',
        'status',
        'academic_year',
        'class',
        'section',
        'check_in_time',
        'check_out_time',
        'marked_by',
        'remarks',
        'sms_sent',
        'sms_sent_at',
        'device_user_id',
        'verify_type',
        'in_out_mode',
        'device_timestamp'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

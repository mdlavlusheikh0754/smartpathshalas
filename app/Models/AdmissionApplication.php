<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $fillable = [
        'application_id',
        'student_type',
        'roll_number',
        'name_bn',
        'name_en',
        'father_name',
        'mother_name',
        'date_of_birth',
        'birth_certificate_no',
        'gender',
        'religion',
        'nationality',
        'present_address',
        'permanent_address',
        'phone',
        'email',
        'class',
        'section',
        'group',
        'previous_school',
        'photo',
        'status',
        'payment_status',
        'transaction_id',
        'remarks',
        'father_mobile',
        'father_occupation',
        'father_nid',
        'father_email',
        'father_income',
        'mother_mobile',
        'mother_occupation',
        'mother_nid',
        'mother_email',
        'guardian_name',
        'guardian_mobile',
        'guardian_relation',
        'guardian_address',
        'special_needs',
        'health_condition',
        'emergency_contact',
        'birth_certificate_file',
        'vaccination_card',
        'father_nid_file',
        'mother_nid_file',
        'previous_school_certificate',
        'other_documents',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];
}

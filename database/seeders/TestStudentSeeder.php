<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class TestStudentSeeder extends Seeder
{
    public function run()
    {
        // Create test students
        $students = [
            [
                'name_bn' => 'মোঃ লাভলু সেখ',
                'name_en' => 'Md. Lavlu Sheikh',
                'father_name' => 'মোঃ আব্দুল করিম',
                'mother_name' => 'মোসাম্মৎ রাহেলা বেগম',
                'class' => 'প্রথম',
                'section' => 'A',
                'roll' => '01',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2010-01-15',
                'gender' => 'male',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01712345678',
                'status' => 'active',
                'eiin_number' => '123456',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
            ],
            [
                'name_bn' => 'ফাতিমা খাতুন',
                'name_en' => 'Fatima Khatun',
                'father_name' => 'মোঃ আব্দুর রহমান',
                'mother_name' => 'মোসাম্মৎ সালমা বেগম',
                'class' => 'দ্বিতীয়',
                'section' => 'A',
                'roll' => '02',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2009-03-20',
                'gender' => 'female',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01812345678',
                'status' => 'active',
                'eiin_number' => '123456',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
            ],
            [
                'name_bn' => 'আহমেদ হাসান',
                'name_en' => 'Ahmed Hasan',
                'father_name' => 'মোঃ আব্দুল হামিদ',
                'mother_name' => 'মোসাম্মৎ নাসরিন আক্তার',
                'class' => 'তৃতীয়',
                'section' => 'B',
                'roll' => '03',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2008-07-10',
                'gender' => 'male',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01912345678',
                'status' => 'active',
                'eiin_number' => '123456',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
            ]
        ];

        foreach ($students as $index => $studentData) {
            try {
                // Add unique student_id and registration_number for each student
                $studentData['student_id'] = 'INA-26-000' . ($index + 1);
                $studentData['registration_number'] = '2026101000' . ($index + 1);
                Student::create($studentData);
                echo "Created student: " . $studentData['name_bn'] . "\n";
            } catch (\Exception $e) {
                echo "Error creating student " . $studentData['name_bn'] . ": " . $e->getMessage() . "\n";
            }
        }
    }
}
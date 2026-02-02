<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;

class ComprehensiveResultSeeder extends Seeder
{
    public function run()
    {
        // Create class
        $class = SchoolClass::firstOrCreate([
            'name' => 'পঞ্চম',
            'section' => 'ক'
        ], [
            'is_active' => true
        ]);

        // Create subjects
        $subjectData = [
            ['name' => 'বাংলা', 'code' => 'BAN5'],
            ['name' => 'ইংরেজি', 'code' => 'ENG5'],
            ['name' => 'গণিত', 'code' => 'MAT5'],
            ['name' => 'বিজ্ঞান', 'code' => 'SCI5'],
            ['name' => 'সমাজ', 'code' => 'SOC5'],
            ['name' => 'ধর্ম', 'code' => 'REL5'],
            ['name' => 'চারু ও কারুকলা', 'code' => 'ART5'],
            ['name' => 'শারীরিক শিক্ষা', 'code' => 'PHY5']
        ];

        $subjects = [];
        foreach ($subjectData as $data) {
            $subject = Subject::firstOrCreate([
                'name' => $data['name'],
                'class_id' => $class->id
            ], [
                'code' => $data['code'],
                'total_marks' => 100,
                'pass_marks' => 33,
                'is_active' => true,
                'type' => 'mandatory'
            ]);
            $subjects[] = $subject;
        }

        // Create exam
        $exam = Exam::firstOrCreate([
            'name' => 'বার্ষিক পরীক্ষা ২০২৬'
        ], [
            'exam_type' => 'annual',
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-15',
            'total_marks' => 800,
            'pass_marks' => 264,
            'status' => 'completed',
            'is_published' => true
        ]);

        // Create exam subjects
        foreach ($subjects as $subject) {
            ExamSubject::firstOrCreate([
                'exam_id' => $exam->id,
                'subject_id' => $subject->id
            ], [
                'total_marks' => 100,
                'pass_marks' => 33,
                'exam_date' => '2026-11-' . rand(1, 15),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00'
            ]);
        }

        // Create sample students
        $studentNames = [
            'মোহাম্মদ রহিম',
            'ফাতেমা খাতুন',
            'আব্দুল করিম',
            'রাহেলা বেগম',
            'মোস্তফা কামাল',
            'সালমা আক্তার',
            'আবুল হাসান',
            'নাসরিন সুলতানা',
            'মাহবুব আলম',
            'রোকেয়া খাতুন',
            'জাহিদ হাসান',
            'শাহিনা পারভীন',
            'আনিসুর রহমান',
            'নূরজাহান বেগম',
            'ইব্রাহিম খলিল',
            'তাসলিমা খাতুন',
            'আবদুর রহমান',
            'হাসিনা আক্তার',
            'মনিরুল ইসলাম',
            'সাবিনা ইয়াসমিন'
        ];

        $students = [];
        foreach ($studentNames as $index => $name) {
            $student = Student::firstOrCreate([
                'roll' => $index + 1,
                'class' => $class->name,
                'section' => $class->section
            ], [
                'name_bn' => $name,
                'name_en' => 'Student ' . ($index + 1),
                'eiin_number' => '123456',
                'board' => 'Dhaka',
                'registration_number' => 'REG' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'father_name' => 'পিতার নাম',
                'mother_name' => 'মাতার নাম',
                'date_of_birth' => '2010-01-01',
                'birth_certificate_no' => 'BC' . str_pad($index + 1, 10, '0', STR_PAD_LEFT),
                'gender' => $index % 2 == 0 ? 'male' : 'female',
                'religion' => 'Islam',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01700000' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'admission_date' => '2026-01-01',
                'admission_type' => 'Regular',
                'status' => 'active',
                'academic_year' => '2026'
            ]);
            $students[] = $student;
        }

        // Create exam results with realistic marks
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                // Generate realistic marks (some students perform better than others)
                $studentPerformance = rand(40, 95); // Base performance level
                $marks = rand(max(0, $studentPerformance - 20), min(100, $studentPerformance + 10));

                // Some students might be absent in some subjects (5% chance)
                $isAbsent = rand(1, 100) <= 5;

                if ($isAbsent) {
                    $status = 'absent';
                    $marks = 0; // Set to 0 instead of null for absent students
                } else {
                    $status = $marks >= 33 ? 'pass' : 'fail';
                }

                ExamResult::firstOrCreate([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject->id,
                    'student_id' => $student->id
                ], [
                    'obtained_marks' => $marks,
                    'total_marks' => 100,
                    'status' => $status
                ]);
            }
        }

        $this->command->info('Comprehensive result data created successfully!');
        $this->command->info('Class: ' . $class->name . ' - ' . $class->section);
        $this->command->info('Exam: ' . $exam->name);
        $this->command->info('Students: ' . count($students));
        $this->command->info('Subjects: ' . count($subjects));
    }
}
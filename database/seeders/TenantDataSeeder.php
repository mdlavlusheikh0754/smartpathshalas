<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\ExamSubject;
use Carbon\Carbon;

class TenantDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting tenant data seeding...');
        
        // Create School Classes
        $this->createClasses();
        
        // Create Subjects
        $this->createSubjects();
        
        // Create Exams
        $this->createExams();
        
        // Create Students
        $this->createStudents();
        
        // Create Exam Subjects (link exams with subjects)
        $this->createExamSubjects();
        
        $this->command->info('Tenant data seeding completed successfully!');
    }
    
    private function createClasses()
    {
        $this->command->info('Creating school classes...');
        
        // Check if classes already exist
        if (SchoolClass::count() > 0) {
            $this->command->info('School classes already exist, skipping...');
            return;
        }
        
        $classes = [
            ['name' => 'প্রথম', 'section' => 'A', 'students' => 0, 'teachers' => 0],
            ['name' => 'প্রথম', 'section' => 'B', 'students' => 0, 'teachers' => 0],
            ['name' => 'দ্বিতীয়', 'section' => 'A', 'students' => 0, 'teachers' => 0],
            ['name' => 'দ্বিতীয়', 'section' => 'B', 'students' => 0, 'teachers' => 0],
            ['name' => 'তৃতীয়', 'section' => 'A', 'students' => 0, 'teachers' => 0],
            ['name' => 'তৃতীয়', 'section' => 'B', 'students' => 0, 'teachers' => 0],
            ['name' => 'চতুর্থ', 'section' => 'A', 'students' => 0, 'teachers' => 0],
            ['name' => 'পঞ্চম', 'section' => 'A', 'students' => 0, 'teachers' => 0],
        ];
        
        foreach ($classes as $classData) {
            SchoolClass::create($classData);
        }
        
        $this->command->info('School classes created successfully!');
    }
    
    private function createSubjects()
    {
        $this->command->info('Creating subjects...');
        
        // Check if subjects already exist
        if (Subject::count() > 0) {
            $this->command->info('Subjects already exist, skipping...');
            return;
        }
        
        $subjects = [
            // Class 1-2 subjects
            ['name' => 'বাংলা', 'code' => 'BAN', 'class_id' => 1, 'type' => 'core'],
            ['name' => 'ইংরেজি', 'code' => 'ENG', 'class_id' => 1, 'type' => 'core'],
            ['name' => 'গণিত', 'code' => 'MAT', 'class_id' => 1, 'type' => 'core'],
            ['name' => 'ইসলাম শিক্ষা', 'code' => 'ISL', 'class_id' => 1, 'type' => 'core'],
            
            ['name' => 'বাংলা', 'code' => 'BAN', 'class_id' => 3, 'type' => 'core'],
            ['name' => 'ইংরেজি', 'code' => 'ENG', 'class_id' => 3, 'type' => 'core'],
            ['name' => 'গণিত', 'code' => 'MAT', 'class_id' => 3, 'type' => 'core'],
            ['name' => 'ইসলাম শিক্ষা', 'code' => 'ISL', 'class_id' => 3, 'type' => 'core'],
            
            // Class 3-5 subjects
            ['name' => 'বাংলা', 'code' => 'BAN', 'class_id' => 5, 'type' => 'core'],
            ['name' => 'ইংরেজি', 'code' => 'ENG', 'class_id' => 5, 'type' => 'core'],
            ['name' => 'গণিত', 'code' => 'MAT', 'class_id' => 5, 'type' => 'core'],
            ['name' => 'বিজ্ঞান', 'code' => 'SCI', 'class_id' => 5, 'type' => 'core'],
            ['name' => 'সমাজ বিজ্ঞান', 'code' => 'SOC', 'class_id' => 5, 'type' => 'core'],
            ['name' => 'ইসলাম শিক্ষা', 'code' => 'ISL', 'class_id' => 5, 'type' => 'core'],
        ];
        
        foreach ($subjects as $subjectData) {
            Subject::create($subjectData);
        }
        
        $this->command->info('Subjects created successfully!');
    }
    
    private function createExams()
    {
        $this->command->info('Creating exams...');
        
        // Check if exams already exist
        if (Exam::count() > 0) {
            $this->command->info('Exams already exist, skipping...');
            return;
        }
        
        $exams = [
            [
                'name' => 'প্রথম সাময়িক পরীক্ষা ২০২৬',
                'exam_type' => 'half_yearly',
                'description' => 'প্রথম সাময়িক পরীক্ষা - জানুয়ারি ২০২৬',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(5),
                'status' => 'active',
                'total_marks' => 100,
                'pass_marks' => 33,
                'classes' => ['প্রথম', 'দ্বিতীয়', 'তৃতীয়', 'চতুর্থ', 'পঞ্চম']
            ],
            [
                'name' => 'দ্বিতীয় সাময়িক পরীক্ষা ২০২৬',
                'exam_type' => 'half_yearly',
                'description' => 'দ্বিতীয় সাময়িক পরীক্ষা - মার্চ ২০২৬',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(40),
                'status' => 'upcoming',
                'total_marks' => 100,
                'pass_marks' => 33,
                'classes' => ['প্রথম', 'দ্বিতীয়', 'তৃতীয়', 'চতুর্থ', 'পঞ্চম']
            ],
            [
                'name' => 'বার্ষিক পরীক্ষা ২০২৬',
                'exam_type' => 'annual',
                'description' => 'বার্ষিক পরীক্ষা - নভেম্বর ২০২৬',
                'start_date' => Carbon::now()->addDays(300),
                'end_date' => Carbon::now()->addDays(315),
                'status' => 'upcoming',
                'total_marks' => 100,
                'pass_marks' => 33,
                'classes' => ['প্রথম', 'দ্বিতীয়', 'তৃতীয়', 'চতুর্থ', 'পঞ্চম']
            ]
        ];

        foreach ($exams as $examData) {
            Exam::create($examData);
        }

        $this->command->info('Exams created successfully!');
    }
    
    private function createStudents()
    {
        $this->command->info('Creating students...');
        
        // Check if students already exist
        if (Student::count() > 0) {
            $this->command->info('Students already exist, skipping...');
            return;
        }
        
        $students = [
            // Class 1A students
            [
                'name_bn' => 'মোঃ রাহুল ইসলাম',
                'name_en' => 'Md. Rahul Islam',
                'father_name' => 'মোঃ আব্দুল করিম',
                'mother_name' => 'মোসাম্মৎ রাহেলা বেগম',
                'class' => 'প্রথম',
                'section' => 'A',
                'roll' => '01',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2018-01-15',
                'gender' => 'male',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01712345678',
                'status' => 'active',
                'eiin_number' => '130512',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
                'student_id' => 'INA-26-0001',
                'registration_number' => '20261010001',
            ],
            [
                'name_bn' => 'ফাতিমা খাতুন',
                'name_en' => 'Fatima Khatun',
                'father_name' => 'মোঃ আব্দুর রহমান',
                'mother_name' => 'মোসাম্মৎ সালমা বেগম',
                'class' => 'প্রথম',
                'section' => 'A',
                'roll' => '02',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2018-03-20',
                'gender' => 'female',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01812345678',
                'status' => 'active',
                'eiin_number' => '130512',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
                'student_id' => 'INA-26-0002',
                'registration_number' => '20261010002',
            ],
            [
                'name_bn' => 'আহমেদ হাসান',
                'name_en' => 'Ahmed Hasan',
                'father_name' => 'মোঃ আব্দুল হামিদ',
                'mother_name' => 'মোসাম্মৎ নাসরিন আক্তার',
                'class' => 'প্রথম',
                'section' => 'A',
                'roll' => '03',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2018-07-10',
                'gender' => 'male',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01912345678',
                'status' => 'active',
                'eiin_number' => '130512',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
                'student_id' => 'INA-26-0003',
                'registration_number' => '20261010003',
            ],
            // Class 2A students
            [
                'name_bn' => 'সাকিব আল হাসান',
                'name_en' => 'Sakib Al Hasan',
                'father_name' => 'মোঃ আলী হোসেন',
                'mother_name' => 'মোসাম্মৎ রোকেয়া বেগম',
                'class' => 'দ্বিতীয়',
                'section' => 'A',
                'roll' => '01',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2017-05-12',
                'gender' => 'male',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01612345678',
                'status' => 'active',
                'eiin_number' => '130512',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
                'student_id' => 'INA-26-0004',
                'registration_number' => '20261010004',
            ],
            [
                'name_bn' => 'আয়েশা সিদ্দিকা',
                'name_en' => 'Ayesha Siddika',
                'father_name' => 'মোঃ জাহিদুল ইসলাম',
                'mother_name' => 'মোসাম্মৎ শাহনাজ পারভীন',
                'class' => 'দ্বিতীয়',
                'section' => 'A',
                'roll' => '02',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2017-09-08',
                'gender' => 'female',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01512345678',
                'status' => 'active',
                'eiin_number' => '130512',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
                'student_id' => 'INA-26-0005',
                'registration_number' => '20261010005',
            ],
            // Class 3A students
            [
                'name_bn' => 'তামিম ইকবাল',
                'name_en' => 'Tamim Iqbal',
                'father_name' => 'মোঃ ইকবাল হোসেন',
                'mother_name' => 'মোসাম্মৎ তাহমিনা আক্তার',
                'class' => 'তৃতীয়',
                'section' => 'A',
                'roll' => '01',
                'admission_date' => '2026-01-01',
                'date_of_birth' => '2016-11-25',
                'gender' => 'male',
                'religion' => 'Islam',
                'nationality' => 'Bangladeshi',
                'present_address' => 'ঢাকা, বাংলাদেশ',
                'permanent_address' => 'ঢাকা, বাংলাদেশ',
                'parent_phone' => '01412345678',
                'status' => 'active',
                'eiin_number' => '130512',
                'board' => 'Dhaka',
                'admission_type' => 'Regular',
                'academic_year' => '২০২৬',
                'student_id' => 'INA-26-0006',
                'registration_number' => '20261010006',
            ]
        ];

        foreach ($students as $studentData) {
            try {
                Student::create($studentData);
            } catch (\Exception $e) {
                $this->command->error("Error creating student " . $studentData['name_bn'] . ": " . $e->getMessage());
            }
        }

        $this->command->info('Students created successfully!');
    }
    
    private function createExamSubjects()
    {
        $this->command->info('Creating exam subjects...');
        
        // Check if exam subjects already exist
        if (ExamSubject::count() > 0) {
            $this->command->info('Exam subjects already exist, skipping...');
            return;
        }
        
        // Get the first exam (প্রথম সাময়িক পরীক্ষা ২০২৬)
        $exam = Exam::where('name', 'প্রথম সাময়িক পরীক্ষা ২০২৬')->first();
        
        if (!$exam) {
            $this->command->error('Exam not found!');
            return;
        }
        
        // Get subjects for different classes
        $subjects = Subject::all();
        
        foreach ($subjects as $subject) {
            ExamSubject::create([
                'exam_id' => $exam->id,
                'subject_id' => $subject->id,
                'class_id' => $subject->class_id,
                'total_marks' => 100,
                'pass_marks' => 33,
                'exam_date' => Carbon::now()->subDays(rand(1, 10)),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
            ]);
        }
        
        $this->command->info('Exam subjects created successfully!');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        // Note: Subjects should be created through the UI, not seeded
        // This seeder only creates sample exams
        
        // Create exams
        $exams = [
            [
                'name' => 'প্রথম সাময়িক পরীক্ষা ২০২৬',
                'exam_type' => 'half_yearly',
                'description' => 'প্রথম সাময়িক পরীক্ষা - জানুয়ারি ২০২৬',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(40),
                'status' => 'upcoming',
                'total_marks' => 100,
                'pass_marks' => 33,
                'classes' => ['১ম', '২য়', '৩য়', '৪র্থ', '৫ম']
            ],
            [
                'name' => 'দ্বিতীয় সাময়িক পরীক্ষা ২০২৬',
                'exam_type' => 'half_yearly',
                'description' => 'দ্বিতীয় সাময়িক পরীক্ষা - মার্চ ২০২৬',
                'start_date' => Carbon::now()->addDays(90),
                'end_date' => Carbon::now()->addDays(100),
                'status' => 'upcoming',
                'total_marks' => 100,
                'pass_marks' => 33,
                'classes' => ['১ম', '২য়', '৩য়', '৪র্থ', '৫ম']
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
                'classes' => ['১ম', '২য়', '৩য়', '৪র্থ', '৫ম']
            ]
        ];

        foreach ($exams as $examData) {
            Exam::create($examData);
        }

        $this->command->info('Exams seeded successfully! (Subjects should be created through UI)');
    }
}
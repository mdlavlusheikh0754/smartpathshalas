<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use Carbon\Carbon;

class MonthlyExamSeeder extends Seeder
{
    public function run(): void
    {
        // Define months in Bengali
        $months = [
            'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
            'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'
        ];

        $this->command->info('Creating monthly exams...');

        foreach ($months as $index => $month) {
            $examName = $month . ' মাসিক পরীক্ষা ২০২৬';
            
            $exam = Exam::firstOrCreate([
                'name' => $examName,
                'exam_type' => 'monthly'
            ], [
                'month' => $month,
                'start_date' => Carbon::now()->addMonths($index),
                'end_date' => Carbon::now()->addMonths($index)->addDays(5),
                'status' => 'upcoming',
                'total_marks' => 100,
                'pass_marks' => 33,
                'is_published' => false
            ]);

            if ($exam->wasRecentlyCreated) {
                $this->command->info("✅ Created: " . $examName);
            } else {
                $this->command->info("ℹ️  Already exists: " . $examName);
            }
        }

        $monthlyCount = Exam::where('exam_type', 'monthly')->count();
        $this->command->info("Total monthly exams: " . $monthlyCount);
        $this->command->info('Monthly exams seeded successfully!');
    }
}
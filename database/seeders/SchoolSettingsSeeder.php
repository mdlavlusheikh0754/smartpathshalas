<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolSetting;

class SchoolSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolSetting::updateSettings([
            'school_name_bn' => 'ইকরা নূরানী একাডেমী',
            'school_name_en' => 'Iqra Noorani Academy',
            'short_code' => '101',
            'eiin' => '130512',
            'established_year' => '2010',
            'school_type' => 'private',
            'education_level' => 'secondary',
            'board' => 'Dhaka',
            'phone' => '01712345678',
            'email' => 'info@iqranooraniacademy.edu.bd',
            'address' => 'বরিশাল, বাংলাদেশ',
            'principal_name' => 'মোহাম্মদ আব্দুল করিম',
            'current_session' => '২০২৬',
            'session_start_date' => '2026-01-01',
            'session_end_date' => '2026-12-31'
        ]);
    }
}
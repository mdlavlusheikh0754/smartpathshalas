<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeStructure;

class FeeStructureSeeder extends Seeder
{
    public function run()
    {
        $feeStructures = [
            // Admission Fees
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '1',
                'amount' => 3000,
                'description' => 'প্রথম শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '2',
                'amount' => 3500,
                'description' => 'দ্বিতীয় শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '3',
                'amount' => 4000,
                'description' => 'তৃতীয় শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '4',
                'amount' => 4500,
                'description' => 'চতুর্থ শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '5',
                'amount' => 5000,
                'description' => 'পঞ্চম শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '6',
                'amount' => 5500,
                'description' => 'ষষ্ঠ শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '7',
                'amount' => 6000,
                'description' => 'সপ্তম শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '8',
                'amount' => 6500,
                'description' => 'অষ্টম শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '9',
                'amount' => 7000,
                'description' => 'নবম শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            [
                'fee_type' => 'admission',
                'fee_name' => 'ভর্তি ফি',
                'class_name' => '10',
                'amount' => 7500,
                'description' => 'দশম শ্রেণীর ভর্তি ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'one_time'
            ],
            // Session Fee
            [
                'fee_type' => 'admission',
                'fee_name' => 'সেশন ফি',
                'class_name' => 'all',
                'amount' => 2000,
                'description' => 'বার্ষিক সেশন ফি',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'yearly'
            ],
            
            // Monthly Fees
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '1',
                'amount' => 1500,
                'description' => 'প্রথম শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '2',
                'amount' => 1600,
                'description' => 'দ্বিতীয় শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '3',
                'amount' => 1800,
                'description' => 'তৃতীয় শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '4',
                'amount' => 1800,
                'description' => 'চতুর্থ শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '5',
                'amount' => 2000,
                'description' => 'পঞ্চম শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '6',
                'amount' => 2200,
                'description' => 'ষষ্ঠ শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '7',
                'amount' => 2400,
                'description' => 'সপ্তম শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '8',
                'amount' => 2600,
                'description' => 'অষ্টম শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '9',
                'amount' => 2800,
                'description' => 'নবম শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ],
            [
                'fee_type' => 'monthly',
                'fee_name' => 'মাসিক বেতন',
                'class_name' => '10',
                'amount' => 3000,
                'description' => 'দশম শ্রেণীর মাসিক বেতন',
                'is_active' => true,
                'is_mandatory' => true,
                'frequency' => 'monthly'
            ]
        ];

        foreach ($feeStructures as $fee) {
            FeeStructure::create($fee);
        }
    }
}
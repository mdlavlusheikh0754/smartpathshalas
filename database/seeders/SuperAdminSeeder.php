<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'সুপার এডমিন',
            'email' => 'admin@smartpathshala.com',
            'password' => Hash::make('admin123'), // আপনার পছন্দমত পাসওয়ার্ড দিন
            'role' => 'super_admin', // এখানে রোল সেট করে দিলাম
            'email_verified_at' => now(),
        ]);
    }
}

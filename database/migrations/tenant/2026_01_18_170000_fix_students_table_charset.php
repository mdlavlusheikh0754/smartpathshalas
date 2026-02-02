<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run these statements if using MySQL
        if (DB::getDriverName() === 'mysql') {
            // Fix charset and collation for students table
            DB::statement('ALTER TABLE students CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            // Specifically fix the student_id column
            DB::statement('ALTER TABLE students MODIFY COLUMN student_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('ALTER TABLE students MODIFY COLUMN registration_number VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('ALTER TABLE students MODIFY COLUMN name_bn VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('ALTER TABLE students MODIFY COLUMN name_en VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('ALTER TABLE students MODIFY COLUMN academic_year VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible
    }
};
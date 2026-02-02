<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add password to students table if it doesn't exist
        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'password')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('password')->nullable()->after('student_id');
                $table->rememberToken()->after('password');
            });
        }

        // Add password to guardians table if it doesn't exist
        if (Schema::hasTable('guardians') && !Schema::hasColumn('guardians', 'password')) {
            Schema::table('guardians', function (Blueprint $table) {
                $table->string('password')->nullable()->after('phone');
                $table->rememberToken()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove password from students table
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'password')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn(['password', 'remember_token']);
            });
        }

        // Remove password from guardians table
        if (Schema::hasTable('guardians') && Schema::hasColumn('guardians', 'password')) {
            Schema::table('guardians', function (Blueprint $table) {
                $table->dropColumn(['password', 'remember_token']);
            });
        }
    }
};

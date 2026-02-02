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
        Schema::table('school_classes', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('school_classes', 'students')) {
                $table->integer('students')->default(0)->after('section');
            }
            
            if (!Schema::hasColumn('school_classes', 'teachers')) {
                $table->integer('teachers')->default(0)->after('students');
            }
            
            if (!Schema::hasColumn('school_classes', 'description')) {
                $table->text('description')->nullable()->after('teachers');
            }
            
            if (!Schema::hasColumn('school_classes', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropColumn(['students', 'teachers', 'description', 'is_active']);
        });
    }
};

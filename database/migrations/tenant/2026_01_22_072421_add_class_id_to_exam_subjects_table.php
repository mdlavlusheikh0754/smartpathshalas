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
        if (!Schema::hasColumn('exam_subjects', 'class_id')) {
            Schema::table('exam_subjects', function (Blueprint $table) {
                $table->unsignedBigInteger('class_id')->nullable()->after('subject_id');
                $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('set null');
            });
            
            // Drop and recreate the unique constraint in a separate statement
            Schema::table('exam_subjects', function (Blueprint $table) {
                $table->dropUnique(['exam_id', 'subject_id']);
            });
            
            Schema::table('exam_subjects', function (Blueprint $table) {
                $table->unique(['exam_id', 'subject_id', 'class_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_subjects', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropUnique(['exam_id', 'subject_id', 'class_id']);
            $table->dropColumn('class_id');
            
            // Restore original unique constraint
            $table->unique(['exam_id', 'subject_id']);
        });
    }
};
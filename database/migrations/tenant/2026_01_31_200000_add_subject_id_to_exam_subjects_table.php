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
        Schema::table('exam_subjects', function (Blueprint $table) {
            // Add subject_id column if it doesn't exist
            if (!Schema::hasColumn('exam_subjects', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
                $table->unique(['exam_id', 'subject_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_subjects', function (Blueprint $table) {
            if (Schema::hasColumn('exam_subjects', 'subject_id')) {
                $table->dropForeign(['subject_id']);
                $table->dropColumn('subject_id');
                $table->dropUnique(['exam_id', 'subject_id']);
            }
        });
    }
};

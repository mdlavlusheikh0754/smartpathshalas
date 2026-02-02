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
        Schema::table('subject_groups', function (Blueprint $table) {
            // Add exam_id column if it doesn't exist
            if (!Schema::hasColumn('subject_groups', 'exam_id')) {
                $table->unsignedBigInteger('exam_id')->nullable()->after('id');
                $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
                $table->index(['exam_id', 'class_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_groups', function (Blueprint $table) {
            if (Schema::hasColumn('subject_groups', 'exam_id')) {
                $table->dropForeign(['exam_id']);
                $table->dropColumn('exam_id');
                $table->dropIndex(['exam_id', 'class_id']);
            }
        });
    }
};

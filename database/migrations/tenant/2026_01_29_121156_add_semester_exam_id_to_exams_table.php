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
        if (!Schema::hasColumn('exams', 'semester_exam_id')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->string('semester_exam_id')->nullable()->after('term_exam_id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropIndex(['semester_exam_id']);
            $table->dropColumn('semester_exam_id');
        });
    }
};
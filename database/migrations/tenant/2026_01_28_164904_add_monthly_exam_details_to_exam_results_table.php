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
        if (!Schema::hasColumn('exam_results', 'monthly_exam_details')) {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->json('monthly_exam_details')->nullable()->after('monthly_marks');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn('monthly_exam_details');
        });
    }
};

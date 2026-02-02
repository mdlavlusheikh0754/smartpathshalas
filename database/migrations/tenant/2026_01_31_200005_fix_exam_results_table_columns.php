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
        Schema::table('exam_results', function (Blueprint $table) {
            // Rename columns to match the model expectations
            if (Schema::hasColumn('exam_results', 'marks_obtained') && !Schema::hasColumn('exam_results', 'obtained_marks')) {
                $table->renameColumn('marks_obtained', 'obtained_marks');
            }
            
            if (Schema::hasColumn('exam_results', 'full_marks') && !Schema::hasColumn('exam_results', 'total_marks')) {
                $table->renameColumn('full_marks', 'total_marks');
            }
            
            if (Schema::hasColumn('exam_results', 'pass_marks')) {
                // Keep pass_marks as is
            }
            
            // Add missing columns
            if (!Schema::hasColumn('exam_results', 'percentage')) {
                $table->decimal('percentage', 5, 2)->nullable()->after('total_marks');
            }
            
            if (!Schema::hasColumn('exam_results', 'status')) {
                $table->enum('status', ['pass', 'fail', 'absent'])->default('pass')->after('percentage');
            }
            
            if (!Schema::hasColumn('exam_results', 'grade')) {
                $table->string('grade')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('exam_results', 'remarks')) {
                $table->text('remarks')->nullable()->after('grade');
            }
            
            if (!Schema::hasColumn('exam_results', 'entered_at')) {
                $table->timestamp('entered_at')->nullable()->after('remarks');
            }
            
            if (!Schema::hasColumn('exam_results', 'entered_by')) {
                $table->integer('entered_by')->nullable()->after('entered_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            // Rename columns back
            if (Schema::hasColumn('exam_results', 'obtained_marks')) {
                $table->renameColumn('obtained_marks', 'marks_obtained');
            }
            
            if (Schema::hasColumn('exam_results', 'total_marks')) {
                $table->renameColumn('total_marks', 'full_marks');
            }
            
            // Drop added columns
            if (Schema::hasColumn('exam_results', 'percentage')) {
                $table->dropColumn('percentage');
            }
            
            if (Schema::hasColumn('exam_results', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('exam_results', 'grade')) {
                $table->dropColumn('grade');
            }
            
            if (Schema::hasColumn('exam_results', 'remarks')) {
                $table->dropColumn('remarks');
            }
            
            if (Schema::hasColumn('exam_results', 'entered_at')) {
                $table->dropColumn('entered_at');
            }
            
            if (Schema::hasColumn('exam_results', 'entered_by')) {
                $table->dropColumn('entered_by');
            }
        });
    }
};

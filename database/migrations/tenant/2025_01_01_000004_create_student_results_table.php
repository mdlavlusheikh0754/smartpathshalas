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
        if (Schema::hasTable('student_results')) {
            return;
        }
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('exam_type'); // monthly/midterm/final/board
            $table->string('exam_name');
            $table->string('academic_year');
            $table->string('class');
            $table->string('section')->nullable();
            $table->string('subject');
            $table->string('subject_code')->nullable();
            $table->integer('written_marks')->nullable();
            $table->integer('practical_marks')->nullable();
            $table->integer('total_marks');
            $table->integer('obtained_marks');
            $table->decimal('percentage', 5, 2);
            $table->string('grade'); // A+/A/B/C/D/F
            $table->decimal('grade_point', 3, 2);
            $table->string('remarks')->nullable();
            $table->string('status')->default('published'); // draft/published
            $table->string('evaluated_by');
            $table->timestamps();
            
            $table->index(['student_id', 'exam_type', 'academic_year']);
            $table->index(['exam_type', 'status']);
            $table->index(['class', 'section', 'exam_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_results');
    }
};

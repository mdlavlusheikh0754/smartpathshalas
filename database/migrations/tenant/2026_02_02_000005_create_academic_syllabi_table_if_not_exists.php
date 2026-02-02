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
        // Only create if it doesn't exist
        if (!Schema::hasTable('academic_syllabi')) {
            Schema::create('academic_syllabi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('class_id')->nullable();
                $table->unsignedBigInteger('exam_id')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('file_path');
                $table->string('file_name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('class_id');
                $table->index('exam_id');
                $table->index('subject_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_syllabi');
    }
};

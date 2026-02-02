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
        if (!Schema::hasTable('subject_groups')) {
            Schema::create('subject_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('exam_id');
                $table->unsignedBigInteger('class_id');
                $table->integer('total_marks')->default(100);
                $table->integer('pass_marks')->default(33);
                $table->json('subject_ids'); // Store array of subject IDs
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
                $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
                
                $table->index(['exam_id', 'class_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_groups');
    }
};
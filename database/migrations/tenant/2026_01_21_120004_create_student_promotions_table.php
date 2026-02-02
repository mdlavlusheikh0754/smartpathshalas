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
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('from_class');
            $table->string('to_class');
            $table->string('from_section')->nullable();
            $table->string('to_section')->nullable();
            $table->string('academic_year');
            $table->enum('promotion_type', ['promoted', 'repeated', 'transferred'])->default('promoted');
            $table->text('remarks')->nullable();
            $table->date('promotion_date');
            $table->foreignId('promoted_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};
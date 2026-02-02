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
        if (Schema::hasTable('school_classes')) {
            return;
        }
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ক্লাসের নাম (প্রথম, দ্বিতীয়, তৃতীয়)
            $table->string('section'); // সেকশন (A, B, C, D)
            $table->integer('students')->default(0); // শিক্ষার্থী সংখ্যা
            $table->integer('teachers')->default(0); // শিক্ষক সংখ্যা
            $table->boolean('is_active')->default(true); // সক্রিয় কিনা
            $table->text('description')->nullable(); // বিবরণ
            $table->timestamps();
            
            // Unique constraint for name + section combination
            $table->unique(['name', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};

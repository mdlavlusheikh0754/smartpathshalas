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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('school_name_bn')->nullable();
            $table->string('school_name_en')->nullable();
            $table->string('logo')->nullable();
            $table->string('eiin')->nullable();
            $table->string('established_year')->nullable();
            $table->enum('school_type', ['government', 'private', 'semi_government'])->default('government');
            $table->enum('education_level', ['primary', 'secondary', 'higher_secondary'])->default('secondary');
            $table->string('board')->nullable();
            $table->string('mpo_number')->nullable();
            
            // Contact Information
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('district')->nullable();
            $table->string('upazila')->nullable();
            
            // Principal Information
            $table->string('principal_name')->nullable();
            $table->string('principal_mobile')->nullable();
            $table->string('principal_email')->nullable();
            $table->date('principal_joining_date')->nullable();
            $table->string('principal_photo')->nullable();
            $table->text('principal_qualification')->nullable();
            
            // School Timing
            $table->time('school_start_time')->nullable();
            $table->time('school_end_time')->nullable();
            $table->enum('weekly_holiday', ['friday', 'saturday', 'sunday'])->default('friday');
            $table->integer('class_duration')->default(45);
            $table->time('break_start_time')->nullable();
            $table->time('break_end_time')->nullable();
            
            // Academic Information
            $table->string('current_session')->nullable();
            $table->date('session_start_date')->nullable();
            $table->date('session_end_date')->nullable();
            $table->integer('total_students')->default(0);
            $table->integer('total_teachers')->default(0);
            $table->integer('total_staff')->default(0);
            $table->integer('total_classrooms')->default(0);
            
            // Financial Information
            $table->decimal('monthly_fee', 10, 2)->nullable();
            $table->decimal('admission_fee', 10, 2)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_routing_number')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};

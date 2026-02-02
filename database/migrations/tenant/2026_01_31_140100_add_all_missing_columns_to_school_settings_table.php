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
        Schema::table('school_settings', function (Blueprint $table) {
            // Add all missing columns from the original migration
            if (!Schema::hasColumn('school_settings', 'eiin')) {
                $table->string('eiin')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'short_code')) {
                $table->string('short_code')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'school_initials')) {
                $table->string('school_initials')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'established_year')) {
                $table->string('established_year')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'school_type')) {
                $table->enum('school_type', ['government', 'private', 'semi_government'])->default('government');
            }
            if (!Schema::hasColumn('school_settings', 'education_level')) {
                $table->enum('education_level', ['primary', 'secondary', 'higher_secondary'])->default('secondary');
            }
            if (!Schema::hasColumn('school_settings', 'board')) {
                $table->string('board')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'mpo_number')) {
                $table->string('mpo_number')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'mobile')) {
                $table->string('mobile')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'website')) {
                $table->string('website')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'district')) {
                $table->string('district')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'upazila')) {
                $table->string('upazila')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'principal_name')) {
                $table->string('principal_name')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'principal_mobile')) {
                $table->string('principal_mobile')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'principal_email')) {
                $table->string('principal_email')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'principal_joining_date')) {
                $table->date('principal_joining_date')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'principal_photo')) {
                $table->string('principal_photo')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'principal_qualification')) {
                $table->text('principal_qualification')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'school_start_time')) {
                $table->time('school_start_time')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'school_end_time')) {
                $table->time('school_end_time')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'weekly_holiday')) {
                $table->enum('weekly_holiday', ['friday', 'saturday', 'sunday'])->default('friday');
            }
            if (!Schema::hasColumn('school_settings', 'class_duration')) {
                $table->integer('class_duration')->default(45);
            }
            if (!Schema::hasColumn('school_settings', 'break_start_time')) {
                $table->time('break_start_time')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'break_end_time')) {
                $table->time('break_end_time')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'current_session')) {
                $table->string('current_session')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'session_start_date')) {
                $table->date('session_start_date')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'session_end_date')) {
                $table->date('session_end_date')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'total_students')) {
                $table->integer('total_students')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'total_teachers')) {
                $table->integer('total_teachers')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'total_staff')) {
                $table->integer('total_staff')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'total_classrooms')) {
                $table->integer('total_classrooms')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'monthly_fee')) {
                $table->decimal('monthly_fee', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'admission_fee')) {
                $table->decimal('admission_fee', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'bank_routing_number')) {
                $table->string('bank_routing_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $columns = [
                'eiin', 'short_code', 'school_initials', 'established_year', 'school_type', 'education_level',
                'board', 'mpo_number', 'phone', 'mobile', 'email', 'website', 'address', 'postal_code',
                'district', 'upazila', 'principal_name', 'principal_mobile', 'principal_email',
                'principal_joining_date', 'principal_photo', 'principal_qualification', 'school_start_time',
                'school_end_time', 'weekly_holiday', 'class_duration', 'break_start_time', 'break_end_time',
                'current_session', 'session_start_date', 'session_end_date', 'total_students', 'total_teachers',
                'total_staff', 'total_classrooms', 'monthly_fee', 'admission_fee', 'bank_name',
                'bank_account_number', 'bank_routing_number'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('school_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

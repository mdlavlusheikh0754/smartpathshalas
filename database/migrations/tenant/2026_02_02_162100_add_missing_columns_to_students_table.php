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
        Schema::table('students', function (Blueprint $table) {
            // Add missing columns that are in the model but not in the database
            if (!Schema::hasColumn('students', 'transport')) {
                $table->string('transport')->default('no')->after('previous_school');
            }
            if (!Schema::hasColumn('students', 'hostel')) {
                $table->string('hostel')->default('no')->after('transport');
            }
            if (!Schema::hasColumn('students', 'guardian_id')) {
                $table->unsignedBigInteger('guardian_id')->nullable()->after('parent_phone');
            }
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable()->after('guardian_id');
            }
            if (!Schema::hasColumn('students', 'registration_number')) {
                $table->string('registration_number')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('students', 'birth_certificate_file')) {
                $table->string('birth_certificate_file')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('students', 'vaccination_card')) {
                $table->string('vaccination_card')->nullable()->after('birth_certificate_file');
            }
            if (!Schema::hasColumn('students', 'father_nid_file')) {
                $table->string('father_nid_file')->nullable()->after('vaccination_card');
            }
            if (!Schema::hasColumn('students', 'mother_nid_file')) {
                $table->string('mother_nid_file')->nullable()->after('father_nid_file');
            }
            if (!Schema::hasColumn('students', 'previous_school_certificate')) {
                $table->string('previous_school_certificate')->nullable()->after('mother_nid_file');
            }
            if (!Schema::hasColumn('students', 'other_documents')) {
                $table->string('other_documents')->nullable()->after('previous_school_certificate');
            }
            if (!Schema::hasColumn('students', 'father_mobile')) {
                $table->string('father_mobile')->nullable()->after('other_documents');
            }
            if (!Schema::hasColumn('students', 'father_occupation')) {
                $table->string('father_occupation')->nullable()->after('father_mobile');
            }
            if (!Schema::hasColumn('students', 'father_nid')) {
                $table->string('father_nid')->nullable()->after('father_occupation');
            }
            if (!Schema::hasColumn('students', 'father_email')) {
                $table->string('father_email')->nullable()->after('father_nid');
            }
            if (!Schema::hasColumn('students', 'father_income')) {
                $table->string('father_income')->nullable()->after('father_email');
            }
            if (!Schema::hasColumn('students', 'mother_mobile')) {
                $table->string('mother_mobile')->nullable()->after('father_income');
            }
            if (!Schema::hasColumn('students', 'mother_occupation')) {
                $table->string('mother_occupation')->nullable()->after('mother_mobile');
            }
            if (!Schema::hasColumn('students', 'mother_nid')) {
                $table->string('mother_nid')->nullable()->after('mother_occupation');
            }
            if (!Schema::hasColumn('students', 'mother_email')) {
                $table->string('mother_email')->nullable()->after('mother_nid');
            }
            if (!Schema::hasColumn('students', 'guardian_name')) {
                $table->string('guardian_name')->nullable()->after('mother_email');
            }
            if (!Schema::hasColumn('students', 'guardian_mobile')) {
                $table->string('guardian_mobile')->nullable()->after('guardian_name');
            }
            if (!Schema::hasColumn('students', 'guardian_relation')) {
                $table->string('guardian_relation')->nullable()->after('guardian_mobile');
            }
            if (!Schema::hasColumn('students', 'guardian_address')) {
                $table->string('guardian_address')->nullable()->after('guardian_relation');
            }
            if (!Schema::hasColumn('students', 'special_needs')) {
                $table->string('special_needs')->nullable()->after('guardian_address');
            }
            if (!Schema::hasColumn('students', 'health_condition')) {
                $table->string('health_condition')->nullable()->after('special_needs');
            }
            if (!Schema::hasColumn('students', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('health_condition');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columnsToRemove = [
                'transport', 'hostel', 'guardian_id', 'password', 'registration_number',
                'birth_certificate_file', 'vaccination_card', 'father_nid_file', 'mother_nid_file',
                'previous_school_certificate', 'other_documents', 'father_mobile', 'father_occupation',
                'father_nid', 'father_email', 'father_income', 'mother_mobile', 'mother_occupation',
                'mother_nid', 'mother_email', 'guardian_name', 'guardian_mobile', 'guardian_relation',
                'guardian_address', 'special_needs', 'health_condition', 'emergency_contact'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
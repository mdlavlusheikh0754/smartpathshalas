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
            // Add missing columns if they don't exist
            
            // Basic student info
            if (!Schema::hasColumn('students', 'student_id')) {
                $table->string('student_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('students', 'guardian_id')) {
                $table->unsignedBigInteger('guardian_id')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('students', 'eiin_number')) {
                $table->string('eiin_number')->nullable()->after('registration_number');
            }
            if (!Schema::hasColumn('students', 'board')) {
                $table->string('board')->nullable()->after('eiin_number');
            }
            if (!Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable()->after('board');
            }
            if (!Schema::hasColumn('students', 'name_bangla')) {
                $table->string('name_bangla')->nullable()->after('name');
            }
            
            // Address fields
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('permanent_address');
            }
            if (!Schema::hasColumn('students', 'parent_phone')) {
                $table->string('parent_phone')->nullable()->after('phone');
            }
            
            // Academic info
            if (!Schema::hasColumn('students', 'group')) {
                $table->string('group')->nullable()->after('class');
            }
            if (!Schema::hasColumn('students', 'admission_type')) {
                $table->string('admission_type')->nullable()->after('admission_date');
            }
            if (!Schema::hasColumn('students', 'previous_school')) {
                $table->string('previous_school')->nullable()->after('admission_type');
            }
            if (!Schema::hasColumn('students', 'transfer_certificate_no')) {
                $table->string('transfer_certificate_no')->nullable()->after('previous_school');
            }
            
            // Financial info
            if (!Schema::hasColumn('students', 'admission_fee')) {
                $table->decimal('admission_fee', 10, 2)->nullable()->after('monthly_fee');
            }
            if (!Schema::hasColumn('students', 'last_monthly_payment')) {
                $table->date('last_monthly_payment')->nullable()->after('monthly_fee_status');
            }
            
            // Additional info
            if (!Schema::hasColumn('students', 'remarks')) {
                $table->text('remarks')->nullable()->after('academic_year');
            }
            
            // Document fields
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
            
            // Parent information
            if (!Schema::hasColumn('students', 'father_mobile')) {
                $table->string('father_mobile')->nullable()->after('father_name');
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
                $table->decimal('father_income', 10, 2)->nullable()->after('father_email');
            }
            
            if (!Schema::hasColumn('students', 'mother_mobile')) {
                $table->string('mother_mobile')->nullable()->after('mother_name');
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
            
            // Guardian information
            if (!Schema::hasColumn('students', 'guardian_name')) {
                $table->string('guardian_name')->nullable()->after('guardian_mobile');
            }
            if (!Schema::hasColumn('students', 'guardian_relation')) {
                $table->string('guardian_relation')->nullable()->after('guardian_name');
            }
            if (!Schema::hasColumn('students', 'guardian_address')) {
                $table->text('guardian_address')->nullable()->after('guardian_relation');
            }
            
            // Additional student information
            if (!Schema::hasColumn('students', 'special_needs')) {
                $table->string('special_needs')->default('no')->after('guardian_address');
            }
            if (!Schema::hasColumn('students', 'health_condition')) {
                $table->text('health_condition')->nullable()->after('special_needs');
            }
            if (!Schema::hasColumn('students', 'transport')) {
                $table->boolean('transport')->default(false)->after('emergency_contact');
            }
            if (!Schema::hasColumn('students', 'hostel')) {
                $table->boolean('hostel')->default(false)->after('transport');
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
                'student_id', 'guardian_id', 'eiin_number', 'board', 'name', 'name_bangla',
                'address', 'parent_phone', 'group', 'admission_type', 'previous_school',
                'transfer_certificate_no', 'admission_fee', 'last_monthly_payment', 'remarks',
                'birth_certificate_file', 'vaccination_card', 'father_nid_file', 'mother_nid_file',
                'previous_school_certificate', 'other_documents', 'father_mobile', 'father_occupation',
                'father_nid', 'father_email', 'father_income', 'mother_mobile', 'mother_occupation',
                'mother_nid', 'mother_email', 'guardian_name', 'guardian_relation', 'guardian_address',
                'special_needs', 'health_condition', 'transport', 'hostel'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

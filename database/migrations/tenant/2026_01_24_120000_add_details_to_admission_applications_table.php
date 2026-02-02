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
        Schema::table('admission_applications', function (Blueprint $table) {
            // Parent Information
            $table->string('father_mobile')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_nid')->nullable();
            $table->string('father_email')->nullable();
            $table->string('father_income')->nullable();
            
            $table->string('mother_mobile')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_nid')->nullable();
            $table->string('mother_email')->nullable();
            
            // Guardian Information
            $table->string('guardian_name')->nullable();
            $table->string('guardian_mobile')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->text('guardian_address')->nullable();
            
            // Additional Information
            $table->string('special_needs')->nullable();
            $table->string('health_condition')->nullable();
            $table->string('emergency_contact')->nullable();
            
            // Documents
            $table->string('birth_certificate_file')->nullable();
            $table->string('vaccination_card')->nullable();
            $table->string('father_nid_file')->nullable();
            $table->string('mother_nid_file')->nullable();
            $table->string('previous_school_certificate')->nullable();
            $table->string('other_documents')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->dropColumn([
                'father_mobile', 'father_occupation', 'father_nid', 'father_email', 'father_income',
                'mother_mobile', 'mother_occupation', 'mother_nid', 'mother_email',
                'guardian_name', 'guardian_mobile', 'guardian_relation', 'guardian_address',
                'special_needs', 'health_condition', 'emergency_contact',
                'birth_certificate_file', 'vaccination_card', 'father_nid_file', 'mother_nid_file',
                'previous_school_certificate', 'other_documents'
            ]);
        });
    }
};

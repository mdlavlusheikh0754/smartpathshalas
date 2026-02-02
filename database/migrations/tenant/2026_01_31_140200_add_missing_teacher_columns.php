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
        Schema::table('teachers', function (Blueprint $table) {
            // Add missing columns if they don't exist
            
            // Basic teacher info
            if (!Schema::hasColumn('teachers', 'teacher_id')) {
                $table->string('teacher_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('teachers', 'name_bangla')) {
                $table->string('name_bangla')->nullable()->after('name');
            }
            if (!Schema::hasColumn('teachers', 'father_name')) {
                $table->string('father_name')->nullable()->after('name_bangla');
            }
            if (!Schema::hasColumn('teachers', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            
            // Personal information
            if (!Schema::hasColumn('teachers', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('mother_name');
            }
            if (!Schema::hasColumn('teachers', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('teachers', 'blood_group')) {
                $table->string('blood_group')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('teachers', 'nid')) {
                $table->string('nid')->nullable()->after('blood_group');
            }
            if (!Schema::hasColumn('teachers', 'religion')) {
                $table->string('religion')->nullable()->after('nid');
            }
            if (!Schema::hasColumn('teachers', 'nationality')) {
                $table->string('nationality')->default('Bangladeshi')->after('religion');
            }
            
            // Address fields
            if (!Schema::hasColumn('teachers', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('address');
            }
            if (!Schema::hasColumn('teachers', 'present_division')) {
                $table->string('present_division')->nullable()->after('permanent_address');
            }
            if (!Schema::hasColumn('teachers', 'present_district')) {
                $table->string('present_district')->nullable()->after('present_division');
            }
            if (!Schema::hasColumn('teachers', 'present_upazila')) {
                $table->string('present_upazila')->nullable()->after('present_district');
            }
            if (!Schema::hasColumn('teachers', 'present_union')) {
                $table->string('present_union')->nullable()->after('present_upazila');
            }
            if (!Schema::hasColumn('teachers', 'present_details')) {
                $table->text('present_details')->nullable()->after('present_union');
            }
            if (!Schema::hasColumn('teachers', 'permanent_division')) {
                $table->string('permanent_division')->nullable()->after('present_details');
            }
            if (!Schema::hasColumn('teachers', 'permanent_district')) {
                $table->string('permanent_district')->nullable()->after('permanent_division');
            }
            if (!Schema::hasColumn('teachers', 'permanent_upazila')) {
                $table->string('permanent_upazila')->nullable()->after('permanent_district');
            }
            if (!Schema::hasColumn('teachers', 'permanent_union')) {
                $table->string('permanent_union')->nullable()->after('permanent_upazila');
            }
            if (!Schema::hasColumn('teachers', 'permanent_details')) {
                $table->text('permanent_details')->nullable()->after('permanent_union');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $columnsToRemove = [
                'teacher_id', 'name_bangla', 'father_name', 'mother_name',
                'date_of_birth', 'gender', 'blood_group', 'nid', 'religion', 'nationality',
                'permanent_address', 'present_division', 'present_district', 'present_upazila', 
                'present_union', 'present_details', 'permanent_division', 'permanent_district', 
                'permanent_upazila', 'permanent_union', 'permanent_details'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('teachers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
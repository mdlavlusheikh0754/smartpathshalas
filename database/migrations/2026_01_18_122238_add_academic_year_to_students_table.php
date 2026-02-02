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
            $table->string('academic_year')->nullable()->after('status');
            $table->string('name_bn')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_bn');
            $table->string('present_address')->nullable()->after('address');
            $table->string('permanent_address')->nullable()->after('present_address');
            $table->string('parent_phone')->nullable()->after('guardian_phone');
            $table->string('eiin_number')->nullable()->after('student_id');
            $table->string('board')->nullable()->after('eiin_number');
            $table->string('admission_type')->nullable()->after('admission_date');
            $table->string('birth_certificate_no')->nullable()->after('date_of_birth');
            $table->decimal('monthly_fee', 10, 2)->default(0)->after('academic_year');
            $table->decimal('admission_fee', 10, 2)->default(0)->after('monthly_fee');
            $table->text('remarks')->nullable()->after('admission_fee');
            $table->string('previous_school')->nullable()->after('admission_type');
            $table->string('transfer_certificate_no')->nullable()->after('previous_school');
            $table->string('group')->nullable()->after('section');
            
            // Add indexes
            $table->index(['academic_year', 'class', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'academic_year',
                'name_bn',
                'name_en', 
                'present_address',
                'permanent_address',
                'parent_phone',
                'eiin_number',
                'board',
                'admission_type',
                'birth_certificate_no',
                'monthly_fee',
                'admission_fee',
                'remarks',
                'previous_school',
                'transfer_certificate_no',
                'group'
            ]);
            
            $table->dropIndex(['academic_year', 'class', 'section']);
        });
    }
};

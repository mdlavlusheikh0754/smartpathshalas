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
        if (Schema::hasTable('students')) {
            return;
        }
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // Format: SCH-YYYY-0001
            $table->string('eiin_number'); // School EIIN
            $table->string('board'); // Education Board
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->string('birth_certificate_no')->unique();
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->string('religion');
            $table->string('nationality')->default('Bangladeshi');
            $table->string('present_address');
            $table->string('permanent_address');
            $table->string('phone')->nullable();
            $table->string('parent_phone');
            $table->string('email')->unique()->nullable();
            $table->string('class');
            $table->string('section')->nullable();
            $table->string('roll')->nullable();
            $table->string('group')->nullable(); // Science/Arts/Commerce
            $table->date('admission_date');
            $table->string('admission_type'); // Regular/Transfer
            $table->string('previous_school')->nullable();
            $table->string('transfer_certificate_no')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('active'); // active/inactive/graduated/transfer
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->decimal('admission_fee', 10, 2)->default(0);
            $table->string('academic_year');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['class', 'section', 'academic_year']);
            $table->index(['status', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

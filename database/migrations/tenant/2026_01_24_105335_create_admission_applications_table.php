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
        if (Schema::hasTable('admission_applications')) {
            return;
        }
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique(); // APP-YYYY-XXXX
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->string('birth_certificate_no')->nullable();
            $table->string('gender');
            $table->string('religion');
            $table->string('nationality')->default('Bangladeshi');
            $table->string('present_address');
            $table->string('permanent_address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('class');
            $table->string('group')->nullable(); // Science/Arts/Commerce
            $table->string('previous_school')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('pending'); // pending/approved/rejected
            $table->string('payment_status')->default('unpaid'); // unpaid/paid
            $table->string('transaction_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};

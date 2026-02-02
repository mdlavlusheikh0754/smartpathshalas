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
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('admission_start_date')->nullable();
            $table->string('admission_end_date')->nullable();
            $table->string('admission_exam_date')->nullable();
            $table->string('class_start_date')->nullable();
            $table->text('admission_process')->nullable();
            $table->text('admission_features')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['admission_start_date', 'admission_end_date', 'admission_exam_date', 'class_start_date', 'admission_process', 'admission_features']);
        });
    }
};

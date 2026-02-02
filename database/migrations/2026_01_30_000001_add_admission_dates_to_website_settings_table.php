<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'admission_start_date')) {
                $table->date('admission_start_date')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'admission_end_date')) {
                $table->date('admission_end_date')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'admission_exam_date')) {
                $table->date('admission_exam_date')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'class_start_date')) {
                $table->date('class_start_date')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'admission_process')) {
                $table->text('admission_process')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'admission_features')) {
                $table->text('admission_features')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'admission_start_date',
                'admission_end_date',
                'admission_exam_date',
                'class_start_date',
                'admission_process',
                'admission_features'
            ]);
        });
    }
};

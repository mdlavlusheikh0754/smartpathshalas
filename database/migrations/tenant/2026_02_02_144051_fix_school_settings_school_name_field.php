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
            // Check if school_name column exists and drop it if it does
            // since we use school_name_bn and school_name_en instead
            if (Schema::hasColumn('school_settings', 'school_name')) {
                $table->dropColumn('school_name');
            }
            
            // Ensure required columns are nullable or have defaults
            if (Schema::hasColumn('school_settings', 'school_name_bn')) {
                $table->string('school_name_bn')->nullable()->change();
            }
            
            if (Schema::hasColumn('school_settings', 'school_name_en')) {
                $table->string('school_name_en')->nullable()->change();
            }
            
            // Add short_code if it doesn't exist
            if (!Schema::hasColumn('school_settings', 'short_code')) {
                $table->string('short_code')->nullable();
            }
            
            // Add logo_position if it doesn't exist
            if (!Schema::hasColumn('school_settings', 'logo_position')) {
                $table->enum('logo_position', ['navbar_only', 'top_and_navbar', 'top_only'])
                    ->default('navbar_only');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            // Add back school_name column if needed
            if (!Schema::hasColumn('school_settings', 'school_name')) {
                $table->string('school_name')->nullable();
            }
        });
    }
};
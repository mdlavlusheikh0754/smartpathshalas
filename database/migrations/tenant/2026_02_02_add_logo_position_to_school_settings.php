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
            // Add logo_position column if it doesn't exist
            if (!Schema::hasColumn('school_settings', 'logo_position')) {
                $table->enum('logo_position', ['navbar_only', 'top_and_navbar', 'top_only'])
                    ->default('navbar_only')
                    ->after('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn('logo_position');
        });
    }
};

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
            // Add missing columns that are needed for school settings sync
            if (!Schema::hasColumn('website_settings', 'eiin')) {
                $table->string('eiin')->nullable();
            }
            
            if (!Schema::hasColumn('website_settings', 'established')) {
                $table->string('established')->nullable();
            }
            
            if (!Schema::hasColumn('website_settings', 'board')) {
                $table->string('board')->nullable();
            }
            
            if (!Schema::hasColumn('website_settings', 'type')) {
                $table->string('type')->nullable();
            }
            
            if (!Schema::hasColumn('website_settings', 'mpo')) {
                $table->string('mpo')->nullable();
            }
            
            // Add principal information columns
            if (!Schema::hasColumn('website_settings', 'principal_name')) {
                $table->string('principal_name')->nullable();
            }
            
            if (!Schema::hasColumn('website_settings', 'principal_photo')) {
                $table->string('principal_photo')->nullable();
            }
            
            // Add academic information columns
            if (!Schema::hasColumn('website_settings', 'total_students')) {
                $table->integer('total_students')->nullable();
            }
            
            if (!Schema::hasColumn('website_settings', 'total_teachers')) {
                $table->integer('total_teachers')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $columns = ['eiin', 'established', 'board', 'type', 'mpo', 'principal_name', 'principal_photo', 'total_students', 'total_teachers'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('website_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
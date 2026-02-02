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
        Schema::table('exam_results', function (Blueprint $table) {
            // Drop the foreign key constraint on entered_by
            $table->dropForeign(['entered_by']);
            
            // Change entered_by to a regular integer column without foreign key constraint
            $table->integer('entered_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            // Re-add the foreign key constraint
            $table->foreignId('entered_by')->nullable()->constrained('users')->onDelete('set null')->change();
        });
    }
};

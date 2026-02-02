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
        // Only create if it doesn't exist
        if (!Schema::hasTable('academic_holidays')) {
            Schema::create('academic_holidays', function (Blueprint $table) {
                $table->id();
                $table->string('holiday_name');
                $table->date('start_date');
                $table->date('end_date');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('start_date');
                $table->index('end_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_holidays');
    }
};

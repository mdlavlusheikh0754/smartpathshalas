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
        if (Schema::hasTable('academic_sessions')) {
            return;
        }
        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_name'); // e.g., "২০২৬", "২০২৫-২০২৬"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false); // Only one session can be current
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->integer('total_students')->default(0);
            $table->integer('total_teachers')->default(0);
            $table->integer('total_staff')->default(0);
            $table->integer('total_classrooms')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_sessions');
    }
};

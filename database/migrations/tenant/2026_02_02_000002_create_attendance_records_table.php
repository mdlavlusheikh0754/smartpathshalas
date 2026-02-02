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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 50);
            $table->unsignedBigInteger('student_id');
            $table->string('rfid_number', 50);
            $table->timestamp('punch_timestamp');
            $table->enum('punch_type', ['IN', 'OUT', 'BREAK_IN', 'BREAK_OUT']);
            $table->timestamp('device_timestamp');
            $table->timestamp('sync_timestamp')->useCurrent();
            $table->boolean('is_duplicate')->default(false);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['student_id', 'punch_timestamp']);
            $table->index(['device_id', 'sync_timestamp']);
            $table->index(['rfid_number', 'punch_timestamp']);
            
            // Unique constraint to prevent duplicates
            $table->unique(['student_id', 'device_timestamp', 'punch_type'], 'unique_attendance');
            
            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
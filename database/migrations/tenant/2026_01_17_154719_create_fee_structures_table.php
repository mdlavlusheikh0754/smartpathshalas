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
        if (Schema::hasTable('fee_structures')) {
            return;
        }
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('fee_type'); // admission, monthly, exam, annual, transport, etc.
            $table->string('fee_name'); // Display name in Bengali
            $table->string('class_name'); // Class name (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, etc.)
            $table->decimal('amount', 10, 2); // Fee amount
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(true); // Whether this fee is mandatory
            $table->string('frequency')->default('one_time'); // one_time, monthly, quarterly, yearly
            $table->json('applicable_months')->nullable(); // For monthly fees, which months apply
            $table->unsignedBigInteger('academic_session_id')->nullable();
            $table->timestamps();
            
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('set null');
            $table->unique(['fee_type', 'class_name', 'academic_session_id'], 'unique_fee_class_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};

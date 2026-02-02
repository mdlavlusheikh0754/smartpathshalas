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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complainant_name');
            $table->string('complainant_type'); // student, parent, teacher, staff, other
            $table->unsignedBigInteger('complainant_id')->nullable(); // For internal users
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('complaint_type'); // academic, behavioral, facility, financial, other
            $table->string('subject');
            $table->text('description');
            $table->text('expected_solution')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('new'); // new, pending, resolved, cancelled
            $table->boolean('is_anonymous')->default(false);
            $table->json('attachments')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};

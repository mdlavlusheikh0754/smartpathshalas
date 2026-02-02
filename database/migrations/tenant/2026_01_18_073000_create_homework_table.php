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
        if (Schema::hasTable('homework')) {
            return;
        }
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('subject');
            $table->string('class');
            $table->string('section')->nullable();
            $table->date('assigned_date');
            $table->date('due_date');
            $table->enum('status', ['active', 'completed', 'overdue'])->default('active');
            $table->string('attachment')->nullable();
            $table->text('instructions')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->timestamps();
            
            $table->index(['class', 'section', 'subject']);
            $table->index(['due_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework');
    }
};

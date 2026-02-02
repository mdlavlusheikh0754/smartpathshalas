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
        Schema::create('book_issues', function (Blueprint $col) {
            $col->id();
            $col->foreignId('book_id')->constrained()->onDelete('cascade');
            $col->foreignId('student_id')->constrained()->onDelete('cascade');
            $col->date('issue_date');
            $col->date('due_date');
            $col->date('return_date')->nullable();
            $col->decimal('fine_amount', 10, 2)->default(0);
            $col->enum('status', ['issued', 'returned', 'lost'])->default('issued');
            $col->text('notes')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};

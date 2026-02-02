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
        if (Schema::hasTable('student_fees')) {
            return;
        }
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('fee_type'); // monthly/admission/exam/lab/library/sports
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->storedAs('amount - paid_amount');
            $table->string('month')->nullable(); // For monthly fees
            $table->string('academic_year');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // cash/bank/mobile/online
            $table->string('transaction_id')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('status')->default('unpaid'); // unpaid/partial/paid/overdue
            $table->text('remarks')->nullable();
            $table->string('collected_by')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'fee_type', 'status']);
            $table->index(['status', 'due_date']);
            $table->index(['academic_year', 'fee_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};

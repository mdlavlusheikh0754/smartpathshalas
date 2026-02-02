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
        if (Schema::hasTable('fee_collections')) {
            return;
        }
        Schema::create('fee_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('student_name');
            $table->string('student_class')->nullable();
            $table->string('student_section')->nullable();
            $table->string('fee_type'); // admission, monthly, exam, etc.
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('due_amount', 10, 2);
            $table->decimal('inventory_cost', 10, 2)->default(0);
            $table->string('payment_method');
            $table->json('inventory_items')->nullable(); // Store selected inventory items
            $table->text('remarks')->nullable();
            $table->string('collected_by');
            $table->timestamp('collected_at');
            $table->string('academic_year');
            $table->string('receipt_number')->unique();
            $table->enum('status', ['completed', 'partial', 'cancelled'])->default('completed');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index(['student_id', 'fee_type']);
            $table->index(['collected_at']);
            $table->index(['academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_collections');
    }
};

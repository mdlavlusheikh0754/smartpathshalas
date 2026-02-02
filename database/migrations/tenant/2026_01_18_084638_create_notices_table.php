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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('attachment')->nullable();
            $table->date('publish_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();
            
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'publish_date']);
            $table->index(['priority', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};

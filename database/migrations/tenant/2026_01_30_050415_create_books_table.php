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
        Schema::create('books', function (Blueprint $col) {
            $col->id();
            $col->string('title');
            $col->string('author');
            $col->string('isbn')->nullable();
            $col->string('category')->nullable();
            $col->text('description')->nullable();
            $col->integer('total_quantity')->default(1);
            $col->integer('available_quantity')->default(1);
            $col->string('shelf_location')->nullable();
            $col->decimal('price', 10, 2)->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

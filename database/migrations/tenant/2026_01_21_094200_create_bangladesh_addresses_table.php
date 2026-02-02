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
        Schema::create('bangladesh_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // division, district, upazila, union
            $table->string('name_bn');
            $table->string('name_en')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'parent_id']);
            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('bangladesh_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bangladesh_addresses');
    }
};
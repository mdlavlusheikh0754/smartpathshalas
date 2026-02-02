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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name_bn'); // Bengali name
            $table->string('name_en')->nullable(); // English name
            $table->string('type'); // division, district, upazila, union, ward, village
            
            // Hierarchy
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('hierarchy_path')->nullable(); // e.g., "1/5/23/45" for quick lookups
            $table->tinyInteger('level')->default(0); // 0=division, 1=district, 2=upazila, 3=union, 4=ward, 5=village
            
            // Geographic Information
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('postal_code', 10)->nullable();
            
            // Administrative Information
            $table->string('code', 20)->nullable(); // Official government code
            $table->string('division_name')->nullable(); // For quick filtering
            $table->string('district_name')->nullable(); // For quick filtering
            $table->string('upazila_name')->nullable(); // For quick filtering
            
            // Additional Information
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // For storing additional data like population, area, etc.
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['type', 'parent_id']);
            $table->index(['type', 'is_active']);
            $table->index('parent_id');
            $table->index('level');
            $table->index(['division_name', 'district_name', 'upazila_name']);
            $table->index('hierarchy_path');
            $table->index(['latitude', 'longitude']);
            
            // Foreign key
            $table->foreign('parent_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
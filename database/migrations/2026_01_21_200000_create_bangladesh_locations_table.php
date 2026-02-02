<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bangladesh_locations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // division, district, upazila, union
            $table->string('name_bn');
            $table->string('name_en')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // parent location id
            $table->timestamps();
            $table->index(['type', 'parent_id']);
            $table->index('name_bn');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bangladesh_locations');
    }
};

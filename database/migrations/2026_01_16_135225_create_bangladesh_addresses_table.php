<?php

use App\Models\BangladeshAddress;
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
        // Run on central database
        Schema::connection('central')->create('bangladesh_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // division, district, upazila, union
            $table->string('name_bn'); // বাংলা নাম
            $table->string('name_en')->nullable(); // English name
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent location ID
            $table->timestamps();
            
            $table->index(['type', 'parent_id']);
            $table->index('name_bn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('bangladesh_addresses');
    }
};

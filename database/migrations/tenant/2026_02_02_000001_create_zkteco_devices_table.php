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
        Schema::create('zkteco_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 50)->unique();
            $table->string('name', 100);
            $table->string('ip_address', 15);
            $table->integer('port')->default(4370);
            $table->string('location', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_heartbeat')->nullable();
            $table->string('firmware_version', 50)->nullable();
            $table->integer('total_capacity')->nullable();
            $table->integer('current_users')->nullable();
            $table->integer('current_records')->nullable();
            $table->timestamps();
            
            $table->index(['device_id', 'is_active']);
            $table->index('last_heartbeat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zkteco_devices');
    }
};
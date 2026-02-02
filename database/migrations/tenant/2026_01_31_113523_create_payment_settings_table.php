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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            
            // SSLCommerz
            $table->boolean('ssl_active')->default(false);
            $table->string('ssl_store_id')->nullable();
            $table->string('ssl_store_password')->nullable();
            $table->enum('ssl_mode', ['sandbox', 'live'])->default('sandbox');

            // Shurjopay
            $table->boolean('shurjopay_active')->default(false);
            $table->string('shurjopay_username')->nullable();
            $table->string('shurjopay_password')->nullable();
            $table->string('shurjopay_prefix')->nullable();
            $table->enum('shurjopay_mode', ['sandbox', 'live'])->default('sandbox');

            // BKash
            $table->boolean('bkash_active')->default(false);
            $table->string('bkash_app_key')->nullable();
            $table->string('bkash_app_secret')->nullable();
            $table->string('bkash_username')->nullable();
            $table->string('bkash_password')->nullable();
            $table->enum('bkash_mode', ['sandbox', 'live'])->default('sandbox');

            // Nagad
            $table->boolean('nagad_active')->default(false);
            $table->string('nagad_merchant_id')->nullable();
            $table->string('nagad_public_key')->nullable();
            $table->string('nagad_private_key')->nullable();
            $table->enum('nagad_mode', ['sandbox', 'live'])->default('sandbox');

            // AmarPay
            $table->boolean('amarpay_active')->default(false);
            $table->string('amarpay_store_id')->nullable();
            $table->string('amarpay_signature_key')->nullable();
            $table->enum('amarpay_mode', ['sandbox', 'live'])->default('sandbox');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};

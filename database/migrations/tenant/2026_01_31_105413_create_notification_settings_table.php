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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            // Email Notifications
            $table->boolean('email_admission')->default(true);
            $table->boolean('email_fee')->default(true);
            $table->boolean('email_exam')->default(true);
            $table->boolean('email_attendance')->default(true);

            // SMS Notifications
            $table->boolean('sms_admission')->default(true);
            $table->boolean('sms_fee')->default(true);
            $table->boolean('sms_exam')->default(true);
            $table->boolean('sms_attendance')->default(true);
            $table->boolean('sms_notice')->default(true);

            // Push Notifications
            $table->boolean('push_notice')->default(true);
            $table->boolean('push_exam')->default(true);
            $table->boolean('push_event')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};

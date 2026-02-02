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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('school_name')->nullable();
            $table->string('slogan')->nullable();
            $table->string('logo')->nullable();
            $table->string('hero_bg')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('principal_photo')->nullable();
            $table->string('chairman_name')->nullable();
            $table->string('chairman_photo')->nullable();
            
            // Basic Info
            $table->text('about_text')->nullable();
            $table->string('established')->nullable();
            $table->string('eiin')->nullable();
            $table->string('board')->nullable();
            $table->string('type')->nullable();
            $table->string('mpo')->nullable();
            $table->string('shift')->nullable();
            
            // Statistics
            $table->integer('total_students')->nullable();
            $table->integer('total_teachers')->nullable();
            $table->integer('male_students')->nullable();
            $table->integer('female_students')->nullable();
            
            // Administration
            $table->string('vice_principal_name')->nullable();
            $table->string('vice_principal_photo')->nullable();
            
            // Academic
            $table->string('classes')->nullable();
            $table->string('shifts')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('exam_system')->nullable();
            
            // Facilities
            $table->json('facilities')->nullable();
            
            // Contact Info
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('fax')->nullable();
            
            // Social Media
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            
            // Notice Bar
            $table->string('notice_1')->nullable();
            $table->string('notice_2')->nullable();
            $table->string('notice_3')->nullable();
            $table->string('notice_4')->nullable();
            
            // Theme Colors
            $table->string('primary_color')->default('#3B82F6');
            $table->string('secondary_color')->default('#8B5CF6');
            $table->string('accent_color')->default('#EC4899');
            
            // Gallery
            $table->json('gallery_images')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};

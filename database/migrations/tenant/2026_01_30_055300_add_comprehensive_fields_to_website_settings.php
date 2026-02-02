<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // About Section
            $table->text('history_text')->nullable()->after('about_text');
            $table->text('mission_text')->nullable()->after('history_text');
            $table->text('vision_text')->nullable()->after('mission_text');
            $table->text('infrastructure_text')->nullable()->after('vision_text');
            
            // Administration Section
            $table->json('managing_committee')->nullable()->after('vice_principal_photo');
            $table->json('teachers_staff')->nullable()->after('managing_committee');
            
            // Academic Section
            $table->text('class_routine_pdf')->nullable()->after('exam_system');
            $table->json('syllabus_files')->nullable()->after('class_routine_pdf');
            $table->json('holiday_list')->nullable()->after('syllabus_files');
            $table->text('academic_calendar_pdf')->nullable()->after('holiday_list');
            
            // Admission Section
            $table->text('admission_rules')->nullable()->after('academic_calendar_pdf');
            $table->text('admission_requirements')->nullable()->after('admission_rules');
            $table->json('admission_fees')->nullable()->after('admission_requirements');
            $table->text('admission_form_pdf')->nullable()->after('admission_fees');
            
            // Contact Section
            $table->string('phone_2')->nullable()->after('phone');
            $table->string('phone_3')->nullable()->after('phone_2');
            $table->text('google_map_embed')->nullable()->after('address');
            
            // Gallery Section
            $table->json('video_links')->nullable()->after('gallery_images');
            
            // Footer Section
            $table->text('copyright_text')->nullable()->after('accent_color');
            $table->json('quick_links')->nullable()->after('copyright_text');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'history_text',
                'mission_text',
                'vision_text',
                'infrastructure_text',
                'managing_committee',
                'teachers_staff',
                'class_routine_pdf',
                'syllabus_files',
                'holiday_list',
                'academic_calendar_pdf',
                'admission_rules',
                'admission_requirements',
                'admission_fees',
                'admission_form_pdf',
                'phone_2',
                'phone_3',
                'google_map_embed',
                'video_links',
                'copyright_text',
                'quick_links',
            ]);
        });
    }
};

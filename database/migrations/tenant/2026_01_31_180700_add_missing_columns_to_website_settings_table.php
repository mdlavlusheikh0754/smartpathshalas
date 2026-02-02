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
        Schema::table('website_settings', function (Blueprint $table) {
            $columns = [
                'eiin' => 'string',
                'established' => 'string',
                'board' => 'string',
                'type' => 'string',
                'mpo' => 'string',
                'logo' => 'string',
                'slogan' => 'string',
                'hero_bg' => 'string',
                'principal_name' => 'string',
                'principal_photo' => 'string',
                'chairman_name' => 'string',
                'chairman_photo' => 'string',
                'about_text' => 'text',
                'shift' => 'string',
                'total_students' => 'integer',
                'total_teachers' => 'integer',
                'male_students' => 'integer',
                'female_students' => 'integer',
                'vice_principal_name' => 'string',
                'vice_principal_photo' => 'string',
                'classes' => 'string',
                'shifts' => 'string',
                'academic_year' => 'string',
                'exam_system' => 'string',
                'facilities' => 'json',
                'phone' => 'string',
                'email' => 'string',
                'address' => 'text',
                'fax' => 'string',
                'facebook' => 'string',
                'youtube' => 'string',
                'twitter' => 'string',
                'instagram' => 'string',
                'notice_1' => 'string',
                'notice_2' => 'string',
                'notice_3' => 'string',
                'notice_4' => 'string',
                'primary_color' => 'string',
                'secondary_color' => 'string',
                'accent_color' => 'string',
                'gallery_images' => 'json',
                'gallery_audio' => 'json',
                'hero_images' => 'string',
                'history_text' => 'text',
                'mission_text' => 'text',
                'vision_text' => 'text',
                'infrastructure_text' => 'text',
                'managing_committee' => 'json',
                'teachers_staff' => 'json',
                'class_routine_pdf' => 'string',
                'syllabus_files' => 'json',
                'holiday_list' => 'json',
                'academic_calendar_pdf' => 'string',
                'admission_start_date' => 'date',
                'admission_end_date' => 'date',
                'admission_exam_date' => 'date',
                'class_start_date' => 'date',
                'admission_rules' => 'text',
                'admission_requirements' => 'text',
                'admission_fees' => 'json',
                'admission_form_pdf' => 'string',
                'admission_process' => 'text',
                'admission_features' => 'text',
                'phone_2' => 'string',
                'phone_3' => 'string',
                'google_map_embed' => 'text',
                'video_links' => 'json',
                'copyright_text' => 'string',
                'quick_links' => 'json'
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('website_settings', $column)) {
                    if ($type === 'json') {
                        $table->json($column)->nullable();
                    } elseif ($type === 'text') {
                        $table->text($column)->nullable();
                    } elseif ($type === 'integer') {
                        $table->integer($column)->nullable();
                    } elseif ($type === 'date') {
                        $table->date($column)->nullable();
                    } else {
                        $table->string($column)->nullable();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $columns = [
                'eiin', 'established', 'board', 'type', 'mpo', 'logo', 'slogan', 'hero_bg',
                'principal_name', 'principal_photo', 'chairman_name', 'chairman_photo',
                'about_text', 'shift', 'total_students', 'total_teachers', 'male_students',
                'female_students', 'vice_principal_name', 'vice_principal_photo', 'classes',
                'shifts', 'academic_year', 'exam_system', 'facilities', 'phone', 'email',
                'address', 'fax', 'facebook', 'youtube', 'twitter', 'instagram',
                'notice_1', 'notice_2', 'notice_3', 'notice_4', 'primary_color',
                'secondary_color', 'accent_color', 'gallery_images', 'gallery_audio',
                'hero_images', 'history_text', 'mission_text', 'vision_text',
                'infrastructure_text', 'managing_committee', 'teachers_staff',
                'class_routine_pdf', 'syllabus_files', 'holiday_list', 'academic_calendar_pdf',
                'admission_start_date', 'admission_end_date', 'admission_exam_date',
                'class_start_date', 'admission_rules', 'admission_requirements',
                'admission_fees', 'admission_form_pdf', 'admission_process',
                'admission_features', 'phone_2', 'phone_3', 'google_map_embed',
                'video_links', 'copyright_text', 'quick_links'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('website_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

<?php

namespace App\Http\Controllers\Api;

use App\Models\SchoolSetting;
use App\Models\WebsiteSetting;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageCompression;

class SchoolController extends BaseApiController
{
    use ImageCompression;

    /**
     * Get public school information
     */
    public function getSchoolInfo()
    {
        $schoolSettings = SchoolSetting::getSettings();
        $websiteSettings = WebsiteSetting::getSettings();

        $schoolInfo = [
            'basic_info' => [
                'name' => $schoolSettings->school_name,
                'name_bangla' => $schoolSettings->school_name_bangla,
                'logo' => $schoolSettings->getImageUrl('logo'),
                'eiin' => $schoolSettings->eiin,
                'establishment_year' => $schoolSettings->establishment_year,
                'school_type' => $schoolSettings->school_type,
                'education_level' => $schoolSettings->education_level,
                'mpo_number' => $schoolSettings->mpo_number,
                'board' => $schoolSettings->board,
            ],
            'contact_info' => [
                'address' => $schoolSettings->address,
                'phone' => $schoolSettings->phone,
                'email' => $schoolSettings->email,
                'website' => $schoolSettings->website,
                'fax' => $schoolSettings->fax,
            ],
            'principal_info' => [
                'name' => $schoolSettings->principal_name,
                'phone' => $schoolSettings->principal_phone,
                'email' => $schoolSettings->principal_email,
                'photo' => $schoolSettings->getImageUrl('principal_photo'),
                'message' => $schoolSettings->principal_message,
            ],
            'timing' => [
                'start_time' => $schoolSettings->start_time,
                'end_time' => $schoolSettings->end_time,
                'break_start' => $schoolSettings->break_start,
                'break_end' => $schoolSettings->break_end,
                'working_days' => $schoolSettings->working_days,
            ],
            'website_settings' => [
                'hero_images' => $websiteSettings->getHeroImages(),
                'about_info' => $websiteSettings->about_info,
                'notice_text' => $websiteSettings->notice_text,
                'facilities' => $websiteSettings->getFacilities(),
                'contact_info' => $websiteSettings->contact_info,
                'social_media' => [
                    'facebook' => $websiteSettings->facebook_url,
                    'youtube' => $websiteSettings->youtube_url,
                    'twitter' => $websiteSettings->twitter_url,
                    'instagram' => $websiteSettings->instagram_url,
                ],
                'theme_colors' => [
                    'primary' => $websiteSettings->primary_color,
                    'secondary' => $websiteSettings->secondary_color,
                    'accent' => $websiteSettings->accent_color,
                ],
            ],
        ];

        return $this->sendResponse($schoolInfo, 'School information retrieved successfully');
    }

    /**
     * Get school settings (authenticated)
     */
    public function getSettings(Request $request)
    {
        $schoolSettings = SchoolSetting::getSettings();
        $websiteSettings = WebsiteSetting::getSettings();

        $settings = [
            'school_settings' => [
                'id' => $schoolSettings->id,
                'school_name' => $schoolSettings->school_name,
                'school_name_bangla' => $schoolSettings->school_name_bangla,
                'logo' => $schoolSettings->getImageUrl('logo'),
                'eiin' => $schoolSettings->eiin,
                'establishment_year' => $schoolSettings->establishment_year,
                'school_type' => $schoolSettings->school_type,
                'education_level' => $schoolSettings->education_level,
                'mpo_number' => $schoolSettings->mpo_number,
                'board' => $schoolSettings->board,
                'address' => $schoolSettings->address,
                'phone' => $schoolSettings->phone,
                'email' => $schoolSettings->email,
                'website' => $schoolSettings->website,
                'fax' => $schoolSettings->fax,
                'principal_name' => $schoolSettings->principal_name,
                'principal_phone' => $schoolSettings->principal_phone,
                'principal_email' => $schoolSettings->principal_email,
                'principal_photo' => $schoolSettings->getImageUrl('principal_photo'),
                'principal_message' => $schoolSettings->principal_message,
                'start_time' => $schoolSettings->start_time,
                'end_time' => $schoolSettings->end_time,
                'break_start' => $schoolSettings->break_start,
                'break_end' => $schoolSettings->break_end,
                'working_days' => $schoolSettings->working_days,
                'logo_position' => $schoolSettings->logo_position,
                'created_at' => $schoolSettings->created_at,
                'updated_at' => $schoolSettings->updated_at,
            ],
            'website_settings' => [
                'id' => $websiteSettings->id,
                'hero_images' => $websiteSettings->getHeroImages(),
                'about_info' => $websiteSettings->about_info,
                'notice_text' => $websiteSettings->notice_text,
                'administration_info' => $websiteSettings->administration_info,
                'academic_info' => $websiteSettings->academic_info,
                'facilities' => $websiteSettings->getFacilities(),
                'gallery_images' => $websiteSettings->getGalleryImages(),
                'contact_info' => $websiteSettings->contact_info,
                'facebook_url' => $websiteSettings->facebook_url,
                'youtube_url' => $websiteSettings->youtube_url,
                'twitter_url' => $websiteSettings->twitter_url,
                'instagram_url' => $websiteSettings->instagram_url,
                'primary_color' => $websiteSettings->primary_color,
                'secondary_color' => $websiteSettings->secondary_color,
                'accent_color' => $websiteSettings->accent_color,
                'created_at' => $websiteSettings->created_at,
                'updated_at' => $websiteSettings->updated_at,
            ],
        ];

        return $this->sendResponse($settings, 'School settings retrieved successfully');
    }

    /**
     * Update school settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:school,website',
            'school_name' => 'sometimes|required|string|max:255',
            'school_name_bangla' => 'sometimes|required|string|max:255',
            'eiin' => 'sometimes|required|string|max:20',
            'establishment_year' => 'sometimes|required|integer|min:1800|max:' . date('Y'),
            'school_type' => 'sometimes|required|string|max:100',
            'education_level' => 'sometimes|required|string|max:100',
            'address' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
            'principal_name' => 'sometimes|required|string|max:255',
            'principal_phone' => 'sometimes|required|string|max:20',
            'principal_email' => 'sometimes|required|email|max:255',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $type = $request->input('type');
        $data = $request->except(['type']);

        if ($type === 'school') {
            $settings = SchoolSetting::getSettings();
            $settings->update($data);
            
            $responseData = [
                'id' => $settings->id,
                'school_name' => $settings->school_name,
                'school_name_bangla' => $settings->school_name_bangla,
                'logo' => $settings->getImageUrl('logo'),
                'updated_at' => $settings->updated_at,
            ];
        } else {
            $settings = WebsiteSetting::getSettings();
            $settings->update($data);
            
            $responseData = [
                'id' => $settings->id,
                'updated_at' => $settings->updated_at,
            ];
        }

        return $this->sendResponse($responseData, ucfirst($type) . ' settings updated successfully');
    }

    /**
     * Upload school logo
     */
    public function uploadLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $schoolSettings = SchoolSetting::getSettings();

        // Delete old logo if exists
        if ($schoolSettings->logo) {
            \Storage::disk('public')->delete($schoolSettings->logo);
        }

        // Upload new logo
        $logoPath = $this->compressAndStore($request->file('logo'), 'school/logos');
        $schoolSettings->update(['logo' => $logoPath]);

        $data = [
            'logo_url' => $schoolSettings->getImageUrl('logo'),
        ];

        return $this->sendResponse($data, 'Logo uploaded successfully');
    }

    /**
     * Get academic sessions
     */
    public function getAcademicSessions()
    {
        $sessions = AcademicSession::orderBy('start_date', 'desc')->get();

        $sessionsData = $sessions->map(function ($session) {
            return [
                'id' => $session->id,
                'name' => $session->name,
                'start_date' => $session->start_date->format('Y-m-d'),
                'end_date' => $session->end_date->format('Y-m-d'),
                'is_current' => $session->is_current,
                'status' => $session->status,
                'description' => $session->description,
                'created_at' => $session->created_at,
                'updated_at' => $session->updated_at,
            ];
        });

        return $this->sendResponse($sessionsData, 'Academic sessions retrieved successfully');
    }

    /**
     * Store academic session
     */
    public function storeAcademicSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_current' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // If this session is set as current, make all others non-current
        if ($data['is_current'] ?? false) {
            AcademicSession::where('is_current', true)->update(['is_current' => false]);
        }

        $session = AcademicSession::create($data);

        $sessionData = [
            'id' => $session->id,
            'name' => $session->name,
            'start_date' => $session->start_date->format('Y-m-d'),
            'end_date' => $session->end_date->format('Y-m-d'),
            'is_current' => $session->is_current,
            'status' => $session->status,
            'description' => $session->description,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
        ];

        return $this->sendResponse($sessionData, 'Academic session created successfully', 201);
    }
}
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Models\SchoolSetting;
use App\Models\NotificationSetting;
use App\Models\SmsSetting;
use App\Models\PaymentSetting;
use App\Services\SmsService;
use App\Traits\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use ImageCompression;
    public function index()
    {
        return view('tenant.settings.index');
    }

    public function school()
    {
        $settings = SchoolSetting::getSettings();
        return view('tenant.settings.school', compact('settings'));
    }

    public function updateSchoolBasic(Request $request)
    {
        $request->validate([
            'school_name_bn' => 'required|string|max:255',
            'school_name_en' => 'required|string|max:255',
            'eiin' => 'nullable|string|max:255',
            'short_code' => 'required|string|max:10',
            'school_initials' => 'nullable|string|max:10',
            'established_year' => 'nullable|string|max:4',
            'school_type' => 'required|in:government,private,semi_government',
            'education_level' => 'required|in:primary,secondary,higher_secondary',
            'board' => 'nullable|string|max:255',
            'mpo_number' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB = 5120KB
            'logo_position' => 'required|in:navbar_only,top_and_navbar,top_only',
        ]);

        $data = $request->except(['_token', 'logo']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldSettings = SchoolSetting::first();
            if ($oldSettings && $oldSettings->logo) {
                Storage::disk('public')->delete($oldSettings->logo);
            }
            
            // Compress and store new logo
            $data['logo'] = $this->compressLogo($request->file('logo'), 'school/logos');
        }

        SchoolSetting::updateSettings($data);

        // Also sync basic info to website settings for landing page
        $websiteData = [
            'school_name' => $data['school_name_bn'],
            'eiin' => $data['eiin'],
            'established' => $data['established_year'],
            'board' => $data['board'],
            'type' => $data['school_type'] == 'government' ? 'সরকারি' : ($data['school_type'] == 'private' ? 'বেসরকারি' : 'আধা-সরকারি'),
            'mpo' => $data['mpo_number'],
        ];

        if ($request->hasFile('logo')) {
            $websiteData['logo'] = $data['logo'];
        }

        WebsiteSetting::updateSettings($websiteData);

        return redirect()->route('tenant.settings.school')->with('success', 'মৌলিক তথ্য সফলভাবে আপডেট হয়েছে! লোগো কম্প্রেস করে সংরক্ষণ করা হয়েছে। ল্যান্ডিং পেজও আপডেট হয়েছে।');
    }

    public function updateSchoolContact(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'district' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['_token']);
        SchoolSetting::updateSettings($data);

        return redirect()->route('tenant.settings.school')->with('success', 'যোগাযোগের তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function updateSchoolPrincipal(Request $request)
    {
        $request->validate([
            'principal_name' => 'nullable|string|max:255',
            'principal_mobile' => 'nullable|string|max:255',
            'principal_email' => 'nullable|email|max:255',
            'principal_joining_date' => 'nullable|date',
            'principal_qualification' => 'nullable|string',
            'principal_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB = 5120KB
        ]);

        $data = $request->except(['_token', 'principal_photo']);

        if ($request->hasFile('principal_photo')) {
            // Delete old photo if exists
            $oldSettings = SchoolSetting::first();
            if ($oldSettings && $oldSettings->principal_photo) {
                Storage::disk('public')->delete($oldSettings->principal_photo);
            }
            
            // Compress and store new photo
            $data['principal_photo'] = $this->compressPhoto($request->file('principal_photo'), 'school/principal');
        }

        SchoolSetting::updateSettings($data);

        // Also sync principal info to website settings for landing page
        $websiteData = [
            'principal_name' => $data['principal_name'],
        ];

        if ($request->hasFile('principal_photo')) {
            $websiteData['principal_photo'] = $data['principal_photo'];
        }

        WebsiteSetting::updateSettings($websiteData);

        return redirect()->route('tenant.settings.school')->with('success', 'প্রধান শিক্ষকের তথ্য সফলভাবে আপডেট হয়েছে! ছবি কম্প্রেস করে সংরক্ষণ করা হয়েছে। ল্যান্ডিং পেজও আপডেট হয়েছে।');
    }

    public function updateSchoolTiming(Request $request)
    {
        $request->validate([
            'school_start_time' => 'nullable|date_format:H:i',
            'school_end_time' => 'nullable|date_format:H:i',
            'weekly_holiday' => 'required|in:friday,saturday,sunday',
            'class_duration' => 'required|integer|min:30|max:120',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time' => 'nullable|date_format:H:i',
        ]);

        $data = $request->except(['_token']);
        SchoolSetting::updateSettings($data);

        return redirect()->route('tenant.settings.school')->with('success', 'স্কুলের সময়সূচী সফলভাবে আপডেট হয়েছে!');
    }

    public function updateSchoolAcademic(Request $request)
    {
        $request->validate([
            'current_session' => 'nullable|string|max:255',
            'session_start_date' => 'nullable|date',
            'session_end_date' => 'nullable|date|after:session_start_date',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'total_staff' => 'nullable|integer|min:0',
            'total_classrooms' => 'nullable|integer|min:0',
        ]);

        $data = $request->except(['_token']);
        SchoolSetting::updateSettings($data);

        // Also sync academic info to website settings for landing page
        $websiteData = [
            'total_students' => $data['total_students'],
            'total_teachers' => $data['total_teachers'],
        ];

        WebsiteSetting::updateSettings($websiteData);

        return redirect()->route('tenant.settings.school')->with('success', 'একাডেমিক তথ্য সফলভাবে আপডেট হয়েছে! ল্যান্ডিং পেজও আপডেট হয়েছে।');
    }

    public function updateSchoolFinancial(Request $request)
    {
        $request->validate([
            'monthly_fee' => 'nullable|numeric|min:0',
            'admission_fee' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_routing_number' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['_token']);
        SchoolSetting::updateSettings($data);

        return redirect()->route('tenant.settings.school')->with('success', 'আর্থিক তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function website()
    {
        $settings = WebsiteSetting::getSettings();
        return view('tenant.settings.website', compact('settings'));
    }

    public function updateWebsite(Request $request)
    {
        $request->validate([
            'chairman_name' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'established' => 'nullable|string|max:255',
            'eiin' => 'nullable|string|max:255',
            'board' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'mpo' => 'nullable|string|max:255',
            'shift' => 'nullable|string|max:255',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'male_students' => 'nullable|integer|min:0',
            'female_students' => 'nullable|integer|min:0',
            'vice_principal_name' => 'nullable|string|max:255',
            'classes' => 'nullable|string|max:255',
            'shifts' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'exam_system' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'fax' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'notice_1' => 'nullable|string|max:255',
            'notice_2' => 'nullable|string|max:255',
            'notice_3' => 'nullable|string|max:255',
            'notice_4' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'hero_bg' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'hero_images' => 'nullable|array',
            'hero_images.*' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB each
            'chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'vice_principal_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB each
            'gallery_audio' => 'nullable|array',
            'gallery_audio.*' => 'file|mimes:mp3,wav,ogg,aac|max:10240', // 10MB each
            'facilities' => 'nullable|array',
            'remove_hero_images' => 'nullable|array',
            // About Section
            'history_text' => 'nullable|string',
            'mission_text' => 'nullable|string',
            'vision_text' => 'nullable|string',
            'infrastructure_text' => 'nullable|string',
            // Academic
            'class_routine_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'syllabus_files' => 'nullable|array',
            'syllabus_files.*' => 'file|mimes:pdf|max:10240', // 10MB each
            'holiday_list' => 'nullable|array',
            'academic_calendar_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            // Admission
            'admission_rules' => 'nullable|string',
            'admission_requirements' => 'nullable|string',
            'admission_fees' => 'nullable|array',
            'admission_form_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            // Contact
            'phone_2' => 'nullable|string|max:255',
            'phone_3' => 'nullable|string|max:255',
            'google_map_embed' => 'nullable|string',
            // Gallery
            'video_links' => 'nullable|array',
            // Footer
            'copyright_text' => 'nullable|string|max:500',
            'quick_links' => 'nullable|array',
            // Administrators
            'administrators' => 'nullable|array',
            'administrators.*.name' => 'nullable|string|max:255',
            'administrators.*.designation' => 'nullable|string|max:255',
            'administrators.*.photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // Committee
            'committee' => 'nullable|array',
            'committee.*.name' => 'nullable|string|max:255',
            'committee.*.designation' => 'nullable|string|max:255',
            'committee.*.phone' => 'nullable|string|max:20',
            'committee.*.email' => 'nullable|email|max:255',
            'committee.*.address' => 'nullable|string|max:500',
            'committee.*.photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // Staff
            'staff' => 'nullable|array',
            'staff.*.name' => 'nullable|string|max:255',
            'staff.*.designation' => 'nullable|string|max:255',
            'staff.*.subject' => 'nullable|string|max:255',
            'staff.*.phone' => 'nullable|string|max:20',
            'staff.*.email' => 'nullable|email|max:255',
            'staff.*.photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->except(['_token', 'logo', 'hero_bg', 'hero_images', 'chairman_photo', 'vice_principal_photo', 'gallery', 'gallery_audio', 'remove_hero_images']);

        // Get old settings for cleanup
        $oldSettings = WebsiteSetting::first();

        // Handle file uploads with compression
        if ($request->hasFile('logo')) {
            if ($oldSettings && $oldSettings->logo) {
                Storage::disk('public')->delete($oldSettings->logo);
            }
            $data['logo'] = $this->compressLogo($request->file('logo'), 'website/hero');
        }

        if ($request->hasFile('hero_bg')) {
            if ($oldSettings && $oldSettings->hero_bg) {
                Storage::disk('public')->delete($oldSettings->hero_bg);
            }
            $data['hero_bg'] = $this->compressAndStoreImage($request->file('hero_bg'), 'website/backgrounds', 1200, 800, 80);
        }

        // Handle multiple hero images
        if ($request->hasFile('hero_images')) {
            $currentHeroImages = [];
            if ($oldSettings && $oldSettings->hero_images) {
                // Handle both JSON string and array formats
                if (is_string($oldSettings->hero_images)) {
                    $currentHeroImages = json_decode($oldSettings->hero_images, true) ?: [];
                } elseif (is_array($oldSettings->hero_images)) {
                    $currentHeroImages = $oldSettings->hero_images;
                }
            }
            
            // Handle image removal
            if ($request->has('remove_hero_images')) {
                $removeIndices = $request->input('remove_hero_images');
                foreach ($removeIndices as $index) {
                    if (isset($currentHeroImages[$index])) {
                        Storage::disk('public')->delete($currentHeroImages[$index]);
                        unset($currentHeroImages[$index]);
                    }
                }
                $currentHeroImages = array_values($currentHeroImages); // Re-index array
            }
            
            // Add new images
            $newHeroImages = [];
            foreach ($request->file('hero_images') as $image) {
                $newHeroImages[] = $this->compressAndStoreImage($image, 'website/hero', 1200, 800, 85);
            }
            
            // Combine existing and new images (limit to 10 total)
            $allHeroImages = array_merge($currentHeroImages, $newHeroImages);
            $data['hero_images'] = json_encode(array_slice($allHeroImages, 0, 10));
            
            // Debug logging
            \Log::info('Hero images update:', [
                'current' => $currentHeroImages,
                'new' => $newHeroImages,
                'final' => json_decode($data['hero_images'], true)
            ]);
            
        } else {
            // Handle image removal without new uploads
            if ($request->has('remove_hero_images') && $oldSettings && $oldSettings->hero_images) {
                $currentHeroImages = [];
                if (is_string($oldSettings->hero_images)) {
                    $currentHeroImages = json_decode($oldSettings->hero_images, true) ?: [];
                } elseif (is_array($oldSettings->hero_images)) {
                    $currentHeroImages = $oldSettings->hero_images;
                }
                
                $removeIndices = $request->input('remove_hero_images');
                
                foreach ($removeIndices as $index) {
                    if (isset($currentHeroImages[$index])) {
                        Storage::disk('public')->delete($currentHeroImages[$index]);
                        unset($currentHeroImages[$index]);
                    }
                }
                
                $data['hero_images'] = json_encode(array_values($currentHeroImages)); // Re-index array
            }
        }

        if ($request->hasFile('chairman_photo')) {
            if ($oldSettings && $oldSettings->chairman_photo) {
                Storage::disk('public')->delete($oldSettings->chairman_photo);
            }
            $data['chairman_photo'] = $this->compressPhoto($request->file('chairman_photo'), 'website/photos');
        }

        if ($request->hasFile('vice_principal_photo')) {
            if ($oldSettings && $oldSettings->vice_principal_photo) {
                Storage::disk('public')->delete($oldSettings->vice_principal_photo);
            }
            $data['vice_principal_photo'] = $this->compressPhoto($request->file('vice_principal_photo'), 'website/photos');
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            if ($oldSettings && $oldSettings->gallery_images) {
                foreach ($oldSettings->gallery_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $galleryImages = [];
            foreach ($request->file('gallery') as $image) {
                $galleryImages[] = $this->compressAndStoreImage($image, 'website/gallery', 800, 600, 85);
            }
            $data['gallery_images'] = $galleryImages;
        }

        // Handle gallery audio
        if ($request->hasFile('gallery_audio')) {
            // Delete old gallery audio
            if ($oldSettings && $oldSettings->gallery_audio) {
                foreach ($oldSettings->gallery_audio as $oldAudio) {
                    Storage::disk('public')->delete($oldAudio);
                }
            }

            $galleryAudio = [];
            foreach ($request->file('gallery_audio') as $audio) {
                $fileName = time() . '_' . uniqid() . '.' . $audio->getClientOriginalExtension();
                $path = $audio->storeAs('website/audio', $fileName, 'public');
                $galleryAudio[] = $path;
            }
            $data['gallery_audio'] = $galleryAudio;
        }

        // Handle class routine PDF
        if ($request->hasFile('class_routine_pdf')) {
            if ($oldSettings && $oldSettings->class_routine_pdf) {
                Storage::disk('public')->delete($oldSettings->class_routine_pdf);
            }
            $data['class_routine_pdf'] = $request->file('class_routine_pdf')->store('website/academic', 'public');
        }

        // Handle syllabus files
        if ($request->hasFile('syllabus_files')) {
            $syllabusFiles = [];
            foreach ($request->file('syllabus_files') as $file) {
                $syllabusFiles[] = $file->store('website/syllabus', 'public');
            }
            $data['syllabus_files'] = $syllabusFiles;
        }

        // Handle holiday list
        if ($request->has('holiday_list')) {
            $holidayList = $request->input('holiday_list', '');
            $data['holiday_list'] = array_filter(array_map('trim', explode("\n", $holidayList ?: '')));
        }

        // Handle facilities
        if ($request->has('facilities')) {
            $data['facilities'] = $request->input('facilities', []);
        } else {
            $data['facilities'] = [];
        }

        // Handle administrators
        if ($request->has('administrators')) {
            $administrators = [];
            foreach ($request->input('administrators', []) as $index => $admin) {
                $adminData = [
                    'name' => $admin['name'] ?? '',
                    'designation' => $admin['designation'] ?? ''
                ];
                
                // Handle photo upload for each administrator
                if ($request->hasFile("administrators.{$index}.photo")) {
                    $photo = $request->file("administrators.{$index}.photo");
                    $adminData['photo'] = $this->compressAndStoreImage($photo, 'website/administrators', 300, 300, 85);
                } elseif (isset($admin['photo']) && !empty($admin['photo'])) {
                    // Keep existing photo path
                    $adminData['photo'] = $admin['photo'];
                }
                
                $administrators[] = $adminData;
            }
            $data['managing_committee'] = $administrators;
        }

        // Handle committee members
        if ($request->has('committee')) {
            $committeeMembers = [];
            foreach ($request->input('committee', []) as $index => $member) {
                $memberData = [
                    'name' => $member['name'] ?? '',
                    'designation' => $member['designation'] ?? '',
                    'phone' => $member['phone'] ?? '',
                    'email' => $member['email'] ?? '',
                    'address' => $member['address'] ?? ''
                ];
                
                // Handle photo upload for each committee member
                if ($request->hasFile("committee.{$index}.photo")) {
                    $photo = $request->file("committee.{$index}.photo");
                    $memberData['photo'] = $this->compressAndStoreImage($photo, 'website/committee', 300, 300, 85);
                } elseif (isset($member['photo']) && !empty($member['photo'])) {
                    // Keep existing photo path
                    $memberData['photo'] = $member['photo'];
                }
                
                $committeeMembers[] = $memberData;
            }
            $data['managing_committee'] = $committeeMembers;
        }

        // Handle staff members
        if ($request->has('staff')) {
            $staffMembers = [];
            foreach ($request->input('staff', []) as $index => $member) {
                $memberData = [
                    'name' => $member['name'] ?? '',
                    'designation' => $member['designation'] ?? '',
                    'subject' => $member['subject'] ?? '',
                    'phone' => $member['phone'] ?? '',
                    'email' => $member['email'] ?? ''
                ];
                
                // Handle photo upload for each staff member
                if ($request->hasFile("staff.{$index}.photo")) {
                    $photo = $request->file("staff.{$index}.photo");
                    $memberData['photo'] = $this->compressAndStoreImage($photo, 'website/staff', 300, 300, 85);
                } elseif (isset($member['photo']) && !empty($member['photo'])) {
                    // Keep existing photo path
                    $memberData['photo'] = $member['photo'];
                }
                
                $staffMembers[] = $memberData;
            }
            $data['teachers_staff'] = $staffMembers;
        }

        // Handle academic calendar PDF
        if ($request->hasFile('academic_calendar_pdf')) {
            if ($oldSettings && $oldSettings->academic_calendar_pdf) {
                Storage::disk('public')->delete($oldSettings->academic_calendar_pdf);
            }
            $data['academic_calendar_pdf'] = $request->file('academic_calendar_pdf')->store('website/academic', 'public');
        }

        // Handle admission form PDF
        if ($request->hasFile('admission_form_pdf')) {
            if ($oldSettings && $oldSettings->admission_form_pdf) {
                Storage::disk('public')->delete($oldSettings->admission_form_pdf);
            }
            $data['admission_form_pdf'] = $request->file('admission_form_pdf')->store('website/admission', 'public');
        }

        // Handle video links
        if ($request->has('video_links')) {
            $videoLinks = $request->input('video_links', '');
            if (is_array($videoLinks)) {
                $data['video_links'] = array_filter($videoLinks);
            } else {
                $data['video_links'] = array_filter(array_map('trim', explode("\n", $videoLinks ?: '')));
            }
        }

        // Handle quick links
        if ($request->has('quick_links')) {
            $quickLinks = $request->input('quick_links', '');
            if (is_array($quickLinks)) {
                $data['quick_links'] = array_filter($quickLinks);
            } else {
                $data['quick_links'] = array_filter(array_map('trim', explode("\n", $quickLinks ?: '')));
            }
        }

        // Update website settings
        WebsiteSetting::updateSettings($data);

        return redirect()->route('tenant.settings.website')->with('success', 'ওয়েবসাইট সেটিংস সফলভাবে আপডেট হয়েছে! সকল ছবি কম্প্রেস করে সংরক্ষণ করা হয়েছে।');
    }

    public function academic()
    {
        return view('tenant.settings.academic');
    }

    public function academicFiles()
    {
        return view('tenant.settings.academic-files');
    }

    public function users()
    {
        return view('tenant.settings.users');
    }

    public function feeStructure()
    {
        try {
            $classes = \App\Models\SchoolClass::active()->ordered()->get();
        } catch (\Exception $e) {
            $classes = collect();
        }
        
        return view('tenant.settings.fee-structure', compact('classes'));
    }

    public function grade()
    {
        return view('tenant.settings.grade');
    }

    public function notification()
    {
        $settings = NotificationSetting::getSettings();
        return view('tenant.settings.notification', compact('settings'));
    }

    public function updateNotification(Request $request)
    {
        $data = [
            'email_admission' => $request->has('email_admission'),
            'email_fee' => $request->has('email_fee'),
            'email_exam' => $request->has('email_exam'),
            'email_attendance' => $request->has('email_attendance'),
            'sms_admission' => $request->has('sms_admission'),
            'sms_fee' => $request->has('sms_fee'),
            'sms_exam' => $request->has('sms_exam'),
            'sms_attendance' => $request->has('sms_attendance'),
            'sms_notice' => $request->has('sms_notice'),
            'push_notice' => $request->has('push_notice'),
            'push_exam' => $request->has('push_exam'),
            'push_event' => $request->has('push_event'),
        ];

        NotificationSetting::updateSettings($data);

        return redirect()->route('tenant.settings.notification')->with('success', 'নোটিফিকেশন সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function smsGateway()
    {
        $settings = SmsSetting::getSettings();
        $customTemplates = \App\Models\CustomSmsTemplate::all();
        return view('tenant.settings.sms-gateway', compact('settings', 'customTemplates'));
    }

    public function updateSmsGateway(Request $request)
    {
        $request->validate([
            'api_url' => 'required_if:sms_provider,null|nullable|url',
            'api_key' => 'required_if:sms_provider,null|nullable|string',
            'api_secret' => 'nullable|string',
            'sender_id' => 'required_if:sms_provider,null|nullable|string',
            'template_admission' => 'nullable|string',
            'template_fee_payment' => 'nullable|string',
            'template_absent' => 'nullable|string',
        ]);

        $data = $request->only([
            'api_url', 
            'api_key', 
            'api_secret', 
            'sender_id',
            'template_admission',
            'template_fee_payment',
            'template_absent'
        ]);

        SmsSetting::updateSettings($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'এসএমএস সেটিংস সফলভাবে আপডেট করা হয়েছে।']);
        }

        return redirect()->route('tenant.settings.smsGateway')->with('success', 'এসএমএস গেটওয়ে সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function sendTestSms(Request $request, SmsService $smsService)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
        ]);

        $message = "SmartPathshala Test SMS: Your SMS Gateway is working correctly!";
        $result = $smsService->sendSms($request->phone, $message);

        return response()->json($result);
    }

    public function paymentGateway()
    {
        $settings = PaymentSetting::getSettings();
        $customPaymentMethods = \App\Models\CustomPaymentMethod::where('is_active', true)->get();
        return view('tenant.settings.payment-gateway', compact('settings', 'customPaymentMethods'));
    }

    public function updatePaymentGateway(Request $request)
    {
        $data = [
            'ssl_active' => $request->has('ssl_active'),
            'ssl_store_id' => $request->ssl_store_id,
            'ssl_store_password' => $request->ssl_store_password,
            'ssl_mode' => $request->ssl_mode,

            'shurjopay_active' => $request->has('shurjopay_active'),
            'shurjopay_username' => $request->shurjopay_username,
            'shurjopay_password' => $request->shurjopay_password,
            'shurjopay_prefix' => $request->shurjopay_prefix,
            'shurjopay_mode' => $request->shurjopay_mode,

            'bkash_active' => $request->has('bkash_active'),
            'bkash_app_key' => $request->bkash_app_key,
            'bkash_app_secret' => $request->bkash_app_secret,
            'bkash_username' => $request->bkash_username,
            'bkash_password' => $request->bkash_password,
            'bkash_mode' => $request->bkash_mode,

            'nagad_active' => $request->has('nagad_active'),
            'nagad_merchant_id' => $request->nagad_merchant_id,
            'nagad_public_key' => $request->nagad_public_key,
            'nagad_private_key' => $request->nagad_private_key,
            'nagad_mode' => $request->nagad_mode,

            'amarpay_active' => $request->has('amarpay_active'),
            'amarpay_store_id' => $request->amarpay_store_id,
            'amarpay_signature_key' => $request->amarpay_signature_key,
            'amarpay_mode' => $request->amarpay_mode,
        ];

        PaymentSetting::updateSettings($data);

        return redirect()->route('tenant.settings.paymentGateway')->with('success', 'পেমেন্ট গেটওয়ে সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function storeCustomSmsTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'template' => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        \App\Models\CustomSmsTemplate::create([
            'name' => $request->name,
            'template' => $request->template,
            'description' => $request->description,
            'variables' => ['name', 'student_name', 'class', 'roll', 'amount', 'date', 'month', 'phone'],
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'কাস্টম টেমপ্লেট সফলভাবে যোগ করা হয়েছে।']);
    }

    public function updateCustomSmsTemplate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'template' => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        $template = \App\Models\CustomSmsTemplate::findOrFail($id);
        $template->update([
            'name' => $request->name,
            'template' => $request->template,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'কাস্টম টেমপ্লেট সফলভাবে আপডেট করা হয়েছে।']);
    }

    public function destroyCustomSmsTemplate($id)
    {
        $template = \App\Models\CustomSmsTemplate::findOrFail($id);
        $template->delete();

        return response()->json(['success' => true, 'message' => 'কাস্টম টেমপ্লেট সফলভাবে মুছে ফেলা হয়েছে।']);
    }

    // Custom Payment Methods
    public function storeCustomPaymentMethod(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'provider' => $request->provider,
            'account_number' => $request->account_number,
            'display_name' => $request->display_name,
            'is_active' => true,
        ];

        if ($request->hasFile('qr_code')) {
            $qrPath = $request->file('qr_code')->store('payment-methods/qr-codes', 'public');
            $data['qr_code'] = $qrPath;
        }

        $paymentMethod = \App\Models\CustomPaymentMethod::create($data);

        return response()->json([
            'success' => true,
            'message' => 'পেমেন্ট মেথড সফলভাবে যোগ করা হয়েছে।',
            'data' => $paymentMethod
        ]);
    }

    public function updateCustomPaymentMethod(Request $request, $id)
    {
        $request->validate([
            'provider' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $paymentMethod = \App\Models\CustomPaymentMethod::findOrFail($id);

        $data = [
            'provider' => $request->provider,
            'account_number' => $request->account_number,
            'display_name' => $request->display_name,
        ];

        if ($request->hasFile('qr_code')) {
            // Delete old QR code if exists
            if ($paymentMethod->qr_code && Storage::disk('public')->exists($paymentMethod->qr_code)) {
                Storage::disk('public')->delete($paymentMethod->qr_code);
            }
            
            $qrPath = $request->file('qr_code')->store('payment-methods/qr-codes', 'public');
            $data['qr_code'] = $qrPath;
        }

        $paymentMethod->update($data);

        return response()->json([
            'success' => true,
            'message' => 'পেমেন্ট মেথড সফলভাবে আপডেট করা হয়েছে।',
            'data' => $paymentMethod
        ]);
    }

    public function destroyCustomPaymentMethod($id)
    {
        $paymentMethod = \App\Models\CustomPaymentMethod::findOrFail($id);

        // Delete QR code if exists
        if ($paymentMethod->qr_code && Storage::disk('public')->exists($paymentMethod->qr_code)) {
            Storage::disk('public')->delete($paymentMethod->qr_code);
        }

        $paymentMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'পেমেন্ট মেথড সফলভাবে মুছে ফেলা হয়েছে।'
        ]);
    }

    public function showCustomPaymentMethod($id)
    {
        $paymentMethod = \App\Models\CustomPaymentMethod::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $paymentMethod
        ]);
    }

    public function backup()
    {
        return view('tenant.settings.backup');
    }

    public function security()
    {
        return view('tenant.settings.security');
    }

    public function deleteHeroImage(Request $request)
    {
        $request->validate([
            'index' => 'required|integer|min:0'
        ]);

        $settings = WebsiteSetting::first();
        if (!$settings || !$settings->hero_images) {
            return response()->json(['success' => false, 'message' => 'কোন হিরো ইমেজ পাওয়া যায়নি।']);
        }

        $heroImages = $settings->getHeroImagesArray();
        $index = $request->input('index');

        if (!isset($heroImages[$index])) {
            return response()->json(['success' => false, 'message' => 'ইমেজ পাওয়া যায়নি।']);
        }

        // Delete the file from storage
        $imagePath = $heroImages[$index];
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Remove from array and re-index
        unset($heroImages[$index]);
        $heroImages = array_values($heroImages);

        // Update database
        $settings->hero_images = json_encode($heroImages);
        $settings->save();

        return response()->json([
            'success' => true, 
            'message' => 'ইমেজটি সফলভাবে মুছে ফেলা হয়েছে।',
            'remaining_count' => count($heroImages)
        ]);
    }

    public function deleteAllHeroImages()
    {
        $settings = WebsiteSetting::first();
        if (!$settings || !$settings->hero_images) {
            return response()->json(['success' => false, 'message' => 'কোন হিরো ইমেজ পাওয়া যায়নি।']);
        }

        $heroImages = $settings->getHeroImagesArray();
        
        // Delete all files from storage
        foreach ($heroImages as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Clear hero_images in database
        $settings->hero_images = json_encode([]);
        $settings->save();

        return response()->json([
            'success' => true, 
            'message' => 'সব হিরো ইমেজ সফলভাবে মুছে ফেলা হয়েছে।'
        ]);
    }

    // Academic Session Management Methods
    public function storeAcademicSession(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'total_staff' => 'nullable|integer|min:0',
            'total_classrooms' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_current' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_current'] = $request->boolean('is_current');

        // If this session is set as current, unset all others
        if ($data['is_current']) {
            \App\Models\AcademicSession::where('is_current', true)->update(['is_current' => false]);
        }

        $session = \App\Models\AcademicSession::create($data);

        return response()->json([
            'success' => true,
            'message' => 'একাডেমিক সেশন সফলভাবে যোগ করা হয়েছে।',
            'session' => $session
        ]);
    }

    public function getAcademicSession(\App\Models\AcademicSession $session)
    {
        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'session_name' => $session->session_name,
                'start_date' => $session->start_date->format('Y-m-d'),
                'end_date' => $session->end_date->format('Y-m-d'),
                'total_students' => $session->total_students,
                'total_teachers' => $session->total_teachers,
                'total_staff' => $session->total_staff,
                'total_classrooms' => $session->total_classrooms,
                'description' => $session->description,
                'is_current' => $session->is_current,
                'is_active' => $session->is_active
            ]
        ]);
    }

    public function updateAcademicSession(Request $request, \App\Models\AcademicSession $session)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'total_staff' => 'nullable|integer|min:0',
            'total_classrooms' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_current' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_current'] = $request->boolean('is_current');

        // If this session is set as current, unset all others
        if ($data['is_current']) {
            \App\Models\AcademicSession::where('id', '!=', $session->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);
        }

        $session->update($data);

        return response()->json([
            'success' => true,
            'message' => 'একাডেমিক সেশন সফলভাবে আপডেট করা হয়েছে।',
            'session' => $session
        ]);
    }

    public function deleteAcademicSession(\App\Models\AcademicSession $session)
    {
        // Prevent deletion of current session
        if ($session->is_current) {
            return response()->json([
                'success' => false,
                'message' => 'বর্তমান সেশন মুছে ফেলা যাবে না। প্রথমে অন্য একটি সেশনকে বর্তমান সেশন হিসেবে সেট করুন।'
            ]);
        }

        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'একাডেমিক সেশন সফলভাবে মুছে ফেলা হয়েছে।'
        ]);
    }

    public function setCurrentAcademicSession(\App\Models\AcademicSession $session)
    {
        $session->setAsCurrent();

        return response()->json([
            'success' => true,
            'message' => 'সেশনটি বর্তমান সেশন হিসেবে সেট করা হয়েছে।'
        ]);
    }

    public function getAcademicSessions()
    {
        try {
            $sessions = \App\Models\AcademicSession::getActiveSessions();
            return response()->json([
                'success' => true,
                'sessions' => $sessions->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'session_name' => $session->session_name,
                        'is_current' => $session->is_current
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সেশন লোড করতে সমস্যা হয়েছে।',
                'sessions' => []
            ]);
        }
    }

    // Fee Structure Management Methods
    public function storeFeeStructure(Request $request)
    {
        $request->validate([
            'fee_type' => 'required|string|max:255',
            'fee_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:10',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|string|in:one_time,monthly,quarterly,half_yearly,yearly',
            'academic_session_id' => 'nullable|exists:academic_sessions,id',
            'description' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['is_mandatory'] = $request->boolean('is_mandatory');
            $data['is_active'] = $request->boolean('is_active');

            $feeStructure = \App\Models\FeeStructure::create($data);

            return response()->json([
                'success' => true,
                'message' => 'ফি স্ট্রাকচার সফলভাবে যোগ করা হয়েছে।',
                'feeStructure' => $feeStructure
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ফি স্ট্রাকচার যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ]);
        }
    }

    public function getFeeStructure(\App\Models\FeeStructure $feeStructure)
    {
        return response()->json([
            'success' => true,
            'feeStructure' => [
                'id' => $feeStructure->id,
                'fee_type' => $feeStructure->fee_type,
                'fee_name' => $feeStructure->fee_name,
                'class_name' => $feeStructure->class_name,
                'amount' => $feeStructure->amount,
                'frequency' => $feeStructure->frequency,
                'academic_session_id' => $feeStructure->academic_session_id,
                'description' => $feeStructure->description,
                'is_mandatory' => $feeStructure->is_mandatory,
                'is_active' => $feeStructure->is_active
            ]
        ]);
    }

    public function updateFeeStructure(Request $request, \App\Models\FeeStructure $feeStructure)
    {
        $request->validate([
            'fee_type' => 'required|string|max:255',
            'fee_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:10',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|string|in:one_time,monthly,quarterly,half_yearly,yearly',
            'academic_session_id' => 'nullable|exists:academic_sessions,id',
            'description' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['is_mandatory'] = $request->boolean('is_mandatory');
            $data['is_active'] = $request->boolean('is_active');

            $feeStructure->update($data);

            return response()->json([
                'success' => true,
                'message' => 'ফি স্ট্রাকচার সফলভাবে আপডেট করা হয়েছে।',
                'feeStructure' => $feeStructure
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ফি স্ট্রাকচার আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteFeeStructure(\App\Models\FeeStructure $feeStructure)
    {
        try {
            $feeStructure->delete();

            return response()->json([
                'success' => true,
                'message' => 'ফি স্ট্রাকচার সফলভাবে মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ফি স্ট্রাকচার মুছতে সমস্যা হয়েছে: ' . $e->getMessage()
            ]);
        }
    }
}

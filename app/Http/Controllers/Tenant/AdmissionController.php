<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionApplication;
use App\Models\SchoolSetting;
use App\Models\WebsiteSetting;
use App\Models\SchoolClass;
use Illuminate\Support\Str;

class AdmissionController extends Controller
{
    private function getSettings()
    {
        return [
            'websiteSettings' => WebsiteSetting::getSettings(),
            'schoolSettings' => SchoolSetting::getSettings(),
        ];
    }

    public function apply()
    {
        $data = $this->getSettings();
        $allClasses = SchoolClass::where('is_active', true)->get();
        $data['classes'] = $allClasses->unique('name');
        
        $sections = [];
        foreach($allClasses as $class) {
            $sections[$class->name][] = $class->section;
        }
        // Ensure unique sections
        foreach($sections as $key => $value) {
            $sections[$key] = array_values(array_unique($value));
        }
        $data['sections'] = $sections;
        
        return view('tenant.pages.admission.apply', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_type' => 'required|in:new,old',
            'roll_number' => 'required_if:student_type,old',
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'religion' => 'required|string',
            'phone' => 'required|string|max:20',
            'class' => 'required|string',
            'section' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'birth_certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'vaccination_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'father_nid_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'mother_nid_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'previous_school_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'emergency_contact' => 'required|string|max:20',
        ]);

        // Construct addresses from components if available, otherwise use direct input
        $presentAddress = $request->present_address;
        if ($request->present_division_name) {
            $presentAddress = implode(', ', array_filter([
                $request->present_address_details,
                $request->present_union_name,
                $request->present_upazila_name,
                $request->present_district_name,
                $request->present_division_name
            ]));
        }

        $permanentAddress = $request->permanent_address;
        if ($request->permanent_division_name) {
            $permanentAddress = implode(', ', array_filter([
                $request->permanent_address_details,
                $request->permanent_union_name,
                $request->permanent_upazila_name,
                $request->permanent_district_name,
                $request->permanent_division_name
            ]));
        }

        // Handle File Upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admission_photos', 'public');
        }

        $birthCertificatePath = null;
        if ($request->hasFile('birth_certificate_file')) {
            $birthCertificatePath = $request->file('birth_certificate_file')->store('admission_documents', 'public');
        }

        $vaccinationCardPath = null;
        if ($request->hasFile('vaccination_card')) {
            $vaccinationCardPath = $request->file('vaccination_card')->store('admission_documents', 'public');
        }

        $fatherNidPath = null;
        if ($request->hasFile('father_nid_file')) {
            $fatherNidPath = $request->file('father_nid_file')->store('admission_documents', 'public');
        }

        $motherNidPath = null;
        if ($request->hasFile('mother_nid_file')) {
            $motherNidPath = $request->file('mother_nid_file')->store('admission_documents', 'public');
        }

        $prevSchoolCertPath = null;
        if ($request->hasFile('previous_school_certificate')) {
            $prevSchoolCertPath = $request->file('previous_school_certificate')->store('admission_documents', 'public');
        }

        // Generate Application ID
        $year = date('Y');
        $count = AdmissionApplication::whereYear('created_at', $year)->count() + 1;
        $applicationId = 'APP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $application = AdmissionApplication::create([
            'application_id' => $applicationId,
            'student_type' => $request->student_type,
            'roll_number' => $request->student_type === 'old' ? $request->roll_number : null,
            'name_bn' => $request->name_bn,
            'name_en' => $request->name_en,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'date_of_birth' => $request->date_of_birth,
            'birth_certificate_no' => $request->birth_certificate_no,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'nationality' => $request->nationality ?? 'Bangladeshi',
            'present_address' => $presentAddress,
            'permanent_address' => $permanentAddress,
            'phone' => $request->phone,
            'email' => $request->email,
            'class' => $request->class,
            'section' => $request->section,
            'group' => $request->group,
            'previous_school' => $request->previous_school,
            'photo' => $photoPath,
            'status' => 'pending',
            
            // New Fields
            'father_mobile' => $request->father_mobile,
            'father_occupation' => $request->father_occupation,
            'father_nid' => $request->father_nid,
            'father_email' => $request->father_email,
            'father_income' => $request->father_income,
            'mother_mobile' => $request->mother_mobile,
            'mother_occupation' => $request->mother_occupation,
            'mother_nid' => $request->mother_nid,
            'mother_email' => $request->mother_email,
            'guardian_name' => $request->guardian_name,
            'guardian_mobile' => $request->guardian_mobile,
            'guardian_relation' => $request->guardian_relation,
            'guardian_address' => $request->guardian_address,
            'special_needs' => $request->special_needs,
            'health_condition' => $request->health_condition,
            'emergency_contact' => $request->emergency_contact,
            'remarks' => $request->remarks,
            
            // Document Files
            'birth_certificate_file' => $birthCertificatePath,
            'vaccination_card' => $vaccinationCardPath,
            'father_nid_file' => $fatherNidPath,
            'mother_nid_file' => $motherNidPath,
            'previous_school_certificate' => $prevSchoolCertPath,
        ]);

        return redirect()->route('tenant.admission.apply')->with('success', 'আপনার আবেদন সফলভাবে জমা দেওয়া হয়েছে। আপনার আবেদন আইডি: ' . $applicationId);
    }
}

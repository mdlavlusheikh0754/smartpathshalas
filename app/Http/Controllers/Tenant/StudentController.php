<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Helpers\PdfCompressor;
use App\Models\Student;
use App\Models\AdmissionApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Hash;
use App\Models\Guardian;
use App\Models\SchoolSetting;
use App\Models\NotificationSetting;
use App\Services\SmsService;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Get students query
        $studentsQuery = Student::orderBy('created_at', 'desc');
        
        // Filter by class if provided
        if ($request->has('class_id')) {
            // Find the class by ID to get name and section
            $schoolClass = \App\Models\SchoolClass::find($request->class_id);
            if ($schoolClass) {
                $studentsQuery->where('class', $schoolClass->name)
                             ->where('section', $schoolClass->section);
            }
        }
        
        $students = $studentsQuery->get();
        
        // Debug: Log student count
        \Log::info('Students count: ' . $students->count());
        
        // Debug: Log first student if exists
        if ($students->count() > 0) {
            \Log::info('First student: ' . $students->first()->name_bn);
        }
        
        // Map database fields to view fields and generate photo URLs
        $students = $students->map(function($student) {
            $student->roll = $student->roll_number ?? $student->roll;
            
            // Generate photo URL
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                $student->photo_url = route('tenant.files', ['path' => $student->photo]);
            } else {
                $student->photo_url = "https://ui-avatars.com/api/?name=" . urlencode($student->name_bn ?? 'Student') . "&background=10b981&color=fff&size=128";
            }
            
            return $student;
        });
        
        // If JSON format requested, return JSON response
        if ($request->get('format') === 'json' || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'students' => $students->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name_bn ?? $student->name,
                        'roll_number' => $student->roll_number ?? $student->roll,
                        'registration_number' => $student->registration_number ?? $student->student_id,
                        'class' => $student->class,
                        'section' => $student->section,
                        'gender' => $student->gender,
                        'status' => $student->status ?? 'active'
                    ];
                })
            ]);
        }
        
        // Calculate stats for web view
        $totalStudents = $students->count();
        $maleStudents = $students->where('gender', 'male')->count();
        $femaleStudents = $students->where('gender', 'female')->count();
        $newThisMonth = $students->where('created_at', '>=', now()->startOfMonth())->count();
        
        // Fetch pending admission applications
        $admissionApplications = AdmissionApplication::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tenant.students.index', compact('students', 'totalStudents', 'maleStudents', 'femaleStudents', 'newThisMonth', 'admissionApplications'));
    }

    public function admissionRequests()
    {
        // Fetch pending admission applications
        $admissionApplications = AdmissionApplication::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tenant.students.admission_requests', compact('admissionApplications'));
    }

    public function loginManagement()
    {
        // Fetch all students with their login credentials
        $students = Student::orderBy('name_bn', 'asc')->get();
        
        // Auto-generate passwords for students who don't have one
        $passwordsGenerated = 0;
        foreach ($students as $student) {
            if (!$student->hasPassword() && $student->date_of_birth) {
                $student->setDefaultPassword();
                $passwordsGenerated++;
            }
        }
        
        // Create or update guardian accounts for students with phone numbers
        $guardiansCreated = 0;
        foreach ($students as $student) {
            if ($student->phone && !$student->guardian_id) {
                // Check if guardian with this phone already exists
                $guardian = \App\Models\Guardian::where('phone', $student->phone)->first();
                
                if (!$guardian) {
                    // Get default password for this student
                    $defaultPassword = $student->getDefaultPassword();
                    
                    // Create new guardian with password
                    $guardian = \App\Models\Guardian::create([
                        'name' => $student->father_name ?? 'Guardian',
                        'phone' => $student->phone,
                        'email' => $student->email,
                        'password' => $defaultPassword ? \Hash::make($defaultPassword) : \Hash::make('12345678'),
                    ]);
                    $guardiansCreated++;
                }
                
                // Link student to guardian
                $student->guardian_id = $guardian->id;
                $student->save();
                
                // Set guardian password if not set
                if (!$guardian->hasPassword()) {
                    $guardian->setDefaultPassword();
                }
            }
        }
        
        // Refresh students to get updated guardian relationships
        $students = Student::with('guardian')->orderBy('name_bn', 'asc')->get();
        
        return view('tenant.students.login-management', compact('students', 'passwordsGenerated', 'guardiansCreated'));
    }

    public function regeneratePassword(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $type = $request->input('type', 'student');
        
        try {
            if ($type === 'student') {
                // Regenerate student password
                $student->setDefaultPassword();
                $message = 'শিক্ষার্থীর পাসওয়ার্ড সফলভাবে পুনরায় তৈরি করা হয়েছে';
            } else {
                // Regenerate guardian password
                if ($student->guardian) {
                    $student->guardian->setDefaultPassword();
                    $message = 'অভিভাবকের পাসওয়ার্ড সফলভাবে পুনরায় তৈরি করা হয়েছে';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'এই শিক্ষার্থীর কোনো অভিভাবক অ্যাকাউন্ট নেই'
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'পাসওয়ার্ড তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        // Generate preview IDs for display
        $currentYear = date('Y');
        $previewStudentId = Student::generateStudentId($currentYear);
        $previewRegistrationNumber = Student::generateRegistrationNumber($currentYear);
        
        return view('tenant.students.create', compact('previewStudentId', 'previewRegistrationNumber'));
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'student_type' => 'required|in:new,old',
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'religion' => 'required|string',
            'nationality' => 'required|string',
            'blood_group' => 'nullable|string',
            'birth_certificate' => 'nullable|string',
            'mobile' => 'nullable|string',
            'present_division' => 'required|string',
            'present_district' => 'required|string',
            'present_upazila' => 'required|string',
            'present_union' => 'nullable|string',
            'present_address_details' => 'required|string',
            'permanent_division' => 'required|string',
            'permanent_district' => 'required|string',
            'permanent_upazila' => 'required|string',
            'permanent_union' => 'nullable|string',
            'permanent_address_details' => 'required|string',
            'admission_date' => 'required|date',
            'roll_number' => 'required_if:student_type,old',
            'class' => 'required|string',
            'section' => 'required|string',
            'shift' => 'nullable|string',
            'academic_year' => 'required|string',
            'previous_school' => 'nullable|string',
            'transport' => 'nullable|string',
            'hostel' => 'nullable|string',
            'father_name' => 'required|string',
            'father_mobile' => 'required|string',
            'father_occupation' => 'nullable|string',
            'father_nid' => 'nullable|string',
            'father_email' => 'nullable|email',
            'father_income' => 'nullable|string',
            'mother_name' => 'required|string',
            'mother_mobile' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'mother_nid' => 'nullable|string',
            'mother_email' => 'nullable|email',
            'guardian_name' => 'nullable|string',
            'guardian_mobile' => 'nullable|string',
            'guardian_relation' => 'nullable|string',
            'guardian_address' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'health_condition' => 'nullable|string',
            'emergency_contact' => 'required|string',
            'status' => 'nullable|string|in:active,inactive,suspended',
            'remarks' => 'nullable|string',
            'photo' => 'nullable|image|max:10240', // 10MB
            'birth_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'vaccination_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'father_nid_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'mother_nid_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'previous_school_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'other_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Handle photo upload separately
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $validated['photo'] = $this->compressAndStore($file, 'students');
        }

        // Handle file uploads with compression
        $documentFields = [
            'birth_certificate_file',
            'vaccination_card',
            'father_nid_file',
            'mother_nid_file',
            'previous_school_certificate',
            'other_documents'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $validated[$field] = $this->compressAndStore($file, 'documents');
            }
        }

        // Generate Roll Number for New Students
        if ($request->student_type === 'new') {
            $academicYear = $this->convertBengaliToEnglish($validated['academic_year']);
            
            // Get max roll for the class/section/year
            // Using raw query to cast roll to integer for correct sorting
            $maxRoll = \App\Models\Student::where('class', $validated['class'])
                ->where('section', $validated['section'])
                ->where('academic_year', $academicYear)
                ->get()
                ->max(function ($student) {
                    return (int) $student->roll;
                });
            
            $validated['roll_number'] = ($maxRoll ?? 0) + 1;
        }

        // Prepare data for database
        $studentData = [
            'name_bn' => $validated['name_bn'],
            'name_en' => $validated['name_en'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'religion' => $validated['religion'],
            'nationality' => $validated['nationality'],
            'blood_group' => $validated['blood_group'],
            'birth_certificate_no' => $validated['birth_certificate'] ?: null,
            'phone' => $validated['mobile'],
            'present_address' => $this->formatAddress([
                'division' => $validated['present_division'],
                'district' => $validated['present_district'],
                'upazila' => $validated['present_upazila'],
                'union' => $validated['present_union'],
                'details' => $validated['present_address_details']
            ]),
            'permanent_address' => $this->formatAddress([
                'division' => $validated['permanent_division'],
                'district' => $validated['permanent_district'],
                'upazila' => $validated['permanent_upazila'],
                'union' => $validated['permanent_union'],
                'details' => $validated['permanent_address_details']
            ]),
            'admission_date' => $validated['admission_date'],
            'roll' => $validated['roll_number'],
            'class' => $validated['class'],
            'section' => $validated['section'],
            'shift' => $validated['shift'] ?? null,
            'academic_year' => $this->convertBengaliToEnglish($validated['academic_year']),
            'previous_school' => $validated['previous_school'],
            'transport' => $validated['transport'] ?? null,
            'hostel' => $validated['hostel'] ?? null,
            'father_name' => $validated['father_name'],
            'father_mobile' => $validated['father_mobile'],
            'father_occupation' => $validated['father_occupation'] ?? null,
            'father_nid' => $validated['father_nid'] ?? null,
            'father_email' => $validated['father_email'] ?? null,
            'father_income' => $validated['father_income'] ?? null,
            'mother_name' => $validated['mother_name'],
            'mother_mobile' => $validated['mother_mobile'] ?? null,
            'mother_occupation' => $validated['mother_occupation'] ?? null,
            'mother_nid' => $validated['mother_nid'] ?? null,
            'mother_email' => $validated['mother_email'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_mobile' => $validated['guardian_mobile'] ?? null,
            'guardian_relation' => $validated['guardian_relation'] ?? null,
            'guardian_address' => $validated['guardian_address'] ?? null,
            'special_needs' => $validated['special_needs'] ?? null,
            'health_condition' => $validated['health_condition'] ?? null,
            'emergency_contact' => $validated['emergency_contact'],
            'parent_phone' => $validated['father_mobile'],
            'status' => $validated['status'] ?? 'active',
            'remarks' => $validated['remarks'],
            'eiin_number' => '130512', // Default EIIN - should be from settings
            'board' => 'Dhaka', // Default board - should be from settings
            'admission_type' => 'Regular',
            'photo' => $validated['photo'] ?? null,
            // Document fields
            'birth_certificate_file' => $validated['birth_certificate_file'] ?? null,
            'vaccination_card' => $validated['vaccination_card'] ?? null,
            'father_nid_file' => $validated['father_nid_file'] ?? null,
            'mother_nid_file' => $validated['mother_nid_file'] ?? null,
            'previous_school_certificate' => $validated['previous_school_certificate'] ?? null,
            'other_documents' => $validated['other_documents'] ?? null,
        ];

        // Process Guardian Logic
        $guardianPhone = $validated['guardian_mobile'] ?? ($validated['father_mobile'] ?? ($validated['mother_mobile'] ?? null));
        
        if ($guardianPhone) {
            $guardianName = $validated['guardian_name'] ?? ($validated['father_name'] ?? ($validated['mother_name'] ?? 'Guardian'));
            $guardianEmail = $validated['father_email'] ?? ($validated['mother_email'] ?? null);

            $guardian = Guardian::firstOrCreate(
                ['phone' => $guardianPhone],
                [
                    'name' => $guardianName,
                    'email' => $guardianEmail,
                    'password' => Hash::make('12345678'), // Default password
                    'status' => 'active'
                ]
            );

            $studentData['guardian_id'] = $guardian->id;
        }

        // Set Default Student Password
        $studentData['password'] = Hash::make('12345678'); // Default password

        // Create student - auto-generation will happen in the model
        try {
            $student = \App\Models\Student::create($studentData);

            return redirect()->route('tenant.students.index')
                ->with('success', 'শিক্ষার্থী সফলভাবে যোগ করা হয়েছে। শিক্ষার্থী আইডি: ' . $student->student_id . ', রেজিস্ট্রেশন নম্বর: ' . $student->registration_number);
        } catch (\Exception $e) {
            \Log::error('Student creation failed: ' . $e->getMessage(), [
                'data' => $studentData,
                'error' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'শিক্ষার্থী যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');
        
        // Filter logic same as index
        $studentsQuery = Student::orderBy('created_at', 'desc');
        if ($request->has('class_id')) {
            $schoolClass = \App\Models\SchoolClass::find($request->class_id);
            if ($schoolClass) {
                $studentsQuery->where('class', $schoolClass->name)
                             ->where('section', $schoolClass->section);
            }
        }
        $students = $studentsQuery->get();

        if ($format === 'pdf') {
            try {
                // Configure mPDF with Bengali font support
                $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];
                
                $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];
                
                $mpdf = new Mpdf([
                    'mode' => 'utf-8',
                    'format' => 'A4-L',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
                    'fontdata' => $fontData + [
                        'solaimanlipi' => [
                            'R' => 'SolaimanLipi.ttf',
                            'useOTL' => 0xFF,
                            'useKashida' => 75,
                        ]
                    ],
                    'default_font' => 'solaimanlipi',
                    'autoScriptToLang' => true,
                    'autoLangToFont' => true,
                ]);
                
                $schoolSettings = \App\Models\SchoolSetting::getSettings();
                $tenantData = tenant('data');
                
                $logoPath = null;
                // Try SchoolSetting logo first
                if (!empty($schoolSettings->logo)) {
                    $path = storage_path('app/public/' . $schoolSettings->logo);
                    if (file_exists($path)) {
                        $logoPath = $path;
                    } else {
                        // Try public path as fallback
                        $path = public_path('storage/' . $schoolSettings->logo);
                        if (file_exists($path)) {
                            $logoPath = $path;
                        }
                    }
                } 
                // Fallback to tenant data logo
                elseif (!empty($tenantData['logo'])) {
                     $path = storage_path('app/public/' . $tenantData['logo']);
                     if (file_exists($path)) {
                         $logoPath = $path;
                     }
                }
                
                $schoolInfo = [
                    'name' => $schoolSettings->school_name_bn ?? $schoolSettings->school_name_en ?? ($tenantData['school_name'] ?? 'Smart Pathshala'),
                    'address' => $schoolSettings->address ?? ($tenantData['address'] ?? ''),
                    'logo' => $logoPath,
                ];
                
                $html = view('tenant.students.export_pdf_mpdf', compact('students', 'schoolInfo'))->render();
                $mpdf->WriteHTML($html);
                
                return response($mpdf->Output('students_list.pdf', 'S'))
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="students_list.pdf"');
            } catch (\Exception $e) {
                Log::error('PDF Export Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'PDF তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
            }
        } elseif ($format === 'excel') {
            // CSV Export
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=students_list.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($students) {
                $file = fopen('php://output', 'w');
                // BOM for Bengali support
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                fputcsv($file, ['আইডি', 'নাম', 'রোল', 'শ্রেণী', 'শাখা', 'লিঙ্গ', 'মোবাইল', 'পিতার নাম', 'মাতার নাম']);

                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->student_id,
                        $student->name_bn ?? $student->name,
                        $student->roll_number ?? $student->roll,
                        $student->class,
                        $student->section,
                        $student->gender == 'male' ? 'ছেলে' : ($student->gender == 'female' ? 'মেয়ে' : $student->gender),
                        $student->phone,
                        $student->father_name,
                        $student->mother_name
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } elseif ($format === 'docx') {
            // DOC Export (HTML)
            $font_path = asset('fonts/SolaimanLipi.ttf');
            $content = view('tenant.students.export_pdf', compact('students', 'font_path'))->render(); 
            
            return response($content)
                ->header('Content-Type', 'application/vnd.ms-word')
                ->header('Content-Disposition', 'attachment; filename="students_list.doc"');
        }

        return redirect()->back();
    }

    /**
     * Format address from components
     */
    private function formatAddress($components)
    {
        $addressParts = [];
        
        // Add details first
        if (!empty($components['details'])) {
            $addressParts[] = $components['details'];
        }
        
        // Check if we have IDs (numeric) or names (string)
        // If numeric, convert to names using database
        if (!empty($components['union'])) {
            if (is_numeric($components['union'])) {
                $union = $this->getAddressName('union', $components['union']);
                if ($union) {
                    $addressParts[] = 'ইউনিয়ন: ' . $union;
                }
            } else {
                $addressParts[] = 'ইউনিয়ন: ' . $components['union'];
            }
        }
        
        if (!empty($components['upazila'])) {
            if (is_numeric($components['upazila'])) {
                $upazila = $this->getAddressName('upazila', $components['upazila']);
                if ($upazila) {
                    $addressParts[] = 'উপজেলা: ' . $upazila;
                }
            } else {
                $addressParts[] = 'উপজেলা: ' . $components['upazila'];
            }
        }
        
        if (!empty($components['district'])) {
            if (is_numeric($components['district'])) {
                $district = $this->getAddressName('district', $components['district']);
                if ($district) {
                    $addressParts[] = 'জেলা: ' . $district;
                }
            } else {
                $addressParts[] = 'জেলা: ' . $components['district'];
            }
        }
        
        if (!empty($components['division'])) {
            if (is_numeric($components['division'])) {
                $division = $this->getAddressName('division', $components['division']);
                if ($division) {
                    $addressParts[] = 'বিভাগ: ' . $division;
                }
            } else {
                $addressParts[] = 'বিভাগ: ' . $components['division'];
            }
        }
        
        return implode(', ', array_filter($addressParts));
    }

    /**
     * Format address from IDs to names
     */
    private function formatAddressFromIds($components)
    {
        $addressParts = [];
        
        // Add details first
        if (!empty($components['details'])) {
            $addressParts[] = $components['details'];
        }
        
        // Get names from IDs using API
        try {
            if (!empty($components['union_id'])) {
                $union = $this->getAddressName('union', $components['union_id']);
                if ($union) {
                    $addressParts[] = 'ইউনিয়ন: ' . $union;
                }
            }
            
            if (!empty($components['upazila_id'])) {
                $upazila = $this->getAddressName('upazila', $components['upazila_id']);
                if ($upazila) {
                    $addressParts[] = 'উপজেলা: ' . $upazila;
                }
            }
            
            if (!empty($components['district_id'])) {
                $district = $this->getAddressName('district', $components['district_id']);
                if ($district) {
                    $addressParts[] = 'জেলা: ' . $district;
                }
            }
            
            if (!empty($components['division_id'])) {
                $division = $this->getAddressName('division', $components['division_id']);
                if ($division) {
                    $addressParts[] = 'বিভাগ: ' . $division;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Address formatting failed: ' . $e->getMessage());
        }
        
        return implode(', ', array_filter($addressParts));
    }

    /**
     * Get address name from ID using database
     */
    private function getAddressName($type, $id)
    {
        try {
            $address = \App\Models\BangladeshAddress::where('id', $id)->first();
            return $address ? $address->name_bn : null;
        } catch (\Exception $e) {
            \Log::error("Failed to get {$type} name for ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert Bengali numbers to English
     */
    private function convertBengaliToEnglish($text)
    {
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($bengaliNumbers, $englishNumbers, $text);
    }

    /**
     * Compress and store file
     */
    private function compressAndStore($file, $folder = 'documents')
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            
            \Log::info('Starting file upload', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $extension,
                'generated_filename' => $filename,
                'folder' => $folder
            ]);
            
            // Ensure directory exists
            $fullPath = storage_path('app/public/' . $folder);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
                \Log::info('Created directory: ' . $fullPath);
            }
            
            // For images, use intervention/image for compression
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                // Check if GD extension is available
                if (!extension_loaded('gd')) {
                    \Log::warning('GD extension not available, using simple store');
                    return $file->storeAs($folder, $filename, 'public');
                }
                
                try {
                    // Simple compression using GD
                    $imageData = file_get_contents($file->getRealPath());
                    $image = imagecreatefromstring($imageData);
                    
                    if ($image === false) {
                        \Log::warning('Failed to create image from string, using simple store');
                        return $file->storeAs($folder, $filename, 'public');
                    }
                    
                    // Get original dimensions
                    $width = imagesx($image);
                    $height = imagesy($image);
                    
                    // Calculate new dimensions (max 1920px)
                    $maxSize = 1920;
                    if ($width > $maxSize || $height > $maxSize) {
                        if ($width > $height) {
                            $newHeight = ($height / $width) * $maxSize;
                            $newWidth = $maxSize;
                        } else {
                            $newWidth = ($width / $height) * $maxSize;
                            $newHeight = $maxSize;
                        }
                    } else {
                        $newWidth = $width;
                        $newHeight = $height;
                    }
                    
                    // Create new image
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    
                    // Save compressed image
                    $path = storage_path('app/public/' . $folder . '/' . $filename);
                    
                    // Save with compression
                    $saved = imagejpeg($newImage, $path, 70); // 70% quality
                    
                    // Free memory
                    imagedestroy($image);
                    imagedestroy($newImage);
                    
                    if ($saved) {
                        \Log::info('Image compressed and saved: ' . $path);
                        return $folder . '/' . $filename;
                    } else {
                        \Log::warning('Failed to save compressed image, using simple store');
                        return $file->storeAs($folder, $filename, 'public');
                    }
                } catch (\Exception $e) {
                    \Log::error('Image compression failed: ' . $e->getMessage());
                    return $file->storeAs($folder, $filename, 'public');
                }
            }
            
            // For other files, just store
            $result = $file->storeAs($folder, $filename, 'public');
            \Log::info('File stored: ' . $result);
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        
        // Map database fields to view fields for compatibility
        $student->roll = $student->roll_number ?? $student->roll;
        
        return view('tenant.students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        
        // Map database fields to view fields for compatibility
        $student->roll = $student->roll_number ?? $student->roll;
        
        return view('tenant.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        // Validate request
        $validated = $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'religion' => 'required|string',
            'nationality' => 'required|string',
            'blood_group' => 'nullable|string',
            'birth_certificate_no' => 'nullable|string',
            'phone' => 'nullable|string',
            'roll_number' => 'required|string',
            'class' => 'required|string',
            'section' => 'required|string',
            'academic_year' => 'required|string',
            'status' => 'required|in:active,inactive,suspended',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'address' => 'required|string',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'present_address_manual' => 'nullable|string',
            'permanent_address_manual' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'admission_date' => 'required|date',
            'previous_school' => 'nullable|string',
            'transport' => 'nullable|string',
            'hostel' => 'nullable|string',
            'father_mobile' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'father_nid' => 'nullable|string',
            'father_email' => 'nullable|email',
            'father_income' => 'nullable|string',
            'mother_mobile' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'mother_nid' => 'nullable|string',
            'mother_email' => 'nullable|email',
            'guardian_name' => 'nullable|string',
            'guardian_mobile' => 'nullable|string',
            'guardian_relation' => 'nullable|string',
            'guardian_address' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'health_condition' => 'nullable|string',
            'emergency_contact' => 'required|string',
            'remarks' => 'nullable|string',
            'photo' => 'nullable|image|max:10240', // 10MB
            // Document validation
            'birth_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'vaccination_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'father_nid_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'mother_nid_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'previous_school_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'other_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Handle address formatting - prioritize manual entry over dropdown
        if ($request->filled('present_address_manual')) {
            $validated['present_address'] = $request->present_address_manual;
        } elseif ($request->filled('present_division')) {
            try {
                $presentAddress = $this->formatAddressFromIds([
                    'division_id' => $request->present_division,
                    'district_id' => $request->present_district,
                    'upazila_id' => $request->present_upazila,
                    'union_id' => $request->present_union,
                    'details' => $request->present_address_details
                ]);
                $validated['present_address'] = $presentAddress;
            } catch (\Exception $e) {
                $validated['present_address'] = $request->present_address_details ?? '';
            }
        }

        if ($request->filled('permanent_address_manual')) {
            $validated['permanent_address'] = $request->permanent_address_manual;
        } elseif ($request->filled('permanent_division')) {
            try {
                $permanentAddress = $this->formatAddressFromIds([
                    'division_id' => $request->permanent_division,
                    'district_id' => $request->permanent_district,
                    'upazila_id' => $request->permanent_upazila,
                    'union_id' => $request->permanent_union,
                    'details' => $request->permanent_address_details
                ]);
                $validated['permanent_address'] = $permanentAddress;
            } catch (\Exception $e) {
                $validated['permanent_address'] = $request->permanent_address_details ?? '';
            }
        }

        // Set address field for compatibility
        $validated['address'] = $validated['present_address'] ?? $request->address ?? '';
        $validated['guardian_phone'] = $request->father_mobile ?? $request->guardian_phone ?? '';
        $validated['parent_phone'] = $request->father_mobile ?? $request->parent_phone ?? '';

        // Map roll_number to roll (database column)
        if (isset($validated['roll_number'])) {
            $validated['roll'] = $validated['roll_number'];
            unset($validated['roll_number']);
        }

        // Remove temporary address fields
        $tempFields = [
            'present_address_manual', 'permanent_address_manual',
            'present_division', 'present_district', 'present_upazila', 'present_union', 'present_address_details',
            'permanent_division', 'permanent_district', 'permanent_upazila', 'permanent_union', 'permanent_address_details'
        ];
        
        foreach ($tempFields as $field) {
            unset($validated[$field]);
        }

        // Handle photo upload with compression
        if ($request->hasFile('photo')) {
            \Log::info('Photo upload detected', [
                'original_name' => $request->file('photo')->getClientOriginalName(),
                'size' => $request->file('photo')->getSize(),
                'mime_type' => $request->file('photo')->getMimeType()
            ]);
            
            // Delete old photo if exists
            if ($student->photo && \Storage::disk('public')->exists($student->photo)) {
                \Storage::disk('public')->delete($student->photo);
                \Log::info('Old photo deleted: ' . $student->photo);
            }
            
            $file = $request->file('photo');
            $validated['photo'] = $this->compressAndStore($file, 'students');
            \Log::info('New photo stored: ' . $validated['photo']);
        } else {
            \Log::info('No photo file uploaded');
        }

        // Handle document uploads
        $documentFields = [
            'birth_certificate_file',
            'vaccination_card',
            'father_nid_file',
            'mother_nid_file',
            'previous_school_certificate',
            'other_documents'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                \Log::info("Document upload detected for {$field}", [
                    'original_name' => $request->file($field)->getClientOriginalName(),
                    'size' => $request->file($field)->getSize(),
                    'mime_type' => $request->file($field)->getMimeType()
                ]);
                
                // Delete old document if exists
                if ($student->$field && \Storage::disk('public')->exists($student->$field)) {
                    \Storage::disk('public')->delete($student->$field);
                    \Log::info("Old document deleted: " . $student->$field);
                }
                
                $file = $request->file($field);
                $validated[$field] = $this->compressAndStore($file, 'documents');
                \Log::info("New document stored for {$field}: " . $validated[$field]);
            }
        }

        // Update student
        $student->update($validated);

        return redirect()->route('tenant.students.show', $id)
            ->with('success', 'শিক্ষার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে');
    }

    /**
     * View document in browser
     */
    public function viewDocument($id, $field)
    {
        $student = Student::findOrFail($id);
        
        if (!$student->$field) {
            abort(404, 'ডকুমেন্ট পাওয়া যায়নি');
        }

        $filePath = storage_path('app/public/' . $student->$field);
        
        if (!file_exists($filePath)) {
            abort(404, 'ফাইল পাওয়া যায়নি');
        }

        $mimeType = mime_content_type($filePath);
        $filename = basename($student->$field);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Download document
     */
    public function downloadDocument($id, $field)
    {
        $student = Student::findOrFail($id);
        
        if (!$student->$field) {
            abort(404, 'ডকুমেন্ট পাওয়া যায়নি');
        }

        $filePath = storage_path('app/public/' . $student->$field);
        
        if (!file_exists($filePath)) {
            abort(404, 'ফাইল পাওয়া যায়নি');
        }

        $extension = pathinfo($student->$field, PATHINFO_EXTENSION);
        $documentNames = [
            'birth_certificate_file' => 'জন্ম_নিবন্ধন_সনদ',
            'vaccination_card' => 'টিকা_কার্ড',
            'father_nid_file' => 'পিতার_ভোটার_আইডি',
            'mother_nid_file' => 'মাতার_ভোটার_আইডি',
            'previous_school_certificate' => 'পূর্ববর্তী_স্কুলের_সনদ',
            'other_documents' => 'অন্যান্য_ডকুমেন্ট'
        ];

        $documentName = $documentNames[$field] ?? 'ডকুমেন্ট';
        $filename = $student->name_en . '_' . $documentName . '.' . $extension;

        return response()->download($filePath, $filename);
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $studentName = $student->name_bn;
            
            // Delete associated files if they exist
            if ($student->photo) {
                \Storage::disk('public')->delete($student->photo);
            }
            
            // Delete the student
            $student->delete();

            return redirect()->route('tenant.students.index')
                ->with('success', 'শিক্ষার্থী "' . $studentName . '" সফলভাবে মুছে ফেলা হয়েছে');
        } catch (\Exception $e) {
            \Log::error('Student deletion failed: ' . $e->getMessage());
            
            return redirect()->route('tenant.students.index')
                ->with('error', 'শিক্ষার্থী মুছতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'integer|exists:students,id'
            ]);

            $studentIds = $request->input('student_ids');
            $students = Student::whereIn('id', $studentIds)->get();

            // Delete associated files for each student
            foreach ($students as $student) {
                if ($student->photo) {
                    \Storage::disk('public')->delete($student->photo);
                }
            }

            // Delete all selected students
            $deletedCount = Student::whereIn('id', $studentIds)->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount} জন শিক্ষার্থী সফলভাবে মুছে ফেলা হয়েছে"
                ]);
            }

            return redirect()->route('tenant.students.index')
                ->with('success', "{$deletedCount} জন শিক্ষার্থী সফলভাবে মুছে ফেলা হয়েছে");
        } catch (\Exception $e) {
            \Log::error('Bulk student deletion failed: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'শিক্ষার্থী মুছতে সমস্যা হয়েছে: ' . $e->getMessage()
                ], 400);
            }

            return redirect()->route('tenant.students.index')
                ->with('error', 'শিক্ষার্থী মুছতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    // Generate ID Card View
    public function idCard($id)
    {
        $student = Student::findOrFail($id);
        $schoolSettings = \App\Models\SchoolSetting::getSettings();
        return view('tenant.students.id-card', compact('student', 'schoolSettings'));
    }

    /**
     * Approve Admission Application
     */
    public function approveAdmission($id)
    {
        try {
            $application = AdmissionApplication::findOrFail($id);

            // Check if already approved
            if ($application->status === 'approved') {
                return redirect()->back()->with('error', 'এই আবেদনটি ইতিমধ্যে অনুমোদিত হয়েছে।');
            }

            // Determine Section and Roll
            // Use application section or default to 'A'
            $section = $application->section ?? 'A';
            
            $lastStudent = Student::where('class', $application->class)
                ->where('section', $section)
                ->orderByRaw('CAST(roll AS UNSIGNED) DESC')
                ->first();
                
            $nextRoll = 1;
            if ($lastStudent && is_numeric($lastStudent->roll)) {
                $nextRoll = intval($lastStudent->roll) + 1;
            }

            // Create Student Data
            $studentData = [
                'name_bn' => $application->name_bn,
                'name_en' => $application->name_en,
                'date_of_birth' => $application->date_of_birth,
                'gender' => $application->gender,
                'religion' => $application->religion,
                'nationality' => $application->nationality,
                'phone' => $application->phone, // Student phone
                'email' => $application->email,
                
                // Addresses
                'present_address' => $application->present_address,
                'permanent_address' => $application->permanent_address,
                
                // Academic
                'class' => $application->class,
                'group' => $application->group,
                'section' => $section,
                'roll' => (string)$nextRoll,
                'admission_date' => now(),
                'academic_year' => date('Y'),
                'previous_school' => $application->previous_school,
                'remarks' => $application->remarks,
                
                // Parent Info
                'father_name' => $application->father_name,
                'father_mobile' => $application->father_mobile,
                'father_occupation' => $application->father_occupation,
                'father_nid' => $application->father_nid,
                'father_email' => $application->father_email,
                'father_income' => $application->father_income,
                
                'mother_name' => $application->mother_name,
                'mother_mobile' => $application->mother_mobile,
                'mother_occupation' => $application->mother_occupation,
                'mother_nid' => $application->mother_nid,
                'mother_email' => $application->mother_email,
                
                'parent_phone' => $application->father_mobile, // Main parent contact
                
                // Guardian Info
                'guardian_name' => $application->guardian_name,
                'guardian_mobile' => $application->guardian_mobile,
                'guardian_relation' => $application->guardian_relation,
                'guardian_address' => $application->guardian_address,
                
                // Additional Info
                'special_needs' => $application->special_needs,
                'health_condition' => $application->health_condition,
                'emergency_contact' => $application->emergency_contact,
                
                // Files
                'photo' => $application->photo,
                'birth_certificate_file' => $application->birth_certificate_file,
                'vaccination_card' => $application->vaccination_card,
                'father_nid_file' => $application->father_nid_file,
                'mother_nid_file' => $application->mother_nid_file,
                'previous_school_certificate' => $application->previous_school_certificate,
                'other_documents' => $application->other_documents,
                
                'status' => 'active',
                'admission_type' => 'Online',
                'eiin_number' => '130512', // Default
                'board' => 'Dhaka', // Default
            ];

            // Create the student
            $student = Student::create($studentData);

            // Update Application Status
            $application->update(['status' => 'approved']);

            // Send Notifications if enabled
            try {
                $notificationSettings = NotificationSetting::getSettings();
                
                if ($notificationSettings->sms_admission) {
                    $smsService = new SmsService();
                    $parentPhone = $student->parent_phone ?? $student->father_mobile ?? $student->mother_mobile;
                    
                    if ($parentPhone) {
                        $schoolName = SchoolSetting::getSetting('school_name_bn', 'স্মার্ট পাঠশালা');
                        $rollBengali = $this->convertToBengaliNumber($student->roll);
                        $message = "অভিনন্দন! আপনার সন্তান {$student->name_bn} {$schoolName}-এ ভর্তির জন্য অনুমোদিত হয়েছে। ক্লাস: {$student->class}, রোল: {$rollBengali}।";
                        $smsService->sendSms($parentPhone, $message);
                    }
                }
                
                // Add Database Notification
                if ($notificationSettings->push_admission) {
                    $schoolName = SchoolSetting::getSetting('school_name_bn', 'স্মার্ট পাঠশালা');
                    $rollBengali = $this->convertToBengaliNumber($student->roll);
                    $notificationTitle = "ভর্তি অনুমোদন";
                    $notificationMessage = "অভিনন্দন! আপনার সন্তান {$student->name_bn} {$schoolName}-এ ভর্তির জন্য অনুমোদিত হয়েছে। ক্লাস: {$student->class}, রোল: {$rollBengali}।";
                    
                    // Notify Guardian if exists
                    if ($student->guardian_id) {
                        \App\Models\Notification::create([
                            'notifiable_id' => $student->guardian_id,
                            'notifiable_type' => \App\Models\Guardian::class,
                            'title' => $notificationTitle,
                            'message' => $notificationMessage,
                            'type' => 'admission_approved',
                        ]);
                    }
                    
                    // Notify Student
                    \App\Models\Notification::create([
                        'notifiable_id' => $student->id,
                        'notifiable_type' => \App\Models\Student::class,
                        'title' => $notificationTitle,
                        'message' => $notificationMessage,
                        'type' => 'admission_approved',
                    ]);
                }
                
            } catch (\Exception $e) {
                \Log::error('Admission Notification Failed: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'আবেদনটি সফলভাবে অনুমোদিত হয়েছে। শিক্ষার্থী আইডি: ' . $student->student_id);

        } catch (\Exception $e) {
            \Log::error('Admission Approval Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'অনুমোদন ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Convert English numbers to Bengali
     */
    private function convertToBengaliNumber($number)
    {
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($englishNumbers, $bengaliNumbers, $number);
    }
}

<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $tenantCount = Tenant::count();
        // নতুন স্কুলগুলোর তালিকা দেখার জন্য
        $tenants = Tenant::with('domains')->latest()->get();

        return view('central.admin.dashboard', compact('tenantCount', 'tenants'));
    }

    public function schools()
    {
        $tenants = Tenant::with('domains')->latest()->paginate(20);
        
        return view('central.admin.schools', compact('tenants'));
    }

    // Production API Methods
    public function storeSchool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'school_id' => 'required|string|unique:tenants,id',
            'domain' => 'required|string|alpha_num|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'principal_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if domain already exists with full format
        $fullDomain = $request->domain . '.smartpathshala.test';
        if (\App\Models\Domain::where('domain', $fullDomain)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Domain already exists',
                'errors' => ['domain' => ['This domain is already taken']]
            ], 422);
        }

        try {
            // Debug: Log request data
            \Log::info('School creation request data: ' . json_encode($request->all()));
            
            // Create Tenant
            \Log::info('Creating tenant with ID: ' . $request->school_id);
            $tenant = Tenant::create(['id' => $request->school_id]);
            \Log::info('Tenant created successfully');
            
            // Create tenant database
            \Log::info('Creating tenant database');
            $tenant->createDatabase();
            \Log::info('Tenant database created successfully');
            
            // Run tenant migrations
            \Log::info('Running tenant migrations');
            $tenant->runMigrations();
            \Log::info('Tenant migrations completed');
            
            // Create Domain with subdomain format
            \Log::info('Creating domain: ' . $request->domain . '.smartpathshala.test');
            $domain = $tenant->domains()->create([
                'domain' => $request->domain . '.smartpathshala.test'
            ]);
            \Log::info('Domain created successfully');

            // Initialize tenant to create admin user
            \Log::info('Initializing tenant');
            tenancy()->initialize($tenant);
            \Log::info('Tenant initialized successfully');
            
            // Create admin user with provided credentials in tenant database
            \Log::info('Creating admin user in tenant database');
            $adminUser = \App\Models\User::create([
                'name' => $request->principal_name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'email_verified_at' => now(),
                'role' => 'admin'
            ]);
            \Log::info('Admin user created successfully in tenant DB');

            // Store additional school data
            $tenant->data = [
                'school_name' => $request->school_name,
                'email' => $request->email,
                'password' => $request->password, // Store for reference (consider security)
                'phone' => $request->phone,
                'address' => $request->address,
                'principal_name' => $request->principal_name,
                'capacity' => $request->capacity,
                'admin_user_id' => $adminUser->id,
                'created_by' => auth()->id()
            ];
            $tenant->save();

            // Return to central context
            tenancy()->end();

            return response()->json([
                'success' => true,
                'message' => 'স্কুল সফলভাবে যোগ করা হয়েছে!',
                'school' => [
                    'id' => $tenant->id,
                    'school_name' => $request->school_name,
                    'domain' => $domain->domain,
                    'admin_email' => $request->email,
                    'admin_password' => $request->password,
                    'login_url' => 'http://' . $domain->domain . '/login',
                    'created_at' => $tenant->created_at->format('d M, Y')
                ]
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('School creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'স্কুল যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => collect($e->getTrace())->take(3)->toArray()
                ]
            ], 500);
        }
    }

    public function deleteSchool($id)
    {
        try {
            $tenant = Tenant::find($id);
            
            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'স্কুল পাওয়া যায়নি'
                ], 404);
            }

            // Delete tenant database
            $tenant->deleteDatabase();
            
            // Delete tenant (will also delete domains)
            $tenant->delete();

            return response()->json([
                'success' => true,
                'message' => 'স্কুল সফলভাবে ডিলিট করা হয়েছে'
            ]);

        } catch (\Exception $e) {
            \Log::error('School deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'স্কুল ডিলিট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewSchool($id)
    {
        try {
            $tenant = Tenant::with('domains')->findOrFail($id);
            
            return view('central.admin.school-view', compact('tenant'));

        } catch (\Exception $e) {
            \Log::error('School view error: ' . $e->getMessage());
            
            return redirect()->route('central.schools')
                ->with('error', 'স্কুলের তথ্য পেতে সমস্যা হয়েছে');
        }
    }

    public function getSchool($id)
    {
        try {
            $tenant = Tenant::with('domains')->findOrFail($id);
            
            $schoolData = [
                'id' => $tenant->id,
                'data' => $tenant->data ?? [],
                'domains' => $tenant->domains->toArray() ?? [],
                'created_at' => $tenant->created_at->format('d M, Y')
            ];
            
            return response()->json([
                'success' => true,
                'data' => $schoolData
            ]);

        } catch (\Exception $e) {
            \Log::error('School retrieval error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'স্কুলের তথ্য পেতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSchool(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'principal_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tenant = Tenant::findOrFail($id);
            
            // Update tenant data
            $tenant->data = array_merge($tenant->data ?? [], [
                'school_name' => $request->school_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'principal_name' => $request->principal_name,
                'capacity' => $request->capacity,
                'updated_by' => auth()->id()
            ]);
            $tenant->save();

            return response()->json([
                'success' => true,
                'message' => 'স্কুল তথ্য সফলভাবে আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function toggleLock(Request $request, $id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            
            $tenant->is_locked = !$tenant->is_locked;
            $tenant->save();

            $status = $tenant->is_locked ? 'লক' : 'আনলক';
            
            return response()->json([
                'success' => true,
                'message' => "স্কুল সফলভাবে {$status} করা হয়েছে",
                'is_locked' => $tenant->is_locked
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createAdmin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন ব্যর্থ হয়েছে',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tenant = Tenant::findOrFail($id);
            
            // Initialize tenant
            tenancy()->initialize($tenant);
            
            // Check if email already exists in tenant database
            if (\App\Models\User::where('email', $request->email)->exists()) {
                tenancy()->end();
                return response()->json([
                    'success' => false,
                    'message' => 'এই ইমেইল দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট আছে'
                ], 422);
            }
            
            // Create admin user in tenant database
            $admin = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'plain_password' => $request->password, // Store plain password for super admin
                'phone' => $request->phone,
                'email_verified_at' => now(),
                'role' => 'admin'
            ]);
            
            $adminData = [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'plain_password' => $admin->plain_password
            ];
            
            // Return to central context
            tenancy()->end();

            return response()->json([
                'success' => true,
                'message' => 'অ্যাডমিন সফলভাবে তৈরি করা হয়েছে',
                'admin' => $adminData
            ]);

        } catch (\Exception $e) {
            // Make sure to end tenancy even on error
            try {
                tenancy()->end();
            } catch (\Exception $endException) {
                // Ignore if already ended
            }
            
            \Log::error('Admin creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'অ্যাডমিন তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAdmins($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            
            // Initialize tenant
            tenancy()->initialize($tenant);
            
            // Get all admin users from tenant database
            $admins = \App\Models\User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->get();
            
            $adminsList = $admins->map(function($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'phone' => $admin->phone ?? null,
                    'plain_password' => $admin->plain_password ?? 'N/A',
                    'created_at' => $admin->created_at->format('d M, Y')
                ];
            });
            
            // Return to central context
            tenancy()->end();

            return response()->json([
                'success' => true,
                'admins' => $adminsList
            ]);

        } catch (\Exception $e) {
            // Make sure to end tenancy even on error
            try {
                tenancy()->end();
            } catch (\Exception $endException) {
                // Ignore if already ended
            }
            
            \Log::error('Get admins error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'অ্যাডমিন তালিকা পেতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAdmin($tenantId, $adminId)
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);
            
            // Initialize tenant
            tenancy()->initialize($tenant);
            
            $admin = \App\Models\User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->findOrFail($adminId);
            
            // Check if this is the last admin
            $adminCount = \App\Models\User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->count();
                
            if ($adminCount <= 1) {
                tenancy()->end();
                return response()->json([
                    'success' => false,
                    'message' => 'সর্বশেষ অ্যাডমিন ডিলিট করা যাবে না'
                ], 422);
            }
            
            $admin->delete();
            
            // Return to central context
            tenancy()->end();

            return response()->json([
                'success' => true,
                'message' => 'অ্যাডমিন সফলভাবে ডিলিট করা হয়েছে'
            ]);

        } catch (\Exception $e) {
            // Make sure to end tenancy even on error
            try {
                tenancy()->end();
            } catch (\Exception $endException) {
                // Ignore if already ended
            }
            
            \Log::error('Delete admin error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'অ্যাডমিন ডিলিট করতে সমস্যা হয়েছে'
            ], 500);
        }
    }

    public function resetAdminPassword(Request $request, $tenantId, $adminId)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে'
            ], 422);
        }

        try {
            $tenant = Tenant::findOrFail($tenantId);
            
            // Initialize tenant
            tenancy()->initialize($tenant);
            
            $admin = \App\Models\User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->findOrFail($adminId);
            
            $admin->password = \Hash::make($request->password);
            $admin->plain_password = $request->password; // Update plain password too
            $admin->save();
            
            // Return to central context
            tenancy()->end();

            return response()->json([
                'success' => true,
                'message' => 'পাসওয়ার্ড সফলভাবে রিসেট করা হয়েছে'
            ]);

        } catch (\Exception $e) {
            // Make sure to end tenancy even on error
            try {
                tenancy()->end();
            } catch (\Exception $endException) {
                // Ignore if already ended
            }
            
            \Log::error('Reset password error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'পাসওয়ার্ড রিসেট করতে সমস্যা হয়েছে'
            ], 500);
        }
    }

    /**
     * Get admission applications from all tenants
     */
    private function getAdmissionApplications()
    {
        $applications = [];
        $tenants = Tenant::with('domains')->get();

        foreach ($tenants as $tenant) {
            try {
                // Initialize tenant
                tenancy()->initialize($tenant);
                
                // Get recent admission applications
                $tenantApplications = \App\Models\AdmissionApplication::with([])
                    ->latest()
                    ->take(10)
                    ->get();

                foreach ($tenantApplications as $application) {
                    $applications[] = [
                        'id' => $application->id,
                        'application_id' => $application->application_id,
                        'name_bn' => $application->name_bn,
                        'name_en' => $application->name_en,
                        'father_name' => $application->father_name,
                        'mother_name' => $application->mother_name,
                        'class' => $application->class,
                        'section' => $application->section,
                        'phone' => $application->phone,
                        'photo' => $application->photo,
                        'status' => $application->status,
                        'created_at' => $application->created_at,
                        'tenant_id' => $tenant->id,
                        'school_name' => $tenant->data['school_name'] ?? $tenant->id,
                        'domain' => $tenant->domains->first()->domain ?? null,
                        // Generate student numbers based on tenant settings
                        'registration_number' => $this->generateRegistrationNumber($tenant, $application),
                        'roll_number' => $this->generateRollNumber($tenant, $application),
                        'id_number' => $this->generateIdNumber($tenant, $application),
                    ];
                }

                // Return to central context
                tenancy()->end();

            } catch (\Exception $e) {
                // Make sure to end tenancy even on error
                try {
                    tenancy()->end();
                } catch (\Exception $endException) {
                    // Ignore if already ended
                }
                
                \Log::error("Error fetching admission applications for tenant {$tenant->id}: " . $e->getMessage());
                continue;
            }
        }

        // Sort by created_at desc
        usort($applications, function($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return collect($applications)->take(20); // Return latest 20 applications
    }

    /**
     * Generate registration number based on tenant settings
     */
    private function generateRegistrationNumber($tenant, $application)
    {
        try {
            // Initialize tenant to get settings
            tenancy()->initialize($tenant);
            
            $settings = \App\Models\SchoolSetting::getSettings();
            $shortCode = $settings->short_code ?? '101';
            $year = $application->created_at->format('y');
            
            // Get application count for this year
            $count = \App\Models\AdmissionApplication::whereYear('created_at', $application->created_at->year)->count();
            
            tenancy()->end();
            
            return $shortCode . '-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            
        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $ex) {}
            return 'REG-' . $application->created_at->format('y') . '-' . str_pad($application->id, 4, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Generate roll number
     */
    private function generateRollNumber($tenant, $application)
    {
        try {
            tenancy()->initialize($tenant);
            
            // Get count of applications for same class
            $count = \App\Models\AdmissionApplication::where('class', $application->class)
                ->where('status', 'approved')
                ->count();
            
            tenancy()->end();
            
            return str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            
        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $ex) {}
            return str_pad($application->id, 3, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Generate ID number based on tenant settings
     */
    private function generateIdNumber($tenant, $application)
    {
        try {
            tenancy()->initialize($tenant);
            
            $settings = \App\Models\SchoolSetting::getSettings();
            $initials = $settings->school_initials ?? strtoupper(substr($tenant->id, 0, 3));
            $year = $application->created_at->format('y');
            
            tenancy()->end();
            
            return $initials . '-' . $year . '-' . str_pad($application->id, 4, '0', STR_PAD_LEFT);
            
        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $ex) {}
            return 'ID-' . $application->created_at->format('y') . '-' . str_pad($application->id, 4, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Get admission application details for modal
     */
    public function getAdmissionApplication($tenantId, $applicationId)
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);
            
            // Initialize tenant
            tenancy()->initialize($tenant);
            
            $application = \App\Models\AdmissionApplication::findOrFail($applicationId);
            
            $applicationData = [
                'id' => $application->id,
                'application_id' => $application->application_id,
                'name_bn' => $application->name_bn,
                'name_en' => $application->name_en,
                'father_name' => $application->father_name,
                'mother_name' => $application->mother_name,
                'date_of_birth' => $application->date_of_birth->format('d/m/Y'),
                'gender' => $application->gender,
                'religion' => $application->religion,
                'class' => $application->class,
                'section' => $application->section,
                'phone' => $application->phone,
                'email' => $application->email,
                'present_address' => $application->present_address,
                'permanent_address' => $application->permanent_address,
                'photo' => $application->photo ? tenant_asset($application->photo) : null,
                'status' => $application->status,
                'created_at' => $application->created_at->format('d M, Y'),
                'tenant_id' => $tenant->id,
                'school_name' => $tenant->data['school_name'] ?? $tenant->id,
                'registration_number' => $this->generateRegistrationNumber($tenant, $application),
                'roll_number' => $this->generateRollNumber($tenant, $application),
                'id_number' => $this->generateIdNumber($tenant, $application),
            ];
            
            // Return to central context
            tenancy()->end();

            return response()->json([
                'success' => true,
                'application' => $applicationData
            ]);

        } catch (\Exception $e) {
            // Make sure to end tenancy even on error
            try {
                tenancy()->end();
            } catch (\Exception $endException) {
                // Ignore if already ended
            }
            
            \Log::error('Get admission application error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'আবেদনের তথ্য পেতে সমস্যা হয়েছে'
            ], 500);
        }
    }
}

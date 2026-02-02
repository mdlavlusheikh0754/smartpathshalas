<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\NotificationSetting;
use App\Models\SchoolSetting;
use App\Services\SmsService;

class FeeController extends Controller
{
    public function index()
    {
        try {
            // Get fee collections with student data
            $feeCollections = \App\Models\FeeCollection::with('student')
                ->orderBy('collected_at', 'desc')
                ->get();

            // Transform data for JavaScript
            $feeRecordsData = $feeCollections->map(function($collection) {
                return [
                    'id' => $collection->id,
                    'date' => $collection->collected_at->format('d/m/Y'),
                    'student' => $collection->student_name,
                    'roll' => $collection->student->roll ?? 'N/A',
                    'class' => $collection->student_class ?? 'N/A',
                    'feeType' => $collection->fee_type,
                    'feeTypeName' => $collection->fee_type_name,
                    'totalAmount' => (float)$collection->total_amount,
                    'paidAmount' => (float)$collection->paid_amount,
                    'dueAmount' => (float)$collection->due_amount,
                    'status' => $collection->due_amount <= 0 ? 'paid' : ($collection->paid_amount > 0 ? 'partial' : 'due'),
                    'receiptNumber' => $collection->receipt_number, // Add receipt number
                    'receiptNumberBengali' => $this->convertToBengaliNumber($collection->receipt_number), // Add Bengali receipt number
                    'paymentMethod' => $collection->payment_method,
                    'collectedBy' => $collection->collected_by
                ];
            });

            return view('tenant.fees.index', compact('feeRecordsData'));
        } catch (\Exception $e) {
            Log::error('Fee index error: ' . $e->getMessage());
            $feeRecordsData = collect();
            return view('tenant.fees.index', compact('feeRecordsData'));
        }
    }

    public function collect()
    {
        try {
            $students = Student::select([
                'id', 'student_id', 'name_bn', 'name_en', 'class', 'section', 
                'roll', 'status', 'academic_year', 'photo'
            ])
            ->where('status', 'active')
            ->orderBy('class', 'asc')
            ->orderBy('section', 'asc')
            ->orderBy('roll', 'asc')
            ->get();

            $studentsData = $students->map(function($student) {
                return [
                    'id' => $student->student_id ?? $student->id,
                    'db_id' => $student->id,
                    'name' => $student->name_bn ?? $student->name_en ?? 'N/A',
                    'roll' => $student->roll ?? 'N/A',
                    'class' => $student->class ?? 'N/A',
                    'section' => $student->section ?? 'A'
                ];
            });

            return view('tenant.fees.collect', compact('studentsData'));
        } catch (\Exception $e) {
            Log::error('Fee collect landing page error: ' . $e->getMessage());
            $studentsData = collect();
            return view('tenant.fees.collect', compact('studentsData'));
        }
    }

    public function collectAdmission()
    {
        try {
            // Get all students from database with only existing columns
            $students = Student::select([
                'id', 'student_id', 'name_bn', 'name_en', 'class', 'section', 
                'roll', 'admission_fee', 'status', 'admission_date', 'academic_year', 'photo'
            ])
            ->orderBy('class', 'asc')
            ->orderBy('section', 'asc')
            ->orderBy('roll', 'asc')
            ->get();
            
            Log::info('FeeController - Students query result', [
                'count' => $students->count(),
                'first_student' => $students->first()
            ]);

            // Get fee structures for admission fees
            $feeStructures = \App\Models\FeeStructure::where('fee_type', 'admission')
                ->where('is_active', true)
                ->orderBy('class_name')
                ->get();

            // Get inventory items that are in stock (optional - table may not exist)
            $inventoryItems = collect();
            try {
                if (Schema::hasTable('inventory_items')) {
                    $inventoryItems = \App\Models\InventoryItem::where('status', '!=', 'out_of_stock')
                        ->orderBy('category')
                        ->orderBy('name')
                        ->get();
                }
            } catch (\Exception $e) {
                Log::warning('Could not load inventory items: ' . $e->getMessage());
                $inventoryItems = collect();
            }

            // Get logged user info
            $currentUser = Auth::user();

            // Transform data for JavaScript
            $studentsData = $students->map(function($student) {
                // Generate photo URL
                $photoUrl = null;
                if ($student->photo && !empty(trim($student->photo))) {
                    try {
                        if (Storage::disk('public')->exists($student->photo)) {
                            $photoUrl = route('tenant.files', ['path' => $student->photo]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('Could not check photo existence: ' . $e->getMessage());
                    }
                }

                return [
                    'id' => $student->student_id ?? $student->id,
                    'name' => $student->name_bn ?? $student->name_en ?? 'N/A',
                    'class' => $student->class ?? 'N/A',
                    'section' => $student->section ?? 'A',
                    'academic_year' => $student->academic_year ?? '2026',
                    'feeType' => 'ভর্তি ফি',
                    'status' => 'বকেয়া',
                    'lastPayment' => 'N/A',
                    'amount' => (int)($student->admission_fee ?? 5000),
                    'photo_url' => $photoUrl
                ];
            });

            Log::info('Students data for admission fees', [
                'total_students' => $students->count(),
                'students_data_count' => $studentsData->count(),
                'first_student' => $studentsData->first()
            ]);

            return view('tenant.fees.collect-admission', compact('students', 'studentsData', 'feeStructures', 'inventoryItems', 'currentUser'));
        } catch (\Exception $e) {
            // If there's an error, return with empty collection and log the error
            Log::error('Admission fee collection error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $students = collect();
            $studentsData = collect();
            $feeStructures = collect();
            $inventoryItems = collect();
            $currentUser = Auth::user();
            return view('tenant.fees.collect-admission', compact('students', 'studentsData', 'feeStructures', 'inventoryItems', 'currentUser'));
        }
    }

    public function collectMonthly()
    {
        try {
            // Debug logging
            Log::info('Monthly fee collection page accessed');
            Log::info('Tenant initialized: ' . (tenancy()->initialized ? 'Yes' : 'No'));
            if (tenancy()->initialized) {
                Log::info('Current tenant: ' . tenant('id'));
            }
            
            // Get school settings for receipt header
            $settings = \App\Models\SchoolSetting::first();
            
            // Get all students from database with only existing columns
            $students = Student::select([
                'id', 'student_id', 'name_bn', 'name_en', 'class', 'section', 
                'roll', 'status', 'academic_year', 'photo'
            ])
            ->where('status', 'active')
            ->orderBy('class', 'asc')
            ->orderBy('section', 'asc')
            ->orderBy('roll', 'asc')
            ->get();

            Log::info('Students found: ' . $students->count());

            // Get fee structures for monthly fees
            $feeStructures = \App\Models\FeeStructure::where('fee_type', 'monthly')
                ->where('is_active', true)
                ->orderBy('class_name')
                ->get();

            Log::info('Fee structures found: ' . $feeStructures->count());

            // Get logged user info
            $currentUser = Auth::user();

            // Get all monthly fee collections for current year to build payment status
            $currentYear = date('Y');
            $monthlyPayments = \App\Models\FeeCollection::where('fee_type', 'monthly')
                ->where('year', $currentYear) // Use the year column
                ->where('status', 'completed')
                ->get()
                ->groupBy('student_id');

            Log::info('Monthly payments found: ' . $monthlyPayments->count());

            // Enrich students collection with payment status
            $currentMonthName = strtolower(date('F'));
            
            $students->each(function($student) use ($monthlyPayments, $currentYear, $currentMonthName) {
                $studentPayments = $monthlyPayments->get($student->id, collect());
                
                $hasMonthlyPayment = $studentPayments->contains(function($payment) use ($currentMonthName) {
                    return strtolower($payment->month) === $currentMonthName;
                });

                // Generate photo URL properly
                $photoUrl = null;
                if ($student->photo && !empty(trim($student->photo))) {
                    try {
                        if (Storage::disk('public')->exists($student->photo)) {
                            $photoUrl = route('tenant.files', ['path' => $student->photo]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('Could not check photo existence for monthly fees: ' . $e->getMessage());
                    }
                }
                
                if (!$photoUrl) {
                    $name = $student->name_bn ?? $student->name_en ?? 'Student';
                    $initial = mb_substr($name, 0, 1);
                    $photoUrl = "https://ui-avatars.com/api/?name=" . urlencode($initial) . "&size=128&background=16A34A&color=fff&font-size=0.6";
                }

                // Build month-wise payment status
                $monthlyStatus = [];
                $months = ['january', 'february', 'march', 'april', 'may', 'june', 
                          'july', 'august', 'september', 'october', 'november', 'december'];
                
                foreach ($months as $month) {
                    $isPaid = $studentPayments->contains(function($payment) use ($month) {
                        return strtolower($payment->month) === strtolower($month);
                    });
                    
                    $monthlyStatus[$month] = [
                        'paid' => $isPaid,
                        'status' => $isPaid ? 'পরিশোধিত' : 'বকেয়া',
                        'icon' => $isPaid ? '✓' : '✗',
                        'class' => $isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    ];
                }

                $student->monthly_status_array = $monthlyStatus;
                $student->photo_url_final = $photoUrl;
                $student->has_paid_current_month = $hasMonthlyPayment;
            });

            // Transform for JavaScript
            $studentsData = $students->map(function($student) {
                return [
                    'id' => $student->student_id ?? $student->id,
                    'db_id' => $student->id,
                    'name' => $student->name_bn ?? $student->name_en ?? 'N/A',
                    'roll' => $student->roll ?? 'N/A',
                    'class' => $student->class ?? 'N/A',
                    'section' => $student->section ?? 'A',
                    'feeType' => 'মাসিক ফি',
                    'status' => $student->has_paid_current_month ? 'পরিশোধিত' : 'বকেয়া',
                    'lastPayment' => $student->last_monthly_payment ? 
                        \Carbon\Carbon::parse($student->last_monthly_payment)->format('d/m/Y') : 'প্রযোজ্য নয়',
                    'photo_url' => $student->photo_url_final,
                    'monthly_status' => $student->monthly_status_array
                ];
            });

            Log::info('Students data transformed: ' . $studentsData->count());

            Log::info('Monthly fee students data', [
                'total_students' => $students->count(),
                'students_data_count' => $studentsData->count(),
                'first_student' => $studentsData->first()
            ]);

            // Calculate total collected for current month
            $totalCollected = \App\Models\FeeCollection::whereYear('collected_at', date('Y'))
                ->whereMonth('collected_at', date('m'))
                ->where('status', 'completed')
                ->sum('paid_amount');
            
            // Calculate total students
            $totalStudentsCount = $students->count();
            
            // Estimate total due (simplified: average fee * students - collected)
            $totalDue = ($totalStudentsCount * 500) - $totalCollected; 
            if ($totalDue < 0) $totalDue = 0;

            Log::info('Returning view with data');

            return view('tenant.fees.collect-monthly', compact('students', 'studentsData', 'feeStructures', 'currentUser', 'totalCollected', 'totalDue', 'settings'));
        } catch (\Exception $e) {
            Log::error('Monthly fee collection error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $students = collect();
            $studentsData = collect();
            $feeStructures = collect();
            $currentUser = Auth::user();
            $settings = \App\Models\SchoolSetting::first();
            return view('tenant.fees.collect-monthly', compact('students', 'studentsData', 'feeStructures', 'currentUser', 'settings'));
        }
    }

    public function collectExam()
    {
        try {
            // Debug logging
            Log::info('Exam fee collection page accessed');
            Log::info('Tenant initialized: ' . (tenancy()->initialized ? 'Yes' : 'No'));
            if (tenancy()->initialized) {
                Log::info('Current tenant: ' . tenant('id'));
            }
            
            // Get school settings for receipt header
            $settings = \App\Models\SchoolSetting::first();
            if (!$settings) {
                // Create default settings if none exist
                $settings = \App\Models\SchoolSetting::create([
                    'school_name_bn' => 'ইকরা নূরানিয়া একাডেমি',
                    'school_name_en' => 'Iqra Noorani Academy',
                    'address' => 'ঢাকা, বাংলাদেশ',
                    'established_year' => '২০২০',
                    'principal_name' => 'প্রধান শিক্ষক',
                    'principal_mobile' => '০১৭১২-৩৪৫৬৭৮'
                ]);
            }
            
            // Get all students
            $students = Student::select([
                'id', 'student_id', 'name_bn', 'name_en', 'class', 'section', 
                'roll', 'status', 'academic_year', 'photo'
            ])
            ->where('status', 'active')
            ->orderBy('class', 'asc')
            ->orderBy('section', 'asc')
            ->orderBy('roll', 'asc')
            ->get();

            Log::info('Students found: ' . $students->count());

            // Get fee structures for exam fees
            $feeStructures = \App\Models\FeeStructure::where('fee_type', 'exam')
                ->where('is_active', true)
                ->orderBy('class_name')
                ->get();

            Log::info('Fee structures found: ' . $feeStructures->count());

            // Get logged user info
            $currentUser = Auth::user();

            $currentYear = date('Y');
            $examPayments = \App\Models\FeeCollection::where('fee_type', 'exam')
                ->where('year', $currentYear)
                ->orderBy('collected_at', 'desc')
                ->get()
                ->groupBy('student_id');

            // Transform for JavaScript
            $studentsData = $students->map(function($student) use ($examPayments) {
                $studentPayments = $examPayments->get($student->id, collect());
                $status = 'বকেয়া';
                $lastPayment = 'N/A';
                
                if ($studentPayments->isNotEmpty()) {
                    $lastPayment = $studentPayments->first()->collected_at?->format('d/m/Y') ?? 'N/A';
                    
                    if ($studentPayments->contains(fn($p) => $p->status === 'completed')) {
                        $status = 'পরিশোধিত';
                    } elseif ($studentPayments->contains(fn($p) => $p->status === 'partial')) {
                        $status = 'আংশিক';
                    }
                }

                // Generate photo URL properly
                $photoUrl = null;
                if ($student->photo && !empty(trim($student->photo))) {
                    $photoPath = storage_path('app/public/' . $student->photo);
                    if (file_exists($photoPath)) {
                        $photoUrl = tenant_asset($student->photo);
                    }
                }
                
                if (!$photoUrl) {
                    $name = $student->name_bn ?? $student->name_en ?? 'Student';
                    $initial = mb_substr($name, 0, 1);
                    $photoUrl = "https://ui-avatars.com/api/?name=" . urlencode($initial) . "&size=128&background=A855F7&color=fff&font-size=0.6";
                }

                return [
                    'id' => $student->student_id ?? $student->id,
                    'db_id' => $student->id,
                    'name' => $student->name_bn ?? $student->name_en ?? 'N/A',
                    'roll' => $student->roll ?? 'N/A',
                    'class' => $student->class ?? 'N/A',
                    'section' => $student->section ?? 'A',
                    'feeType' => 'পরীক্ষার ফি',
                    'status' => $status,
                    'lastPayment' => $lastPayment,
                    'photo_url' => $photoUrl
                ];
            })->values();

            Log::info('Students data transformed: ' . $studentsData->count());

            return view('tenant.fees.collect-exam', compact('students', 'studentsData', 'feeStructures', 'currentUser', 'settings'));
        } catch (\Exception $e) {
            Log::error('Exam fee collection error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $students = collect();
            $studentsData = collect();
            $feeStructures = collect();
            $currentUser = Auth::user();
            $settings = (object) [
                'school_name_bn' => 'ইকরা নূরানিয়া একাডেমি',
                'school_name_en' => 'Iqra Noorani Academy',
                'address' => 'ঢাকা, বাংলাদেশ',
                'established_year' => '২০২০',
                'principal_name' => 'প্রধান শিক্ষক',
                'principal_mobile' => '০১৭১২-৩৪৫৬৭৮',
                'logo' => null
            ];
            return view('tenant.fees.collect-exam', compact('students', 'studentsData', 'feeStructures', 'currentUser', 'settings'));
        }
    }

    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Fee collection request received', [
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'accept' => $request->header('Accept'),
                'data' => $request->all()
            ]);

            // Validate the request
            $request->validate([
                'student_id' => 'required|string',
                'fee_type' => 'required|string|in:admission,monthly,exam',
                'exam_type' => 'nullable|string',
                'payment_method' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'donation_amount' => 'nullable|numeric|min:0',
                'zakat_amount' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'grant_amount' => 'nullable|numeric|min:0', // Add grant_amount validation
                'inventory_items' => 'nullable|string',
                'remarks' => 'nullable|string|max:500',
                'month' => 'nullable|string',
                'selected_months' => 'nullable|array', // For multiple months
                'selected_months.*' => 'string',
                'month_count' => 'nullable|integer|min:1',
                'year' => 'nullable|string',
                'academic_year' => 'nullable|string', // Add academic_year validation
                'voucher_no' => 'nullable|string',
                'collection_date' => 'nullable|date',
                'collector_name' => 'nullable|string'
            ]);

            // Find the student
            $student = Student::where('student_id', $request->student_id)
                             ->orWhere('id', $request->student_id)
                             ->first();

            if (!$student) {
                Log::warning('Student not found for fee collection', ['student_id' => $request->student_id]);
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'শিক্ষার্থী পাওয়া যায়নি']);
                }
                return redirect()->back()->with('error', 'শিক্ষার্থী পাওয়া যায়নি');
            }

            Log::info('Student found', ['id' => $student->id, 'name' => $student->name_bn]);

            // Parse inventory items (only for admission fees)
            $inventoryItems = [];
            $inventoryCost = 0;
            if ($request->fee_type === 'admission' && $request->inventory_items) {
                $inventoryItems = json_decode($request->inventory_items, true) ?? [];
                
                // Calculate inventory cost and update stock
                foreach ($inventoryItems as $item) {
                    $inventoryItem = \App\Models\InventoryItem::find($item['id']);
                    if ($inventoryItem) {
                        // Check if enough stock is available
                        if ($inventoryItem->stock < $item['quantity']) {
                            if ($request->ajax()) {
                                return response()->json(['success' => false, 'message' => "পর্যাপ্ত স্টক নেই: {$inventoryItem->name}"]);
                            }
                            return redirect()->back()->with('error', "পর্যাপ্ত স্টক নেই: {$inventoryItem->name}");
                        }
                        
                        // Reduce stock
                        $inventoryItem->stock -= $item['quantity'];
                        $inventoryItem->save();
                        
                        $inventoryCost += $item['totalPrice'];
                    }
                }
            }

            // Calculate amounts
            $totalAmount = (float)$request->total_amount;
            $paidAmount = (float)$request->paid_amount;
            $donationAmount = (float)($request->donation_amount ?? 0);
            $grantAmount = (float)($request->grant_amount ?? $donationAmount); // Use grant_amount if provided, fallback to donation_amount
            $zakatAmount = (float)($request->zakat_amount ?? 0);
            $discountAmount = (float)($request->discount_amount ?? 0);
            
            // Due amount calculation: Total - Discount - Paid - Zakat - Grant
            $dueAmount = $totalAmount - $discountAmount - $paidAmount - $zakatAmount - $grantAmount;

            Log::info('Amounts calculated', ['total' => $totalAmount, 'paid' => $paidAmount, 'due' => $dueAmount]);

            // Handle multiple months for monthly fees
            $month = $request->month;
            if ($request->fee_type === 'exam') {
                $examTypeId = $request->input('exam_type');
                if (!empty($examTypeId)) {
                    $examFee = \App\Models\FeeStructure::find($examTypeId);
                    $month = $examFee?->fee_name ?? (string)$examTypeId;
                }
            }
            
            $selectedMonths = $request->selected_months ?? [$month];
            $academicYear = $request->academic_year ?? $request->year ?? date('Y'); // Use academic_year if provided
            $feeCollections = [];
            $receiptNumbers = [];

            // If multiple months are selected, create separate records for each month
            if ($request->fee_type === 'monthly' && count($selectedMonths) > 1) {
                Log::info('Processing multiple months', ['count' => count($selectedMonths)]);
                $monthlyFee = $totalAmount / count($selectedMonths);
                $monthlyPaid = $paidAmount / count($selectedMonths);
                $monthlyGrant = $grantAmount / count($selectedMonths);
                $monthlyZakat = $zakatAmount / count($selectedMonths);
                $monthlyDiscount = $discountAmount / count($selectedMonths);
                $monthlyDue = max(0, $monthlyFee - $monthlyDiscount - $monthlyPaid - $monthlyZakat - $monthlyGrant);

                foreach ($selectedMonths as $month) {
                    $feeCollection = \App\Models\FeeCollection::create([
                        'student_id' => $student->id,
                        'student_name' => $student->name_bn ?? $student->name_en,
                        'student_class' => $student->class,
                        'student_section' => $student->section,
                        'fee_type' => $request->fee_type,
                        'fee_type_name' => $this->getFeeTypeName($request->fee_type),
                        'month' => $month,
                        'month_count' => 1,
                        'year' => $academicYear,
                        'total_amount' => $monthlyFee,
                        'discount_amount' => $monthlyDiscount,
                        'zakat_amount' => $monthlyZakat,
                        'grant_amount' => $monthlyGrant,
                        'paid_amount' => $monthlyPaid,
                        'due_amount' => $monthlyDue,
                        'inventory_cost' => 0,
                        'payment_method' => $request->payment_method,
                        'inventory_items' => [],
                        'remarks' => $request->remarks,
                        'collected_by' => $request->collector_name ?? (Auth::user() ? Auth::user()->name : 'Admin'),
                        'collected_at' => $request->collection_date ?? now(),
                        'academic_year' => $academicYear,
                        'voucher_no' => $request->voucher_no,
                        'status' => $monthlyDue <= 0 ? 'completed' : 'partial'
                    ]);
                    
                    $feeCollections[] = $feeCollection;
                    $receiptNumbers[] = $feeCollection->receipt_number;
                }
            } else {
                Log::info('Processing single month/admission');
                // Single month or non-monthly fee
                $feeCollection = \App\Models\FeeCollection::create([
                    'student_id' => $student->id,
                    'student_name' => $student->name_bn ?? $student->name_en,
                    'student_class' => $student->class,
                    'student_section' => $student->section,
                    'fee_type' => $request->fee_type,
                    'fee_type_name' => $this->getFeeTypeName($request->fee_type),
                    'month' => $selectedMonths[0] ?? $request->month,
                    'month_count' => $request->month_count ?? 1,
                    'year' => $academicYear,
                    'total_amount' => $totalAmount,
                    'discount_amount' => $discountAmount,
                    'zakat_amount' => $zakatAmount,
                    'grant_amount' => $grantAmount,
                    'paid_amount' => $paidAmount,
                    'due_amount' => max(0, $dueAmount),
                    'inventory_cost' => $inventoryCost,
                    'payment_method' => $request->payment_method,
                    'inventory_items' => $inventoryItems,
                    'remarks' => $request->remarks,
                    'collected_by' => $request->collector_name ?? (Auth::user() ? Auth::user()->name : 'Admin'),
                    'collected_at' => $request->collection_date ?? now(),
                    'academic_year' => $academicYear,
                    'voucher_no' => $request->voucher_no,
                    'status' => $dueAmount <= 0 ? 'completed' : 'partial'
                ]);
                
                $feeCollections[] = $feeCollection;
                $receiptNumbers[] = $feeCollection->receipt_number;
            }

            Log::info('Fee collections created', ['count' => count($feeCollections)]);

            // Update student's fee status if fully paid
            if (count($feeCollections) > 0 && $feeCollections[0]->due_amount <= 0) {
                if ($request->fee_type === 'monthly') {
                    Log::info('Updating student status to current');
                    $student->update([
                        'monthly_fee_status' => 'current',
                        'last_monthly_payment' => now()
                    ]);
                }
            }

            $feeTypeName = $this->getFeeTypeName($request->fee_type);
            
            if (count($selectedMonths) > 1) {
                $monthNames = array_map([$this, 'getMonthName'], $selectedMonths);
                $monthText = ' (' . implode(', ', $monthNames) . ')';
                $receiptText = implode(', ', $receiptNumbers);
            } else {
                $monthText = $selectedMonths[0] ? ' (' . $this->getMonthName($selectedMonths[0]) . ')' : '';
                $receiptText = $receiptNumbers[0];
            }
            
            $successMessage = $feeTypeName . $monthText . ' সফলভাবে সংগ্রহ করা হয়েছে!';

            // Send Notifications if enabled
            try {
                $notificationSettings = NotificationSetting::getSettings();
                
                if ($notificationSettings->sms_fee) {
                    $smsService = new SmsService();
                    $parentPhone = $student->parent_phone ?? $student->father_mobile ?? $student->mother_mobile;
                    
                    if ($parentPhone) {
                        $schoolName = SchoolSetting::getSetting('school_name_bn', 'স্মার্ট পাঠশালা');
                        $paidAmountBengali = $this->convertToBengaliNumber($paidAmount);
                        $message = "পরিশোধিত: {$student->name_bn}। {$feeTypeName} বাবদ {$paidAmountBengali} টাকা গ্রহণ করা হয়েছে। রশিদ নং: {$receiptText}। ধন্যবাদ - {$schoolName}";
                        $smsService->sendSms($parentPhone, $message);
                    }
                }
                
                // Add Database Notification
                if ($notificationSettings->push_fee) {
                    $schoolName = SchoolSetting::getSetting('school_name_bn', 'স্মার্ট পাঠশালা');
                    $paidAmountBengali = $this->convertToBengaliNumber($paidAmount);
                    $notificationTitle = "ফি পরিশোধ সফল";
                    $notificationMessage = "{$feeTypeName} বাবদ {$paidAmountBengali} টাকা গ্রহণ করা হয়েছে। রশিদ নং: {$receiptText}।";
                    
                    // Notify Guardian if exists
                    if ($student->guardian_id) {
                        \App\Models\Notification::create([
                            'notifiable_id' => $student->guardian_id,
                            'notifiable_type' => \App\Models\Guardian::class,
                            'title' => $notificationTitle,
                            'message' => $notificationMessage,
                            'type' => 'fee_collected',
                        ]);
                    }
                    
                    // Notify Student
                    \App\Models\Notification::create([
                        'notifiable_id' => $student->id,
                        'notifiable_type' => \App\Models\Student::class,
                        'title' => $notificationTitle,
                        'message' => $notificationMessage,
                        'type' => 'fee_collected',
                    ]);
                }
                
            } catch (\Exception $e) {
                Log::error('Fee Notification Failed: ' . $e->getMessage());
            }

            // Log successful response
            Log::info('Fee collection successful', [
                'receipt_number' => $receiptText,
                'fee_collection_id' => $feeCollections[0]->id,
                'total_collections' => count($feeCollections)
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => $successMessage,
                    'receipt_number' => $receiptText, // This will be used in JavaScript
                    'fee_collection_id' => $feeCollections[0]->id,
                    'total_collections' => count($feeCollections)
                ], 200, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $redirectRoute = $request->fee_type === 'monthly' ? 'tenant.fees.collect.monthly' : 'tenant.fees.collect.admission';
            return redirect()->route($redirectRoute)->with('success', $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Fee collection validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'ভ্যালিডেশন ত্রুটি: ' . implode(', ', \Illuminate\Support\Arr::flatten($e->errors()))
                ], 422, [
                    'Content-Type' => 'application/json'
                ]);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Fee collection error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'ফি সংগ্রহে সমস্যা হয়েছে। আবার চেষ্টা করুন।'
                ], 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            
            return redirect()->back()->with('error', 'ফি সংগ্রহে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        }
    }

    /**
     * Get fee type name in Bengali
     */
    private function getFeeTypeName($feeType)
    {
        $types = [
            'admission' => 'ভর্তি ফি',
            'monthly' => 'মাসিক বেতন',
            'exam' => 'পরীক্ষার ফি'
        ];
        return $types[$feeType] ?? $feeType;
    }

    /**
     * Get month name in Bengali
     */
    private function getMonthName($monthValue)
    {
        $months = [
            'january' => 'জানুয়ারি',
            'february' => 'ফেব্রুয়ারি',
            'march' => 'মার্চ',
            'april' => 'এপ্রিল',
            'may' => 'মে',
            'june' => 'জুন',
            'july' => 'জুলাই',
            'august' => 'আগস্ট',
            'september' => 'সেপ্টেম্বর',
            'october' => 'অক্টোবর',
            'november' => 'নভেম্বর',
            'december' => 'ডিসেম্বর'
        ];
        return $months[$monthValue] ?? $monthValue;
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

    public function structure()
    {
        return view('tenant.fees.structure');
    }

    public function due()
    {
        return view('tenant.fees.due');
    }
}

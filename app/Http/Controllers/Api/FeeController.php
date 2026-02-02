<?php

namespace App\Http\Controllers\Api;

use App\Models\FeeStructure;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FeeController extends BaseApiController
{
    /**
     * Get fee overview
     */
    public function index(Request $request)
    {
        // Get fee structure summary
        $feeStructures = FeeStructure::where('is_active', true)
            ->select('fee_type', 'class_name', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as fee_count'))
            ->groupBy('fee_type', 'class_name')
            ->orderBy('class_name')
            ->orderBy('fee_type')
            ->get();

        $feeData = $feeStructures->map(function ($fee) {
            return [
                'fee_type' => $fee->fee_type,
                'class' => $fee->class_name,
                'total_amount' => $fee->total_amount,
                'fee_count' => $fee->fee_count,
            ];
        });

        // Get summary statistics
        $totalFeeTypes = FeeStructure::where('is_active', true)->distinct('fee_type')->count();
        $totalClasses = FeeStructure::where('is_active', true)->distinct('class_name')->count();
        $totalAmount = FeeStructure::where('is_active', true)->sum('amount');

        $summary = [
            'total_fee_types' => $totalFeeTypes,
            'total_classes' => $totalClasses,
            'total_amount' => $totalAmount,
            'fee_structures' => $feeData,
        ];

        return $this->sendResponse($summary, 'Fee overview retrieved successfully');
    }

    /**
     * Get fee structure
     */
    public function getFeeStructure(Request $request)
    {
        $query = FeeStructure::where('is_active', true);

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->where('class_name', $request->class);
        }

        if ($request->has('fee_type') && $request->fee_type) {
            $query->where('fee_type', $request->fee_type);
        }

        if ($request->has('frequency') && $request->frequency) {
            $query->where('frequency', $request->frequency);
        }

        $feeStructures = $query->orderBy('class_name')
            ->orderBy('fee_type')
            ->get();

        $feeData = $feeStructures->map(function ($fee) {
            return [
                'id' => $fee->id,
                'fee_type' => $fee->fee_type,
                'fee_name' => $fee->fee_name,
                'class' => $fee->class_name,
                'amount' => $fee->amount,
                'description' => $fee->description,
                'is_mandatory' => $fee->is_mandatory,
                'frequency' => $fee->frequency,
                'applicable_months' => $fee->applicable_months,
                'created_at' => $fee->created_at,
            ];
        });

        return $this->sendResponse($feeData, 'Fee structure retrieved successfully');
    }

    /**
     * Get student fees
     */
    public function getStudentFees($studentId, Request $request)
    {
        $student = Student::find($studentId);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        // Get applicable fee structures for this student's class
        $feeStructures = FeeStructure::where('is_active', true)
            ->where('class_name', $student->class)
            ->orderBy('fee_type')
            ->get();

        $studentFees = $feeStructures->map(function ($fee) use ($student) {
            // TODO: Get actual payment status from fee_payments table
            $isPaid = false; // Default to unpaid
            $paidAmount = 0;
            $dueAmount = $fee->amount;

            return [
                'id' => $fee->id,
                'fee_type' => $fee->fee_type,
                'fee_name' => $fee->fee_name,
                'amount' => $fee->amount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'is_paid' => $isPaid,
                'is_mandatory' => $fee->is_mandatory,
                'frequency' => $fee->frequency,
                'due_date' => now()->addDays(30)->format('Y-m-d'), // TODO: Calculate actual due date
                'payment_status' => $isPaid ? 'paid' : 'pending',
            ];
        });

        $summary = [
            'total_fees' => $feeStructures->sum('amount'),
            'paid_amount' => $studentFees->sum('paid_amount'),
            'due_amount' => $studentFees->sum('due_amount'),
            'paid_count' => $studentFees->where('is_paid', true)->count(),
            'pending_count' => $studentFees->where('is_paid', false)->count(),
        ];

        $data = [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->class,
                'section' => $student->section,
                'student_id' => $student->student_id,
            ],
            'summary' => $summary,
            'fees' => $studentFees,
        ];

        return $this->sendResponse($data, 'Student fees retrieved successfully');
    }

    /**
     * Collect fee payment
     */
    public function collectFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank,online,cheque',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $student = Student::find($request->student_id);
        $feeStructure = FeeStructure::find($request->fee_structure_id);

        // TODO: Create actual fee payment record in database
        $paymentData = [
            'message' => 'Fee payment functionality not fully implemented yet',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->class,
                'student_id' => $student->student_id,
            ],
            'fee' => [
                'id' => $feeStructure->id,
                'fee_type' => $feeStructure->fee_type,
                'fee_name' => $feeStructure->fee_name,
                'total_amount' => $feeStructure->amount,
            ],
            'payment' => [
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'remarks' => $request->remarks,
                'collected_by' => auth()->user()->name ?? 'System',
            ],
            'created_at' => now(),
        ];

        return $this->sendResponse($paymentData, 'Fee payment collected successfully', 201);
    }

    /**
     * Get due fees
     */
    public function getDueFees(Request $request)
    {
        $query = Student::where('status', 'active');

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }

        if ($request->has('section') && $request->section) {
            $query->where('section', $request->section);
        }

        $students = $query->get();

        $dueFeesData = [];

        foreach ($students as $student) {
            $feeStructures = FeeStructure::where('is_active', true)
                ->where('class_name', $student->class)
                ->get();

            $totalDue = 0;
            $dueCount = 0;

            foreach ($feeStructures as $fee) {
                // TODO: Check actual payment status from database
                $isPaid = false; // Default to unpaid
                if (!$isPaid) {
                    $totalDue += $fee->amount;
                    $dueCount++;
                }
            }

            if ($totalDue > 0) {
                $dueFeesData[] = [
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->name,
                        'class' => $student->class,
                        'section' => $student->section,
                        'student_id' => $student->student_id,
                        'guardian_phone' => $student->guardian_phone,
                    ],
                    'due_amount' => $totalDue,
                    'due_count' => $dueCount,
                    'last_payment_date' => null, // TODO: Get from actual payment records
                ];
            }
        }

        // Sort by due amount descending
        usort($dueFeesData, function ($a, $b) {
            return $b['due_amount'] <=> $a['due_amount'];
        });

        $summary = [
            'total_students_with_dues' => count($dueFeesData),
            'total_due_amount' => array_sum(array_column($dueFeesData, 'due_amount')),
            'average_due_per_student' => count($dueFeesData) > 0 ? array_sum(array_column($dueFeesData, 'due_amount')) / count($dueFeesData) : 0,
        ];

        $data = [
            'summary' => $summary,
            'due_fees' => $dueFeesData,
        ];

        return $this->sendResponse($data, 'Due fees retrieved successfully');
    }

    /**
     * Get fee reports
     */
    public function getFeeReports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:collection,due,class_wise,monthly',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'class' => 'nullable|string',
            'month' => 'nullable|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $reportType = $request->report_type;
        $data = [];

        switch ($reportType) {
            case 'collection':
                $data = $this->getCollectionReport($request->date_from, $request->date_to);
                break;
            case 'due':
                $data = $this->getDueReport($request->class);
                break;
            case 'class_wise':
                $data = $this->getClassWiseReport();
                break;
            case 'monthly':
                $data = $this->getMonthlyReport($request->month);
                break;
        }

        return $this->sendResponse($data, ucfirst($reportType) . ' fee report retrieved successfully');
    }

    /**
     * Get collection report
     */
    private function getCollectionReport($dateFrom = null, $dateTo = null)
    {
        // TODO: Implement with actual payment data
        return [
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'total_amount' => 0,
            'total_transactions' => 0,
            'collections' => [],
            'message' => 'Collection report functionality not implemented yet',
        ];
    }

    /**
     * Get due report
     */
    private function getDueReport($class = null)
    {
        // TODO: Implement with actual due payment data
        return [
            'class' => $class,
            'total_due_amount' => 0,
            'total_students' => 0,
            'dues' => [],
            'message' => 'Due report functionality not implemented yet',
        ];
    }

    /**
     * Get class-wise report
     */
    private function getClassWiseReport()
    {
        // TODO: Implement with actual class-wise fee data
        return [
            'classes' => [],
            'summary' => [
                'total_students' => 0,
                'total_amount' => 0,
                'collected_amount' => 0,
                'due_amount' => 0,
            ],
            'message' => 'Class-wise report functionality not implemented yet',
        ];
    }

    /**
     * Get monthly report
     */
    private function getMonthlyReport($month = null)
    {
        $month = $month ?: now()->format('Y-m');
        
        // TODO: Implement with actual monthly fee data
        return [
            'month' => $month,
            'total_amount' => 0,
            'total_transactions' => 0,
            'daily_data' => [],
            'message' => 'Monthly report functionality not implemented yet',
        ];
    }
}
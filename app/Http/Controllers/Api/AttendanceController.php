<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AttendanceController extends BaseApiController
{
    /**
     * Get attendance records with pagination and filters
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['student']);

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        if ($request->has('section') && $request->section) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section', $request->section);
            });
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $attendance = $query->paginate($perPage);

        // Transform data
        $attendance->getCollection()->transform(function ($record) {
            return $this->transformAttendance($record);
        });

        return $this->sendPaginatedResponse($attendance, 'Attendance records retrieved successfully');
    }

    /**
     * Store attendance records
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Check if attendance already exists for this student and date
        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return $this->sendError('Attendance already recorded for this student on this date', [], 409);
        }

        $data = $request->all();
        
        // Set teacher ID if authenticated
        if (auth()->check()) {
            $data['teacher_id'] = auth()->id();
        }

        $attendance = Attendance::create($data);
        $attendance->load('student');

        return $this->sendResponse($this->transformAttendance($attendance), 'Attendance recorded successfully', 201);
    }

    /**
     * Get attendance by class and date
     */
    public function getByClassAndDate($class, $date)
    {
        $students = Student::where('class', $class)
            ->where('status', 'active')
            ->orderBy('roll_number')
            ->get();

        $attendanceData = [];

        foreach ($students as $student) {
            $attendance = Attendance::where('student_id', $student->id)
                ->whereDate('date', $date)
                ->first();

            $attendanceData[] = [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'roll_number' => $student->roll_number,
                    'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                ],
                'attendance' => $attendance ? $this->transformAttendance($attendance, false) : null,
            ];
        }

        return $this->sendResponse($attendanceData, 'Class attendance retrieved successfully');
    }

    /**
     * Store bulk attendance
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'class' => 'required|string',
            'section' => 'nullable|string',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $date = $request->date;
        $attendanceRecords = $request->attendance;
        $teacherId = auth()->id();

        $createdRecords = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($attendanceRecords as $record) {
                // Check if attendance already exists
                $existingAttendance = Attendance::where('student_id', $record['student_id'])
                    ->whereDate('date', $date)
                    ->first();

                if ($existingAttendance) {
                    // Update existing record
                    $existingAttendance->update([
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                        'teacher_id' => $teacherId,
                    ]);
                    $existingAttendance->load('student');
                    $createdRecords[] = $this->transformAttendance($existingAttendance);
                } else {
                    // Create new record
                    $attendance = Attendance::create([
                        'student_id' => $record['student_id'],
                        'date' => $date,
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                        'teacher_id' => $teacherId,
                    ]);
                    $attendance->load('student');
                    $createdRecords[] = $this->transformAttendance($attendance);
                }
            }

            DB::commit();

            return $this->sendResponse([
                'records' => $createdRecords,
                'total_records' => count($createdRecords),
            ], 'Bulk attendance recorded successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendServerError('Failed to record bulk attendance: ' . $e->getMessage());
        }
    }

    /**
     * Get student attendance history
     */
    public function getStudentAttendance($studentId, Request $request)
    {
        $student = Student::find($studentId);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        $query = Attendance::where('student_id', $studentId);

        // Apply date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Get attendance records
        $attendance = $query->orderBy('date', 'desc')->get();

        // Calculate statistics
        $totalDays = $attendance->count();
        $presentDays = $attendance->where('status', 'present')->count();
        $absentDays = $attendance->where('status', 'absent')->count();
        $lateDays = $attendance->where('status', 'late')->count();
        $excusedDays = $attendance->where('status', 'excused')->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays + $lateDays) / $totalDays * 100, 2) : 0;

        $data = [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->class,
                'section' => $student->section,
                'roll_number' => $student->roll_number,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            ],
            'statistics' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
                'excused_days' => $excusedDays,
                'attendance_percentage' => $attendancePercentage,
            ],
            'records' => $attendance->map(function ($record) {
                return $this->transformAttendance($record, false);
            }),
        ];

        return $this->sendResponse($data, 'Student attendance retrieved successfully');
    }

    /**
     * Get attendance reports
     */
    public function getReports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:daily,monthly,class_wise,student_wise',
            'date' => 'required_if:report_type,daily|date',
            'month' => 'required_if:report_type,monthly|date_format:Y-m',
            'class' => 'required_if:report_type,class_wise|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $reportType = $request->report_type;
        $data = [];

        switch ($reportType) {
            case 'daily':
                $data = $this->getDailyReport($request->date);
                break;
            case 'monthly':
                $data = $this->getMonthlyReport($request->month);
                break;
            case 'class_wise':
                $data = $this->getClassWiseReport($request->class, $request->date_from, $request->date_to);
                break;
            case 'student_wise':
                $data = $this->getStudentWiseReport($request->date_from, $request->date_to);
                break;
        }

        return $this->sendResponse($data, ucfirst($reportType) . ' attendance report retrieved successfully');
    }

    /**
     * Transform attendance data
     */
    private function transformAttendance($attendance, $includeStudent = true)
    {
        $data = [
            'id' => $attendance->id,
            'date' => $attendance->date->format('Y-m-d'),
            'status' => $attendance->status,
            'status_text' => $this->getStatusText($attendance->status),
            'remarks' => $attendance->remarks,
            'created_at' => $attendance->created_at,
            'updated_at' => $attendance->updated_at,
        ];

        if ($includeStudent && $attendance->student) {
            $data['student'] = [
                'id' => $attendance->student->id,
                'name' => $attendance->student->name,
                'class' => $attendance->student->class,
                'section' => $attendance->student->section,
                'roll_number' => $attendance->student->roll_number,
            ];
        }

        return $data;
    }

    /**
     * Get status text in Bangla
     */
    private function getStatusText($status)
    {
        $statuses = [
            'present' => 'উপস্থিত',
            'absent' => 'অনুপস্থিত',
            'late' => 'দেরি',
            'excused' => 'ছুটি',
        ];

        return $statuses[$status] ?? 'অজানা';
    }

    /**
     * Get daily attendance report
     */
    private function getDailyReport($date)
    {
        $attendance = Attendance::with('student')
            ->whereDate('date', $date)
            ->get();

        $totalStudents = Student::where('status', 'active')->count();
        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount = $attendance->where('status', 'absent')->count();
        $lateCount = $attendance->where('status', 'late')->count();

        return [
            'date' => $date,
            'summary' => [
                'total_students' => $totalStudents,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'attendance_percentage' => $totalStudents > 0 ? round(($presentCount + $lateCount) / $totalStudents * 100, 2) : 0,
            ],
            'records' => $attendance->map(function ($record) {
                return $this->transformAttendance($record);
            }),
        ];
    }

    /**
     * Get monthly attendance report
     */
    private function getMonthlyReport($month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $attendance = Attendance::with('student')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $groupedByDate = $attendance->groupBy(function ($item) {
            return $item->date->format('Y-m-d');
        });

        $dailyStats = [];
        foreach ($groupedByDate as $date => $records) {
            $dailyStats[] = [
                'date' => $date,
                'present' => $records->where('status', 'present')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
            ];
        }

        return [
            'month' => $month,
            'total_records' => $attendance->count(),
            'daily_statistics' => $dailyStats,
        ];
    }

    /**
     * Get class-wise attendance report
     */
    private function getClassWiseReport($class, $dateFrom = null, $dateTo = null)
    {
        $query = Attendance::with('student')
            ->whereHas('student', function ($q) use ($class) {
                $q->where('class', $class);
            });

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $attendance = $query->get();

        $students = Student::where('class', $class)
            ->where('status', 'active')
            ->get();

        $studentStats = [];
        foreach ($students as $student) {
            $studentAttendance = $attendance->where('student_id', $student->id);
            $totalDays = $studentAttendance->count();
            $presentDays = $studentAttendance->where('status', 'present')->count();
            $lateDays = $studentAttendance->where('status', 'late')->count();

            $studentStats[] = [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'roll_number' => $student->roll_number,
                ],
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $studentAttendance->where('status', 'absent')->count(),
                'late_days' => $lateDays,
                'attendance_percentage' => $totalDays > 0 ? round(($presentDays + $lateDays) / $totalDays * 100, 2) : 0,
            ];
        }

        return [
            'class' => $class,
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'student_statistics' => $studentStats,
        ];
    }

    /**
     * Get student-wise attendance report
     */
    private function getStudentWiseReport($dateFrom = null, $dateTo = null)
    {
        $query = Attendance::with('student');

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $attendance = $query->get();

        $groupedByStudent = $attendance->groupBy('student_id');

        $studentStats = [];
        foreach ($groupedByStudent as $studentId => $records) {
            $student = $records->first()->student;
            $totalDays = $records->count();
            $presentDays = $records->where('status', 'present')->count();
            $lateDays = $records->where('status', 'late')->count();

            $studentStats[] = [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'class' => $student->class,
                    'section' => $student->section,
                    'roll_number' => $student->roll_number,
                ],
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $records->where('status', 'absent')->count(),
                'late_days' => $lateDays,
                'attendance_percentage' => $totalDays > 0 ? round(($presentDays + $lateDays) / $totalDays * 100, 2) : 0,
            ];
        }

        return [
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'student_statistics' => $studentStats,
        ];
    }
}
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentAttendance;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        // Get today's attendance records
        $presentCount = StudentAttendance::where('attendance_date', $today)
            ->where('status', 'present')
            ->count();
        
        $absentCount = StudentAttendance::where('attendance_date', $today)
            ->where('status', 'absent')
            ->count();
        
        $leaveCount = StudentAttendance::where('attendance_date', $today)
            ->where('status', 'leave')
            ->count();
        
        $totalCount = $presentCount + $absentCount;
        $percentage = $totalCount > 0 ? round(($presentCount / $totalCount) * 100, 1) : 0;
        
        // Get recent attendance records
        $recentAttendance = StudentAttendance::with('student')
            ->where('attendance_date', $today)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('tenant.attendance.index', compact('percentage', 'recentAttendance'))
            ->with([
                'present' => $presentCount,
                'absent' => $absentCount,
                'leave' => $leaveCount
            ]);
    }

    public function take()
    {
        return view('tenant.attendance.take');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'attendance_date' => 'required|date',
                'attendances' => 'required|array',
                'attendances.*.student_id' => 'required|exists:students,id',
                'attendances.*.status' => 'required|in:present,absent,leave,late',
                'class_id' => 'nullable|exists:school_classes,id'
            ]);

            $date = $validated['attendance_date'];
            $count = 0;
            
            foreach ($validated['attendances'] as $item) {
                // Check if already exists for this student and date
                $exists = StudentAttendance::where('student_id', $item['student_id'])
                            ->where('attendance_date', $date)
                            ->exists();
                
                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'এই শিক্ষার্থীর উপস্থিতি ইতিমধ্যে নেওয়া হয়েছে (Already Attended)'
                    ]);
                }

                $student = Student::find($item['student_id']);
                
                StudentAttendance::create([
                        'student_id' => $item['student_id'],
                        'attendance_date' => $date,
                        'status' => $item['status'],
                        'class' => $student->class,
                        'section' => $student->section,
                        'academic_year' => date('Y'),
                        'marked_by' => auth()->user()->name ?? 'Admin',
                        'check_in_time' => $item['status'] == 'present' ? now()->format('H:i:s') : null
                ]);
                $count++;
            }

            return response()->json([
                'success' => true, 
                'message' => 'উপস্থিতি সফলভাবে সংরক্ষিত হয়েছে'
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance Save Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'ত্রুটি: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudents(Request $request, $classId)
    {
        try {
            $date = $request->input('date', date('Y-m-d'));
            
            $class = \App\Models\SchoolClass::findOrFail($classId);
            
            $students = Student::where('class', $class->name)
                ->where('section', $class->section)
                ->where('status', 'active')
                ->select('id', 'name_en', 'name_bn', 'roll', 'photo')
                ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
                ->get();
            
            $attendanceData = [];
            foreach ($students as $student) {
                $attendance = StudentAttendance::where('student_id', $student->id)
                                ->where('attendance_date', $date)
                                ->first();
                
                $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($student->name_en) . '&background=10b981&color=fff&size=128';
                if ($student->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->photo)) {
                    $photoUrl = route('tenant.files', ['path' => $student->photo]);
                }

                $attendanceData[] = [
                    'id' => $student->id,
                    'name' => $student->name_en ?: $student->name_bn,
                    'roll_number' => $student->roll,
                    'photo_url' => $photoUrl,
                    'attendance_status' => $attendance ? $attendance->status : null
                ];
            }
            
            return response()->json([
                'success' => true,
                'students' => $attendanceData
            ]);
        } catch (\Exception $e) {
             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function report()
    {
        return view('tenant.attendance.report');
    }

    public function settings()
    {
        $classes = \App\Models\SchoolClass::select('name')->distinct()->orderBy('name')->pluck('name');
        $sections = \App\Models\SchoolClass::select('section')->whereNotNull('section')->distinct()->orderBy('section')->pluck('section');
        return view('tenant.attendance.settings', compact('classes', 'sections'));
    }

    public function idCards()
    {
        $classes = \App\Models\SchoolClass::select('name')->distinct()->orderBy('name')->pluck('name');
        $sections = \App\Models\SchoolClass::select('section')->whereNotNull('section')->distinct()->orderBy('section')->pluck('section');
        $schoolSettings = \App\Models\SchoolSetting::getSettings();
        return view('tenant.attendance.id-cards', compact('classes', 'sections', 'schoolSettings'));
    }

    // API: Mark Attendance by Scan (RFID/QR/Manual ID)
    public function markByScan(Request $request)
    {
        try {
            $code = $request->input('code');
            $date = $request->input('date', date('Y-m-d'));

            if (!$code) {
                return response()->json(['success' => false, 'message' => 'Code is required']);
            }

            // Find student by various identifiers
            $student = Student::where('student_id', $code)
                ->orWhere('qr_code', $code)
                ->orWhere('rfid_card', $code)
                ->orWhere('id', $code)
                ->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'শিক্ষার্থী পাওয়া যায়নি (Student Not Found)']);
            }

            // Check if already attended
            $exists = StudentAttendance::where('student_id', $student->id)
                ->where('attendance_date', $date)
                ->exists();

            if ($exists) {
                // Get student photo URL
                $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($student->name_en) . '&background=10b981&color=fff&size=128';
                if ($student->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->photo)) {
                    $photoUrl = route('tenant.files', ['path' => $student->photo]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'এই শিক্ষার্থীর উপস্থিতি ইতিমধ্যে নেওয়া হয়েছে (Already Attended)',
                    'student' => [
                        'name' => $student->name_en ?: $student->name_bn,
                        'class' => $student->class,
                        'roll' => $student->roll,
                        'photo_url' => $photoUrl
                    ]
                ]);
            }

            // Create attendance
            StudentAttendance::create([
                'student_id' => $student->id,
                'attendance_date' => $date,
                'status' => 'present',
                'class' => $student->class,
                'section' => $student->section,
                'academic_year' => date('Y'),
                'marked_by' => auth()->user()->name ?? 'System',
                'check_in_time' => now()->format('H:i:s')
            ]);

            // Get student photo URL for success response too
            $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($student->name_en) . '&background=10b981&color=fff&size=128';
            if ($student->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->photo)) {
                $photoUrl = route('tenant.files', ['path' => $student->photo]);
            }

            return response()->json([
                'success' => true,
                'message' => 'উপস্থিতি সফলভাবে গ্রহণ করা হয়েছে (Attendance Marked)',
                'student' => [
                    'name' => $student->name_en ?: $student->name_bn,
                    'class' => $student->class,
                    'roll' => $student->roll,
                    'photo_url' => $photoUrl
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Scan Attendance Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'ত্রুটি: ' . $e->getMessage()], 500);
        }
    }

    // API: QR and RFID List
    public function qrRfidList(Request $request)
    {
        Log::info('qrRfidList called', ['user_id' => auth()->id(), 'request' => $request->all()]);
        try {
            $query = Student::query();
            
            if ($request->filled('class')) {
                $query->where('class', $request->input('class'));
            }
            
            if ($request->filled('section')) {
                $query->where('section', $request->input('section'));
            }
            
            if ($request->filled('status')) {
                $status = $request->input('status');
                if ($status === 'active') {
                    $query->where('status', 'active');
                } elseif ($status === 'inactive') {
                    $query->where('status', 'inactive');
                } elseif ($status === 'configured') {
                    $query->whereNotNull('qr_code')->whereNotNull('rfid_card');
                } elseif ($status === 'qr_only') {
                    $query->whereNotNull('qr_code')->whereNull('rfid_card');
                } elseif ($status === 'rfid_only') {
                    $query->whereNull('qr_code')->whereNotNull('rfid_card');
                } elseif ($status === 'not_configured') {
                    $query->whereNull('qr_code')->whereNull('rfid_card');
                }
            }
            
            $students = $query->orderBy('class')
                ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name_en ?: $student->name_bn,
                        'student_id' => $student->student_id,
                        'class' => $student->class,
                        'section' => $student->section,
                        'roll' => $student->roll,
                        'photo_url' => $student->photo_url,
                        'blood_group' => $student->blood_group,
                        'session' => $student->academic_year ?? date('Y'),
                        'parent_mobile' => $student->parent_phone ?? $student->father_mobile ?? $student->guardian_mobile,
                        'qr_code' => $student->qr_code,
                        'rfid_card' => $student->rfid_card,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // API: Generate QR for Single Student
    public function generateQr($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            // Generate QR content (e.g., Student ID)
            $qrContent = $student->student_id;
            
            $student->update([
                'qr_code' => $qrContent
            ]);
            
            return response()->json([
                'success' => true,
                'qr_code' => $qrContent,
                'message' => 'QR Code generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // API: Set RFID for Student
    public function setRfid(Request $request, $id)
    {
        try {
            $request->validate([
                'rfid_card' => 'required|string|max:255'
            ]);
            
            $student = Student::findOrFail($id);
            $student->update([
                'rfid_card' => $request->input('rfid_card')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'RFID Card set successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // API: Generate QR for All Students
    public function generateAllQr(Request $request)
    {
        try {
            $count = 0;
            Student::chunkById(100, function ($students) use (&$count) {
                foreach ($students as $student) {
                    // Generate unique QR code using student_id (which is unique)
                    if ($student->qr_code !== $student->student_id) {
                        $student->update([
                            'qr_code' => $student->student_id
                        ]);
                        $count++;
                    }
                }
            });
            
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => "{$count} টি QR Code সফলভাবে তৈরি/আপডেট করা হয়েছে"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

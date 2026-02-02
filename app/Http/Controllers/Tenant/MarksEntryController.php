<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MarksEntryController extends Controller
{
    /**
     * Get all exams for marks entry
     */
    public function getExams()
    {
        try {
            $exams = Exam::select('id', 'name', 'exam_type', 'start_date', 'end_date', 'status')
                ->active()
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'exams' => $exams
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'পরীক্ষার তথ্য লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all classes
     */
    public function getClasses()
    {
        try {
            $classes = SchoolClass::select('id', 'name', 'section')
                ->orderBy('name')
                ->orderBy('section')
                ->get()
                ->map(function ($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'section' => $class->section,
                        'full_name' => $class->name . ' - ' . $class->section
                    ];
                });

            return response()->json([
                'success' => true,
                'classes' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ক্লাসের তথ্য লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exam subjects for a specific exam
     */
    public function getExamSubjects($examId)
    {
        try {
            $classId = request()->get('class_id');
            $exam = Exam::findOrFail($examId);
            
            if (!$classId) {
                return response()->json([
                    'success' => true,
                    'exam' => [
                        'id' => $exam->id,
                        'name' => $exam->name,
                        'exam_type' => $exam->exam_type
                    ],
                    'subjects' => []
                ]);
            }
            
            // Get subject groups for this exam and class first
            $subjectGroups = \App\Models\SubjectGroup::where('exam_id', $examId)
                                                   ->where('class_id', $classId)
                                                   ->get();
            
            // Get IDs of subjects that are in groups
            $groupedSubjectIds = [];
            foreach ($subjectGroups as $group) {
                $groupedSubjectIds = array_merge($groupedSubjectIds, $group->subject_ids ?? []);
            }
            
            // Get individual exam subjects (exclude those in groups)
            $examSubjectsQuery = ExamSubject::where('exam_id', $examId)
                ->with('subject:id,name,code,class_id')
                ->whereHas('subject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                });
            
            // Exclude subjects that are in groups
            if (!empty($groupedSubjectIds)) {
                $examSubjectsQuery->whereNotIn('subject_id', $groupedSubjectIds);
            }
            
            $examSubjects = $examSubjectsQuery->get()
                ->map(function ($examSubject) {
                    return [
                        'id' => $examSubject->subject_id,
                        'name' => $examSubject->subject->name,
                        'code' => $examSubject->subject->code,
                        'total_marks' => $examSubject->total_marks,
                        'pass_marks' => $examSubject->pass_marks,
                        'exam_date' => $examSubject->exam_date,
                        'type' => 'individual'
                    ];
                });
            
            // Format subject groups with subject names
            $formattedGroups = $subjectGroups->map(function ($group) {
                // Get subject names for this group
                $subjectNames = [];
                if (!empty($group->subject_ids)) {
                    $subjects = Subject::whereIn('id', $group->subject_ids)->get();
                    $subjectNames = $subjects->pluck('name')->toArray();
                }
                
                return [
                    'id' => 'group_' . $group->id,
                    'name' => $group->name,
                    'code' => 'GRP' . $group->id,
                    'total_marks' => $group->total_marks,
                    'pass_marks' => $group->pass_marks,
                    'exam_date' => null,
                    'type' => 'group',
                    'subject_ids' => $group->subject_ids,
                    'subject_names' => $subjectNames,
                    'group_id' => $group->id
                ];
            });
            
            // Combine individual subjects and groups
            $allSubjects = $examSubjects->concat($formattedGroups);

            return response()->json([
                'success' => true,
                'exam' => [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'exam_type' => $exam->exam_type
                ],
                'subjects' => $allSubjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'বিষয়ের তথ্য লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students for a specific class (Optimized for instant loading)
     */
    public function getStudents($classId)
    {
        try {
            // Get the class details first
            $class = SchoolClass::findOrFail($classId);
            
            // Get students with all needed fields
            $students = Student::where('class', $class->name)
                ->where('section', $class->section)
                ->where('status', 'active')
                ->select('id', 'name_bn', 'name_en', 'roll as roll_number', 'registration_number', 'photo')
                ->orderBy('roll')
                ->get();

            // Use array map for faster processing
            $studentsArray = [];
            
            foreach ($students as $student) {
                // Generate photo URL
                $photoUrl = null;
                if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                    $photoUrl = route('tenant.files', ['path' => $student->photo]);
                }
                
                // Fallback to avatar if no photo
                if (!$photoUrl) {
                    $name = $student->name_bn ?? $student->name_en ?? 'Student';
                    $initial = mb_substr($name, 0, 1);
                    $photoUrl = "https://ui-avatars.com/api/?name=" . urlencode($initial) . "&size=128&background=4F46E5&color=fff";
                }
                
                $studentsArray[] = [
                    'id' => $student->id,
                    'name' => $student->name_bn ?? $student->name_en,
                    'roll_number' => $student->roll_number,
                    'registration_number' => $student->registration_number,
                    'photo' => $student->photo,
                    'photo_url' => $photoUrl
                ];
            }

            return response()->json([
                'success' => true,
                'students' => $studentsArray
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ছাত্র/ছাত্রীদের তথ্য লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get existing marks for exam, subject, and class
     */
    public function getMarks(Request $request)
    {
        try {
            // Check if this is a group
            $subjectId = $request->subject_id;
            $isGroup = is_string($subjectId) && strpos($subjectId, 'group_') === 0;
            
            if ($isGroup) {
                // Handle group marks retrieval
                return $this->getGroupMarks($request);
            }
            
            $validator = Validator::make($request->all(), [
                'exam_id' => 'required|exists:exams,id',
                'subject_id' => 'required|exists:subjects,id',
                'class_id' => 'required|exists:school_classes,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'অবৈধ তথ্য',
                    'errors' => $validator->errors()
                ], 422);
            }

            $examSubject = ExamSubject::where('exam_id', $request->exam_id)
                ->where('subject_id', $request->subject_id)
                ->first();

            if (!$examSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই পরীক্ষায় বিষয়টি পাওয়া যায়নি'
                ], 404);
            }

            // Get class details
            $class = SchoolClass::findOrFail($request->class_id);
            
            $results = ExamResult::where('exam_id', $request->exam_id)
                ->where('subject_id', $request->subject_id)
                ->whereHas('student', function ($query) use ($class) {
                    $query->where('class', $class->name)
                          ->where('section', $class->section)
                          ->where('status', 'active');
                })
                ->with('student:id,name_bn,roll,registration_number,photo')
                ->get()
                ->each(function ($result) {
                    if ($result->student) {
                        $photoUrl = null;
                        if ($result->student->photo && Storage::disk('public')->exists($result->student->photo)) {
                            $photoUrl = route('tenant.files', ['path' => $result->student->photo]);
                        }
                        $result->student->photo_url = $photoUrl;
                        // Ensure roll_number is available for frontend
                        $result->student->roll_number = $result->student->roll;
                    }
                })
                ->keyBy('student_id');

            return response()->json([
                'success' => true,
                'exam_subject' => [
                    'total_marks' => $examSubject->total_marks,
                    'pass_marks' => $examSubject->pass_marks
                ],
                'marks' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'মার্ক লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get marks for a group of subjects
     */
    private function getGroupMarks(Request $request)
    {
        try {
            // Extract group ID from subject_id (format: group_X)
            $groupId = str_replace('group_', '', $request->subject_id);
            
            // Get the subject group
            $subjectGroup = \App\Models\SubjectGroup::findOrFail($groupId);
            
            // Get class details
            $class = SchoolClass::findOrFail($request->class_id);
            
            // Get marks from the first subject in the group
            // (All subjects in group have same marks)
            $firstSubjectId = $subjectGroup->subject_ids[0] ?? null;
            
            if (!$firstSubjectId) {
                return response()->json([
                    'success' => true,
                    'exam_subject' => [
                        'total_marks' => $subjectGroup->total_marks,
                        'pass_marks' => $subjectGroup->pass_marks
                    ],
                    'marks' => []
                ]);
            }
            
            $results = ExamResult::where('exam_id', $request->exam_id)
                ->where('subject_id', $firstSubjectId)
                ->whereHas('student', function ($query) use ($class) {
                    $query->where('class', $class->name)
                          ->where('section', $class->section)
                          ->where('status', 'active');
                })
                ->with('student:id,name_bn,roll,registration_number,photo')
                ->get()
                ->each(function ($result) {
                    if ($result->student) {
                        $photoUrl = null;
                        if ($result->student->photo && Storage::disk('public')->exists($result->student->photo)) {
                            $photoUrl = route('tenant.files', ['path' => $result->student->photo]);
                        }
                        $result->student->photo_url = $photoUrl;
                        // Ensure roll_number is available for frontend
                        $result->student->roll_number = $result->student->roll;
                    }
                })
                ->keyBy('student_id');
            
            return response()->json([
                'success' => true,
                'exam_subject' => [
                    'total_marks' => $subjectGroup->total_marks,
                    'pass_marks' => $subjectGroup->pass_marks
                ],
                'marks' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'গ্রুপ মার্ক লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save marks for students
     */
    public function saveMarks(Request $request)
    {
        try {
            // Check if this is a group
            $subjectId = $request->subject_id;
            $isGroup = is_string($subjectId) && strpos($subjectId, 'group_') === 0;
            
            if ($isGroup) {
                // Handle group marks saving
                return $this->saveGroupMarks($request);
            }
            
            $validator = Validator::make($request->all(), [
                'exam_id' => 'required|exists:exams,id',
                'subject_id' => 'required|exists:subjects,id',
                'marks' => 'required|array',
                'marks.*.student_id' => 'required|exists:students,id',
                'marks.*.marks' => 'nullable|numeric|min:0',
                'marks.*.is_present' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'অবৈধ তথ্য',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get exam subject details
            $examSubject = ExamSubject::where('exam_id', $request->exam_id)
                ->where('subject_id', $request->subject_id)
                ->first();

            if (!$examSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই পরীক্ষায় বিষয়টি পাওয়া যায়নি'
                ], 404);
            }

            DB::beginTransaction();

            $savedCount = 0;
            $errors = [];

            foreach ($request->marks as $markData) {
                try {
                    $studentId = $markData['student_id'];
                    $isPresent = $markData['is_present'];
                    $obtainedMarks = $isPresent ? ($markData['marks'] ?? null) : null;

                    // Validate marks are within range
                    if ($isPresent && $obtainedMarks !== null) {
                        if ($obtainedMarks > $examSubject->total_marks) {
                            $errors[] = "Student ID {$studentId}: প্রাপ্ত নম্বর পূর্ণমান থেকে বেশি হতে পারে না";
                            continue;
                        }
                    }

                    // Determine status based on 33% rule
                    $status = 'absent';
                    if ($isPresent) {
                        if ($obtainedMarks !== null) {
                            $percentage = ($obtainedMarks / $examSubject->total_marks) * 100;
                            // 33% rule: less than 33% is always fail
                            $status = $percentage >= 33 ? 'pass' : 'fail';
                        } else {
                            $status = null; // Not yet entered
                        }
                    }

                    // Update or create result
                    $result = ExamResult::updateOrCreate(
                        [
                            'exam_id' => $request->exam_id,
                            'subject_id' => $request->subject_id,
                            'student_id' => $studentId
                        ],
                        [
                            'obtained_marks' => $obtainedMarks,
                            'total_marks' => $examSubject->total_marks,
                            'status' => $status,
                            'entered_by' => Auth::id(),
                            'entered_at' => now()
                        ]
                    );

                    $savedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Student ID {$studentId}: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$savedCount}টি ছাত্র/ছাত্রীর মার্ক সফলভাবে সংরক্ষণ করা হয়েছে",
                'saved_count' => $savedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'মার্ক সংরক্ষণ করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Save marks for a group of subjects
     */
    private function saveGroupMarks(Request $request)
    {
        try {
            // Extract group ID from subject_id (format: group_X)
            $groupId = str_replace('group_', '', $request->subject_id);
            
            // Get the subject group
            $subjectGroup = \App\Models\SubjectGroup::findOrFail($groupId);
            
            DB::beginTransaction();
            
            $savedCount = 0;
            $errors = [];
            
            // For each student in the marks array
            foreach ($request->marks as $markData) {
                try {
                    $studentId = $markData['student_id'];
                    $isPresent = $markData['is_present'];
                    $obtainedMarks = $isPresent ? ($markData['marks'] ?? null) : null;
                    
                    // Validate marks are within range
                    if ($isPresent && $obtainedMarks !== null) {
                        if ($obtainedMarks > $subjectGroup->total_marks) {
                            $errors[] = "Student ID {$studentId}: প্রাপ্ত নম্বর পূর্ণমান থেকে বেশি হতে পারে না";
                            continue;
                        }
                    }
                    
                    // Determine status based on 33% rule
                    $status = 'absent';
                    if ($isPresent) {
                        if ($obtainedMarks !== null) {
                            $percentage = ($obtainedMarks / $subjectGroup->total_marks) * 100;
                            $status = $percentage >= 33 ? 'pass' : 'fail';
                        } else {
                            $status = null;
                        }
                    }
                    
                    // Save marks for each subject in the group with same marks
                    foreach ($subjectGroup->subject_ids as $subjectId) {
                        ExamResult::updateOrCreate(
                            [
                                'exam_id' => $request->exam_id,
                                'subject_id' => $subjectId,
                                'student_id' => $studentId
                            ],
                            [
                                'obtained_marks' => $obtainedMarks,
                                'total_marks' => $subjectGroup->total_marks,
                                'status' => $status,
                                'entered_by' => Auth::id(),
                                'entered_at' => now()
                            ]
                        );
                    }
                    
                    $savedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Student ID {$studentId}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "{$savedCount}টি ছাত্র/ছাত্রীর গ্রুপ মার্ক সফলভাবে সংরক্ষণ করা হয়েছে",
                'saved_count' => $savedCount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'গ্রুপ মার্ক সংরক্ষণ করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get marks summary
     */
    public function getSummary(Request $request)
    {
        try {
            // Check if this is a group
            $subjectId = $request->subject_id;
            $isGroup = is_string($subjectId) && strpos($subjectId, 'group_') === 0;
            
            // If group, get first subject from the group
            if ($isGroup) {
                $groupId = str_replace('group_', '', $subjectId);
                $subjectGroup = \App\Models\SubjectGroup::findOrFail($groupId);
                $subjectId = $subjectGroup->subject_ids[0] ?? null;
                
                if (!$subjectId) {
                    return response()->json([
                        'success' => true,
                        'summary' => [
                            'total_students' => 0,
                            'present' => 0,
                            'absent' => 0,
                            'passed' => 0,
                            'failed' => 0
                        ]
                    ]);
                }
            }
            
            $validator = Validator::make($request->all(), [
                'exam_id' => 'required|exists:exams,id',
                'class_id' => 'required|exists:school_classes,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'অবৈধ তথ্য',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get class details
            $class = SchoolClass::findOrFail($request->class_id);
            
            $totalStudents = Student::where('class', $class->name)
                ->where('section', $class->section)
                ->where('status', 'active')
                ->count();

            $results = ExamResult::where('exam_id', $request->exam_id)
                ->where('subject_id', $subjectId)
                ->whereHas('student', function ($query) use ($class) {
                    $query->where('class', $class->name)
                          ->where('section', $class->section);
                })
                ->get();

            $presentCount = $results->where('status', '!=', 'absent')->count();
            $absentCount = $results->where('status', 'absent')->count();
            $passedCount = $results->where('status', 'pass')->count();
            $failedCount = $results->where('status', 'fail')->count();

            return response()->json([
                'success' => true,
                'summary' => [
                    'total_students' => $totalStudents,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'passed' => $passedCount,
                    'failed' => $failedCount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সারসংক্ষেপ লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the marks save page with real data
     */
    public function showSavePage()
    {
        try {
            // Get saved marks data with relationships
            $savedMarks = ExamResult::with(['exam', 'subject', 'student'])
                ->select('exam_id', 'subject_id', 'entered_by', 'entered_at')
                ->selectRaw('COUNT(*) as student_count')
                ->groupBy('exam_id', 'subject_id', 'entered_by', 'entered_at')
                ->orderBy('entered_at', 'desc')
                ->get()
                ->map(function ($result) {
                    return [
                        'id' => $result->exam_id . '_' . $result->subject_id,
                        'exam' => $result->exam->name ?? 'পরীক্ষার নাম নেই',
                        'subject' => $result->subject->name ?? 'বিষয়ের নাম নেই',
                        'students' => $result->student_count,
                        'save_date' => $result->entered_at ? $result->entered_at->format('d/m/Y') : 'তারিখ নেই',
                        'saved_by' => $result->enteredBy->name ?? 'অজানা ব্যবহারকারী'
                    ];
                });

            // Get statistics
            $stats = [
                'total_saved' => ExamResult::count(),
                'today_saved' => ExamResult::whereDate('entered_at', today())->count(),
                'total_backups' => 0, // This would be from a backups table if implemented
                'last_backup' => null // This would be from a backups table if implemented
            ];

            return view('tenant.marks.save', compact('savedMarks', 'stats'));
        } catch (\Exception $e) {
            return view('tenant.marks.save', [
                'savedMarks' => collect([]),
                'stats' => [
                    'total_saved' => 0,
                    'today_saved' => 0,
                    'total_backups' => 0,
                    'last_backup' => null
                ]
            ]);
        }
    }

    /**
     * Get saved marks data via API
     */
    public function getSavedMarks()
    {
        try {
            // Get saved marks data with relationships
            $savedMarks = ExamResult::with(['exam', 'subject', 'student'])
                ->select('exam_id', 'subject_id', 'entered_by', 'entered_at')
                ->selectRaw('COUNT(*) as student_count')
                ->groupBy('exam_id', 'subject_id', 'entered_by', 'entered_at')
                ->orderBy('entered_at', 'desc')
                ->get()
                ->map(function ($result) {
                    return [
                        'id' => $result->exam_id . '_' . $result->subject_id,
                        'exam' => $result->exam->name ?? 'পরীক্ষার নাম নেই',
                        'subject' => $result->subject->name ?? 'বিষয়ের নাম নেই',
                        'students' => $result->student_count,
                        'save_date' => $result->entered_at ? $result->entered_at->format('d/m/Y') : 'তারিখ নেই',
                        'saved_by' => $result->enteredBy->name ?? 'অজানা ব্যবহারকারী'
                    ];
                });

            // Get statistics
            $stats = [
                'total_saved' => ExamResult::count(),
                'today_saved' => ExamResult::whereDate('entered_at', today())->count(),
                'total_backups' => 0,
                'last_backup' => null
            ];

            return response()->json([
                'success' => true,
                'savedMarks' => $savedMarks,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সংরক্ষিত মার্ক লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create backup of marks class-wise and save to Google Drive
     */
    public function createBackup()
    {
        try {
            // Increase limits for PDF generation
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);

            $classes = SchoolClass::all();
            $schoolName = \App\Models\SchoolSetting::getSettings()->school_name_bn ?? 'School Name';
            
            $backupFolder = 'Marks_Backup_' . date('Y-m-d_H-i');
            $generatedFiles = [];
            $hasGoogleDrive = true; // Assume true, catch exception if not configured

            foreach ($classes as $class) {
                // Get students of this class
                $students = Student::where('class', $class->name)
                                  ->where('section', $class->section)
                                  ->where('status', 'active')
                                  ->orderBy('roll_no') // Assuming roll_no exists
                                  ->get();
                                  
                if ($students->isEmpty()) continue;
                
                // Get exams that have results for these students
                $studentIds = $students->pluck('id');
                $examIds = ExamResult::whereIn('student_id', $studentIds)
                                    ->select('exam_id')
                                    ->distinct()
                                    ->pluck('exam_id');
                                    
                if ($examIds->isEmpty()) continue;
                
                $exams = Exam::whereIn('id', $examIds)->get();
                $examsData = [];

                foreach ($exams as $exam) {
                    // Get subjects for this class (or from results)
                    // Better to get subjects that have results for this exam and these students
                    $subjectIds = ExamResult::where('exam_id', $exam->id)
                                          ->whereIn('student_id', $studentIds)
                                          ->select('subject_id')
                                          ->distinct()
                                          ->pluck('subject_id');
                                          
                    if ($subjectIds->isEmpty()) continue;
                    
                    $subjects = Subject::whereIn('id', $subjectIds)->get();
                    
                    // Attach marks to students for this exam
                    // Clone students to avoid reference issues between exams
                    $examStudents = $students->map(function ($student) {
                        return clone $student;
                    });

                    foreach ($examStudents as $student) {
                         $student->marks = ExamResult::where('exam_id', $exam->id)
                                                    ->where('student_id', $student->id)
                                                    ->get();
                         $student->total_marks = $student->marks->sum('obtained_marks');
                         // Simple pass/fail logic based on existing code (33%)
                         // Note: This is simplified. Real logic might check each subject.
                         $isFail = $student->marks->contains('status', 'fail');
                         $student->status = $isFail ? 'Fail' : 'Pass';
                    }
                    
                    $exam->subjects = $subjects;
                    $exam->students = $examStudents;
                    $examsData[] = $exam;
                }

                if (empty($examsData)) continue;

                $pdf = Pdf::loadView('tenant.marks.pdf.class-wise', [
                    'schoolName' => $schoolName,
                    'className' => $class->name . ' (' . $class->section . ')',
                    'exams' => $examsData
                ]);
                
                $fileName = "Marks_{$class->name}_{$class->section}.pdf";
                
                // Save to Google Drive
                try {
                     Storage::disk('google')->put($backupFolder . '/' . $fileName, $pdf->output());
                     $generatedFiles[] = $fileName;
                } catch (\Exception $e) {
                    Log::error("Failed to upload to Drive: " . $e->getMessage());
                    // Fallback to local storage if Drive fails? 
                    // For now, we want to know if Drive fails.
                    $hasGoogleDrive = false;
                    Storage::disk('public')->put('backups/' . $backupFolder . '/' . $fileName, $pdf->output());
                    $generatedFiles[] = $fileName . " (Local)";
                }
            }
            
            if (empty($generatedFiles)) {
                 return response()->json(['success' => false, 'message' => 'ব্যাকআপ তৈরি করার মতো কোনো ডাটা পাওয়া যায়নি।']);
            }

            $message = $hasGoogleDrive 
                ? 'Google Drive এ ব্যাকআপ সফলভাবে তৈরি করা হয়েছে!' 
                : 'Google Drive কনফিগার করা নেই, লোকালে ব্যাকআপ সেভ করা হয়েছে।';

            return response()->json([
                'success' => true, 
                'message' => $message,
                'folder' => $backupFolder,
                'files' => $generatedFiles
            ]);

        } catch (\Exception $e) {
            Log::error('Backup Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'ব্যাকআপ তৈরিতে সমস্যা: ' . $e->getMessage()], 500);
        }
    }
}

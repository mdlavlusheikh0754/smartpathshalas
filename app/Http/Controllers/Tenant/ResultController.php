<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\SubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class ResultController extends Controller
{
    /**
     * Display results page
     */
    public function index()
    {
        return view('tenant.results.index');
    }

    /**
     * Show comprehensive results table
     */
    public function comprehensiveTable()
    {
        return view('tenant.results.comprehensive-table');
    }

    /**
     * Get all exams for results
     */
    public function getExams()
    {
        try {
            $exams = Exam::select('id', 'name', 'exam_type', 'month', 'start_date', 'end_date', 'status', 'is_published')
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
     * Get all classes for results
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
     * Get exam results for a specific exam and class
     */
    public function getResults(Request $request)
    {
        try {
            $examId = $request->get('exam_id');
            $classId = $request->get('class_id');

            if (!$examId || !$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'পরীক্ষা এবং ক্লাস নির্বাচন করুন'
                ], 400);
            }

            // Get exam details
            $exam = Exam::findOrFail($examId);
            
            // Get class details
            $class = SchoolClass::findOrFail($classId);
            
            // Get subject groups for this exam and class
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
            
            $examSubjects = $examSubjectsQuery->get();

            // Get students for this class
            $students = Student::where('class', $class->name)
                ->where('section', $class->section)
                ->where('status', 'active')
                ->select('id', 'name_bn', 'name_en', 'roll', 'registration_number', 'photo')
                ->orderBy('roll')
                ->get()
                ->map(function ($student) {
                    if ($student->photo) {
                        $student->photo_url = url('/storage/' . $student->photo);
                    } else {
                        $student->photo_url = null;
                    }
                    // Map roll to roll_number for frontend compatibility
                    $student->roll_number = $student->roll;
                    // Use the name accessor which will return name_bn ?? name_en ?? 'Unknown'
                    $student->name = $student->name;
                    return $student;
                });

            // Get results for all students and subjects
            $results = [];
            foreach ($students as $student) {
                $studentResults = [];
                $totalMarks = 0;
                $totalPossible = 0;
                $subjectsWithMarks = 0;
                $failedSubjects = 0;
                $absentSubjects = 0;

                // Process individual subjects
                foreach ($examSubjects as $examSubject) {
                    $result = ExamResult::where('exam_id', $examId)
                        ->where('subject_id', $examSubject->subject_id)
                        ->where('student_id', $student->id)
                        ->first();

                    if ($result) {
                        // IMPORTANT: obtained_marks ONLY contains exam marks, NOT monthly marks
                        $examMarksOnly = $result->obtained_marks;
                        
                        $percentage = $examMarksOnly > 0 ? ($examMarksOnly / $examSubject->total_marks) * 100 : 0;
                        $subjectStatus = $result->status;
                        
                        // Override status based on 33% rule (using exam marks only)
                        if ($result->status !== 'absent' && $examMarksOnly !== null && $examMarksOnly > 0) {
                            $subjectStatus = $percentage >= 33 ? 'pass' : 'fail';
                        }
                        
                        $monthlyExamDetails = null;
                        if ($result->monthly_exam_details) {
                            $monthlyExamDetails = is_string($result->monthly_exam_details) 
                                ? json_decode($result->monthly_exam_details, true) 
                                : $result->monthly_exam_details;
                        }
                        
                        // Store exam marks separately from monthly marks
                        $studentResults[] = [
                            'subject_id' => $examSubject->subject_id,
                            'subject_name' => $examSubject->subject->name,
                            'subject_code' => $examSubject->subject->code,
                            'total_marks' => $examSubject->total_marks,
                            'pass_marks' => $examSubject->pass_marks,
                            'obtained_marks' => $examMarksOnly,  // ONLY exam marks, NOT combined with monthly
                            'monthly_average' => $result->monthly_average ?? 0,  // Monthly marks stored separately
                            'status' => $subjectStatus,
                            'grade' => $this->calculateGrade($examMarksOnly, $examSubject->total_marks),
                            'is_present' => $result->status !== 'absent',
                            'is_group' => false,
                            'percentage' => round($percentage, 2),
                            'monthly_exam_details' => $monthlyExamDetails
                        ];

                        if ($result->status === 'absent') {
                            $absentSubjects++;
                        } elseif ($examMarksOnly !== null && $examMarksOnly > 0) {
                            // Add ONLY exam marks to total (not monthly)
                            $totalMarks += $examMarksOnly;
                            $subjectsWithMarks++;
                            
                            // Check if failed based on 33% rule
                            if ($percentage < 33) {
                                $failedSubjects++;
                            }
                        }
                        
                        $totalPossible += $examSubject->total_marks;
                    } else {
                        // No result found - mark as not attempted
                        $studentResults[] = [
                            'subject_id' => $examSubject->subject_id,
                            'subject_name' => $examSubject->subject->name,
                            'subject_code' => $examSubject->subject->code,
                            'total_marks' => $examSubject->total_marks,
                            'pass_marks' => $examSubject->pass_marks,
                            'obtained_marks' => null,
                            'status' => null,
                            'grade' => null,
                            'is_present' => true,
                            'is_group' => false
                        ];
                        
                        $totalPossible += $examSubject->total_marks;
                    }
                }
                
                // Process subject groups
                foreach ($subjectGroups as $group) {
                    // Get result from first subject in the group (all subjects have same marks)
                    $firstSubjectId = $group->subject_ids[0] ?? null;
                    
                    if ($firstSubjectId) {
                        $result = ExamResult::where('exam_id', $examId)
                            ->where('subject_id', $firstSubjectId)
                            ->where('student_id', $student->id)
                            ->first();
                        
                        // Get subject names for the group
                        $subjectNames = Subject::whereIn('id', $group->subject_ids)
                            ->pluck('name')
                            ->toArray();
                        
                        if ($result) {
                            // IMPORTANT: obtained_marks ONLY contains exam marks for groups too
                            $examMarksOnly = $result->obtained_marks;
                            $percentage = ($examMarksOnly / $group->total_marks) * 100;
                            $subjectStatus = $result->status;
                            
                            // Override status based on 33% rule (using exam marks only)
                            if ($result->status !== 'absent' && $examMarksOnly !== null) {
                                $subjectStatus = $percentage >= 33 ? 'pass' : 'fail';
                            }
                            
                            $studentResults[] = [
                                'subject_id' => 'group_' . $group->id,
                                'subject_name' => $group->name,
                                'subject_code' => 'GRP' . $group->id,
                                'subject_names' => $subjectNames,
                                'total_marks' => $group->total_marks,
                                'pass_marks' => $group->pass_marks,
                                'obtained_marks' => $examMarksOnly,  // ONLY exam marks, NOT combined with monthly
                                'monthly_average' => $result->monthly_average ?? 0,  // Monthly marks stored separately
                                'status' => $subjectStatus,
                                'grade' => $this->calculateGrade($examMarksOnly, $group->total_marks),
                                'is_present' => $result->status !== 'absent',
                                'is_group' => true,
                                'group_id' => $group->id
                            ];
                            
                            if ($result->status === 'absent') {
                                $absentSubjects++;
                            } elseif ($examMarksOnly !== null) {
                                // Add ONLY exam marks to total (not monthly)
                                $totalMarks += $examMarksOnly;
                                $subjectsWithMarks++;
                                
                                // Check if failed based on 33% rule
                                if ($percentage < 33) {
                                    $failedSubjects++;
                                }
                            }
                            
                            $totalPossible += $group->total_marks;
                        } else {
                            // No result found
                            $studentResults[] = [
                                'subject_id' => 'group_' . $group->id,
                                'subject_name' => $group->name,
                                'subject_code' => 'GRP' . $group->id,
                                'subject_names' => $subjectNames,
                                'total_marks' => $group->total_marks,
                                'pass_marks' => $group->pass_marks,
                                'obtained_marks' => null,
                                'status' => null,
                                'grade' => null,
                                'is_present' => true,
                                'is_group' => true,
                                'group_id' => $group->id
                            ];
                            
                            $totalPossible += $group->total_marks;
                        }
                    }
                }

                // Calculate overall grade and result
                $overallGrade = null;
                $overallResult = null;
                $percentage = 0;

                if ($subjectsWithMarks > 0 && $totalPossible > 0) {
                    $percentage = ($totalMarks / $totalPossible) * 100;
                    
                    // If any subject is failed or absent, overall grade and result should be "ফেল"
                    if ($failedSubjects > 0 || $absentSubjects > 0) {
                        $overallGrade = 'ফেল';
                        $overallResult = 'ফেল';
                    } else if ($percentage >= 33) {
                        // Calculate grade only if no subjects failed and overall >= 33%
                        $overallGrade = $this->calculateGrade($totalMarks, $totalPossible);
                        $overallResult = 'পাস';
                    } else {
                        // Overall percentage < 33%
                        $overallGrade = 'ফেল';
                        $overallResult = 'ফেল';
                    }
                }

                // Get monthly marks and details from the first result (all subjects should have same monthly_average)
                $monthlyMarks = 0;
                $monthlyExamDetails = null;
                $anyResult = ExamResult::where('exam_id', $examId)
                    ->where('student_id', $student->id)
                    ->whereNotNull('monthly_average')
                    ->where('monthly_average', '>', 0)
                    ->first();
                
                if ($anyResult) {
                    $monthlyMarks = $anyResult->monthly_average;
                    if ($anyResult->monthly_exam_details) {
                        $monthlyExamDetails = is_string($anyResult->monthly_exam_details) 
                            ? json_decode($anyResult->monthly_exam_details, true) 
                            : $anyResult->monthly_exam_details;
                    }
                }

                $results[] = [
                    'student' => $student,
                    'subjects' => $studentResults,
                    'total_marks' => $totalMarks,
                    'total_possible' => $totalPossible,
                    'percentage' => round($percentage, 2),
                    'overall_grade' => $overallGrade,
                    'overall_result' => $overallResult,
                    'failed_subjects' => $failedSubjects,
                    'absent_subjects' => $absentSubjects,
                    'monthly_average' => $monthlyMarks,
                    'monthly_exam_details' => $monthlyExamDetails
                ];
            }

            return response()->json([
                'success' => true,
                'api_version' => '2.0', // Force cache refresh
                'timestamp' => now()->toIso8601String(),
                'exam' => [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'exam_type' => $exam->exam_type
                ],
                'class' => [
                    'id' => $class->id,
                    'name' => $class->name,
                    'section' => $class->section,
                    'full_name' => $class->name . ' - ' . $class->section
                ],
                'subjects' => $examSubjects->map(function ($examSubject) {
                    return [
                        'id' => $examSubject->subject_id,
                        'name' => $examSubject->subject->name,
                        'code' => $examSubject->subject->code,
                        'total_marks' => $examSubject->total_marks,
                        'pass_marks' => $examSubject->pass_marks,
                        'is_group' => false
                    ];
                })->concat(
                    $subjectGroups->map(function ($group) {
                        // Get subject names for the group
                        $subjectNames = Subject::whereIn('id', $group->subject_ids)
                            ->pluck('name')
                            ->toArray();
                        
                        return [
                            'id' => 'group_' . $group->id,
                            'name' => $group->name,
                            'code' => 'GRP' . $group->id,
                            'subject_names' => $subjectNames,
                            'total_marks' => $group->total_marks,
                            'pass_marks' => $group->pass_marks,
                            'is_group' => true,
                            'group_id' => $group->id
                        ];
                    })
                ),
                'results' => $results
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
              ->header('Pragma', 'no-cache')
              ->header('Expires', '0');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ফলাফল লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate grade based on marks using 33% rule
     */
    private function calculateGrade($obtainedMarks, $totalMarks)
    {
        if ($obtainedMarks === null || $totalMarks === null || $totalMarks == 0) {
            return null;
        }

        $percentage = ($obtainedMarks / $totalMarks) * 100;

        // 33% rule: less than 33% shows "ফেল" in grade
        if ($percentage < 33) return 'ফেল';
        elseif ($percentage >= 80) return 'A+';
        elseif ($percentage >= 70) return 'A';
        elseif ($percentage >= 60) return 'A-';
        elseif ($percentage >= 50) return 'B';
        elseif ($percentage >= 40) return 'C';
        elseif ($percentage >= 33) return 'D';
        else return 'ফেল';
    }
    
    /**
     * Export results to various formats
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $examId = $request->query('exam_id');
        $classId = $request->query('class_id');
        
        if (!$examId || !$classId) {
            return redirect()->back()->with('error', 'পরীক্ষা এবং ক্লাস নির্বাচন করুন');
        }
        
        try {
            // Get the same data as getResults method
            $exam = Exam::findOrFail($examId);
            $class = SchoolClass::findOrFail($classId);
            
            // Get subject groups for this exam and class
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
            
            $examSubjects = $examSubjectsQuery->get();

            // Get students for this class
            $students = Student::where('class', $class->name)
                ->where('section', $class->section)
                ->where('status', 'active')
                ->select('id', 'name_bn', 'name_en', 'roll', 'registration_number', 'photo')
                ->orderBy('roll')
                ->get()
                ->map(function ($student) {
                    // Map roll to roll_number for frontend compatibility
                    $student->roll_number = $student->roll;
                    // Use the name accessor which will return name_bn ?? name_en ?? 'Unknown'
                    $student->name = $student->name;
                    return $student;
                });

            // Get results for all students and subjects (simplified version for export)
            $results = [];
            foreach ($students as $student) {
                $studentResults = [];
                $totalMarks = 0;
                $totalPossible = 0;
                $subjectsWithMarks = 0;
                $failedSubjects = 0;
                $absentSubjects = 0;

                // Process individual subjects
                foreach ($examSubjects as $examSubject) {
                    $result = ExamResult::where('exam_id', $examId)
                        ->where('subject_id', $examSubject->subject_id)
                        ->where('student_id', $student->id)
                        ->first();

                    if ($result) {
                        $percentage = ($result->obtained_marks / $examSubject->total_marks) * 100;
                        $subjectStatus = $result->status;
                        
                        if ($result->status !== 'absent' && $result->obtained_marks !== null) {
                            $subjectStatus = $percentage >= 33 ? 'pass' : 'fail';
                        }
                        
                        $studentResults[] = [
                            'subject_name' => $examSubject->subject->name,
                            'total_marks' => $examSubject->total_marks,
                            'obtained_marks' => $result->obtained_marks,
                            'status' => $subjectStatus,
                            'grade' => $this->calculateGrade($result->obtained_marks, $examSubject->total_marks),
                            'is_present' => $result->status !== 'absent',
                            'is_group' => false
                        ];

                        if ($result->status === 'absent') {
                            $absentSubjects++;
                        } elseif ($result->obtained_marks !== null) {
                            $totalMarks += $result->obtained_marks;
                            $subjectsWithMarks++;
                            
                            if ($percentage < 33) {
                                $failedSubjects++;
                            }
                        }
                        
                        $totalPossible += $examSubject->total_marks;
                    }
                }
                
                // Process subject groups
                foreach ($subjectGroups as $group) {
                    $firstSubjectId = $group->subject_ids[0] ?? null;
                    
                    if ($firstSubjectId) {
                        $result = ExamResult::where('exam_id', $examId)
                            ->where('subject_id', $firstSubjectId)
                            ->where('student_id', $student->id)
                            ->first();
                        
                        $subjectNames = Subject::whereIn('id', $group->subject_ids)
                            ->pluck('name')
                            ->toArray();
                        
                        if ($result) {
                            $percentage = ($result->obtained_marks / $group->total_marks) * 100;
                            $subjectStatus = $result->status;
                            
                            if ($result->status !== 'absent' && $result->obtained_marks !== null) {
                                $subjectStatus = $percentage >= 33 ? 'pass' : 'fail';
                            }
                            
                            $studentResults[] = [
                                'subject_name' => $group->name,
                                'subject_names' => $subjectNames,
                                'total_marks' => $group->total_marks,
                                'obtained_marks' => $result->obtained_marks,
                                'status' => $subjectStatus,
                                'grade' => $this->calculateGrade($result->obtained_marks, $group->total_marks),
                                'is_present' => $result->status !== 'absent',
                                'is_group' => true
                            ];
                            
                            if ($result->status === 'absent') {
                                $absentSubjects++;
                            } elseif ($result->obtained_marks !== null) {
                                $totalMarks += $result->obtained_marks;
                                $subjectsWithMarks++;
                                
                                if ($percentage < 33) {
                                    $failedSubjects++;
                                }
                            }
                            
                            $totalPossible += $group->total_marks;
                        }
                    }
                }

                // Calculate overall grade and result
                $overallGrade = null;
                $overallResult = null;
                $percentage = 0;

                if ($subjectsWithMarks > 0 && $totalPossible > 0) {
                    $percentage = ($totalMarks / $totalPossible) * 100;
                    
                    if ($failedSubjects > 0 || $absentSubjects > 0) {
                        $overallGrade = 'ফেল';
                        $overallResult = 'ফেল';
                    } else if ($percentage >= 33) {
                        $overallGrade = $this->calculateGrade($totalMarks, $totalPossible);
                        $overallResult = 'পাস';
                    } else {
                        $overallGrade = 'ফেল';
                        $overallResult = 'ফেল';
                    }
                }

                // Get monthly marks from the first result (all subjects should have same monthly_average)
                $monthlyMarks = 0;
                $anyResult = ExamResult::where('exam_id', $examId)
                    ->where('student_id', $student->id)
                    ->whereNotNull('monthly_average')
                    ->where('monthly_average', '>', 0)
                    ->first();
                
                if ($anyResult) {
                    $monthlyMarks = $anyResult->monthly_average;
                }

                $results[] = [
                    'student' => $student,
                    'subjects' => $studentResults,
                    'total_marks' => $totalMarks,
                    'total_possible' => $totalPossible,
                    'percentage' => round($percentage, 2),
                    'overall_grade' => $overallGrade,
                    'overall_result' => $overallResult,
                    'monthly_average' => $monthlyMarks
                ];
            }

            // Prepare subjects list for export
            $allSubjects = $examSubjects->map(function ($examSubject) {
                return [
                    'name' => $examSubject->subject->name,
                    'total_marks' => $examSubject->total_marks,
                    'is_group' => false
                ];
            })->concat(
                $subjectGroups->map(function ($group) {
                    $subjectNames = Subject::whereIn('id', $group->subject_ids)
                        ->pluck('name')
                        ->toArray();
                    
                    return [
                        'name' => $group->name,
                        'subject_names' => $subjectNames,
                        'total_marks' => $group->total_marks,
                        'is_group' => true
                    ];
                })
            );

            if ($format === 'pdf') {
                return $this->exportPdf($exam, $class, $results, $allSubjects);
            } elseif ($format === 'excel') {
                return $this->exportExcel($exam, $class, $results, $allSubjects);
            } elseif ($format === 'docx') {
                return $this->exportDocx($exam, $class, $results, $allSubjects);
            }

        } catch (\Exception $e) {
            \Log::error('Results Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'এক্সপোর্ট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Export results as PDF
     */
    private function exportPdf($exam, $class, $results, $subjects)
    {
        try {
            // Configure mPDF with proper Bengali font support
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'margin_left' => 8,
                'margin_right' => 8,
                'margin_top' => 8,
                'margin_bottom' => 8,
                'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
                'fontdata' => $fontData + [
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
                'default_font' => 'dejavusans',
                'tempDir' => storage_path('app/temp')
            ]);
            
            $schoolSettings = \App\Models\SchoolSetting::getSettings();
            $tenantData = tenant('data');
            
            $logoPath = null;
            if (!empty($schoolSettings->logo)) {
                $path = storage_path('app/public/' . $schoolSettings->logo);
                if (file_exists($path)) {
                    $logoPath = $path;
                } else {
                    $path = public_path('storage/' . $schoolSettings->logo);
                    if (file_exists($path)) {
                        $logoPath = $path;
                    }
                }
            } elseif (!empty($tenantData['logo'])) {
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
            
            $html = view('tenant.results.export_pdf', compact('exam', 'class', 'results', 'subjects', 'schoolInfo'))->render();
            
            // Write HTML with proper Bengali font support
            $mpdf->WriteHTML($html);
            
            $filename = 'results_' . $exam->name . '_' . $class->name . '_' . $class->section . '.pdf';
            
            return response($mpdf->Output($filename, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export results as Excel (CSV)
     */
    private function exportExcel($exam, $class, $results, $subjects)
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=results_" . $exam->name . "_" . $class->name . "_" . $class->section . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($exam, $class, $results, $subjects) {
            $file = fopen('php://output', 'w');
            // BOM for Bengali support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            $headerRow = ['রোল', 'নাম', 'রেজিস্ট্রেশন'];
            foreach ($subjects as $subject) {
                if ($subject['is_group']) {
                    $headerRow[] = $subject['name'] . ' (গ্রুপ)';
                } else {
                    $headerRow[] = $subject['name'];
                }
            }
            $headerRow = array_merge($headerRow, ['মোট নম্বর', 'শতাংশ', 'গ্রেড', 'ফলাফল']);
            fputcsv($file, $headerRow);

            // Data rows
            foreach ($results as $result) {
                $row = [
                    $result['student']->roll,
                    $result['student']->name,
                    $result['student']->registration_number ?? 'N/A'
                ];
                
                // Add subject marks
                foreach ($result['subjects'] as $subjectResult) {
                    if (!$subjectResult['is_present']) {
                        $row[] = 'অনুপস্থিত';
                    } elseif ($subjectResult['obtained_marks'] !== null) {
                        $row[] = $subjectResult['obtained_marks'];
                    } else {
                        $row[] = '-';
                    }
                }
                
                $row = array_merge($row, [
                    $result['total_marks'] > 0 ? $result['total_marks'] : '-',
                    $result['percentage'] > 0 ? $result['percentage'] . '%' : '-',
                    $result['overall_grade'] ?? '-',
                    $result['overall_result'] ?? '-'
                ]);
                
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export results as DOCX (HTML)
     */
    private function exportDocx($exam, $class, $results, $subjects)
    {
        $schoolSettings = \App\Models\SchoolSetting::getSettings();
        $tenantData = tenant('data');
        
        $schoolInfo = [
            'name' => $schoolSettings->school_name_bn ?? $schoolSettings->school_name_en ?? ($tenantData['school_name'] ?? 'Smart Pathshala'),
            'address' => $schoolSettings->address ?? ($tenantData['address'] ?? ''),
        ];
        
        $content = view('tenant.results.export_docx', compact('exam', 'class', 'results', 'subjects', 'schoolInfo'))->render();
        
        $filename = 'results_' . $exam->name . '_' . $class->name . '_' . $class->section . '.doc';
        
        return response($content)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get monthly exams marks summary
     */
    public function getMonthlyExamsSummary(Request $request)
    {
        try {
            $classId = $request->get('class_id');
            $monthlyExamIds = $request->get('monthly_exam_ids', []);
            
            // If monthly_exam_ids is a string (comma-separated), convert to array
            if (is_string($monthlyExamIds) && !empty($monthlyExamIds)) {
                $monthlyExamIds = explode(',', $monthlyExamIds);
                $monthlyExamIds = array_map('trim', $monthlyExamIds);
            }
            
            if (empty($monthlyExamIds)) {
                return response()->json([
                    'success' => true,
                    'summary' => []
                ]);
            }
            
            $monthlyExams = Exam::whereIn('id', $monthlyExamIds)
                               ->where('exam_type', 'monthly')
                               ->get();
            
            $summary = [];
            
            foreach ($monthlyExams as $exam) {
                // Count the number of subjects in this exam
                $examSubjectsCount = ExamSubject::where('exam_id', $exam->id)->count();
                
                // Get average marks per student for this exam
                $avgMarksPerStudent = ExamResult::where('exam_id', $exam->id)
                                              ->whereNotNull('obtained_marks')
                                              ->where('status', '!=', 'absent')
                                              ->avg('obtained_marks');
                
                // Get total possible marks for this exam (sum of all subjects)
                $maxMarks = ExamSubject::where('exam_id', $exam->id)->sum('total_marks');
                
                // Get count of students who took this exam
                $studentsCount = ExamResult::where('exam_id', $exam->id)
                                         ->whereNotNull('obtained_marks')
                                         ->where('status', '!=', 'absent')
                                         ->distinct('student_id')
                                         ->count();
                
                \Log::info('Monthly Exam Summary', [
                    'exam_id' => $exam->id,
                    'exam_name' => $exam->name,
                    'subjects_count' => $examSubjectsCount,
                    'avg_marks_per_student' => $avgMarksPerStudent,
                    'max_marks' => $maxMarks,
                    'students_count' => $studentsCount
                ]);
                
                $summary[] = [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'month' => $exam->month,
                    'exam_subjects_count' => $examSubjectsCount,
                    'total_marks' => $maxMarks,
                    'avg_marks_per_student' => round($avgMarksPerStudent ?: 0, 2),
                    'students_count' => $studentsCount,
                    'marks_per_subject' => $examSubjectsCount > 0 ? round($maxMarks / $examSubjectsCount, 2) : 0
                ];
            }
            
            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getMonthlyExamsSummary', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'মাসিক পরীক্ষার সারসংক্ষেপ লোড করতে সমস্যা হয়েছে'
            ], 500);
        }
    }
    
    /**
     * Calculate results with monthly marks
     */
    public function calculateWithMonthly(Request $request)
    {
        try {
            $examId = $request->exam_id;
            $classId = $request->class_id;
            $selectedMonths = $request->monthly_exam_ids ?? $request->months;
            
            \Log::info('Calculate with monthly request:', [
                'exam_id' => $examId,
                'class_id' => $classId,
                'monthly_exam_ids' => $selectedMonths,
                'all_request_data' => $request->all()
            ]);
            
            if (!$examId || !$classId || empty($selectedMonths)) {
                \Log::warning('Missing required fields:', [
                    'exam_id' => $examId,
                    'class_id' => $classId,
                    'monthly_exam_ids' => $selectedMonths
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'সব তথ্য প্রদান করুন'
                ], 400);
            }
            
            // Get class details
            $class = SchoolClass::findOrFail($classId);
            
            // Get the term exam
            $termExam = Exam::find($examId);
            
            if (!$termExam) {
                return response()->json([
                    'success' => false,
                    'message' => 'নির্বাচিত সামায়িক পরীক্ষা পাওয়া যায়নি'
                ], 404);
            }
            
            // Get monthly exams for selected months
            $monthlyExams = Exam::where('exam_type', 'monthly')
                               ->whereIn('id', $selectedMonths)
                               ->get();
            
            if ($monthlyExams->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'নির্বাচিত মাসের কোন মাসিক পরীক্ষা পাওয়া যায়নি'
                ], 404);
            }
            
            // Get all students in this class
            $students = Student::where('class', $class->name)
                              ->where('section', $class->section)
                              ->where('status', 'active')
                              ->get();
            
            // Get all subjects for this class
            $subjects = Subject::where('class_id', $classId)->get();
            
            DB::beginTransaction();
            
            $studentsUpdated = 0;
            $totalUpdates = 0;
            
            foreach ($students as $student) {
                $studentHasUpdate = false;
                
                // Calculate monthly average for this student across all selected monthly exams
                $totalStudentMonthlyMarks = 0;
                $totalMonthlyExamSubjects = 0;
                $monthlyDetailsArray = [];
                
                foreach ($monthlyExams as $monthlyExam) {
                    $monthlyExamMarks = [];
                    
                    foreach ($subjects as $subject) {
                        $monthlyResult = ExamResult::where('exam_id', $monthlyExam->id)
                                                  ->where('subject_id', $subject->id)
                                                  ->where('student_id', $student->id)
                                                  ->first();
                        
                        if ($monthlyResult && $monthlyResult->obtained_marks !== null && $monthlyResult->status !== 'absent') {
                            $monthlyExamMarks[] = [
                                'subject_id' => $subject->id,
                                'subject_name' => $subject->name,
                                'marks' => $monthlyResult->obtained_marks
                            ];
                            $totalStudentMonthlyMarks += $monthlyResult->obtained_marks;
                        }
                    }
                    
                    if (!empty($monthlyExamMarks)) {
                        $monthlyDetailsArray[$monthlyExam->id] = [
                            'exam_name' => $monthlyExam->name,
                            'marks' => $monthlyExamMarks
                        ];
                        $totalMonthlyExamSubjects += count($monthlyExamMarks);
                    }
                }
                
                // Convert details array to simpler format
                $simplifiedDetails = [];
                foreach ($monthlyDetailsArray as $examId => $details) {
                    $simplifiedDetails[] = [
                        'exam_name' => $details['exam_name'],
                        'total_marks' => array_sum(array_column($details['marks'], 'marks')),
                        'subjects' => count($details['marks'])
                    ];
                }
                
                // If no monthly marks found for this student, skip
                if ($totalStudentMonthlyMarks === 0) {
                    continue;
                }
                
                // Calculate average: Total Student Monthly Marks / Total Monthly Exam Subjects (groups)
                $monthlyAverage = round($totalStudentMonthlyMarks / $totalMonthlyExamSubjects, 2);
                
                // Update ALL subjects of this student with the SAME monthly average
                $isFirstSubject = true;
                foreach ($subjects as $subject) {
                    $termExamSubject = ExamSubject::where('exam_id', $termExam->id)
                                                 ->where('subject_id', $subject->id)
                                                 ->first();
                    
                    if (!$termExamSubject) {
                        continue;
                    }
                    
                    $termResult = ExamResult::where('exam_id', $termExam->id)
                                          ->where('subject_id', $subject->id)
                                          ->where('student_id', $student->id)
                                          ->first();
                    
                    if (!$termResult) {
                        continue;
                    }
                    
                    // Get original term exam marks
                    if (!$termResult->original_marks) {
                        $originalTermMarks = $termResult->obtained_marks;
                    } else {
                        $originalTermMarks = $termResult->original_marks;
                    }
                    
                    // Store monthly average ONLY in the first subject, NULL for others
                    $monthlyAvgToStore = $isFirstSubject ? $monthlyAverage : null;
                    
                    // Update: Store the monthly average (or NULL for other subjects)
                    $termResult->update([
                        'obtained_marks' => $originalTermMarks,  // Only term marks (NOT combined)
                        'original_marks' => $originalTermMarks,  // Backup of original term marks
                        'monthly_average' => $monthlyAvgToStore,   // Monthly average only in first subject
                        'monthly_exam_details' => $isFirstSubject && !empty($simplifiedDetails) ? json_encode($simplifiedDetails) : null,
                        'status' => $termResult->status          // Keep original status
                    ]);
                    
                    $totalUpdates++;
                    $studentHasUpdate = true;
                    $isFirstSubject = false;  // Set to false after first subject
                }
                
                if ($studentHasUpdate) {
                    $studentsUpdated++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'মাসিক নম্বর সফলভাবে যোগ করা হয়েছে',
                'students_updated' => $studentsUpdated,
                'total_updates' => $totalUpdates,
                'monthly_exams_count' => $monthlyExams->count()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error calculating with monthly marks:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'মাসিক নম্বর যোগ করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle exam publish status
     */
    public function togglePublish(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id'
        ]);

        try {
            $exam = Exam::findOrFail($request->exam_id);
            $exam->is_published = !$exam->is_published;
            $exam->save();

            $status = $exam->is_published ? 'প্রকাশিত' : 'অপ্রকাশিত';

            return response()->json([
                'success' => true,
                'message' => "ফলাফল সফলভাবে {$status} করা হয়েছে।",
                'is_published' => $exam->is_published
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'পাবলিশ স্ট্যাটাস পরিবর্তন করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
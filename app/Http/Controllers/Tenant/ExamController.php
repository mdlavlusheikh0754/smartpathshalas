<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        try {
            $exams = Exam::with(['subjects'])->latest()->get();
            $subjects = Subject::active()->get();
            $classes = SchoolClass::active()->get();
        } catch (\Exception $e) {
            // Handle case where tables don't exist yet
            $exams = collect();
            $subjects = collect();
            $classes = collect();
        }
        
        return view('tenant.exams.index', compact('exams', 'subjects', 'classes'));
    }

    public function manage()
    {
        try {
            $exams = Exam::with(['subjects'])->latest()->get();
            $subjects = Subject::active()->get();
            $classes = SchoolClass::active()->get();
            
            $stats = [
                'total_exams' => $exams->count(),
                'upcoming_exams' => $exams->where('status', 'upcoming')->count(),
                'ongoing_exams' => $exams->where('status', 'ongoing')->count(),
                'completed_exams' => $exams->where('status', 'completed')->count(),
                'published_exams' => $exams->where('is_published', true)->count(),
                'total_subjects' => $subjects->count(),
            ];
            
            return view('tenant.exams.manage', compact('exams', 'subjects', 'classes', 'stats'));
            
        } catch (\Exception $e) {
            // Handle case where tables don't exist yet
            $exams = collect([]);
            $subjects = collect([]);
            $classes = collect([]);
            $stats = [
                'total_exams' => 0,
                'upcoming_exams' => 0,
                'ongoing_exams' => 0,
                'completed_exams' => 0,
                'published_exams' => 0,
                'total_subjects' => 0,
            ];
            
            return view('tenant.exams.manage', compact('exams', 'subjects', 'classes', 'stats'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exam_type' => 'nullable|string',
            'description' => 'nullable|string',
            'total_marks' => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1'
        ]);

        $exam = Exam::create([
            'name' => $request->name,
            'exam_type' => $request->exam_type,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_marks' => $request->total_marks ?? 100,
            'pass_marks' => $request->pass_marks ?? 33,
            'status' => 'upcoming'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'পরীক্ষা সফলভাবে যোগ করা হয়েছে',
                'exam' => $exam->load('subjects')
            ]);
        }

        return redirect()->route('tenant.exams.index')->with('success', 'পরীক্ষা সফলভাবে যোগ করা হয়েছে');
    }

    public function show($id)
    {
        $exam = Exam::with(['subjects', 'results.student', 'results.subject'])->findOrFail($id);
        return view('tenant.exams.show', compact('exam'));
    }

    public function getData($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            return response()->json([
                'success' => true,
                'exam' => $exam
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'পরীক্ষা পাওয়া যায়নি'
            ], 404);
        }
    }

    public function getApiList()
    {
        try {
            $exams = Exam::select('id', 'name', 'exam_type', 'month', 'status', 'start_date', 'end_date', 'classes')
                        ->orderBy('start_date', 'desc')
                        ->get();
            
            return response()->json([
                'success' => true,
                'exams' => $exams
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'পরীক্ষার তালিকা লোড করতে ব্যর্থ হয়েছে'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        
        // Validate request - make all fields except name and dates optional
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exam_type' => 'nullable|string',
            'status' => 'nullable|string|in:upcoming,ongoing,completed',
            'description' => 'nullable|string',
            'total_marks' => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1'
        ]);

        // Update only fields that are present in request
        $exam->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'পরীক্ষা সফলভাবে আপডেট করা হয়েছে',
                'exam' => $exam->load('subjects')
            ]);
        }

        return redirect()->route('tenant.exams.index')->with('success', 'পরীক্ষা সফলভাবে আপডেট করা হয়েছে');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        
        if (!$exam->canDelete()) {
            return response()->json([
                'success' => false,
                'message' => 'এই পরীক্ষা মুছে ফেলা যাবে না'
            ], 422);
        }

        $exam->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে'
            ]);
        }

        return redirect()->route('tenant.exams.index')->with('success', 'পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে');
    }

    /**
     * Bulk delete exams
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'exam_ids' => 'required|array|min:1',
            'exam_ids.*' => 'required|integer|exists:exams,id'
        ]);

        $examIds = $request->exam_ids;
        $exams = Exam::whereIn('id', $examIds)->get();
        
        // Check if any exam cannot be deleted
        $undeletableExams = [];
        foreach ($exams as $exam) {
            if (!$exam->canDelete()) {
                $undeletableExams[] = $exam->name;
            }
        }
        
        if (!empty($undeletableExams)) {
            return response()->json([
                'success' => false,
                'message' => 'নিম্নলিখিত পরীক্ষাগুলো মুছে ফেলা যাবে না: ' . implode(', ', $undeletableExams)
            ], 422);
        }

        $deletedCount = Exam::whereIn('id', $examIds)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount}টি পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে",
                'deleted_count' => $deletedCount
            ]);
        }

        return redirect()->route('tenant.exams.manage')
                        ->with('success', "{$deletedCount}টি পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে।");
    }

    public function updateStatus($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->updateStatus();
        
        return response()->json([
            'success' => true,
            'status' => $exam->status,
            'message' => 'পরীক্ষার স্ট্যাটাস আপডেট করা হয়েছে'
        ]);
    }

    public function publish($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->publish();
        
        return response()->json([
            'success' => true,
            'message' => 'পরীক্ষা প্রকাশ করা হয়েছে'
        ]);
    }

    public function unpublish($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->unpublish();
        
        return response()->json([
            'success' => true,
            'message' => 'পরীক্ষা প্রকাশ বাতিল করা হয়েছে'
        ]);
    }

    public function subjectSelection()
    {
        try {
            $exams = Exam::latest()->get();
            $subjects = Subject::where('is_active', true)->get();
            $classes = SchoolClass::where('is_active', true)->orderBy('name')->get();
        } catch (\Exception $e) {
            // Handle case where tables don't exist yet
            $exams = collect();
            $subjects = collect();
            $classes = collect();
        }
        
        return view('tenant.exams.subject-selection', compact('exams', 'subjects', 'classes'));
    }
    public function getExamSubjects($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            
            // Get class_id from request if provided
            $classId = request()->get('class_id');
            
            if (!$classId) {
                return response()->json([]);
            }
            
            // Get individual subjects (not in groups)
            // Use table prefix to avoid ambiguous column error
            $examSubjects = $exam->subjects()
                                ->where('subjects.class_id', $classId)
                                ->get();
            
            // Get subject groups for this exam and class
            $subjectGroups = \App\Models\SubjectGroup::where('exam_id', $examId)
                                                   ->where('class_id', $classId)
                                                   ->get();
            
            // Get IDs of subjects that are in groups
            $groupedSubjectIds = [];
            foreach ($subjectGroups as $group) {
                $groupedSubjectIds = array_merge($groupedSubjectIds, $group->subject_ids ?? []);
            }
            
            // Format individual subjects (exclude those in groups)
            $formattedSubjects = $examSubjects->filter(function ($subject) use ($groupedSubjectIds) {
                return !in_array($subject->id, $groupedSubjectIds);
            })->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'class_id' => $subject->class_id,
                    'fullMarks' => $subject->pivot->total_marks ?? $subject->total_marks ?? 100,
                    'examMarks' => $subject->pivot->total_marks ?? $subject->total_marks ?? 100,
                    'examDate' => $subject->pivot->exam_date ?? null,
                    'startTime' => $subject->pivot->start_time ?? null,
                    'endTime' => $subject->pivot->end_time ?? null,
                ];
            })->values();
            
            // Format groups as "group_" prefixed items
            $formattedGroups = $subjectGroups->map(function ($group) {
                return [
                    'id' => 'group_' . $group->id,
                    'name' => $group->name,
                    'is_group' => true,
                    'group_id' => $group->id,
                    'subjects' => $group->subject_ids,
                    'class_id' => $group->class_id,
                    'fullMarks' => $group->total_marks,
                    'examMarks' => $group->total_marks,
                    'passMarks' => $group->pass_marks,
                ];
            })->values();
            
            // Merge individual subjects and groups
            $result = $formattedSubjects->concat($formattedGroups);

            return response()->json($result->values());
        } catch (\Exception $e) {
            \Log::error('Error loading exam subjects:', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'পরীক্ষার বিষয় লোড করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subject groups for an exam and class
     */
    public function getSubjectGroups($examId)
    {
        try {
            $classId = request()->get('class_id');
            
            if (!$classId) {
                return response()->json([]);
            }
            
            $subjectGroups = \App\Models\SubjectGroup::where('exam_id', $examId)
                                                   ->where('class_id', $classId)
                                                   ->get();
            
            return response()->json($subjectGroups);
        } catch (\Exception $e) {
            \Log::error('Error loading subject groups:', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return response()->json([]);
        }
    }

    /**
     * Delete a specific subject group
     */
    public function deleteSubjectGroup($examId, $groupId)
    {
        try {
            $classId = request()->get('class_id');
            
            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ক্লাস ID প্রয়োজন'
                ], 422);
            }
            
            // Delete specific subject group
            $deleted = \App\Models\SubjectGroup::where('exam_id', $examId)
                                             ->where('class_id', $classId)
                                             ->where('id', $groupId)
                                             ->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'সাবজেক্ট গ্রুপ সফলভাবে মুছে ফেলা হয়েছে'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'সাবজেক্ট গ্রুপ পাওয়া যায়নি'
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting subject group:', ['exam_id' => $examId, 'group_id' => $groupId, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'সাবজেক্ট গ্রুপ মুছতে সমস্যা হয়েছে'
            ], 500);
        }
    }

    /**
     * Delete subject groups for an exam and class
     */
    public function deleteSubjectGroups($examId)
    {
        try {
            $classId = request()->get('class_id');
            
            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ক্লাস ID প্রয়োজন'
                ], 422);
            }
            
            // Delete all subject groups for this exam and class
            \App\Models\SubjectGroup::where('exam_id', $examId)
                                   ->where('class_id', $classId)
                                   ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'সাবজেক্ট গ্রুপ সফলভাবে মুছে ফেলা হয়েছে'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting subject groups:', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'সাবজেক্ট গ্রুপ মুছতে সমস্যা হয়েছে'
            ], 500);
        }
    }

    /**
     * Save exam subjects (including grouped subjects)
     */
    public function saveExamSubjects(Request $request, $examId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            
            // Debug logging
            \Log::info('SaveExamSubjects called', [
                'exam_id' => $examId,
                'request_data' => $request->all(),
                'request_method' => $request->method(),
                'request_headers' => $request->headers->all()
            ]);
            
            $request->validate([
                'subjects' => 'array', // Remove required validation to allow empty arrays
                'class_id' => 'required|exists:school_classes,id',
                'subject_groups' => 'sometimes|array'
            ]);

            $classId = $request->class_id;
            $subjectIds = $request->subjects ?? []; // Default to empty array if not provided
            $subjectGroups = $request->subject_groups ?? [];

            \Log::info('Processing subjects', [
                'subject_ids' => $subjectIds,
                'subject_groups' => $subjectGroups,
                'class_id' => $classId
            ]);

            // Remove existing subjects for this exam that belong to this class FIRST
            $existingSubjectIds = Subject::where('class_id', $classId)->pluck('id');
            $exam->subjects()->detach($existingSubjectIds);
            
            // Clear existing subject groups for this exam and class
            \App\Models\SubjectGroup::where('exam_id', $examId)
                                   ->where('class_id', $classId)
                                   ->delete();

            // Separate individual subjects from grouped subjects
            $individualSubjects = [];
            $groupedSubjectIds = []; // Track which subjects are in groups (to exclude from individual attach)
            $createdGroups = []; // Track created groups
            
            foreach ($subjectIds as $subjectId) {
                if (is_string($subjectId) && strpos($subjectId, 'group_') === 0) {
                    // This is a grouped subject, find the actual subject IDs
                    $groupId = str_replace('group_', '', $subjectId);
                    $group = collect($subjectGroups)->firstWhere('id', $groupId);
                    
                    \Log::info('Processing group', [
                        'group_id' => $groupId,
                        'group_data' => $group
                    ]);
                    
                    if ($group && isset($group['subjects']) && is_array($group['subjects'])) {
                        // Create subject group in database
                        $subjectGroup = \App\Models\SubjectGroup::create([
                            'name' => $group['name'] ?? "গ্রুপ {$groupId}",
                            'exam_id' => $examId,
                            'class_id' => $classId,
                            'total_marks' => $group['totalMarks'] ?? 100,
                            'pass_marks' => $group['passMarks'] ?? 33,
                            'subject_ids' => $group['subjects'],
                            'description' => "Subject group for exam {$examId}",
                            'is_active' => true
                        ]);
                        
                        \Log::info('Created subject group', [
                            'group_id' => $subjectGroup->id,
                            'subjects' => $group['subjects']
                        ]);
                        
                        $createdGroups[] = $subjectGroup;
                        // Track grouped subject IDs to exclude them from individual attachment
                        $groupedSubjectIds = array_merge($groupedSubjectIds, $group['subjects']);
                    }
                } else {
                    // This is an individual subject
                    $individualSubjects[] = $subjectId;
                }
            }
            
            // Only use individual subjects for attachment (NOT grouped subjects)
            // Groups are stored separately in subject_groups table
            $allSubjectIds = $individualSubjects;
            
            \Log::info('Final subject processing', [
                'individual_subjects' => $individualSubjects,
                'grouped_subject_ids' => $groupedSubjectIds,
                'all_subject_ids' => $allSubjectIds
            ]);
            
            // Allow empty subject arrays for clearing selections
            if (empty($allSubjectIds)) {
                \Log::info('No subjects selected - clearing all selections for exam and class');
                
                return response()->json([
                    'success' => true,
                    'message' => 'সকল বিষয় সফলভাবে সরানো হয়েছে',
                    'subjects_count' => 0,
                    'groups_count' => count($subjectGroups),
                    'class_id' => $classId
                ]);
            }

            // Get subjects with their details and verify they belong to the selected class
            $subjects = Subject::whereIn('id', $allSubjectIds)
                              ->where('class_id', $classId)
                              ->get();
            
            if ($subjects->count() !== count($allSubjectIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'কিছু বিষয় নির্বাচিত ক্লাসের সাথে মিলছে না'
                ], 422);
            }
            
            // Prepare pivot data for attaching subjects to exam
            // Only attach individual subjects (NOT grouped subjects)
            $pivotData = [];
            foreach ($subjects as $subject) {
                // Individual subjects only (groups are stored separately)
                $pivotData[$subject->id] = [
                    'total_marks' => $subject->total_marks ?? 100,
                    'pass_marks' => $subject->pass_marks ?? 33,
                    'exam_date' => $exam->start_date ?? now()->toDateString(),
                    'start_time' => '09:00:00',
                    'end_time' => '12:00:00',
                    'instructions' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            // Attach new subjects
            if (!empty($pivotData)) {
                $exam->subjects()->attach($pivotData);
            }

            // Calculate total count (individual subjects + groups)
            $totalCount = count($individualSubjects) + count($createdGroups);
            
            return response()->json([
                'success' => true,
                'message' => 'পরীক্ষার বিষয়সমূহ সফলভাবে সংরক্ষণ করা হয়েছে',
                'subjects_count' => $totalCount,
                'individual_subjects_count' => count($individualSubjects),
                'groups_count' => count($createdGroups),
                'class_id' => $classId
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in saveExamSubjects', [
                'exam_id' => $examId,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন এরর: ' . collect($e->errors())->flatten()->implode(', '),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in saveExamSubjects', [
                'exam_id' => $examId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'বিষয় সংরক্ষণ করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }
}

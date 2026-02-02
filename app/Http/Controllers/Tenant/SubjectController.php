<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        return view('tenant.subjects.index');
    }

    /**
     * Get all subjects as JSON
     */
    public function getSubjects(Request $request): JsonResponse
    {
        try {
            $query = Subject::query();
            
            // Only show active subjects by default
            if (!$request->has('include_inactive')) {
                $query->where('is_active', true);
            }
            
            if ($request->has('class_id') && $request->class_id) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->has('type') && $request->type) {
                $query->where('type', $request->type);
            }

            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('code', 'like', "%{$searchTerm}%");
                });
            }
            
            $subjects = $query->orderBy('name')->get();
            
            $formattedSubjects = $subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'type' => $subject->type,
                    'class_id' => $subject->class_id,
                    'classes' => $this->formatClasses($subject->classes ?? []),
                    'fullMarks' => $subject->total_marks ?? 100,
                    'passMarks' => $subject->pass_marks ?? 33,
                    'status' => $subject->is_active ? 'active' : 'inactive',
                    'description' => $subject->description
                ];
            });

            return response()->json($formattedSubjects);
        } catch (\Exception $e) {
            Log::error('Error fetching subjects:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Error loading subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // First check if there are any classes available
            $classCount = \App\Models\SchoolClass::count();
            if ($classCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'প্রথমে ক্লাস পেজ থেকে ক্লাস যোগ করুন',
                    'error' => 'no_classes_available'
                ], 422);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:subjects,code',
                'type' => 'required|in:mandatory,optional',
                'selectedClasses' => 'required|array|min:1',
                'selectedClasses.*' => 'required|integer|exists:school_classes,id',
                'fullMarks' => 'required|integer|min:1|max:1000',
                'description' => 'nullable|string'
            ]);

            $createdSubjects = [];
            
            // Create subject for each selected class
            foreach ($request->selectedClasses as $classId) {
                $selectedClass = \App\Models\SchoolClass::find($classId);
                if (!$selectedClass) {
                    continue;
                }

                $classes = [$selectedClass->full_name];
                
                // Create unique code for each class if multiple classes selected
                $subjectCode = $request->code;
                if (count($request->selectedClasses) > 1) {
                    // Append class name to make unique
                    $subjectCode = $request->code . '-' . strtoupper(str_replace(' ', '', $selectedClass->name));
                }
                
                // Check if this combination already exists
                $exists = Subject::where('name', $request->name)
                    ->where('class_id', $classId)
                    ->exists();
                    
                if ($exists) {
                    continue; // Skip if already exists for this class
                }

                $subject = Subject::create([
                    'name' => $request->name,
                    'code' => $subjectCode,
                    'type' => $request->type,
                    'class_id' => $classId,
                    'description' => $request->description,
                    'classes' => $classes,
                    'total_marks' => $request->fullMarks,
                    'pass_marks' => round($request->fullMarks * 0.33), // 33% pass marks
                    'is_active' => true
                ]);
                
                $createdSubjects[] = [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'type' => $subject->type,
                    'classes' => $this->formatClasses($subject->classes),
                    'class_id' => $subject->class_id,
                    'fullMarks' => $subject->total_marks,
                    'status' => 'active',
                    'description' => $subject->description
                ];
            }

            if (empty($createdSubjects)) {
                return response()->json([
                    'success' => false,
                    'message' => 'নির্বাচিত ক্লাসগুলিতে এই বিষয় ইতিমধ্যে বিদ্যমান',
                ], 422);
            }

            $classCount = count($createdSubjects);
            return response()->json([
                'success' => true,
                'message' => "নতুন বিষয় সফলভাবে {$classCount}টি ক্লাসে যোগ করা হয়েছে",
                'subjects' => $createdSubjects,
                'subject' => $createdSubjects[0] // For backward compatibility
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন এরর: ' . collect($e->errors())->flatten()->implode(', '),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Subject creation error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'বিষয় যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage(),
                'error' => 'server_error'
            ], 500);
        }
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
                'type' => 'required|in:mandatory,optional',
                'fullMarks' => 'required|integer|min:1|max:1000',
                'description' => 'nullable|string'
            ]);

            $subject->update([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'description' => $request->description,
                'total_marks' => $request->fullMarks,
                'pass_marks' => round($request->fullMarks * 0.33),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'বিষয়টি সফলভাবে আপডেট করা হয়েছে',
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'type' => $request->type,
                    'classes' => $this->formatClasses($subject->classes),
                    'fullMarks' => $subject->total_marks,
                    'status' => $subject->is_active ? 'active' : 'inactive',
                    'description' => $subject->description
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বিষয় আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'বিষয়টি সফলভাবে মুছে ফেলা হয়েছে'
        ]);
    }

    /**
     * Bulk delete subjects
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'subject_ids' => 'required|array|min:1',
                'subject_ids.*' => 'required|integer|exists:subjects,id'
            ]);

            $subjectIds = $request->subject_ids;
            $deletedCount = Subject::whereIn('id', $subjectIds)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount}টি বিষয় সফলভাবে মুছে ফেলা হয়েছে",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'অবৈধ তথ্য',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk delete error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'বিষয় মুছতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active classes for dropdown
     */
    public function getClasses(): JsonResponse
    {
        $classes = \App\Models\SchoolClass::active()->ordered()->get();
        
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'name' => $class->name,
                'section' => $class->section,
                'full_name' => $class->full_name
            ];
        });

        return response()->json($formattedClasses);
    }

    /**
     * Get subject statistics
     */
    public function getStats(): JsonResponse
    {
        $totalSubjects = Subject::count();
        $activeSubjects = Subject::where('is_active', true)->count();
        
        $mandatorySubjects = Subject::where('type', 'mandatory')->count();
        $optionalSubjects = Subject::where('type', 'optional')->count();

        return response()->json([
            'totalSubjects' => $totalSubjects,
            'mandatorySubjects' => $mandatorySubjects,
            'optionalSubjects' => $optionalSubjects,
            'activeSubjects' => $activeSubjects
        ]);
    }



    /**
     * Format classes array for display
     */
    private function formatClasses($classes): string
    {
        if (!is_array($classes) || empty($classes)) {
            return '';
        }

        // If it's already formatted class names, return as is
        if (count($classes) === 1 && strpos($classes[0], ' - ') !== false) {
            return $classes[0];
        }

        // Legacy format handling
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $firstClass = str_replace($englishNumbers, $bengaliNumbers, str_replace('Class ', '', $classes[0]));
        $lastClass = str_replace($englishNumbers, $bengaliNumbers, str_replace('Class ', '', end($classes)));

        if (count($classes) === 1) {
            return $firstClass . 'ষ্ঠ';
        }

        return $firstClass . 'ষ্ঠ - ' . $lastClass . 'ম';
    }
}
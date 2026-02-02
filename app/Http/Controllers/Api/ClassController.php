<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClassController extends BaseApiController
{
    /**
     * Get all classes with student counts
     */
    public function index(Request $request)
    {
        // Get classes from SchoolClass model
        $schoolClasses = SchoolClass::active()->ordered()->get();
        
        // Get actual student counts from Student model
        $studentCounts = Student::select('class', 'section', DB::raw('count(*) as student_count'))
            ->where('status', 'active')
            ->groupBy('class', 'section')
            ->get()
            ->keyBy(function ($item) {
                return $item->class . '-' . $item->section;
            });

        $classData = $schoolClasses->map(function ($class) use ($studentCounts) {
            $key = $class->name . '-' . $class->section;
            $actualStudentCount = $studentCounts->get($key)?->student_count ?? 0;
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'section' => $class->section,
                'full_name' => $class->full_name,
                'configured_students' => $class->students,
                'actual_students' => $actualStudentCount,
                'teachers' => $class->teachers,
                'description' => $class->description,
                'is_active' => $class->is_active,
                'created_at' => $class->created_at,
            ];
        });

        return $this->sendResponse($classData, 'Classes retrieved successfully');
    }

    /**
     * Get class statistics
     */
    public function show($id)
    {
        $class = SchoolClass::find($id);
        
        if (!$class) {
            return $this->sendNotFound('Class not found');
        }

        // Get actual students in this class
        $students = Student::where('class', $class->name)
            ->where('section', $class->section)
            ->where('status', 'active')
            ->get();

        $studentsData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'roll_number' => $student->roll_number,
                'gender' => $student->gender,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            ];
        });

        $classStats = [
            'id' => $class->id,
            'name' => $class->name,
            'section' => $class->section,
            'full_name' => $class->full_name,
            'configured_students' => $class->students,
            'configured_teachers' => $class->teachers,
            'actual_students' => $students->count(),
            'male_students' => $students->where('gender', 'male')->count(),
            'female_students' => $students->where('gender', 'female')->count(),
            'description' => $class->description,
            'is_active' => $class->is_active,
            'students' => $studentsData,
        ];

        return $this->sendResponse($classStats, 'Class details retrieved successfully');
    }

    /**
     * Create a new class
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'section' => [
                'required',
                'string',
                'max:10',
                Rule::unique('school_classes')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })
            ],
            'students' => 'nullable|integer|min:0',
            'teachers' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $class = SchoolClass::create([
                'name' => $request->name,
                'section' => $request->section,
                'students' => $request->students ?? 0,
                'teachers' => $request->teachers ?? 0,
                'description' => $request->description
            ]);

            return $this->sendResponse($class, 'Class created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Error creating class', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update class information
     */
    public function update(Request $request, $id)
    {
        $class = SchoolClass::find($id);
        
        if (!$class) {
            return $this->sendNotFound('Class not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'section' => [
                'required',
                'string',
                'max:10',
                Rule::unique('school_classes')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($id)
            ],
            'students' => 'nullable|integer|min:0',
            'teachers' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $class->update([
                'name' => $request->name,
                'section' => $request->section,
                'students' => $request->students ?? 0,
                'teachers' => $request->teachers ?? 0,
                'description' => $request->description
            ]);

            return $this->sendResponse($class, 'Class updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating class', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a class
     */
    public function destroy($id)
    {
        $class = SchoolClass::find($id);
        
        if (!$class) {
            return $this->sendNotFound('Class not found');
        }

        try {
            // Check if there are students in this class
            $studentCount = Student::where('class', $class->name)
                ->where('section', $class->section)
                ->where('status', 'active')
                ->count();

            if ($studentCount > 0) {
                return $this->sendError('Cannot delete class with active students', [
                    'student_count' => $studentCount,
                    'message' => 'Please transfer or remove all students before deleting this class'
                ]);
            }

            $class->delete();

            return $this->sendResponse([], 'Class deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting class', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get students in a specific class
     */
    public function getStudents($id)
    {
        $class = SchoolClass::find($id);
        
        if (!$class) {
            return $this->sendNotFound('Class not found');
        }

        $students = Student::where('class', $class->name)
            ->where('section', $class->section)
            ->where('status', 'active')
            ->orderBy('roll_number')
            ->get();

        $studentsData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'name_bangla' => $student->name_bangla,
                'roll_number' => $student->roll_number,
                'gender' => $student->gender,
                'phone' => $student->phone,
                'guardian_phone' => $student->guardian_phone,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            ];
        });

        return $this->sendResponse($studentsData, 'Class students retrieved successfully');
    }

    /**
     * Get class statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'total_classes' => SchoolClass::count(),
                'active_classes' => SchoolClass::active()->count(),
                'total_configured_students' => SchoolClass::sum('students'),
                'total_configured_teachers' => SchoolClass::sum('teachers'),
                'actual_students' => Student::where('status', 'active')->count(),
                'classes_by_section' => SchoolClass::selectRaw('section, COUNT(*) as count')
                    ->groupBy('section')
                    ->pluck('count', 'section')
            ];

            return $this->sendResponse($stats, 'Class statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving statistics', ['error' => $e->getMessage()]);
        }
    }
}
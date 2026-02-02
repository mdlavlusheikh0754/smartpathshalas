<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClassRoutine;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    public function getRoutine(Request $request)
    {
        $classId = $request->query('class_id');
        $section = $request->query('section');

        if (!$classId || !$section) {
            return response()->json([
                'success' => false,
                'message' => 'Class ID and Section are required'
            ], 400);
        }

        try {
            // Get the selected class
            $schoolClass = SchoolClass::find($classId);
            
            if (!$schoolClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }

            // Get routines for this class
            $routines = ClassRoutine::with(['schoolClass', 'subject', 'teacher'])
                ->where('class_id', $classId)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

            $routineData = [];

            foreach ($routines as $routine) {
                $routineData[] = [
                    'id' => $routine->id,
                    'day' => $routine->day,
                    'start_time' => $routine->start_time,
                    'end_time' => $routine->end_time,
                    'room_no' => $routine->room_no,
                    'is_break' => $routine->is_break,
                    'break_name' => $routine->break_name,
                    'subject_name' => $routine->subject ? $routine->subject->name : null,
                    'teacher_name' => $routine->teacher ? $routine->teacher->name : null,
                ];
            }

            return response()->json([
                'success' => true,
                'routines' => $routineData,
                'class_name' => $schoolClass->name,
                'section' => $schoolClass->section
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching routine: ' . $e->getMessage()
            ], 500);
        }
    }
}

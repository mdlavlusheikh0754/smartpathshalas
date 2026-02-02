<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassRoutine;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class RoutineController extends BaseApiController
{
    public function getClassRoutine(Request $request)
    {
        try {
            $classId = $request->query('class_id');
            $section = $request->query('section');

            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class ID is required'
                ], 400);
            }

            // Fetch routines for the class
            $daysMap = [
                'Saturday' => 1,
                'Sunday' => 2,
                'Monday' => 3,
                'Tuesday' => 4,
                'Wednesday' => 5,
                'Thursday' => 6,
                'Friday' => 7,
            ];

            $routines = ClassRoutine::with(['subject', 'teacher'])
                ->where('class_id', $classId)
                ->get()
                ->map(function ($routine) use ($daysMap) {
                    return [
                        'id' => $routine->id,
                        'day' => $daysMap[$routine->day] ?? $routine->day,
                        'day_name' => $routine->day,
                        'start_time' => date('h:i A', strtotime($routine->start_time)),
                        'end_time' => date('h:i A', strtotime($routine->end_time)),
                        'subject_name' => $routine->subject?->name ?? 'N/A',
                        'teacher_name' => $routine->teacher?->name ?? 'N/A',
                        'room_no' => $routine->room_no,
                        'is_break' => $routine->is_break,
                        'break_name' => $routine->break_name,
                    ];
                })
                ->sortBy(['day', 'start_time'])
                ->values();

            return response()->json([
                'success' => true,
                'routines' => $routines,
                'count' => $routines->count()
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching class routine: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching routine data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

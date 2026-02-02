<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends BaseApiController
{
    /**
     * Get all exams
     */
    public function index(Request $request)
    {
        // TODO: Implement with real Exam model
        $exams = [];

        return $this->sendResponse($exams, 'Exams retrieved successfully');
    }

    /**
     * Store a new exam
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_bangla' => 'nullable|string|max:255',
            'type' => 'required|in:monthly,terminal,half_yearly,annual,final',
            'class' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'subjects' => 'required|array',
            'subjects.*.name' => 'required|string|max:100',
            'subjects.*.marks' => 'required|integer|min:1',
            'subjects.*.duration' => 'required|integer|min:30',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // TODO: Create exam in database
        $examData = [
            'id' => null, // Will be set after database creation
            'name' => $request->name,
            'name_bangla' => $request->name_bangla,
            'type' => $request->type,
            'class' => $request->class,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'upcoming',
            'subjects' => $request->subjects,
            'total_subjects' => count($request->subjects),
            'total_marks' => array_sum(array_column($request->subjects, 'marks')),
            'description' => $request->description,
            'created_by' => auth()->user()->name ?? 'System',
            'created_at' => now(),
        ];

        return $this->sendResponse($examData, 'Exam created successfully', 201);
    }

    /**
     * Get a specific exam
     */
    public function show($id)
    {
        // TODO: Implement with real Exam model
        return $this->sendNotFound('Exam not found');
    }

    /**
     * Update an exam
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'name_bangla' => 'nullable|string|max:255',
            'type' => 'sometimes|required|in:monthly,terminal,half_yearly,annual,final',
            'class' => 'sometimes|required|string|max:50',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'status' => 'sometimes|required|in:upcoming,ongoing,completed,cancelled',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // TODO: Update exam in database
        $examData = [
            'id' => $id,
            'message' => 'Exam update functionality not implemented yet',
            'updated_at' => now(),
        ];

        return $this->sendResponse($examData, 'Exam updated successfully');
    }

    /**
     * Delete an exam
     */
    public function destroy($id)
    {
        // TODO: Implement exam deletion
        $data = [
            'id' => $id,
            'message' => 'Exam deletion functionality not implemented yet',
            'deleted_at' => now(),
        ];

        return $this->sendResponse($data, 'Exam deletion not implemented');
    }

    /**
     * Get exam results
     */
    public function getResults($id, Request $request)
    {
        // TODO: Implement with real exam results
        $data = [
            'exam_id' => $id,
            'message' => 'Exam results functionality not implemented yet',
            'results' => [],
        ];

        return $this->sendResponse($data, 'Exam results not implemented');
    }

    /**
     * Store exam results
     */
    public function storeResults(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'results' => 'required|array',
            'results.*.student_id' => 'required|integer',
            'results.*.marks' => 'required|array',
            'results.*.marks.*' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // TODO: Implement result storage in database
        $data = [
            'exam_id' => $id,
            'message' => 'Exam result storage functionality not implemented yet',
            'created_at' => now(),
        ];

        return $this->sendResponse($data, 'Exam result storage not implemented', 201);
    }

    /**
     * Calculate grade based on percentage
     */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 80) return 'A+';
        if ($percentage >= 70) return 'A';
        if ($percentage >= 60) return 'A-';
        if ($percentage >= 50) return 'B';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageCompression;

class TeacherController extends BaseApiController
{
    use ImageCompression;

    /**
     * Get all teachers with pagination and filters
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        // Apply filters
        if ($request->has('subject') && $request->subject) {
            $query->where('subject', 'like', "%{$request->subject}%");
        }

        if ($request->has('designation') && $request->designation) {
            $query->where('designation', $request->designation);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('teacher_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $teachers = $query->paginate($perPage);

        // Transform data
        $teachers->getCollection()->transform(function ($teacher) {
            return $this->transformTeacher($teacher);
        });

        return $this->sendPaginatedResponse($teachers, 'Teachers retrieved successfully');
    }

    /**
     * Store a new teacher
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_bangla' => 'nullable|string|max:255',
            'designation' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:teachers,email',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'joining_date' => 'required|date',
            'qualification' => 'required|string',
            'experience' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // Generate teacher ID if not provided
        if (!isset($data['teacher_id']) || empty($data['teacher_id'])) {
            $data['teacher_id'] = 'TCH' . date('Y') . str_pad(Teacher::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->compressAndStore($request->file('photo'), 'teachers/photos');
        }

        $teacher = Teacher::create($data);

        return $this->sendResponse($this->transformTeacher($teacher), 'Teacher created successfully', 201);
    }

    /**
     * Get a specific teacher
     */
    public function show($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return $this->sendNotFound('Teacher not found');
        }

        return $this->sendResponse($this->transformTeacher($teacher), 'Teacher retrieved successfully');
    }

    /**
     * Update a teacher
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return $this->sendNotFound('Teacher not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'name_bangla' => 'nullable|string|max:255',
            'designation' => 'sometimes|required|string|max:100',
            'subject' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|unique:teachers,email,' . $id,
            'address' => 'sometimes|required|string',
            'date_of_birth' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'joining_date' => 'sometimes|required|date',
            'qualification' => 'sometimes|required|string',
            'experience' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:active,inactive,resigned,terminated',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $data['photo'] = $this->compressAndStore($request->file('photo'), 'teachers/photos');
        }

        $teacher->update($data);

        return $this->sendResponse($this->transformTeacher($teacher), 'Teacher updated successfully');
    }

    /**
     * Delete a teacher
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return $this->sendNotFound('Teacher not found');
        }

        // Delete photo if exists
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return $this->sendResponse([], 'Teacher deleted successfully');
    }

    /**
     * Upload teacher photo
     */
    public function uploadPhoto(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return $this->sendNotFound('Teacher not found');
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Delete old photo
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        // Upload new photo
        $photoPath = $this->compressAndStore($request->file('photo'), 'teachers/photos');
        $teacher->update(['photo' => $photoPath]);

        $data = [
            'photo_url' => asset('storage/' . $photoPath),
        ];

        return $this->sendResponse($data, 'Photo uploaded successfully');
    }

    /**
     * Transform teacher data
     */
    private function transformTeacher($teacher)
    {
        return [
            'id' => $teacher->id,
            'teacher_id' => $teacher->teacher_id,
            'name' => $teacher->name,
            'name_bangla' => $teacher->name_bangla,
            'designation' => $teacher->designation,
            'subject' => $teacher->subject,
            'phone' => $teacher->phone,
            'email' => $teacher->email,
            'address' => $teacher->address,
            'date_of_birth' => $teacher->date_of_birth,
            'gender' => $teacher->gender,
            'blood_group' => $teacher->blood_group,
            'religion' => $teacher->religion,
            'nationality' => $teacher->nationality,
            'joining_date' => $teacher->joining_date,
            'qualification' => $teacher->qualification,
            'experience' => $teacher->experience,
            'salary' => $teacher->salary,
            'status' => $teacher->status,
            'photo' => $teacher->photo ? asset('storage/' . $teacher->photo) : null,
            'years_of_service' => $teacher->joining_date ? now()->diffInYears($teacher->joining_date) : 0,
            'created_at' => $teacher->created_at,
            'updated_at' => $teacher->updated_at,
        ];
    }
}
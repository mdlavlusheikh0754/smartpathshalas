<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageCompression;

class StudentController extends BaseApiController
{
    use ImageCompression;

    /**
     * Get all students with pagination and filters
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }

        if ($request->has('section') && $request->section) {
            $query->where('section', $request->section);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%");
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
        $students = $query->paginate($perPage);

        // Transform data
        $students->getCollection()->transform(function ($student) {
            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'name_bangla' => $student->name_bangla,
                'class' => $student->class,
                'section' => $student->section,
                'roll_number' => $student->roll_number,
                'phone' => $student->phone,
                'email' => $student->email,
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'guardian_phone' => $student->guardian_phone,
                'address' => $student->address,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'blood_group' => $student->blood_group,
                'religion' => $student->religion,
                'nationality' => $student->nationality,
                'admission_date' => $student->admission_date,
                'status' => $student->status,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ];
        });

        return $this->sendPaginatedResponse($students, 'Students retrieved successfully');
    }

    /**
     * Store a new student
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_bangla' => 'nullable|string|max:255',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'roll_number' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:students,email',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'admission_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // Generate student ID if not provided
        if (!isset($data['student_id']) || empty($data['student_id'])) {
            $data['student_id'] = 'STU' . date('Y') . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->compressAndStore($request->file('photo'), 'students/photos');
        }

        $student = Student::create($data);

        $studentData = [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'name' => $student->name,
            'name_bangla' => $student->name_bangla,
            'class' => $student->class,
            'section' => $student->section,
            'roll_number' => $student->roll_number,
            'phone' => $student->phone,
            'email' => $student->email,
            'father_name' => $student->father_name,
            'mother_name' => $student->mother_name,
            'guardian_phone' => $student->guardian_phone,
            'address' => $student->address,
            'date_of_birth' => $student->date_of_birth,
            'gender' => $student->gender,
            'blood_group' => $student->blood_group,
            'religion' => $student->religion,
            'nationality' => $student->nationality,
            'admission_date' => $student->admission_date,
            'status' => $student->status,
            'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];

        return $this->sendResponse($studentData, 'Student created successfully', 201);
    }

    /**
     * Get a specific student
     */
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        $studentData = [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'name' => $student->name,
            'name_bangla' => $student->name_bangla,
            'class' => $student->class,
            'section' => $student->section,
            'roll_number' => $student->roll_number,
            'phone' => $student->phone,
            'email' => $student->email,
            'father_name' => $student->father_name,
            'mother_name' => $student->mother_name,
            'guardian_phone' => $student->guardian_phone,
            'address' => $student->address,
            'date_of_birth' => $student->date_of_birth,
            'gender' => $student->gender,
            'blood_group' => $student->blood_group,
            'religion' => $student->religion,
            'nationality' => $student->nationality,
            'admission_date' => $student->admission_date,
            'status' => $student->status,
            'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];

        return $this->sendResponse($studentData, 'Student retrieved successfully');
    }

    /**
     * Update a student
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'name_bangla' => 'nullable|string|max:255',
            'class' => 'sometimes|required|string|max:50',
            'section' => 'nullable|string|max:10',
            'roll_number' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:students,email,' . $id,
            'father_name' => 'sometimes|required|string|max:255',
            'mother_name' => 'sometimes|required|string|max:255',
            'guardian_phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'date_of_birth' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'admission_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:active,inactive,graduated,transferred',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $this->compressAndStore($request->file('photo'), 'students/photos');
        }

        $student->update($data);

        $studentData = [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'name' => $student->name,
            'name_bangla' => $student->name_bangla,
            'class' => $student->class,
            'section' => $student->section,
            'roll_number' => $student->roll_number,
            'phone' => $student->phone,
            'email' => $student->email,
            'father_name' => $student->father_name,
            'mother_name' => $student->mother_name,
            'guardian_phone' => $student->guardian_phone,
            'address' => $student->address,
            'date_of_birth' => $student->date_of_birth,
            'gender' => $student->gender,
            'blood_group' => $student->blood_group,
            'religion' => $student->religion,
            'nationality' => $student->nationality,
            'admission_date' => $student->admission_date,
            'status' => $student->status,
            'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];

        return $this->sendResponse($studentData, 'Student updated successfully');
    }

    /**
     * Delete a student
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        // Delete photo if exists
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return $this->sendResponse([], 'Student deleted successfully');
    }

    /**
     * Get students by class
     */
    public function getByClass($class)
    {
        $students = Student::where('class', $class)
            ->where('status', 'active')
            ->orderBy('roll_number')
            ->get();

        $studentsData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'name_bangla' => $student->name_bangla,
                'class' => $student->class,
                'section' => $student->section,
                'roll_number' => $student->roll_number,
                'phone' => $student->phone,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            ];
        });

        return $this->sendResponse($studentsData, 'Students retrieved successfully');
    }

    /**
     * Upload student photo
     */
    public function uploadPhoto(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Delete old photo
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        // Upload new photo
        $photoPath = $this->compressAndStore($request->file('photo'), 'students/photos');
        $student->update(['photo' => $photoPath]);

        $data = [
            'photo_url' => asset('storage/' . $photoPath),
        ];

        return $this->sendResponse($data, 'Photo uploaded successfully');
    }

    /**
     * Get students list with QR/RFID status
     */
    public function getQrRfidList(Request $request)
    {
        $query = Student::query();

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }

        if ($request->has('section') && $request->section) {
            $query->where('section', $request->section);
        }

        if ($request->has('status') && $request->status) {
            $status = $request->status;
            switch ($status) {
                case 'configured':
                    $query->whereNotNull('qr_code')->whereNotNull('rfid_card');
                    break;
                case 'qr_only':
                    $query->whereNotNull('qr_code')->whereNull('rfid_card');
                    break;
                case 'rfid_only':
                    $query->whereNull('qr_code')->whereNotNull('rfid_card');
                    break;
                case 'not_configured':
                    $query->whereNull('qr_code')->whereNull('rfid_card');
                    break;
            }
        }

        $students = $query->where('status', 'active')
            ->orderBy('class')
            ->orderBy('section')
            ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
            ->get();

        $studentsData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name_bn ?? $student->name_en ?? $student->name,
                'class' => $student->class,
                'section' => $student->section,
                'roll' => $student->roll ?? $student->roll_number,
                'photo_url' => $student->photo_url,
                'qr_code' => $student->qr_code,
                'rfid_card' => $student->rfid_card,
            ];
        });

        return $this->sendResponse(['students' => $studentsData], 'Students retrieved successfully');
    }

    /**
     * Generate QR code for a student
     */
    public function generateQR($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        // Generate unique QR code using student ID and timestamp
        $qrCode = 'QR-' . $student->student_id . '-' . time();

        $student->update(['qr_code' => $qrCode]);

        return $this->sendResponse([
            'qr_code' => $qrCode,
            'student_id' => $student->student_id,
            'student_name' => $student->name_bn ?? $student->name_en ?? $student->name
        ], 'QR Code generated successfully');
    }

    /**
     * Generate QR codes for all students
     */
    public function generateAllQR(Request $request)
    {
        $query = Student::where('status', 'active');

        // Only generate for students without QR code
        $query->whereNull('qr_code');

        $students = $query->get();
        $count = 0;

        foreach ($students as $student) {
            $qrCode = 'QR-' . $student->student_id . '-' . time() . '-' . $student->id;
            $student->update(['qr_code' => $qrCode]);
            $count++;
        }

        return $this->sendResponse([
            'count' => $count,
            'message' => "$count টি QR Code তৈরি হয়েছে"
        ], 'QR Codes generated successfully');
    }

    /**
     * Set RFID card for a student
     */
    public function setRFID(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->sendNotFound('Student not found');
        }

        $validator = Validator::make($request->all(), [
            'rfid_card' => 'nullable|string|max:50|unique:students,rfid_card,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $rfidCard = $request->input('rfid_card');

        // Allow empty string to remove RFID
        if (empty($rfidCard)) {
            $rfidCard = null;
        }

        $student->update(['rfid_card' => $rfidCard]);

        return $this->sendResponse([
            'rfid_card' => $rfidCard,
            'student_id' => $student->student_id,
            'student_name' => $student->name_bn ?? $student->name_en ?? $student->name
        ], $rfidCard ? 'RFID card set successfully' : 'RFID card removed successfully');
    }
}
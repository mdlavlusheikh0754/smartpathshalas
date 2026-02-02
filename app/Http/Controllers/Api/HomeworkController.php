<?php

namespace App\Http\Controllers\Api;

use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageCompression;

class HomeworkController extends BaseApiController
{
    use ImageCompression;

    /**
     * Get all homework with pagination and filters (for authenticated users)
     */
    public function index(Request $request)
    {
        $query = Homework::query();

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }

        if ($request->has('section') && $request->section) {
            $query->where('section', $request->section);
        }

        if ($request->has('subject') && $request->subject) {
            $query->where('subject', $request->subject);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->where('assigned_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('assigned_date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'assigned_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $homework = $query->paginate($perPage);

        // Transform data
        $homework->getCollection()->transform(function ($item) {
            return $this->transformHomework($item);
        });

        return $this->sendPaginatedResponse($homework, 'Homework retrieved successfully');
    }

    /**
     * Get public homework (for students/parents without authentication)
     */
    public function getPublicHomework(Request $request)
    {
        $query = Homework::where('status', 'active');

        // Apply filters
        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }

        if ($request->has('subject') && $request->subject) {
            $query->where('subject', $request->subject);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Get upcoming and recent homework
        $upcoming = $query->clone()
            ->where('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        $recent = $query->clone()
            ->where('assigned_date', '<=', now())
            ->orderBy('assigned_date', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'upcoming' => $upcoming->map(function ($item) {
                return $this->transformHomework($item, false);
            }),
            'recent' => $recent->map(function ($item) {
                return $this->transformHomework($item, false);
            }),
        ];

        return $this->sendResponse($data, 'Public homework retrieved successfully');
    }

    /**
     * Store a new homework
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:100',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'instructions' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();
        $data['status'] = $data['status'] ?? 'active';

        // Set teacher ID if authenticated
        if (auth()->check()) {
            $data['teacher_id'] = auth()->id();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('homework/attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        $homework = Homework::create($data);

        return $this->sendResponse($this->transformHomework($homework), 'Homework created successfully', 201);
    }

    /**
     * Get a specific homework
     */
    public function show($id)
    {
        $homework = Homework::find($id);

        if (!$homework) {
            return $this->sendNotFound('Homework not found');
        }

        return $this->sendResponse($this->transformHomework($homework), 'Homework retrieved successfully');
    }

    /**
     * Get homework details (public endpoint)
     */
    public function getHomeworkDetails($id)
    {
        $homework = Homework::where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$homework) {
            return $this->sendNotFound('Homework not found');
        }

        return $this->sendResponse($this->transformHomework($homework, false), 'Homework details retrieved successfully');
    }

    /**
     * Update a homework
     */
    public function update(Request $request, $id)
    {
        $homework = Homework::find($id);

        if (!$homework) {
            return $this->sendNotFound('Homework not found');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'subject' => 'sometimes|required|string|max:100',
            'class' => 'sometimes|required|string|max:50',
            'section' => 'nullable|string|max:10',
            'assigned_date' => 'sometimes|required|date',
            'due_date' => 'sometimes|required|date|after_or_equal:assigned_date',
            'instructions' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($homework->attachment) {
                Storage::disk('public')->delete($homework->attachment);
            }

            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('homework/attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        $homework->update($data);

        return $this->sendResponse($this->transformHomework($homework), 'Homework updated successfully');
    }

    /**
     * Delete a homework
     */
    public function destroy($id)
    {
        $homework = Homework::find($id);

        if (!$homework) {
            return $this->sendNotFound('Homework not found');
        }

        // Delete attachment if exists
        if ($homework->attachment) {
            Storage::disk('public')->delete($homework->attachment);
        }

        $homework->delete();

        return $this->sendResponse([], 'Homework deleted successfully');
    }

    /**
     * Get homework by class
     */
    public function getByClass($class)
    {
        $homework = Homework::where('class', $class)
            ->where('status', 'active')
            ->orderBy('assigned_date', 'desc')
            ->get();

        $homeworkData = $homework->map(function ($item) {
            return $this->transformHomework($item, false);
        });

        return $this->sendResponse($homeworkData, 'Homework retrieved successfully');
    }

    /**
     * Upload homework attachment
     */
    public function uploadAttachment(Request $request, $id)
    {
        $homework = Homework::find($id);

        if (!$homework) {
            return $this->sendNotFound('Homework not found');
        }

        $validator = Validator::make($request->all(), [
            'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Delete old attachment
        if ($homework->attachment) {
            Storage::disk('public')->delete($homework->attachment);
        }

        // Upload new attachment
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('homework/attachments', $filename, 'public');
        
        $homework->update(['attachment' => $path]);

        $data = [
            'attachment_url' => asset('storage/' . $path),
            'attachment_name' => $file->getClientOriginalName(),
        ];

        return $this->sendResponse($data, 'Attachment uploaded successfully');
    }

    /**
     * Transform homework data
     */
    private function transformHomework($homework, $includeTeacher = true)
    {
        $data = [
            'id' => $homework->id,
            'title' => $homework->title,
            'description' => $homework->description,
            'subject' => $homework->subject,
            'class' => $homework->class,
            'section' => $homework->section,
            'assigned_date' => $homework->assigned_date?->format('Y-m-d'),
            'due_date' => $homework->due_date?->format('Y-m-d'),
            'instructions' => $homework->instructions,
            'attachment' => $homework->attachment ? asset('storage/' . $homework->attachment) : null,
            'attachment_name' => $homework->attachment ? basename($homework->attachment) : null,
            'status' => $homework->status,
            'status_text' => $homework->getStatusText(),
            'is_overdue' => $homework->due_date && $homework->due_date->isPast(),
            'days_remaining' => $homework->due_date ? now()->diffInDays($homework->due_date, false) : null,
            'created_at' => $homework->created_at,
            'updated_at' => $homework->updated_at,
        ];

        if ($includeTeacher && $homework->teacher) {
            $data['teacher'] = [
                'id' => $homework->teacher->id,
                'name' => $homework->teacher->name,
                'email' => $homework->teacher->email,
            ];
        }

        return $data;
    }
}
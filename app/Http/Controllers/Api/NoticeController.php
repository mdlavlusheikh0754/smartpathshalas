<?php

namespace App\Http\Controllers\Api;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class NoticeController extends BaseApiController
{
    /**
     * Get all notices with pagination and filters (for authenticated users)
     */
    public function index(Request $request)
    {
        $query = Notice::query();

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $notices = $query->paginate($perPage);

        // Transform data
        $notices->getCollection()->transform(function ($notice) {
            return $this->transformNotice($notice);
        });

        return $this->sendPaginatedResponse($notices, 'Notices retrieved successfully');
    }

    /**
     * Get public notices (for students/parents without authentication)
     */
    public function getPublicNotices(Request $request)
    {
        $query = Notice::where('status', 'active');

        // Apply filters
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Get recent notices
        $notices = $query->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $noticesData = $notices->map(function ($notice) {
            return $this->transformNotice($notice, false);
        });

        return $this->sendResponse($noticesData, 'Public notices retrieved successfully');
    }

    /**
     * Store a new notice
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'status' => 'nullable|in:active,inactive',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after:publish_date',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();
        $data['status'] = $data['status'] ?? 'active';
        $data['priority'] = $data['priority'] ?? 'normal';

        // Set author if authenticated
        if (auth()->check()) {
            $data['author_id'] = auth()->id();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('notices/attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        $notice = Notice::create($data);

        return $this->sendResponse($this->transformNotice($notice), 'Notice created successfully', 201);
    }

    /**
     * Get a specific notice
     */
    public function show($id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return $this->sendNotFound('Notice not found');
        }

        return $this->sendResponse($this->transformNotice($notice), 'Notice retrieved successfully');
    }

    /**
     * Get notice details (public endpoint)
     */
    public function getNoticeDetails($id)
    {
        $notice = Notice::where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$notice) {
            return $this->sendNotFound('Notice not found');
        }

        return $this->sendResponse($this->transformNotice($notice, false), 'Notice details retrieved successfully');
    }

    /**
     * Update a notice
     */
    public function update(Request $request, $id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return $this->sendNotFound('Notice not found');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'priority' => 'sometimes|required|in:low,normal,high,urgent',
            'status' => 'sometimes|required|in:active,inactive',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after:publish_date',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($notice->attachment) {
                Storage::disk('public')->delete($notice->attachment);
            }

            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('notices/attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        $notice->update($data);

        return $this->sendResponse($this->transformNotice($notice), 'Notice updated successfully');
    }

    /**
     * Delete a notice
     */
    public function destroy($id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return $this->sendNotFound('Notice not found');
        }

        // Delete attachment if exists
        if ($notice->attachment) {
            Storage::disk('public')->delete($notice->attachment);
        }

        $notice->delete();

        return $this->sendResponse([], 'Notice deleted successfully');
    }

    /**
     * Upload notice attachment
     */
    public function uploadAttachment(Request $request, $id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return $this->sendNotFound('Notice not found');
        }

        $validator = Validator::make($request->all(), [
            'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Delete old attachment
        if ($notice->attachment) {
            Storage::disk('public')->delete($notice->attachment);
        }

        // Upload new attachment
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('notices/attachments', $filename, 'public');
        
        $notice->update(['attachment' => $path]);

        $data = [
            'attachment_url' => asset('storage/' . $path),
            'attachment_name' => $file->getClientOriginalName(),
        ];

        return $this->sendResponse($data, 'Attachment uploaded successfully');
    }

    /**
     * Transform notice data
     */
    private function transformNotice($notice, $includeAuthor = true)
    {
        $data = [
            'id' => $notice->id,
            'title' => $notice->title,
            'content' => $notice->content,
            'priority' => $notice->priority ?? 'normal',
            'priority_text' => $this->getPriorityText($notice->priority ?? 'normal'),
            'status' => $notice->status,
            'attachment' => $notice->attachment ? asset('storage/' . $notice->attachment) : null,
            'attachment_name' => $notice->attachment ? basename($notice->attachment) : null,
            'publish_date' => $notice->publish_date?->format('Y-m-d'),
            'expire_date' => $notice->expire_date?->format('Y-m-d'),
            'is_expired' => $notice->expire_date && $notice->expire_date->isPast(),
            'is_published' => !$notice->publish_date || $notice->publish_date->isPast(),
            'created_at' => $notice->created_at,
            'updated_at' => $notice->updated_at,
        ];

        if ($includeAuthor && $notice->author) {
            $data['author'] = [
                'id' => $notice->author->id,
                'name' => $notice->author->name,
                'email' => $notice->author->email,
            ];
        }

        return $data;
    }

    /**
     * Get priority text
     */
    private function getPriorityText($priority)
    {
        $priorities = [
            'low' => 'কম গুরুত্বপূর্ণ',
            'normal' => 'সাধারণ',
            'high' => 'গুরুত্বপূর্ণ',
            'urgent' => 'জরুরি',
        ];

        return $priorities[$priority] ?? 'সাধারণ';
    }
}
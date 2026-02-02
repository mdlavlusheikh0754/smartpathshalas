<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Traits\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    use ImageCompression;

    /**
     * Display homework list for public (landing page)
     */
    public function index()
    {
        $recentHomework = Homework::getRecent(6);
        $upcomingHomework = Homework::getUpcoming(7);
        
        return view('tenant.homework.index', compact('recentHomework', 'upcomingHomework'));
    }

    /**
     * Display homework management for authenticated users
     */
    public function manage()
    {
        $homework = Homework::orderBy('assigned_date', 'desc')->paginate(10);
        
        return view('tenant.homework.manage', compact('homework'));
    }

    /**
     * Show the form for creating new homework
     */
    public function create()
    {
        return view('tenant.homework.create');
    }

    /**
     * Store a newly created homework
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:100',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'instructions' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store file
            $path = $file->storeAs('homework/attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        // Set teacher ID if authenticated
        if (auth()->check()) {
            $data['teacher_id'] = auth()->id();
        }

        Homework::create($data);

        return redirect()->route('tenant.homework.manage')
                        ->with('success', 'বাড়ির কাজ সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Display the specified homework
     */
    public function show($id)
    {
        $homework = Homework::findOrFail($id);
        
        // If it's an AJAX request, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $homework->id,
                'title' => $homework->title,
                'description' => $homework->description,
                'subject' => $homework->subject,
                'class' => $homework->class,
                'section' => $homework->section,
                'assigned_date' => $homework->assigned_date->format('d M, Y'),
                'due_date' => $homework->due_date->format('d M, Y'),
                'status' => $homework->getStatusText(),
                'status_color' => $homework->getStatusColor(),
                'instructions' => $homework->instructions,
                'attachment_url' => $homework->getAttachmentUrl(),
                'is_overdue' => $homework->isOverdue(),
                'days_remaining' => $homework->due_date->diffForHumans()
            ]);
        }
        
        return view('tenant.homework.show', compact('homework'));
    }

    /**
     * Show the form for editing homework
     */
    public function edit($id)
    {
        $homework = Homework::findOrFail($id);
        
        return view('tenant.homework.edit', compact('homework'));
    }

    /**
     * Update the specified homework
     */
    public function update(Request $request, $id)
    {
        $homework = Homework::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:100',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'status' => 'required|in:active,completed,overdue,inactive',
            'instructions' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($homework->attachment) {
                Storage::disk('public')->delete($homework->attachment);
            }

            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $path = $file->storeAs('homework/attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        $homework->update($data);

        return redirect()->route('tenant.homework.manage')
                        ->with('success', 'বাড়ির কাজ সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified homework
     */
    public function destroy($id)
    {
        $homework = Homework::findOrFail($id);

        // Delete attachment file if exists
        if ($homework->attachment) {
            Storage::disk('public')->delete($homework->attachment);
        }

        $homework->delete();

        return redirect()->route('tenant.homework.manage')
                        ->with('success', 'বাড়ির কাজ সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Get homework details for AJAX (modal)
     */
    public function getDetails($id)
    {
        $homework = Homework::findOrFail($id);
        
        return response()->json([
            'id' => $homework->id,
            'title' => $homework->title,
            'description' => $homework->description,
            'subject' => $homework->subject,
            'class' => $homework->class,
            'section' => $homework->section,
            'assigned_date' => $homework->assigned_date->format('d M, Y'),
            'due_date' => $homework->due_date->format('d M, Y'),
            'status' => $homework->getStatusText(),
            'status_color' => $homework->getStatusColor(),
            'instructions' => $homework->instructions,
            'attachment_url' => $homework->getAttachmentUrl(),
            'is_overdue' => $homework->isOverdue(),
            'days_remaining' => $homework->due_date->diffForHumans()
        ]);
    }

    /**
     * Get homework by class (AJAX)
     */
    public function getByClass(Request $request)
    {
        $class = $request->get('class');
        $section = $request->get('section');

        $homework = Homework::getByClass($class, $section);

        return response()->json($homework);
    }

    /**
     * Get subjects for homework (AJAX)
     */
    public function getSubjects(Request $request)
    {
        $subjects = \App\Models\Subject::active()
            ->orderBy('name')
            ->get(['id', 'name', 'name_en', 'name_bn']);

        return response()->json([
            'success' => true,
            'subjects' => $subjects
        ]);
    }
}

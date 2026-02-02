<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Complaint::query();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('complaint_type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('complainant_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->latest()->paginate(10);
        
        $stats = [
            'total' => Complaint::count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'urgent' => Complaint::where('priority', 'urgent')->count(),
        ];

        return view('tenant.complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.complaints.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:255',
            'complainant_type' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'complaint_type' => 'required|string',
            'priority' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'expected_solution' => 'nullable|string',
            'is_anonymous' => 'nullable|boolean',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaints/attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }
        }

        $complaint = Complaint::create([
            'complainant_name' => $validated['complainant_name'],
            'complainant_type' => $validated['complainant_type'],
            'complainant_id' => auth()->id(), // Set if logged in
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'complaint_type' => $validated['complaint_type'],
            'priority' => $validated['priority'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'expected_solution' => $validated['expected_solution'],
            'is_anonymous' => $request->has('is_anonymous'),
            'attachments' => $attachments,
            'status' => 'new',
        ]);

        return redirect()->route('tenant.complaints.index')
            ->with('success', 'আপনার অভিযোগটি সফলভাবে জমা দেওয়া হয়েছে।');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        return view('tenant.complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);
        return view('tenant.complaints.edit', compact('complaint'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string',
            'priority' => 'required|string',
            'resolution_notes' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'resolution_notes' => $validated['resolution_notes'],
        ];

        if ($validated['status'] === 'resolved' && $complaint->status !== 'resolved') {
            $updateData['resolved_by'] = auth()->id();
            $updateData['resolved_at'] = now();
        }

        $complaint->update($updateData);

        return redirect()->route('tenant.complaints.index')
            ->with('success', 'অভিযোগের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        
        // Delete attachments if any
        if ($complaint->attachments) {
            foreach ($complaint->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $complaint->delete();

        return redirect()->route('tenant.complaints.index')
            ->with('success', 'অভিযোগটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

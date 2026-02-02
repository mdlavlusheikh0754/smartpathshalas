<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('tenant.notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:active,inactive',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:publish_date',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('notices', 'public');
            $validated['attachment'] = $path;
        }

        $validated['author_id'] = auth()->id();

        $notice = Notice::create($validated);

        // Send Notifications
        try {
            $notificationSettings = \App\Models\NotificationSetting::getSettings();
            if ($notificationSettings->push_notice) {
                $students = \App\Models\Student::where('status', 'active')->get();
                $guardians = \App\Models\Guardian::where('status', 'active')->get();

                foreach ($students as $student) {
                    \App\Models\Notification::create([
                        'notifiable_id' => $student->id,
                        'notifiable_type' => \App\Models\Student::class,
                        'title' => 'নতুন নোটিশ: ' . $notice->title,
                        'message' => mb_substr(strip_tags($notice->content), 0, 100) . '...',
                        'type' => 'new_notice',
                    ]);
                }

                foreach ($guardians as $guardian) {
                    \App\Models\Notification::create([
                        'notifiable_id' => $guardian->id,
                        'notifiable_type' => \App\Models\Guardian::class,
                        'title' => 'নতুন নোটিশ: ' . $notice->title,
                        'message' => mb_substr(strip_tags($notice->content), 0, 100) . '...',
                        'type' => 'new_notice',
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Notice Notification Failed: ' . $e->getMessage());
        }

        return redirect()->route('tenant.notices.index')->with('success', 'নোটিশ সফলভাবে প্রকাশ করা হয়েছে');
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);
        return view('tenant.notices.show', compact('notice'));
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return view('tenant.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:active,inactive',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:publish_date',
        ]);

        if ($request->hasFile('attachment')) {
            if ($notice->attachment) {
                Storage::disk('public')->delete($notice->attachment);
            }
            $path = $request->file('attachment')->store('notices', 'public');
            $validated['attachment'] = $path;
        }

        $notice->update($validated);

        return redirect()->route('tenant.notices.index')->with('success', 'নোটিশ সফলভাবে আপডেট করা হয়েছে');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        if ($notice->attachment) {
            Storage::disk('public')->delete($notice->attachment);
        }
        $notice->delete();
        return redirect()->route('tenant.notices.index')->with('success', 'নোটিশ সফলভাবে মুছে ফেলা হয়েছে');
    }
}

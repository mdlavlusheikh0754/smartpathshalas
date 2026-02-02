<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Models\WebsiteSetting;
use App\Models\Notice;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private function getSettings()
    {
        $data = [
            'websiteSettings' => WebsiteSetting::getSettings(),
            'schoolSettings' => SchoolSetting::getSettings(),
        ];

        // Try to get notices, handle missing table gracefully
        try {
            $data['scrollingNotices'] = Notice::active()->published()->notExpired()->orderBy('created_at', 'desc')->take(5)->get();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), "doesn't exist")) {
                $data['scrollingNotices'] = collect();
            } else {
                throw $e;
            }
        }

        return $data;
    }

    public function home()
    {
        return view('tenant.welcome', $this->getSettings());
    }

    public function about()
    {
        return view('tenant.pages.about', $this->getSettings());
    }

    public function administration()
    {
        return view('tenant.pages.administration', $this->getSettings());
    }

    public function academic()
    {
        return view('tenant.pages.academic', $this->getSettings());
    }

    public function notice()
    {
        $data = $this->getSettings();
        $data['notices'] = Notice::active()->published()->notExpired()->orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.pages.notice', $data);
    }

    public function noticeShow($id)
    {
        $data = $this->getSettings();
        $data['notice'] = Notice::active()->published()->findOrFail($id);
        return view('tenant.pages.notice_show', $data);
    }

    public function gallery()
    {
        return view('tenant.pages.gallery', $this->getSettings());
    }

    public function contact()
    {
        return view('tenant.pages.contact', $this->getSettings());
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Store the contact message in session or database
        // For now, we'll just store in session for display
        session()->flash('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।');
        
        // TODO: Send email notification to admin
        // Mail::to(config('mail.from.address'))->send(new ContactMessage($validated));
        
        return redirect()->route('tenant.contact');
    }

    // Administration Sub-pages
    public function committee()
    {
        return view('tenant.pages.administration.committee', $this->getSettings());
    }

    public function staff()
    {
        $data = $this->getSettings();
        
        // Get teachers from database
        $teachers = \App\Models\Teacher::active()->orderBy('name')->get();
        $data['teachers'] = $teachers;
        
        // Get staff from website settings
        $staffFromSettings = $data['websiteSettings']->teachers_staff ?? [];
        $data['staffFromSettings'] = $staffFromSettings;
        
        return view('tenant.pages.administration.staff', $data);
    }

    // Academic Sub-pages
    public function routine()
    {
        $data = $this->getSettings();
        
        // Get all active classes
        $classes = \App\Models\SchoolClass::active()->ordered()->get();
        $data['classes'] = $classes;
        
        return view('tenant.pages.academic.routine', $data);
    }

    public function syllabus()
    {
        $data = $this->getSettings();
        
        // Get all active syllabuses with relationships
        $syllabuses = \App\Models\AcademicSyllabus::with(['schoolClass', 'exam', 'subject'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Group syllabuses by class
        $syllabusesByClass = $syllabuses->groupBy('class_id');
        
        $data['syllabuses'] = $syllabuses;
        $data['syllabusesByClass'] = $syllabusesByClass;
        
        return view('tenant.pages.academic.syllabus', $data);
    }

    public function downloadSyllabus($id)
    {
        try {
            $syllabus = \App\Models\AcademicSyllabus::findOrFail($id);
            
            $filePath = storage_path('app/public/' . $syllabus->file_path);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'ফাইল পাওয়া যায়নি।');
            }
            
            return response()->download($filePath, $syllabus->file_name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ফাইল ডাউনলোড করতে সমস্যা হয়েছে।');
        }
    }

    public function holidays()
    {
        $data = $this->getSettings();
        
        // Get all active holidays
        $holidays = \App\Models\AcademicHoliday::where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->get();
        
        $data['holidays'] = $holidays;
        
        return view('tenant.pages.academic.holidays', $data);
    }

    public function calendar()
    {
        $data = $this->getSettings();
        
        // Get all active calendars
        $calendars = \App\Models\AcademicCalendar::with('academicSession')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $data['calendars'] = $calendars;
        
        return view('tenant.pages.academic.calendar', $data);
    }

    public function downloadCalendar($id)
    {
        try {
            $calendar = \App\Models\AcademicCalendar::findOrFail($id);
            
            $filePath = storage_path('app/public/' . $calendar->file_path);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'ফাইল পাওয়া যায়নি।');
            }
            
            return response()->download($filePath, $calendar->file_name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ফাইল ডাউনলোড করতে সমস্যা হয়েছে।');
        }
    }

    // Admission Pages
    public function admissionApply()
    {
        return view('tenant.pages.admission.apply', $this->getSettings());
    }

    public function admissionRules()
    {
        $data = $this->getSettings();
        
        // Get website settings for admission information
        $websiteSettings = \App\Models\WebsiteSetting::getSettings();
        $data['websiteSettings'] = $websiteSettings;
        
        // Get fee structures for admission fees
        $feeStructures = \App\Models\FeeStructure::where('is_active', true)
            ->orderBy('fee_type')
            ->orderBy('class_name')
            ->get();
        $data['feeStructures'] = $feeStructures;
        
        return view('tenant.pages.admission.rules', $data);
    }

    // Students Login Info Page
    public function studentsInfo()
    {
        return view('tenant.pages.students-info', $this->getSettings());
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AcademicSyllabus;
use App\Models\AcademicHoliday;
use App\Models\AcademicCalendar;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcademicFileController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::active()->ordered()->get();
        $exams = Exam::where('status', '!=', 'cancelled')->get();
        $subjects = Subject::where('is_active', true)->get();
        $sessions = AcademicSession::getActiveSessions();
        
        $syllabuses = AcademicSyllabus::with(['schoolClass', 'exam', 'subject'])->get();
        $holidays = AcademicHoliday::where('is_active', true)->orderBy('start_date')->get();
        $calendars = AcademicCalendar::with('academicSession')->where('is_active', true)->get();
        
        return view('tenant.settings.academic-files', compact('classes', 'exams', 'subjects', 'sessions', 'syllabuses', 'holidays', 'calendars'));
    }

    // Syllabus Management
    public function storeSyllabus(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'exam_id' => 'nullable|exists:exams,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'file' => 'required|file|mimes:pdf|max:10240',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $filePath = $request->file('file')->store('academic/syllabus', 'public');
            
            AcademicSyllabus::create([
                'class_id' => $request->class_id,
                'exam_id' => $request->exam_id,
                'subject_id' => $request->subject_id,
                'file_path' => $filePath,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'description' => $request->description,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'সিলেবাস সফলভাবে আপলোড করা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সিলেবাস আপলোড করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSyllabus($id)
    {
        try {
            $syllabus = AcademicSyllabus::findOrFail($id);
            
            if (Storage::disk('public')->exists($syllabus->file_path)) {
                Storage::disk('public')->delete($syllabus->file_path);
            }
            
            $syllabus->delete();

            return response()->json([
                'success' => true,
                'message' => 'সিলেবাস সফলভাবে মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সিলেবাস মুছতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    // Holiday Management
    public function storeHoliday(Request $request)
    {
        $request->validate([
            'holiday_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            AcademicHoliday::create([
                'holiday_name' => $request->holiday_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ছুটির দিন সফলভাবে যোগ করা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ছুটির দিন যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateHoliday(Request $request, $id)
    {
        $request->validate([
            'holiday_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $holiday = AcademicHoliday::findOrFail($id);
            $holiday->update([
                'holiday_name' => $request->holiday_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ছুটির দিন সফলভাবে আপডেট করা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ছুটির দিন আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteHoliday($id)
    {
        try {
            $holiday = AcademicHoliday::findOrFail($id);
            $holiday->delete();

            return response()->json([
                'success' => true,
                'message' => 'ছুটির দিন সফলভাবে মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ছুটির দিন মুছতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    // Academic Calendar Management
    public function storeCalendar(Request $request)
    {
        $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'file' => 'required|file|mimes:pdf|max:10240',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $filePath = $request->file('file')->store('academic/calendar', 'public');
            
            AcademicCalendar::create([
                'academic_session_id' => $request->academic_session_id,
                'file_path' => $filePath,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'description' => $request->description,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'একাডেমিক ক্যালেন্ডার সফলভাবে আপলোড করা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'একাডেমিক ক্যালেন্ডার আপলোড করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteCalendar($id)
    {
        try {
            $calendar = AcademicCalendar::findOrFail($id);
            
            if (Storage::disk('public')->exists($calendar->file_path)) {
                Storage::disk('public')->delete($calendar->file_path);
            }
            
            $calendar->delete();

            return response()->json([
                'success' => true,
                'message' => 'একাডেমিক ক্যালেন্ডার সফলভাবে মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'একাডেমিক ক্যালেন্ডার মুছতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get data for dropdowns
    public function getSubjectsByClass($classId)
    {
        try {
            $subjects = Subject::where('is_active', true)
                ->whereHas('classes', function ($query) use ($classId) {
                    $query->where('school_class_id', $classId);
                })
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'বিষয় লোড করতে সমস্যা হয়েছে।'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolSetting;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class PublicResultController extends Controller
{
    public function index()
    {
        // Ensure session is started for public access
        if (!session()->isStarted()) {
            session()->start();
        }
        
        if (!session()->has('_token')) {
            session()->regenerateToken();
        }

        // Only show published exams
        $exams = Exam::select('id', 'name', 'exam_type')
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        $websiteSettings = WebsiteSetting::getSettings();
        $schoolSettings = SchoolSetting::getSettings();

        // Prevent caching to avoid CSRF token issues
        return response()->view('tenant.public-result.index', compact('exams', 'websiteSettings', 'schoolSettings'))
                        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
    }

    public function search(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|string', // Can be roll or registration
            'captcha_answer' => 'required|numeric',
            'captcha_correct' => 'required|numeric',
            'board' => 'nullable|string',
            'year' => 'nullable|string',
        ]);

        // Validate captcha
        if ($request->captcha_answer != $request->captcha_correct) {
            return back()->with('error', 'ক্যাপচা সঠিক নয়। অনুগ্রহ করে আবার চেষ্টা করুন।')->withInput();
        }

        $examId = $request->exam_id;
        $studentIdInput = $request->student_id;

        $exam = Exam::findOrFail($examId);
        
        // Check if exam is published
        if (!$exam->is_published) {
            return back()->with('error', 'এই পরীক্ষার ফলাফল এখনও প্রকাশিত হয়নি।')->withInput();
        }

        // Find student by ID or Roll across all classes
        $student = Student::where(function($q) use ($studentIdInput) {
                $q->where('student_id', $studentIdInput)
                  ->orWhere('roll', $studentIdInput)
                  ->orWhere('registration_number', $studentIdInput);
            })
            ->first();

        if (!$student) {
            return back()->with('error', 'শিক্ষার্থী খুঁজে পাওয়া যায়নি। অনুগ্রহ করে সঠিক তথ্য দিন।')->withInput();
        }

        // Find the class for this student
        $class = SchoolClass::where('name', $student->class)
            ->where('section', $student->section)
            ->first();

        if (!$class) {
            return back()->with('error', 'শিক্ষার্থীর ক্লাস খুঁজে পাওয়া যায়নি।')->withInput();
        }

        // Use the exact same logic as admin results page
        $resultController = new \App\Http\Controllers\Tenant\ResultController();
        $request->merge(['exam_id' => $examId, 'class_id' => $class->id]);
        
        $response = $resultController->getResults($request);
        $responseData = $response->getData(true);
        
        if (!$responseData['success']) {
            return back()->with('error', 'ফলাফল লোড করতে সমস্যা হয়েছে।')->withInput();
        }
        
        // Find the specific student's result from the response
        $studentResult = null;
        foreach ($responseData['results'] as $result) {
            if ($result['student']['id'] == $student->id) {
                $studentResult = $result;
                break;
            }
        }
        
        if (!$studentResult) {
            return back()->with('error', 'এই শিক্ষার্থীর ফলাফল পাওয়া যায়নি।')->withInput();
        }

        // Calculate rank for this student
        $rank = $this->calculateRankFromResults($responseData['results'], $student->id);

        // Calculate total marks and GPA from studentResult
        $totalMarks = 0;
        $totalPossible = 0;
        $totalGPA = 0;
        $subjectCount = 0;

        if (isset($studentResult['subjects']) && is_array($studentResult['subjects'])) {
            foreach ($studentResult['subjects'] as $subject) {
                if (isset($subject['obtained'])) {
                    $totalMarks += $subject['obtained'];
                }
                if (isset($subject['total'])) {
                    $totalPossible += $subject['total'];
                }
                if (isset($subject['gpa'])) {
                    $totalGPA += $subject['gpa'];
                    $subjectCount++;
                }
            }
        }

        $overallGPA = $subjectCount > 0 ? round($totalGPA / $subjectCount, 2) : 0;
        $overallGrade = $this->calculateGradeFromGPA($overallGPA);
        $overallResult = $this->calculateResultStatus($overallGPA, $studentResult);

        // Extract subjects for the results table
        $studentResults = isset($studentResult['subjects']) && is_array($studentResult['subjects']) 
            ? $studentResult['subjects'] 
            : [];

        $websiteSettings = WebsiteSetting::getSettings();
        $schoolSettings = SchoolSetting::getSettings();

        return view('tenant.public-result.result-sheet', compact(
            'student', 'exam', 'class', 'studentResult', 'studentResults', 'rank',
            'websiteSettings', 'schoolSettings', 'totalMarks', 'totalPossible',
            'overallGPA', 'overallGrade', 'overallResult'
        ));
    }

    public function download(Request $request)
    {
        $examId = $request->exam_id;
        $classId = $request->class_id;
        $studentId = $request->student_id;

        $exam = Exam::findOrFail($examId);
        
        // Check if exam is published
        if (!$exam->is_published) {
            return back()->with('error', 'এই পরীক্ষার ফলাফল এখনও প্রকাশিত হয়নি।');
        }
        
        $class = SchoolClass::findOrFail($classId);
        $student = Student::findOrFail($studentId);

        // Use the exact same logic as admin results page
        $resultController = new \App\Http\Controllers\Tenant\ResultController();
        $request->merge(['exam_id' => $examId, 'class_id' => $classId]);
        
        $response = $resultController->getResults($request);
        $responseData = $response->getData(true);
        
        if (!$responseData['success']) {
            return back()->with('error', 'ফলাফল লোড করতে সমস্যা হয়েছে।');
        }
        
        // Find the specific student's result from the response
        $studentResult = null;
        foreach ($responseData['results'] as $result) {
            if ($result['student']['id'] == $student->id) {
                $studentResult = $result;
                break;
            }
        }
        
        if (!$studentResult) {
            return back()->with('error', 'এই শিক্ষার্থীর ফলাফল পাওয়া যায়নি।');
        }

        // Calculate rank for this student
        $rank = $this->calculateRankFromResults($responseData['results'], $student->id);

        try {
            // Configure mPDF with proper Bengali font support
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            
            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
                'fontdata' => $fontData + [
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
                'default_font' => 'solaimanlipi',
                'tempDir' => $tempDir
            ]);
            
            $websiteSettings = WebsiteSetting::getSettings();
            $schoolSettings = SchoolSetting::getSettings();
            
            // Prepare logo path for PDF
            $logoPath = null;
            if ($schoolSettings->logo) {
                $logoPath = storage_path('app/public/' . $schoolSettings->logo);
            } elseif ($websiteSettings->logo) {
                $logoPath = storage_path('app/public/' . $websiteSettings->logo);
            }
            
            $html = view('tenant.public-result.result-sheet-pdf', compact(
                'student', 'exam', 'class', 'studentResult', 'rank',
                'websiteSettings', 'schoolSettings', 'logoPath'
            ))->render();
            
            // Write HTML with proper Bengali font support
            $mpdf->WriteHTML($html);
            
            $filename = 'result_sheet_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $student->name) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $exam->name) . '.pdf';
            
            return response($mpdf->Output($filename, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            \Log::error('PDF Download Error: ' . $e->getMessage());
            \Log::error('Font file exists: ' . (file_exists(public_path('fonts/SolaimanLipi.ttf')) ? 'Yes' : 'No'));
            \Log::error('Temp directory exists: ' . (file_exists(storage_path('app/temp')) ? 'Yes' : 'No'));
            return back()->with('error', 'PDF ডাউনলোড করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        }
    }

    private function calculateRankFromResults($allResults, $currentStudentId)
    {
        // Sort results by total marks (descending)
        usort($allResults, function($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'];
        });
        
        // Find rank of current student
        $rank = 1;
        foreach ($allResults as $result) {
            if ($result['student']['id'] == $currentStudentId) {
                return $rank;
            }
            $rank++;
        }
        
        return $rank;
    }

    private function calculateGradeFromGPA($gpa)
    {
        if ($gpa >= 4.0) return 'A+';
        if ($gpa >= 3.75) return 'A';
        if ($gpa >= 3.5) return 'A-';
        if ($gpa >= 3.25) return 'B+';
        if ($gpa >= 3.0) return 'B';
        if ($gpa >= 2.75) return 'B-';
        if ($gpa >= 2.5) return 'C+';
        if ($gpa >= 2.25) return 'C';
        if ($gpa >= 2.0) return 'C-';
        if ($gpa >= 1.75) return 'D+';
        if ($gpa >= 1.5) return 'D';
        return 'F';
    }

    private function calculateResultStatus($overallGPA, $studentResult)
    {
        // Check if student has any F grades
        if (isset($studentResult['subjects']) && is_array($studentResult['subjects'])) {
            foreach ($studentResult['subjects'] as $subject) {
                if (isset($subject['gpa']) && $subject['gpa'] < 1.5) {
                    return 'ফেল';
                }
            }
        }
        
        // If overall GPA is below passing threshold, return fail
        if ($overallGPA < 1.5) {
            return 'ফেল';
        }
        
        return 'পাস';
    }

}

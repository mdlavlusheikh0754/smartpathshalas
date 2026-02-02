    /**
     * Calculate results with monthly marks
     */
    public function calculateWithMonthly(Request $request)
    {
        try {
            $examId = $request->exam_id;
            $classId = $request->class_id;
            $selectedMonths = $request->monthly_exam_ids ?? $request->months;
            
            if (!$examId || !$classId || empty($selectedMonths)) {
                return response()->json([
                    'success' => false,
                    'message' => 'সব তথ্য প্রদান করুন'
                ], 400);
            }
            
            // Get class details
            $class = SchoolClass::findOrFail($classId);
            
            // Get the term exam
            $termExam = Exam::find($examId);
            
            if (!$termExam) {
                return response()->json([
                    'success' => false,
                    'message' => 'নির্বাচিত সামায়িক পরীক্ষা পাওয়া যায়নি'
                ], 404);
            }
            
            // Get monthly exams for selected months
            $monthlyExams = Exam::where('exam_type', 'monthly')
                               ->whereIn('month', $selectedMonths)
                               ->where(function($query) use ($classId) {
                                   $query->whereJsonContains('classes', (int)$classId)
                                         ->orWhereJsonContains('classes', (string)$classId)
                                         ->orWhereNull('classes')
                                         ->orWhere('classes', '[]')
                                         ->orWhere('classes', 'null');
                               })
                               ->get();
            
            if ($monthlyExams->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'নির্বাচিত মাসের কোন মাসিক পরীক্ষা পাওয়া যায়নি'
                ], 404);
            }
            
            // Get all students in this class
            $students = Student::where('class', $class->name)
                              ->where('section', $class->section)
                              ->where('status', 'active')
                              ->get();
            
            // Get all subjects for this class
            $subjects = Subject::where('class_id', $classId)->get();
            
            DB::beginTransaction();
            
            $studentsUpdated = 0;
            $totalUpdates = 0;
            
            foreach ($students as $student) {
                $studentHasUpdate = false;
                
                foreach ($subjects as $subject) {
                    // Get term exam subject configuration
                    $termExamSubject = ExamSubject::where('exam_id', $termExam->id)
                                                 ->where('subject_id', $subject->id)
                                                 ->first();
                    
                    if (!$termExamSubject) {
                        continue; // Skip if subject not in term exam
                    }
                    
                    // Get term exam marks
                    $termResult = ExamResult::where('exam_id', $termExam->id)
                                          ->where('subject_id', $subject->id)
                                          ->where('student_id', $student->id)
                                          ->first();
                    
                    // Get monthly exam marks for this subject
                    $monthlyMarks = [];
                    foreach ($monthlyExams as $monthlyExam) {
                        $monthlyResult = ExamResult::where('exam_id', $monthlyExam->id)
                                                  ->where('subject_id', $subject->id)
                                                  ->where('student_id', $student->id)
                                                  ->first();
                        
                        if ($monthlyResult && $monthlyResult->obtained_marks !== null && $monthlyResult->status !== 'absent') {
                            $monthlyMarks[] = $monthlyResult->obtained_marks;
                        }
                    }
                    
                    // If no monthly marks found, skip
                    if (empty($monthlyMarks)) {
                        continue;
                    }
                    
                    // Calculate average of monthly marks
                    $monthlyAverage = round(array_sum($monthlyMarks) / count($monthlyMarks), 2);
                    
                    // Create or update term result
                    if (!$termResult) {
                        // Create new result with monthly average only
                        $finalMarks = $monthlyAverage;
                        $totalMarks = $termExamSubject->total_marks;
                        
                        ExamResult::create([
                            'exam_id' => $termExam->id,
                            'subject_id' => $subject->id,
                            'student_id' => $student->id,
                            'obtained_marks' => $finalMarks,
                            'total_marks' => $totalMarks,
                            'status' => ($finalMarks / $totalMarks * 100) >= 33 ? 'pass' : 'fail'
                        ]);
                    } else {
                        // Add monthly average to existing term marks
                        $finalMarks = $termResult->obtained_marks + $monthlyAverage;
                        $totalMarks = $termExamSubject->total_marks;
                        
                        $percentage = ($finalMarks / $totalMarks) * 100;
                        $status = $percentage >= 33 ? 'pass' : 'fail';
                        
                        $termResult->update([
                            'obtained_marks' => $finalMarks,
                            'status' => $status
                        ]);
                    }
                    
                    $totalUpdates++;
                    $studentHasUpdate = true;
                }
                
                if ($studentHasUpdate) {
                    $studentsUpdated++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'মাসিক নম্বর সফলভাবে যোগ করা হয়েছে',
                'students_updated' => $studentsUpdated,
                'total_updates' => $totalUpdates,
                'months_count' => count($selectedMonths),
                'monthly_exams_found' => $monthlyExams->count()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error calculating with monthly marks:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'মাসিক নম্বর যোগ করতে সমস্যা হয়েছে',
                'error' => $e->getMessage()
            ], 500);
        }
    }

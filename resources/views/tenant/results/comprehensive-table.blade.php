@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">সামগ্রিক ফলাফল তালিকা</h1>
                <p class="text-gray-600 mt-1">বিস্তারিত ফলাফল তালিকা দেখুন এবং প্রিন্ট করুন</p>
            </div>
            <div class="flex gap-3">
                <button onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </button>
                
                <button onclick="printTable()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    প্রিন্ট করুন
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষা নির্বাচন করুন</label>
                    <select id="examSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">পরীক্ষা নির্বাচন করুন</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস নির্বাচন করুন</label>
                    <select id="classSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">ক্লাস নির্বাচন করুন</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="loadResults()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                        ফলাফল লোড করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div id="resultsContainer" class="hidden">
            <!-- School Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 text-center" id="schoolHeader">
                <h2 class="text-2xl font-bold text-gray-800 mb-2" id="schoolName">ইকরা নূরানিয়া একাডেমি</h2>
                <p class="text-gray-600 mb-1" id="schoolAddress">ঢাকা, বাংলাদেশ</p>
                <p class="text-lg font-semibold text-blue-600" id="examTitle">বার্ষিক পরীক্ষা - ২০২৬</p>
                <p class="text-gray-600" id="classInfo">শ্রেণী: পঞ্চম</p>
            </div>

            <!-- Comprehensive Results Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full border-collapse" id="resultsTable">
                        <thead class="bg-gray-50">
                            <tr id="headerRow1">
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">ক্রম</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">পরীক্ষার্থীর নাম</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">রোল</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">রেজি.</th>
                                <th id="subjectsColspan" colspan="8" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700 bg-blue-50">বিষয়ভিত্তিক প্রাপ্ত নম্বর</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">মোট নম্বর</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">শতাংশ</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">গ্রেড</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">ফলাফল</th>
                                <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center text-sm font-bold text-gray-700">মন্তব্য</th>
                            </tr>
                            <tr id="headerRow2">
                                <!-- Subject headers will be populated dynamically -->
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                            <!-- Results will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">পরিসংখ্যান</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="totalStudents">০</div>
                        <div class="text-sm text-gray-600">মোট পরীক্ষার্থী</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="passedStudents">০</div>
                        <div class="text-sm text-gray-600">উত্তীর্ণ</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600" id="failedStudents">০</div>
                        <div class="text-sm text-gray-600">অনুত্তীর্ণ</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600" id="passPercentage">০%</div>
                        <div class="text-sm text-gray-600">উত্তীর্ণের হার</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">ফলাফল লোড হচ্ছে...</p>
        </div>

        <!-- No Results State -->
        <div id="noResultsState" class="hidden text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">কোনো ফলাফল পাওয়া যায়নি</h3>
            <p class="text-gray-600">নির্বাচিত পরীক্ষা এবং ক্লাসের জন্য কোনো ফলাফল পাওয়া যায়নি।</p>
        </div>
    </div>
</div>

<script>
// Bengali number conversion
function toBengaliNumber(num) {
    const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return num.toString().split('').map(digit => bengaliNumbers[parseInt(digit)] || digit).join('');
}

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadExams();
    loadClasses();
});

// Load exams
function loadExams() {
    fetch('/admin/results/api/exams')
        .then(response => response.json())
        .then(data => {
            const examSelect = document.getElementById('examSelect');
            examSelect.innerHTML = '<option value="">পরীক্ষা নির্বাচন করুন</option>';
            
            if (data.success && data.exams) {
                data.exams.forEach(exam => {
                    const option = document.createElement('option');
                    option.value = exam.id;
                    option.textContent = exam.name;
                    examSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading exams:', error);
        });
}

// Load classes
function loadClasses() {
    fetch('/admin/results/api/classes')
        .then(response => response.json())
        .then(data => {
            const classSelect = document.getElementById('classSelect');
            classSelect.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
            
            if (data.success && data.classes) {
                data.classes.forEach(cls => {
                    const option = document.createElement('option');
                    option.value = cls.id;
                    option.textContent = `${cls.name} - ${cls.section}`;
                    classSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading classes:', error);
        });
}

// Load results
function loadResults() {
    const examId = document.getElementById('examSelect').value;
    const classId = document.getElementById('classSelect').value;
    
    if (!examId || !classId) {
        alert('পরীক্ষা এবং ক্লাস নির্বাচন করুন');
        return;
    }
    
    // Show loading state
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('resultsContainer').classList.add('hidden');
    document.getElementById('noResultsState').classList.add('hidden');
    
    fetch(`/results/api/results?exam_id=${examId}&class_id=${classId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingState').classList.add('hidden');
            
            if (data.success && data.results && data.results.length > 0) {
                displayResults(data);
                document.getElementById('resultsContainer').classList.remove('hidden');
            } else {
                document.getElementById('noResultsState').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading results:', error);
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('noResultsState').classList.remove('hidden');
        });
}

// Display results in table
function displayResults(data) {
    const { exam, class: classInfo, results, subjects } = data;
    
    console.log('Display Results Data:', data); // Debug log
    
    // Update header information
    document.getElementById('examTitle').textContent = `${exam.name} - ২০২৬`;
    document.getElementById('classInfo').textContent = `শ্রেণী: ${classInfo.name} - ${classInfo.section}`;
    
    // Update subjects colspan
    const subjectsColspan = document.getElementById('subjectsColspan');
    subjectsColspan.colSpan = subjects.length;
    
    // Create subject headers in second row
    const headerRow2 = document.getElementById('headerRow2');
    headerRow2.innerHTML = '';
    
    subjects.forEach(subject => {
        const th = document.createElement('th');
        th.className = 'border border-gray-300 px-2 py-2 text-center text-xs font-bold text-gray-700 bg-blue-50';
        th.textContent = subject.name.length > 8 ? subject.name.substring(0, 8) + '...' : subject.name;
        th.title = subject.name; // Full name on hover
        headerRow2.appendChild(th);
    });
    
    // Create result rows
    const tbody = document.getElementById('resultsTableBody');
    tbody.innerHTML = '';
    
    let totalStudents = 0;
    let passedStudents = 0;
    let failedStudents = 0;
    
    results.forEach((result, index) => {
        totalStudents++;
        if (result.overall_result === 'পাস') {
            passedStudents++;
        } else {
            failedStudents++;
        }
        
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        // Serial number
        const serialCell = document.createElement('td');
        serialCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm';
        serialCell.textContent = toBengaliNumber(index + 1);
        row.appendChild(serialCell);
        
        // Student name
        const nameCell = document.createElement('td');
        nameCell.className = 'border border-gray-300 px-3 py-2 text-sm';
        nameCell.textContent = result.student.name;
        row.appendChild(nameCell);
        
        // Roll number
        const rollCell = document.createElement('td');
        rollCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm';
        rollCell.textContent = toBengaliNumber(result.student.roll || '');
        row.appendChild(rollCell);
        
        // Registration number
        const regCell = document.createElement('td');
        regCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm';
        regCell.textContent = toBengaliNumber(result.student.registration_number || '');
        row.appendChild(regCell);
        
        // Subject marks - ensure we match the subjects order
        subjects.forEach(subject => {
            const subjectResult = result.subjects.find(s => 
                s.subject_id == subject.id || 
                s.subject_name === subject.name ||
                (s.subject_id === 'group_' + subject.id)
            );
            
            const markCell = document.createElement('td');
            markCell.className = 'border border-gray-300 px-2 py-2 text-center text-sm';
            
            if (subjectResult) {
                if (!subjectResult.is_present) {
                    markCell.textContent = 'অনুপস্থিত';
                    markCell.className += ' text-red-600 font-medium';
                } else if (subjectResult.obtained_marks !== null && subjectResult.obtained_marks !== undefined) {
                    markCell.textContent = toBengaliNumber(subjectResult.obtained_marks);
                    // Color code based on pass/fail
                    const percentage = subjectResult.percentage || ((subjectResult.obtained_marks / (subjectResult.total_marks || 100)) * 100);
                    if (percentage >= 33) {
                        markCell.className += ' text-green-600';
                    } else {
                        markCell.className += ' text-red-600';
                    }
                } else {
                    markCell.textContent = '-';
                }
            } else {
                markCell.textContent = '-';
                markCell.className += ' text-gray-400';
            }
            
            row.appendChild(markCell);
        });
        
        // Total marks
        const totalCell = document.createElement('td');
        totalCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm font-medium';
        totalCell.textContent = result.total_marks > 0 ? toBengaliNumber(result.total_marks) : '-';
        row.appendChild(totalCell);
        
        // Percentage
        const percentageCell = document.createElement('td');
        percentageCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm';
        percentageCell.textContent = result.percentage > 0 ? toBengaliNumber(result.percentage.toFixed(2)) + '%' : '-';
        row.appendChild(percentageCell);
        
        // Grade
        const gradeCell = document.createElement('td');
        gradeCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm font-bold';
        gradeCell.textContent = result.overall_grade || '-';
        
        // Color code grades
        if (result.overall_grade === 'A+') {
            gradeCell.className += ' text-green-600';
        } else if (result.overall_grade === 'A') {
            gradeCell.className += ' text-blue-600';
        } else if (result.overall_grade === 'B') {
            gradeCell.className += ' text-yellow-600';
        } else if (result.overall_grade === 'C') {
            gradeCell.className += ' text-orange-600';
        } else if (result.overall_grade === 'D') {
            gradeCell.className += ' text-red-600';
        } else {
            gradeCell.className += ' text-red-600';
        }
        
        row.appendChild(gradeCell);
        
        // Result
        const resultCell = document.createElement('td');
        resultCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm font-medium';
        resultCell.textContent = result.overall_result || '-';
        
        if (result.overall_result === 'পাস') {
            resultCell.className += ' text-green-600';
        } else {
            resultCell.className += ' text-red-600';
        }
        
        row.appendChild(resultCell);
        
        // Comments
        const commentCell = document.createElement('td');
        commentCell.className = 'border border-gray-300 px-3 py-2 text-center text-sm';
        commentCell.textContent = result.comments || '-';
        row.appendChild(commentCell);
        
        tbody.appendChild(row);
    });
    
    // Update statistics
    const passPercentage = totalStudents > 0 ? ((passedStudents / totalStudents) * 100).toFixed(1) : 0;
    
    document.getElementById('totalStudents').textContent = toBengaliNumber(totalStudents);
    document.getElementById('passedStudents').textContent = toBengaliNumber(passedStudents);
    document.getElementById('failedStudents').textContent = toBengaliNumber(failedStudents);
    document.getElementById('passPercentage').textContent = toBengaliNumber(passPercentage) + '%';
}

// Print function
function printTable() {
    const printContent = document.getElementById('resultsContainer').innerHTML;
    const originalContent = document.body.innerHTML;
    
    // Create print styles
    const printStyles = `
        <style>
            @media print {
                body { font-family: 'SolaimanLipi', Arial, sans-serif; }
                table { border-collapse: collapse; width: 100%; font-size: 12px; }
                th, td { border: 1px solid #000; padding: 4px; text-align: center; }
                .bg-gray-50 { background-color: #f9f9f9 !important; }
                .bg-blue-50 { background-color: #eff6ff !important; }
                .text-green-600 { color: #059669 !important; }
                .text-red-600 { color: #dc2626 !important; }
                .text-blue-600 { color: #2563eb !important; }
                .text-yellow-600 { color: #d97706 !important; }
                .text-orange-600 { color: #ea580c !important; }
                .text-purple-600 { color: #9333ea !important; }
            }
        </style>
    `;
    
    document.body.innerHTML = printStyles + printContent;
    window.print();
    document.body.innerHTML = originalContent;
    
    // Reload the page to restore functionality
    location.reload();
}
</script>

<style>
/* Custom styles for better table appearance */
#resultsTable {
    font-size: 14px;
}

#resultsTable th {
    background-color: #f8fafc;
    font-weight: 600;
}

#resultsTable td {
    white-space: nowrap;
}

/* Print styles */
@media print {
    body {
        font-family: 'SolaimanLipi', Arial, sans-serif;
    }
    
    .no-print {
        display: none !important;
    }
    
    #resultsTable {
        font-size: 12px;
    }
    
    #resultsTable th,
    #resultsTable td {
        border: 1px solid #000 !important;
        padding: 4px !important;
    }
}
</style>
@endsection

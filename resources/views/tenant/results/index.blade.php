@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    @media print {
        /* Hide navigation and buttons when printing */
        .no-print, nav, header, .bg-gray-600, .bg-purple-600, .bg-gradient-to-r.from-green-600 {
            display: none !important;
        }
        
        /* Optimize table for printing */
        body {
            background: white !important;
        }
        
        #resultsSection {
            box-shadow: none !important;
            border: 1px solid #ddd;
        }
        
        table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        /* Make sticky columns normal for print */
        .sticky {
            position: static !important;
        }
        
        /* Adjust font sizes for print */
        .text-3xl {
            font-size: 1.5rem !important;
        }
        
        .text-2xl {
            font-size: 1.25rem !important;
        }
    }
    
    /* Smooth scroll for better UX */
    .overflow-x-auto {
        scroll-behavior: smooth;
    }
    
    /* Enhance sticky positioning */
    .sticky {
        position: sticky;
        background: inherit;
    }
</style>
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">পরীক্ষার ফলাফল</h1>
                <p class="text-gray-600 mt-1">পরীক্ষার ফলাফল দেখুন এবং প্রিন্ট করুন</p>
            </div>
            <div class="flex gap-3">
                <button onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </button>
                
                <button onclick="openCalculateResultModal()" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Calculate Result
                </button>
                
                <!-- Export Dropdown -->
                <div id="exportDropdown" class="relative hidden" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="bg-white border border-gray-200 text-gray-600 hover:text-gray-800 hover:border-gray-300 px-4 py-2 rounded-lg font-medium shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        এক্সপোর্ট
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border border-gray-100 overflow-hidden">
                        <a href="#" id="exportPdfLink" class="js-export-link block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors duration-200 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            পিডিএফ (PDF)
                        </a>
                        <a href="#" id="exportExcelLink" class="js-export-link block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-colors duration-200 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            এক্সেল (Excel)
                        </a>
                        <a href="#" id="exportDocxLink" class="js-export-link block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-800 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ডকএক্স (DOCX)
                        </a>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Exam and Class Selection -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">পরীক্ষা নির্বাচন করুন *</label>
                    <select id="examSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadResults()">
                        <option value="">পরীক্ষা নির্বাচন করুন</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ক্লাস নির্বাচন করুন *</label>
                    <select id="classSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadResults()">
                        <option value="">ক্লাস নির্বাচন করুন</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden text-center py-8">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-gray-500 bg-white">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ফলাফল লোড হচ্ছে...
            </div>
        </div>

        <!-- Professional Results Table -->
        <div id="resultsSection" class="bg-white rounded-2xl shadow-xl overflow-hidden hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-6 py-5">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white">পরীক্ষার ফলাফল</h3>
                    <div class="text-indigo-100 mt-2">
                        <span id="examInfo" class="font-semibold">-</span>
                        <span class="text-indigo-200 mx-2">|</span>
                        <span id="classInfo" class="font-semibold">-</span>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Results Summary Cards -->
                <div id="resultsSummary" class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6 hidden">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border-l-4 border-blue-500">
                        <div class="text-3xl font-bold text-blue-700" id="totalStudents">০</div>
                        <div class="text-sm text-blue-600 font-medium mt-1">মোট শিক্ষার্থী</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border-l-4 border-green-500">
                        <div class="text-3xl font-bold text-green-700" id="passedStudents">০</div>
                        <div class="text-sm text-green-600 font-medium mt-1">পাস</div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border-l-4 border-red-500">
                        <div class="text-3xl font-bold text-red-700" id="failedStudents">০</div>
                        <div class="text-sm text-red-600 font-medium mt-1">ফেল</div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-4 border-l-4 border-yellow-500">
                        <div class="text-3xl font-bold text-yellow-700" id="averagePercentage">০%</div>
                        <div class="text-sm text-yellow-600 font-medium mt-1">গড় শতাংশ</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border-l-4 border-purple-500">
                        <div class="text-3xl font-bold text-purple-700" id="highestMarks">০</div>
                        <div class="text-sm text-purple-600 font-medium mt-1">সর্বোচ্চ নম্বর</div>
                    </div>
                </div>

                <!-- Professional Results Table -->
                <div class="overflow-x-auto rounded-xl border-2 border-gray-200 shadow-lg">
                    <table class="w-full" style="min-width: max-content;">
                        <thead>
                            <tr id="resultsTableHeader" class="bg-gradient-to-r from-indigo-600 to-purple-600">
                                <th class="px-4 py-3 text-center text-xs font-bold text-white border-r-2 border-indigo-400 sticky left-0 bg-indigo-600 z-20">রোল</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white border-r-2 border-indigo-400 sticky left-20 bg-indigo-600 z-20">শিক্ষার্থীর তথ্য<br><span class="text-xs font-normal text-indigo-100">Student Information</span></th>
                                <!-- Subject columns will be added dynamically here -->
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div id="emptyState" class="text-center py-12 hidden">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-700">কোনো ফলাফল পাওয়া যায়নি</h3>
                    <p class="mt-2 text-sm text-gray-500">নির্বাচিত পরীক্ষা এবং ক্লাসের জন্য কোনো ফলাফল ডাটা নেই।</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calculate Result Modal -->
<div id="calculateResultModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-purple-700">Calculate Result - মাসিক পরীক্ষার নম্বর যোগ করুন</h3>
                <p class="text-gray-600 mt-1">সামায়িক পরীক্ষায় মাসিক পরীক্ষার গড় নম্বর যোগ করুন</p>
            </div>
            <button onclick="closeCalculateResultModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-6 p-4 bg-purple-50 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-purple-800">
                    <p class="font-medium mb-1">মাসিক নম্বর কীভাবে যোগ হবে:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>নির্বাচিত মাসগুলোর গড় (Average) নম্বর হিসাব করা হবে</li>
                        <li>১টির বেশি মাস নির্বাচন করলে সব মাসের গড় নেওয়া হবে</li>
                        <li>সামায়িক পরীক্ষার নম্বরের সাথে মাসিক গড় যোগ হবে</li>
                        <li>চূড়ান্ত ফলাফল আপডেট হবে</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Term Exam Selection -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">সামায়িক পরীক্ষা নির্বাচন করুন *</label>
            <select id="termExamSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="onTermExamChange()">
                <option value="">সামায়িক পরীক্ষা নির্বাচন করুন</option>
            </select>
        </div>

        <!-- Class Selection -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">ক্লাস নির্বাচন করুন *</label>
            <select id="termClassSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="loadAvailableMonthlyExamsForCalculate()">
                <option value="">ক্লাস নির্বাচন করুন</option>
            </select>
        </div>

        <!-- Month Selection -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-3">মাস সিলেক্ট করুন (একাধিক মাস নির্বাচন করতে পারবেন):</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="monthsCheckboxes">
                <!-- Months will be populated by JavaScript -->
            </div>
        </div>

        <!-- Selected Months Marks Preview -->
        <div id="marksPreview" class="mb-6 hidden">
            <h4 class="text-sm font-bold text-gray-700 mb-3">নির্বাচিত মাসের নম্বর:</h4>
            <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                <div id="previewContent" class="space-y-2 text-sm">
                    <!-- Preview will be populated -->
                </div>
            </div>
        </div>

        <div id="availableMonthlyExamsCalculate" class="mb-6 hidden">
            <h4 class="text-sm font-bold text-gray-700 mb-3">উপলব্ধ মাসিক পরীক্ষা:</h4>
            <div id="monthlyExamsListCalculate" class="space-y-2">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="calculateWithMonthlyMarks()" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                হিসাব করুন
            </button>
            <button onclick="closeCalculateResultModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition-colors">
                বাতিল করুন
            </button>
        </div>
    </div>
</div>

<script>
// Force browser to reload - Cache buster version: {{ time() }}
console.log('Results page loaded at: ' + new Date().toISOString());
let currentResults = null;
let currentExam = null;
let currentClass = null;
let subjects = [];
let allExams = [];
let allClasses = [];
let selectedMonths = [];
let selectedTermExam = null;
let selectedTermClass = null;
let availableMonths = [];

const bengaliMonths = [
    'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
    'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'
];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadExams();
    loadClasses();
    loadAllExamsForCalculate();
});

// Load exams
async function loadExams() {
    try {
        const response = await fetch('/admin/results/api/exams');
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'পরীক্ষা লোড করতে ব্যর্থ');
        }
        
        allExams = data.exams; // Store all exams
        
        const examSelect = document.getElementById('examSelect');
        if (examSelect) {
            examSelect.innerHTML = '<option value="">পরীক্ষা নির্বাচন করুন</option>';
            
            data.exams.forEach(exam => {
                const option = document.createElement('option');
                option.value = exam.id;
                option.textContent = exam.name;
                examSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading exams:', error);
        showErrorModal('পরীক্ষার তথ্য লোড করতে সমস্যা হয়েছে: ' + error.message);
    }
}

// Load classes
async function loadClasses() {
    try {
        const response = await fetch('/admin/results/api/classes');
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'ক্লাস লোড করতে ব্যর্থ');
        }
        
        allClasses = data.classes; // Store all classes
        
        const classSelect = document.getElementById('classSelect');
        if (classSelect) {
            classSelect.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
            
            data.classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.full_name;
                classSelect.appendChild(option);
            });
        }
        
        // Also populate term class select
        const termClassSelect = document.getElementById('termClassSelect');
        if (termClassSelect) {
            termClassSelect.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
            
            data.classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.full_name;
                termClassSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading classes:', error);
        showErrorModal('ক্লাসের তথ্য লোড করতে সমস্যা হয়েছে: ' + error.message);
    }
}

// Load results
async function loadResults() {
    const examId = document.getElementById('examSelect').value;
    const classId = document.getElementById('classSelect').value;
    
    if (!examId || !classId) {
        document.getElementById('resultsSection').classList.add('hidden');
        document.getElementById('exportDropdown').classList.add('hidden');
        return;
    }
    
    try {
        document.getElementById('loadingIndicator').classList.remove('hidden');
        document.getElementById('resultsSection').classList.add('hidden');
        
        const response = await fetch(`/admin/results/api/results?exam_id=${examId}&class_id=${classId}&_t=${Date.now()}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'ফলাফল লোড করতে ব্যর্থ');
        }
        
        currentResults = data.results;
        currentExam = data.exam;
        currentClass = data.class;
        subjects = data.subjects;
        
        // Debug: Log first student's data
        if (currentResults.length > 0) {
            console.log('=== First Student Data ===');
            console.log('Student:', currentResults[0].student.name);
            console.log('Total marks:', currentResults[0].total_marks);
            console.log('Monthly marks:', currentResults[0].monthly_marks);
            console.log('Subjects:', currentResults[0].subjects.map(s => `${s.subject_name}: ${s.obtained_marks}`));
        }
        
        // Update exam and class info
        document.getElementById('examInfo').textContent = currentExam.name;
        document.getElementById('classInfo').textContent = currentClass.full_name;
        
        // Render results
        renderResults();
        
        document.getElementById('resultsSection').classList.remove('hidden');
        document.getElementById('exportDropdown').classList.remove('hidden');
        
        // Update export links
        updateExportLinks();
        
        // Calculate Result button is always visible
        
    } catch (error) {
        console.error('Error loading results:', error);
        showErrorModal('ফলাফল লোড করতে সমস্যা হয়েছে: ' + error.message);
    } finally {
        document.getElementById('loadingIndicator').classList.add('hidden');
    }
}

// Render results table
function renderResults() {
    if (!currentResults || !subjects) return;
    
    // Check if this is a monthly exam
    const isMonthlyExam = currentExam && currentExam.exam_type === 'monthly';
    
    // Hide/Show monthly-related columns based on exam type
    const monthlyAvgHeader = document.getElementById('monthlyAvgHeader');
    const finalTotalHeader = document.getElementById('finalTotalHeader');
    const finalRankHeader = document.getElementById('finalRankHeader');
    
    if (isMonthlyExam) {
        if (monthlyAvgHeader) monthlyAvgHeader.style.display = 'none';
        if (finalTotalHeader) finalTotalHeader.style.display = 'none';
        if (finalRankHeader) finalRankHeader.style.display = 'none';
    } else {
        if (monthlyAvgHeader) monthlyAvgHeader.style.display = '';
        if (finalTotalHeader) finalTotalHeader.style.display = '';
        if (finalRankHeader) finalRankHeader.style.display = '';
    }
    
    // Update summary
    updateResultsSummary();
    
    // Update table header
    updateTableHeader();
    
    // Update table body
    updateTableBody(isMonthlyExam);
}

// Update results summary
function updateResultsSummary() {
    const totalStudents = currentResults.length;
    const passedStudents = currentResults.filter(r => r.overall_result === 'পাস').length;
    const failedStudents = totalStudents - passedStudents;
    
    let totalPercentage = 0;
    let studentsWithResults = 0;
    let highestMarks = 0;
    
    currentResults.forEach(result => {
        if (result.percentage > 0) {
            totalPercentage += result.percentage;
            studentsWithResults++;
        }
        
        // Calculate total with monthly for highest marks
        const totalWithMonthly = parseFloat(result.total_marks || 0) + parseFloat(result.monthly_marks || 0);
        if (totalWithMonthly > highestMarks) {
            highestMarks = totalWithMonthly;
        }
    });
    
    const averagePercentage = studentsWithResults > 0 ? (totalPercentage / studentsWithResults).toFixed(1) : 0;
    
    document.getElementById('totalStudents').textContent = toBengaliNumber(totalStudents);
    document.getElementById('passedStudents').textContent = toBengaliNumber(passedStudents);
    document.getElementById('failedStudents').textContent = toBengaliNumber(failedStudents);
    document.getElementById('averagePercentage').textContent = toBengaliNumber(averagePercentage) + '%';
    document.getElementById('highestMarks').textContent = toBengaliNumber(Number(highestMarks).toFixed(1));
    
    document.getElementById('resultsSummary').classList.remove('hidden');
}

// Update table header
function updateTableHeader() {
    const tableHeader = document.getElementById('resultsTableHeader');
    
    // Check if monthly exam to determine how many fixed columns at end
    const isMonthlyExam = currentExam && currentExam.exam_type === 'monthly';
    
    // Clear all dynamic columns first (keep only Roll and Student Info columns)
    while (tableHeader.children.length > 2) {
        tableHeader.removeChild(tableHeader.children[2]);
    }
    
    // Add subject columns dynamically in reverse order
    for (let i = subjects.length - 1; i >= 0; i--) {
        const subject = subjects[i];
        const th = document.createElement('th');
        
        // Different styling for groups
        if (subject.is_group) {
            th.className = 'px-3 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
            const subjectNamesText = subject.subject_names && subject.subject_names.length > 0 
                ? subject.subject_names.join(' + ') 
                : '';
            th.innerHTML = `
                <div class="font-bold text-white">${subject.name}</div>
                ${subjectNamesText ? `<div class="text-xs text-purple-100 font-medium mt-0.5">${subjectNamesText}</div>` : ''}
                <div class="text-xs text-purple-100 mt-0.5">${toBengaliNumber(subject.total_marks)} (গ্রুপ)</div>
            `;
        } else {
            th.className = 'px-3 py-3 text-center text-xs font-bold text-white bg-indigo-600 border-x border-indigo-400';
            th.innerHTML = `
                <div class="font-bold text-white">${subject.name}</div>
                <div class="text-xs text-indigo-100 mt-0.5">${toBengaliNumber(subject.total_marks)}</div>
            `;
        }
        
        th.style.minWidth = '120px';
        tableHeader.insertBefore(th, tableHeader.children[2]);
    }
    
    // Add fixed columns after subjects
    // Total Number
    const totalTh = document.createElement('th');
    totalTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
    totalTh.style.minWidth = '120px';
    totalTh.innerHTML = `
        <div>মোট নম্বর</div>
        <div class="text-xs font-normal text-purple-100">(মূল পরীক্ষা)</div>
    `;
    tableHeader.appendChild(totalTh);
    
    // Grade
    const gradeTh = document.createElement('th');
    gradeTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
    gradeTh.style.minWidth = '90px';
    gradeTh.innerHTML = `
        <div>গ্রেড</div>
        <div class="text-xs font-normal text-purple-100">Grade</div>
    `;
    tableHeader.appendChild(gradeTh);
    
    // GPA
    const gpaTh = document.createElement('th');
    gpaTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
    gpaTh.style.minWidth = '90px';
    gpaTh.innerHTML = `
        <div>GPA</div>
        <div class="text-xs font-normal text-purple-100">Point</div>
    `;
    tableHeader.appendChild(gpaTh);
    
    // Rank
    const rankTh = document.createElement('th');
    rankTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
    rankTh.style.minWidth = '90px';
    rankTh.innerHTML = `
        <div>র‍্যাংক</div>
        <div class="text-xs font-normal text-purple-100">Rank</div>
    `;
    tableHeader.appendChild(rankTh);
    
    // Pass/Fail
    const passfailTh = document.createElement('th');
    passfailTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
    passfailTh.style.minWidth = '100px';
    passfailTh.innerHTML = `
        <div>ফলাফল</div>
        <div class="text-xs font-normal text-purple-100">Pass/Fail</div>
    `;
    tableHeader.appendChild(passfailTh);
    
    // Add monthly-related columns only if NOT monthly exam
    if (!isMonthlyExam) {
        // Monthly Avg
        const monthlyTh = document.createElement('th');
        monthlyTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400 relative group';
        monthlyTh.style.minWidth = '120px';
        monthlyTh.innerHTML = `
            <div class="cursor-help">মাসিক গড়</div>
            <div class="text-xs font-normal text-purple-100">Monthly Avg</div>
        `;
        tableHeader.appendChild(monthlyTh);
        
        // Final Total
        const finalTotalTh = document.createElement('th');
        finalTotalTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-x border-purple-400';
        finalTotalTh.style.minWidth = '120px';
        finalTotalTh.innerHTML = `
            <div>সর্বমোট</div>
            <div class="text-xs font-normal text-purple-100">(মূল+মাসিক)</div>
        `;
        tableHeader.appendChild(finalTotalTh);
        
        // Final Rank
        const finalRankTh = document.createElement('th');
        finalRankTh.className = 'px-4 py-3 text-center text-xs font-bold text-white bg-purple-600 border-l border-purple-400';
        finalRankTh.style.minWidth = '110px';
        finalRankTh.innerHTML = `
            <div>চূড়ান্ত র‍্যাংক</div>
            <div class="text-xs font-normal text-purple-100">Final Rank</div>
        `;
        tableHeader.appendChild(finalRankTh);
    }
}

// Update table body
function updateTableBody(isMonthlyExam = false) {
    const tableBody = document.getElementById('resultsTableBody');
    tableBody.innerHTML = '';
    
    // Calculate rankings based on main exam marks (without monthly)
    const rankedResults = [...currentResults].sort((a, b) => (b.total_marks || 0) - (a.total_marks || 0));
    const mainRankMap = {};
    rankedResults.forEach((result, index) => {
        mainRankMap[result.student.id] = index + 1;
    });
    
    // Calculate final rankings (with monthly marks)
    const finalRankedResults = [...currentResults].sort((a, b) => {
        const totalA = (a.total_marks || 0) + (a.monthly_marks || 0);
        const totalB = (b.total_marks || 0) + (b.monthly_marks || 0);
        return totalB - totalA;
    });
    const finalRankMap = {};
    finalRankedResults.forEach((result, index) => {
        finalRankMap[result.student.id] = index + 1;
    });
    
    // Sort by roll number for display
    const sortedResults = [...currentResults].sort((a, b) => {
        const rollA = a.student.roll_number || a.student.roll || 0;
        const rollB = b.student.roll_number || b.student.roll || 0;
        return rollA - rollB;
    });
    
    sortedResults.forEach(result => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors';
        
        // Roll Number with sticky position
        let rowHtml = `
            <td class="px-4 py-4 text-center text-sm font-bold text-gray-900 sticky left-0 bg-white z-10 border-r-2 border-gray-300">
                ${result.student.roll_number && result.student.roll_number > 0 ? toBengaliNumber(result.student.roll_number) : '-'}
            </td>
        `;
        
        // Student Information with Photo, Name, Registration Number (sticky)
        const photoUrl = result.student.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(result.student.name)}&background=6366f1&color=fff&size=128`;
        rowHtml += `
            <td class="px-4 py-4 sticky left-20 bg-white z-10 border-r-2 border-gray-300" style="min-width: 280px;">
                <div class="flex items-center gap-3">
                    <img src="${photoUrl}" 
                         alt="${result.student.name}" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-indigo-400 shadow-sm"
                         onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(result.student.name)}&background=6366f1&color=fff&size=128'">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-gray-900">${result.student.name}</div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            <span class="font-medium">রেজি:</span> ${result.student.registration_number || 'N/A'}
                        </div>
                    </div>
                </div>
            </td>
        `;
        
        // Subject Marks (Dynamic) - ONLY shows exam marks, NEVER monthly marks
        result.subjects.forEach(subjectResult => {
            let displayText = '-';
            let bgColor = 'bg-white';
            let textColor = 'text-gray-400';
            let borderColor = 'border-gray-100';
            
            // Check if student is present
            if (!subjectResult.is_present) {
                displayText = 'অনুপস্থিত';
                bgColor = 'bg-red-50';
                textColor = 'text-red-600 font-semibold';
                borderColor = 'border-red-200';
            } 
            // Only display exam marks (obtained_marks), NEVER add monthly marks here
            else if (subjectResult.obtained_marks !== null && subjectResult.obtained_marks !== undefined) {
                // obtained_marks ONLY contains exam marks (NOT combined with monthly)
                displayText = toBengaliNumber(subjectResult.obtained_marks);
                
                if (subjectResult.status === 'pass') {
                    bgColor = 'bg-green-50';
                    textColor = 'text-green-700 font-bold';
                    borderColor = 'border-green-200';
                } else if (subjectResult.status === 'fail') {
                    bgColor = 'bg-red-50';
                    textColor = 'text-red-700 font-bold';
                    borderColor = 'border-red-200';
                } else {
                    bgColor = 'bg-yellow-50';
                    textColor = 'text-yellow-700 font-bold';
                    borderColor = 'border-yellow-200';
                }
            }
            
            rowHtml += `
                <td class="px-3 py-3 text-center ${bgColor} border-x ${borderColor}">
                    <div class="${textColor} text-sm">${displayText}</div>
                    ${subjectResult.grade ? `<div class="text-xs text-gray-500 mt-0.5">${subjectResult.grade}</div>` : ''}
                </td>
            `;
        });
        
        // Total Number (Without Monthly)
        const mainTotal = result.total_marks || 0;
        const totalPossible = result.total_possible || 0;
        rowHtml += `
            <td class="px-4 py-3 text-center bg-blue-50 border-x border-blue-200">
                <div class="text-base font-bold text-blue-700">${mainTotal > 0 ? toBengaliNumber(mainTotal.toFixed(1)) : '-'}</div>
                <div class="text-xs text-blue-600">/ ${toBengaliNumber(totalPossible)}</div>
            </td>
        `;
        
        // Grade
        const grade = result.overall_grade || '-';
        rowHtml += `
            <td class="px-4 py-3 text-center bg-purple-50 border-x border-purple-200">
                <span class="inline-block px-3 py-1.5 text-base font-bold ${getGradeColor(grade)} rounded-lg shadow-sm">
                    ${grade}
                </span>
            </td>
        `;
        
        // GPA
        const percentage = result.percentage || 0;
        const gpa = calculateGPA(percentage);
        rowHtml += `
            <td class="px-4 py-3 text-center bg-indigo-50 border-x border-indigo-200">
                <span class="inline-block px-3 py-1.5 text-base font-bold text-indigo-700 rounded-lg shadow-sm">
                    ${gpa > 0 ? toBengaliNumber(gpa.toFixed(2)) : '-'}
                </span>
            </td>
        `;
        
        // Rank (Based on Main Exam)
        const mainRank = mainRankMap[result.student.id] || '-';
        const rankBadgeColor = mainRank === 1 ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : 
                                mainRank === 2 ? 'bg-gray-100 text-gray-800 border-gray-300' : 
                                mainRank === 3 ? 'bg-orange-100 text-orange-800 border-orange-300' : 
                                'bg-blue-50 text-blue-700 border-blue-200';
        rowHtml += `
            <td class="px-4 py-3 text-center bg-yellow-50 border-x border-yellow-200">
                <span class="inline-block px-3 py-1.5 text-base font-bold ${rankBadgeColor} rounded-lg border-2">
                    ${mainRank !== '-' ? toBengaliNumber(mainRank) : '-'}
                </span>
            </td>
        `;
        
        // Pass/Fail Status
        const passFailStatus = result.overall_result || '-';
        const passFailColor = passFailStatus === 'পাস' ? 'text-green-600 bg-green-50' : 
                              passFailStatus === 'ফেল' ? 'text-red-600 bg-red-50' : 'text-gray-500';
        rowHtml += `
            <td class="px-4 py-3 text-center bg-green-50 border-x border-green-200">
                <span class="inline-block px-3 py-1.5 text-sm font-bold ${passFailColor} rounded-lg">
                    ${passFailStatus}
                </span>
            </td>
        `;
        
        // Monthly Exam Average Column (Only shown for Term Exams, NEVER for Monthly Exams)
        if (!isMonthlyExam) {
            // Get monthly marks from the result object
            const monthlyMarks = parseFloat(result.monthly_marks || 0);
            
            // Build tooltip content if monthly_exam_details exists
            let tooltipHtml = '';
            if (result.monthly_exam_details && Array.isArray(result.monthly_exam_details) && result.monthly_exam_details.length > 0) {
                tooltipHtml = '<div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-50">';
                tooltipHtml += '<div class="bg-gray-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg whitespace-nowrap">';
                tooltipHtml += '<div class="font-bold mb-1 text-center border-b border-gray-700 pb-1">মাসিক পরীক্ষার বিস্তারিত</div>';
                result.monthly_exam_details.forEach(detail => {
                    tooltipHtml += `<div class="flex justify-between gap-3 py-0.5">`;
                    tooltipHtml += `<span>${detail.exam_name}:</span>`;
                    tooltipHtml += `<span class="font-bold">${toBengaliNumber(detail.marks)}</span>`;
                    tooltipHtml += `</div>`;
                });
                tooltipHtml += '</div>';
                tooltipHtml += '<div class="w-3 h-3 bg-gray-900 transform rotate-45 absolute top-full left-1/2 -translate-x-1/2 -mt-1.5"></div>';
                tooltipHtml += '</div>';
            }
            
            // Display Monthly Average in separate column with label "মাসিক গড়"
            rowHtml += `
                <td class="px-4 py-3 text-center bg-orange-50 border-x border-orange-200 relative group">
                    <div class="text-base font-bold text-orange-700 cursor-help">${monthlyMarks > 0 ? toBengaliNumber(monthlyMarks.toFixed(1)) : '-'}</div>
                    <div class="text-xs text-orange-600">মাসিক গড়</div>
                    ${tooltipHtml}
                </td>
            `;
            
            // Total Number (With Monthly)
            const finalTotal = mainTotal + monthlyMarks;
            rowHtml += `
                <td class="px-4 py-3 text-center bg-indigo-50 border-x border-indigo-200">
                    <div class="text-lg font-bold text-indigo-700">${finalTotal > 0 ? toBengaliNumber(finalTotal.toFixed(1)) : '-'}</div>
                    <div class="text-xs text-indigo-600">সর্বমোট</div>
                </td>
            `;
            
            // Final Rank (With Monthly)
            const finalRank = finalRankMap[result.student.id] || '-';
            const finalRankBadgeColor = finalRank === 1 ? 'bg-gradient-to-br from-yellow-200 to-yellow-300 text-yellow-900 border-yellow-400 shadow-md' : 
                                         finalRank === 2 ? 'bg-gradient-to-br from-gray-200 to-gray-300 text-gray-900 border-gray-400 shadow-md' : 
                                         finalRank === 3 ? 'bg-gradient-to-br from-orange-200 to-orange-300 text-orange-900 border-orange-400 shadow-md' : 
                                         'bg-pink-50 text-pink-700 border-pink-200';
            rowHtml += `
                <td class="px-4 py-3 text-center bg-pink-50 border-l border-pink-200">
                    <span class="inline-block px-3 py-1.5 text-lg font-bold ${finalRankBadgeColor} rounded-lg border-2">
                        ${finalRank !== '-' ? toBengaliNumber(finalRank) : '-'}
                    </span>
                </td>
            `;
        }
        
        row.innerHTML = rowHtml;
        tableBody.appendChild(row);
    });
    
    // Show empty state if no results
    if (sortedResults.length === 0) {
        document.getElementById('emptyState').classList.remove('hidden');
    } else {
        document.getElementById('emptyState').classList.add('hidden');
    }
}

// Get grade color
function getGradeColor(grade) {
    switch(grade) {
        case 'A+': return 'bg-gradient-to-br from-green-100 to-green-200 text-green-800 border-2 border-green-300';
        case 'A': return 'bg-gradient-to-br from-green-100 to-green-200 text-green-700 border-2 border-green-300';
        case 'A-': return 'bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 border-2 border-blue-300';
        case 'B': return 'bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 border-2 border-blue-300';
        case 'C': return 'bg-gradient-to-br from-yellow-100 to-yellow-200 text-yellow-700 border-2 border-yellow-300';
        case 'D': return 'bg-gradient-to-br from-orange-100 to-orange-200 text-orange-700 border-2 border-orange-300';
        case 'F': return 'bg-gradient-to-br from-red-100 to-red-200 text-red-700 border-2 border-red-300';
        case 'ফেল': return 'bg-gradient-to-br from-red-200 to-red-300 text-red-800 border-2 border-red-400';
        default: return 'bg-gray-100 text-gray-600 border-2 border-gray-300';
    }
}

// Get result color
function getResultColor(result) {
    switch(result) {
        case 'পাস': return 'text-green-600';
        case 'ফেল': return 'text-red-600';
        default: return 'text-gray-500';
    }
}

// Print results
function printResults() {
    window.print();
}

// Helper function to convert to Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    
    // Remove .00 from numbers
    let numStr = num.toString();
    if (numStr.includes('.')) {
        // Parse as float and check if it's a whole number
        const numFloat = parseFloat(numStr);
        if (Number.isInteger(numFloat)) {
            numStr = Math.floor(numFloat).toString();
        } else {
            // Keep decimal but remove trailing zeros
            numStr = numFloat.toString();
        }
    }
    
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯', '.': '.'};
    return numStr.replace(/[\d.]/g, d => banglaDigits[d] || d);
}

// Calculate GPA from percentage
function calculateGPA(percentage) {
    if (percentage >= 80) return 5.00;
    else if (percentage >= 70) return 4.00;
    else if (percentage >= 60) return 3.50;
    else if (percentage >= 50) return 3.00;
    else if (percentage >= 40) return 2.00;
    else if (percentage >= 33) return 1.00;
    else return 0.00;
}

// Show error modal
function showErrorModal(message) {
    const modalHtml = `
        <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-red-600 mb-2">সমস্যা!</h3>
                    <p class="text-gray-700 text-lg">${message}</p>
                </div>
                
                <button onclick="closeErrorModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-colors">
                    ঠিক আছে
                </button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function closeErrorModal() {
    const modal = document.getElementById('errorModal');
    if (modal) {
        modal.remove();
    }
}

// Open calculate result modal
function openCalculateResultModal() {
    // Populate term exams dropdown
    populateTermExams();
    
    // Populate months from available exams
    populateAvailableMonths();
    
    // Reset selections
    document.getElementById('termExamSelect').value = '';
    document.getElementById('termClassSelect').value = '';
    selectedMonths = [];
    selectedTermExam = null;
    selectedTermClass = null;
    
    // Show modal
    document.getElementById('calculateResultModal').classList.remove('hidden');
    document.getElementById('calculateResultModal').classList.add('flex');
}

// Close calculate result modal
function closeCalculateResultModal() {
    document.getElementById('calculateResultModal').classList.add('hidden');
    document.getElementById('calculateResultModal').classList.remove('flex');
    selectedMonths = [];
    selectedTermExam = null;
    selectedTermClass = null;
}

// Load all exams for calculate modal
async function loadAllExamsForCalculate() {
    try {
        const response = await fetch('/exams/api/list');
        const data = await response.json();
        
        if (data.success && data.exams) {
            allExams = data.exams;
            
            // Extract unique months from all exams
            availableMonths = [...new Set(
                data.exams
                    .filter(exam => exam.month)
                    .map(exam => exam.month)
            )];
            
            console.log('Available months:', availableMonths);
        }
    } catch (error) {
        console.error('Error loading exams for calculate:', error);
    }
}

// Populate term exams dropdown
function populateTermExams() {
    const termExamSelect = document.getElementById('termExamSelect');
    termExamSelect.innerHTML = '<option value="">সামায়িক পরীক্ষা নির্বাচন করুন</option>';
    
    // Filter term exams (include first_semester, second_semester, half_yearly, annual)
    const termExams = allExams.filter(exam => 
        ['first_semester', 'second_semester', 'half_yearly', 'annual'].includes(exam.exam_type)
    );
    
    termExams.forEach(exam => {
        const option = document.createElement('option');
        option.value = exam.id;
        option.textContent = exam.name;
        option.dataset.examType = exam.exam_type;
        option.dataset.classes = JSON.stringify(exam.classes || []);
        termExamSelect.appendChild(option);
    });
}

// Populate available months
function populateAvailableMonths() {
    const monthsContainer = document.getElementById('monthsCheckboxes');
    monthsContainer.innerHTML = '';
    
    if (availableMonths.length === 0) {
        monthsContainer.innerHTML = '<p class="text-gray-500 text-sm">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>';
        return;
    }
    
    availableMonths.forEach((month) => {
        const div = document.createElement('div');
        div.className = 'flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer';
        div.onclick = function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            updateSelectedMonths();
        };
        
        div.innerHTML = `
            <input type="checkbox" value="${month}" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500 cursor-pointer" onclick="event.stopPropagation(); updateSelectedMonths();">
            <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer flex-1">${month}</label>
        `;
        
        monthsContainer.appendChild(div);
    });
}

// Handle term exam selection change
function onTermExamChange() {
    const termExamSelect = document.getElementById('termExamSelect');
    const selectedOption = termExamSelect.options[termExamSelect.selectedIndex];
    
    if (selectedOption.value) {
        selectedTermExam = {
            id: selectedOption.value,
            exam_type: selectedOption.dataset.examType,
            classes: JSON.parse(selectedOption.dataset.classes || '[]')
        };
        
        // Populate classes for this exam
        populateClassesForTermExam();
    } else {
        selectedTermExam = null;
        document.getElementById('termClassSelect').innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
    }
}

// Populate classes for selected term exam
function populateClassesForTermExam() {
    const termClassSelect = document.getElementById('termClassSelect');
    termClassSelect.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
    
    if (!selectedTermExam || !selectedTermExam.classes || selectedTermExam.classes.length === 0) {
        // If no classes specified, show all classes
        allClasses.forEach(cls => {
            const option = document.createElement('option');
            option.value = cls.id;
            option.textContent = cls.full_name;
            termClassSelect.appendChild(option);
        });
    } else {
        // Show only classes associated with this exam
        const examClassIds = selectedTermExam.classes;
        allClasses.forEach(cls => {
            if (examClassIds.includes(cls.id) || examClassIds.includes(String(cls.id))) {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.full_name;
                termClassSelect.appendChild(option);
            }
        });
    }
}

// Load available monthly exams for calculate modal
function loadAvailableMonthlyExamsForCalculate() {
    const classId = document.getElementById('termClassSelect').value;
    selectedTermClass = classId;
    
    if (!classId) {
        document.getElementById('availableMonthlyExamsCalculate').classList.add('hidden');
        return;
    }
    
    try {
        // Filter monthly exams for selected class
        const monthlyExams = allExams.filter(exam => 
            exam.exam_type === 'monthly' && 
            exam.month // Only check if month is set, make classes optional
        );
        
        const monthlyExamsList = document.getElementById('monthlyExamsListCalculate');
        const availableSection = document.getElementById('availableMonthlyExamsCalculate');
        
        if (monthlyExams.length > 0) {
            monthlyExamsList.innerHTML = monthlyExams.map(exam => `
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-medium text-gray-900">${exam.name}</span>
                            ${exam.month ? `<span class="ml-2 text-sm text-purple-600">(${exam.month})</span>` : ''}
                        </div>
                        <span class="text-sm text-gray-500">${exam.status}</span>
                    </div>
                </div>
            `).join('');
            availableSection.classList.remove('hidden');
        } else {
            availableSection.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading monthly exams:', error);
    }
}

function updateSelectedMonths() {
    const checkboxes = document.querySelectorAll('#monthsCheckboxes input[type="checkbox"]');
    selectedMonths = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    
    console.log('Selected months:', selectedMonths);
    
    // Show preview of marks for selected months
    if (selectedMonths.length > 0) {
        showMonthsMarksPreview();
    } else {
        document.getElementById('marksPreview').classList.add('hidden');
    }
}

// Show marks preview for selected months
async function showMonthsMarksPreview() {
    const previewDiv = document.getElementById('marksPreview');
    const previewContent = document.getElementById('previewContent');
    
    if (selectedMonths.length === 0) {
        previewDiv.classList.add('hidden');
        return;
    }
    
    try {
        console.log('Fetching marks preview for months:', selectedMonths);
        
        // Fetch monthly exams summary
        const url = `/admin/results/api/monthly-exams-summary?monthly_exam_ids=${selectedMonths.join(',')}`;
        console.log('Fetch URL:', url);
        
        const response = await fetch(url);
        console.log('Fetch response status:', response.status);
        
        if (!response.ok) {
            console.error('HTTP Error:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            previewDiv.classList.add('hidden');
            return;
        }
        
        const data = await response.json();
        console.log('API Response:', data);
        
        if (!data.success) {
            console.error('API Error:', data.message);
            previewDiv.classList.add('hidden');
            return;
        }
        
        if (!data.summary || data.summary.length === 0) {
            console.warn('No summary data received');
            previewDiv.classList.add('hidden');
            return;
        }
        
        console.log('Building preview HTML with', data.summary.length, 'exams');
        let previewHtml = '';
        let totalExamSubjects = 0;
        let totalMarks = 0;
        
        data.summary.forEach((exam, index) => {
            console.log('Processing exam', index + 1, ':', exam);
            totalExamSubjects += exam.exam_subjects_count;
            // Use avg_marks_per_student instead of actual_total_marks
            const marksToUse = exam.avg_marks_per_student || 0;
            totalMarks += marksToUse;
            console.log('Exam', index + 1, '- Subjects:', exam.exam_subjects_count, 'Avg Marks:', marksToUse);
            
            previewHtml += `
                <div class="flex justify-between items-center p-3 bg-white rounded border-l-4 border-blue-400 mb-2">
                    <div>
                        <div class="font-medium text-gray-700">${exam.name}</div>
                        <div class="text-xs text-gray-500">মাস: ${exam.month || 'N/A'}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-blue-600 font-bold">${toBengaliNumber(marksToUse)}</div>
                        <div class="text-xs text-gray-500">${exam.students_count} ছাত্র</div>
                    </div>
                </div>
            `;
        });
        
        console.log('Total marks:', totalMarks, 'Total subjects:', totalExamSubjects);
        
        // Add summary
        const monthlyAverage = selectedMonths.length > 0 ? (totalMarks / selectedMonths.length).toFixed(2) : 0;
        console.log('Calculated monthly average:', monthlyAverage);
        
        previewHtml += `
            <div class="mt-3 pt-3 border-t-2 border-gray-200">
                <div class="flex justify-between p-2 bg-purple-50 rounded font-bold">
                    <span>মোট:</span>
                    <span>মোট নম্বর: ${toBengaliNumber(totalMarks.toFixed(2))} | পরীক্ষা: ${selectedMonths.length}টি</span>
                </div>
                <div class="flex justify-between p-2 bg-green-50 rounded font-bold text-green-700 mt-2">
                    <span>মাসিক গড়:</span>
                    <span>${toBengaliNumber(monthlyAverage)}</span>
                </div>
            </div>
        `;
        
        console.log('Inserting preview HTML');
        previewContent.innerHTML = previewHtml;
        previewDiv.classList.remove('hidden');
        
        console.log('Preview shown successfully');
        
    } catch (error) {
        console.error('Error in showMonthsMarksPreview:', error);
        console.error('Error stack:', error.stack);
        previewDiv.classList.add('hidden');
    }
}

// Calculate with monthly marks
async function calculateWithMonthlyMarks() {
    // Validate selections
    if (!selectedTermExam) {
        showErrorModal('সামায়িক পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    if (!selectedTermClass) {
        showErrorModal('ক্লাস নির্বাচন করুন');
        return;
    }
    
    if (selectedMonths.length === 0) {
        showErrorModal('অন্তত একটি মাস নির্বাচন করুন');
        return;
    }
    
    try {
        // Show loading
        const modal = document.getElementById('calculateResultModal');
        const calculateBtn = modal.querySelector('button[onclick="calculateWithMonthlyMarks()"]');
        calculateBtn.disabled = true;
        calculateBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            হিসাব করা হচ্ছে...
        `;
        
        const requestData = {
            exam_id: selectedTermExam.id,
            class_id: selectedTermClass,
            monthly_exam_ids: selectedMonths
        };
        console.log('Sending request to calculate results:', requestData);
        
        const response = await fetch('/admin/results/api/calculate-with-monthly', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse response as JSON:', e);
            throw new Error('সার্ভার রেসপন্স সঠিক নয়: ' + responseText.substring(0, 100));
        }
        
        if (!data.success) {
            throw new Error(data.message || 'হিসাব করতে ব্যর্থ');
        }
        
        // Close modal
        closeCalculateResultModal();
        
        // Show success message
        showSuccessModal(`সফলভাবে ${selectedMonths.length}টি মাসের নম্বর যোগ করা হয়েছে! ${data.students_updated || 0} জন শিক্ষার্থীর ফলাফল আপডেট করা হয়েছে।`);
        
        // Reload results if viewing the same exam
        if (currentExam && currentClass && currentExam.id == selectedTermExam.id && currentClass.id == selectedTermClass) {
            await loadResults();
        }
        
    } catch (error) {
        console.error('Error calculating with monthly marks:', error);
        showErrorModal('হিসাব করতে সমস্যা হয়েছে: ' + error.message);
    } finally {
        // Reset button
        const modal = document.getElementById('calculateResultModal');
        const btn = modal.querySelector('button[onclick="calculateWithMonthlyMarks()"]');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                হিসাব করুন
            `;
        }
    }
}

// Show success modal
function showSuccessModal(message) {
    const modalHtml = `
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-green-600 mb-2">সফল!</h3>
                    <p class="text-gray-700 text-lg">${message}</p>
                </div>
                
                <button onclick="closeSuccessModal()" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition-colors">
                    ঠিক আছে
                </button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.remove();
    }
}

// Update export links with current exam and class
function updateExportLinks() {
    if (!currentExam || !currentClass) return;
    
    const baseUrl = '/admin/results/export';
    const params = `?exam_id=${currentExam.id}&class_id=${currentClass.id}`;
    
    document.getElementById('exportPdfLink').href = baseUrl + params + '&format=pdf';
    document.getElementById('exportExcelLink').href = baseUrl + params + '&format=excel';
    document.getElementById('exportDocxLink').href = baseUrl + params + '&format=docx';
}

// Calculate Result Modal Functions
async function loadAllExamsForCalculate() {
    try {
        const response = await fetch('/admin/results/api/exams');
        const data = await response.json();
        if (data.success) {
            allExams = data.exams;
            console.log('Loaded all exams for calculate:', allExams);
        }
    } catch (error) {
        console.error('Error loading exams for calculate:', error);
    }
}

function openCalculateResultModal() {
    populateTermExams();
    populateClassesForCalculate();
    
    document.getElementById('termExamSelect').value = '';
    document.getElementById('termClassSelect').value = '';
    document.getElementById('monthsCheckboxes').innerHTML = '';
    selectedTermExam = null;
    selectedTermClass = null;
    selectedMonths = [];
    
    document.getElementById('calculateResultModal').classList.remove('hidden');
    document.getElementById('calculateResultModal').classList.add('flex');
}

function closeCalculateResultModal() {
    document.getElementById('calculateResultModal').classList.add('hidden');
    document.getElementById('calculateResultModal').classList.remove('flex');
}

function populateTermExams() {
    const termExamSelect = document.getElementById('termExamSelect');
    termExamSelect.innerHTML = '<option value="">সামায়িক পরীক্ষা নির্বাচন করুন</option>';
    
    // Filter term exams (include first_semester, second_semester, half_yearly, annual)
    const termExams = allExams.filter(exam => 
        ['first_semester', 'second_semester', 'half_yearly', 'annual'].includes(exam.exam_type)
    );
    
    termExams.forEach(exam => {
        const option = document.createElement('option');
        option.value = exam.id;
        option.textContent = exam.name;
        option.dataset.examType = exam.exam_type;
        option.dataset.classes = JSON.stringify(exam.classes || []);
        termExamSelect.appendChild(option);
    });
}

function populateClassesForCalculate() {
    const termClassSelect = document.getElementById('termClassSelect');
    termClassSelect.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
    
    allClasses.forEach(cls => {
        const option = document.createElement('option');
        option.value = cls.id;
        option.textContent = cls.full_name;
        termClassSelect.appendChild(option);
    });
}

function onTermExamChange() {
    const termExamSelect = document.getElementById('termExamSelect');
    const selectedOption = termExamSelect.options[termExamSelect.selectedIndex];
    
    if (selectedOption.value) {
        selectedTermExam = {
            id: selectedOption.value,
            exam_type: selectedOption.dataset.examType,
            classes: JSON.parse(selectedOption.dataset.classes || '[]')
        };
        loadAvailableMonthlyExamsForCalculate();
    } else {
        selectedTermExam = null;
        document.getElementById('monthsCheckboxes').innerHTML = '';
    }
}

async function loadAvailableMonthlyExamsForCalculate() {
    const classId = document.getElementById('termClassSelect').value;
    selectedTermClass = classId;
    
    console.log('Loading monthly exams for calculate...');
    console.log('Selected term exam:', selectedTermExam);
    console.log('Selected class:', classId);
    console.log('All exams:', allExams);
    
    if (!selectedTermExam || !classId) {
        document.getElementById('monthsCheckboxes').innerHTML = '';
        return;
    }
    
    const monthlyExams = allExams.filter(exam => {
        console.log('Checking exam:', exam.name, 'Type:', exam.exam_type, 'Month:', exam.month);
        return exam.exam_type === 'monthly' && exam.month;
    });
    
    console.log('Filtered monthly exams:', monthlyExams);
    
    const monthsContainer = document.getElementById('monthsCheckboxes');
    monthsContainer.innerHTML = '';
    
    if (monthlyExams.length === 0) {
        monthsContainer.innerHTML = '<p class="text-gray-500 text-sm col-span-3">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>';
        return;
    }
    
    monthlyExams.forEach(exam => {
        const div = document.createElement('div');
        div.className = 'flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer';
        div.onclick = function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            updateSelectedMonths();
        };
        
        div.innerHTML = `
            <input type="checkbox" value="${exam.id}" class="month-checkbox w-5 h-5 text-purple-600 rounded focus:ring-purple-500 cursor-pointer" onclick="event.stopPropagation(); updateSelectedMonths();">
            <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer flex-1">
                ${exam.name}
                <span class="ml-2 text-xs text-purple-600">(${exam.month})</span>
            </label>
        `;
        
        monthsContainer.appendChild(div);
    });
}

function updateSelectedMonths() {
    const checkboxes = document.querySelectorAll('.month-checkbox:checked');
    selectedMonths = Array.from(checkboxes).map(cb => cb.value);
    
    console.log('Selected months updated:', selectedMonths);
    
    // Show preview of marks for selected months
    if (selectedMonths.length > 0) {
        showMonthsMarksPreview();
    } else {
        document.getElementById('marksPreview').classList.add('hidden');
    }
}

async function calculateWithMonthlyMarks() {
    if (!selectedTermExam) {
        showErrorModal('সামায়িক পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    if (!selectedTermClass) {
        showErrorModal('ক্লাস নির্বাচন করুন');
        return;
    }
    
    if (selectedMonths.length === 0) {
        showErrorModal('অন্তত একটি মাস নির্বাচন করুন');
        return;
    }
    
    try {
        const requestData = {
            exam_id: selectedTermExam.id,
            class_id: selectedTermClass,
            monthly_exam_ids: selectedMonths
        };
        console.log('Sending request to calculate results:', requestData);
        
        const response = await fetch('/admin/results/api/calculate-with-monthly', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse response as JSON:', e);
            throw new Error('সার্ভার রেসপন্স সঠিক নয়: ' + responseText.substring(0, 100));
        }
        
        if (data.success) {
            closeCalculateResultModal();
            showSuccessModal(data.message || 'মাসিক নম্বর সফলভাবে যোগ করা হয়েছে');
            
            if (currentExam && currentClass) {
                loadResults();
            }
        } else {
            showErrorModal(data.message || 'হিসাব করতে ব্যর্থ হয়েছে');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    }
}

function showErrorModal(message) {
    const modalHtml = `
        <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-red-600 mb-2">ত্রুটি!</h3>
                    <p class="text-gray-700 text-lg">${message}</p>
                </div>
                
                <button onclick="closeErrorModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-colors">
                    ঠিক আছে
                </button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function closeErrorModal() {
    const modal = document.getElementById('errorModal');
    if (modal) {
        modal.remove();
    }
}
</script>

<style>
@media print {
    /* Page setup */
    @page {
        size: A4 landscape;
        margin: 0.5cm;
    }
    
    body {
        font-size: 9px;
        background: white !important;
        color: black !important;
        margin: 0;
        padding: 5px;
    }
    
    /* Hide page wrapper and other sections */
    body > div.p-8 > div.max-w-full > div:not(#resultsSection) {
        display: none !important;
    }
    
    body > div.p-8 > div.max-w-full > .mb-6 {
        display: none !important;
    }
    
    /* Show results section */
    #resultsSection {
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }
    
    .p-8 {
        padding: 0 !important;
    }
    
    .max-w-full {
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    /* Hide summary cards and buttons in print */
    #resultsSummary,
    #resultsSection button,
    #resultsSection .bg-gradient-to-r button {
        display: none !important;
    }
    
    /* Print header - match PDF format */
    #resultsSection > .bg-gradient-to-r,
    #resultsSection > div:first-child {
        background: white !important;
        border-bottom: 2px solid #000 !important;
        padding: 10px !important;
        margin-bottom: 10px !important;
        page-break-inside: avoid;
    }
    
    #resultsSection .bg-gradient-to-r h3,
    #resultsSection h3 {
        color: black !important;
        font-size: 16px !important;
        text-align: center;
        margin: 5px 0;
    }
    
    #resultsSection .bg-gradient-to-r div,
    #resultsSection .bg-gradient-to-r span,
    #resultsSection .text-indigo-100,
    #resultsSection .text-indigo-200 {
        color: black !important;
        font-size: 10px !important;
    }
    
    /* Table styling for print - match PDF */
    table,
    .results-table,
    #resultsTableContainer table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 10px !important;
        font-size: 8px !important;
        page-break-inside: auto;
    }
    
    thead,
    .results-table thead,
    #resultsTableContainer thead {
        display: table-header-group;
    }
    
    th,
    .results-table th,
    #resultsTableContainer th {
        background-color: #e5e7eb !important;
        color: black !important;
        border: 1px solid #000 !important;
        padding: 4px 2px !important;
        text-align: center !important;
        font-size: 8px !important;
        font-weight: bold !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        page-break-inside: avoid;
        page-break-after: avoid;
    }
    
    td,
    .results-table td,
    #resultsTableContainer td {
        border: 1px solid #000 !important;
        padding: 3px 2px !important;
        text-align: center !important;
        font-size: 8px !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        page-break-inside: avoid;
    }
    
    /* Student info column */
    td:nth-child(2),
    .results-table td:nth-child(2),
    #resultsTableContainer td:nth-child(2) {
        text-align: left !important;
        min-width: 100px !important;
    }
    
    /* Results table container */
    #resultsTableContainer,
    .p-6 {
        padding: 5px !important;
        display: block !important;
    }
    
    /* Hide sticky positioning */
    .sticky {
        position: relative !important;
        left: auto !important;
        background: white !important;
    }
    
    /* Hide student photos in print */
    img,
    .results-table img,
    #resultsTableContainer img {
        display: none !important;
    }
    
    /* Show student info without photo */
    .flex.items-center,
    .results-table .flex.items-center,
    #resultsTableContainer .flex.items-center {
        display: block !important;
    }
    
    .flex.items-center > div {
        display: block !important;
    }
    
    /* Remove backgrounds, keep borders */
    .bg-green-50,
    .bg-red-50,
    .bg-yellow-50,
    .bg-blue-50,
    .bg-purple-50 {
        background-color: white !important;
    }
    
    /* Keep text colors for pass/fail */
    .text-green-700,
    .text-green-600 {
        color: #059669 !important;
        font-weight: bold !important;
    }
    
    .text-red-700,
    .text-red-600 {
        color: #dc2626 !important;
        font-weight: bold !important;
    }
    
    .text-yellow-700 {
        color: #f59e0b !important;
    }
    
    /* Grade styling */
    .grade-a-plus,
    .result-pass {
        color: #059669 !important;
        font-weight: bold !important;
    }
    
    .grade-fail,
    .result-fail {
        color: #dc2626 !important;
        font-weight: bold !important;
    }
    
    /* Absent marks */
    .absent-mark {
        color: #6b7280 !important;
        font-style: italic !important;
    }
    
    /* Remove shadows and rounded corners */
    .shadow-xl,
    .shadow-lg,
    .shadow-md,
    .shadow-sm,
    .rounded-2xl,
    .rounded-xl,
    .rounded-lg {
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    
    /* Container adjustments */
    .max-w-full {
        max-width: 100% !important;
    }
    
    .p-8,
    .p-6 {
        padding: 5px !important;
    }
    
    /* Print header info */
    .print-header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
    
    /* Ensure all columns are visible */
    thead tr th,
    tbody tr td {
        page-break-inside: avoid;
    }
    
    /* Remove hover effects */
    tr:hover {
        background-color: transparent !important;
    }
}
</style>
@endsection

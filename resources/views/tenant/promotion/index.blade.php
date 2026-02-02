@extends('layouts.tenant')

@section('content')
<div class="p-8 print-content">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between print-header">
            <div>
                <h1 class="text-3xl font-bold text-emerald-600">প্রমোশন করুন</h1>
                <p class="text-gray-600 mt-1">শিক্ষার্থীদের পরবর্তী ক্লাসে প্রমোশন দিন এবং রেকর্ড রাখুন।</p>
                <!-- Print-only information -->
                <div class="hidden print:block mt-2">
                    <p class="text-sm">প্রিন্ট তারিখ: {{ date('d/m/Y') }} | সময়: {{ date('H:i') }}</p>
                    <p class="text-sm">স্কুল: {{ $settings->school_name_bn ?? 'ইকরা নূরানিয়া একাডেমি' }}</p>
                </div>
            </div>
            <div class="flex gap-3 no-print">
                <button onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 print-keep">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </button>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 print-keep">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    পেজ প্রিন্ট করুন
                </button>
                <button onclick="promoteAll()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    সবাইকে প্রমোশন দিন
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 no-print">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">প্রমোটেড শিক্ষার্থী</p>
                        <p class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট শিক্ষার্থী</p>
                        <p class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">প্রমোশন হার</p>
                        <p class="text-3xl font-bold text-gray-900">০%</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">শেষ প্রমোশন</p>
                        <p class="text-3xl font-bold text-gray-900">--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotion Criteria -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 no-print">
            <h3 class="text-lg font-bold text-gray-900 mb-4">প্রমোশনের মানদণ্ড নির্বাচন করুন</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বর্তমান ক্লাস *</label>
                    <select id="currentClass" onchange="loadStudents()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="">ক্লাস নির্বাচন করুন</option>
                        <option value="1">শ্রেণি ১</option>
                        <option value="2">শ্রেণি ২</option>
                        <option value="3">শ্রেণি ৩</option>
                        <option value="4">শ্রেণি ৪</option>
                        <option value="5">শ্রেণি ৫</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পরবর্তী ক্লাস *</label>
                    <select id="nextClass" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="">ক্লাস নির্বাচন করুন</option>
                        <option value="2">শ্রেণি ২</option>
                        <option value="3">শ্রেণি ৩</option>
                        <option value="4">শ্রেণি ৪</option>
                        <option value="5">শ্রেণি ৫</option>
                        <option value="6">শ্রেণি ৬</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ন্যূনতম উপস্থিতি (%)</label>
                    <input type="number" id="minAttendance" value="75" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ন্যূনতম গড় মার্ক</label>
                    <input type="number" id="minMarks" value="33" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Promotion Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">শিক্ষার্থীদের প্রমোশন তালিকা</h3>
                    <div class="flex gap-2">
                        <button onclick="selectAll()" class="bg-emerald-100 hover:bg-emerald-200 text-emerald-800 px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                            সব নির্বাচন করুন
                        </button>
                        <button onclick="deselectAll()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                            সব অপসারণ করুন
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll()" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শিক্ষার্থী আইডি</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বর্তমান ক্লাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">রোল</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">উপস্থিতি</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">গড় মার্ক</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">প্রমোশন স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap no-print">মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody id="promotionTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Summary Section -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">মোট শিক্ষার্থী</p>
                        <p class="text-xl font-bold text-gray-900" id="totalStudents">০</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">প্রমোশনের যোগ্য</p>
                        <p class="text-xl font-bold text-green-600" id="eligibleStudents">০</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">নির্বাচিত</p>
                        <p class="text-xl font-bold text-emerald-600" id="selectedStudents">০</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">প্রমোশন হার</p>
                        <p class="text-xl font-bold text-purple-600" id="promotionRate">০%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Sample data - replace with actual data from controller
let students = [
    {
        id: 1,
        student_id: 'STU001',
        name: 'মোঃ লাভলু সেখ',
        current_class: 'শ্রেণি ১',
        roll: '০১',
        attendance: 85,
        average_marks: 78,
        eligible: true,
        selected: false
    },
    {
        id: 2,
        student_id: 'STU002',
        name: 'ফাতেমা খাতুন',
        current_class: 'শ্রেণি ১',
        roll: '০২',
        attendance: 92,
        average_marks: 85,
        eligible: true,
        selected: false
    },
    {
        id: 3,
        student_id: 'STU003',
        name: 'আব্দুল করিম',
        current_class: 'শ্রেণি ১',
        roll: '০৩',
        attendance: 70,
        average_marks: 65,
        eligible: false,
        selected: false
    },
    {
        id: 4,
        student_id: 'STU004',
        name: 'আয়েশা বেগম',
        current_class: 'শ্রেণি ১',
        roll: '০৪',
        attendance: 88,
        average_marks: 72,
        eligible: true,
        selected: false
    }
];

let filteredStudents = [...students];

// Helper functions for Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Load students based on class
function loadStudents() {
    const currentClass = document.getElementById('currentClass').value;
    
    if (!currentClass) {
        filteredStudents = [];
    } else {
        // Filter students based on class (in real app, this would be API call)
        filteredStudents = students.filter(student => student.current_class.includes(currentClass));
        
        // Check eligibility based on criteria
        const minAttendance = parseInt(document.getElementById('minAttendance').value) || 75;
        const minMarks = parseInt(document.getElementById('minMarks').value) || 33;
        
        filteredStudents.forEach(student => {
            student.eligible = student.attendance >= minAttendance && student.average_marks >= minMarks;
        });
    }
    
    renderPromotionTable();
    updateSummary();
}

// Render promotion table
function renderPromotionTable() {
    const tableBody = document.getElementById('promotionTableBody');
    if (!tableBody) return;
    
    if (filteredStudents.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg font-medium">কোনো শিক্ষার্থী পাওয়া যায়নি</p>
                        <p class="text-sm">ক্লাস নির্বাচন করে আবার চেষ্টা করুন</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = filteredStudents.map(student => {
        const statusConfig = {
            true: { class: 'bg-green-100 text-green-800', text: 'যোগ্য', icon: '✅' },
            false: { class: 'bg-red-100 text-red-800', text: 'অযোগ্য', icon: '❌' }
        };
        
        const status = statusConfig[student.eligible];
        const checkboxId = `student_${student.id}`;
        
        return `
            <tr class="hover:bg-gray-50 transition-colors ${!student.eligible ? 'opacity-50' : ''}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" 
                           id="${checkboxId}"
                           ${!student.eligible ? 'disabled' : ''}
                           ${student.selected ? 'checked' : ''}
                           onchange="toggleStudent(${student.id})"
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${student.student_id}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${student.name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${student.current_class}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${student.roll}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold ${student.attendance >= 75 ? 'text-green-600' : 'text-red-600'}">${toBengaliNumber(student.attendance)}%</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold ${student.average_marks >= 33 ? 'text-green-600' : 'text-red-600'}">${toBengaliNumber(student.average_marks)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium ${status.class}">
                        ${status.icon} ${status.text}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap no-print">
                    <input type="text" 
                           placeholder="মন্তব্য"
                           ${!student.eligible ? 'disabled' : ''}
                           class="w-24 px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                </td>
            </tr>
        `;
    }).join('');
}

// Toggle student selection
function toggleStudent(studentId) {
    const student = students.find(s => s.id === studentId);
    if (student && student.eligible) {
        student.selected = !student.selected;
        updateSummary();
    }
}

// Toggle all students
function toggleAll() {
    const selectAll = document.getElementById('selectAllCheckbox').checked;
    
    filteredStudents.forEach(student => {
        if (student.eligible) {
            student.selected = selectAll;
            const checkbox = document.getElementById(`student_${student.id}`);
            if (checkbox) {
                checkbox.checked = selectAll;
            }
        }
    });
    
    updateSummary();
}

// Select all eligible students
function selectAll() {
    filteredStudents.forEach(student => {
        if (student.eligible) {
            student.selected = true;
            const checkbox = document.getElementById(`student_${student.id}`);
            if (checkbox) {
                checkbox.checked = true;
            }
        }
    });
    
    updateSummary();
}

// Deselect all students
function deselectAll() {
    filteredStudents.forEach(student => {
        student.selected = false;
        const checkbox = document.getElementById(`student_${student.id}`);
        if (checkbox) {
            checkbox.checked = false;
        }
    });
    
    updateSummary();
}

// Update summary
function updateSummary() {
    const totalStudents = filteredStudents.length;
    const eligibleStudents = filteredStudents.filter(s => s.eligible).length;
    const selectedStudents = filteredStudents.filter(s => s.selected).length;
    const promotionRate = totalStudents > 0 ? (selectedStudents / totalStudents) * 100 : 0;
    
    document.getElementById('totalStudents').textContent = toBengaliNumber(totalStudents);
    document.getElementById('eligibleStudents').textContent = toBengaliNumber(eligibleStudents);
    document.getElementById('selectedStudents').textContent = toBengaliNumber(selectedStudents);
    document.getElementById('promotionRate').textContent = promotionRate.toFixed(1) + '%';
}

// Promote all selected students
function promoteAll() {
    const selectedStudents = filteredStudents.filter(s => s.selected);
    const currentClass = document.getElementById('currentClass').value;
    const nextClass = document.getElementById('nextClass').value;
    
    if (!currentClass || !nextClass) {
        alert('অনুগ্রহ করে বর্তমান ক্লাস এবং পরবর্তী ক্লাস নির্বাচন করুন!');
        return;
    }
    
    if (selectedStudents.length === 0) {
        alert('অনুগ্রহ করে কমপক্ষে একজন শিক্ষার্থী নির্বাচন করুন!');
        return;
    }
    
    if (confirm(`আপনি কি ${selectedStudents.length} জন শিক্ষার্থীকে প্রমোশন দিতে চান?`)) {
        // In real app, this would be an API call
        alert(`${selectedStudents.length} জন শিক্ষার্থী সফলভাবে প্রমোটেড হয়েছে!`);
        
        // Remove promoted students from list
        students = students.filter(s => !selectedStudents.find(ps => ps.id === s.id));
        filteredStudents = [...students];
        renderPromotionTable();
        updateSummary();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Promotion Page Loaded');
    renderPromotionTable();
    updateSummary();
});
</script>
@endpush
@endsection

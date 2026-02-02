@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">মার্ক এন্ট্রি</h1>
                <p class="text-gray-600 mt-1">পরীক্ষার নম্বর প্রবেশ করুন এবং ফলাফল তৈরি করুন</p>
            </div>
            <div class="flex gap-3">
                <button onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </button>
                <button onclick="saveAllMarks()" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    সকল বিষয়ের মার্ক সংরক্ষণ
                </button>
            </div>
        </div>

        <!-- Exam Selection -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">পরীক্ষা নির্বাচন করুন *</label>
                    <select id="examSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="loadExamData()">
                        <option value="">পরীক্ষা নির্বাচন করুন</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ক্লাস নির্বাচন করুন *</label>
                    <select id="classSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="handleClassChange()">>
                        <option value="">ক্লাস নির্বাচন করুন</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Exam Info Card -->
        <div id="examInfoCard" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-700" id="examName">-</div>
                    <div class="text-sm text-gray-600">পরীক্ষার নাম</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-700" id="subjectName">-</div>
                    <div class="text-sm text-gray-600">বিষয়</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-700" id="totalMarks">-</div>
                    <div class="text-sm text-gray-600">পূর্ণমান</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600" id="passMarks">-</div>
                    <div class="text-sm text-gray-600">পাস মার্ক</div>
                </div>
            </div>
        </div>

        <!-- Marks Entry Table -->
        <div id="marksEntrySection" class="bg-white rounded-2xl shadow-lg overflow-hidden hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">মার্ক এন্ট্রি</h3>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" style="min-width: max-content;">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr id="tableHeaderRow">
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700 sticky left-0 bg-gray-50 z-10 border-r-2 border-gray-200">রোল নং</th>
                                <th class="px-4 py-3 text-left text-sm font-bold text-gray-700 sticky left-16 bg-gray-50 z-10 border-r-2 border-gray-200" style="min-width: 200px;">ছাত্র/ছাত্রী</th>
                                <!-- Subject columns will be added dynamically -->
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700 bg-gray-50 border-l-2 border-gray-300">মোট নম্বর</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700 bg-gray-50">গ্রেড</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700 bg-gray-50">ফলাফল</th>
                            </tr>
                        </thead>
                        <tbody id="marksTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Marks Entry Modal -->
<div id="marksEntryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white" id="modalTitle">মার্ক এন্ট্রি</h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <div class="flex items-center gap-4 mb-4">
                    <img id="modalStudentPhoto" src="" alt="Student Photo" class="w-20 h-20 rounded-full object-cover border-4 border-green-500" onerror="this.src='https://ui-avatars.com/api/?name=Student&background=10b981&color=fff&size=128'">
                    <div>
                        <div class="text-lg font-bold text-gray-900" id="modalStudentName">-</div>
                        <div class="text-sm text-gray-600">রোল: <span id="modalStudentRoll">-</span></div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-sm font-bold text-gray-700 mb-2">বিষয়: <span id="modalSubjectName" class="text-gray-700">-</span></div>
                <div class="text-sm text-gray-600">পূর্ণমান: <span id="modalTotalMarks" class="font-bold">-</span> | পাস মার্ক: <span id="modalPassMarks" class="font-bold">-</span></div>
            </div>
            
            <div class="mb-4">
                <label class="flex items-center gap-2 mb-4">
                    <input type="checkbox" id="modalAttendance" class="form-checkbox h-5 w-5 text-gray-600 rounded focus:ring-gray-500" checked onchange="toggleMarksInput()">
                    <span class="text-sm font-bold text-gray-700">উপস্থিত</span>
                </label>
            </div>
            
            <div id="marksInputSection">
                <label class="block text-sm font-bold text-gray-700 mb-2">প্রাপ্ত নম্বর *</label>
                <input type="text" id="modalMarksInput" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-center text-2xl font-bold" placeholder="নম্বর ইনপুট করুন" onkeydown="handleKeyDown(event)" oninput="handleInputChange()" onchange="handleInputChange()" onblur="autoCorrectMarks()">
                <div class="text-xs text-gray-500 mt-1 text-center">বাংলা ও ইংরেজি উভয় সংখ্যা সাপোর্ট করে | Enter চাপুন পরবর্তী ছাত্রের জন্য</div>
                
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-sm text-gray-600 mb-1">গ্রেড</div>
                        <div class="text-2xl font-bold text-gray-700" id="modalGrade">-</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-sm text-gray-600 mb-1">ফলাফল</div>
                        <div class="text-2xl font-bold text-gray-700" id="modalResult">-</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex gap-3">
                <button onclick="closeMarksModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-3 rounded-lg font-bold">বাতিল</button>
                <button onclick="saveMarksFromModal()" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-3 rounded-lg font-bold">সংরক্ষণ</button>
                <button onclick="saveAndMoveToNext()" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-3 rounded-lg font-bold">পরবর্তী ছাত্র</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentExam = null;
let currentSubject = null;
let students = [];
let marks = {};
let examSubjects = []; // Store exam subjects for the selected class

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadExams();
    loadClasses();
    
    // Check if exam_id is provided in URL
    const urlParams = new URLSearchParams(window.location.search);
    const examId = urlParams.get('exam_id');
    if (examId) {
        setTimeout(() => {
            document.getElementById('examSelect').value = examId;
            loadExamData();
        }, 500);
    }
});

// Load exams
async function loadExams() {
    try {
        const response = await fetch('/marks/api/exams');
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'পরীক্ষা লোড করতে ব্যর্থ');
        }
        
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
        const response = await fetch('/marks/api/classes');
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'ক্লাস লোড করতে ব্যর্থ');
        }
        
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
    } catch (error) {
        console.error('Error loading classes:', error);
        showErrorModal('ক্লাসের তথ্য লোড করতে সমস্যা হয়েছে: ' + error.message);
    }
}

// Load exam data
async function loadExamData() {
    const examId = document.getElementById('examSelect').value;
    if (!examId) {
        const examInfoCard = document.getElementById('examInfoCard');
        const subjectSelect = document.getElementById('subjectSelect');
        
        if (examInfoCard) {
            examInfoCard.classList.add('hidden');
        }
        if (subjectSelect) {
            subjectSelect.innerHTML = '<option value="">বিষয় নির্বাচন করুন</option>';
        }
        currentExam = null;
        return;
    }
    
    try {
        const classId = document.getElementById('classSelect').value;
        let url = `/marks/api/marks-exam-subjects/${examId}`;
        if (classId) {
            url += `?class_id=${classId}`;
        }
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'পরীক্ষার তথ্য লোড করতে ব্যর্থ');
        }
        
        currentExam = {
            id: data.exam.id,
            name: data.exam.name,
            subjects: data.subjects
        };
        
        console.log('Loaded exam with subjects:', currentExam);
        
        const examNameElement = document.getElementById('examName');
        if (examNameElement) {
            examNameElement.textContent = currentExam.name;
        }
        
        // If class is already selected, reload students with exam subjects
        if (classId) {
            loadStudents();
        }
        
    } catch (error) {
        console.error('Error loading exam data:', error);
        showErrorModal('পরীক্ষার তথ্য লোড করতে সমস্যা হয়েছে: ' + error.message);
    }
}

// Handle class change
async function handleClassChange() {
    const examId = document.getElementById('examSelect').value;
    const classId = document.getElementById('classSelect').value;
    
    if (!examId || !classId) {
        return;
    }
    
    // Reload exam subjects with class filter
    await loadExamData();
    
    // Then load students
    await loadStudents();
}

// Load students (Optimized for instant loading)
async function loadStudents() {
    const examId = document.getElementById('examSelect').value;
    const classId = document.getElementById('classSelect').value;
    
    console.log('loadStudents called - Exam:', examId, 'Class:', classId);
    
    if (!examId) {
        showErrorModal('প্রথমে একটি পরীক্ষা নির্বাচন করুন');
        document.getElementById('classSelect').value = '';
        return;
    }
    
    if (!classId) {
        students = [];
        document.getElementById('marksEntrySection').classList.add('hidden');
        return;
    }
    
    try {
        // Show loading indicator
        const marksEntrySection = document.getElementById('marksEntrySection');
        marksEntrySection.classList.remove('hidden');
        document.getElementById('marksTableBody').innerHTML = '<tr><td colspan="10" class="text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div><div class="mt-2 text-gray-600">ছাত্র/ছাত্রী লোড হচ্ছে...</div></td></tr>';
        
        // Load students for the class using our API with optimized fetch
        const studentsResponse = await fetch(`/marks/api/students/${classId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
        
        if (!studentsResponse.ok) {
            throw new Error('Network response was not ok');
        }
        
        const studentsData = await studentsResponse.json();
        
        console.log('Students API response:', studentsData);
        
        if (!studentsData.success) {
            throw new Error(studentsData.message || 'ছাত্র/ছাত্রী লোড করতে ব্যর্থ');
        }
        
        students = studentsData.students;
        console.log('Students loaded:', students.length);
        
        if (students.length === 0) {
            showErrorModal('এই ক্লাসে কোন ছাত্র/ছাত্রী নেই');
            document.getElementById('marksEntrySection').classList.add('hidden');
        } else {
            // Load existing marks for all subjects (async, don't wait)
            loadAllMarks().then(() => {
                // Re-render table after marks are loaded
                renderMarksTableWithSubjects();
            });
            
            // Render table immediately with students (without waiting for marks)
            renderMarksTableWithSubjects();
        }
    } catch (error) {
        console.error('Error loading students:', error);
        showErrorModal('ছাত্র/ছাত্রীদের তথ্য লোড করতে সমস্যা হয়েছে: ' + error.message);
        document.getElementById('marksEntrySection').classList.add('hidden');
    }
}

// Load all marks for all subjects (Optimized with parallel loading)
async function loadAllMarks() {
    if (!currentExam || !currentExam.subjects) return;
    
    const classId = document.getElementById('classSelect').value;
    
    try {
        // Load marks for all subjects in parallel for better performance
        const markPromises = currentExam.subjects.map(subject => 
            fetch(`/marks/api/marks?exam_id=${currentExam.id}&subject_id=${subject.id}&class_id=${classId}`)
                .then(response => response.json())
                .then(data => ({ subject, data }))
                .catch(error => {
                    console.error(`Error loading marks for subject ${subject.id}:`, error);
                    return { subject, data: { success: false } };
                })
        );
        
        // Wait for all marks to load
        const results = await Promise.all(markPromises);
        
        // Process all results
        results.forEach(({ subject, data }) => {
            if (data.success && data.marks) {
                Object.keys(data.marks).forEach(studentId => {
                    const result = data.marks[studentId];
                    const studentKey = `${currentExam.id}_${subject.id}_${result.student_id}`;
                    marks[studentKey] = {
                        present: result.status !== 'absent',
                        marks: result.obtained_marks || '',
                        grade: result.grade || '',
                        result: result.status === 'pass' ? 'পাস' : (result.status === 'fail' ? 'ফেল' : 'অনুপস্থিত')
                    };
                });
            }
        });
    } catch (error) {
        console.error('Error loading all marks:', error);
    }
}

// Render marks table with multiple subjects
function renderMarksTableWithSubjects() {
    if (!currentExam || !currentExam.subjects || currentExam.subjects.length === 0) {
        console.log('No exam or subjects available');
        return;
    }
    
    const tableHeaderRow = document.getElementById('tableHeaderRow');
    const tableBody = document.getElementById('marksTableBody');
    
    if (!tableHeaderRow || !tableBody) {
        console.warn('Table elements not found');
        return;
    }
    
    // Clear existing subject columns
    while (tableHeaderRow.children.length > 5) {
        tableHeaderRow.removeChild(tableHeaderRow.children[2]);
    }
    
    // Add subject columns to header (insert before "মোট নম্বর" column)
    const totalMarksColumn = tableHeaderRow.children[2];
    currentExam.subjects.forEach(subject => {
        const th = document.createElement('th');
        th.className = 'px-3 py-3 text-center text-sm font-bold text-gray-700 bg-yellow-50 border-x border-gray-200 cursor-pointer hover:bg-yellow-100 transition';
        th.style.minWidth = '140px';
        
        // Different display for groups vs individual subjects
        if (subject.type === 'group') {
            // Display group name and subject names
            const subjectNamesText = subject.subject_names && subject.subject_names.length > 0 
                ? subject.subject_names.join(' + ') 
                : '';
            
            th.innerHTML = `
                <div class="font-bold text-purple-700">${subject.name}</div>
                ${subjectNamesText ? `<div class="text-xs text-purple-600 font-medium">${subjectNamesText}</div>` : ''}
                <div class="text-xs text-purple-600">${toBengaliNumber(subject.total_marks)} নম্বর (গ্রুপ)</div>
            `;
            th.className += ' bg-purple-50';
        } else {
            th.innerHTML = `
                <div class="font-bold text-gray-700">${subject.name}</div>
                <div class="text-xs text-gray-600">${toBengaliNumber(subject.total_marks)} নম্বর</div>
            `;
        }
        
        tableHeaderRow.insertBefore(th, totalMarksColumn);
    });
    
    // Clear table body
    tableBody.innerHTML = '';
    
    // Add student rows
    students.forEach(student => {
        if (!student || !student.id) {
            console.warn('Invalid student data found, skipping');
            return;
        }
        
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        let rowHtml = `
            <td class="px-4 py-3 text-center text-sm font-bold text-gray-900 sticky left-0 bg-white z-5 border-r-2 border-gray-200">${toBengaliNumber(student.roll_number)}</td>
            <td class="px-4 py-3 sticky left-16 bg-white z-5 border-r-2 border-gray-200">
                <div class="flex items-center gap-3">
                    <img src="${student.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=10b981&color=fff&size=128'}" 
                         alt="${student.name}" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-green-500"
                         onerror="console.log('Photo failed to load:', this.src); this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('${student.name}') + '&background=10b981&color=fff&size=128'"
                         onload="console.log('Photo loaded successfully:', this.src)">
                    <div>
                        <div class="text-sm font-bold text-gray-900">${student.name}</div>
                        <div class="text-xs text-gray-500">${student.registration_number || 'N/A'}</div>
                    </div>
                </div>
            </td>
        `;
        
        let totalMarks = 0;
        let totalPossible = 0;
        let subjectsWithMarks = 0;
        let failedSubjects = 0;
        
        // Add marks cells for each subject
        currentExam.subjects.forEach(subject => {
            const studentKey = `${currentExam.id}_${subject.id}_${student.id}`;
            const studentMark = marks[studentKey] || { present: true, marks: '', grade: '', result: '' };
            
            let displayText = '-';
            let bgColor = 'bg-white';
            let textColor = 'text-gray-400';
            let cellClass = 'px-3 py-3 text-center border-x border-gray-100 cursor-pointer hover:bg-yellow-100 transition';
            
            // Different styling for groups
            if (subject.type === 'group') {
                bgColor = 'bg-purple-25';
                cellClass = 'px-3 py-3 text-center border-x border-purple-100 cursor-pointer hover:bg-purple-100 transition';
            }
            
            if (!studentMark.present) {
                displayText = 'অনুপস্থিত';
                bgColor = subject.type === 'group' ? 'bg-purple-100' : 'bg-gray-100';
                textColor = 'text-gray-600';
            } else if (studentMark.marks !== '' && studentMark.marks !== null) {
                const marksValue = parseFloat(studentMark.marks);
                displayText = toBengaliNumber(marksValue);
                totalMarks += marksValue;
                totalPossible += subject.total_marks;
                subjectsWithMarks++;
                
                // Check if failed in this subject based on 33% rule
                const percentage = (marksValue / subject.total_marks) * 100;
                
                if (percentage >= 33) {
                    bgColor = subject.type === 'group' ? 'bg-purple-50' : 'bg-gray-50';
                    textColor = subject.type === 'group' ? 'text-purple-700 font-bold' : 'text-gray-700 font-bold';
                } else {
                    bgColor = subject.type === 'group' ? 'bg-red-100' : 'bg-gray-100';
                    textColor = 'text-red-700 font-bold';
                    failedSubjects++;
                }
            } else {
                totalPossible += subject.total_marks;
            }
            
            rowHtml += `
                <td class="${cellClass} ${bgColor}" 
                    onclick="openMarksModal(${student.id}, '${subject.id}')">
                    <div class="${textColor} text-sm font-medium">${displayText}</div>
                    ${subject.type === 'group' ? '<div class="text-xs text-purple-500">গ্রুপ</div>' : ''}
                </td>
            `;
        });
        
        // Calculate overall grade and result
        let overallGrade = '';
        let overallResult = '';
        let resultColor = 'text-gray-500';
        
        if (subjectsWithMarks === currentExam.subjects.length && totalPossible > 0) {
            const percentage = (totalMarks / totalPossible) * 100;
            
            // If any subject is failed, overall grade and result should be "ফেল"
            if (failedSubjects > 0) {
                overallGrade = 'ফেল';
                overallResult = 'ফেল';
                resultColor = 'text-red-600';
            } else if (percentage >= 33) {
                // Calculate grade only if no subjects failed and overall >= 33%
                if (percentage >= 80) overallGrade = 'A+';
                else if (percentage >= 70) overallGrade = 'A';
                else if (percentage >= 60) overallGrade = 'A-';
                else if (percentage >= 50) overallGrade = 'B';
                else if (percentage >= 40) overallGrade = 'C';
                else if (percentage >= 33) overallGrade = 'D';
                
                overallResult = 'পাস';
                resultColor = 'text-green-600';
            } else {
                // Overall percentage < 33%
                overallGrade = 'ফেল';
                overallResult = 'ফেল';
                resultColor = 'text-red-600';
            }
        }
        
        rowHtml += `
            <td class="px-4 py-3 text-center bg-gray-50 border-l-2 border-gray-300">
                <div class="text-sm font-bold text-gray-700">${subjectsWithMarks === currentExam.subjects.length ? toBengaliNumber(totalMarks) : '-'}</div>
                <div class="text-xs text-gray-500">${toBengaliNumber(totalPossible)}</div>
            </td>
            <td class="px-4 py-3 text-center bg-gray-50">
                <span class="px-2 py-1 text-sm font-bold ${getGradeColor(overallGrade)} rounded-full">
                    ${overallGrade || '-'}
                </span>
            </td>
            <td class="px-4 py-3 text-center bg-gray-50">
                <span class="text-sm font-bold ${resultColor}">
                    ${overallResult || '-'}
                </span>
            </td>
        `;
        
        row.innerHTML = rowHtml;
        tableBody.appendChild(row);
    });
}

// Update attendance for specific subject
function updateAttendanceForSubject(studentId, subjectId, isPresent) {
    if (!currentExam || !studentId || !subjectId) {
        console.warn('Cannot update attendance: missing required data');
        return;
    }
    
    const studentKey = `${currentExam.id}_${subjectId}_${studentId}`;
    if (!marks[studentKey]) {
        marks[studentKey] = { present: true, marks: '', grade: '', result: '' };
    }
    
    marks[studentKey].present = isPresent;
    
    if (!isPresent) {
        marks[studentKey].marks = '';
        marks[studentKey].grade = '';
        marks[studentKey].result = 'অনুপস্থিত';
    } else {
        marks[studentKey].result = '';
    }
    
    renderMarksTableWithSubjects();
}

// Update marks for specific subject
function updateMarksForSubject(studentId, subjectId, marksValue) {
    if (!currentExam || !studentId || !subjectId) {
        console.warn('Cannot update marks: missing required data');
        return;
    }
    
    const studentKey = `${currentExam.id}_${subjectId}_${studentId}`;
    if (!marks[studentKey]) {
        marks[studentKey] = { present: true, marks: '', grade: '', result: '' };
    }
    
    marks[studentKey].marks = marksValue;
    
    // Find the subject to get total marks and pass marks
    const subject = examSubjects.find(s => s.id == subjectId);
    
    if (marksValue && subject) {
        const numMarks = parseFloat(marksValue);
        const totalMarks = subject.total_marks || subject.fullMarks || 100;
        const passMarks = subject.pass_marks || (totalMarks * 0.33);
        
        // Calculate grade based on 33% rule
        const percentage = (numMarks / totalMarks) * 100;
        let grade = '';
        let result = '';
        
        if (percentage < 33) {
            grade = 'ফেল';
            result = 'ফেল';
        } else {
            if (percentage >= 80) grade = 'A+';
            else if (percentage >= 70) grade = 'A';
            else if (percentage >= 60) grade = 'A-';
            else if (percentage >= 50) grade = 'B';
            else if (percentage >= 40) grade = 'C';
            else if (percentage >= 33) grade = 'D';
            
            result = 'পাস';
        }
        
        marks[studentKey].grade = grade;
        marks[studentKey].result = result;
    } else {
        marks[studentKey].grade = '';
        marks[studentKey].result = '';
    }
    
    renderMarksTableWithSubjects();
}

// Get grade color
function getGradeColor(grade) {
    switch(grade) {
        case 'A+': return 'bg-gray-100 text-gray-800';
        case 'A': return 'bg-gray-100 text-gray-700';
        case 'A-': return 'bg-gray-100 text-gray-700';
        case 'B': return 'bg-gray-100 text-gray-700';
        case 'C': return 'bg-gray-100 text-gray-700';
        case 'D': return 'bg-gray-100 text-gray-700';
        case 'F': return 'bg-gray-100 text-gray-700';
        case 'ফেল': return 'bg-red-200 text-red-800 font-bold';
        default: return 'bg-gray-100 text-gray-700';
    }
}

// Get result color
function getResultColor(result) {
    switch(result) {
        case 'পাস': return 'bg-gray-100 text-gray-700';
        case 'ফেল': return 'bg-gray-100 text-gray-700';
        case 'অনুপস্থিত': return 'bg-gray-100 text-gray-700';
        default: return 'bg-gray-100 text-gray-700';
    }
}

// Save all marks
async function saveAllMarks() {
    if (!currentExam || !currentExam.subjects) {
        showErrorModal('প্রথমে একটি পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    const classId = document.getElementById('classSelect').value;
    if (!classId) {
        showErrorModal('প্রথমে একটি ক্লাস নির্বাচন করুন');
        return;
    }
    
    if (students.length === 0) {
        showErrorModal('কোন ছাত্র/ছাত্রী পাওয়া যায়নি');
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Save marks for each subject
        let successCount = 0;
        let errorCount = 0;
        
        for (const subject of currentExam.subjects) {
            const subjectMarksData = [];
            
            students.forEach(student => {
                if (!student || !student.id) {
                    console.warn('Invalid student data found, skipping');
                    return;
                }
                
                const studentKey = `${currentExam.id}_${subject.id}_${student.id}`;
                const studentMark = marks[studentKey] || { present: true, marks: '', grade: '', result: '' };
                
                subjectMarksData.push({
                    student_id: student.id,
                    marks: studentMark.marks || null,
                    is_present: studentMark.present
                });
            });
            
            try {
                const response = await fetch('/marks/api/marks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        exam_id: currentExam.id,
                        subject_id: subject.id,
                        marks: subjectMarksData
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    successCount++;
                } else {
                    errorCount++;
                    console.error(`Error saving marks for subject ${subject.name}:`, data.message);
                }
            } catch (error) {
                errorCount++;
                console.error(`Error saving marks for subject ${subject.name}:`, error);
            }
        }
        
        if (successCount > 0) {
            showSuccessModal(`${toBengaliNumber(successCount)}টি বিষয়ের মার্ক সফলভাবে সংরক্ষণ করা হয়েছে!${errorCount > 0 ? ` (${toBengaliNumber(errorCount)}টি বিষয়ে সমস্যা হয়েছে)` : ''}`);
            
            // Reload marks to show updated data
            await loadAllMarks();
            renderMarksTableWithSubjects();
        } else {
            showErrorModal('কোন মার্ক সংরক্ষণ করা যায়নি');
        }
        
    } catch (error) {
        console.error('Error saving marks:', error);
        showErrorModal('মার্ক সংরক্ষণ করতে সমস্যা হয়েছে: ' + error.message);
    }
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

// Show success modal
function showSuccessModal(message) {
    const modalHtml = `
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">সফল!</h3>
                    <p class="text-gray-700 text-lg">${message}</p>
                </div>
                
                <button onclick="closeSuccessModal()" class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-bold transition-colors">
                    ঠিক আছে
                </button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        closeSuccessModal();
    }, 3000);
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.remove();
    }
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

// Modal variables
let modalStudentId = null;
let modalSubjectId = null;
let currentStudentIndex = -1;
let currentSubjectIndex = -1;

// Bengali to English number conversion
function convertBengaliToEnglish(bengaliNumber) {
    const bengaliDigits = {'০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4', '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'};
    return bengaliNumber.toString().replace(/[০-৯]/g, d => bengaliDigits[d] || d);
}

// English to Bengali number conversion
function convertEnglishToBengali(englishNumber) {
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return englishNumber.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Auto-correct marks if greater than total marks (e.g., 1000 -> 100, 999 -> 99)
function autoCorrectMarks() {
    const input = document.getElementById('modalMarksInput');
    const totalMarks = parseFloat(document.getElementById('modalTotalMarks').textContent) || 100;
    
    let value = input.value;
    if (!value) return;
    
    // Convert Bengali to English
    const englishValue = convertBengaliToEnglish(value);
    let numericValue = parseFloat(englishValue);
    
    // If marks > total marks, try to extract the last 2 or 3 digits
    if (numericValue > totalMarks) {
        const valueStr = Math.floor(numericValue).toString();
        
        // If it's a 4-digit number like 1000, take last 3 digits (000 -> 0, but we want 100)
        // Better logic: divide by 10 until it's <= totalMarks
        while (numericValue > totalMarks && numericValue >= 100) {
            numericValue = Math.floor(numericValue / 10);
        }
        
        // If still greater, try modulo
        if (numericValue > totalMarks) {
            numericValue = numericValue % (totalMarks + 1);
        }
        
        // Update the input
        input.value = numericValue.toString();
        
        // Show a brief notification
        const originalColor = input.style.borderColor;
        input.style.borderColor = '#f59e0b'; // Orange warning
        setTimeout(() => {
            input.style.borderColor = originalColor;
        }, 1000);
        
        console.log(`Auto-corrected: ${englishValue} -> ${numericValue}`);
    }
    
    // Calculate grade after correction
    calculateGradeInModal();
}

// Handle input changes
function handleInputChange() {
    const input = document.getElementById('modalMarksInput');
    let value = input.value;
    
    // Convert Bengali numbers to English for processing
    const englishValue = convertBengaliToEnglish(value);
    
    // Remove any non-numeric characters except decimal point
    const numericValue = englishValue.replace(/[^0-9.]/g, '');
    
    // Update input with clean numeric value
    input.value = numericValue;
    
    // Calculate grade
    calculateGradeInModal();
}

// Handle marks input with Bengali/English support
function handleMarksInput(event) {
    const input = event.target;
    let value = input.value;
    
    // Convert Bengali numbers to English for processing
    const englishValue = convertBengaliToEnglish(value);
    
    // Remove any non-numeric characters except decimal point
    const numericValue = englishValue.replace(/[^0-9.]/g, '');
    
    // Update input with clean numeric value
    input.value = numericValue;
    
    // Don't call calculateGradeInModal here as it's called by other events
}

// Handle key down events
function handleKeyDown(event) {
    // Handle Enter key
    if (event.key === 'Enter') {
        event.preventDefault();
        saveAndMoveToNext();
    }
    
    // Allow: backspace, delete, tab, escape, enter, decimal point
    if ([46, 8, 9, 27, 13, 110, 190].indexOf(event.keyCode) !== -1 ||
        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (event.keyCode === 65 && event.ctrlKey === true) ||
        (event.keyCode === 67 && event.ctrlKey === true) ||
        (event.keyCode === 86 && event.ctrlKey === true) ||
        (event.keyCode === 88 && event.ctrlKey === true) ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        return;
    }
    
    // Allow Bengali digits (০-৯) and English digits (0-9)
    if (!((event.keyCode >= 48 && event.keyCode <= 57) || // 0-9
          (event.keyCode >= 96 && event.keyCode <= 105) || // numpad 0-9
          (event.key >= '০' && event.key <= '৯'))) { // Bengali digits
        event.preventDefault();
    }
}

// Save current marks and move to next student
async function saveAndMoveToNext() {
    if (modalStudentId === null || modalSubjectId === null) {
        console.error('Modal student or subject not set');
        return;
    }
    
    // Save current marks
    const success = await saveCurrentMarks();
    
    if (success) {
        // Find next student for the same subject
        const nextStudent = findNextStudent();
        
        if (nextStudent) {
            // Open modal for next student
            openMarksModal(nextStudent.id, modalSubjectId);
        } else {
            // No more students, close modal
            closeMarksModal();
            showSuccessModal('সকল ছাত্র/ছাত্রীর মার্ক সংরক্ষণ সম্পন্ন হয়েছে!');
        }
    }
}

// Save current marks to database
async function saveCurrentMarks() {
    try {
        const isPresent = document.getElementById('modalAttendance').checked;
        const obtainedMarks = document.getElementById('modalMarksInput').value;
        
        // Convert Bengali to English for database storage
        const englishMarks = convertBengaliToEnglish(obtainedMarks);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const response = await fetch('/marks/api/marks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                exam_id: currentExam.id,
                subject_id: modalSubjectId,
                marks: [{
                    student_id: modalStudentId,
                    marks: isPresent && englishMarks ? parseFloat(englishMarks) : null,
                    is_present: isPresent
                }]
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update local marks object
            const studentKey = `${currentExam.id}_${modalSubjectId}_${modalStudentId}`;
            const grade = document.getElementById('modalGrade').textContent;
            const result = document.getElementById('modalResult').textContent;
            
            marks[studentKey] = {
                present: isPresent,
                marks: isPresent && englishMarks ? parseFloat(englishMarks) : '',
                grade: isPresent && grade !== '-' ? grade : '',
                result: isPresent && result !== '-' ? result : ''
            };
            
            // Update table display
            renderMarksTableWithSubjects();
            
            return true;
        } else {
            showErrorModal('মার্ক সংরক্ষণ করতে সমস্যা হয়েছে: ' + data.message);
            return false;
        }
    } catch (error) {
        console.error('Error saving marks:', error);
        showErrorModal('মার্ক সংরক্ষণ করতে সমস্যা হয়েছে: ' + error.message);
        return false;
    }
}

// Find next student for marks entry
function findNextStudent() {
    if (!students || students.length === 0) return null;
    
    // Find current student index
    const currentIndex = students.findIndex(s => s.id === modalStudentId);
    
    if (currentIndex === -1) return null;
    
    // Return next student if exists
    if (currentIndex + 1 < students.length) {
        return students[currentIndex + 1];
    }
    
    return null; // No more students
}

// Open marks entry modal
function openMarksModal(studentId, subjectId) {
    console.log('openMarksModal called with:', { studentId, subjectId, studentsCount: students?.length, hasCurrentExam: !!currentExam, hasSubjects: !!currentExam?.subjects });
    
    if (!students || !currentExam || !currentExam.subjects) {
        console.error('Required data not available for modal', { students, currentExam });
        showErrorModal('প্রথমে পরীক্ষা এবং ক্লাস নির্বাচন করুন');
        return;
    }
    
    const student = students.find(s => s && s.id === studentId);
    const subject = currentExam.subjects.find(s => s && s.id == subjectId); // Use == for string/number comparison
    
    console.log('Found student:', student, 'Found subject:', subject);
    
    if (!student || !subject) {
        console.error('Student or subject not found', { studentId, subjectId, availableStudents: students.map(s => ({ id: s.id, name: s.name })), availableSubjects: currentExam.subjects.map(s => ({ id: s.id, name: s.name })) });
        showErrorModal('ছাত্র/ছাত্রী বা বিষয় পাওয়া যায়নি');
        return;
    }
    
    // Store current editing info
    modalStudentId = studentId;
    modalSubjectId = subjectId;
    
    // Get existing marks
    const studentKey = `${currentExam.id}_${subjectId}_${studentId}`;
    const studentMark = marks[studentKey] || { present: true, marks: '', grade: '', result: '' };
    
    // Populate modal
    document.getElementById('modalTitle').textContent = 'মার্ক এন্ট্রি';
    document.getElementById('modalStudentName').textContent = student.name;
    document.getElementById('modalStudentRoll').textContent = toBengaliNumber(student.roll_number);
    document.getElementById('modalStudentPhoto').src = student.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=10b981&color=fff&size=128`;
    
    // Add error handling for modal photo
    document.getElementById('modalStudentPhoto').onerror = function() {
        this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=10b981&color=fff&size=128`;
    };
    
    // Different display for groups vs individual subjects
    if (subject.type === 'group') {
        document.getElementById('modalSubjectName').innerHTML = `${subject.name} <span class="text-purple-600 text-sm">(গ্রুপ)</span>`;
    } else {
        document.getElementById('modalSubjectName').textContent = subject.name;
    }
    
    document.getElementById('modalTotalMarks').textContent = toBengaliNumber(subject.total_marks);
    document.getElementById('modalPassMarks').textContent = toBengaliNumber(subject.pass_marks);
    document.getElementById('modalAttendance').checked = studentMark.present;
    document.getElementById('modalMarksInput').value = studentMark.marks || '';
    document.getElementById('modalMarksInput').max = subject.total_marks;
    
    // Toggle marks input based on attendance
    toggleMarksInput();
    
    // Calculate grade if marks exist
    if (studentMark.marks) {
        calculateGradeInModal();
    } else {
        document.getElementById('modalGrade').textContent = '-';
        document.getElementById('modalResult').textContent = '-';
    }
    
    // Show modal
    document.getElementById('marksEntryModal').classList.remove('hidden');
    
    // Focus on marks input if present
    if (studentMark.present) {
        setTimeout(() => {
            document.getElementById('modalMarksInput').focus();
        }, 100);
    }
}

// Close marks modal
function closeMarksModal() {
    document.getElementById('marksEntryModal').classList.add('hidden');
    modalStudentId = null;
    modalSubjectId = null;
}

// Toggle marks input based on attendance
function toggleMarksInput() {
    const isPresent = document.getElementById('modalAttendance').checked;
    const marksInputSection = document.getElementById('marksInputSection');
    const marksInput = document.getElementById('modalMarksInput');
    
    if (isPresent) {
        marksInputSection.classList.remove('hidden');
        marksInput.disabled = false;
    } else {
        marksInputSection.classList.add('hidden');
        marksInput.disabled = true;
        marksInput.value = '';
        document.getElementById('modalGrade').textContent = '-';
        document.getElementById('modalResult').textContent = '-';
    }
}

// Calculate grade in modal
function calculateGradeInModal() {
    const marksInput = document.getElementById('modalMarksInput');
    let inputValue = marksInput.value;
    
    // Convert Bengali to English for calculation
    const englishValue = convertBengaliToEnglish(inputValue);
    const obtainedMarks = parseFloat(englishValue);
    
    if (!obtainedMarks || isNaN(obtainedMarks) || obtainedMarks === 0) {
        document.getElementById('modalGrade').textContent = '-';
        document.getElementById('modalResult').textContent = '-';
        return;
    }
    
    const subject = currentExam.subjects.find(s => s.id === modalSubjectId);
    if (!subject) {
        return;
    }
    
    // Validate marks
    if (obtainedMarks > subject.total_marks) {
        marksInput.value = subject.total_marks.toString();
        return;
    }
    
    if (obtainedMarks < 0) {
        marksInput.value = '0';
        return;
    }
    
    // Calculate percentage
    const percentage = (obtainedMarks / subject.total_marks) * 100;
    
    // Determine grade and result based on 33% rule
    let grade = '';
    let result = '';
    
    if (percentage < 33) {
        // Less than 33% is always fail
        grade = 'ফেল';
        result = 'ফেল';
    } else {
        // 33% or above - calculate grade normally
        if (percentage >= 80) grade = 'A+';
        else if (percentage >= 70) grade = 'A';
        else if (percentage >= 60) grade = 'A-';
        else if (percentage >= 50) grade = 'B';
        else if (percentage >= 40) grade = 'C';
        else if (percentage >= 33) grade = 'D';
        
        result = 'পাস';
    }
    
    // Update modal display
    document.getElementById('modalGrade').textContent = grade;
    document.getElementById('modalResult').textContent = result;
}

// Save marks from modal (for save button click)
async function saveMarksFromModal() {
    const success = await saveCurrentMarks();
    
    if (success) {
        closeMarksModal();
        showSuccessModal('মার্ক সফলভাবে সংরক্ষণ করা হয়েছে!');
    }
}
</script>
@endsection
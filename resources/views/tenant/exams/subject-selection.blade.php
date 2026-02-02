@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.drag-item {
    transition: all 0.2s ease;
    cursor: grab;
}

.drag-item:active {
    cursor: grabbing;
}

.drag-item.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
    z-index: 1000;
}

.drop-zone {
    min-height: 400px;
    transition: all 0.3s ease;
}

.drop-zone.drag-over {
    background-color: #f0f9ff;
    border-color: #3b82f6;
    transform: scale(1.02);
}

.subject-item {
    transition: all 0.2s ease;
}

.subject-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.slide-in {
    animation: slideIn 0.3s ease;
}
</style>

<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-600">পরীক্ষার বিষয় নির্বাচন</h1>
                <p class="text-gray-600 mt-1">পরীক্ষায় অন্তর্ভুক্ত বিষয়সমূহ নির্বাচন করুন</p>
            </div>
            <div class="flex gap-3">
                <button onclick="goBackToExams()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </button>
            </div>
        </div>

        <!-- Selection Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-800">পরীক্ষা এবং ক্লাস নির্বাচন করুন</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">পরীক্ষা নির্বাচন করুন *</label>
                    <select id="examSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="loadExamSubjects()">
                        <option value="">--- প্রথমে পরীক্ষা নির্বাচন করুন ---</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">ক্লাস নির্বাচন করুন *</label>
                    <select id="classSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="filterSubjectsByClass()" disabled>
                        <option value="">--- প্রথমে পরীক্ষা নির্বাচন করুন ---</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->section }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm text-blue-600" id="selectionInfo">নির্দিষ্ট ক্লাস: নেই</span>
            </div>
        </div>

        <!-- Drag & Drop Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">সব এর বিষয়সমূহ</h3>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-white text-sm">নির্বাচিত বিষয়</div>
                        <div class="text-2xl font-bold text-yellow-300" id="selectedCount">০</div>
                    </div>
                    <button onclick="addCustomSubject()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-3 rounded-lg font-bold flex items-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        নতুন বিষয় যোগ করুন
                    </button>
                    <button onclick="saveSelectedSubjects()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        সব বিষয় সংরক্ষণ করুন
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Search -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="searchSubjects" placeholder="বিষয় খুঁজুন..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" oninput="filterSubjects()">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Drag & Drop Interface -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Available Subjects (Left Side) -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">উপলব্ধ বিষয়সমূহ</h3>
                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium" id="availableCount">০</span>
                        </div>
                        <div id="availableSubjects" class="space-y-2 min-h-[400px] border-2 border-dashed border-gray-300 rounded-lg p-4 drop-zone"
                             ondrop="handleDrop(event, 'available')" 
                             ondragover="handleDragOver(event)"
                             ondragenter="handleDragEnter(event)"
                             ondragleave="handleDragLeave(event)">
                            <!-- Available subjects will be loaded here -->
                        </div>
                    </div>

                    <!-- Subject Groups (Middle) -->
                    <div class="bg-yellow-50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-yellow-800">বিষয় গ্রুপ তৈরি</h3>
                            <button onclick="createNewGroup()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-lg text-sm font-medium">
                                নতুন গ্রুপ
                            </button>
                        </div>
                        <div class="text-sm text-yellow-600 mb-4">
                            একাধিক বিষয় একসাথে করুন (যেমন: আরবি+বাংলা)
                        </div>
                        <div id="subjectGroups" class="space-y-3 min-h-[400px]">
                            <!-- Subject groups will be created here -->
                        </div>
                    </div>

                    <!-- Selected Subjects (Right Side) -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-blue-800">নির্বাচিত বিষয়সমূহ</h3>
                            <span class="bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm font-medium" id="selectedCountSide">০</span>
                        </div>
                        <div class="text-sm text-blue-600 mb-4">
                            পরীক্ষায় অন্তর্ভুক্ত বিষয়সমূহ
                        </div>
                        <div id="selectedSubjects" class="space-y-2 min-h-[400px] border-2 border-dashed border-blue-300 rounded-lg p-4 drop-zone"
                             ondrop="handleDrop(event, 'selected')" 
                             ondragover="handleDragOver(event)"
                             ondragenter="handleDragEnter(event)"
                             ondragleave="handleDragLeave(event)">
                            <!-- Selected subjects will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button onclick="selectAllAvailable()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                        সব নির্বাচন করুন
                    </button>
                    <button onclick="clearAllSelections()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                        সব ক্লিয়ার করুন
                    </button>
                    <button onclick="saveSelectedSubjects()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                        সংরক্ষণ করুন
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allSubjects = [];
let selectedSubjects = [];
let subjectGroups = [];
let currentExamId = null;
let currentClassId = null;
let draggedElement = null;
let groupCounter = 0;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // First load all subjects
    loadAllSubjects();
    
    // Check if exam_id and class_id are provided in URL
    const urlParams = new URLSearchParams(window.location.search);
    const examId = urlParams.get('exam_id');
    const classId = urlParams.get('class_id');
    
    if (examId) {
        setTimeout(() => {
            document.getElementById('examSelect').value = examId;
            loadExamSubjects();
            
            // If class_id is also provided, select it after exam is loaded
            if (classId) {
                setTimeout(() => {
                    document.getElementById('classSelect').value = classId;
                    filterSubjectsByClass();
                }, 1000);
            }
        }, 500);
    } else {
        // If no exam_id in URL, start with empty selection
        selectedSubjects = [];
        subjectGroups = []; // Clear groups on page load
        currentExamId = null;
        currentClassId = null;
    }
});

// Load all subjects from database
async function loadAllSubjects() {
    try {
        const response = await fetch('/subjects/api/subjects');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const subjects = await response.json();
        allSubjects = subjects;
        console.log('Loaded subjects:', allSubjects);
        console.log('Subject structure sample:', allSubjects[0]);
        renderSubjects();
    } catch (error) {
        console.error('Error loading subjects:', error);
        showEmptyState();
    }
}

// Load exam subjects from database
async function loadExamSubjects() {
    const examId = document.getElementById('examSelect').value;
    const classSelect = document.getElementById('classSelect');
    
    currentExamId = examId;
    
    // Update URL with exam_id
    updateURL();
    
    if (!examId) {
        selectedSubjects = [];
        subjectGroups = []; // Clear groups when no exam selected
        classSelect.disabled = true;
        classSelect.innerHTML = '<option value="">--- প্রথমে পরীক্ষা নির্বাচন করুন ---</option>';
        currentClassId = null;
        renderSubjects();
        return;
    }
    
    // Enable class selection
    classSelect.disabled = false;
    classSelect.innerHTML = '<option value="">--- ক্লাস নির্বাচন করুন ---</option>';
    @foreach($classes as $class)
    classSelect.innerHTML += '<option value="{{ $class->id }}">{{ $class->name }} - {{ $class->section }}</option>';
    @endforeach
    
    // Clear previous selections and groups
    selectedSubjects = [];
    subjectGroups = []; // Clear groups when exam changes
    currentClassId = null;
    renderSubjects();
}

// Load exam subjects for specific class
async function loadExamSubjectsForClass(examId, classId) {
    if (!examId || !classId) {
        selectedSubjects = [];
        subjectGroups = []; // Clear groups when no class selected
        return;
    }
    
    try {
        // Load existing exam subjects
        const response = await fetch(`/exams/api/exam-subjects/${examId}?class_id=${classId}`);
        if (response.ok) {
            const examSubjects = await response.json();
            selectedSubjects = examSubjects.map(s => s.id);
            console.log('Loaded exam subjects for class:', selectedSubjects);
        } else {
            selectedSubjects = [];
        }
        
        // Load existing subject groups for this exam and class
        try {
            const groupResponse = await fetch(`/exams/api/subject-groups/${examId}?class_id=${classId}`);
            if (groupResponse.ok) {
                const existingGroups = await groupResponse.json();
                subjectGroups = existingGroups.map(group => ({
                    id: group.id.toString(),
                    name: group.name,
                    subjects: group.subject_ids || [],
                    totalMarks: group.total_marks || 100,
                    passMarks: group.pass_marks || 33
                }));
                
                // Add group IDs to selected subjects
                existingGroups.forEach(group => {
                    const groupSubjectId = `group_${group.id}`;
                    if (!selectedSubjects.includes(groupSubjectId)) {
                        selectedSubjects.push(groupSubjectId);
                    }
                });
                
                // Update group counter to avoid ID conflicts
                if (subjectGroups.length > 0) {
                    const maxId = Math.max(...subjectGroups.map(g => parseInt(g.id) || 0));
                    groupCounter = Math.max(groupCounter, maxId);
                }
                
                console.log('Loaded existing subject groups:', subjectGroups);
                console.log('Updated selected subjects with groups:', selectedSubjects);
            } else {
                subjectGroups = [];
            }
        } catch (error) {
            console.log('No existing subject groups found or error loading them:', error);
            subjectGroups = [];
        }
        
        // Re-render after loading
        renderSubjects();
    } catch (error) {
        console.error('Error loading exam subjects for class:', error);
        selectedSubjects = [];
        subjectGroups = [];
        renderSubjects();
    }
}

// Filter subjects by class
function filterSubjectsByClass() {
    const examId = document.getElementById('examSelect').value;
    const classId = document.getElementById('classSelect').value;
    
    if (!examId) {
        showErrorModal('প্রথমে একটি পরীক্ষা নির্বাচন করুন');
        document.getElementById('classSelect').value = '';
        return;
    }
    
    currentClassId = classId;
    
    // Update URL with both exam_id and class_id
    updateURL();
    
    if (!classId) {
        currentClassId = null;
        selectedSubjects = [];
        subjectGroups = []; // Clear groups when no class selected
        document.getElementById('selectionInfo').textContent = 'নির্দিষ্ট ক্লাস: নেই';
        
        // Update URL to remove class_id
        updateURL();
        
        renderSubjects();
        return;
    }
    
    // Clear previous selections and groups when changing class
    selectedSubjects = [];
    subjectGroups = [];
    
    const classSelect = document.getElementById('classSelect');
    const selectedOption = classSelect.options[classSelect.selectedIndex];
    const className = selectedOption.text;
    
    console.log('Class selected:', classId, className);
    console.log('All subjects:', allSubjects);
    console.log('Subjects for class:', allSubjects.filter(s => s.class_id == classId));
    
    document.getElementById('selectionInfo').textContent = `নির্দিষ্ট ক্লাস: ${className}`;
    
    // Load previously selected subjects and groups for this exam and class
    loadExamSubjectsForClass(examId, classId);
}

// Filter subjects by search
function filterSubjects() {
    renderSubjects();
}

// Render subjects in both sides
function renderSubjects() {
    renderAvailableSubjects();
    renderSelectedSubjects();
    renderSubjectGroups();
    updateCounts();
}

// Render available subjects (left side)
function renderAvailableSubjects() {
    const container = document.getElementById('availableSubjects');
    const searchTerm = document.getElementById('searchSubjects').value.toLowerCase();
    
    console.log('renderAvailableSubjects called with currentClassId:', currentClassId);
    console.log('Total subjects:', allSubjects.length);
    console.log('Current subject groups:', subjectGroups);
    
    // Show message if no class is selected
    if (!currentClassId) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-lg font-medium">প্রথমে একটি ক্লাস নির্বাচন করুন</p>
                <p class="text-sm mt-1">উপরে থেকে ক্লাস নির্বাচন করুন</p>
            </div>
        `;
        return;
    }
    
    // Get all subject IDs that are in groups
    const subjectsInGroups = [];
    subjectGroups.forEach(group => {
        if (group.subjects && Array.isArray(group.subjects)) {
            subjectsInGroups.push(...group.subjects);
        }
    });
    
    console.log('Subjects in groups:', subjectsInGroups);
    
    let filteredSubjects = allSubjects.filter(subject => {
        const matchesSearch = subject.name.toLowerCase().includes(searchTerm) || 
                             subject.code.toLowerCase().includes(searchTerm);
        
        // Check if subject belongs to the selected class
        let matchesClass = true;
        if (currentClassId) {
            // First check class_id (primary relationship)
            if (subject.class_id) {
                matchesClass = subject.class_id == currentClassId;
            } 
            // Fallback to classes array if class_id is not set
            else if (subject.classes && Array.isArray(subject.classes)) {
                matchesClass = subject.classes.some(cls => {
                    // Handle different formats of class data
                    if (typeof cls === 'object' && cls.id) {
                        return cls.id == currentClassId;
                    } else if (typeof cls === 'string' || typeof cls === 'number') {
                        return cls == currentClassId;
                    }
                    return false;
                });
            } else {
                matchesClass = false;
            }
        }
        
        // Check if subject is in any group
        const inGroup = subjectsInGroups.includes(subject.id);
        
        // Check if subject is individually selected
        const notSelected = !selectedSubjects.includes(subject.id);
        
        return matchesSearch && matchesClass && notSelected && !inGroup;
    });
    
    container.innerHTML = '';
    
    if (filteredSubjects.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p>এই ক্লাসের জন্য কোন বিষয় উপলব্ধ নেই</p>
                <p class="text-sm mt-1">সব বিষয় ইতিমধ্যে নির্বাচিত বা গ্রুপে যোগ করা হয়েছে</p>
            </div>
        `;
    } else {
        filteredSubjects.forEach(subject => {
            const subjectElement = createSubjectElement(subject, 'available');
            container.appendChild(subjectElement);
        });
    }
}

// Render selected subjects (right side)
function renderSelectedSubjects() {
    const container = document.getElementById('selectedSubjects');
    
    // Get individual subjects
    const individualSubjectIds = selectedSubjects.filter(id => !id.toString().startsWith('group_'));
    const selectedSubjectsList = allSubjects.filter(subject => individualSubjectIds.includes(subject.id));
    
    // Get group subjects
    const groupSubjectIds = selectedSubjects.filter(id => id.toString().startsWith('group_'));
    
    container.innerHTML = '';
    
    if (selectedSubjectsList.length === 0 && groupSubjectIds.length === 0) {
        const currentClassText = currentClassId ? 
            document.getElementById('classSelect').options[document.getElementById('classSelect').selectedIndex].text : 
            'কোন ক্লাস';
            
        container.innerHTML = `
            <div class="text-center text-blue-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <p class="text-lg font-medium">কোন বিষয় নির্বাচিত নেই</p>
                <p class="text-sm mt-1">${currentClassId ? `${currentClassText} ক্লাসের জন্য বিষয় নির্বাচন করুন` : 'বাম পাশ থেকে বিষয় যোগ করুন'}</p>
            </div>
        `;
    } else {
        // Render individual subjects
        selectedSubjectsList.forEach(subject => {
            const subjectElement = createSubjectElement(subject, 'selected');
            container.appendChild(subjectElement);
        });
        
        // Render group subjects
        groupSubjectIds.forEach(groupSubjectId => {
            const groupId = groupSubjectId.replace('group_', '');
            const group = subjectGroups.find(g => g.id === groupId);
            
            if (group) {
                const groupElement = createGroupElement(group, 'selected');
                container.appendChild(groupElement);
            }
        });
    }
}

// Create group element for display
function createGroupElement(group, type) {
    const div = document.createElement('div');
    div.className = `subject-item p-3 rounded-lg border-2 cursor-pointer transition-all duration-200 slide-in ${
        type === 'available' 
            ? 'bg-white border-gray-200 hover:border-green-400 hover:shadow-md' 
            : 'bg-purple-100 border-purple-200 hover:border-purple-400'
    }`;
    
    const groupSubjectId = `group_${group.id}`;
    div.dataset.subjectId = groupSubjectId;
    div.dataset.subjectType = type;
    
    const subjectNames = group.subjects.map(subjectId => {
        const subject = allSubjects.find(s => s.id === subjectId);
        return subject ? subject.name : `বিষয় ${subjectId}`;
    }).join(' + ');
    
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">${group.name}</h4>
                <p class="text-sm text-gray-600">${subjectNames}</p>
                <p class="text-xs text-purple-600 mt-1">গ্রুপ: ${toBengaliNumber(group.subjects.length)}টি বিষয় • ${toBengaliNumber(group.totalMarks)} নম্বর</p>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                ${type === 'selected' 
                    ? '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'
                    : '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>'
                }
            </div>
        </div>
    `;
    
    // Add click event
    div.addEventListener('click', () => {
        if (type === 'selected') {
            removeGroupFromExam(group.id);
        }
    });
    
    return div;
}

// Remove group from exam
async function removeGroupFromExam(groupId) {
    showConfirmModal('আপনি কি এই গ্রুপটি সরাতে চান?', async () => {
        const groupSubjectId = `group_${groupId}`;
        selectedSubjects = selectedSubjects.filter(id => id !== groupSubjectId);
        
        // If this is a saved exam and class, update the database
        if (currentExamId && currentClassId) {
            await saveSelectedSubjects();
        } else {
            renderSubjects();
        }
    });
}

// Create subject element
function createSubjectElement(subject, type) {
    const div = document.createElement('div');
    div.className = `subject-item p-3 rounded-lg border-2 cursor-pointer transition-all duration-200 drag-item slide-in ${
        type === 'available' 
            ? 'bg-white border-gray-200 hover:border-green-400 hover:shadow-md' 
            : 'bg-blue-100 border-blue-200 hover:border-blue-400'
    }`;
    div.draggable = true;
    div.dataset.subjectId = subject.id;
    div.dataset.subjectType = type;
    
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">${subject.name}</h4>
                <p class="text-sm text-gray-600">${subject.code} • ${toBengaliNumber(subject.fullMarks || subject.totalMarks || 100)} নম্বর</p>
                ${subject.isGroup ? `<div class="text-xs text-purple-600 mt-1">গ্রুপ: ${subject.subjects.length}টি বিষয়</div>` : ''}
            </div>
            <div class="flex items-center gap-2">
                ${subject.isGroup 
                    ? '<svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
                    : type === 'available' 
                        ? '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>'
                        : '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'
                }
            </div>
        </div>
    `;
    
    // Add click event
    div.addEventListener('click', () => {
        if (type === 'available') {
            addSubjectToExam(subject.id);
        } else {
            // For selected subjects, handle removal
            if (subject.isGroup) {
                removeGroupFromExam(subject.groupId);
            } else {
                removeSubjectFromExam(subject.id);
            }
        }
    });
    
    // Add drag events
    div.addEventListener('dragstart', handleDragStart);
    div.addEventListener('dragend', handleDragEnd);
    
    return div;
}

// Drag and Drop handlers
function handleDragStart(e) {
    draggedElement = e.target;
    e.target.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragEnd(e) {
    e.target.classList.remove('dragging');
    draggedElement = null;
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
}

function handleDragEnter(e) {
    e.preventDefault();
    e.target.closest('.drop-zone').classList.add('drag-over');
}

function handleDragLeave(e) {
    if (!e.target.closest('.drop-zone').contains(e.relatedTarget)) {
        e.target.closest('.drop-zone').classList.remove('drag-over');
    }
}

function handleDrop(e, targetType) {
    e.preventDefault();
    e.target.closest('.drop-zone').classList.remove('drag-over');
    
    if (!draggedElement) return;
    
    const subjectId = parseInt(draggedElement.dataset.subjectId);
    const sourceType = draggedElement.dataset.subjectType;
    
    if (targetType === 'selected' && sourceType === 'available') {
        addSubjectToExam(subjectId);
    } else if (targetType === 'available' && sourceType === 'selected') {
        removeSubjectFromExam(subjectId);
    }
}

// Add subject to exam
async function addSubjectToExam(subjectId) {
    if (!selectedSubjects.includes(subjectId)) {
        selectedSubjects.push(subjectId);
        
        // If this is a saved exam and class, update the database
        if (currentExamId && currentClassId) {
            await saveSelectedSubjects();
        } else {
            renderSubjects();
        }
    }
}

// Remove subject from exam
async function removeSubjectFromExam(subjectId) {
    showConfirmModal('আপনি কি এই বিষয়টি সরাতে চান?', async () => {
        selectedSubjects = selectedSubjects.filter(id => id !== subjectId);
        
        // If this is a saved exam and class, update the database
        if (currentExamId && currentClassId) {
            await saveSelectedSubjects();
        } else {
            renderSubjects();
        }
    });
}

// Update counts
function updateCounts() {
    const searchTerm = document.getElementById('searchSubjects').value.toLowerCase();
    
    // Get all subject IDs that are in groups
    const subjectsInGroups = [];
    subjectGroups.forEach(group => {
        if (group.subjects && Array.isArray(group.subjects)) {
            subjectsInGroups.push(...group.subjects);
        }
    });
    
    const availableList = allSubjects.filter(subject => {
        const matchesSearch = subject.name.toLowerCase().includes(searchTerm) || 
                             subject.code.toLowerCase().includes(searchTerm);
        
        // Check if subject belongs to the selected class
        let matchesClass = true;
        if (currentClassId) {
            // First check class_id (primary relationship)
            if (subject.class_id) {
                matchesClass = subject.class_id == currentClassId;
            } 
            // Fallback to classes array if class_id is not set
            else if (subject.classes && Array.isArray(subject.classes)) {
                matchesClass = subject.classes.some(cls => {
                    // Handle different formats of class data
                    if (typeof cls === 'object' && cls.id) {
                        return cls.id == currentClassId;
                    } else if (typeof cls === 'string' || typeof cls === 'number') {
                        return cls == currentClassId;
                    }
                    return false;
                });
            } else {
                matchesClass = false;
            }
        }
        
        const notSelected = !selectedSubjects.includes(subject.id);
        
        // Check if subject is in any group
        const inGroup = subjectsInGroups.includes(subject.id);
        
        return matchesSearch && matchesClass && notSelected && !inGroup;
    });
    
    document.getElementById('availableCount').textContent = toBengaliNumber(availableList.length);
    document.getElementById('selectedCount').textContent = toBengaliNumber(selectedSubjects.length);
    document.getElementById('selectedCountSide').textContent = toBengaliNumber(selectedSubjects.length);
}

// Clear all selections
async function clearAllSelections() {
    if (!currentExamId || !currentClassId) {
        selectedSubjects = [];
        subjectGroups = [];
        renderSubjects();
        return;
    }
    
    try {
        // Delete subject groups from database
        const response = await fetch(`/exams/api/subject-groups/${currentExamId}?class_id=${currentClassId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            console.log('Subject groups deleted from database');
        }
    } catch (error) {
        console.error('Error deleting subject groups:', error);
    }
    
    selectedSubjects = [];
    subjectGroups = []; // Clear all groups as well
    renderSubjects();
}

// Select all available
function selectAllAvailable() {
    const searchTerm = document.getElementById('searchSubjects').value.toLowerCase();
    
    // Get all subject IDs that are in groups
    const subjectsInGroups = [];
    subjectGroups.forEach(group => {
        if (group.subjects && Array.isArray(group.subjects)) {
            subjectsInGroups.push(...group.subjects);
        }
    });
    
    const availableList = allSubjects.filter(subject => {
        const matchesSearch = subject.name.toLowerCase().includes(searchTerm) || 
                             subject.code.toLowerCase().includes(searchTerm);
        
        // Check if subject belongs to the selected class
        let matchesClass = true;
        if (currentClassId) {
            // First check class_id (primary relationship)
            if (subject.class_id) {
                matchesClass = subject.class_id == currentClassId;
            } 
            // Fallback to classes array if class_id is not set
            else if (subject.classes && Array.isArray(subject.classes)) {
                matchesClass = subject.classes.some(cls => {
                    // Handle different formats of class data
                    if (typeof cls === 'object' && cls.id) {
                        return cls.id == currentClassId;
                    } else if (typeof cls === 'string' || typeof cls === 'number') {
                        return cls == currentClassId;
                    }
                    return false;
                });
            } else {
                matchesClass = false;
            }
        }
        
        const notSelected = !selectedSubjects.includes(subject.id);
        
        // Check if subject is in any group
        const inGroup = subjectsInGroups.includes(subject.id);
        
        return matchesSearch && matchesClass && notSelected && !inGroup;
    });
    
    availableList.forEach(subject => {
        if (!selectedSubjects.includes(subject.id)) {
            selectedSubjects.push(subject.id);
        }
    });
    
    renderSubjects();
}

// Save selected subjects
async function saveSelectedSubjects() {
    if (!currentExamId) {
        showErrorModal('প্রথমে একটি পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    if (!currentClassId) {
        showErrorModal('প্রথমে একটি ক্লাস নির্বাচন করুন');
        return;
    }
    
    // Allow empty selection for removal purposes
    if (selectedSubjects.length === 0) {
        // If no subjects selected, we're clearing all selections
        console.log('Clearing all selections for exam and class');
    } else {
        // Additional validation: check if we have valid subjects or groups
        let hasValidSelection = false;
        
        // Check for individual subjects
        const individualSubjects = selectedSubjects.filter(id => !id.toString().startsWith('group_'));
        if (individualSubjects.length > 0) {
            hasValidSelection = true;
        }
        
        // Check for groups with subjects
        const groupSubjects = selectedSubjects.filter(id => id.toString().startsWith('group_'));
        for (const groupId of groupSubjects) {
            const groupIdOnly = groupId.replace('group_', '');
            const group = subjectGroups.find(g => g.id === groupIdOnly);
            if (group && group.subjects && group.subjects.length > 0) {
                hasValidSelection = true;
                break;
            }
        }
        
        if (!hasValidSelection) {
            showErrorModal('কোন বৈধ বিষয় বা গ্রুপ নির্বাচিত নেই। প্রথমে বিষয় নির্বাচন করুন বা গ্রুপে বিষয় যোগ করুন।');
            return;
        }
    }
    
    try {
        console.log('Saving exam subjects...');
        console.log('Selected subjects:', selectedSubjects);
        console.log('Subject groups:', subjectGroups);
        console.log('Current exam ID:', currentExamId);
        console.log('Current class ID:', currentClassId);
        
        const requestData = {
            subjects: selectedSubjects,
            class_id: currentClassId,
            subject_groups: subjectGroups
        };
        
        console.log('Request data being sent:', requestData);
        
        const response = await fetch(`/exams/api/exam-subjects/${currentExamId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        const result = await response.json();
        console.log('Save response:', result);
        
        if (result.success) {
            const classSelect = document.getElementById('classSelect');
            const selectedOption = classSelect.options[classSelect.selectedIndex];
            const className = selectedOption.text;
            
            let message = '';
            if (selectedSubjects.length === 0) {
                message = `${className} ক্লাসের সকল বিষয় সফলভাবে সরানো হয়েছে!`;
            } else {
                message = `${className} ক্লাসের জন্য ${toBengaliNumber(selectedSubjects.length)}টি বিষয় সফলভাবে সংরক্ষণ করা হয়েছে!`;
                
                if (result.groups_count > 0) {
                    message += ` (${toBengaliNumber(result.groups_count)}টি গ্রুপ সহ)`;
                }
            }
            
            // Show custom centered success modal
            showSuccessModal(message);
            
            // Update URL to maintain state
            updateURL();
            
            // Re-render to show updated state
            renderSubjects();
        } else {
            console.error('Save failed:', result);
            let errorMessage = result.message || 'সংরক্ষণ করতে সমস্যা হয়েছে';
            
            // If there are validation errors, show them
            if (result.errors) {
                errorMessage += '\n\nবিস্তারিত:\n';
                Object.keys(result.errors).forEach(field => {
                    result.errors[field].forEach(error => {
                        errorMessage += `- ${error}\n`;
                    });
                });
            }
            
            showErrorModal(errorMessage);
        }
    } catch (error) {
        console.error('Error saving exam subjects:', error);
        
        let errorMessage = 'সংরক্ষণ করতে সমস্যা হয়েছে';
        
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
            errorMessage += '\n\nনেটওয়ার্ক সমস্যা হয়েছে। ইন্টারনেট সংযোগ চেক করুন।';
        } else {
            errorMessage += '\n\nবিস্তারিত: ' + error.message;
        }
        
        showErrorModal(errorMessage);
    }
}

// Show empty state
function showEmptyState() {
    document.getElementById('availableSubjects').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <p class="text-lg font-medium">কোন বিষয় পাওয়া যায়নি</p>
            <p class="text-sm mt-1">প্রথমে বিষয় তৈরি করুন</p>
        </div>
    `;
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

// Show confirmation modal
function showConfirmModal(message, onConfirm) {
    const modalHtml = `
        <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-yellow-600 mb-2">নিশ্চিত করুন</h3>
                    <p class="text-gray-700 text-lg">${message}</p>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="closeConfirmModal()" class="flex-1 px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-bold transition-colors">
                        না
                    </button>
                    <button onclick="confirmAction()" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-colors">
                        হ্যাঁ
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Store the callback function
    window.confirmCallback = onConfirm;
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.remove();
    }
    window.confirmCallback = null;
}

function confirmAction() {
    if (window.confirmCallback) {
        window.confirmCallback();
    }
    closeConfirmModal();
}

// Helper function to convert to Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Update URL with current exam and class selection
function updateURL() {
    const url = new URL(window.location);
    
    if (currentExamId) {
        url.searchParams.set('exam_id', currentExamId);
    } else {
        url.searchParams.delete('exam_id');
    }
    
    if (currentClassId) {
        url.searchParams.set('class_id', currentClassId);
    } else {
        url.searchParams.delete('class_id');
    }
    
    // Update URL without reloading the page
    window.history.replaceState({}, '', url);
}

// Go back to exams page
function goBackToExams() {
    window.location.href = '/exams';
}

// Subject Group Functions
function createNewGroup() {
    groupCounter++;
    const groupId = `${groupCounter}`;
    
    const group = {
        id: groupId,
        name: `গ্রুপ ${toBengaliNumber(groupCounter)}`,
        subjects: [],
        totalMarks: 100,
        passMarks: 33
    };
    
    subjectGroups.push(group);
    console.log('Created new group:', group);
    renderSubjectGroups();
}

function renderSubjectGroups() {
    const container = document.getElementById('subjectGroups');
    
    if (subjectGroups.length === 0) {
        container.innerHTML = `
            <div class="text-center text-yellow-600 py-8">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-sm">কোন গ্রুপ নেই</p>
                <p class="text-xs mt-1">নতুন গ্রুপ তৈরি করুন</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = subjectGroups.map(group => {
        // Get subject names for display
        const subjectNames = group.subjects.map(subjectId => {
            const subject = allSubjects.find(s => s.id === subjectId);
            return subject ? subject.name : `বিষয় ${subjectId}`;
        }).join(' + ');
        
        return `
            <div class="bg-white rounded-lg border-2 border-yellow-200 p-4 group-container" data-group-id="${group.id}">
                <div class="flex items-center justify-between mb-3">
                    <input type="text" value="${group.name}" 
                           class="font-bold text-gray-800 bg-transparent border-none outline-none group-name-input flex-1"
                           onchange="updateGroupName('${group.id}', this.value)">
                    <button onclick="deleteGroup('${group.id}')" class="text-red-500 hover:text-red-700 ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="text-sm text-gray-600 mb-3">
                    <strong>বিষয়সমূহ:</strong> ${subjectNames || 'কোন বিষয় নেই'}
                </div>
                
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="text-xs text-gray-600">পূর্ণমান</label>
                        <input type="number" value="${group.totalMarks}" min="1" max="200"
                               class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                               onchange="updateGroupMarks('${group.id}', 'totalMarks', this.value)">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600">পাস মার্ক</label>
                        <input type="number" value="${group.passMarks}" min="1" max="200"
                               class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                               onchange="updateGroupMarks('${group.id}', 'passMarks', this.value)">
                    </div>
                </div>
                
                <div class="min-h-[100px] border-2 border-dashed border-yellow-300 rounded-lg p-2 group-drop-zone"
                     ondrop="handleGroupDrop(event, '${group.id}')" 
                     ondragover="handleDragOver(event)"
                     ondragenter="handleDragEnter(event)"
                     ondragleave="handleDragLeave(event)">
                    ${group.subjects.length === 0 ? 
                        '<div class="text-center text-yellow-600 text-xs py-4">বিষয় এখানে টেনে আনুন</div>' :
                        group.subjects.map(subjectId => {
                            const subject = allSubjects.find(s => s.id === subjectId);
                            return subject ? `
                                <div class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs mb-1 flex items-center justify-between">
                                    <span>${subject.name}</span>
                                    <button onclick="removeFromGroup('${group.id}', ${subjectId})" class="text-yellow-600 hover:text-yellow-800">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            ` : '';
                        }).join('')
                    }
                </div>
                
                <button onclick="addGroupToSelected('${group.id}')" 
                        class="w-full mt-2 bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm font-medium"
                        ${group.subjects.length === 0 ? 'disabled' : ''}>
                    গ্রুপ যোগ করুন
                </button>
            </div>
        `;
    }).join('');
}

function updateGroupName(groupId, newName) {
    const group = subjectGroups.find(g => g.id === groupId);
    if (group) {
        group.name = newName;
    }
}

function updateGroupMarks(groupId, field, value) {
    const group = subjectGroups.find(g => g.id === groupId);
    if (group) {
        group[field] = parseInt(value) || 0;
    }
}

function deleteGroup(groupId) {
    showConfirmModal('আপনি কি এই গ্রুপটি মুছে ফেলতে চান?', () => {
        // Return subjects to available list and remove group from selected
        const group = subjectGroups.find(g => g.id === groupId);
        if (group) {
            // Remove individual subjects from selected if they were there
            group.subjects.forEach(subjectId => {
                if (selectedSubjects.includes(subjectId)) {
                    selectedSubjects = selectedSubjects.filter(id => id !== subjectId);
                }
            });
            
            // Remove group from selected subjects
            const groupSubjectId = `group_${groupId}`;
            selectedSubjects = selectedSubjects.filter(id => id !== groupSubjectId);
        }
        
        // Remove group from groups array
        subjectGroups = subjectGroups.filter(g => g.id !== groupId);
        
        // If this is an existing group (has numeric ID), delete from database
        if (currentExamId && currentClassId && !isNaN(groupId)) {
            deleteGroupFromDatabase(groupId);
        }
        
        renderSubjectGroups();
        renderSubjects();
    });
}

// Delete group from database
async function deleteGroupFromDatabase(groupId) {
    try {
        const response = await fetch(`/exams/api/subject-groups/${currentExamId}/group/${groupId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                class_id: currentClassId
            })
        });
        
        if (response.ok) {
            console.log('Group deleted from database successfully');
        } else {
            console.error('Failed to delete group from database');
        }
    } catch (error) {
        console.error('Error deleting group from database:', error);
    }
}

function handleGroupDrop(e, groupId) {
    e.preventDefault();
    e.target.closest('.group-drop-zone').classList.remove('drag-over');
    
    if (!draggedElement) return;
    
    const subjectId = parseInt(draggedElement.dataset.subjectId);
    const sourceType = draggedElement.dataset.subjectType;
    
    if (sourceType === 'available') {
        addSubjectToGroup(groupId, subjectId);
    }
}

function addSubjectToGroup(groupId, subjectId) {
    const group = subjectGroups.find(g => g.id === groupId);
    if (group && !group.subjects.includes(subjectId)) {
        group.subjects.push(subjectId);
        console.log(`Added subject ${subjectId} to group ${groupId}:`, group);
        renderSubjectGroups();
        renderSubjects();
    }
}

function removeFromGroup(groupId, subjectId) {
    const group = subjectGroups.find(g => g.id === groupId);
    if (group) {
        group.subjects = group.subjects.filter(id => id !== subjectId);
        renderSubjectGroups();
        renderSubjects();
    }
}

function addGroupToSelected(groupId) {
    const group = subjectGroups.find(g => g.id === groupId);
    if (group && group.subjects.length > 0) {
        // Add the group ID to selected subjects (this will be handled specially by backend)
        const groupSubjectId = `group_${groupId}`;
        
        if (!selectedSubjects.includes(groupSubjectId)) {
            selectedSubjects.push(groupSubjectId);
        }
        
        console.log(`Added group to selected: ${groupSubjectId}`, group);
        console.log('Current selected subjects:', selectedSubjects);
        console.log('Current subject groups:', subjectGroups);
        
        renderSubjects();
    }
}

// Add custom subject
function addCustomSubject() {
    const modalHtml = `
        <div id="customSubjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">নতুন বিষয় যোগ করুন</h3>
                    <button onclick="closeCustomSubjectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="customSubjectForm" onsubmit="saveCustomSubject(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের নাম *</label>
                            <input type="text" id="subjectName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="যেমন: গণিত">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিষয় কোড *</label>
                            <input type="text" id="subjectCode" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="যেমন: MATH101">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">সর্বোচ্চ নম্বর *</label>
                            <input type="number" id="fullMarks" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">পাশের নম্বর *</label>
                            <input type="number" id="passMarks" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="33">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের ধরন *</label>
                            <select id="subjectType" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                <option value="mandatory">বাধ্যতামূলক</option>
                                <option value="optional">ঐচ্ছিক</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস নির্বাচন করুন *</label>
                            <select id="selectedClass" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                <option value="">ক্লাস নির্বাচন করুন</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeCustomSubjectModal()" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                            বাতিল
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-bold hover:from-purple-700 hover:to-pink-700">
                            সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Load classes into the dropdown
    loadClassesForSubject();
}

function closeCustomSubjectModal() {
    const modal = document.getElementById('customSubjectModal');
    if (modal) {
        modal.remove();
    }
}

async function loadClassesForSubject() {
    try {
        const response = await fetch('/subjects/api/classes');
        const classes = await response.json();
        const select = document.getElementById('selectedClass');
        if (select) {
            select.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
            classes.forEach(cls => {
                select.innerHTML += `<option value="${cls.id}">${cls.name} - ${cls.section}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading classes:', error);
    }
}

async function saveCustomSubject(event) {
    event.preventDefault();
    
    const formData = {
        name: document.getElementById('subjectName').value,
        code: document.getElementById('subjectCode').value,
        fullMarks: document.getElementById('fullMarks').value,
        pass_marks: document.getElementById('passMarks').value,
        type: document.getElementById('subjectType').value,
        selectedClass: document.getElementById('selectedClass').value
    };
    
    try {
        const response = await fetch('/subjects/api/subjects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeCustomSubjectModal();
            showSuccessModal('বিষয় সফলভাবে যোগ করা হয়েছে!');
            loadAllSubjects(); // Reload subjects list
        } else {
            showErrorModal(result.message || 'বিষয় যোগ করতে সমস্যা হয়েছে');
        }
    } catch (error) {
        console.error('Error saving custom subject:', error);
        showErrorModal('বিষয় যোগ করতে সমস্যা হয়েছে');
    }
}
</script>
@endsection
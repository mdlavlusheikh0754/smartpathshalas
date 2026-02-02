@extends('layouts.tenant')

@section('content')
<div class="p-8 print-content">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between print-header">
            <div>
                <h1 class="text-3xl font-bold text-red-600">পরীক্ষার বিষয়</h1>
                <p class="text-gray-600 mt-1">পরীক্ষায় অন্তর্ভুক্ত বিষয়সমূহ যোগ করুন এবং পরিচালনা করুন।</p>
                <!-- Print-only information -->
                <div class="hidden print:block mt-2">
                    <p class="text-sm">প্রিন্ট তারিখ: {{ date('d/m/Y') }} | সময়: {{ date('H:i') }}</p>
                    <p class="text-sm">স্কুল: ইকরা নূরানিয়া একাডেমি</p>
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
                <button onclick="openAddModal()" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    নতুন বিষয় যোগ করুন
                </button>
                <a href="{{ route('tenant.exams.subject-selection') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    পরীক্ষার বিষয় ব্যবস্থাপনা
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 no-print">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-red-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalSubjects">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">সক্রিয় বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900" id="activeSubjects">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">তত্ত্বীয় বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900" id="theorySubjects">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">ব্যবহারিক বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900" id="practicalSubjects">০</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 no-print">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের নাম দিয়ে খুঁজুন</label>
                    <div class="relative">
                        <input type="text" id="searchByName" placeholder="বিষয়ের নাম দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" oninput="filterSubjects()">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের ধরন</label>
                    <select id="typeFilter" onchange="filterSubjects()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">সকল ধরন</option>
                        <option value="theory">তত্ত্বীয়</option>
                        <option value="practical">ব্যবহারিক</option>
                        <option value="both">উভয়</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="statusFilter" onchange="filterSubjects()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">সকল স্ট্যাটাস</option>
                        <option value="active">সক্রিয়</option>
                        <option value="inactive">নিষ্ক্রিয়</option>
                    </select>
                </div>

                <button onclick="resetFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                    ফিল্টার রিসেট করুন
                </button>
            </div>
        </div>

        <!-- Subjects Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">বিষয়ের তালিকা</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বিষয়ের নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বিষয় কোড</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বিষয়ের ধরন</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">পূর্ণমান</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">পাস মার্ক</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap no-print">পরিচালনা</th>
                        </tr>
                    </thead>
                    <tbody id="subjectsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        দেখাচ্ছে <span id="showingFrom">১</span> থেকে <span id="showingTo">১০</span> এর মধ্যে <span id="totalItems">০</span> টি
                    </div>
                    <div class="flex gap-2">
                        <button onclick="previousPage()" id="prevBtn" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            আগে
                        </button>
                        <span class="px-3 py-1 text-sm text-gray-700">
                            পৃষ্ঠা <span id="currentPage">১</span> / <span id="totalPages">১</span>
                        </span>
                        <button onclick="nextPage()" id="nextBtn" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            পরে
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="subjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">নতুন বিষয় যোগ করুন</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="subjectForm" onsubmit="saveSubject(event)">
            <input type="hidden" id="subjectId" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের নাম (বাংলা) *</label>
                    <input type="text" id="subjectNameBn" name="name_bn" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের নাম (English) *</label>
                    <input type="text" id="subjectNameEn" name="name_en" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয় কোড *</label>
                    <input type="text" id="subjectCode" name="code" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের ধরন *</label>
                    <select id="subjectType" name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">ধরন নির্বাচন করুন</option>
                        <option value="theory">তত্ত্বীয়</option>
                        <option value="practical">ব্যবহারিক</option>
                        <option value="both">উভয়</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ণমান *</label>
                    <input type="number" id="fullMarks" name="full_marks" required min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পাস মার্ক *</label>
                    <input type="number" id="passMarks" name="pass_marks" required min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="active">সক্রিয়</option>
                        <option value="inactive">নিষ্ক্রিয়</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বর্ণনা</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    বাতিল করুন
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-bold">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let subjects = [];
let currentPage = 1;
let itemsPerPage = 10;
let filteredSubjects = [...subjects];

// Helper functions for Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Filter subjects
function filterSubjects() {
    const nameFilter = document.getElementById('searchByName').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    filteredSubjects = subjects.filter(subject => {
        const matchesName = subject.name_bn.toLowerCase().includes(nameFilter) || 
                           subject.name_en.toLowerCase().includes(nameFilter) ||
                           subject.code.toLowerCase().includes(nameFilter);
        const matchesType = !typeFilter || subject.type === typeFilter;
        const matchesStatus = !statusFilter || subject.status === statusFilter;
        
        return matchesName && matchesType && matchesStatus;
    });
    
    currentPage = 1;
    renderSubjectsTable();
}

// Reset filters
function resetFilters() {
    document.getElementById('searchByName').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('statusFilter').value = '';
    
    filteredSubjects = [...subjects];
    currentPage = 1;
    renderSubjectsTable();
}

// Render subjects table
function renderSubjectsTable() {
    const tableBody = document.getElementById('subjectsTableBody');
    if (!tableBody) return;
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredSubjects.slice(startIndex, endIndex);
    
    if (pageData.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p class="text-lg font-medium">কোনো বিষয় পাওয়া যায়নি</p>
                        <p class="text-sm">নতুন বিষয় যোগ করতে উপরের বাটনে ক্লিক করুন</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = pageData.map(subject => {
        const typeConfig = {
            theory: { text: 'তত্ত্বীয়', class: 'bg-blue-100 text-blue-800' },
            practical: { text: 'ব্যবহারিক', class: 'bg-orange-100 text-orange-800' },
            both: { text: 'উভয়', class: 'bg-purple-100 text-purple-800' }
        };
        
        const statusConfig = {
            active: { text: 'সক্রিয়', class: 'bg-green-100 text-green-800' },
            inactive: { text: 'নিষ্ক্রিয়', class: 'bg-gray-100 text-gray-800' }
        };
        
        const type = typeConfig[subject.type] || typeConfig.theory;
        const status = statusConfig[subject.status] || statusConfig.active;
        
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${subject.name_bn}</div>
                    <div class="text-sm text-gray-500">${subject.name_en}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${subject.code}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${type.class}">
                        ${type.text}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${toBengaliNumber(subject.full_marks)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${toBengaliNumber(subject.pass_marks)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${status.class}">
                        ${status.text}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap no-print">
                    <div class="flex gap-2">
                        <button onclick="editSubject(${subject.id})" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            সম্পাদনা
                        </button>
                        <button onclick="deleteSubject(${subject.id})" class="text-red-600 hover:text-red-800 font-medium text-sm">
                            মুছুন
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    updatePagination();
    updateStats();
}

// Update pagination
function updatePagination() {
    const totalItems = filteredSubjects.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, totalItems);
    
    document.getElementById('showingFrom').textContent = toBengaliNumber(startIndex);
    document.getElementById('showingTo').textContent = toBengaliNumber(endIndex);
    document.getElementById('totalItems').textContent = toBengaliNumber(totalItems);
    document.getElementById('currentPage').textContent = toBengaliNumber(currentPage);
    document.getElementById('totalPages').textContent = toBengaliNumber(totalPages);
    
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
}

// Update statistics
function updateStats() {
    const total = subjects.length;
    const active = subjects.filter(s => s.status === 'active').length;
    const theory = subjects.filter(s => s.type === 'theory' || s.type === 'both').length;
    const practical = subjects.filter(s => s.type === 'practical' || s.type === 'both').length;
    
    document.getElementById('totalSubjects').textContent = toBengaliNumber(total);
    document.getElementById('activeSubjects').textContent = toBengaliNumber(active);
    document.getElementById('theorySubjects').textContent = toBengaliNumber(theory);
    document.getElementById('practicalSubjects').textContent = toBengaliNumber(practical);
}

// Pagination functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        renderSubjectsTable();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredSubjects.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderSubjectsTable();
    }
}

// Modal functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'নতুন বিষয় যোগ করুন';
    document.getElementById('subjectForm').reset();
    document.getElementById('subjectId').value = '';
    document.getElementById('subjectModal').classList.remove('hidden');
}

function editSubject(id) {
    const subject = subjects.find(s => s.id === id);
    if (!subject) return;
    
    document.getElementById('modalTitle').textContent = 'বিষয় সম্পাদনা করুন';
    document.getElementById('subjectId').value = subject.id;
    document.getElementById('subjectNameBn').value = subject.name_bn || '';
    document.getElementById('subjectNameEn').value = subject.name_en || '';
    document.getElementById('subjectCode').value = subject.code || '';
    document.getElementById('subjectType').value = subject.type || '';
    document.getElementById('fullMarks').value = subject.full_marks || '';
    document.getElementById('passMarks').value = subject.pass_marks || '';
    document.getElementById('status').value = subject.status || 'active';
    document.getElementById('description').value = subject.description || '';
    
    document.getElementById('subjectModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('subjectModal').classList.add('hidden');
}

function deleteSubject(id) {
    if (confirm('আপনি কি নিশ্চিত যে এই বিষয়টি মুছে ফেলতে চান?')) {
        subjects = subjects.filter(s => s.id !== id);
        filteredSubjects = filteredSubjects.filter(s => s.id !== id);
        renderSubjectsTable();
        alert('বিষয় সফলভাবে মুছে ফেলা হয়েছে!');
    }
}

function saveSubject(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const subjectData = Object.fromEntries(formData);
    
    if (subjectData.id) {
        // Edit existing subject
        const index = subjects.findIndex(s => s.id == subjectData.id);
        if (index !== -1) {
            subjects[index] = { ...subjects[index], ...subjectData };
        }
    } else {
        // Add new subject
        const newSubject = {
            id: subjects.length > 0 ? Math.max(...subjects.map(s => s.id)) + 1 : 1,
            ...subjectData
        };
        subjects.push(newSubject);
    }
    
    filteredSubjects = [...subjects];
    renderSubjectsTable();
    closeModal();
    alert('বিষয় সফলভাবে সংরক্ষণ করা হয়েছে!');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Exam Subjects Page Loaded');
    renderSubjectsTable();
});
</script>
@endpush
@endsection

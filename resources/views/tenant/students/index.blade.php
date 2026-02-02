@extends('layouts.tenant')

@section('content')
@php
// Helper function to convert English numbers to Bengali
function convertToBengaliNumbers($text) {
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($englishNumbers, $bengaliNumbers, $text);
}
@endphp
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">শিক্ষার্থী তালিকা</h1>
            <p class="text-gray-600 mt-1">সকল শিক্ষার্থীর তথ্য দেখুন এবং পরিচালনা করুন</p>
        </div>
        <div class="flex gap-4">
            <!-- Device Sync Buttons -->
            <button onclick="bulkSyncToDevice()" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                <i class="fas fa-sync"></i>সকল ছাত্র Device এ Sync করুন
            </button>
            <button onclick="checkSyncStatus()" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>Sync Status দেখুন
            </button>
            
            <a href="{{ route('tenant.students.admission-requests') }}" class="bg-white border border-gray-200 text-gray-600 hover:text-gray-800 hover:border-gray-300 px-6 py-3 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                ভর্তির আবেদন
                @if(isset($admissionApplications) && $admissionApplications->count() > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md animate-pulse">
                    {{ convertToBengaliNumbers($admissionApplications->count()) }}
                </span>
                @endif
            </a>
            <a href="{{ route('tenant.students.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                নতুন শিক্ষার্থী যোগ করুন
            </a>
            <a href="{{ route('tenant.students.login-management') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                লগইন ম্যানেজমেন্ট
            </a>
        </div>
    </div>


    <div id="myTabContent">
        <!-- Students Tab -->
        <div id="students" role="tabpanel" aria-labelledby="students-tab">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">মোট শিক্ষার্থী</p>
                    <h3 class="text-3xl font-bold mt-1">{{ convertToBengaliNumbers($totalStudents ?? 0) }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">ছাত্র</p>
                    <h3 class="text-3xl font-bold mt-1">{{ convertToBengaliNumbers($maleStudents ?? 0) }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm">ছাত্রী</p>
                    <h3 class="text-3xl font-bold mt-1">{{ convertToBengaliNumbers($femaleStudents ?? 0) }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">নতুন (এই মাসে)</p>
                    <h3 class="text-3xl font-bold mt-1">{{ convertToBengaliNumbers($newThisMonth ?? 0) }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" id="searchInput" placeholder="নাম দিয়ে খুঁজুন..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" oninput="filterStudents()">
            </div>
            <div>
                <select id="classFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterStudents()">
                    <option value="">সকল ক্লাস</option>
                    @php
                        try {
                            $classes = \App\Models\SchoolClass::active()->ordered()->get();
                        } catch (\Exception $e) {
                            $classes = collect();
                        }
                    @endphp
                    @foreach($classes as $class)
                        <option value="{{ $class->name }}">{{ $class->name }} শ্রেণী - {{ $class->section }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="sectionFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterStudents()">
                    <option value="">সকল সেকশন</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
            <div>
                <button onclick="resetFilters()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    রিসেট করুন
                </button>
            </div>
        </div>
        <div id="searchResults" class="mt-3 text-sm text-gray-600"></div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">রোল নং</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">নাম</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">ক্লাস</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">সেকশন</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">মোবাইল</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">অভিভাবক</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($students && $students->count() > 0)
                        @foreach($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors student-row" 
                            data-name="{{ strtolower($student->name_bn . ' ' . $student->name_en) }}" 
                            data-class="{{ $student->class }}" 
                            data-section="{{ $student->section }}">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ convertToBengaliNumbers($student->roll ?? 'প্রযোজ্য নয়') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0">
                                        <img src="{{ $student->photo_url }}" alt="{{ $student->name_bn }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $student->name_bn }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->name_en }}</p>
                                        <p class="text-xs text-blue-600 font-medium">{{ convertToBengaliNumbers($student->student_id) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $student->class }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $student->section }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ convertToBengaliNumbers($student->phone ?? 'প্রযোজ্য নয়') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div>
                                    <p class="font-medium">{{ $student->father_name }}</p>
                                    <p class="text-xs text-gray-500">{{ convertToBengaliNumbers($student->parent_phone) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('tenant.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="বিস্তারিত দেখুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('tenant.students.edit', $student->id) }}" class="text-green-600 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-colors" title="সম্পাদনা করুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete({{ $student->id }}, '{{ $student->name_bn }}')" class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="মুছে ফেলুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <!-- Hidden delete form -->
                                    <form id="deleteForm{{ $student->id }}" action="{{ route('tenant.students.destroy', $student->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">কোনো শিক্ষার্থী পাওয়া যায়নি</p>
                                <p class="text-sm text-gray-400 mt-1">নতুন শিক্ষার্থী যোগ করতে "নতুন শিক্ষার্থী যোগ করুন" বাটনে ক্লিক করুন</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-700">
                দেখানো হচ্ছে <span class="font-medium" id="showingFrom">১</span> থেকে <span class="font-medium" id="showingTo">১০</span> এর মধ্যে <span class="font-medium" id="totalRecords">১০</span> টি
            </div>
            <div class="flex gap-2" id="paginationButtons">
                <button onclick="changePage('prev')" id="prevBtn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors" disabled>পূর্ববর্তী</button>
                <button onclick="changePage(1)" class="px-4 py-2 bg-blue-600 text-white rounded-lg page-btn" data-page="1">১</button>
                <button onclick="changePage(2)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors page-btn" data-page="2">২</button>
                <button onclick="changePage(3)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors page-btn" data-page="3">৩</button>
                <button onclick="changePage('next')" id="nextBtn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">পরবর্তী</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে <span class="student-name font-semibold"></span> কে মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button onclick="submitDelete()" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Delete Confirmation Modal */
.delete-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.delete-modal.active {
    display: flex;
}

.delete-modal-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
let currentDeleteFormId = null;
let currentPage = 1;
let itemsPerPage = 10;
let allRows = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    allRows = Array.from(document.querySelectorAll('tbody tr.student-row'));
    updatePagination();
});

function confirmDelete(id, name) {
    currentDeleteFormId = id;
    // Update modal content with student name
    const modal = document.getElementById('deleteModal');
    const nameSpan = modal.querySelector('.student-name');
    if (nameSpan) {
        nameSpan.textContent = name;
    }
    modal.classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    currentDeleteFormId = null;
}

function submitDelete() {
    if (currentDeleteFormId) {
        const form = document.getElementById('deleteForm' + currentDeleteFormId);
        if (form) {
            // Show loading state
            const submitBtn = document.querySelector('#deleteModal button[onclick="submitDelete()"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'মুছে ফেলা হচ্ছে...';
            }
            form.submit();
        } else {
            console.error('Delete form not found for ID:', currentDeleteFormId);
            alert('ডিলিট ফর্ম পাওয়া যায়নি। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।');
            closeDeleteModal();
        }
    } else {
        console.error('No delete form ID set');
        alert('কোনো শিক্ষার্থী নির্বাচিত নেই। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।');
        closeDeleteModal();
    }
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Real-time search and filter
function filterStudents() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const classFilter = document.getElementById('classFilter').value.toLowerCase();
    const sectionFilter = document.getElementById('sectionFilter').value.toUpperCase();
    
    const rows = document.querySelectorAll('tbody tr.student-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const nameCell = row.querySelector('td:nth-child(2)');
        const classCell = row.querySelector('td:nth-child(3)');
        const sectionCell = row.querySelector('td:nth-child(4)');
        
        if (!nameCell || !classCell || !sectionCell) return;
        
        const name = nameCell.textContent.toLowerCase();
        const studentClass = classCell.textContent.toLowerCase();
        const section = sectionCell.textContent.trim();
        
        const matchesSearch = name.includes(searchInput);
        const matchesClass = !classFilter || studentClass.includes(classFilter);
        const matchesSection = !sectionFilter || section === sectionFilter;
        
        if (matchesSearch && matchesClass && matchesSection) {
            visibleRows.push(row);
        }
    });
    
    // Update allRows with filtered results
    allRows = visibleRows;
    currentPage = 1; // Reset to first page
    updatePagination();
    
    // Update search results text
    const resultsDiv = document.getElementById('searchResults');
    if (searchInput || classFilter || sectionFilter) {
        resultsDiv.textContent = `${visibleRows.length} টি ফলাফল পাওয়া গেছে`;
        resultsDiv.classList.add('text-blue-600', 'font-medium');
    } else {
        resultsDiv.textContent = '';
        resultsDiv.classList.remove('text-blue-600', 'font-medium');
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('classFilter').value = '';
    document.getElementById('sectionFilter').value = '';
    allRows = Array.from(document.querySelectorAll('tbody tr.student-row'));
    currentPage = 1;
    updatePagination();
    document.getElementById('searchResults').textContent = '';
}

// Pagination functions
function updatePagination() {
    const totalItems = allRows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Hide all student rows first
    document.querySelectorAll('tbody tr.student-row').forEach(row => row.style.display = 'none');
    
    // Show only current page rows
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
    
    for (let i = startIndex; i < endIndex; i++) {
        if (allRows[i]) {
            allRows[i].style.display = '';
        }
    }
    
    // Update pagination info
    document.getElementById('showingFrom').textContent = totalItems > 0 ? toBengaliNumber(startIndex + 1) : '০';
    document.getElementById('showingTo').textContent = toBengaliNumber(endIndex);
    document.getElementById('totalRecords').textContent = toBengaliNumber(totalItems);
    
    // Update pagination buttons
    updatePaginationButtons(totalPages);
}

function updatePaginationButtons(totalPages) {
    const paginationDiv = document.getElementById('paginationButtons');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    // Enable/disable prev/next buttons
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    
    prevBtn.classList.toggle('opacity-50', currentPage === 1);
    prevBtn.classList.toggle('cursor-not-allowed', currentPage === 1);
    nextBtn.classList.toggle('opacity-50', currentPage === totalPages || totalPages === 0);
    nextBtn.classList.toggle('cursor-not-allowed', currentPage === totalPages || totalPages === 0);
    
    // Generate page buttons
    let buttonsHTML = '<button onclick="changePage(\'prev\')" id="prevBtn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors ' + 
        (currentPage === 1 ? 'opacity-50 cursor-not-allowed' : '') + '" ' + 
        (currentPage === 1 ? 'disabled' : '') + '>পূর্ববর্তী</button>';
    
    // Show max 5 page buttons
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        buttonsHTML += `<button onclick="changePage(${i})" class="px-4 py-2 rounded-lg transition-colors ${
            isActive ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-100'
        }" data-page="${i}">${toBengaliNumber(i)}</button>`;
    }
    
    buttonsHTML += '<button onclick="changePage(\'next\')" id="nextBtn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors ' + 
        (currentPage === totalPages || totalPages === 0 ? 'opacity-50 cursor-not-allowed' : '') + '" ' + 
        (currentPage === totalPages || totalPages === 0 ? 'disabled' : '') + '>পরবর্তী</button>';
    
    paginationDiv.innerHTML = buttonsHTML;
}

function changePage(page) {
    const totalPages = Math.ceil(allRows.length / itemsPerPage);
    
    if (page === 'prev') {
        if (currentPage > 1) currentPage--;
    } else if (page === 'next') {
        if (currentPage < totalPages) currentPage++;
    } else {
        currentPage = page;
    }
    
    updatePagination();
    
    // Scroll to top of table
    document.querySelector('.bg-white.rounded-2xl.shadow-lg.overflow-hidden').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Convert English numbers to Bengali
function toBengaliNumber(num) {
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return String(num).split('').map(digit => bengaliDigits[parseInt(digit)] || digit).join('');
}

// Add search icon animation
document.getElementById('searchInput').addEventListener('input', function() {
    this.style.borderColor = this.value ? '#3B82F6' : '';
});

// Device Sync Functions
function syncStudentToDevice(studentId) {
    if (confirm('এই ছাত্রকে Device এ sync করবেন?')) {
        fetch(`/api/students/${studentId}/sync-to-device`, { 
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Sync করতে ত্রুটি হয়েছে', 'error');
        });
    }
}

function bulkSyncToDevice() {
    if (confirm('সকল ছাত্রকে Device এ sync করবেন? এটি কিছু সময় নিতে পারে।')) {
        showNotification('Sync প্রক্রিয়া শুরু হয়েছে...', 'info');
        
        fetch('/api/students/bulk-sync-to-device', { 
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Bulk sync করতে ত্রুটি হয়েছে', 'error');
        });
    }
}

function checkSyncStatus() {
    fetch('/api/device/sync-status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.stats;
                const message = `
                    Device Sync Status:
                    • Pending: ${stats.pending}
                    • Completed: ${stats.completed}
                    • Failed: ${stats.failed}
                    • Total: ${stats.total}
                `;
                alert(message);
            }
        })
        .catch(error => {
            showNotification('Status check করতে ত্রুটি হয়েছে', 'error');
        });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection

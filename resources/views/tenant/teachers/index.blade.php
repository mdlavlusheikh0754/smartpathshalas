@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">শিক্ষক তালিকা</h1>
            <p class="text-gray-600 mt-1">সকল শিক্ষকের তথ্য দেখুন এবং পরিচালনা করুন</p>
        </div>
        <a href="{{ route('tenant.teachers.create') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            নতুন শিক্ষক যোগ করুন
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">মোট শিক্ষক</p>
                    <h3 class="text-3xl font-bold mt-1">০</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">পুরুষ শিক্ষক</p>
                    <h3 class="text-3xl font-bold mt-1">০</h3>
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
                    <p class="text-pink-100 text-sm">মহিলা শিক্ষক</p>
                    <h3 class="text-3xl font-bold mt-1">০</h3>
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
                    <h3 class="text-3xl font-bold mt-1">০</h3>
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
                <input type="text" id="searchInput" placeholder="নাম দিয়ে খুঁজুন..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" oninput="filterTeachers()">
            </div>
            <div>
                <select id="subjectFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="filterTeachers()">
                    <option value="">সকল বিষয়</option>
                    <option value="গণিত">গণিত</option>
                    <option value="বাংলা">বাংলা</option>
                    <option value="ইংরেজি">ইংরেজি</option>
                    <option value="বিজ্ঞান">বিজ্ঞান</option>
                </select>
            </div>
            <div>
                <select id="genderFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="filterTeachers()">
                    <option value="">সকল লিঙ্গ</option>
                    <option value="পুরুষ">পুরুষ</option>
                    <option value="মহিলা">মহিলা</option>
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

    <!-- Teachers Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">আইডি</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">নাম</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">বিষয়</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">লিঙ্গ</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">মোবাইল</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">কোনো শিক্ষক পাওয়া যায়নি</p>
                            <p class="text-sm text-gray-400 mt-1">নতুন শিক্ষক যোগ করতে "নতুন শিক্ষক যোগ করুন" বাটনে ক্লিক করুন</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-700">
                দেখানো হচ্ছে <span class="font-medium" id="showingFrom">০</span> থেকে <span class="font-medium" id="showingTo">০</span> এর মধ্যে <span class="font-medium" id="totalRecords">০</span> টি
            </div>
            <div class="flex gap-2" id="paginationButtons">
                <button onclick="changePage('prev')" id="prevBtn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors" disabled>পূর্ববর্তী</button>
                <button onclick="changePage(1)" class="px-4 py-2 bg-green-600 text-white rounded-lg page-btn" data-page="1">১</button>
                <button onclick="changePage(2)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors page-btn" data-page="2">২</button>
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
            <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে এই শিক্ষককে মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
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
    allRows = Array.from(document.querySelectorAll('tbody tr'));
    updatePagination();
});

function confirmDelete(id) {
    currentDeleteFormId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    currentDeleteFormId = null;
}

function submitDelete() {
    if (currentDeleteFormId) {
        document.getElementById('deleteForm' + currentDeleteFormId).submit();
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
function filterTeachers() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const subjectFilter = document.getElementById('subjectFilter').value;
    const genderFilter = document.getElementById('genderFilter').value;
    
    const rows = document.querySelectorAll('tbody tr');
    let visibleRows = [];
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const subject = row.querySelector('td:nth-child(3)').textContent;
        const gender = row.querySelector('td:nth-child(4)').textContent;
        
        const matchesSearch = name.includes(searchInput);
        const matchesSubject = !subjectFilter || subject === subjectFilter;
        const matchesGender = !genderFilter || gender === genderFilter;
        
        if (matchesSearch && matchesSubject && matchesGender) {
            visibleRows.push(row);
        }
    });
    
    // Update allRows with filtered results
    allRows = visibleRows;
    currentPage = 1; // Reset to first page
    updatePagination();
    
    // Update search results text
    const resultsDiv = document.getElementById('searchResults');
    if (searchInput || subjectFilter || genderFilter) {
        resultsDiv.textContent = `${visibleRows.length} টি ফলাফল পাওয়া গেছে`;
        resultsDiv.classList.add('text-green-600', 'font-medium');
    } else {
        resultsDiv.textContent = '';
        resultsDiv.classList.remove('text-green-600', 'font-medium');
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('subjectFilter').value = '';
    document.getElementById('genderFilter').value = '';
    allRows = Array.from(document.querySelectorAll('tbody tr'));
    currentPage = 1;
    updatePagination();
    document.getElementById('searchResults').textContent = '';
}

// Pagination functions
function updatePagination() {
    const totalItems = allRows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Hide all rows first
    document.querySelectorAll('tbody tr').forEach(row => row.style.display = 'none');
    
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
            isActive ? 'bg-green-600 text-white' : 'border border-gray-300 hover:bg-gray-100'
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
    this.style.borderColor = this.value ? '#10B981' : '';
});
</script>
@endsection

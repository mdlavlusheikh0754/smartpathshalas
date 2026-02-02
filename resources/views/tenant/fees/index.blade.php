@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ফি ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">ফি সংগ্রহ এবং বকেয়া পরিচালনা করুন</p>
        </div>
        <a href="{{ route('tenant.fees.collect') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            ফি সংগ্রহ করুন
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm">মোট সংগৃহীত</p>
            <h3 id="totalCollected" class="text-3xl font-bold mt-1">৳ ০</h3>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-red-100 text-sm">মোট বকেয়া</p>
            <h3 id="totalDue" class="text-3xl font-bold mt-1">৳ ০</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm">এই মাসে</p>
            <h3 id="thisMonth" class="text-3xl font-bold mt-1">৳ ০</h3>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-purple-100 text-sm">সংগ্রহ হার</p>
            <h3 id="collectionRate" class="text-3xl font-bold mt-1">০%</h3>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ক্লাস</label>
                <select id="filterClass" onchange="filterTransactions()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">সব ক্লাস</option>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">ফি টাইপ</label>
                <select id="filterFeeType" onchange="filterTransactions()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">সব ধরনের ফি</option>
                    @foreach(\App\Models\FeeStructure::getFeeTypes() as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">স্ট্যাটাস</label>
                <select id="filterStatus" onchange="filterTransactions()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="paid">পরিশোধিত</option>
                    <option value="due">বকেয়া</option>
                    <option value="partial">আংশিক</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষার্থী খুঁজুন</label>
                <input type="text" id="searchStudent" oninput="filterTransactions()" placeholder="নাম বা রোল নম্বর" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button onclick="resetFilters()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    রিসেট
                </button>
            </div>
        </div>
    </div>

    <!-- Fee Records Table -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">ফি রেকর্ড</h3>
            <div class="text-sm text-gray-600">
                <span id="recordCount">মোট: ২০ টি রেকর্ড</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">তারিখ</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">শিক্ষার্থী</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">ক্লাস</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">ফি টাইপ</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">পরিমাণ</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">পরিশোধিত</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">বকেয়া</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">স্ট্যাটাস</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody id="feeTableBody" class="divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-600">
                <span id="paginationInfo">কোনো রেকর্ড নেই</span>
            </div>
            <div class="flex gap-2" id="paginationButtons">
                <!-- Pagination buttons will be generated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Fee records - populated from database
let feeRecords = @json($feeRecordsData ?? []);
let filteredRecords = [...feeRecords];
let currentPage = 1;
let recordsPerPage = 10;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    renderFeeTable();
    updateStats();
});

// Filter transactions
function filterTransactions() {
    const classFilter = document.getElementById('filterClass').value;
    const feeTypeFilter = document.getElementById('filterFeeType').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const searchTerm = document.getElementById('searchStudent').value.toLowerCase();
    
    filteredRecords = feeRecords.filter(record => {
        const matchClass = !classFilter || record.class === classFilter;
        const matchFeeType = !feeTypeFilter || record.feeType === feeTypeFilter;
        const matchStatus = !statusFilter || record.status === statusFilter;
        const matchSearch = !searchTerm || 
            record.student.toLowerCase().includes(searchTerm) || 
            record.roll.includes(searchTerm);
        
        return matchClass && matchFeeType && matchStatus && matchSearch;
    });
    
    currentPage = 1;
    renderFeeTable();
}

// Reset filters
function resetFilters() {
    document.getElementById('filterClass').value = '';
    document.getElementById('filterFeeType').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('searchStudent').value = '';
    
    filteredRecords = [...feeRecords];
    currentPage = 1;
    renderFeeTable();
}

// Render fee table
function renderFeeTable() {
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const tbody = document.getElementById('feeTableBody');
    
    if (filteredRecords.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500">কোনো ফি রেকর্ড পাওয়া যায়নি</td></tr>';
        document.getElementById('recordCount').textContent = 'মোট: ০ টি রেকর্ড';
        document.getElementById('paginationInfo').textContent = 'কোনো রেকর্ড নেই';
        document.getElementById('paginationButtons').innerHTML = '';
        return;
    }
    
    const startIndex = (currentPage - 1) * recordsPerPage;
    const endIndex = startIndex + recordsPerPage;
    const pageRecords = filteredRecords.slice(startIndex, endIndex);
    
    tbody.innerHTML = pageRecords.map(record => {
        const statusClass = {
            paid: 'bg-green-100 text-green-600',
            due: 'bg-red-100 text-red-600',
            partial: 'bg-yellow-100 text-yellow-600'
        };
        
        const statusText = {
            paid: 'পরিশোধিত',
            due: 'বকেয়া',
            partial: 'আংশিক'
        };
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-700">${toBengaliNumber(record.date)}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${record.student}</div>
                    <div class="text-xs text-gray-500">রোল: ${toBengaliNumber(record.roll)}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">${record.class}</td>
                <td class="px-6 py-4 text-sm text-gray-700">${record.feeTypeName}</td>
                <td class="px-6 py-4 text-sm text-gray-700">৳ ${toBengaliNumber(record.totalAmount.toLocaleString())}</td>
                <td class="px-6 py-4 text-sm text-gray-700">৳ ${toBengaliNumber(record.paidAmount.toLocaleString())}</td>
                <td class="px-6 py-4 text-sm text-gray-700">৳ ${toBengaliNumber(record.dueAmount.toLocaleString())}</td>
                <td class="px-6 py-4">
                    <span class="${statusClass[record.status]} px-3 py-1 rounded-full text-xs font-bold">
                        ${statusText[record.status]}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <button onclick="viewReceipt(${record.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        রসিদ দেখুন
                    </button>
                </td>
            </tr>
        `;
    }).join('');
    
    // Update record count
    document.getElementById('recordCount').textContent = `মোট: ${toBengaliNumber(filteredRecords.length)} টি রেকর্ড`;
    
    // Update pagination info
    const totalRecords = filteredRecords.length;
    const startRecord = totalRecords > 0 ? startIndex + 1 : 0;
    const endRecord = Math.min(endIndex, totalRecords);
    document.getElementById('paginationInfo').textContent = 
        `দেখানো হচ্ছে ${toBengaliNumber(startRecord)} থেকে ${toBengaliNumber(endRecord)} এর মধ্যে ${toBengaliNumber(totalRecords)} টি`;
    
    renderPagination();
}

// Render pagination
function renderPagination() {
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const totalPages = Math.ceil(filteredRecords.length / recordsPerPage);
    const paginationContainer = document.getElementById('paginationButtons');
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let buttons = '';
    
    // Previous button
    if (currentPage > 1) {
        buttons += `<button onclick="changePage(${currentPage - 1})" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50">পূর্ববর্তী</button>`;
    }
    
    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        buttons += `
            <button onclick="changePage(${i})" class="px-3 py-2 text-sm ${isActive ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-300 rounded-lg">
                ${toBengaliNumber(i)}
            </button>
        `;
    }
    
    // Next button
    if (currentPage < totalPages) {
        buttons += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50">পরবর্তী</button>`;
    }
    
    paginationContainer.innerHTML = buttons;
}

// Change page
function changePage(page) {
    currentPage = page;
    renderFeeTable();
}

// Update stats
function updateStats() {
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const totalCollected = feeRecords.reduce((sum, record) => sum + record.paidAmount, 0);
    const totalDue = feeRecords.reduce((sum, record) => sum + record.dueAmount, 0);
    
    // This month's collection
    const thisMonth = feeRecords
        .filter(record => {
            const recordDate = new Date(record.date.split('/').reverse().join('-'));
            const currentDate = new Date();
            return recordDate.getMonth() === currentDate.getMonth() && 
                   recordDate.getFullYear() === currentDate.getFullYear();
        })
        .reduce((sum, record) => sum + record.paidAmount, 0);
    
    const totalAmount = totalCollected + totalDue;
    const collectionRate = totalAmount > 0 ? Math.round((totalCollected / totalAmount) * 100) : 0;
    
    document.getElementById('totalCollected').textContent = `৳ ${toBengaliNumber(totalCollected.toLocaleString())}`;
    document.getElementById('totalDue').textContent = `৳ ${toBengaliNumber(totalDue.toLocaleString())}`;
    document.getElementById('thisMonth').textContent = `৳ ${toBengaliNumber(thisMonth.toLocaleString())}`;
    document.getElementById('collectionRate').textContent = `${toBengaliNumber(collectionRate)}%`;
}

// View receipt (placeholder)
function viewReceipt(recordId) {
    const record = feeRecords.find(r => r.id === recordId);
    if (record) {
        // Bengali number conversion
        function toBengaliNumber(num) {
            const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            return String(num).replace(/[0-9]/g, function(match) {
                return bengaliNumbers[englishNumbers.indexOf(match)];
            });
        }
        
        const receiptNumber = record.receiptNumber ? toBengaliNumber(record.receiptNumber) : 'N/A';
        alert(`রসিদ দেখানো হবে:\n\nশিক্ষার্থী: ${record.student}\nফি টাইপ: ${record.feeTypeName}\nভাউচার নম্বর: ${receiptNumber}\nপরিমাণ: ৳ ${toBengaliNumber(record.paidAmount.toLocaleString())}`);
    }
}
</script>
@endsection

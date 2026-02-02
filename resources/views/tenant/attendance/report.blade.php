@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">উপস্থিতি রিপোর্ট</h1>
            <p class="text-gray-600 mt-1">ক্লাস ও তারিখ অনুযায়ী উপস্থিতি রিপোর্ট দেখুন</p>
        </div>
        <a href="{{ route('tenant.attendance.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            ফিরে যান
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস নির্বাচন করুন</label>
                <select id="filterClass" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">সকল ক্লাস</option>
                    @foreach(\App\Models\SchoolClass::active()->ordered()->get() as $class)
                        <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->section }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শুরুর তারিখ</label>
                <input type="date" id="filterStartDate" value="{{ date('Y-m-d', strtotime('-7 days')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শেষ তারিখ</label>
                <input type="date" id="filterEndDate" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end">
                <button onclick="loadReport()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                    রিপোর্ট দেখুন
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6" id="statsCards">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm">গড় উপস্থিতি</p>
            <h3 class="text-3xl font-bold mt-1" id="avgPercentage">--</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm">মোট উপস্থিত</p>
            <h3 class="text-3xl font-bold mt-1" id="totalPresent">--</h3>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-red-100 text-sm">মোট অনুপস্থিত</p>
            <h3 class="text-3xl font-bold mt-1" id="totalAbsent">--</h3>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-yellow-100 text-sm">মোট ছুটি</p>
            <h3 class="text-3xl font-bold mt-1" id="totalLeave">--</h3>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">উপস্থিতি রেকর্ড</h3>
            <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ছাত্র/ছাত্রী</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ক্লাস</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">রোল</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সময়</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            ফিল্টার নির্বাচন করে "রিপোর্ট দেখুন" বাটনে ক্লিক করুন
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="px-6 py-4 border-t border-gray-200 hidden">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span id="showingFrom">1</span> to <span id="showingTo">10</span> of <span id="totalRecords">0</span> results
                </div>
                <div class="flex gap-2" id="paginationButtons">
                    <!-- Pagination buttons will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;

async function loadReport(page = 1) {
    const classId = document.getElementById('filterClass').value;
    const startDate = document.getElementById('filterStartDate').value;
    const endDate = document.getElementById('filterEndDate').value;
    
    const tbody = document.getElementById('reportTableBody');
    tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">লোড হচ্ছে...</td></tr>';

    try {
        const params = new URLSearchParams({
            class_id: classId,
            start_date: startDate,
            end_date: endDate,
            page: page
        });

        const response = await fetch(`{{ route('tenant.attendance.report-data') }}?${params}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            // Update statistics
            document.getElementById('avgPercentage').textContent = data.stats.avg_percentage + '%';
            document.getElementById('totalPresent').textContent = data.stats.total_present;
            document.getElementById('totalAbsent').textContent = data.stats.total_absent;
            document.getElementById('totalLeave').textContent = data.stats.total_leave;

            // Update table
            if (data.attendances.data.length > 0) {
                tbody.innerHTML = data.attendances.data.map(att => {
                    const statusClass = att.status === 'present' ? 'bg-green-100 text-green-700 border-green-200' :
                                      att.status === 'absent' ? 'bg-red-100 text-red-700 border-red-200' :
                                      att.status === 'leave' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' :
                                      'bg-orange-100 text-orange-700 border-orange-200';
                    
                    const dotClass = att.status === 'present' ? 'bg-green-500' :
                                   att.status === 'absent' ? 'bg-red-500' :
                                   att.status === 'leave' ? 'bg-yellow-500' : 'bg-orange-500';

                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${att.date}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="${att.student.photo_url}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-100">
                                    <span class="text-sm font-medium text-gray-900">${att.student.name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${att.student.student_id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${att.student.class}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${att.student.roll}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase border ${statusClass}">
                                    <span class="w-2 h-2 mr-1.5 rounded-full ${dotClass}"></span>
                                    ${att.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${att.time}</td>
                        </tr>
                    `;
                }).join('');

                // Update pagination
                updatePagination(data.attendances);
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">কোন রেকর্ড পাওয়া যায়নি</td></tr>';
                document.getElementById('pagination').classList.add('hidden');
            }
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">${data.message}</td></tr>`;
        }
    } catch (error) {
        console.error(error);
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">তথ্য লোড করতে সমস্যা হয়েছে</td></tr>';
    }
}

function updatePagination(paginationData) {
    const pagination = document.getElementById('pagination');
    
    if (paginationData.last_page > 1) {
        pagination.classList.remove('hidden');
        
        document.getElementById('showingFrom').textContent = paginationData.from || 0;
        document.getElementById('showingTo').textContent = paginationData.to || 0;
        document.getElementById('totalRecords').textContent = paginationData.total;
        
        const buttonsDiv = document.getElementById('paginationButtons');
        let buttons = '';
        
        // Previous button
        if (paginationData.current_page > 1) {
            buttons += `<button onclick="loadReport(${paginationData.current_page - 1})" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>`;
        }
        
        // Page numbers
        for (let i = 1; i <= paginationData.last_page; i++) {
            if (i === paginationData.current_page) {
                buttons += `<button class="px-3 py-1 bg-blue-600 text-white rounded-md">${i}</button>`;
            } else if (i === 1 || i === paginationData.last_page || (i >= paginationData.current_page - 2 && i <= paginationData.current_page + 2)) {
                buttons += `<button onclick="loadReport(${i})" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">${i}</button>`;
            } else if (i === paginationData.current_page - 3 || i === paginationData.current_page + 3) {
                buttons += `<span class="px-2">...</span>`;
            }
        }
        
        // Next button
        if (paginationData.current_page < paginationData.last_page) {
            buttons += `<button onclick="loadReport(${paginationData.current_page + 1})" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Next</button>`;
        }
        
        buttonsDiv.innerHTML = buttons;
    } else {
        pagination.classList.add('hidden');
    }
}

function exportReport() {
    const classId = document.getElementById('filterClass').value;
    const startDate = document.getElementById('filterStartDate').value;
    const endDate = document.getElementById('filterEndDate').value;
    
    const params = new URLSearchParams({
        class_id: classId,
        start_date: startDate,
        end_date: endDate,
        export: 'csv'
    });
    
    window.location.href = `{{ route('tenant.attendance.export') }}?${params}`;
}

// Load initial report
document.addEventListener('DOMContentLoaded', function() {
    loadReport();
});
</script>
@endsection

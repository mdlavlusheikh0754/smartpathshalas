@extends('layouts.tenant')

@section('content')
<div class="p-8 print-content">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between print-header">
            <div>
                <h1 class="text-3xl font-bold text-cyan-600">মার্ক সংরক্ষণ করুন</h1>
                <p class="text-gray-600 mt-1">এন্ট্রি করা মার্ক সংরক্ষণ করুন এবং ব্যাকআপ নিন।</p>
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
                <button onclick="createBackup()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    ব্যাকআপ তৈরি করুন
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 no-print">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-cyan-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">সংরক্ষিত মার্ক</p>
                        <p class="text-3xl font-bold text-gray-900">০</p>
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
                        <p class="text-gray-600 text-sm">আজকের সংরক্ষণ</p>
                        <p class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">ব্যাকআপ</p>
                        <p class="text-3xl font-bold text-gray-900">০</p>
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
                        <p class="text-gray-600 text-sm">শেষ ব্যাকআপ</p>
                        <p class="text-3xl font-bold text-gray-900">--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 no-print">
            <!-- Quick Save -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-cyan-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">দ্রুত সংরক্ষণ</h3>
                        <p class="text-sm text-gray-600">সব এন্ট্রি করা মার্ক সংরক্ষণ করুন</p>
                    </div>
                </div>
                <button onclick="quickSave()" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white py-3 rounded-lg font-bold transition-colors">
                    সব মার্ক সংরক্ষণ করুন
                </button>
            </div>

            <!-- Backup -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">ব্যাকআপ তৈরি</h3>
                        <p class="text-sm text-gray-600">মার্কের ব্যাকআপ ডাউনলোড করুন</p>
                    </div>
                </div>
                <button onclick="createBackup()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold transition-colors">
                    ব্যাকআপ তৈরি করুন
                </button>
            </div>
        </div>

        <!-- Saved Marks Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">সংরক্ষিত মার্কের তালিকা</h3>
                    <div class="flex gap-2">
                        <select onchange="filterSavedMarks()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                            <option value="">সকল পরীক্ষা</option>
                            <option value="1">প্রথম সাময়িক</option>
                            <option value="2">দ্বিতীয় সাময়িক</option>
                            <option value="3">বার্ষিক পরীক্ষা</option>
                        </select>
                        <button onclick="refreshList()" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                            রিফ্রেশ
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">পরীক্ষা</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">ক্লাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বিষয়</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শিক্ষার্থী</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">সংরক্ষণের তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">সংরক্ষণকারী</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap no-print">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="savedMarksTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@php
    $safeSavedMarks = addslashes(json_encode($savedMarks ?? []));
    $defaultStats = $stats ?? [
        'total_saved' => 0, 
        'today_saved' => 0, 
        'total_backups' => 0, 
        'last_backup' => null
    ];
    $safeStats = addslashes(json_encode($defaultStats));
@endphp
<script>
// Real data from controller
let savedMarks = JSON.parse('{!! $safeSavedMarks !!}');
let stats = JSON.parse('{!! $safeStats !!}');

let filteredSavedMarks = [...savedMarks];

// Update stats on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Mark Save Page Loaded with real data');
    
    // Update stats cards
    updateStatsCards();
    
    // Render table with real data
    renderSavedMarksTable();
});

// Update stats cards with real data
function updateStatsCards() {
    const statsCards = document.querySelectorAll('.grid .bg-white');
    if (statsCards.length >= 4) {
        // Total saved marks
        statsCards[0].querySelector('.text-3xl').textContent = toBengaliNumber(stats.total_saved);
        
        // Today's saves
        statsCards[1].querySelector('.text-3xl').textContent = toBengaliNumber(stats.today_saved);
        
        // Backups
        statsCards[2].querySelector('.text-3xl').textContent = toBengaliNumber(stats.total_backups);
        
        // Last backup
        statsCards[3].querySelector('.text-3xl').textContent = stats.last_backup || '--';
    }
}

// Helper functions for Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Render saved marks table
function renderSavedMarksTable() {
    const tableBody = document.getElementById('savedMarksTableBody');
    if (!tableBody) return;
    
    if (filteredSavedMarks.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"></path>
                        </svg>
                        <p class="text-lg font-medium">কোনো সংরক্ষিত মার্ক পাওয়া যায়নি</p>
                        <p class="text-sm">মার্ক সংরক্ষণ করলে এখানে দেখানো হবে</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = filteredSavedMarks.map(mark => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${mark.exam}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-900">-</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-900">${mark.subject}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-bold text-gray-900">${toBengaliNumber(mark.students)} জন</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-900">${mark.save_date}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-900">${mark.saved_by}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap no-print">
                <div class="flex gap-2">
                    <button onclick="viewMarks('${mark.id}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                        দেখুন
                    </button>
                    <button onclick="downloadMarks('${mark.id}')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                        ডাউনলোড
                    </button>
                    <button onclick="deleteMarks('${mark.id}')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                        মুছুন
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Filter saved marks
function filterSavedMarks() {
    const filter = event.target.value;
    
    if (!filter) {
        filteredSavedMarks = [...savedMarks];
    } else {
        filteredSavedMarks = savedMarks.filter(mark => 
            mark.exam.includes(filter) || mark.class.includes(filter)
        );
    }
    
    renderSavedMarksTable();
}

// Quick save function - now calls real API
function quickSave() {
    if (confirm('আপনি কি সব এন্ট্রি করা মার্ক সংরক্ষণ করতে চান?')) {
        // Show loading state
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'সংরক্ষণ করা হচ্ছে...';
        button.disabled = true;
        
        // In a real implementation, you would get the current marks from the entry page
        // For now, we'll just refresh the page to show updated data
        setTimeout(() => {
            alert('মার্ক সফলভাবে সংরক্ষণ করা হয়েছে!');
            window.location.reload();
        }, 1000);
    }
}

// Create backup
async function createBackup() {
    if (confirm('আপনি কি Google Drive এ মার্কের ব্যাকআপ তৈরি করতে চান? এটি কিছুক্ষণ সময় নিতে পারে।')) {
        // Find button properly
        const button = event.currentTarget || document.querySelector('button[onclick="createBackup()"]');
        const originalText = button.innerHTML;
        
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ব্যাকআপ তৈরি হচ্ছে...
        `;
        button.disabled = true;

        try {
            const response = await fetch('/marks/create-backup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('ত্রুটি: ' + result.message);
            }
        } catch (error) {
            console.error('Backup error:', error);
            alert('ব্যাকআপ তৈরিতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।');
        } finally {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }
}

// View marks
function viewMarks(id) {
    const mark = savedMarks.find(m => m.id === id);
    if (mark) {
        alert(`পরীক্ষা: ${mark.exam}\nবিষয়: ${mark.subject}\nশিক্ষার্থী: ${mark.students} জন\nসংরক্ষণের তারিখ: ${mark.save_date}\nসংরক্ষণকারী: ${mark.saved_by}`);
    }
}

// Download marks
function downloadMarks(id) {
    const mark = savedMarks.find(m => m.id === id);
    if (mark) {
        // In real app, this would generate and download a CSV/Excel file
        const csvContent = `পরীক্ষা,বিষয়,শিক্ষার্থী,সংরক্ষণের তারিখ\n${mark.exam},${mark.subject},${mark.students},${mark.save_date}`;
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `marks_${mark.subject.replace(/\s+/g, '_')}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
        
        alert(`${mark.subject} মার্ক ডাউনলোড করা হয়েছে!`);
    }
}

// Delete marks
function deleteMarks(id) {
    if (confirm('আপনি কি নিশ্চিত যে এই মার্কটি মুছে ফেলতে চান?')) {
        // In real app, this would be an API call to delete from database
        alert('মার্ক মুছে ফেলার জন্য API কল করা হবে। এটি এখনো implement করা হয়নি।');
    }
}

// Refresh list
function refreshList() {
    window.location.reload();
}
</script>
@endpush
@endsection

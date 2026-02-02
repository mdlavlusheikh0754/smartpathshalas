@extends('layouts.tenant')

@section('content')
<div class="p-8 print-content">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between print-header">
            <div>
                <h1 class="text-3xl font-bold text-purple-600">পরীক্ষা ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">পরীক্ষা সময়সূচী এবং ফলাফল পরিচালনা করুন</p>
                <!-- Print-only information -->
                <div class="hidden print:block mt-2">
                    <p class="text-sm">প্রিন্ট তারিখ: {{ date('d/m/Y') }} | সময়: {{ date('H:i') }}</p>
                    <p class="text-sm">স্কুল: {{ $settings->school_name_bn ?? 'ইকরা নূরানিয়া একাডেমি' }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 no-print">
            
            <!-- Exam Management Card -->
            <a href="{{ route('tenant.exams.manage') }}" class="group block">
                <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                            ম্যানেজমেন্ট
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">পরীক্ষা ম্যানেজমেন্ট</h3>
                    <p class="text-purple-100 text-sm mb-4">পরীক্ষা তৈরি, সম্পাদনা এবং পরিচালনা করুন।</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold">{{ $exams->count() }}</span>
                        <span class="text-sm text-purple-100">মোট পরীক্ষা</span>
                    </div>
                </div>
            </a>

                        
            <!-- Results Management Card -->
            <a href="{{ route('tenant.results.index') }}" class="group block">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                            ফলাফল
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">ফলাফল দেখুন</h3>
                    <p class="text-blue-100 text-sm mb-4">পরীক্ষার ফলাফল দেখুন, প্রকাশ করুন এবং ডাউনলোড করুন।</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold">০</span>
                        <span class="text-sm text-blue-100">প্রকাশিত ফলাফল</span>
                    </div>
                </div>
            </a>

            <!-- Subjects Card -->
            <a href="{{ route('tenant.exams.subjects') }}" class="group block">
                <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                            বিষয়
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">পরীক্ষার বিষয়</h3>
                    <p class="text-red-100 text-sm mb-4">পরীক্ষায় অন্তর্ভুক্ত বিষয়সমূহ যোগ করুন এবং পরিচালনা করুন।</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold">০</span>
                        <span class="text-sm text-red-100">মোট বিষয়</span>
                    </div>
                </div>
            </a>

            <!-- Mark Entry Card -->
            <a href="/marks/entry" class="group block">
                <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                            এন্ট্রি
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">মার্ক এন্ট্রি</h3>
                    <p class="text-yellow-100 text-sm mb-4">শিক্ষার্থীদের পরীক্ষার নম্বর এন্ট্রি করুন এবং সম্পাদনা করুন।</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold">০</span>
                        <span class="text-sm text-yellow-100">এন্ট্রি সম্পন্ন</span>
                    </div>
                </div>
            </a>

            <!-- Mark Save Card -->
            <a href="/marks/save" class="group block">
                <div class="bg-gradient-to-br from-cyan-600 to-cyan-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                            সংরক্ষণ
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">মার্ক সংরক্ষণ করুন</h3>
                    <p class="text-cyan-100 text-sm mb-4">এন্ট্রি করা মার্ক সংরক্ষণ করুন এবং ব্যাকআপ নিন।</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold">০</span>
                        <span class="text-sm text-cyan-100">সংরক্ষিত মার্ক</span>
                    </div>
                </div>
            </a>

            <!-- Promotion Card -->
            <a href="/promotion" class="group block">
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                            প্রমোশন
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">প্রমোশন করুন</h3>
                    <p class="text-emerald-100 text-sm mb-4">শিক্ষার্থীদের পরবর্তী ক্লাসে প্রমোশন দিন এবং রেকর্ড রাখুন।</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold">০</span>
                        <span class="text-sm text-emerald-100">প্রমোটেড শিক্ষার্থী</span>
                    </div>
                </div>
            </a>

                    </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 no-print">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট পরীক্ষা</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalExams">০</p>
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
                        <p class="text-gray-600 text-sm">সম্পন্ন হয়েছে</p>
                        <p class="text-3xl font-bold text-gray-900" id="completedExams">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">চলমান পরীক্ষা</p>
                        <p class="text-3xl font-bold text-gray-900" id="ongoingExams">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">আসন্ন পরীক্ষা</p>
                        <p class="text-3xl font-bold text-gray-900" id="upcomingExams">০</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 no-print">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষার নাম দিয়ে খুঁজুন</label>
                    <div class="relative">
                        <input type="text" id="searchByName" placeholder="পরীক্ষার নাম দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" oninput="filterExams()">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="statusFilter" onchange="filterExams()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">সকল স্ট্যাটাস</option>
                        <option value="upcoming">আসন্ন</option>
                        <option value="ongoing">চলমান</option>
                        <option value="completed">সম্পন্ন</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">মাস</label>
                    <select id="monthFilter" onchange="filterExams()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">সকল মাস</option>
                        <option value="1">জানুয়ারি</option>
                        <option value="2">ফেব্রুয়ারি</option>
                        <option value="3">মার্চ</option>
                        <option value="4">এপ্রিল</option>
                        <option value="5">মে</option>
                        <option value="6">জুন</option>
                        <option value="7">জুলাই</option>
                        <option value="8">আগস্ট</option>
                        <option value="9">সেপ্টেম্বর</option>
                        <option value="10">অক্টোবর</option>
                        <option value="11">নভেম্বর</option>
                        <option value="12">ডিসেম্বর</option>
                    </select>
                </div>

                <div>
                    <button onclick="resetFilters()" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                        খুঁজুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Exams Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">পরীক্ষার নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শুরুর তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শেষ তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অংশগ্রহণকারী</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap no-print">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="examsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span id="paginationInfo">কোনো পরীক্ষা নেই</span>
                </div>
                <div class="flex gap-2" id="paginationButtons">
                    <!-- Pagination buttons will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Exam Modal -->
<div id="examModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h2 id="modalTitle" class="text-2xl font-bold">নতুন পরীক্ষা যোগ করুন</h2>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="examForm" onsubmit="saveExam(event)">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">পরীক্ষার নাম *</label>
                        <input type="text" id="examName" name="name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="যেমন: প্রথম সাময়িক পরীক্ষা">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">শুরুর তারিখ *</label>
                            <input type="date" id="examStartDate" name="start_date" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">শেষ তারিখ *</label>
                            <input type="date" id="examEndDate" name="end_date" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">পরীক্ষার ধরন</label>
                        <select name="exam_type" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="weekly">সাপ্তাহিক পরীক্ষা</option>
                            <option value="monthly">মাসিক পরীক্ষা</option>
                            <option value="half_yearly">অর্ধবার্ষিক পরীক্ষা</option>
                            <option value="annual">বার্ষিক পরীক্ষা</option>
                            <option value="test">টেস্ট পরীক্ষা</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">বর্ণনা</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="পরীক্ষা সম্পর্কে অতিরিক্ত তথ্য"></textarea>
                    </div>

                    <!-- Hidden field for edit -->
                    <input type="hidden" id="examId" name="id">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition-colors">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-bold transition-all transform hover:scale-[1.02]">
                        পরীক্ষা সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Sample data - replace with actual data from controller
let exams = @json($exams ?? []);
let subjects = @json($subjects ?? []);
let classes = @json($classes ?? []);

let currentPage = 1;
let itemsPerPage = 10;
let filteredExams = [...exams];

// Helper functions for Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Filter exams based on search criteria
function filterExams() {
    const nameSearch = document.getElementById('searchByName').value.toLowerCase().trim();
    const statusFilter = document.getElementById('statusFilter').value;
    const monthFilter = document.getElementById('monthFilter').value;
    
    filteredExams = exams.filter(exam => {
        const matchesName = !nameSearch || exam.name.toLowerCase().includes(nameSearch);
        const matchesStatus = !statusFilter || exam.status === statusFilter;
        const matchesMonth = !monthFilter || new Date(exam.start_date).getMonth() + 1 == monthFilter;
        
        return matchesName && matchesStatus && matchesMonth;
    });
    
    currentPage = 1;
    renderExamsTable();
    updateStats();
}

// Reset filters
function resetFilters() {
    document.getElementById('searchByName').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('monthFilter').value = '';
    filteredExams = [...exams];
    currentPage = 1;
    renderExamsTable();
    updateStats();
}

// Update statistics
function updateStats() {
    const total = exams.length;
    const completed = exams.filter(e => e.status === 'completed').length;
    const ongoing = exams.filter(e => e.status === 'ongoing').length;
    const upcoming = exams.filter(e => e.status === 'upcoming').length;
    
    document.getElementById('totalExams').textContent = toBengaliNumber(total);
    document.getElementById('completedExams').textContent = toBengaliNumber(completed);
    document.getElementById('ongoingExams').textContent = toBengaliNumber(ongoing);
    document.getElementById('upcomingExams').textContent = toBengaliNumber(upcoming);
}

// Render exams table
function renderExamsTable() {
    const tableBody = document.getElementById('examsTableBody');
    if (!tableBody) return;
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageExams = filteredExams.slice(startIndex, endIndex);
    
    if (pageExams.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-lg font-medium">কোনো পরীক্ষা পাওয়া যায়নি</p>
                        <p class="text-sm">অনুসন্ধানের মাপদণ্ড পরিবর্তন করে আবার চেষ্টা করুন</p>
                    </div>
                </td>
            </tr>
        `;
        updatePagination();
        return;
    }
    
    tableBody.innerHTML = pageExams.map(exam => {
        const statusConfig = {
            'upcoming': { class: 'bg-orange-100 text-orange-800', text: 'আসন্ন', icon: '📅' },
            'ongoing': { class: 'bg-blue-100 text-blue-800', text: 'চলমান', icon: '⏳' },
            'completed': { class: 'bg-green-100 text-green-800', text: 'সম্পন্ন', icon: '✅' }
        };
        
        const status = statusConfig[exam.status] || statusConfig['upcoming'];
        
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${exam.name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${new Date(exam.start_date).toLocaleDateString('bn-BD')}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${new Date(exam.end_date).toLocaleDateString('bn-BD')}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium ${status.class}">
                        ${status.icon} ${status.text}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${toBengaliNumber(exam.participants_count || 0)} জন</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap no-print">
                    <div class="flex gap-2">
                        <button onclick="editExam(${exam.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                            সম্পাদনা
                        </button>
                        <button onclick="deleteExam(${exam.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                            মুছুন
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    updatePagination();
}

// Update pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredExams.length / itemsPerPage);
    const startItem = (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, filteredExams.length);
    
    document.getElementById('paginationInfo').textContent = 
        `${toBengaliNumber(startItem)}-${toBengaliNumber(endItem)} of ${toBengaliNumber(filteredExams.length)} পরীক্ষা`;
    
    const paginationButtons = document.getElementById('paginationButtons');
    let buttonsHTML = '';
    
    // Previous button
    if (currentPage > 1) {
        buttonsHTML += `<button onclick="changePage(${currentPage - 1})" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">পূর্ববর্তী</button>`;
    }
    
    // Page numbers
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        const isActive = i === currentPage;
        buttonsHTML += `<button onclick="changePage(${i})" class="px-3 py-2 ${isActive ? 'bg-purple-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'} rounded-lg transition-colors">${toBengaliNumber(i)}</button>`;
    }
    
    // Next button
    if (currentPage < totalPages) {
        buttonsHTML += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">পরবর্তী</button>`;
    }
    
    paginationButtons.innerHTML = buttonsHTML;
}

// Change page
function changePage(page) {
    currentPage = page;
    renderExamsTable();
}

// Modal functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'নতুন পরীক্ষা যোগ করুন';
    document.getElementById('examForm').reset();
    document.getElementById('examId').value = '';
    document.getElementById('examModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('examModal').classList.add('hidden');
}

function editExam(id) {
    const exam = exams.find(e => e.id === id);
    if (!exam) return;
    
    document.getElementById('modalTitle').textContent = 'পরীক্ষা সম্পাদনা করুন';
    document.getElementById('examId').value = exam.id;
    document.getElementById('examName').value = exam.name;
    document.getElementById('examStartDate').value = exam.start_date;
    document.getElementById('examEndDate').value = exam.end_date;
    document.getElementById('examModal').classList.remove('hidden');
}

function deleteExam(id) {
    if (confirm('আপনি কি নিশ্চিত যে এই পরীক্ষাটি মুছে ফেলতে চান?')) {
        fetch(`/exams/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                exams = exams.filter(e => e.id !== id);
                filteredExams = filteredExams.filter(e => e.id !== id);
                renderExamsTable();
                updateStats();
                alert(data.message);
            } else {
                alert(data.message || 'পরীক্ষা মুছে ফেলা যায়নি');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
        });
    }
}

function saveExam(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const examData = Object.fromEntries(formData);
    
    const url = examData.id ? `/exams/${examData.id}` : '/exams';
    const method = examData.id ? 'PUT' : 'POST';
    
    // Add CSRF token
    if (!examData.id) {
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    } else {
        formData.append('_method', 'PUT');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (examData.id) {
                // Update existing exam
                const index = exams.findIndex(e => e.id == examData.id);
                if (index !== -1) {
                    exams[index] = data.exam;
                }
            } else {
                // Add new exam
                exams.push(data.exam);
            }
            
            filteredExams = [...exams];
            renderExamsTable();
            updateStats();
            closeModal();
            
            // Show success message
            alert(data.message);
        } else {
            alert(data.message || 'একটি ত্রুটি ঘটেছে');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Exams Management Page Loaded');
    renderExamsTable();
    updateStats();
});
</script>
@endpush
@endsection

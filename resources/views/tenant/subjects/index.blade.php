@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="p-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent bengali-text">বিষয় ম্যানেজমেন্ট</h1>
                        <p class="text-gray-600 mt-1 bengali-text">স্কুলের সকল বিষয়সমূহ পরিচালনা করুন</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="exportSubjects()" class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="bengali-text">এক্সপোর্ট</span>
                </button>
                <button onclick="openAddSubjectModal()" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="bengali-text">নতুন বিষয় যোগ করুন</span>
                </button>
            </div>
        </div>
    </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text mb-1">মোট বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900 bengali-text" id="totalSubjects">০</p>
                        <p class="text-xs text-gray-500 bengali-text mt-1">এখনো কোন বিষয় যোগ করা হয়নি</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text mb-1">বাধ্যতামূলক বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900 bengali-text" id="mandatorySubjects">০</p>
                        <p class="text-xs text-blue-600 bengali-text mt-1">মূল বিষয়সমূহ</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text mb-1">ঐচ্ছিক বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900 bengali-text" id="optionalSubjects">০</p>
                        <p class="text-xs text-amber-600 bengali-text mt-1">অতিরিক্ত বিষয়</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text mb-1">সক্রিয় বিষয়</p>
                        <p class="text-3xl font-bold text-gray-900 bengali-text" id="activeSubjects">০</p>
                        <p class="text-xs text-purple-600 bengali-text mt-1">চালু অবস্থায়</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200/50">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 bengali-text mb-1">বিষয়সমূহের তালিকা</h2>
                        <p class="text-sm text-gray-600 bengali-text">সকল একাডেমিক বিষয়ের বিস্তারিত তথ্য</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 mt-4 lg:mt-0">
                        <div class="relative">
                            <select class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-2.5 pr-8 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text shadow-sm" id="classFilter">
                                <option value="">সকল ক্লাস</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative">
                            <select class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-2.5 pr-8 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text shadow-sm" id="typeFilter">
                                <option value="">সকল ধরন</option>
                                <option value="mandatory">বাধ্যতামূলক</option>
                                <option value="optional">ঐচ্ছিক</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="বিষয় অনুসন্ধান করুন..." class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm shadow-sm bg-white" id="searchInput">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Bar (Hidden by default) -->
            <div id="bulkActionsBar" class="hidden bg-gradient-to-r from-red-50 to-red-100 border-b-2 border-red-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-semibold text-gray-700 bengali-text">
                            <span id="selectedCount">০</span> টি বিষয় নির্বাচিত
                        </span>
                        <button onclick="clearSelection()" class="text-sm text-gray-600 hover:text-gray-800 bengali-text underline">
                            নির্বাচন বাতিল করুন
                        </button>
                    </div>
                    <button onclick="bulkDeleteSubjects()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span class="bengali-text">নির্বাচিত বিষয় মুছুন</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full" id="subjectsTable">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2" onchange="toggleSelectAll(this)">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">বিষয়ের নাম</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">বিষয় কোড</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">ধরন</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">ক্লাস</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">পূর্ণমান</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">অবস্থা</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bengali-text">কার্যক্রম</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="subjectsTableBody">
                        <!-- Dynamic content will be inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-gray-700 bengali-text mb-4 sm:mb-0">
                        মোট <span class="font-semibold text-indigo-600" id="totalCount">০</span> টি বিষয়ের মধ্যে <span class="font-semibold">০-০</span> দেখানো হচ্ছে
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors bengali-text shadow-sm">পূর্ববর্তী</button>
                        <button class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors shadow-sm">১</button>
                        <button class="px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors shadow-sm">২</button>
                        <button class="px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors bengali-text shadow-sm">পরবর্তী</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div id="addSubjectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl border border-white/20 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white bengali-text">নতুন বিষয় যোগ করুন</h3>
                        <p class="text-indigo-100 text-sm bengali-text">একাডেমিক বিষয়ের তথ্য প্রদান করুন</p>
                    </div>
                </div>
                <button onclick="closeAddSubjectModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-xl p-2 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <form id="addSubjectForm" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয়ের নাম *</label>
                    <input type="text" id="subjectName" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200" placeholder="যেমন: বাংলা, ইংরেজি, গণিত">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয় কোড *</label>
                    <input type="text" id="subjectCode" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200" placeholder="যেমন: BAN-101">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয়ের ধরন *</label>
                    <div class="relative">
                        <select id="subjectType" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200 appearance-none">
                            <option value="">নির্বাচন করুন</option>
                            <option value="mandatory">বাধ্যতামূলক</option>
                            <option value="optional">ঐচ্ছিক</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-gray-700 bengali-text">ক্লাস নির্বাচন করুন * (একাধিক নির্বাচন করতে পারবেন)</label>
                        <button type="button" onclick="toggleAllClasses()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium bengali-text">
                            সব নির্বাচন করুন / বাতিল করুন
                        </button>
                    </div>
                    <div id="classCheckboxContainer" class="border border-gray-300 rounded-xl p-4 bg-gray-50 max-h-60 overflow-y-auto space-y-2">
                        <div class="text-sm text-gray-500 bengali-text">ক্লাস লোড হচ্ছে...</div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 bengali-text">টিপস: যেকোনো ক্লাসে ক্লিক করে নির্বাচন করুন</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">পূর্ণমান *</label>
                    <input type="number" id="fullMarks" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200" placeholder="১০০">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয়ের বিবরণ</label>
                    <textarea id="subjectDescription" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200 resize-none" placeholder="বিষয় সম্পর্কে বিস্তারিত তথ্য লিখুন..."></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeAddSubjectModal()" class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 bengali-text">
                    বাতিল
                </button>
                <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 bengali-text">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Subject Modal -->
<div id="editSubjectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl border border-white/20 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white bengali-text">বিষয় সম্পাদনা করুন</h3>
                        <p class="text-orange-100 text-sm bengali-text">বিষয়ের তথ্য আপডেট করুন</p>
                    </div>
                </div>
                <button onclick="closeEditSubjectModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-xl p-2 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <form id="editSubjectForm" class="p-8">
            <input type="hidden" id="editSubjectId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয়ের নাম *</label>
                    <input type="text" id="editSubjectName" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয় কোড *</label>
                    <input type="text" id="editSubjectCode" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয়ের ধরন *</label>
                    <select id="editSubjectType" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200">
                        <option value="mandatory">বাধ্যতামূলক</option>
                        <option value="optional">ঐচ্ছিক</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">পূর্ণমান *</label>
                    <input type="number" id="editFullMarks" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 bengali-text">বিষয়ের বিবরণ</label>
                    <textarea id="editSubjectDescription" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bengali-text bg-gray-50 focus:bg-white transition-all duration-200 resize-none" placeholder="বিষয় সম্পর্কে বিস্তারিত তথ্য লিখুন..."></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeEditSubjectModal()" class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 bengali-text">
                    বাতিল
                </button>
                <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-600 border border-transparent rounded-xl hover:from-amber-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 bengali-text">
                    আপডেট করুন
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
.bengali-text {
    font-family: 'SolaimanLipi', 'Kalpurush', 'Nikosh', Arial, sans-serif;
}

/* Custom scrollbar for modal */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Smooth transitions for all interactive elements */
* {
    transition: all 0.2s ease-in-out;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Modal backdrop animation */
.fixed.inset-0 {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Card hover effects */
.hover\:shadow-xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Button press effect */
button:active {
    transform: scale(0.98);
}
</style>
<script>
// Sample subjects data - starts empty
let subjects = [];

// Load subjects from database on page load
async function loadSubjects() {
    try {
        const classId = document.getElementById('classFilter').value;
        const type = document.getElementById('typeFilter').value;
        const search = document.getElementById('searchInput').value;
        
        let url = '/subjects/api/subjects?t=' + Date.now();
        if (classId) url += '&class_id=' + classId;
        if (type) url += '&type=' + type;
        if (search) url += '&search=' + encodeURIComponent(search);

        const response = await fetch(url);
        const data = await response.json();
        subjects = data;
        renderSubjects();
        // Update table count only, keep cards global
        document.getElementById('totalCount').textContent = convertToBengaliNumbers(subjects.length.toString());
    } catch (error) {
        console.error('Error loading subjects:', error);
        showNotification('বিষয় লোড করতে সমস্যা হয়েছে', 'error');
    }
}

// Load classes for filter dropdown
async function loadFilterClasses() {
    try {
        const response = await fetch('/subjects/api/classes?t=' + Date.now());
        const classes = await response.json();
        
        const filterSelect = document.getElementById('classFilter');
        filterSelect.innerHTML = '<option value="">সকল ক্লাস</option>';
        
        classes.forEach(cls => {
            const option = document.createElement('option');
            option.value = cls.id;
            option.textContent = cls.full_name;
            filterSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading filter classes:', error);
    }
}

// Load statistics from database
async function loadStats() {
    try {
        const response = await fetch('/subjects/api/stats?t=' + Date.now());
        const stats = await response.json();
        
        document.getElementById('totalSubjects').textContent = convertToBengaliNumbers(stats.totalSubjects.toString());
        document.getElementById('mandatorySubjects').textContent = convertToBengaliNumbers(stats.mandatorySubjects.toString());
        document.getElementById('optionalSubjects').textContent = convertToBengaliNumbers(stats.optionalSubjects.toString());
        document.getElementById('activeSubjects').textContent = convertToBengaliNumbers(stats.activeSubjects.toString());
        document.getElementById('totalCount').textContent = convertToBengaliNumbers(stats.totalSubjects.toString());
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Load classes for subject form (with checkboxes)
async function loadClasses() {
    try {
        const response = await fetch('/subjects/api/classes?t=' + Date.now());
        const data = await response.json();
        
        if (data.success) {
            const classes = data.classes;
            const container = document.getElementById('classCheckboxContainer');
            container.innerHTML = '';
            
            if (classes.length === 0) {
                container.innerHTML = '<div class="text-sm text-gray-500 bengali-text">কোন ক্লাস পাওয়া যায়নি</div>';
                return;
            }
            
            classes.forEach(cls => {
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'flex items-center p-2 hover:bg-indigo-50 rounded-lg transition-colors duration-150 cursor-pointer';
                checkboxDiv.innerHTML = `
                    <input type="checkbox" 
                           id="class_${cls.id}" 
                           name="selectedClasses[]" 
                           value="${cls.id}" 
                           class="class-checkbox w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer">
                    <label for="class_${cls.id}" class="ml-3 text-sm font-medium text-gray-700 bengali-text cursor-pointer flex-grow">
                        ${cls.full_name}
                    </label>
                `;
                
                // Make entire div clickable
                checkboxDiv.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        const checkbox = checkboxDiv.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                });
                
                container.appendChild(checkboxDiv);
            });
        } else {
            // Fallback for old API response format
            const classes = data;
            const container = document.getElementById('classCheckboxContainer');
            container.innerHTML = '';
            
            if (classes.length === 0) {
                container.innerHTML = '<div class="text-sm text-gray-500 bengali-text">কোন ক্লাস পাওয়া যায়নি</div>';
                return;
            }
            
            classes.forEach(cls => {
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'flex items-center p-2 hover:bg-indigo-50 rounded-lg transition-colors duration-150 cursor-pointer';
                checkboxDiv.innerHTML = `
                    <input type="checkbox" 
                           id="class_${cls.id}" 
                           name="selectedClasses[]" 
                           value="${cls.id}" 
                           class="class-checkbox w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer">
                    <label for="class_${cls.id}" class="ml-3 text-sm font-medium text-gray-700 bengali-text cursor-pointer flex-grow">
                        ${cls.full_name}
                    </label>
                `;
                
                // Make entire div clickable
                checkboxDiv.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        const checkbox = checkboxDiv.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                });
                
                container.appendChild(checkboxDiv);
            });
        }
    } catch (error) {
        console.error('Error loading classes:', error);
        const container = document.getElementById('classCheckboxContainer');
        container.innerHTML = '<div class="text-sm text-red-500 bengali-text">ক্লাস লোড করতে সমস্যা হয়েছে</div>';
    }
}

// Toggle all classes selection
function toggleAllClasses() {
    const checkboxes = document.querySelectorAll('.class-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

// Convert English numbers to Bengali
function convertToBengaliNumbers(text) {
    const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return englishNumbers.reduce((result, eng, index) => {
        return result.replace(new RegExp(eng, 'g'), bengaliNumbers[index]);
    }, text);
}

// Modal functions
function openAddSubjectModal() {
    // Check if there are any classes available
    fetch('/subjects/api/classes')
        .then(response => response.json())
        .then(classes => {
            console.log('Available classes:', classes); // Debug log
            if (classes.length === 0) {
                showNotification('প্রথমে ক্লাস পেজ থেকে ক্লাস যোগ করুন', 'error');
                return;
            }
            
            document.getElementById('addSubjectModal').classList.remove('hidden');
            document.getElementById('addSubjectForm').reset();
            loadClasses(); // Load classes when modal opens
        })
        .catch(error => {
            console.error('Error checking classes:', error);
            showNotification('ক্লাস চেক করতে সমস্যা হয়েছে', 'error');
        });
}

function closeAddSubjectModal() {
    document.getElementById('addSubjectModal').classList.add('hidden');
}

function openEditSubjectModal() {
    document.getElementById('editSubjectModal').classList.remove('hidden');
}

function closeEditSubjectModal() {
    document.getElementById('editSubjectModal').classList.add('hidden');
}

// Edit subject function
function editSubject(id) {
    const subject = subjects.find(s => s.id === id);
    if (subject) {
        document.getElementById('editSubjectId').value = subject.id;
        document.getElementById('editSubjectName').value = subject.name;
        document.getElementById('editSubjectCode').value = subject.code;
        document.getElementById('editSubjectType').value = subject.type;
        document.getElementById('editFullMarks').value = subject.fullMarks;
        document.getElementById('editSubjectDescription').value = subject.description || '';
        openEditSubjectModal();
    }
}

// Delete subject function
function deleteSubject(id) {
    const subject = subjects.find(s => s.id === id);
    if (subject) {
        showDeleteConfirmModal(id, subject.name);
    }
}

// Show delete confirmation modal
function showDeleteConfirmModal(id, subjectName) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative bg-white rounded-2xl shadow-2xl border border-white/20 w-full max-w-md">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white bengali-text">বিষয় মুছে ফেলুন</h3>
                </div>
            </div>
            
            <div class="p-6 text-center">
                <div class="mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900 bengali-text mb-2">আপনি কি নিশ্চিত যে এই বিষয়টি মুছে ফেলতে চান?</p>
                    <p class="text-gray-600 bengali-text">বিষয়: <span class="font-semibold text-gray-900">${subjectName}</span></p>
                    <p class="text-sm text-red-600 bengali-text mt-2">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
                </div>
            </div>

            <div class="flex justify-center space-x-4 p-6 pt-0">
                <button onclick="this.closest('.fixed').remove()" class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 bengali-text">
                    বাতিল
                </button>
                <button onclick="confirmDeleteSubject(${id}); this.closest('.fixed').remove()" class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 bengali-text">
                    মুছে ফেলুন
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Confirm delete subject
async function confirmDeleteSubject(id) {
    try {
        const response = await fetch(`/subjects/api/subjects/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            subjects = subjects.filter(s => s.id !== id);
            renderSubjects();
            loadStats(); // Reload stats from database
            showNotification(result.message, 'success');
        } else {
            showNotification('বিষয় মুছতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        console.error('Error deleting subject:', error);
        showNotification('বিষয় মুছতে সমস্যা হয়েছে', 'error');
    }
}

// Add subject form submission
document.getElementById('addSubjectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Get selected classes from checkboxes
    const selectedCheckboxes = document.querySelectorAll('.class-checkbox:checked');
    const selectedClasses = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (selectedClasses.length === 0) {
        showNotification('অন্তত একটি ক্লাস নির্বাচন করুন', 'error');
        return;
    }
    
    const formData = {
        name: document.getElementById('subjectName').value,
        code: document.getElementById('subjectCode').value,
        type: document.getElementById('subjectType').value,
        selectedClasses: selectedClasses, // Changed to array
        fullMarks: parseInt(document.getElementById('fullMarks').value),
        description: document.getElementById('subjectDescription').value
    };
    
    console.log('Sending data:', formData); // Debug log
    
    try {
        const response = await fetch('/subjects/api/subjects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }
        
        const result = await response.json();
        console.log('Server response:', result); // Debug log
        
        if (result.success) {
            // Add all created subjects to the array
            if (result.subjects && result.subjects.length > 0) {
                result.subjects.forEach(subject => subjects.push(subject));
            } else if (result.subject) {
                subjects.push(result.subject);
            }
            renderSubjects();
            loadStats(); // Reload stats from database
            closeAddSubjectModal();
            showNotification(result.message, 'success');
        } else {
            showNotification(result.message || 'বিষয় যোগ করতে সমস্যা হয়েছে', 'error');
            console.error('Validation errors:', result.errors); // Debug log
        }
    } catch (error) {
        console.error('Error adding subject:', error);
        if (error.message.includes('non-JSON')) {
            showNotification('সার্ভার এরর। প্রথমে ক্লাস যোগ করুন।', 'error');
        } else {
            showNotification('বিষয় যোগ করতে সমস্যা হয়েছে। প্রথমে ক্লাস যোগ করুন।', 'error');
        }
    }
});

// Edit subject form submission
document.getElementById('editSubjectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = parseInt(document.getElementById('editSubjectId').value);
    const formData = {
        name: document.getElementById('editSubjectName').value,
        code: document.getElementById('editSubjectCode').value,
        type: document.getElementById('editSubjectType').value,
        fullMarks: parseInt(document.getElementById('editFullMarks').value),
        description: document.getElementById('editSubjectDescription') ? document.getElementById('editSubjectDescription').value : ''
    };
    
    try {
        const response = await fetch(`/subjects/api/subjects/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            const subjectIndex = subjects.findIndex(s => s.id === id);
            if (subjectIndex !== -1) {
                subjects[subjectIndex] = result.subject;
            }
            renderSubjects();
            loadStats(); // Reload stats from database
            closeEditSubjectModal();
            showNotification(result.message, 'success');
        } else {
            showNotification('বিষয় আপডেট করতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        console.error('Error updating subject:', error);
        showNotification('বিষয় আপডেট করতে সমস্যা হয়েছে', 'error');
    }
});

// Render subjects table
function renderSubjects(filteredSubjects = subjects) {
    const tbody = document.getElementById('subjectsTableBody');
    tbody.innerHTML = '';
    
    if (filteredSubjects.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 bengali-text mb-2">কোন বিষয় পাওয়া যায়নি</h3>
                        <p class="text-gray-500 bengali-text mb-4">এখনো কোন বিষয় যোগ করা হয়নি। বিষয় যোগ করার আগে প্রথমে ক্লাস পেজ থেকে ক্লাস যোগ করুন।</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="/classes" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="bengali-text">প্রথমে ক্লাস যোগ করুন</span>
                            </a>
                            <button onclick="openAddSubjectModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="bengali-text">বিষয় যোগ করুন</span>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    filteredSubjects.forEach(subject => {
        const typeClass = subject.type === 'mandatory' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
        const typeText = subject.type === 'mandatory' ? 'বাধ্যতামূলক' : 'ঐচ্ছিক';
        
        const row = `
            <tr class="hover:bg-gray-50 subject-row" data-type="${subject.type}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" class="subject-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2" data-subject-id="${subject.id}" onchange="updateBulkActions()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 bengali-text">${subject.name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${subject.code}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${typeClass} bengali-text">${typeText}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 bengali-text">${subject.classes}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 bengali-text">${subject.fullMarks}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 bengali-text">সক্রিয়</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="editSubject(${subject.id})" class="text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteSubject(${subject.id})" class="text-red-600 hover:text-red-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}



// Search functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(loadSubjects, 300);
});

// Filter functionality
document.getElementById('typeFilter').addEventListener('change', loadSubjects);
document.getElementById('classFilter').addEventListener('change', loadSubjects);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadFilterClasses();
    loadStats();
    loadSubjects();
});

// Export subjects function
function exportSubjects() {
    const csvContent = "data:text/csv;charset=utf-8," 
        + "বিষয়ের নাম,বিষয় কোড,ধরন,ক্লাস,পূর্ণমান,অবস্থা\n"
        + subjects.map(subject => {
            const typeText = subject.type === 'mandatory' ? 'বাধ্যতামূলক' : 'ঐচ্ছিক';
            return `${subject.name},${subject.code},${typeText},${subject.classes},${subject.fullMarks},সক্রিয়`;
        }).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "subjects_list.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('বিষয়ের তালিকা সফলভাবে এক্সপোর্ট করা হয়েছে', 'success');
}

// Show notification
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="bengali-text">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const addModal = document.getElementById('addSubjectModal');
    const editModal = document.getElementById('editSubjectModal');
    
    if (e.target === addModal) {
        closeAddSubjectModal();
    }
    if (e.target === editModal) {
        closeEditSubjectModal();
    }
});

// Bulk delete functionality
let selectedSubjects = new Set();

// Toggle select all checkbox
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.subject-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        const subjectId = parseInt(cb.dataset.subjectId);
        if (checkbox.checked) {
            selectedSubjects.add(subjectId);
        } else {
            selectedSubjects.delete(subjectId);
        }
    });
    updateBulkActions();
}

// Update bulk actions bar visibility
function updateBulkActions() {
    selectedSubjects.clear();
    document.querySelectorAll('.subject-checkbox:checked').forEach(cb => {
        selectedSubjects.add(parseInt(cb.dataset.subjectId));
    });
    
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    if (selectedSubjects.size > 0) {
        bulkActionsBar.classList.remove('hidden');
        selectedCount.textContent = convertToBengaliNumbers(selectedSubjects.size.toString());
        
        // Update select all checkbox state
        const allCheckboxes = document.querySelectorAll('.subject-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.subject-checkbox:checked');
        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length && allCheckboxes.length > 0;
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
    } else {
        bulkActionsBar.classList.add('hidden');
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }
}

// Clear selection
function clearSelection() {
    selectedSubjects.clear();
    document.querySelectorAll('.subject-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    document.getElementById('selectAll').indeterminate = false;
    updateBulkActions();
}

// Bulk delete subjects
async function bulkDeleteSubjects() {
    if (selectedSubjects.size === 0) {
        showNotification('কোন বিষয় নির্বাচিত হয়নি', 'error');
        return;
    }
    
    // Show confirmation modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative bg-white rounded-2xl shadow-2xl border border-white/20 w-full max-w-md">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white bengali-text">একাধিক বিষয় মুছে ফেলুন</h3>
                </div>
            </div>
            
            <div class="p-6 text-center">
                <div class="mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900 bengali-text mb-2">আপনি কি নিশ্চিত যে ${convertToBengaliNumbers(selectedSubjects.size.toString())}টি বিষয় মুছে ফেলতে চান?</p>
                    <p class="text-sm text-red-600 bengali-text mt-2">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
                </div>
            </div>

            <div class="flex justify-center space-x-4 p-6 pt-0">
                <button onclick="this.closest('.fixed').remove()" class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 bengali-text">
                    বাতিল
                </button>
                <button onclick="confirmBulkDelete(); this.closest('.fixed').remove()" class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 bengali-text">
                    মুছে ফেলুন
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Confirm bulk delete
async function confirmBulkDelete() {
    try {
        const subjectIds = Array.from(selectedSubjects);
        
        const response = await fetch('/subjects/api/subjects/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ subject_ids: subjectIds })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Remove deleted subjects from local array
            subjects = subjects.filter(s => !selectedSubjects.has(s.id));
            selectedSubjects.clear();
            
            renderSubjects();
            loadStats(); // Reload stats
            updateBulkActions();
            showNotification(result.message, 'success');
        } else {
            showNotification(result.message || 'বিষয় মুছতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        console.error('Error in bulk delete:', error);
        showNotification('বিষয় মুছতে সমস্যা হয়েছে', 'error');
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadFilterClasses();
    loadSubjects();
    loadStats();
    loadClasses();
});
</script>
@endpush
@endsection
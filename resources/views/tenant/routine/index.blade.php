@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">ক্লাস রুটিন</h1>
            <p class="text-gray-600 mt-2 bengali-text">সকল ক্লাসের সাপ্তাহিক রুটিন পরিচালনা করুন</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('tenant.routine.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="bengali-text">নতুন রুটিন তৈরি</span>
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">ক্লাস নির্বাচন</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" id="classFilter">
                    <option value="">সকল ক্লাস</option>
                    <option value="6">৬ষ্ঠ শ্রেণি</option>
                    <option value="7">৭ম শ্রেণি</option>
                    <option value="8">৮ম শ্রেণি</option>
                    <option value="9">৯ম শ্রেণি</option>
                    <option value="10">১০ম শ্রেণি</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">শিক্ষক নির্বাচন</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" id="teacherFilter">
                    <option value="">সকল শিক্ষক</option>
                    <option value="1">মোঃ আব্দুল করিম</option>
                    <option value="2">ফাতেমা খাতুন</option>
                    <option value="3">রহিমা বেগম</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিষয় নির্বাচন</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" id="subjectFilter">
                    <option value="">সকল বিষয়</option>
                    <option value="bangla">বাংলা</option>
                    <option value="english">ইংরেজি</option>
                    <option value="math">গণিত</option>
                    <option value="science">বিজ্ঞান</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors bengali-text">ফিল্টার প্রয়োগ</button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">মোট ক্লাস</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">৫</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">সাপ্তাহিক পিরিয়ড</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">২১০</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">সক্রিয় শিক্ষক</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">১৫</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">মোট বিষয়</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">১২</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Routine Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm bengali-text tab-button active" data-tab="class-6">
                    ৬ষ্ঠ শ্রেণি
                </button>
                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm bengali-text tab-button" data-tab="class-7">
                    ৭ম শ্রেণি
                </button>
                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm bengali-text tab-button" data-tab="class-8">
                    ৮ম শ্রেণি
                </button>
                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm bengali-text tab-button" data-tab="class-9">
                    ৯ম শ্রেণি
                </button>
                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm bengali-text tab-button" data-tab="class-10">
                    ১০ম শ্রেণি
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Class 6 Routine -->
            <div id="class-6" class="tab-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 bengali-text">৬ষ্ঠ শ্রেণির রুটিন</h3>
                    <div class="flex space-x-2">
                        <button class="text-indigo-600 hover:text-indigo-800 text-sm bengali-text">সম্পাদনা</button>
                        <button class="text-green-600 hover:text-green-800 text-sm bengali-text">প্রিন্ট</button>
                    </div>
                </div>
                
                <!-- Timetable -->
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">সময়</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">শনিবার</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">রবিবার</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">সোমবার</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">মঙ্গলবার</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">বুধবার</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">বৃহস্পতিবার</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r bengali-text">৮:০০ - ৮:৪৫</td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-orange-100 p-2 rounded">
                                        <div class="font-medium text-orange-800 bengali-text">আরবি</div>
                                        <div class="text-xs text-orange-600 bengali-text">হাফেজ সাহেব</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-blue-100 p-2 rounded">
                                        <div class="font-medium text-blue-800 bengali-text">বাংলা</div>
                                        <div class="text-xs text-blue-600 bengali-text">মোঃ করিম</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-green-100 p-2 rounded">
                                        <div class="font-medium text-green-800 bengali-text">ইংরেজি</div>
                                        <div class="text-xs text-green-600 bengali-text">ফাতেমা খাতুন</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-yellow-100 p-2 rounded">
                                        <div class="font-medium text-yellow-800 bengali-text">গণিত</div>
                                        <div class="text-xs text-yellow-600 bengali-text">রহিম উদ্দিন</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-purple-100 p-2 rounded">
                                        <div class="font-medium text-purple-800 bengali-text">বিজ্ঞান</div>
                                        <div class="text-xs text-purple-600 bengali-text">সালমা বেগম</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="bg-red-100 p-2 rounded">
                                        <div class="font-medium text-red-800 bengali-text">ইসলাম শিক্ষা</div>
                                        <div class="text-xs text-red-600 bengali-text">হাফেজ সাহেব</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r bengali-text">৮:৪৫ - ৯:৩০</td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-teal-100 p-2 rounded">
                                        <div class="font-medium text-teal-800 bengali-text">কুরআন মজিদ</div>
                                        <div class="text-xs text-teal-600 bengali-text">হাফেজ সাহেব</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-green-100 p-2 rounded">
                                        <div class="font-medium text-green-800 bengali-text">ইংরেজি</div>
                                        <div class="text-xs text-green-600 bengali-text">ফাতেমা খাতুন</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-yellow-100 p-2 rounded">
                                        <div class="font-medium text-yellow-800 bengali-text">গণিত</div>
                                        <div class="text-xs text-yellow-600 bengali-text">রহিম উদ্দিন</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-blue-100 p-2 rounded">
                                        <div class="font-medium text-blue-800 bengali-text">বাংলা</div>
                                        <div class="text-xs text-blue-600 bengali-text">মোঃ করিম</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-indigo-100 p-2 rounded">
                                        <div class="font-medium text-indigo-800 bengali-text">সমাজ বিজ্ঞান</div>
                                        <div class="text-xs text-indigo-600 bengali-text">নাসির সাহেব</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="bg-pink-100 p-2 rounded">
                                        <div class="font-medium text-pink-800 bengali-text">শারীরিক শিক্ষা</div>
                                        <div class="text-xs text-pink-600 bengali-text">খেলার শিক্ষক</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r bengali-text">৯:৩০ - ৯:৪৫</td>
                                <td colspan="6" class="px-4 py-3 text-sm text-center font-medium text-gray-600 bengali-text">বিরতি</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r bengali-text">৯:৪৫ - ১০:৩০</td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-cyan-100 p-2 rounded">
                                        <div class="font-medium text-cyan-800 bengali-text">হাদিস শরিফ</div>
                                        <div class="text-xs text-cyan-600 bengali-text">হাফেজ সাহেব</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-purple-100 p-2 rounded">
                                        <div class="font-medium text-purple-800 bengali-text">বিজ্ঞান</div>
                                        <div class="text-xs text-purple-600 bengali-text">সালমা বেগম</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-red-100 p-2 rounded">
                                        <div class="font-medium text-red-800 bengali-text">ইসলাম শিক্ষা</div>
                                        <div class="text-xs text-red-600 bengali-text">হাফেজ সাহেব</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-green-100 p-2 rounded">
                                        <div class="font-medium text-green-800 bengali-text">ইংরেজি</div>
                                        <div class="text-xs text-green-600 bengali-text">ফাতেমা খাতুন</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-r">
                                    <div class="bg-yellow-100 p-2 rounded">
                                        <div class="font-medium text-yellow-800 bengali-text">গণিত</div>
                                        <div class="text-xs text-yellow-600 bengali-text">রহিম উদ্দিন</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="bg-blue-100 p-2 rounded">
                                        <div class="font-medium text-blue-800 bengali-text">বাংলা</div>
                                        <div class="text-xs text-blue-600 bengali-text">মোঃ করিম</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Other class tabs would be similar -->
            <div id="class-7" class="tab-content hidden">
                <h3 class="text-lg font-semibold text-gray-900 bengali-text mb-4">৭ম শ্রেণির রুটিন</h3>
                <p class="text-gray-600 bengali-text">৭ম শ্রেণির রুটিন এখানে দেখানো হবে...</p>
            </div>

            <div id="class-8" class="tab-content hidden">
                <h3 class="text-lg font-semibold text-gray-900 bengali-text mb-4">৮ম শ্রেণির রুটিন</h3>
                <p class="text-gray-600 bengali-text">৮ম শ্রেণির রুটিন এখানে দেখানো হবে...</p>
            </div>

            <div id="class-9" class="tab-content hidden">
                <h3 class="text-lg font-semibold text-gray-900 bengali-text mb-4">৯ম শ্রেণির রুটিন</h3>
                <p class="text-gray-600 bengali-text">৯ম শ্রেণির রুটিন এখানে দেখানো হবে...</p>
            </div>

            <div id="class-10" class="tab-content hidden">
                <h3 class="text-lg font-semibold text-gray-900 bengali-text mb-4">১০ম শ্রেণির রুটিন</h3>
                <p class="text-gray-600 bengali-text">১০ম শ্রেণির রুটিন এখানে দেখানো হবে...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked button
            this.classList.add('active', 'border-indigo-500', 'text-indigo-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show target tab content
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });

    // Set first tab as active by default
    if (tabButtons.length > 0) {
        tabButtons[0].classList.add('border-indigo-500', 'text-indigo-600');
        tabButtons[0].classList.remove('border-transparent', 'text-gray-500');
    }
});
</script>
@endpush
@endsection
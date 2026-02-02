@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Left side - Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">মাসিক ফি সংগ্রহ</h1>
                <p class="text-gray-600 mt-1">শিক্ষার্থীদের মাসিক ফি সংগ্রহ এবং ব্যবস্থাপনা</p>
            </div>
            
            <!-- Right side - Actions -->
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a href="/fees/collect" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
                
                <!-- Print Button -->
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors print-button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    প্রিন্ট করুন
                </button>
                
                <!-- Export Button -->
                <button class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    এক্সপোর্ট করুন
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট শিক্ষার্থী</p>
                        <p class="text-3xl font-bold text-gray-900">{{ isset($students) ? $students->count() : 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-red-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট বকেয়া</p>
                        <p class="text-3xl font-bold text-gray-900">৳ {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], number_format($totalDue ?? 0)) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট আদায়</p>
                        <p class="text-3xl font-bold text-gray-900">৳ {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], number_format($totalCollected ?? 0)) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">চলতি মাস</p>
                        @php
                            $monthNamesBn = [
                                'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
                                'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
                                'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
                                'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
                            ];
                            $currentMonthEn = date('F');
                            $currentMonthBn = $monthNamesBn[$currentMonthEn] ?? $currentMonthEn;
                            $currentYearBn = str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], date('Y'));
                        @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ $currentMonthBn }} {{ $currentYearBn }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 no-print">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম</label>
                    <div class="relative">
                        <input type="text" id="searchByName" placeholder="নাম দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" oninput="filterStudents()">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী আইডি</label>
                    <input type="text" id="searchById" placeholder="আইডি দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" oninput="filterStudents()">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস</label>
                    <select id="classFilter" onchange="filterStudents()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">মাস</label>
                    <select id="monthFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">চলতি মাস</option>
                        <option value="january">জানুয়ারি</option>
                        <option value="february">ফেব্রুয়ারি</option>
                        <option value="march">মার্চ</option>
                        <option value="april">এপ্রিল</option>
                        <option value="may">মে</option>
                        <option value="june">জুন</option>
                        <option value="july">জুলাই</option>
                        <option value="august">আগস্ট</option>
                        <option value="september">সেপ্টেম্বর</option>
                        <option value="october">অক্টোবর</option>
                        <option value="november">নভেম্বর</option>
                        <option value="december">ডিসেম্বর</option>
                    </select>
                </div>

                <div>
                    <button onclick="resetFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                        খুঁজুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-4 text-left text-sm font-bold">আইডি</th>
                            <th class="px-4 py-4 text-left text-sm font-bold">নাম</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">জানু</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">ফেব্রু</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">মার্চ</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">এপ্রিল</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">মে</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">জুন</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">জুলাই</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">আগস্ট</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">সেপ্টে</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">অক্টো</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">নভে</th>
                            <th class="px-3 py-4 text-center text-sm font-bold">ডিসে</th>
                            <th class="px-4 py-4 text-center text-sm font-bold no-print">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody" class="divide-y divide-gray-200">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors student-row" 
                            data-name="{{ $student->name_bn ?? $student->name_en ?? '' }}"
                            data-id="{{ $student->student_id ?? $student->id }}"
                            data-class="{{ $student->class ?? '' }}">
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $student->student_id ?? $student->id }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($student->name_bn ?? $student->name_en ?? 'N', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $student->name_bn ?? $student->name_en ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->class ?? '' }} শ্রেণী</p>
                                    </div>
                                </div>
                            </td>
                            @php
                                $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
                                $monthlyStatus = $student->monthly_status_array ?? [];
                            @endphp
                            @foreach($months as $month)
                                @php
                                    $status = $monthlyStatus[$month] ?? ['paid' => false];
                                    $isPaid = $status['paid'];
                                @endphp
                                <td class="px-3 py-4 text-center">
                                    @if($isPaid)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 cursor-pointer hover:bg-green-200 transition-colors" title="পরিশোধিত" onclick="openFeeModal('{{ $student->student_id ?? $student->id }}', '{{ $month }}')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 cursor-pointer hover:bg-red-200 transition-colors" title="বকেয়া" onclick="openFeeModal('{{ $student->student_id ?? $student->id }}', '{{ $month }}')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-4 py-4 text-center no-print">
                                <button onclick="openFeeModal('{{ $student->student_id ?? $student->id }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                    ফি সংগ্রহ
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">কোনো শিক্ষার্থী পাওয়া যায়নি</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Fee Collection Modal -->
<div id="feeModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 backdrop-blur-sm transition-all duration-300">
    <div class="relative top-5 mx-auto p-0 border-0 w-full max-w-3xl shadow-2xl rounded-3xl bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                মাসিক ফি সংগ্রহ করুন
            </h2>
            <button onclick="closeFeeModal()" class="text-white hover:bg-white/20 p-2 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="collectForm" onsubmit="submitMonthlyFeeNew(event)" class="p-6">
            <input type="hidden" id="studentId" name="student_id">
            <input type="hidden" name="fee_type" value="monthly">
            
            <!-- Student Info Card -->
            <div class="bg-gray-50 rounded-2xl p-4 mb-6 flex items-center gap-4 border border-gray-100 shadow-sm">
                <div class="relative">
                    <img id="studentPhoto" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Student" class="w-16 h-16 rounded-full border-4 border-white shadow-md object-cover">
                    <div class="absolute -bottom-1 -right-1 bg-green-500 w-5 h-5 rounded-full border-4 border-white"></div>
                </div>
                <div>
                    <h3 id="studentName" class="text-xl font-bold text-gray-900 mb-1">শিক্ষার্থীর নাম</h3>
                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-gray-600 text-sm font-medium">
                        <span id="displayId" class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                            আইডি: INA-26-0001
                        </span>
                        <span id="displayClass" class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.827a2 2 0 00-2.788 0L2.121 8.3c-.46.461-.46 1.208 0 1.67l.47.47a.75.75 0 11-1.06 1.06l-.47-.47a2.25 2.25 0 010-3.182l5.485-5.485a3.5 3.5 0 014.88 0l5.485 5.485a2.25 2.25 0 010 3.182l-.47.47a.75.75 0 11-1.06-1.06l.47-.47c.46-.461.46-1.208 0-1.67l-5.485-5.485z"></path><path d="M10 10.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                            ক্লাস: প্লে
                        </span>
                        <span id="displaySection" class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                            শাখা: A
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Row 1 -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">বছর</label>
                    <select name="year" id="yearInput" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="2024">২০২৪</option>
                        <option value="2025">২০২৫</option>
                        <option value="2026" selected>২০২৬</option>
                        <option value="2027">২০২৭</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">মাস</label>
                    <select name="month" id="monthInput" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="january">জানুয়ারি</option>
                        <option value="february" selected>ফেব্রুয়ারি</option>
                        <option value="march">মার্চ</option>
                        <option value="april">এপ্রিল</option>
                        <option value="may">মে</option>
                        <option value="june">জুন</option>
                        <option value="july">জুলাই</option>
                        <option value="august">আগস্ট</option>
                        <option value="september">সেপ্টেম্বর</option>
                        <option value="october">অক্টোবর</option>
                        <option value="november">নভেম্বর</option>
                        <option value="december">ডিসেম্বর</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">মাসের সংখ্যা(অগ্রিম)</label>
                    <input type="number" name="month_count" value="1" min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" oninput="updateSummary('month')">
                </div>

                <!-- Row 2 -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">ভাউচার নম্বর</label>
                    <input type="text" name="voucher_no" id="voucherNo" value="" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 font-mono focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">মাসিক ফি</label>
                    <input type="number" name="total_amount" id="totalAmount" value="500" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 font-bold text-blue-600 focus:ring-2 focus:ring-blue-500 transition-all" readonly>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">প্রদত্ত পরিমাণ*</label>
                    <input type="text" name="paid_amount" id="paidAmount" value="500" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-bold text-green-600" oninput="updateSummary('paid')">
                </div>

                <!-- Row 3 -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">অনুদান থেকে সংগ্রহ</label>
                    <input type="text" name="donation_amount" id="donationAmount" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" oninput="updateSummary('donation')">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">আদায়কারি*</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input type="text" name="collector_name" value="{{ $currentUser->name ?? 'মোঃ লাভলু সেখ' }}" class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-all font-medium" readonly>
                    </div>
                </div>

                <!-- Row 4 -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">পেমেন্ট পদ্ধতি</label>
                    <select name="payment_method" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="cash" selected>নগদ</option>
                        <option value="bkash">বিকাশ</option>
                        <option value="bank">ব্যাংক</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">তারিখ*</label>
                    <div class="relative">
                        <input type="date" name="collection_date" id="collectionDate" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-200">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    হিসাবের সারাংশ
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
                        <span>মাসিক ফি:</span>
                        <span id="summaryFee" class="font-bold text-gray-900">৳ ৫০০</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
                        <span>প্রদত্ত পরিমাণ:</span>
                        <span id="summaryPaid" class="font-bold text-gray-900">৳ ৫০০</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
                        <span>অনুদান:</span>
                        <span id="summaryDonation" class="font-bold text-gray-900">৳ ০</span>
                    </div>
                    <div class="pt-2 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-lg font-black text-gray-900">মোট পরিমাণ:</span>
                        <span id="summaryTotal" class="text-xl font-black text-green-600">৳ ৫০০</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeFeeModal()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold transition-all transform hover:scale-105 text-sm">
                    বাতিল
                </button>
                <button type="submit" class="px-8 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-green-100 transition-all transform hover:scale-105 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-[60] backdrop-blur-sm transition-all duration-300">
    <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-2xl shadow-2xl rounded-2xl bg-white overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-6 flex flex-col items-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 id="successTitle" class="text-xl font-bold text-white text-center leading-tight">সংগ্রহ সম্পন্ন!</h2>
        </div>
        <div class="p-4">
            <div id="successContent" class="space-y-3 text-gray-700 font-medium">
                <!-- Content will be injected here -->
            </div>
            <div class="mt-4 flex gap-2">
                <button onclick="printAndClose()" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    রসিদ প্রিন্ট করুন
                </button>
                <button onclick="closeSuccessModal()" class="flex-1 py-2 bg-gray-900 hover:bg-black text-white rounded-lg font-bold transition-all transform hover:scale-[1.02] text-sm">
                    ঠিক আছে
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal Receipt Template -->
<script>
let studentsData = @json($studentsData ?? []);
let feeStructures = @json($feeStructures ?? []);
let currentStudent = null;

// Settings data for receipt
let schoolSettings = {
    logo: @json($settings->logo ? tenant_asset($settings->logo) : null),
    school_name: @json($settings->school_name_bn ?? $settings->school_name_en ?? 'স্মার্ট পাঠশালা'),
    address: @json($settings->address ?? 'প্রতিষ্ঠানের ঠিকানা'),
    established_year: @json($settings->established_year ?? '২০২০'),
    principal_name: @json($settings->principal_name ?? 'প্রধান শিক্ষক'),
    principal_mobile: @json($settings->principal_mobile ?? $settings->mobile ?? $settings->phone ?? '০১৭১২-৩৪৫৬৭৮')
};

// Helper function for tenant assets
function tenant_asset(path) {
    if (!path) return '/assets/logo.png';
    return '/storage/' + path;
}

// Helper to convert Bengali digits to English
function toEnglishDigits(str) {
    if (str === null || str === undefined) return '0';
    const banglaDigits = {'০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4', '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'};
    return str.toString().replace(/[০-৯]/g, d => banglaDigits[d]);
}

// Helper to parse number from input (handles Bengali digits)
function parseNumber(val) {
    return parseFloat(toEnglishDigits(val)) || 0;
}

// Helper to format to Bengali digits for display
function toBanglaDigits(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

function toBengaliNumber(num) {
    return toBanglaDigits(num);
}

const monthNamesBn = {
    'january': 'জানুয়ারি', 'february': 'ফেব্রুয়ারি', 'march': 'মার্চ', 'april': 'এপ্রিল',
    'may': 'মে', 'june': 'জুন', 'july': 'জুলাই', 'august': 'আগস্ট',
    'september': 'সেপ্টেম্বর', 'october': 'অক্টোবর', 'november': 'নভেম্বর', 'december': 'ডিসেম্বর'
};

function getSelectedMonths(startMonth, count) {
    const months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
    let startIndex = months.indexOf(startMonth.toLowerCase());
    if (startIndex === -1) startIndex = 0;
    
    let result = [];
    for (let i = 0; i < count; i++) {
        result.push(months[(startIndex + i) % 12]);
    }
    return result;
}

// Open fee modal
function openFeeModal(studentId, selectedMonth = null) {
    currentStudent = studentsData.find(s => s.id == studentId);
    if (!currentStudent) {
        alert('শিক্ষার্থী পাওয়া যায়নি!');
        return;
    }

    // Populate Student Info Card
    document.getElementById('studentId').value = studentId;
    document.getElementById('studentName').textContent = currentStudent.name;
    document.getElementById('displayId').innerHTML = `<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>আইডি: ${currentStudent.id}`;
    document.getElementById('displayClass').innerHTML = `<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.827a2 2 0 00-2.788 0L2.121 8.3c-.46.461-.46 1.208 0 1.67l.47.47a.75.75 0 11-1.06 1.06l-.47-.47a2.25 2.25 0 010-3.182l5.485-5.485a3.5 3.5 0 014.88 0l5.485 5.485a2.25 2.25 0 010 3.182l-.47.47a.75.75 0 11-1.06-1.06l.47-.47c.46-.461.46-1.208 0-1.67l-5.485-5.485z"></path><path d="M10 10.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>ক্লাস: ${currentStudent.class}`;
    document.getElementById('displaySection').innerHTML = `<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>শাখা: ${currentStudent.section}`;
    
    // Student Photo (Use the photo_url provided by the controller)
    document.getElementById('studentPhoto').src = currentStudent.photo_url;

    // Find and set monthly fee based on student's class
    const monthlyFee = feeStructures.find(fee => 
        fee.class_name === currentStudent.class || 
        fee.class_name === currentStudent.class.replace(/[^\d]/g, '')
    );
    
    const feeAmount = monthlyFee ? parseFloat(monthlyFee.amount) : 500;
    document.getElementById('totalAmount').value = feeAmount;
    document.getElementById('paidAmount').value = feeAmount;
    
    // Auto-generate Voucher No
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const randomV = Math.floor(100 + Math.random() * 900);
    document.getElementById('voucherNo').value = `${year}${month}${day}-${randomV}`;
    
    // Set month - if selectedMonth is provided, use it, otherwise use current month
    const monthSelect = document.getElementById('monthInput');
    if (selectedMonth) {
        monthSelect.value = selectedMonth;
    } else {
        const currentMonth = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(new Date()).toLowerCase();
        monthSelect.value = currentMonth;
    }

    updateSummary();
    
    document.getElementById('feeModal').classList.remove('hidden');
}

// Update summary calculations
function updateSummary(source = null) {
    const feeEl = document.getElementById('totalAmount');
    const monthsEl = document.querySelector('input[name="month_count"]');
    const paidEl = document.getElementById('paidAmount');
    const donationEl = document.getElementById('donationAmount');

    const fee = parseNumber(feeEl.value);
    const months = parseNumber(monthsEl.value) || 1;
    const paid = parseNumber(paidEl.value);
    
    const totalFee = fee * months;

    // Auto-calculate donation if paid is changed
    if (source === 'paid') {
        const calculatedDonation = Math.max(0, totalFee - paid);
        donationEl.value = calculatedDonation;
    } else if (source === 'month') {
        // If months change, default paid to totalFee
        paidEl.value = totalFee;
        donationEl.value = 0;
    }

    const donation = parseNumber(donationEl.value);
    const totalCollected = paid + donation;

    // Update Summary Box (Using Bengali digits)
    document.getElementById('summaryFee').textContent = `৳ ${toBengaliNumber(totalFee.toLocaleString())}`;
    document.getElementById('summaryPaid').textContent = `৳ ${toBengaliNumber(paid.toLocaleString())}`;
    document.getElementById('summaryDonation').textContent = `৳ ${toBengaliNumber(donation.toLocaleString())}`;
    document.getElementById('summaryTotal').textContent = `৳ ${toBengaliNumber(totalCollected.toLocaleString())}`;
}

// Close fee modal
function closeFeeModal() {
    document.getElementById('feeModal').classList.add('hidden');
    document.getElementById('collectForm').reset();
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    location.reload();
}

function printAndClose() {
    window.print();
    // Force reload after print
    setTimeout(() => {
        location.reload();
    }, 500);
}

function showSuccessModal(data, receiptNumbers) {
    const studentName = currentStudent ? currentStudent.name : 'শিক্ষার্থী';
    const monthsBn = data.selected_months.map(m => monthNamesBn[m] || m).join(', ');
    const voucherNo = data.voucher_no;
    const monthCount = data.month_count;
    
    const title = monthCount > 1 
        ? `✅ ${studentName}-এর ${toBengaliNumber(monthCount)} মাসের ফি সংগ্রহ সম্পন্ন`
        : `✅ ${studentName}-এর ${monthNamesBn[data.month] || data.month} মাসের ফি সংগ্রহ সম্পন্ন`;
    
    const receiptTemplate = (copyType) => `
        <div class="receipt-container border-2 border-gray-300 rounded-xl p-3 bg-white shadow-sm mb-3 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-gray-100 px-3 py-0.5 rounded-bl-lg border-l border-b border-gray-200 text-[9px] font-bold uppercase text-gray-500 tracking-wider">
                ${copyType}
            </div>
            
            <!-- School Header -->
            <div class="text-center border-b border-gray-200 pb-2 mb-2">
                <div class="flex items-center gap-3 mb-2">
                    <img src="${schoolSettings.logo ? schoolSettings.logo : '/assets/logo.png'}" alt="School Logo" class="w-16 h-16 object-contain border-2 border-gray-200 rounded-lg p-2 bg-white shadow-sm flex-shrink-0">
                    <div class="flex-1 text-center">
                        <h2 class="text-lg font-black text-gray-900 leading-tight">${schoolSettings.school_name}</h2>
                        <p class="text-xs text-gray-600 mt-1">${schoolSettings.address}</p>
                        <p class="text-xs text-gray-600">প্রতিষ্ঠান: ${schoolSettings.established_year} ইং</p>
                        <p class="text-xs text-gray-600">মোবাইল: ${schoolSettings.principal_mobile}</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mb-2">
                <h3 class="text-base font-black text-gray-800 tracking-tight">ফি প্রাপ্তির রসিদ</h3>
                <p class="text-xs text-gray-500 font-bold mt-0.5">ভাউচার নম্বর: ${voucherNo}</p>
            </div>
            
            <div class="flex items-center gap-3 mb-2">
                <div class="relative">
                    <img src="${currentStudent ? currentStudent.photo_url : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'}" alt="Student Photo" class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover shadow-sm">
                    <div class="absolute -bottom-0.5 -right-0.5 bg-green-500 w-3 h-3 rounded-full border-2 border-white shadow-sm"></div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">${studentName}</h4>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">আইডি: ${currentStudent ? currentStudent.id || currentStudent.student_id || 'N/A' : 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">${currentStudent ? currentStudent.class || 'N/A' : 'N/A'} শ্রেণী</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs">মাস:</span>
                    <span class="font-bold text-gray-900 text-xs">${monthsBn}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-y border-dashed border-gray-100">
                    <span class="text-gray-500 text-xs">সংগ্রহের পরিমাণ:</span>
                    <span class="text-base font-black text-green-600">৳ ${toBengaliNumber(data.paid_amount.toLocaleString())}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs">সংগ্রহের তারিখ:</span>
                    <span class="font-bold text-gray-900 text-xs">${new Date().toLocaleDateString('bn-BD')}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs">আদায়কারী:</span>
                    <span class="font-bold text-gray-900 text-xs">${data.collector_name || 'মোঃ লাভলু সেখ'}</span>
                </div>
            </div>
            <div class="mt-2 flex justify-between items-end">
                <div class="text-center">
                    <div class="w-16 border-t border-gray-300 mt-4"></div>
                    <p class="text-[8px] text-gray-400 mt-0.5">অভিভাবকের স্বাক্ষর</p>
                </div>
                <div class="text-center">
                    <div class="w-16 border-t border-gray-300 mt-4"></div>
                    <p class="text-[8px] text-gray-400 mt-0.5">কর্তৃপক্ষের স্বাক্ষর</p>
                </div>
            </div>
        </div>
    `;

    let html = `
        <div id="receiptPrintArea" class="space-y-4">
            ${receiptTemplate('অফিস কপি')}
            <div class="print-divider border-t-2 border-dashed border-gray-300 my-4 no-screen"></div>
            ${receiptTemplate('গ্রাহক কপি')}
        </div>
    `;
    
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successContent').innerHTML = html;
    document.getElementById('successModal').classList.remove('hidden');
    document.getElementById('feeModal').classList.add('hidden');
}

// Collect fee submission
async function submitMonthlyFeeNew(event) {
    event.preventDefault();
    const form = event.target;
    
    const startMonth = form.month.value;
    const monthCount = parseNumber(form.month_count.value) || 1;
    const selectedMonths = getSelectedMonths(startMonth, monthCount);
    
    const data = {
        student_id: form.student_id.value,
        fee_type: 'monthly',
        year: form.year.value,
        month: startMonth,
        selected_months: selectedMonths,
        month_count: monthCount,
        voucher_no: form.voucher_no.value,
        total_amount: parseNumber(form.total_amount.value) * monthCount,
        paid_amount: parseNumber(form.paid_amount.value),
        donation_amount: parseNumber(form.donation_amount.value),
        grant_amount: parseNumber(form.donation_amount.value), // Send as both for compatibility
        payment_method: form.payment_method.value,
        collection_date: form.collection_date.value,
        collector_name: form.collector_name.value
    };

    try {
        console.log('Submitting fee data:', data);
        const response = await fetch('{{ route("tenant.fees.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Server response:', result);
        
        if (result.success) {
            showSuccessModal(data, result.receipt_number);
        } else {
            alert(result.message || 'একটি ত্রুটি ঘটেছে!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('ফি সংগ্রহে সমস্যা হয়েছে।');
    }
}

// Filter students
function filterStudents() {
    const nameSearch = document.getElementById('searchByName').value.toLowerCase();
    const idSearch = document.getElementById('searchById').value.toLowerCase();
    const classFilter = document.getElementById('classFilter').value;
    
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const id = row.getAttribute('data-id').toLowerCase();
        const studentClass = row.getAttribute('data-class');
        
        const matchesName = name.includes(nameSearch);
        const matchesId = id.includes(idSearch);
        const matchesClass = !classFilter || studentClass === classFilter;
        
        if (matchesName && matchesId && matchesClass) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Reset filters
function resetFilters() {
    document.getElementById('searchByName').value = '';
    document.getElementById('searchById').value = '';
    document.getElementById('classFilter').value = '';
    document.getElementById('monthFilter').value = '';
    filterStudents();
}
</script>
@endsection

@push('styles')
<style>
@media print {
    /* Set page size to A5 */
    @page {
        size: A5;
        margin: 8mm;
    }
    
    /* Hide everything except what we want to print */
    body > *:not(#successModal) {
        display: none !important;
    }
    
    /* Show only the success modal */
    #successModal {
        display: block !important;
        position: static !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: auto !important;
        background: white !important;
        overflow: visible !important;
    }
    
    /* Remove modal styling */
    #successModal > div {
        all: unset !important;
        display: block !important;
        width: 100% !important;
        height: auto !important;
    }
    
    /* Hide modal header and buttons */
    #successModal .bg-gradient-to-r,
    #successModal .mt-4.flex.gap-2 {
        display: none !important;
    }
    
    /* Show receipt content */
    #successContent {
        display: block !important;
        visibility: visible !important;
    }
    
    /* Style receipt containers */
    .receipt-container {
        border: 2px solid #000 !important;
        margin-bottom: 12px !important;
        page-break-inside: avoid !important;
        background: white !important;
        padding: 8px !important;
        font-size: 9px !important;
        display: block !important;
        visibility: visible !important;
    }
    
    /* Text sizes */
    .receipt-container h2 {
        font-size: 14px !important;
    }
    
    .receipt-container h3 {
        font-size: 12px !important;
    }
    
    .receipt-container h4 {
        font-size: 10px !important;
    }
    
    .receipt-container p, .receipt-container span {
        font-size: 8px !important;
    }
    
    /* Images */
    .receipt-container img[alt="School Logo"] {
        width: 40px !important;
        height: 40px !important;
    }
    
    .receipt-container img[alt="Student Photo"] {
        width: 30px !important;
        height: 30px !important;
    }
    
    /* Show divider */
    .print-divider {
        display: block !important;
        border-top: 2px dashed #000 !important;
        margin: 15px 0 !important;
    }
    
    /* Ensure text is black */
    * {
        color: black !important;
    }
    
    /* Reduce spacing */
    .receipt-container .mb-2 {
        margin-bottom: 4px !important;
    }
    
    .receipt-container .mt-2 {
        margin-top: 4px !important;
    }
    
    .receipt-container .gap-4 {
        gap: 12px !important;
    }
    
    /* Signature section */
    .receipt-container .w-16 {
        width: 60px !important;
    }
}
</style>
@endpush

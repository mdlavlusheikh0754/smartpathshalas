@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-600">পরীক্ষা ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">পরীক্ষা তৈরি, সম্পাদনা এবং পরিচালনা করুন।</p>
            </div>
            <div class="flex gap-3">
                <button onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </button>
                <button onclick="openCalculateResultModal()" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Calculate Result
                </button>
                <button onclick="openAddModal()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    নতুন পরীক্ষা যোগ করুন
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট পরীক্ষা</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalExams">{{ $exams->count() }}</p>
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
                        <p class="text-3xl font-bold text-gray-900" id="completedExams">{{ $exams->where('status', 'completed')->count() }}</p>
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
                        <p class="text-3xl font-bold text-gray-900" id="ongoingExams">{{ $exams->where('status', 'ongoing')->count() }}</p>
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
                        <p class="text-3xl font-bold text-gray-900" id="upcomingExams">{{ $exams->where('status', 'upcoming')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exams Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">পরীক্ষার তালিকা</h3>
                <div id="bulkActions" class="hidden flex items-center gap-3">
                    <span class="text-sm text-gray-600"><span id="selectedCount">০</span>টি নির্বাচিত</span>
                    <button onclick="bulkDelete()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        নির্বাচিত মুছুন
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-4 text-left">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">পরীক্ষার নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">ধরন</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">শুরুর তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">শেষ তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700" style="min-width: 120px;">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>ফলাফল প্রকাশ</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">পরিচালনা</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($exams as $exam)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" class="exam-checkbox w-4 h-4 text-purple-600 rounded focus:ring-purple-500" value="{{ $exam->id }}" onchange="updateBulkActions()">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $exam->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">
                                    @if($exam->exam_type === 'monthly')
                                        {{ $exam->month ?? 'মাসিক' }}
                                    @elseif($exam->exam_type === 'weekly')
                                        সাপ্তাহিক
                                    @elseif($exam->exam_type === 'half_yearly')
                                        অর্ধবার্ষিক
                                    @elseif($exam->exam_type === 'annual')
                                        বার্ষিক
                                    @elseif($exam->exam_type === 'test')
                                        টেস্ট
                                    @else
                                        {{ $exam->exam_type ?? 'সাধারণ' }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $exam->start_date ? $exam->start_date->format('d/m/Y') : '--' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $exam->end_date ? $exam->end_date->format('d/m/Y') : '--' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($exam->status === 'upcoming')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        📅 আসন্ন
                                    </span>
                                @elseif($exam->status === 'ongoing')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ⏳ চলমান
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ সম্পন্ন
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center" style="min-width: 140px;">
                                <button 
                                    onclick="togglePublishExamButton({{ $exam->id }}, {{ $exam->is_published ? 'true' : 'false' }}, this)"
                                    class="px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-200 {{ $exam->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200 border border-green-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}"
                                    title="ক্লিক করে ফলাফল প্রকাশ/অপ্রকাশ করুন">
                                    @if($exam->is_published)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            প্রকাশিত
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                            অপ্রকাশিত
                                        </span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button onclick="editExam({{ $exam->id }})" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        সম্পাদনা
                                    </button>
                                    <button onclick="deleteExam({{ $exam->id }})" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        মুছুন
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-lg font-medium">কোনো পরীক্ষা পাওয়া যায়নি</p>
                                    <p class="text-sm">নতুন পরীক্ষা যোগ করতে উপরের বাটনে ক্লিক করুন</p>
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

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mx-auto mb-4 w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">সফল!</h3>
        <p id="successMessage" class="text-gray-600 mb-6">পরীক্ষা সফলভাবে সংরক্ষণ করা হয়েছে</p>
        <button onclick="closeSuccessModal()" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-bold w-full">
            ঠিক আছে
        </button>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mx-auto mb-4 w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">নিশ্চিতকরণ</h3>
        <p id="confirmDeleteMessage" class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে এই পরীক্ষাটি মুছে ফেলতে চান?</p>
        <div class="flex gap-3">
            <button onclick="closeConfirmDeleteModal(false)" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                বাতিল করুন
            </button>
            <button onclick="closeConfirmDeleteModal(true)" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-bold">
                মুছে ফেলুন
            </button>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mx-auto mb-4 w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">নিশ্চিতকরণ</h3>
        <p id="bulkDeleteMessage" class="text-gray-600 mb-6 text-lg">আপনি কি নিশ্চিত যে <span class="font-bold text-red-600" id="bulkDeleteCount">০</span>টি পরীক্ষা মুছে ফেলতে চান?</p>
        <div class="flex gap-3">
            <button onclick="closeBulkDeleteModal(false)" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                বাতিল করুন
            </button>
            <button onclick="closeBulkDeleteModal(true)" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-bold">
                মুছে ফেলুন
            </button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mx-auto mb-4 w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">ত্রুটি!</h3>
        <p id="errorMessage" class="text-gray-600 mb-6">একটি ত্রুটি ঘটেছে</p>
        <button onclick="closeErrorModal()" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-bold w-full">
            ঠিক আছে
        </button>
    </div>
</div>

<!-- Calculate Result Modal -->
<div id="calculateResultModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Calculate Result - মাসিক পরীক্ষার নম্বর যোগ করুন</h2>
                <button onclick="closeCalculateResultModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        পরীক্ষার ধরন নির্বাচন করুন (সামায়িক পরীক্ষা) *
                    </label>
                    <select id="termExamSelect" onchange="onTermExamChange()" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">সামায়িক পরীক্ষা নির্বাচন করুন</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        ক্লাস নির্বাচন করুন *
                    </label>
                    <select id="termClassSelect" onchange="loadAvailableMonthlyExamsForCalculate()" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">ক্লাস নির্বাচন করুন</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        মাসিক পরীক্ষা সিলেক্ট করুন (একাধিক নির্বাচন করতে পারবেন):
                    </label>
                    <div id="monthlyExamsCheckboxes" class="space-y-2 max-h-64 overflow-y-auto">
                        <p class="text-gray-500 text-sm">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button onclick="closeCalculateResultModal()" class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition-colors">
                        বাতিল করুন
                    </button>
                    <button onclick="calculateWithMonthlyMarks()" class="flex-1 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-bold transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        হিসাব করুন
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="examModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">নতুন পরীক্ষা যোগ করুন</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="examForm" onsubmit="saveExam(event)">
            <input type="hidden" id="examId" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষার নাম *</label>
                    <input type="text" id="examName" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষার ধরন</label>
                    <select id="examType" name="exam_type" onchange="toggleMonthDropdown()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">নির্বাচন করুন</option>
                        <option value="weekly">সাপ্তাহিক</option>
                        <option value="monthly">মাসিক</option>
                        <option value="1st_term">১ম টার্ম</option>
                        <option value="2nd_term">২য় টার্ম</option>
                        <option value="final">চূড়ান্ত</option>
                        <option value="annual">বার্ষিক</option>
                    </select>
                </div>

                <div id="monthSelection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">মাস নির্বাচন করুন</label>
                    <select id="month" name="month" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">মাস নির্বাচন করুন</option>
                        <option value="জানুয়ারি">জানুয়ারি</option>
                        <option value="ফেব্রুয়ারি">ফেব্রুয়ারি</option>
                        <option value="মার্চ">মার্চ</option>
                        <option value="এপ্রিল">এপ্রিল</option>
                        <option value="মে">মে</option>
                        <option value="জুন">জুন</option>
                        <option value="জুলাই">জুলাই</option>
                        <option value="আগস্ট">আগস্ট</option>
                        <option value="সেপ্টেম্বর">সেপ্টেম্বর</option>
                        <option value="অক্টোবর">অক্টোবর</option>
                        <option value="নভেম্বর">নভেম্বর</option>
                        <option value="ডিসেম্বর">ডিসেম্বর</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="upcoming">আসন্ন</option>
                        <option value="ongoing">চলমান</option>
                        <option value="completed">সম্পন্ন</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শুরুর তারিখ *</label>
                    <input type="date" id="startDate" name="start_date" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শেষ তারিখ *</label>
                    <input type="date" id="endDate" name="end_date" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">সর্বোচ্চ নম্বর</label>
                    <input type="number" id="totalMarks" name="total_marks" value="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পাস নম্বর</label>
                    <input type="number" id="passMarks" name="pass_marks" value="33" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">বর্ণনা</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    বাতিল করুন
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-bold">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Convert numbers to Bengali
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

// Initialize Bengali numbers on page load
document.addEventListener('DOMContentLoaded', function() {
    // Convert all stat numbers to Bengali
    document.getElementById('totalExams').textContent = toBengaliNumber(document.getElementById('totalExams').textContent);
    document.getElementById('completedExams').textContent = toBengaliNumber(document.getElementById('completedExams').textContent);
    document.getElementById('ongoingExams').textContent = toBengaliNumber(document.getElementById('ongoingExams').textContent);
    document.getElementById('upcomingExams').textContent = toBengaliNumber(document.getElementById('upcomingExams').textContent);
    
    // Initialize Bengali utilities if available
    if (typeof BengaliUtils !== 'undefined') {
        BengaliUtils.displayBengaliNumbers();
    }
});

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

function toggleMonthDropdown() {
    const examType = document.getElementById('examType').value;
    const monthSelection = document.getElementById('monthSelection');
    
    if (examType === 'monthly') {
        monthSelection.classList.remove('hidden');
    } else {
        monthSelection.classList.add('hidden');
    }
}

function showSuccessModal(message) {
    // Wait for DOM to be ready
    const checkModal = setInterval(() => {
        const successMessageEl = document.getElementById('successMessage');
        const successModalEl = document.getElementById('successModal');
        
        if (successMessageEl && successModalEl) {
            clearInterval(checkModal);
            successMessageEl.textContent = message;
            successModalEl.classList.remove('hidden');
            
            // Auto close after 3 seconds
            setTimeout(() => {
                closeSuccessModal();
            }, 3000);
        }
    }, 100);
    
    // Stop checking after 5 seconds
    setTimeout(() => {
        clearInterval(checkModal);
    }, 5000);
}

function closeSuccessModal() {
    const successModalEl = document.getElementById('successModal');
    if (successModalEl) {
        successModalEl.classList.add('hidden');
    }
}

function showErrorModal(message) {
    const errorMessageEl = document.getElementById('errorMessage');
    const errorModalEl = document.getElementById('errorModal');
    
    if (errorMessageEl && errorModalEl) {
        errorMessageEl.textContent = message;
        errorModalEl.classList.remove('hidden');
    } else {
        console.error('Error modal elements not found');
        alert(message || 'একটি ত্রুটি ঘটেছে');
    }
}

function closeErrorModal() {
    const errorModalEl = document.getElementById('errorModal');
    if (errorModalEl) {
        errorModalEl.classList.add('hidden');
    }
}

let deleteExamId = null;

function showConfirmDeleteModal(id) {
    deleteExamId = id;
    const confirmDeleteModalEl = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModalEl) {
        confirmDeleteModalEl.classList.remove('hidden');
    }
}

function closeConfirmDeleteModal(confirmed) {
    const confirmDeleteModalEl = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModalEl) {
        confirmDeleteModalEl.classList.add('hidden');
    }
    if (confirmed && deleteExamId) {
        performDelete(deleteExamId);
        deleteExamId = null;
    }
}

function performDelete(id) {
    fetch(`/exams/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal('পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে');
            
            // Reload page after modal closes
            setTimeout(() => {
                window.location.reload();
            }, 3500);
        } else {
            showErrorModal(data.message || 'পরীক্ষা মুছে ফেলা যায়নি');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    });
}

function deleteExam(id) {
    showConfirmDeleteModal(id);
}

function editExam(id) {
    // Fetch exam data and populate form
    fetch(`/exams/${id}/data`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.exam) {
                const exam = data.exam;
                document.getElementById('modalTitle').textContent = 'পরীক্ষা সম্পাদনা করুন';
                document.getElementById('examId').value = exam.id;
                document.getElementById('examName').value = exam.name;
                document.getElementById('examType').value = exam.exam_type || '';
                document.getElementById('status').value = exam.status;
                
                // Format dates for date input (YYYY-MM-DD)
                if (exam.start_date) {
                    const startDate = new Date(exam.start_date);
                    document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
                }
                if (exam.end_date) {
                    const endDate = new Date(exam.end_date);
                    document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
                }
                
                document.getElementById('totalMarks').value = exam.total_marks;
                document.getElementById('passMarks').value = exam.pass_marks;
                document.getElementById('description').value = exam.description || '';
                
                // Handle month dropdown for monthly exams
                if (exam.exam_type === 'monthly' && exam.month) {
                    document.getElementById('month').value = exam.month;
                    document.getElementById('monthSelection').classList.remove('hidden');
                } else {
                    document.getElementById('monthSelection').classList.add('hidden');
                }
                
                document.getElementById('examModal').classList.remove('hidden');
            } else {
                showErrorModal(data.message || 'পরীক্ষার তথ্য লোড করতে ব্যর্থ হয়েছে');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('পরীক্ষার তথ্য লোড করতে ব্যর্থ হয়েছে');
        });
}

function saveExam(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const examId = formData.get('id');
    const url = examId ? `/exams/${examId}` : '/exams';
    const method = examId ? 'PUT' : 'POST';
    
    formData.append('_method', method);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Log form data for debugging
    console.log('Form data:', Object.fromEntries(formData));
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            closeModal();
            showSuccessModal('পরীক্ষা সফলভাবে সংরক্ষণ করা হয়েছে');
            
            // Reload page after modal closes
            setTimeout(() => {
                window.location.reload();
            }, 3500);
        } else {
            showErrorModal(data.message || 'একটি ত্রুটি ঘটেছে');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    });
}

// Bulk delete functions
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.exam-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.exam-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    // Update selected count with Bengali numbers
    const count = checkboxes.length;
    selectedCount.textContent = toBengaliNumber(count);
    
    // Show/hide bulk actions
    if (count > 0) {
        bulkActions.classList.remove('hidden');
        bulkActions.classList.add('flex');
    } else {
        bulkActions.classList.add('hidden');
        bulkActions.classList.remove('flex');
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.exam-checkbox');
    selectAllCheckbox.checked = allCheckboxes.length > 0 && count === allCheckboxes.length;
}

function toBengaliNumber(num) {
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

let pendingBulkDeleteIds = [];

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.exam-checkbox:checked');
    const examIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (examIds.length === 0) {
        showErrorModal('অনুগ্রহ করে কমপক্ষে একটি পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    // Store exam IDs for later use
    pendingBulkDeleteIds = examIds;
    
    // Update modal with count
    const count = examIds.length;
    document.getElementById('bulkDeleteCount').textContent = toBengaliNumber(count);
    
    // Show modal
    document.getElementById('bulkDeleteModal').classList.remove('hidden');
}

function closeBulkDeleteModal(confirmed) {
    const modal = document.getElementById('bulkDeleteModal');
    modal.classList.add('hidden');
    
    if (!confirmed) {
        pendingBulkDeleteIds = [];
        return;
    }
    
    // Proceed with deletion
    executeBulkDelete();
}

function executeBulkDelete() {
    const examIds = pendingBulkDeleteIds;
    
    // Show loading state
    const bulkDeleteBtn = document.querySelector('#bulkActions button');
    const originalText = bulkDeleteBtn.innerHTML;
    bulkDeleteBtn.disabled = true;
    bulkDeleteBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        মুছে ফেলা হচ্ছে...
    `;
    
    fetch('/exams/bulk-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            exam_ids: examIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data.message || 'পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            bulkDeleteBtn.disabled = false;
            bulkDeleteBtn.innerHTML = originalText;
            showErrorModal(data.message || 'পরীক্ষা মুছে ফেলতে ব্যর্থ হয়েছে');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        bulkDeleteBtn.disabled = false;
        bulkDeleteBtn.innerHTML = originalText;
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    });
    
    pendingBulkDeleteIds = [];
}

// Calculate Result Modal Functions
let allExams = [];
let allClasses = [];
let selectedTermExam = null;
let selectedTermClass = null;
let selectedMonthlyExams = [];

// Load all exams and classes on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAllExamsAndClasses();
});

async function loadAllExamsAndClasses() {
    try {
        // Load exams
        const examsResponse = await fetch('/exams/api/list');
        const examsData = await examsResponse.json();
        if (examsData.success) {
            allExams = examsData.exams;
        }
        
        // Load classes
        const classesResponse = await fetch('/admin/results/api/classes');
        const classesData = await classesResponse.json();
        if (classesData.success) {
            allClasses = classesData.classes;
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

function openCalculateResultModal() {
    // Populate term exams
    populateTermExams();
    
    // Reset selections
    document.getElementById('termExamSelect').value = '';
    document.getElementById('termClassSelect').value = '';
    document.getElementById('monthlyExamsCheckboxes').innerHTML = '<p class="text-gray-500 text-sm">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>';
    selectedTermExam = null;
    selectedTermClass = null;
    selectedMonthlyExams = [];
    
    // Show modal
    document.getElementById('calculateResultModal').classList.remove('hidden');
    document.getElementById('calculateResultModal').classList.add('flex');
}

function closeCalculateResultModal() {
    document.getElementById('calculateResultModal').classList.add('hidden');
    document.getElementById('calculateResultModal').classList.remove('flex');
    selectedTermExam = null;
    selectedTermClass = null;
    selectedMonthlyExams = [];
}

function populateTermExams() {
    const termExamSelect = document.getElementById('termExamSelect');
    termExamSelect.innerHTML = '<option value="">সামায়িক পরীক্ষা নির্বাচন করুন</option>';
    
    // Filter term exams (exclude monthly exams)
    const termExams = allExams.filter(exam => exam.exam_type !== 'monthly');
    
    termExams.forEach(exam => {
        const option = document.createElement('option');
        option.value = exam.id;
        option.textContent = exam.name;
        option.dataset.examType = exam.exam_type;
        option.dataset.classes = JSON.stringify(exam.classes || []);
        termExamSelect.appendChild(option);
    });
}

function onTermExamChange() {
    const termExamSelect = document.getElementById('termExamSelect');
    const selectedOption = termExamSelect.options[termExamSelect.selectedIndex];
    
    if (selectedOption.value) {
        selectedTermExam = {
            id: selectedOption.value,
            exam_type: selectedOption.dataset.examType,
            classes: JSON.parse(selectedOption.dataset.classes || '[]')
        };
        
        // Populate classes for this exam
        populateClassesForTermExam();
    } else {
        selectedTermExam = null;
        document.getElementById('termClassSelect').innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
        document.getElementById('monthlyExamsCheckboxes').innerHTML = '<p class="text-gray-500 text-sm">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>';
    }
}

function populateClassesForTermExam() {
    const termClassSelect = document.getElementById('termClassSelect');
    termClassSelect.innerHTML = '<option value="">ক্লাস নির্বাচন করুন</option>';
    
    if (!selectedTermExam || !selectedTermExam.classes || selectedTermExam.classes.length === 0) {
        // If no classes specified, show all classes
        allClasses.forEach(cls => {
            const option = document.createElement('option');
            option.value = cls.id;
            option.textContent = cls.full_name;
            termClassSelect.appendChild(option);
        });
    } else {
        // Show only classes associated with this exam
        const examClassIds = selectedTermExam.classes;
        allClasses.forEach(cls => {
            if (examClassIds.includes(cls.id) || examClassIds.includes(String(cls.id))) {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.full_name;
                termClassSelect.appendChild(option);
            }
        });
    }
}

function loadAvailableMonthlyExamsForCalculate() {
    const classId = document.getElementById('termClassSelect').value;
    selectedTermClass = classId;
    
    if (!classId) {
        document.getElementById('monthlyExamsCheckboxes').innerHTML = '<p class="text-gray-500 text-sm">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>';
        return;
    }
    
    // Filter monthly exams
    const monthlyExams = allExams.filter(exam => exam.exam_type === 'monthly' && exam.month);
    
    const container = document.getElementById('monthlyExamsCheckboxes');
    
    if (monthlyExams.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">কোন মাসিক পরীক্ষা পাওয়া যায়নি</p>';
        return;
    }
    
    container.innerHTML = '';
    monthlyExams.forEach(exam => {
        const div = document.createElement('div');
        div.className = 'flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-green-300 transition-colors cursor-pointer';
        div.onclick = function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            updateSelectedMonthlyExams();
        };
        
        div.innerHTML = `
            <input type="checkbox" value="${exam.id}" class="monthly-exam-checkbox w-5 h-5 text-green-600 rounded focus:ring-green-500 cursor-pointer" onclick="event.stopPropagation(); updateSelectedMonthlyExams();">
            <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer flex-1">
                ${exam.name}
                <span class="ml-2 text-xs text-green-600">(${exam.month})</span>
            </label>
        `;
        
        container.appendChild(div);
    });
}

function updateSelectedMonthlyExams() {
    const checkboxes = document.querySelectorAll('.monthly-exam-checkbox:checked');
    selectedMonthlyExams = Array.from(checkboxes).map(cb => parseInt(cb.value));
}

async function calculateWithMonthlyMarks() {
    // Validate selections
    if (!selectedTermExam) {
        showErrorModal('সামায়িক পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    if (!selectedTermClass) {
        showErrorModal('ক্লাস নির্বাচন করুন');
        return;
    }
    
    if (selectedMonthlyExams.length === 0) {
        showErrorModal('অন্তত একটি মাসিক পরীক্ষা নির্বাচন করুন');
        return;
    }
    
    try {
        const requestData = {
            exam_id: selectedTermExam.id,
            class_id: selectedTermClass,
            monthly_exam_ids: selectedMonthlyExams
        };
        console.log('Sending request to calculate results:', requestData);
        
        const response = await fetch('/admin/results/api/calculate-with-monthly', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse response as JSON:', e);
            throw new Error('সার্ভার রেসপন্স সঠিক নয়: ' + responseText.substring(0, 100));
        }
        
        if (data.success) {
            closeCalculateResultModal();
            showSuccessModal(data.message || 'মাসিক নম্বর সফলভাবে যোগ করা হয়েছে');
        } else {
            showErrorModal(data.message || 'হিসাব করতে ব্যর্থ হয়েছে');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    }
}

// Toggle publish exam with button
async function togglePublishExamButton(examId, isPublished, buttonElement) {
    try {
        const response = await fetch('/admin/results/api/toggle-publish', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                exam_id: examId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update button appearance based on new status
            if (data.is_published) {
                buttonElement.className = 'px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-200 bg-green-100 text-green-700 hover:bg-green-200 border border-green-300';
                buttonElement.innerHTML = `
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        প্রকাশিত
                    </span>
                `;
                buttonElement.onclick = function() { togglePublishExamButton(examId, true, this); };
            } else {
                buttonElement.className = 'px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300';
                buttonElement.innerHTML = `
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                        অপ্রকাশিত
                    </span>
                `;
                buttonElement.onclick = function() { togglePublishExamButton(examId, false, this); };
            }
            
            const statusText = data.is_published ? 'প্রকাশিত' : 'অপ্রকাশিত';
            showSuccessModal(`ফলাফল সফলভাবে ${statusText} করা হয়েছে।`);
        } else {
            showErrorModal(data.message || 'পাবলিশ স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    }
}

// Toggle publish exam
async function togglePublishExam(examId, isPublished) {
    try {
        const response = await fetch('/admin/results/api/toggle-publish', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                exam_id: examId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const statusText = data.is_published ? 'প্রকাশিত' : 'অপ্রকাশিত';
            showSuccessModal(`ফলাফল সফলভাবে ${statusText} করা হয়েছে।`);
        } else {
            // Revert checkbox if failed
            event.target.checked = !isPublished;
            showErrorModal(data.message || 'পাবলিশ স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
        }
    } catch (error) {
        console.error('Error:', error);
        // Revert checkbox if failed
        event.target.checked = !isPublished;
        showErrorModal('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
    }
}

</script>
@endpush
@endsection

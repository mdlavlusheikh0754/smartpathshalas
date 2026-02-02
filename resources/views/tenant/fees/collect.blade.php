@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">ফি সংগ্রহ করুন</h1>
            <p class="text-gray-600 text-lg">শিক্ষার্থীদের থেকে বিভিন্ন ধরনের ফি সংগ্রহ করুন</p>
            <a href="{{ route('tenant.fees.index') }}" class="inline-flex items-center gap-2 mt-4 text-green-600 hover:text-green-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফি ড্যাশবোর্ডে ফিরে যান
            </a>
        </div>

        <!-- Fee Collection Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Admission Fee Card -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white bg-opacity-20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-white text-opacity-80 text-sm">আজকের সংগ্রহ</div>
                        <div class="text-2xl font-bold">০%</div>
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold mb-2">ভর্তি ফি আদায়</h3>
                <p class="text-blue-100 mb-6">নতুন শিক্ষার্থীদের ভর্তি ও সেশন ফি সংগ্রহ</p>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-blue-100">আজকের সংগ্রহ:</span>
                        <span class="font-bold">৳ ০</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-blue-100">মোট বকেয়া:</span>
                        <span class="font-bold">৳ ০</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-blue-100">শিক্ষার্থী সংখ্যা:</span>
                        <span class="font-bold">০ জন</span>
                    </div>
                </div>
                
                <a href="{{ route('tenant.fees.collect.admission') }}" class="w-full bg-white text-blue-600 py-4 rounded-2xl font-bold text-lg hover:bg-blue-50 transition-colors shadow-lg block text-center">
                    ভর্তি ফি সংগ্রহ করুন
                </a>
            </div>

            <!-- Monthly Fee Card -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white bg-opacity-20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-white text-opacity-80 text-sm">আজকের সংগ্রহ</div>
                        <div class="text-2xl font-bold">০%</div>
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold mb-2">মাসিক ফি আদায়</h3>
                <p class="text-green-100 mb-6">জানুয়ারি ২০২৬ মাসের বেতন সংগ্রহ</p>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-green-100">আজকের সংগ্রহ:</span>
                        <span class="font-bold">৳ ০</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-green-100">মোট বকেয়া:</span>
                        <span class="font-bold">৳ ০</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-green-100">শিক্ষার্থী সংখ্যা:</span>
                        <span class="font-bold">০ জন</span>
                    </div>
                </div>
                
                <a href="{{ route('tenant.fees.collect.monthly') }}" class="w-full bg-white text-green-600 py-4 rounded-2xl font-bold text-lg hover:bg-green-50 transition-colors shadow-lg block text-center">
                    মাসিক ফি সংগ্রহ করুন
                </a>
            </div>

            <!-- Exam Fee Card -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white bg-opacity-20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-white text-opacity-80 text-sm">আজকের সংগ্রহ</div>
                        <div class="text-2xl font-bold">০%</div>
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold mb-2">পরীক্ষার ফি আদায়</h3>
                <p class="text-purple-100 mb-6">বার্ষিক ও অর্ধবার্ষিক পরীক্ষার ফি সংগ্রহ</p>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">আজকের সংগ্রহ:</span>
                        <span class="font-bold">৳ ০</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">মোট বকেয়া:</span>
                        <span class="font-bold">৳ ০</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">শিক্ষার্থী সংখ্যা:</span>
                        <span class="font-bold">০ জন</span>
                    </div>
                </div>
                
                <a href="{{ route('tenant.fees.collect.exam') }}" class="w-full bg-white text-purple-600 py-4 rounded-2xl font-bold text-lg hover:bg-purple-50 transition-colors shadow-lg block text-center">
                    পরীক্ষার ফি সংগ্রহ করুন
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">আজকের মোট সংগ্রহ</p>
                        <p class="text-2xl font-bold text-gray-900">৳ ০</p>
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
                        <p class="text-2xl font-bold text-gray-900">৳ ০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট শিক্ষার্থী</p>
                        <p class="text-2xl font-bold text-gray-900">০ জন</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">সংগ্রহের হার</p>
                        <p class="text-2xl font-bold text-gray-900">০%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Fee Collection Modal -->
<div id="feeModal" class="modal">
    <div class="modal-content max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">ফি সংগ্রহ করুন</h2>
            <button onclick="closeFeeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="collectForm" onsubmit="collectFee(event)">
            <!-- Student Selection Section -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">শিক্ষার্থী নির্বাচন</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী খুঁজুন *</label>
                        <div class="relative">
                            <input type="text" id="studentSearch" placeholder="নাম বা রোল নম্বর দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" oninput="searchStudents()">
                            <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        
                        <!-- Search Results -->
                        <div id="searchResults" class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                            <!-- Results will be populated by JavaScript -->
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">নির্বাচিত শিক্ষার্থী</label>
                        <div id="selectedStudent" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 min-h-[52px] flex items-center">
                            কোনো শিক্ষার্থী নির্বাচিত নয়
                        </div>
                        <input type="hidden" id="selectedStudentId" name="student_id">
                        <input type="hidden" id="selectedFeeType" name="fee_type">
                    </div>
                </div>
            </div>

            <!-- Fee Information Section -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">ফি তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div id="monthField" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাস (মাসিক বেতনের জন্য) *</label>
                        <select id="monthSelect" name="month" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">মাস নির্বাচন করুন</option>
                            <option value="january">জানুয়ারি ২০২৬</option>
                            <option value="february">ফেব্রুয়ারি ২০২৬</option>
                            <option value="march">মার্চ ২০২৬</option>
                            <option value="april">এপ্রিল ২০২৬</option>
                            <option value="may">মে ২০২৬</option>
                            <option value="june">জুন ২০২৬</option>
                            <option value="july">জুলাই ২০২৬</option>
                            <option value="august">আগস্ট ২০২৬</option>
                            <option value="september">সেপ্টেম্বর ২০২৬</option>
                            <option value="october">অক্টোবর ২০২৬</option>
                            <option value="november">নভেম্বর ২০২৬</option>
                            <option value="december">ডিসেম্বর ২০২৬</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মোট পরিমাণ</label>
                        <input type="text" id="totalAmount" name="total_amount" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg font-bold text-green-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পরিশোধিত পরিমাণ *</label>
                        <input type="text" id="paidAmount" name="paid_amount" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg font-bold" oninput="calculateDue()" placeholder="৳ ০">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বকেয়া পরিমাণ</label>
                        <input type="text" id="dueAmount" name="due_amount" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg font-bold text-red-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ভাউচার নম্বর</label>
                        <input type="text" id="voucherNo" name="voucher_no" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 font-mono focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট পদ্ধতি *</label>
                        <select name="payment_method" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="cash">নগদ</option>
                            <option value="bank">ব্যাংক ট্রান্সফার</option>
                            <option value="mobile">মোবাইল ব্যাংকিং</option>
                            <option value="card">কার্ড</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ*</label>
                        <input type="date" name="collection_date" id="collectionDate" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">মন্তব্য</label>
                    <textarea name="remarks" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="অতিরিক্ত কোনো তথ্য থাকলে লিখুন"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="button" onclick="closeFeeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-xl font-bold text-center transition-colors">
                    বাতিল করুন
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    ফি সংগ্রহ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Message Modal -->
<div id="successModal" class="modal no-print">
    <div class="modal-content max-w-lg">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6 flex flex-col items-center rounded-t-xl -mt-8 -mx-8 mb-6">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-white text-center">সফল!</h3>
        </div>
        <div class="p-2">
            <div id="successContent" class="space-y-4 text-gray-700 font-medium">
                <!-- Content will be injected here -->
            </div>
            <div class="mt-8 flex gap-3 no-print">
                <button onclick="printAndClose()" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    রসিদ প্রিন্ট করুন
                </button>
                <button onclick="collectAnother()" class="flex-1 py-3 bg-gray-900 hover:bg-black text-white rounded-xl font-bold transition-all transform hover:scale-[1.02]">
                    ঠিক আছে
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide unnecessary elements */
    .no-print, .no-screen {
        display: none !important;
    }
    
    /* Hide main page elements when printing success modal */
    body > *:not(#successModal) {
        display: none !important;
    }
    
    /* Show only success modal when printing */
    #successModal {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        background: white !important;
        display: block !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    #successModal .modal-content {
        position: static !important;
        top: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        background: white !important;
    }

    /* Hide modal header and buttons */
    #successModal .bg-gradient-to-r, #successModal .mt-8.flex {
        display: none !important;
    }

    #successContent {
        padding: 0 !important;
        margin: 0 !important;
    }

    .receipt-container {
        border: 2px solid #e5e7eb !important;
        box-shadow: none !important;
        margin-bottom: 20px !important;
        page-break-inside: avoid !important;
        background-color: white !important;
        -webkit-print-color-adjust: exact !important;
    }

    .print-divider {
        display: block !important;
        border-top: 2px dashed #9ca3af !important;
        margin: 30px 0 !important;
    }
}
.modal {
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

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
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

.search-result-item {
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    cursor: pointer;
    transition: background-color 0.2s;
}

.search-result-item:hover {
    background-color: #f3f4f6;
}

.search-result-item:last-child {
    border-bottom: none;
}
</style>

<script>
// Student data from database
let students = @json($studentsData ?? []);

let feeStructure = {
    admission: {1: 3000, 2: 3500, 3: 4000, 4: 4500, 5: 5000},
    monthly: {1: 1500, 2: 1600, 3: 1800, 4: 1800, 5: 2000},
    exam: {1: 1000, 2: 1200, 3: 1200, 4: 1500, 5: 1500}
};

let selectedStudent = null;
let currentFeeType = null;

// Helper functions for Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

function toEnglishNumber(str) {
    const banglaDigits = {'০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4', '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'};
    return str.toString().replace(/[০-৯]/g, d => banglaDigits[d]);
}

// Open fee modal
function openFeeModal(feeType) {
    currentFeeType = feeType;
    document.getElementById('selectedFeeType').value = feeType;
    
    const feeTypeNames = {
        admission: 'ভর্তি/সেশন ফি সংগ্রহ',
        monthly: 'মাসিক বেতন সংগ্রহ',
        exam: 'পরীক্ষার ফি সংগ্রহ'
    };
    
    document.getElementById('modalTitle').textContent = feeTypeNames[feeType];
    
    // Show/hide month field for monthly fee
    const monthField = document.getElementById('monthField');
    if (feeType === 'monthly') {
        monthField.style.display = 'block';
        document.getElementById('monthSelect').required = true;
    } else {
        monthField.style.display = 'none';
        document.getElementById('monthSelect').required = false;
        document.getElementById('monthSelect').value = '';
    }
    
    // Auto-generate Voucher No
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const randomV = Math.floor(100 + Math.random() * 900);
    document.getElementById('voucherNo').value = `${year}${month}${day}-${randomV}`;
    
    document.getElementById('feeModal').classList.add('active');
    
    // Focus on search
    setTimeout(() => {
        document.getElementById('studentSearch').focus();
    }, 100);
}

// Close fee modal
function closeFeeModal() {
    document.getElementById('feeModal').classList.remove('active');
    
    // Reset form
    document.getElementById('collectForm').reset();
    document.getElementById('selectedStudent').innerHTML = 'কোনো শিক্ষার্থী নির্বাচিত নয়';
    document.getElementById('selectedStudent').className = 'w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 min-h-[52px] flex items-center';
    document.getElementById('selectedStudentId').value = '';
    document.getElementById('totalAmount').value = '';
    document.getElementById('dueAmount').value = '';
    document.getElementById('searchResults').classList.add('hidden');
    
    selectedStudent = null;
    currentFeeType = null;
}

// Search students
function searchStudents() {
    const searchTerm = document.getElementById('studentSearch').value.toLowerCase().trim();
    const resultsContainer = document.getElementById('searchResults');
    
    if (searchTerm.length < 1) {
        resultsContainer.classList.add('hidden');
        return;
    }
    
    const filteredStudents = students.filter(student => 
        student.name.toLowerCase().includes(searchTerm) || 
        student.id.toLowerCase().includes(searchTerm) ||
        (student.roll && student.roll.toLowerCase().includes(searchTerm))
    );
    
    if (filteredStudents.length === 0) {
        resultsContainer.innerHTML = '<div class="p-4 text-gray-500 text-center">কোনো শিক্ষার্থী পাওয়া যায়নি</div>';
        resultsContainer.classList.remove('hidden');
        return;
    }
    
    resultsContainer.innerHTML = filteredStudents.map(student => `
        <div class="search-result-item" onclick="selectStudent('${student.id}')">
            <div class="font-medium text-gray-900">${student.name}</div>
            <div class="text-sm text-gray-500">আইডি: ${student.id} | ক্লাস: ${student.class}</div>
        </div>
    `).join('');
    
    resultsContainer.classList.remove('hidden');
}

// Select student
function selectStudent(studentId) {
    selectedStudent = students.find(s => s.id === studentId);
    if (!selectedStudent) return;
    
    document.getElementById('selectedStudent').innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                ${selectedStudent.name.charAt(0)}
            </div>
            <div>
                <div class="font-medium text-gray-900">${selectedStudent.name}</div>
                <div class="text-sm text-gray-500">আইডি: ${selectedStudent.id} | ক্লাস: ${selectedStudent.class}</div>
            </div>
        </div>
    `;
    
    document.getElementById('selectedStudentId').value = selectedStudent.id;
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('studentSearch').value = selectedStudent.name;
    
    updateFeeAmount();
}

// Update fee amount based on student class and fee type
function updateFeeAmount() {
    if (!selectedStudent || !currentFeeType) {
        document.getElementById('totalAmount').value = '';
        return;
    }
    
    // Check if class mapping exists
    let className = selectedStudent.class;
    // Map class names to structure keys if needed
    const classMap = {
        'প্লে': 1, 'নার্সারি': 1, 'কেজি': 1,
        'প্রথম': 1, 'দ্বিতীয়': 2, 'তৃতীয়': 3, 'চতুর্থ': 4, 'পঞ্চম': 5
    };
    
    let classKey = classMap[className] || className;
    
    if (feeStructure[currentFeeType] && feeStructure[currentFeeType][classKey]) {
        const amount = feeStructure[currentFeeType][classKey];
        document.getElementById('totalAmount').value = `৳ ${toBengaliNumber(amount.toLocaleString())}`;
        document.getElementById('paidAmount').value = amount;
        calculateDue();
    }
}

// Calculate due amount
function calculateDue() {
    const totalAmountText = document.getElementById('totalAmount').value;
    const paidAmountValue = document.getElementById('paidAmount').value;
    
    if (!totalAmountText || !paidAmountValue) {
        document.getElementById('dueAmount').value = '';
        return;
    }
    
    const totalAmount = parseInt(toEnglishNumber(totalAmountText).replace(/[৳,\s]/g, ''));
    const paidAmount = parseInt(toEnglishNumber(paidAmountValue).replace(/[৳,\s]/g, ''));
    
    if (isNaN(totalAmount) || isNaN(paidAmount)) {
        document.getElementById('dueAmount').value = '';
        return;
    }
    
    const dueAmount = totalAmount - paidAmount;
    document.getElementById('dueAmount').value = `৳ ${toBengaliNumber(dueAmount.toLocaleString())}`;
}

// Collect fee
async function collectFee(event) {
    event.preventDefault();
    
    if (!selectedStudent) {
        alert('দয়া করে একজন শিক্ষার্থী নির্বাচন করুন');
        return;
    }
    
    const form = event.target;
    const totalAmount = parseInt(toEnglishNumber(document.getElementById('totalAmount').value).replace(/[৳,\s]/g, ''));
    const paidAmount = parseInt(toEnglishNumber(document.getElementById('paidAmount').value).replace(/[৳,\s]/g, ''));
    
    const data = {
        student_id: selectedStudent.id,
        fee_type: currentFeeType,
        month: form.month ? form.month.value : null,
        voucher_no: form.voucher_no.value,
        total_amount: totalAmount,
        paid_amount: paidAmount,
        payment_method: form.payment_method.value,
        collection_date: form.collection_date.value,
        remarks: form.remarks.value
    };

    try {
        const response = await fetch('{{ route("tenant.fees.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
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

function showSuccessModal(data, receiptNumber) {
    const studentName = selectedStudent ? selectedStudent.name : 'শিক্ষার্থী';
    const feeTypeNames = {
        admission: 'ভর্তি ফি',
        monthly: 'মাসিক বেতন',
        exam: 'পরীক্ষার ফি'
    };
    const feeTypeName = feeTypeNames[data.fee_type];
    
    const receiptTemplate = (copyType) => `
        <div class="receipt-container border-2 border-gray-300 rounded-2xl p-6 bg-white shadow-sm mb-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-gray-100 px-4 py-1 rounded-bl-xl border-l border-b border-gray-200 text-[10px] font-bold uppercase text-gray-500 tracking-wider">
                ${copyType}
            </div>
            <div class="text-center border-b border-gray-200 pb-4 mb-4">
                <h3 class="text-xl font-black text-gray-800 tracking-tight">${feeTypeName} রসিদ</h3>
                <p class="text-sm text-gray-500 font-bold mt-1">রসিদ নম্বর: ${toBengaliNumber(receiptNumber)}</p>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">শিক্ষার্থীর নাম:</span>
                    <span class="font-bold text-gray-900">${studentName}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">শিক্ষার্থী আইডি:</span>
                    <span class="font-bold text-gray-900 font-mono">${selectedStudent.id}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-y border-dashed border-gray-100">
                    <span class="text-gray-500 text-sm">সংগ্রহের পরিমাণ:</span>
                    <span class="text-xl font-black text-green-600">৳ ${toBengaliNumber(data.paid_amount.toLocaleString())}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">সংগ্রহের তারিখ:</span>
                    <span class="font-bold text-gray-900">${new Date(data.collection_date).toLocaleDateString('bn-BD')}</span>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-end">
                <div class="text-center">
                    <div class="w-24 border-t border-gray-300 mt-8"></div>
                    <p class="text-[10px] text-gray-400 mt-1">অভিভাবকের স্বাক্ষর</p>
                </div>
                <div class="text-center">
                    <div class="w-24 border-t border-gray-300 mt-8"></div>
                    <p class="text-[10px] text-gray-400 mt-1">কর্তৃপক্ষের স্বাক্ষর</p>
                </div>
            </div>
        </div>
    `;

    const html = `
        <div id="receiptPrintArea" class="space-y-4">
            ${receiptTemplate('অফিস কপি')}
            <div class="print-divider border-t-2 border-dashed border-gray-300 my-4 no-screen"></div>
            ${receiptTemplate('গ্রাহক কপি')}
        </div>
    `;
    
    document.getElementById('successContent').innerHTML = html;
    document.getElementById('successModal').classList.add('active');
    document.getElementById('feeModal').classList.remove('active');
}

function printAndClose() {
    window.print();
    setTimeout(() => {
        collectAnother();
    }, 500);
}

// Collect another fee
function collectAnother() {
    document.getElementById('successModal').classList.remove('active');
    location.reload();
}

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    const searchContainer = document.getElementById('studentSearch')?.parentElement;
    const resultsContainer = document.getElementById('searchResults');
    
    if (searchContainer && !searchContainer.contains(e.target)) {
        resultsContainer?.classList.add('hidden');
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($feeType))
        openFeeModal('{{ $feeType }}');
    @endif
});
</script>
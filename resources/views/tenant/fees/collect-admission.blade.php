@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-blue-600">ভর্তি ফি সংগ্রহ</h1>
                <p class="text-gray-600 mt-1">নতুন শিক্ষার্থীদের ভর্তি ও সেশন ফি সংগ্রহ করুন</p>
            </div>
            <a href="{{ route('tenant.fees.collect') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফি সংগ্রহ পেজে ফিরে যান
            </a>
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
                        <p class="text-3xl font-bold text-gray-900">
                            @php
                                $totalStudents = isset($students) ? $students->count() : 0;
                                $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                                $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                                echo str_replace($englishNumbers, $bengaliNumbers, $totalStudents);
                            @endphp
                        </p>
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
                        <p class="text-3xl font-bold text-gray-900">
                            @php
                                $totalDue = isset($students) ? ($students->sum('admission_fee') ?? ($totalStudents * 5000)) : 0;
                                $formattedDue = '৳ ' . number_format($totalDue);
                                echo str_replace($englishNumbers, $bengaliNumbers, $formattedDue);
                            @endphp
                        </p>
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
                        <p class="text-3xl font-bold text-gray-900">৳ ০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">পরিশোধিত</p>
                        <p class="text-3xl font-bold text-gray-900">
                            @php
                                $totalStudentsStr = str_replace($englishNumbers, $bengaliNumbers, $totalStudents);
                                echo "০/{$totalStudentsStr} শিক্ষার্থী";
                            @endphp
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম দিয়ে খুঁজুন</label>
                    <div class="relative">
                        <input type="text" id="searchByName" placeholder="শিক্ষার্থীর নাম দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" oninput="filterStudents()">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী আইডি দিয়ে খুঁজুন</label>
                    <div class="relative">
                        <input type="text" id="searchById" placeholder="শিক্ষার্থী আইডি দিয়ে খুঁজুন" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" oninput="filterStudents()">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস</label>
                    <select id="classFilter" onchange="filterStudents()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">সাল</label>
                    <select id="yearFilter" onchange="filterStudents()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="" selected>সকল সাল</option>
                        <option value="2026">২০২৬</option>
                        <option value="2025">২০২৫</option>
                        <option value="2024">২০২৪</option>
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
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শিক্ষার্থী আইডি</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">ক্লাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শাখা</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">ফি টাইপ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">সর্বশেষ পেমেন্ট</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span id="paginationInfo">কোনো শিক্ষার্থী নেই</span>
                </div>
                <div class="flex gap-2" id="paginationButtons">
                    <!-- Pagination buttons will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fee Collection Modal -->
<div id="feeModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-blue-600">ভর্তি ফি সংগ্রহ করুন</h2>
            <button onclick="closeFeeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="collectForm" onsubmit="collectFee(event)">
            <!-- Student Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-4">
                    <div id="studentInitial" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        M
                    </div>
                    <div class="flex-1">
                        <h3 id="studentName" class="text-lg font-bold text-gray-900">Md Salman Sheikh</h3>
                        <p id="studentDetails" class="text-sm text-gray-600">আইডি: STD001 | ক্লাস: N/A | শাখা: A</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">ক্লাস অনুযায়ী ফি</div>
                        <div id="classFeeAmount" class="text-lg font-bold text-blue-600">৳ ৫,০০০</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Fee Calculation -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">ফি হিসাব</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোট ভর্তি ফি</label>
                            <input type="text" id="totalAmount" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-lg font-bold text-blue-600" value="৳ ৫,০০০">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ছাড়</label>
                            <input type="text" id="discountAmount" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" oninput="calculateFinalAmount()" placeholder="৳ ০">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোট</label>
                            <input type="text" id="finalAmount" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-green-50 text-lg font-bold text-green-600" value="৳ ৫,০০০">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">পরিশোধিত পরিমাণ *</label>
                                <input type="text" id="paidAmount" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-bold" oninput="calculateDue()" placeholder="৳ ০">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">বকেয়া পরিমাণ</label>
                                <input type="text" id="dueAmount" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-red-50 text-lg font-bold text-red-600">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ভাউচার নম্বর</label>
                                <input type="text" id="voucherNo" name="voucher_no" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="স্বয়ংক্রিয়ভাবে তৈরি হবে">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট পদ্ধতি *</label>
                                <select name="payment_method" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="cash">নগদ</option>
                                    <option value="bank">ব্যাংক ট্রান্সফার</option>
                                    <option value="mobile">মোবাইল ব্যাংকিং</option>
                                    <option value="card">কার্ড</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Inventory Items -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">ইনভেন্টরি আইটেম</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">আইটেম নির্বাচন</label>
                            <select id="inventorySelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">আইটেম নির্বাচন করুন</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-stock="{{ $item->stock }}">
                                        {{ $item->name }} - ৳{{ number_format($item->price) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">পরিমাণ</label>
                                <input type="number" id="itemQuantity" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="১">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">দাম</label>
                                <input type="text" id="itemPrice" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
                            </div>
                        </div>
                        
                        <button type="button" onclick="addInventoryItem()" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                            আইটেম যোগ করুন
                        </button>

                        <!-- Selected Items List -->
                        <div id="selectedItems" class="hidden">
                            <h5 class="font-medium text-gray-900 mb-2">নির্বাচিত আইটেম:</h5>
                            <div id="itemsList" class="space-y-2 max-h-32 overflow-y-auto">
                                <!-- Items will be added here -->
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-300">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900">মোট ইনভেন্টরি খরচ:</span>
                                    <span id="totalInventoryCost" class="font-bold text-green-600">৳ ০</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">মন্তব্য</label>
                <textarea name="remarks" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="অতিরিক্ত কোনো তথ্য থাকলে লিখুন"></textarea>
            </div>

            <!-- Collector Info -->
            <div class="bg-green-50 rounded-lg p-4 mt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">ফি সংগ্রহকারী</div>
                        <div class="font-semibold text-gray-800">{{ $currentUser->name ?? 'অজানা ব্যবহারকারী' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">সংগ্রহের তারিখ</div>
                        <div class="font-semibold text-gray-800">{{ date('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Total Amount Summary -->
            <div class="bg-blue-50 rounded-lg p-4 mt-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">মোট পরিশোধযোগ্য পরিমাণ</div>
                        <div id="totalPaidAmount" class="text-xl font-bold text-blue-600">৳ ৫,০০০</div>
                        <div class="text-xs text-gray-500 mt-1">ভর্তি ফি + ইনভেন্টরি খরচ</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">সংগ্রহের সময়</div>
                        <div class="font-semibold text-gray-800">{{ date('h:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 mt-6">
                <button type="button" onclick="closeFeeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-bold transition-colors">
                    বাতিল করুন
                </button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold transition-colors">
                    ফি সংগ্রহ ও রসিদ ডাউনলোড করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal no-print">
    <div class="modal-content a5-modal">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 flex flex-col items-center rounded-t-xl -mt-6 -mx-6 mb-6">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-white text-center">সংগ্রহ সম্পন্ন!</h3>
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
                <button onclick="closeSuccessModal()" class="flex-1 py-3 bg-gray-900 hover:bg-black text-white rounded-xl font-bold transition-all transform hover:scale-[1.02]">
                    ঠিক আছে
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* A5 Modal Styling */
.a5-modal {
    width: 148mm !important; /* A5 width */
    max-width: 148mm !important;
    height: 210mm !important; /* A5 height */
    max-height: 210mm !important;
    overflow-y: auto !important;
    margin: 20px auto !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    border: 1px solid #e5e7eb !important;
}

/* Adjust modal backdrop for A5 */
#successModal {
    padding: 20px !important;
}

/* Scale content to fit A5 */
.a5-modal #successContent {
    font-size: 11px !important;
    line-height: 1.3 !important;
}

.a5-modal .receipt-container {
    max-width: 100% !important;
    margin: 0 !important;
    font-size: 11px !important;
}

.a5-modal h1 {
    font-size: 16px !important;
}

.a5-modal h2 {
    font-size: 14px !important;
}

.a5-modal h3 {
    font-size: 13px !important;
}

.a5-modal table {
    font-size: 11px !important;
}

.a5-modal .w-16.h-16 {
    width: 3rem !important;
    height: 3rem !important;
}

.a5-modal .p-6 {
    padding: 1rem !important;
}

.a5-modal .p-3 {
    padding: 0.5rem !important;
}

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
    padding: 1.5rem;
    max-width: 900px; /* Reduced from 1200px */
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
</style>

<script>
// Student data - populated from database
let students = @json($studentsData ?? []);

console.log('Students data loaded:', students);
console.log('Students count:', students.length);

// Fee structures data
let feeStructures = @json($feeStructures ?? []);

// Inventory items data
let inventoryItems = @json($inventoryItems ?? []);

let filteredStudents = [...students];
let selectedStudent = null;
let selectedItems = [];

// Initialize table on load
document.addEventListener('DOMContentLoaded', function() {
    renderStudentsTable();
});

// Calculate final amount after discount
function calculateFinalAmount() {
    // Bengali to English number conversion
    function toEnglishNumber(bengaliNum) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(bengaliNum).replace(/[০-৯]/g, function(match) {
            return englishNumbers[bengaliNumbers.indexOf(match)];
        });
    }
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const totalAmountText = document.getElementById('totalAmount').value;
    const discountAmountText = document.getElementById('discountAmount').value;
    
    const totalAmount = parseInt(toEnglishNumber(totalAmountText).replace(/[৳,\s]/g, '')) || 0;
    const discountAmount = parseInt(toEnglishNumber(discountAmountText).replace(/[৳,\s]/g, '')) || 0;
    
    const finalAmount = totalAmount - discountAmount;
    document.getElementById('finalAmount').value = `৳ ${toBengaliNumber(finalAmount.toLocaleString())}`;
    
    // Update total paid amount and due calculation
    updateTotalPaidAmount();
    calculateDue();
}

// Add inventory item
function addInventoryItem() {
    const itemSelect = document.getElementById('inventorySelect');
    const quantityInput = document.getElementById('itemQuantity');
    
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    const itemId = selectedOption.value;
    const itemName = selectedOption.text.split(' (')[0]; // Get name before category
    const quantity = parseInt(quantityInput.value) || 1;
    const unitPrice = parseFloat(selectedOption.dataset.price) || 0;
    const stock = parseInt(selectedOption.dataset.stock) || 0;
    const totalPrice = unitPrice * quantity;
    
    if (!itemId) {
        alert('দয়া করে একটি আইটেম নির্বাচন করুন');
        return;
    }
    
    if (quantity > stock) {
        alert(`স্টকে শুধুমাত্র ${stock} টি আছে`);
        return;
    }
    
    // Check if item already exists
    const existingItemIndex = selectedItems.findIndex(item => item.id === itemId);
    if (existingItemIndex !== -1) {
        const newQuantity = selectedItems[existingItemIndex].quantity + quantity;
        if (newQuantity > stock) {
            alert(`মোট পরিমাণ স্টকের চেয়ে বেশি হতে পারে না (স্টক: ${stock})`);
            return;
        }
        selectedItems[existingItemIndex].quantity = newQuantity;
        selectedItems[existingItemIndex].totalPrice = selectedItems[existingItemIndex].quantity * unitPrice;
    } else {
        selectedItems.push({
            id: itemId,
            name: itemName,
            quantity: quantity,
            unitPrice: unitPrice,
            totalPrice: totalPrice,
            stock: stock
        });
    }
    
    renderSelectedItems();
    
    // Reset form
    itemSelect.value = '';
    quantityInput.value = '';
    document.getElementById('itemPrice').value = '';
}

// Render selected items
function renderSelectedItems() {
    const itemsList = document.getElementById('itemsList');
    const selectedItemsContainer = document.getElementById('selectedItems');
    
    if (selectedItems.length === 0) {
        selectedItemsContainer.classList.add('hidden');
        return;
    }
    
    selectedItemsContainer.classList.remove('hidden');
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    itemsList.innerHTML = selectedItems.map((item, index) => `
        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border">
            <div class="flex-1">
                <span class="font-medium text-gray-900 text-sm">${item.name}</span>
                <div class="text-xs text-gray-500 mt-1">
                    পরিমাণ: ${toBengaliNumber(item.quantity)} | একক দাম: ৳${toBengaliNumber(item.unitPrice.toLocaleString())}
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-bold text-green-600 text-sm">৳ ${toBengaliNumber(item.totalPrice.toLocaleString())}</span>
                <button type="button" onclick="removeInventoryItem(${index})" class="text-red-600 hover:text-red-800 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
    
    updateInventoryTotal();
}

// Remove inventory item
function removeInventoryItem(index) {
    selectedItems.splice(index, 1);
    renderSelectedItems();
}

// Update inventory total
function updateInventoryTotal() {
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const total = selectedItems.reduce((sum, item) => sum + item.totalPrice, 0);
    document.getElementById('totalInventoryCost').textContent = `৳ ${toBengaliNumber(total.toLocaleString())}`;
    
    // Update total paid amount and due calculation
    updateTotalPaidAmount();
    calculateDue();
}

// Update item price when item is selected
document.getElementById('inventorySelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const priceInput = document.getElementById('itemPrice');
    const quantityInput = document.getElementById('itemQuantity');
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    if (selectedOption.value) {
        const price = selectedOption.dataset.price;
        const stock = selectedOption.dataset.stock;
        
        priceInput.value = `৳ ${toBengaliNumber(Number(price).toLocaleString())}`;
        quantityInput.max = stock;
        quantityInput.placeholder = `সর্বোচ্চ: ${toBengaliNumber(stock)}`;
    } else {
        priceInput.value = '';
        quantityInput.max = '';
        quantityInput.placeholder = '১';
    }
});

// Filter students
function filterStudents() {
    const nameSearch = document.getElementById('searchByName').value.toLowerCase();
    const idSearch = document.getElementById('searchById').value.toLowerCase();
    const classFilter = document.getElementById('classFilter').value;
    const yearFilter = document.getElementById('yearFilter').value;
    
    filteredStudents = students.filter(student => {
        const matchName = !nameSearch || student.name.toLowerCase().includes(nameSearch);
        const matchId = !idSearch || student.id.toLowerCase().includes(idSearch);
        const matchClass = !classFilter || student.class.includes(classFilter);
        const matchYear = !yearFilter || (student.academic_year && student.academic_year.includes(yearFilter));
        
        return matchName && matchId && matchClass && matchYear;
    });
    
    renderStudentsTable();
}

// Reset filters
function resetFilters() {
    document.getElementById('searchByName').value = '';
    document.getElementById('searchById').value = '';
    document.getElementById('classFilter').value = '';
    document.getElementById('yearFilter').value = '';
    
    filteredStudents = [...students];
    renderStudentsTable();
}

// Render students table
function renderStudentsTable() {
    const tbody = document.getElementById('studentsTableBody');
    
    if (filteredStudents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">কোনো শিক্ষার্থী পাওয়া যায়নি</td></tr>';
        document.getElementById('paginationInfo').textContent = 'কোনো শিক্ষার্থী নেই';
        return;
    }
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    tbody.innerHTML = filteredStudents.map(student => {
        const statusClass = student.status === 'পরিশোধিত' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        const actionButton = student.status === 'পরিশোধিত' ? 
            '<button class="bg-gray-100 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">পরিশোধিত</button>' :
            `<button onclick="openFeeModal('${student.id}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">আদায় করুন</button>`;
        
        // Photo display logic - same as students index page
        const photoDisplay = student.photo_url ? 
            `<img src="${student.photo_url}" alt="${student.name}" class="w-10 h-10 rounded-full object-cover border-2 border-blue-200">` :
            `<div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                ${student.name.charAt(0)}
            </div>`;
        
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">${toBengaliNumber(student.id)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        ${photoDisplay}
                        <span class="text-sm font-medium text-gray-900">${student.name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">${student.class}</td>
                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">${student.section}</td>
                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">${student.feeType}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="${statusClass} px-3 py-1 rounded-full text-xs font-bold">
                        ${student.status}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">${student.lastPayment === 'N/A' ? 'প্রযোজ্য নয়' : student.lastPayment}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${actionButton}
                </td>
            </tr>
        `;
    }).join('');
    
    // Update pagination info
    document.getElementById('paginationInfo').textContent = `মোট ${toBengaliNumber(filteredStudents.length)} জন শিক্ষার্থী`;
}

// Open fee modal
function openFeeModal(studentId) {
    selectedStudent = students.find(s => s.id === studentId);
    if (!selectedStudent) return;
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    // Update student photo and info in modal
    const studentInitialElement = document.getElementById('studentInitial');
    if (selectedStudent.photo_url) {
        // Replace the initial div with an image
        studentInitialElement.innerHTML = `<img src="${selectedStudent.photo_url}" alt="${selectedStudent.name}" class="w-12 h-12 rounded-full object-cover">`;
    } else {
        // Show initial letter
        studentInitialElement.textContent = selectedStudent.name.charAt(0);
    }
    
    document.getElementById('studentName').textContent = selectedStudent.name;
    document.getElementById('studentDetails').textContent = `আইডি: ${toBengaliNumber(selectedStudent.id)} | ক্লাস: ${selectedStudent.class} | শাখা: ${selectedStudent.section}`;
    
    // Set default amounts
    const defaultAmount = getClassAdmissionFee(selectedStudent.class);
    document.getElementById('totalAmount').value = `৳ ${toBengaliNumber(defaultAmount.toLocaleString())}`;
    document.getElementById('finalAmount').value = `৳ ${toBengaliNumber(defaultAmount.toLocaleString())}`;
    document.getElementById('classFeeAmount').textContent = `৳ ${toBengaliNumber(defaultAmount.toLocaleString())}`;
    document.getElementById('paidAmount').value = '';
    document.getElementById('discountAmount').value = '';
    document.getElementById('dueAmount').value = '';
    
    // Reset selected items
    selectedItems = [];
    renderSelectedItems();
    
    // Initialize total paid amount
    updateTotalPaidAmount();
    
    // Auto-generate Voucher No
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const randomV = Math.floor(100 + Math.random() * 900);
    document.getElementById('voucherNo').value = `${year}${month}${day}-${randomV}`;
    
    document.getElementById('feeModal').classList.add('active');
}

// Get admission fee for a class
function getClassAdmissionFee(studentClass) {
    const admissionFee = feeStructures.find(fee => 
        fee.fee_type === 'admission' && (fee.class_name === studentClass || fee.class_name === 'all')
    );
    
    return admissionFee ? Number(admissionFee.amount) : 5000; // Default 5000 if not found
}

// Close fee modal
function closeFeeModal() {
    document.getElementById('feeModal').classList.remove('active');
    selectedStudent = null;
    selectedItems = [];
    renderSelectedItems();
}

// Calculate due amount
function calculateDue() {
    if (!selectedStudent) return;
    
    // Bengali to English number conversion
    function toEnglishNumber(bengaliNum) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(bengaliNum).replace(/[০-৯]/g, function(match) {
            return englishNumbers[bengaliNumbers.indexOf(match)];
        });
    }
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const finalAmountText = document.getElementById('finalAmount').value;
    const paidAmountText = document.getElementById('paidAmount').value;
    
    const finalAmount = parseInt(toEnglishNumber(finalAmountText).replace(/[৳,\s]/g, '')) || 0;
    const paidAmount = parseInt(toEnglishNumber(paidAmountText).replace(/[৳,\s]/g, '')) || 0;
    
    // Add inventory cost to final amount (after discount has already been applied)
    const inventoryCost = selectedItems.reduce((sum, item) => sum + item.totalPrice, 0);
    const totalWithInventory = finalAmount + inventoryCost;
    
    // Due = Total (after discount + inventory) - Paid Amount
    const dueAmount = Math.max(0, totalWithInventory - paidAmount);
    document.getElementById('dueAmount').value = `৳ ${toBengaliNumber(dueAmount.toLocaleString())}`;
    
    // Update total paid amount display
    updateTotalPaidAmount();
}

// Update total paid amount calculation
function updateTotalPaidAmount() {
    // Bengali to English number conversion
    function toEnglishNumber(bengaliNum) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(bengaliNum).replace(/[০-৯]/g, function(match) {
            return englishNumbers[bengaliNumbers.indexOf(match)];
        });
    }
    
    // Bengali number conversion
    function toBengaliNumber(num) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(num).replace(/[0-9]/g, function(match) {
            return bengaliNumbers[englishNumbers.indexOf(match)];
        });
    }
    
    const finalAmountText = document.getElementById('finalAmount').value;
    const finalAmount = parseInt(toEnglishNumber(finalAmountText).replace(/[৳,\s]/g, '')) || 0;
    
    // Add inventory cost
    const inventoryCost = selectedItems.reduce((sum, item) => sum + item.totalPrice, 0);
    const totalAmount = finalAmount + inventoryCost;
    
    // Update the display element if it exists
    const totalPaidElement = document.getElementById('totalPaidAmount');
    if (totalPaidElement) {
        totalPaidElement.textContent = `৳ ${toBengaliNumber(totalAmount.toLocaleString())}`;
    }
}

// Collect fee
function collectFee(event) {
    event.preventDefault();
    
    if (!selectedStudent) return;
    
    const formData = new FormData(event.target);
    const paymentMethod = formData.get('payment_method');
    
    if (!paymentMethod) {
        alert('দয়া করে পেমেন্ট পদ্ধতি নির্বাচন করুন');
        return;
    }
    
    // Show loading state
    const submitButton = event.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'রসিদ তৈরি হচ্ছে...';
    submitButton.disabled = true;
    
    // Bengali to English number conversion
    function toEnglishNumber(bengaliNum) {
        const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return String(bengaliNum).replace(/[০-৯]/g, function(match) {
            return englishNumbers[bengaliNumbers.indexOf(match)];
        });
    }
    
    // Get amounts
    const totalAmountText = document.getElementById('totalAmount').value;
    const discountAmountText = document.getElementById('discountAmount').value;
    const paidAmountText = document.getElementById('paidAmount').value;
    
    const totalAmount = parseInt(toEnglishNumber(totalAmountText).replace(/[৳,\s]/g, '')) || 0;
    const discountAmount = parseInt(toEnglishNumber(discountAmountText).replace(/[৳,\s]/g, '')) || 0;
    const paidAmount = parseInt(toEnglishNumber(paidAmountText).replace(/[৳,\s]/g, '')) || 0;
    
    // Add inventory cost to total
    const inventoryCost = selectedItems.reduce((sum, item) => sum + item.totalPrice, 0);
    const finalTotalAmount = totalAmount + inventoryCost;
    
    // Prepare form data for submission
    const submitData = new FormData();
    submitData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    submitData.append('student_id', selectedStudent.id);
    submitData.append('payment_method', paymentMethod);
    submitData.append('voucher_no', document.getElementById('voucherNo').value);
    submitData.append('fee_type', 'admission');
    submitData.append('total_amount', finalTotalAmount);
    submitData.append('discount_amount', discountAmount);
    submitData.append('paid_amount', paidAmount);
    submitData.append('inventory_items', JSON.stringify(selectedItems));
    submitData.append('remarks', formData.get('remarks') || '');
    
    // Submit to server
    fetch('{{ route("tenant.fees.store") }}', {
        method: 'POST',
        body: submitData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update student status in local data
            selectedStudent.status = 'পরিশোধিত';
            selectedStudent.lastPayment = new Date().toLocaleDateString('bn-BD');
            
            // Generate and auto-download PDF receipt
            generateAndDownloadPDF(submitData, data.receipt_number);
            
            // Show success message
            alert('ফি সংগ্রহ সম্পন্ন! রসিদ ডাউনলোড হচ্ছে...');
            
            // Close modal and refresh
            setTimeout(() => {
                closeFeeModal();
                location.reload();
            }, 1000);
        } else {
            alert(data.message || 'ফি সংগ্রহে সমস্যা হয়েছে');
            // Reset button
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('ফি সংগ্রহে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        // Reset button
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

function generateAndDownloadPDF(data, receiptNumber) {
    const studentName = selectedStudent ? selectedStudent.name : 'শিক্ষার্থী';
    const voucherNo = data.get('voucher_no');
    
    // Helper to format to Bengali digits for display
    function toBengaliNumber(num) {
        if (num === null || num === undefined) return '০';
        const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
        return num.toString().replace(/\d/g, d => banglaDigits[d]);
    }
    
    // Get school information (no logo needed)
    const schoolInfo = {
        name: 'ইকরা নূরানিয়া একাডেমি',
        address: 'ঢাকা, বাংলাদেশ',
        phone: '০১৭১২-৩৪৫৬৭৮',
        email: 'info@iqranooraniacademy.edu.bd'
    };
    
    // Get collector name
    const collectorName = @json(Auth::user()->name ?? 'Admin');
    
    // Student photo display - removed for cleaner receipt
    const studentPhoto = '';
    
    const receiptTemplate = (copyType) => `
        <div class="receipt-container">
            <!-- Header with School Info -->
            <div class="receipt-header">
                <div style="margin-bottom: 0;">
                    <h1 style="font-size: 9pt; font-weight: bold; margin: 0; color: black; line-height: 1;">${schoolInfo.name}</h1>
                    <p style="font-size: 6pt; margin: 0; color: black;">${schoolInfo.address}</p>
                    <p style="font-size: 5pt; margin: 0; color: black;">📞 ${schoolInfo.phone} | ✉️ ${schoolInfo.email}</p>
                </div>
                <div style="position: absolute; top: 0; right: 0; border: 1px solid black; padding: 0 1px; font-size: 5pt; font-weight: bold; color: black;">
                    ${copyType}
                </div>
            </div>
            
            <!-- Receipt Title -->
            <div class="receipt-title">
                <h2 style="font-size: 8pt; font-weight: bold; margin: 0; color: black;">ভর্তি ফি রসিদ</h2>
                <p style="margin: 0; font-weight: bold; color: black; font-size: 6pt;">ভাউচার নম্বর: ${toBengaliNumber(voucherNo)}</p>
            </div>
            
            <!-- Receipt Body -->
            <div class="receipt-body">
                <!-- Student Info -->
                <div class="student-info">
                    <h3 style="font-size: 7pt; font-weight: bold; margin: 0; color: black; text-align: center;">${studentName}</h3>
                    <div style="font-size: 5pt; color: black; text-align: center;">
                        <span style="font-weight: bold;">আইডি:</span> ${toBengaliNumber(selectedStudent.id)} | 
                        <span style="font-weight: bold;">ক্লাস:</span> ${selectedStudent.class} | 
                        <span style="font-weight: bold;">শাখা:</span> ${selectedStudent.section}
                    </div>
                </div>
                
                <!-- Payment Details Table -->
                <table class="payment-table">
                    <tr>
                        <td style="font-weight: bold; color: black; width: 60%; font-size: 6pt;">মোট ভর্তি ফি</td>
                        <td style="text-align: right; font-weight: bold; color: black; border-right: none; font-size: 6pt;">৳ ${toBengaliNumber(data.get('total_amount'))}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: black; font-size: 6pt;">ছাড়</td>
                        <td style="text-align: right; font-weight: bold; color: black; border-right: none; font-size: 6pt;">৳ ${toBengaliNumber(data.get('discount_amount') || 0)}</td>
                    </tr>
                    <tr style="background: #f0f0f0;">
                        <td style="font-weight: bold; color: black; font-size: 6pt;">সংগ্রহের পরিমাণ</td>
                        <td style="text-align: right; font-weight: bold; font-size: 7pt; color: black; border-right: none;">৳ ${toBengaliNumber(data.get('paid_amount'))}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: black; font-size: 6pt;">পেমেন্ট পদ্ধতি</td>
                        <td style="text-align: right; color: black; border-right: none; font-size: 6pt;">${data.get('payment_method') === 'cash' ? 'নগদ' : data.get('payment_method')}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: black; font-size: 6pt;">তারিখ ও সময়</td>
                        <td style="text-align: right; color: black; border-right: none; font-size: 6pt;">${toBengaliNumber(new Date().toLocaleDateString('bn-BD'))} - ${toBengaliNumber(new Date().toLocaleTimeString('bn-BD', {hour: '2-digit', minute: '2-digit'}))}</td>
                    </tr>
                    <tr style="border-bottom: none;">
                        <td style="font-weight: bold; color: black; border-bottom: none; font-size: 6pt;">আদায়কারী</td>
                        <td style="text-align: right; font-weight: bold; color: black; border-right: none; border-bottom: none; font-size: 6pt;">${collectorName}</td>
                    </tr>
                </table>
                
                <!-- Signature Section -->
                <div class="signature-section">
                    <div style="display: flex; justify-content: space-between;">
                        <div style="text-align: center; width: 45%;">
                            <div style="height: 10px; border-bottom: 1px solid black; margin-bottom: 0;"></div>
                            <p style="font-size: 5pt; font-weight: bold; margin: 0; color: black;">অভিভাবকের স্বাক্ষর</p>
                        </div>
                        <div style="text-align: center; width: 45%;">
                            <div style="height: 10px; border-bottom: 1px solid black; margin-bottom: 0;"></div>
                            <p style="font-size: 5pt; font-weight: bold; margin: 0; color: black;">কর্তৃপক্ষের স্বাক্ষর</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="footer">
                    <p style="font-size: 5pt; margin: 0; color: black; font-style: italic;">ধন্যবাদ! আপনার সন্তানের উজ্জ্বল ভবিষ্যতের জন্য আমরা প্রতিশ্রুতিবদ্ধ।</p>
                </div>
            </div>
        </div>
    `;

    // Create a temporary container for the receipt
    const printContainer = document.createElement('div');
    printContainer.innerHTML = `
        <html>
        <head>
            <meta charset="UTF-8">
            <title>ভর্তি ফি রসিদ - ${studentName}</title>
            <style>
                @page {
                    size: A5;
                    margin: 0.15in;
                }
                body {
                    font-family: 'Noto Sans Bengali', Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background: white;
                    color: black;
                    font-size: 8pt;
                    line-height: 1.0;
                }
                .receipt-container {
                    height: 3.2in;
                    width: 100%;
                    background: white;
                    border: 1px solid black;
                    margin-bottom: 0;
                    page-break-inside: avoid;
                    display: flex;
                    flex-direction: column;
                }
                .receipt-header {
                    border-bottom: 1px solid black;
                    padding: 1px;
                    text-align: center;
                    position: relative;
                    flex-shrink: 0;
                }
                .receipt-title {
                    border-bottom: 1px solid gray;
                    padding: 1px;
                    text-align: center;
                    background: #f8f8f8;
                    flex-shrink: 0;
                }
                .receipt-body {
                    padding: 1px;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }
                .student-info {
                    border: 1px solid gray;
                    padding: 1px;
                    margin-bottom: 1px;
                    background: #fafafa;
                    flex-shrink: 0;
                }
                .payment-table {
                    width: 100%;
                    border-collapse: collapse;
                    border: 1px solid black;
                    margin: 0;
                    font-size: 6pt;
                    flex-shrink: 0;
                }
                .signature-section {
                    margin-top: auto;
                    padding-top: 1px;
                    border-top: 1px solid gray;
                    flex-shrink: 0;
                }
                .footer {
                    text-align: center;
                    border-top: 1px solid gray;
                    padding-top: 0;
                    margin-top: 0;
                    flex-shrink: 0;
                }
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
                td {
                    vertical-align: top;
                    padding: 0px;
                    border-right: 1px solid gray;
                    border-bottom: 1px solid gray;
                }
                .cut-line {
                    border-top: 1px dashed black;
                    margin: 0;
                    text-align: center;
                    padding: 0;
                    font-size: 6pt;
                    height: 8px;
                }
                @media print {
                    body { 
                        -webkit-print-color-adjust: exact;
                        font-size: 6pt;
                    }
                    .receipt-container { 
                        box-shadow: none !important;
                        height: 3.2in;
                        margin-bottom: 0;
                    }
                    .cut-line {
                        margin: 0;
                        padding: 0;
                        height: 8px;
                    }
                }
            </style>
        </head>
        <body>
            ${receiptTemplate('অফিস কপি')}
            <div class="cut-line">
                <span style="background: white; padding: 0 2px; font-weight: bold; font-size: 6pt;">✂️ কাটার লাইন</span>
            </div>
            ${receiptTemplate('গ্রাহক কপি')}
        </body>
        </html>
    `;
    
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContainer.innerHTML);
    printWindow.document.close();
    
    // Wait for content to load, then print and close
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };
}

function printAndClose() {
    // Add a small delay to ensure modal content is fully rendered
    setTimeout(() => {
        // Ensure the receipt content is visible
        const receiptArea = document.getElementById('receiptPrintArea');
        const successModal = document.getElementById('successModal');
        
        if (receiptArea && successModal) {
            // Make sure modal is visible for print
            successModal.style.display = 'block';
            receiptArea.style.display = 'block';
            
            // Print the page
            window.print();
            
            // Close modal after print dialog
            setTimeout(() => {
                closeSuccessModal();
            }, 1000);
        } else {
            console.error('Receipt content not found for printing');
            alert('প্রিন্ট করার জন্য রসিদের তথ্য পাওয়া যায়নি');
        }
    }, 300);
}

function showSuccessModal(data, receiptNumber) {
    const studentName = selectedStudent ? selectedStudent.name : 'শিক্ষার্থী';
    const voucherNo = data.get('voucher_no');
    
    // Helper to format to Bengali digits for display
    function toBengaliNumber(num) {
        if (num === null || num === undefined) return '০';
        const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
        return num.toString().replace(/\d/g, d => banglaDigits[d]);
    }
    
    // Get school information with logo
    const schoolInfo = {
        name: 'ইকরা নূরানিয়া একাডেমি',
        address: 'ঢাকা, বাংলাদেশ',
        phone: '০১৭১২-৩৪৫৬৭৮',
        email: 'info@iqranooraniacademy.edu.bd',
        logo: '/images/school-logo.png' // Add your school logo path here
    };
    
    // Get collector name (current user or from form)
    const collectorName = @json(Auth::user()->name ?? 'Admin');
    
    // Student photo display (black and white for print)
    const studentPhoto = selectedStudent && selectedStudent.photo_url ? 
        `<img src="${selectedStudent.photo_url}" alt="${studentName}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-800">` :
        `<div class="w-16 h-16 bg-gray-200 border-2 border-gray-800 rounded-full flex items-center justify-center text-gray-800 font-bold text-xl">
            ${studentName.charAt(0)}
        </div>`;
    
    const receiptTemplate = (copyType) => `
        <div class="receipt-container bg-white border-2 border-gray-800 mb-8 print-page">
            <!-- Header with School Info and Logo -->
            <div class="border-b-2 border-gray-800 p-6 text-center">
                <div class="flex items-center justify-center gap-4 mb-3">
                    <img src="${schoolInfo.logo}" alt="School Logo" class="w-12 h-12 object-contain" onerror="this.style.display='none'">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">${schoolInfo.name}</h1>
                        <p class="text-sm text-gray-700">${schoolInfo.address}</p>
                    </div>
                </div>
                <div class="text-xs text-gray-600 space-x-4">
                    <span>📞 ${schoolInfo.phone}</span>
                    <span>✉️ ${schoolInfo.email}</span>
                </div>
                <div class="absolute top-2 right-2 border border-gray-600 px-2 py-1 text-xs font-bold text-gray-700">
                    ${copyType}
                </div>
            </div>
            
            <!-- Receipt Title -->
            <div class="border-b border-gray-400 p-4 text-center bg-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-1">ভর্তি ফি রসিদ</h2>
                <p class="text-gray-700 font-semibold">ভাউচার নম্বর: ${toBengaliNumber(voucherNo)}</p>
            </div>
            
            <!-- Student Info with Photo -->
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6 p-3 border border-gray-400">
                    ${studentPhoto}
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">${studentName}</h3>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><span class="font-semibold">আইডি:</span> ${toBengaliNumber(selectedStudent.id)}</p>
                            <p><span class="font-semibold">ক্লাস:</span> ${selectedStudent.class} | <span class="font-semibold">শাখা:</span> ${selectedStudent.section}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Details Table -->
                <table class="w-full border-collapse border border-gray-800 mb-6">
                    <tr class="border-b border-gray-600">
                        <td class="border-r border-gray-600 p-3 font-semibold text-gray-700">মোট ফি</td>
                        <td class="p-3 text-right font-bold">৳ ${toBengaliNumber(data.get('total_amount'))}</td>
                    </tr>
                    <tr class="border-b border-gray-600">
                        <td class="border-r border-gray-600 p-3 font-semibold text-gray-700">ছাড়</td>
                        <td class="p-3 text-right font-bold">৳ ${toBengaliNumber(data.get('discount_amount') || 0)}</td>
                    </tr>
                    <tr class="border-b-2 border-gray-800 bg-gray-100">
                        <td class="border-r border-gray-600 p-3 font-bold text-gray-900">সংগ্রহের পরিমাণ</td>
                        <td class="p-3 text-right font-bold text-xl">৳ ${toBengaliNumber(data.get('paid_amount'))}</td>
                    </tr>
                    <tr class="border-b border-gray-600">
                        <td class="border-r border-gray-600 p-3 font-semibold text-gray-700">পেমেন্ট পদ্ধতি</td>
                        <td class="p-3 text-right">${data.get('payment_method') === 'cash' ? 'নগদ' : data.get('payment_method')}</td>
                    </tr>
                    <tr class="border-b border-gray-600">
                        <td class="border-r border-gray-600 p-3 font-semibold text-gray-700">তারিখ</td>
                        <td class="p-3 text-right">${toBengaliNumber(new Date().toLocaleDateString('bn-BD'))}</td>
                    </tr>
                    <tr>
                        <td class="border-r border-gray-600 p-3 font-semibold text-gray-700">আদায়কারী</td>
                        <td class="p-3 text-right font-semibold">${collectorName}</td>
                    </tr>
                </table>
                
                <!-- Signature Section -->
                <div class="mt-8 pt-4 border-t border-gray-400">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="h-16 border-b border-gray-600 mb-2"></div>
                            <p class="text-sm font-semibold text-gray-700">অভিভাবকের স্বাক্ষর</p>
                        </div>
                        <div class="text-center">
                            <div class="h-16 border-b border-gray-600 mb-2"></div>
                            <p class="text-sm font-semibold text-gray-700">কর্তৃপক্ষের স্বাক্ষর</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-6 text-center border-t border-gray-300 pt-3">
                    <p class="text-xs text-gray-600">ধন্যবাদ! আপনার সন্তানের উজ্জ্বল ভবিষ্যতের জন্য আমরা প্রতিশ্রুতিবদ্ধ।</p>
                </div>
            </div>
        </div>
    `;

    const html = `
        <style>
            /* Screen styles */
            .receipt-container {
                position: relative;
                max-width: 210mm;
                margin: 0 auto;
                background: white !important;
            }
            
            /* Print styles */
            @media print {
                @page {
                    size: A5;
                    margin: 10mm;
                }
                
                /* Reset everything for print */
                * {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                
                /* Hide everything except the success modal */
                body > *:not(#successModal) {
                    display: none !important;
                }
                
                /* Reset modal for print */
                #successModal {
                    position: static !important;
                    display: block !important;
                    background: white !important;
                    width: 100% !important;
                    height: auto !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    overflow: visible !important;
                }
                
                #successModal .modal-content {
                    position: static !important;
                    display: block !important;
                    background: white !important;
                    width: 100% !important;
                    height: auto !important;
                    max-width: none !important;
                    max-height: none !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    box-shadow: none !important;
                    border: none !important;
                    border-radius: 0 !important;
                    overflow: visible !important;
                }
                
                /* Hide modal header and buttons */
                #successModal .bg-gradient-to-r,
                #successModal .no-print {
                    display: none !important;
                }
                
                /* Show only receipt content */
                #successContent {
                    display: block !important;
                    width: 100% !important;
                    height: auto !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    background: white !important;
                }
                
                #receiptPrintArea,
                .print-content {
                    display: block !important;
                    width: 100% !important;
                    height: auto !important;
                    background: white !important;
                    color: black !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                .receipt-container {
                    display: block !important;
                    width: 100% !important;
                    height: auto !important;
                    background: white !important;
                    color: black !important;
                    border: 2px solid black !important;
                    box-shadow: none !important;
                    margin: 0 0 20px 0 !important;
                    padding: 0 !important;
                    page-break-inside: avoid !important;
                }
                
                .print-page {
                    page-break-after: always !important;
                    width: 100% !important;
                    height: auto !important;
                    background: white !important;
                    color: black !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                .print-page:last-child {
                    page-break-after: avoid !important;
                }
                
                .print-divider {
                    display: block !important;
                    border-top: 2px dashed black !important;
                    margin: 20px 0 !important;
                    text-align: center !important;
                    background: white !important;
                    page-break-before: always !important;
                }
                
                .print-divider span {
                    background: white !important;
                    padding: 0 10px !important;
                    color: black !important;
                    font-size: 12px !important;
                }
                
                /* Table styling */
                table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                    border: 2px solid black !important;
                    background: white !important;
                    margin: 10px 0 !important;
                }
                
                td, th {
                    border: 1px solid black !important;
                    background: white !important;
                    color: black !important;
                    padding: 8px !important;
                    font-size: 12px !important;
                    line-height: 1.3 !important;
                }
                
                /* Text styling */
                h1, h2, h3, h4, h5, h6 {
                    color: black !important;
                    background: transparent !important;
                    margin: 5px 0 !important;
                    font-weight: bold !important;
                }
                
                h1 { font-size: 18px !important; }
                h2 { font-size: 16px !important; }
                h3 { font-size: 14px !important; }
                
                p, span, div {
                    color: black !important;
                    background: transparent !important;
                    font-size: 12px !important;
                    line-height: 1.3 !important;
                }
                
                /* Background colors for print */
                .bg-gray-100 {
                    background: #f0f0f0 !important;
                }
                
                .bg-gray-200 {
                    background: #e0e0e0 !important;
                }
                
                /* Images */
                img {
                    max-width: 100% !important;
                    height: auto !important;
                    display: block !important;
                }
                
                /* Hide screen-only elements */
                .no-print, .no-screen {
                    display: none !important;
                }
                
                /* Ensure content is visible */
                body {
                    font-size: 12px !important;
                    line-height: 1.4 !important;
                    color: black !important;
                    background: white !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
            }
        </style>
        
        <div id="receiptPrintArea" class="print-content">
            ${receiptTemplate('অফিস কপি')}
            <div class="print-divider border-t-2 border-dashed border-gray-600 my-6 text-center no-screen">
                <span class="bg-white px-4 text-gray-600 text-sm font-medium">✂️ কাটার লাইন</span>
            </div>
            ${receiptTemplate('গ্রাহক কপি')}
        </div>
    `;
    
    document.getElementById('successContent').innerHTML = html;
    
    // Debug: Ensure content is properly set
    const successContent = document.getElementById('successContent');
    const receiptArea = document.getElementById('receiptPrintArea');
    
    console.log('Success content set:', successContent ? 'Yes' : 'No');
    console.log('Receipt area found:', receiptArea ? 'Yes' : 'No');
    console.log('HTML length:', html.length);
    
    document.getElementById('successModal').classList.add('active');
    document.getElementById('feeModal').classList.remove('active');
}

// Close success modal
function closeSuccessModal() {
    document.getElementById('successModal').classList.remove('active');
    location.reload();
}

// Close modals when clicking outside
document.getElementById('feeModal').addEventListener('click', function(e) {
    if (e.target === this) closeFeeModal();
});

document.getElementById('successModal').addEventListener('click', function(e) {
    if (e.target === this) closeSuccessModal();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing students table...');
    console.log('Total students:', students.length);
    filteredStudents = [...students];
    console.log('Filtered students:', filteredStudents.length);
    renderStudentsTable();
});
</script>
@endsection
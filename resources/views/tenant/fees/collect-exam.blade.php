@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-600">পরীক্ষার ফি সংগ্রহ</h1>
                <p class="text-gray-600 mt-1">শিক্ষার্থীদের পরীক্ষার ফি সংগ্রহ করুন</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenant.fees.collect') }}" class="text-purple-600 hover:text-purple-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফি সংগ্রহ পেজে ফিরে যান
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট শিক্ষার্থী</p>
                        <p class="text-3xl font-bold text-gray-900">{{ isset($students) ? $students->count() : 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold">আইডি</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">শ্রেণী</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">শাখা</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">পরীক্ষার ধরন</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">ফি পরিমাণ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">অবস্থা</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody" class="divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Exam Fee Collection Modal -->
<div id="examFeeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-4 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold">পরীক্ষার ফি সংগ্রহ করুন</h2>
                <button onclick="closeExamFeeModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-4">
            <!-- Student Info Card -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-3 mb-4 border border-purple-200">
                <div class="flex items-center gap-4">
                    <!-- Student Photo -->
                    <div id="modalStudentPhoto" class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl overflow-hidden border-4 border-white shadow-lg">
                        <img id="modalStudentPhotoImg" src="" alt="" class="w-full h-full object-cover rounded-full hidden">
                        <span id="modalStudentInitial">ছ</span>
                    </div>
                    <!-- Student Details -->
                    <div class="flex-1">
                        <h3 id="modalStudentName" class="text-lg font-bold text-gray-900 mb-0.5">নাম লোড হচ্ছে</h3>
                        <p id="modalStudentDetails" class="text-xs text-gray-600 mb-1">আইডি: INA-26-0001 | শ্রেণী: প্রথম | শাখা: A</p>
                        <div class="flex gap-4 text-[10px] text-gray-500">
                            <span>রোল: ০১</span>
                            <span>শিক্ষাবর্ষ: ২০২৬</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fee Collection Form -->
            <form id="examFeeForm" onsubmit="submitExamFee(event)">
                <div class="space-y-3">

                    <!-- Exam Type Selection -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">পরীক্ষার ধরন নির্বাচন করুন *</label>
                        <select id="examTypeSelect" name="exam_type" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="updateExamFee()">
                            <option value="">নির্বাচন করুন</option>
                            @foreach($feeStructures as $fee)
                                <option value="{{ $fee->id }}" data-amount="{{ $fee->amount }}">
                                    {{ $fee->fee_name }} (৳ {{ $fee->amount }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fee Calculation Section -->
                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                        <h4 class="text-sm font-bold text-gray-700 mb-1.5">ফি হিসাব</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">পরীক্ষার ফি:</span>
                                <span id="modalExamFee" class="font-bold text-gray-900">৳ ০</span>
                            </div>
                            
                            <div class="border-t border-green-200 pt-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">প্রদত্ত পরিমাণ *</label>
                                <input type="text" inputmode="numeric" id="modalPaidAmount" name="paid_amount" required class="w-full px-4 py-2 border-2 border-green-300 rounded-lg text-lg font-bold focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="০" oninput="calculateExamDue()" onkeyup="calculateExamDue()" onchange="calculateExamDue()">
                            </div>


                            <div class="bg-white rounded-lg p-2 border border-green-300">
                                <h5 class="text-xs font-bold text-gray-700 mb-1">সংক্ষিপ্ত হিসাব</h5>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span>পরীক্ষার ফি:</span>
                                        <span id="summaryExamFee">৳ ০</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>প্রদত্ত পরিমাণ:</span>
                                        <span id="summaryPaidAmount">৳ ০</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-sm border-t border-gray-200 pt-1">
                                        <span>বকেয়া পরিমাণ:</span>
                                        <span id="summaryDueAmount" class="text-red-600">৳ ০</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">পেমেন্ট পদ্ধতি *</label>
                        <select name="payment_method" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="cash">নগদ</option>
                            <option value="bank">ব্যাংক ট্রান্সফার</option>
                            <option value="mobile_banking">মোবাইল ব্যাংকিং</option>
                            <option value="card">কার্ড</option>
                        </select>
                    </div>


                    <!-- Collector Info -->
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <h4 class="text-sm font-bold text-gray-700 mb-1.5">ফি সংগ্রহকারী</h4>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-600">নাম:</span>
                                <span class="font-bold text-gray-900">{{ $currentUser->name ?? 'প্রশাসক' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">সংগ্রহের তারিখ:</span>
                                <span class="font-bold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="mt-2 p-2 bg-white rounded-lg border border-blue-300">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-700">মোট প্রদানযোগ্য পরিমাণ:</span>
                                <span id="totalPayableAmount" class="text-lg font-black text-purple-600">৳ ০</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="modalStudentId" name="student_id">
                    <input type="hidden" name="fee_type" value="exam">
                    <input type="hidden" name="collection_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="collector_name" value="{{ $currentUser->name ?? 'প্রশাসক' }}">
                    <input type="hidden" id="modalVoucherNo" name="voucher_no">
                    <input type="hidden" id="modalTotalAmount" name="total_amount">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="closeExamFeeModal()" class="flex-1 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition-colors">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="flex-1 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-bold transition-all transform hover:scale-[1.02]">
                        পরীক্ষার ফি সংগ্রহ করুন
                    </button>
                </div>
            </form>
        </div>
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
            <h2 id="successTitle" class="text-xl font-bold text-white text-center leading-tight">সফলভাবে সংগৃহীত!</h2>
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
                    প্রিন্ট করুন
                </button>
                <button onclick="collectAnother()" class="flex-1 py-2 bg-gray-900 hover:bg-black text-white rounded-lg font-bold transition-all transform hover:scale-[1.02] text-sm">
                    আরো নিন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JSON data for JS consumption -->
@php
    $schoolSettings = [
        'logo' => $settings->logo ? tenant_asset($settings->logo) : null,
        'school_name' => $settings->school_name_bn ?? $settings->school_name_en ?? 'স্মার্ট পাঠশালা',
        'address' => $settings->address ?? 'প্রতিষ্ঠানের ঠিকানা',
        'established_year' => $settings->established_year ?? '২০২০',
        'principal_name' => $settings->principal_name ?? 'প্রধান শিক্ষক',
        'principal_mobile' => $settings->principal_mobile ?? $settings->mobile ?? $settings->phone ?? '০১৭১২-৩৪৫৬৭৮'
    ];
@endphp
<script type="application/json" id="studentsDataJson">@json($studentsData ?? [])</script>
<script type="application/json" id="feeStructuresJson">@json($feeStructures ?? [])</script>
<script type="application/json" id="schoolSettingsJson">@json($schoolSettings)</script>

<script>
let studentsData = JSON.parse(document.getElementById('studentsDataJson')?.textContent || '[]');
let filteredStudents = [...studentsData];
let feeStructures = JSON.parse(document.getElementById('feeStructuresJson')?.textContent || '[]');

let schoolSettings = JSON.parse(document.getElementById('schoolSettingsJson')?.textContent || '{}');

console.log('=== Exam Fee Collection Page Loaded ===');
console.log('Students loaded:', studentsData.length);
console.log('Fee structures loaded:', feeStructures.length);

// Helper functions for Bengali numbers
function toBengaliNumber(num) {
    if (num === null || num === undefined || num === '') return '০';
    const banglaDigits = {'0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪', '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}

function formatCurrency(amount) {
    const num = parseInt(amount) || 0;
    return toBengaliNumber(num.toLocaleString());
}

function parseCurrencyInput(value) {
    if (value === null || value === undefined) return 0;
    let s = value.toString().trim();
    if (s === '') return 0;
    
    const bnToEn = {'০':'0','১':'1','২':'2','৩':'3','৪':'4','৫':'5','৬':'6','৭':'7','৮':'8','৯':'9'};
    s = s.replace(/[০-৯]/g, ch => bnToEn[ch] ?? ch);
    s = s.replace(/[,\s]/g, '');
    s = s.replace(/[^\d.]/g, '');
    
    const parsed = parseFloat(s);
    return Number.isFinite(parsed) ? Math.floor(parsed) : 0;
}

// Render students table
function renderStudentsTable() {
    const tableBody = document.getElementById('studentsTableBody');
    if (!tableBody) return;
    
    if (studentsData.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p class="text-lg font-medium">কোন শিক্ষার্থী খুঁজে পাওয়া যায়নি</p>
                        <p class="text-sm">দয়া করে প্রথমে শিক্ষার্থী যোগ করুন</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = studentsData.map(student => {
        const statusText = student.status || 'বকেয়া';
        let statusClass = 'bg-red-100 text-red-800';
        if (statusText === 'পরিশোধিত') statusClass = 'bg-green-100 text-green-800';
        if (statusText === 'আংশিক') statusClass = 'bg-yellow-100 text-yellow-800';
        
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">${toBengaliNumber(student.id)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <img src="${student.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name.charAt(0)) + '&size=40&background=A855F7&color=fff'}" 
                             class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover" 
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(student.name.charAt(0))}&size=40&background=A855F7&color=fff'">
                        <div>
                            <div class="text-sm font-medium text-gray-900">${student.name}</div>
                            <div class="text-xs text-gray-500">রোল: ${toBengaliNumber(student.roll)}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${student.class}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">${student.section}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">পরীক্ষার ফি</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">৳ ২৫০ - ১,০০০</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <button onclick="openExamFeeModal('${student.id}')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        ফি সংগ্রহ করুন
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

// Open exam fee modal
function openExamFeeModal(studentId) {
    const sid = String(studentId);
    const student = studentsData.find(s => String(s.id) === sid || String(s.student_id ?? '') === sid);
    if (!student) return;
    
    // Set selected student
    window.selectedStudent = student;
    
    // Populate student info
    const studentNameEl = document.getElementById('modalStudentName');
    const studentDetailsEl = document.getElementById('modalStudentDetails');
    const studentIdEl = document.getElementById('modalStudentId');
    
    if (studentNameEl) studentNameEl.textContent = student.name ?? '';
    if (studentDetailsEl) studentDetailsEl.textContent = `আইডি: ${student.id ?? ''} | শ্রেণী: ${student.class ?? ''} | শাখা: ${student.section ?? ''}`;
    if (studentIdEl) studentIdEl.value = student.id ?? '';
    
    // Set student photo
    const photoImg = document.getElementById('modalStudentPhotoImg');
    const photoInitial = document.getElementById('modalStudentInitial');
    
    if (photoImg && photoInitial) {
        if (student.photo_url && student.photo_url !== '') {
            photoImg.src = student.photo_url;
            photoImg.classList.remove('hidden');
            photoInitial.classList.add('hidden');
        } else {
            photoImg.classList.add('hidden');
            photoInitial.classList.remove('hidden');
            photoInitial.textContent = (student.name ?? '').toString().charAt(0);
        }
    }
    
    // Generate voucher number
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const randomV = Math.floor(100 + Math.random() * 900);
    document.getElementById('modalVoucherNo').value = `EX${year}${month}${day}-${randomV}`;
    
    // Reset form and calculations
    document.getElementById('examFeeForm').reset();
    resetFeeCalculation();
    
    if (studentIdEl) studentIdEl.value = student.id ?? '';
    document.getElementById('modalVoucherNo').value = `EX${year}${month}${day}-${randomV}`;
    
    // Show modal
    document.getElementById('examFeeModal').classList.remove('hidden');
}

// Close exam fee modal
function closeExamFeeModal(clearSelected = true) {
    document.getElementById('examFeeModal').classList.add('hidden');
    if (clearSelected) window.selectedStudent = null;
}

// Update exam fee based on selected type
function updateExamFee() {
    const examTypeSelect = document.getElementById('examTypeSelect');
    if (!examTypeSelect) return;
    
    const selectedOption = examTypeSelect.options[examTypeSelect.selectedIndex];
    if (!selectedOption || !selectedOption.value) {
        resetFeeCalculation();
        return;
    }
    
    const feeAmount = parseInt(selectedOption.getAttribute('data-amount')) || 0;
    
    // Update display values
    document.getElementById('modalExamFee').textContent = `৳ ${formatCurrency(feeAmount)}`;
    document.getElementById('summaryExamFee').textContent = `৳ ${formatCurrency(feeAmount)}`;
    
    // Update hidden total
    document.getElementById('modalTotalAmount').value = feeAmount;
    
    // Update total payable display immediately
    document.getElementById('totalPayableAmount').textContent = `৳ ${formatCurrency(feeAmount)}`;
    
    // Sync calculation
    calculateExamDue();
}

function resetFeeCalculation() {
    document.getElementById('modalExamFee').textContent = `৳ ০`;
    document.getElementById('summaryExamFee').textContent = `৳ ০`;
    document.getElementById('modalTotalAmount').value = 0;
    document.getElementById('modalPaidAmount').value = '';
    calculateExamDue();
}

// Calculate exam due amount
function calculateExamDue() {
    const totalAmount = parseInt(document.getElementById('modalTotalAmount').value) || 0;
    const paidAmount = parseCurrencyInput(document.getElementById('modalPaidAmount').value);
    
    const dueAmount = Math.max(0, totalAmount - paidAmount);
    
    // Update summary section
    document.getElementById('summaryPaidAmount').textContent = `৳ ${formatCurrency(paidAmount)}`;
    document.getElementById('summaryDueAmount').textContent = `৳ ${formatCurrency(dueAmount)}`;
    
    // Update total payable amount (at the bottom)
    document.getElementById('totalPayableAmount').textContent = `৳ ${formatCurrency(totalAmount)}`;
}

// Submit exam fee
async function submitExamFee(event) {
    event.preventDefault();
    
    if (!window.selectedStudent) {
        alert('দয়া করে একটি শিক্ষার্থী নির্বাচন করুন');
        return;
    }
    
    const form = event.target;
    const examType = document.getElementById('examTypeSelect')?.value;
    const paymentMethod = form.payment_method.value;
    const paidAmount = parseCurrencyInput(form.paid_amount.value);
    const totalAmount = parseInt(document.getElementById('modalTotalAmount').value) || 0;
    
    // Validation
    if (!examType) {
        alert('দয়া করে পরীক্ষার ধরন নির্বাচন করুন');
        return;
    }
    
    if (!paymentMethod) {
        alert('দয়া করে পেমেন্ট পদ্ধতি নির্বাচন করুন');
        return;
    }
    
    if (paidAmount <= 0) {
        alert('দয়া করে সঠিক প্রদানের পরিমাণ দিন');
        return;
    }
    
    const data = {
        student_id: window.selectedStudent.id,
        fee_type: 'exam',
        exam_type: examType,
        voucher_no: form.voucher_no.value,
        total_amount: totalAmount,
        paid_amount: paidAmount,
        payment_method: paymentMethod,
        collection_date: form.collection_date.value,
        collector_name: form.collector_name.value,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    };

    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = `
        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        প্রক্রিয়াকরণ...
    `;
    submitButton.disabled = true;

    try {
        const response = await fetch('/fees/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data._token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            
            // Check if response is HTML (redirect to login)
            if (errorText.includes('<!DOCTYPE') || errorText.includes('<html')) {
                throw new Error('দয়া করে আবার লগইন করে চেষ্টা করুন');
            }
            
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Response data:', result);
        
        if (result.success) {
            const studentSnapshot = window.selectedStudent ? { ...window.selectedStudent } : null;
            const paid = parseCurrencyInput(data.paid_amount);
            const total = parseCurrencyInput(data.total_amount);
            const newStatus = paid >= total ? 'পরিশোধিত' : 'আংশিক';
            
            if (studentSnapshot) {
                const sid = String(studentSnapshot.id ?? '');
                const sdb = String(studentSnapshot.db_id ?? '');
                const idx = studentsData.findIndex(s => String(s.id ?? '') === sid || String(s.db_id ?? '') === sdb);
                if (idx !== -1) {
                    studentsData[idx] = { ...studentsData[idx], status: newStatus };
                }
            }
            closeExamFeeModal(false);
            showSuccessModal(data, result.receipt_number, studentSnapshot);
            renderStudentsTable();
        } else {
            alert('ত্রুটি: ' + (result.message || 'ফি সংগ্রহে সমস্যা হয়েছে'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('ফি সংগ্রহে সমস্যা হয়েছে: ' + error.message);
    } finally {
        // Reset button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }
}

function showSuccessModal(data, receiptNumber, student) {
    const studentName = student?.name || 'শিক্ষার্থী';
    const selectedFee = feeStructures.find(f => String(f.id) === String(data.exam_type));
    const examName = selectedFee?.fee_name || 'পরীক্ষা';
    const voucherNo = data.voucher_no;
    
    const title = `${studentName}-এর ${examName} এর ফি সংগৃহীত`;
    
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
                        <p class="text-xs text-gray-600">প্রতিষ্ঠিত: ${schoolSettings.established_year} সাল</p>
                        <p class="text-xs text-gray-600">মোবাইল: ${schoolSettings.principal_mobile}</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mb-2">
                <h3 class="text-base font-black text-gray-800 tracking-tight">পরীক্ষার ফি প্রাপ্তির রশিদ</h3>
                <p class="text-xs text-gray-500 font-bold mt-0.5">ভাউচার নম্বর: ${toBengaliNumber(voucherNo)}</p>
            </div>
            
            <div class="flex items-center gap-3 mb-2 pb-2 border-b border-dashed border-gray-200">
                <div class="relative">
                    <img src="${student?.photo_url || 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'}" alt="Student Photo" class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover shadow-sm" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent((student?.name || 'S').toString().charAt(0))}&size=40&background=A855F7&color=fff'">
                    <div class="absolute -bottom-0.5 -right-0.5 bg-purple-500 w-3 h-3 rounded-full border-2 border-white shadow-sm"></div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">${studentName}</h4>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">আইডি: ${student?.id || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">${student?.class || 'N/A'} শ্রেণী</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs">পরীক্ষার ধরন:</span>
                    <span class="font-bold text-gray-900 text-xs">${examName}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-y border-dashed border-gray-100">
                    <span class="text-gray-500 text-xs">প্রদত্ত পরিমাণ:</span>
                    <span class="text-base font-black text-purple-600">৳ ${toBengaliNumber(data.paid_amount.toLocaleString())}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs">সংগ্রহের তারিখ:</span>
                    <span class="font-bold text-gray-900 text-xs">${new Date(data.collection_date).toLocaleDateString('bn-BD')}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs">সংগ্রহকারী:</span>
                    <span class="font-bold text-gray-900 text-xs">${data.collector_name}</span>
                </div>
            </div>
            <div class="mt-2 flex justify-between items-end">
                <div class="text-center">
                    <div class="w-16 border-t border-gray-300 mt-4"></div>
                    <p class="text-[8px] text-gray-400 mt-0.5">অভিভাবক স্বাক্ষর</p>
                </div>
                <div class="text-center">
                    <div class="w-16 border-t border-gray-300 mt-4"></div>
                    <p class="text-[8px] text-gray-400 mt-0.5">সংগ্রহকারী স্বাক্ষর</p>
                </div>
            </div>
        </div>
    `;

    let html = `
        <div id="receiptPrintArea" class="space-y-4">
            ${receiptTemplate('অফিস কপি')}
            <div class="print-divider border-t-2 border-dashed border-gray-300 my-4 no-screen"></div>
            ${receiptTemplate('ছাত্র কপি')}
        </div>
    `;
    
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successContent').innerHTML = html;
    document.getElementById('successModal').classList.remove('hidden');
}

function printAndClose() {
    window.print();
    setTimeout(() => {
        collectAnother();
    }, 500);
}

// Collect another fee
function collectAnother() {
    document.getElementById('successModal').classList.add('hidden');
    window.selectedStudent = null;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Exam Fee Collection Page Initialized');
    renderStudentsTable();
});

// Add print styles
const printStyles = `
<style>
@media print {
    @page {
        size: A5;
        margin: 8mm;
    }
    
    body > *:not(#successModal) {
        display: none !important;
    }
    
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
    
    #successModal > div {
        all: unset !important;
        display: block !important;
        width: 100% !important;
        height: auto !important;
    }
    
    #successModal .bg-gradient-to-r,
    #successModal .mt-4.flex {
        display: none !important;
    }
    
    #successContent {
        display: block !important;
        visibility: visible !important;
    }
    
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
    
    .receipt-container img[alt="School Logo"] {
        width: 12px !important;
        height: 12px !important;
    }
    
    .receipt-container img[alt="Student Photo"] {
        width: 8px !important;
        height: 8px !important;
    }
}
</style>
`;

// Add print styles to head
document.head.insertAdjacentHTML('beforeend', printStyles);
</script>
@endsection

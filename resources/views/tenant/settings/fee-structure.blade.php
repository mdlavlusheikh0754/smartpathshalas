@extends('layouts.tenant')

@section('content')
<style>
    /* Modal backdrop blur effect */
    #feeStructureModal {
        backdrop-filter: blur(4px);
    }
    
    /* Fee structure card hover effects */
    .fee-structure-card {
        transition: all 0.3s ease;
    }
    
    .fee-structure-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    /* Form input focus effects */
    input:focus, textarea:focus, select:focus {
        ring: 2px;
        ring-color: rgb(59 130 246);
        border-color: rgb(59 130 246);
    }
    
    /* Checkbox styling */
    .fee-delete-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #dc2626;
        cursor: pointer;
    }
    
    .fee-delete-checkbox:checked + * {
        opacity: 0.7;
    }
    
    /* Bengali font support with Unicode */
    .bengali-input {
        font-family: 'Noto Sans Bengali', 'SolaimanLipi', 'Kalpurush', 'Nikosh', 'Siyam Rupali', 'Mukti', Arial, sans-serif;
        font-feature-settings: "liga" 1, "calt" 1, "kern" 1;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        unicode-bidi: bidi-override;
        direction: ltr;
        writing-mode: horizontal-tb;
    }
    
    /* Ensure proper Unicode rendering */
    .bengali-input:focus {
        font-variant-ligatures: common-ligatures;
        font-feature-settings: "liga" 1, "calt" 1, "kern" 1, "clig" 1;
    }
    
    /* Bengali amount display */
    #bengaliAmountDisplay {
        color: #059669;
        font-weight: 500;
        font-family: 'Noto Sans Bengali', 'SolaimanLipi', 'Kalpurush', 'Nikosh', Arial, sans-serif;
        font-feature-settings: "liga" 1, "calt" 1, "kern" 1;
    }
    
    #englishAmountDisplay {
        color: #2563eb;
        font-weight: 500;
        font-family: Arial, sans-serif;
    }
    
    /* Amount input special styling */
    #feeAmount {
        font-size: 18px;
        font-weight: 500;
        text-align: left;
    }
    
    #feeAmount:focus {
        font-size: 18px;
    }
    
    /* Input method editor support */
    .bengali-input {
        ime-mode: active;
        -ms-ime-mode: active;
    }
</style>

<div class="p-8">
    <div class="w-full">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">ফি স্ট্রাকচার ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">ফি এর ধরন এবং পরিমাণ নির্ধারণ করুন</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenant.fees.index') }}" class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 px-4 py-2 border border-green-300 rounded-lg hover:bg-green-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ফি ম্যানেজমেন্ট
                </a>
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2 px-4 py-2 border border-blue-300 rounded-lg hover:bg-blue-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
                <button type="button" onclick="addNewFeeStructure()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2 rounded-xl font-bold hover:shadow-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    নতুন ফি যোগ করুন
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Fee Structure Management -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ফি স্ট্রাকচার সমূহ</h2>
                </div>
                
                <!-- Bulk Delete Controls -->
                <div id="bulkDeleteControls" class="hidden flex items-center gap-3">
                    <span id="selectedCount" class="text-sm text-gray-600">০টি নির্বাচিত</span>
                    <button type="button" onclick="selectAllFees()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        সব নির্বাচন করুন
                    </button>
                    <button type="button" onclick="deselectAllFees()" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                        সব বাতিল করুন
                    </button>
                    <button type="button" onclick="deleteSelectedFees()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        নির্বাচিত মুছুন
                    </button>
                </div>
            </div>

            <!-- Fee Structure Filter -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex flex-wrap gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ক্লাস</label>
                        <select id="feeFilterClass" onchange="filterFeeStructures()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                            <option value="">সব ক্লাস</option>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">ফি টাইপ</label>
                        <select id="feeFilterType" onchange="filterFeeStructures()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                            <option value="">সব ধরনের ফি</option>
                            @foreach(\App\Models\FeeStructure::getFeeTypes() as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">সেশন</label>
                        <select id="feeFilterSession" onchange="filterFeeStructures()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                            <option value="">সব সেশন</option>
                            @php
                                try {
                                    $sessions = \App\Models\AcademicSession::getActiveSessions();
                                } catch (\Exception $e) {
                                    $sessions = collect();
                                }
                            @endphp
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->session_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="resetFeeFilters()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                            রিসেট
                        </button>
                    </div>
                </div>
            </div>

            <!-- Fee Structures Grid -->
            <div id="feeStructuresGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @php
                    try {
                        $feeStructures = \App\Models\FeeStructure::where('is_active', true)
                                                              ->orderBy('class_name')
                                                              ->orderBy('fee_type')
                                                              ->get();
                    } catch (\Exception $e) {
                        $feeStructures = collect();
                    }
                @endphp
                
                @forelse($feeStructures as $fee)
                <div class="fee-structure-card bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300" data-class="{{ $fee->class_name }}" data-type="{{ $fee->fee_type }}" data-session="{{ $fee->academic_session_id }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $fee->fee_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $fee->class_name_bengali }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="fee-delete-checkbox" data-fee-id="{{ $fee->id }}" onchange="updateDeleteSelection()">
                            <button type="button" onclick="editFeeStructure({{ $fee->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="deleteFeeStructure({{ $fee->id }})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">পরিমাণ:</span>
                            <span class="text-xl font-bold text-green-600">{{ $fee->formatted_amount }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">ফ্রিকোয়েন্সি:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ \App\Models\FeeStructure::getFrequencyOptions()[$fee->frequency] ?? $fee->frequency }}
                            </span>
                        </div>
                        
                        @if($fee->academic_session_id)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">সেশন:</span>
                            <span class="text-sm font-medium text-blue-600">
                                {{ $fee->academicSession->session_name ?? 'N/A' }}
                            </span>
                        </div>
                        @endif
                        
                        <div class="flex items-center gap-2 pt-2">
                            @if($fee->is_mandatory)
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold">বাধ্যতামূলক</span>
                            @else
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-bold">ঐচ্ছিক</span>
                            @endif
                            
                            @if($fee->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">সক্রিয়</span>
                            @else
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-bold">নিষ্ক্রিয়</span>
                            @endif
                        </div>
                        
                        @if($fee->description)
                        <div class="pt-2 border-t border-gray-200">
                            <p class="text-sm text-gray-600">{{ $fee->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300">
                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">কোন ফি স্ট্রাকচার নেই</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">আপনার স্কুলের জন্য প্রথম ফি স্ট্রাকচার তৈরি করুন। বিভিন্ন ক্লাস এবং ফি টাইপের জন্য আলাদা আলাদা ফি নির্ধারণ করতে পারবেন।</p>
                    <button type="button" onclick="addNewFeeStructure()" class="bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2 mx-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        প্রথম ফি স্ট্রাকচার যোগ করুন
                    </button>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
// Fee Structure Management Functions
window.addNewFeeStructure = function() {
    showFeeStructureModal();
}

window.editFeeStructure = function(feeId) {
    // Fetch fee structure data and populate modal
    fetch(`/settings/school/fee-structures/${feeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFeeStructureModal(data.feeStructure);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ফি স্ট্রাকচার লোড করতে সমস্যা হয়েছে।');
        });
}

// Bengali Unicode input support
function initializeBengaliInput() {
    // Add Bengali input support to all Bengali input fields
    document.querySelectorAll('.bengali-input').forEach(input => {
        // Set proper attributes for Bengali input
        input.setAttribute('inputmode', 'text');
        input.setAttribute('autocorrect', 'off');
        input.setAttribute('autocapitalize', 'off');
        input.setAttribute('spellcheck', 'false');
        
        // Handle composition events for proper Unicode rendering
        input.addEventListener('compositionstart', function(e) {
            console.log('Bengali composition started');
        });
        
        input.addEventListener('compositionupdate', function(e) {
            console.log('Bengali composition updating:', e.data);
        });
        
        input.addEventListener('compositionend', function(e) {
            console.log('Bengali composition ended:', e.data);
            // Ensure proper Unicode normalization
            if (e.target.value) {
                e.target.value = e.target.value.normalize('NFC');
            }
        });
        
        // Handle input events for Unicode validation
        input.addEventListener('input', function(e) {
            // Normalize Unicode characters
            if (e.target.value) {
                const normalizedValue = e.target.value.normalize('NFC');
                if (normalizedValue !== e.target.value) {
                    e.target.value = normalizedValue;
                }
            }
            
            // Validate Bengali Unicode characters
            validateBengaliInput(e.target);
        });
        
        // Handle paste events
        input.addEventListener('paste', function(e) {
            setTimeout(() => {
                if (e.target.value) {
                    e.target.value = e.target.value.normalize('NFC');
                    validateBengaliInput(e.target);
                }
            }, 10);
        });
    });
}

function validateBengaliInput(input) {
    const value = input.value;
    if (!value) return;
    
    // Bengali Unicode range: U+0980–U+09FF
    const bengaliRegex = /[\u0980-\u09FF]/;
    const englishRegex = /[a-zA-Z]/;
    
    // Check if input contains Bengali characters
    const hasBengali = bengaliRegex.test(value);
    const hasEnglish = englishRegex.test(value);
    
    // Visual feedback for input type
    if (hasBengali && !hasEnglish) {
        input.style.borderColor = '#059669'; // Green for Bengali
        input.style.backgroundColor = '#f0fdf4'; // Light green background
    } else if (hasEnglish && !hasBengali) {
        input.style.borderColor = '#3b82f6'; // Blue for English
        input.style.backgroundColor = '#eff6ff'; // Light blue background
    } else if (hasBengali && hasEnglish) {
        input.style.borderColor = '#f59e0b'; // Orange for mixed
        input.style.backgroundColor = '#fffbeb'; // Light orange background
    } else {
        input.style.borderColor = '#d1d5db'; // Default gray
        input.style.backgroundColor = '#ffffff'; // White background
    }
}

// Enhanced Bengali number conversion with proper Unicode
function convertToBengaliNumbers(num) {
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return num.toString().replace(/\d/g, function(digit) {
        return bengaliDigits[parseInt(digit)];
    }).normalize('NFC');
}

function convertToEnglishNumbers(bengaliNum) {
    const englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    
    let result = bengaliNum.toString();
    bengaliDigits.forEach((bengali, index) => {
        result = result.replace(new RegExp(bengali, 'g'), englishDigits[index]);
    });
    return result.normalize('NFC');
}

window.handleAmountInput = function(input) {
    const value = input.value;
    const bengaliDisplay = document.getElementById('bengaliAmountDisplay');
    const englishDisplay = document.getElementById('englishAmountDisplay');
    
    if (!value) {
        bengaliDisplay.classList.add('hidden');
        englishDisplay.classList.add('hidden');
        return;
    }
    
    // Convert Bengali numbers to English for processing
    const englishValue = convertToEnglishNumbers(value);
    
    // Check if it's a valid number
    const numericValue = parseFloat(englishValue);
    
    if (!isNaN(numericValue) && numericValue >= 0) {
        // Show Bengali representation
        const bengaliAmount = convertToBengaliNumbers(englishValue);
        bengaliDisplay.textContent = `বাংলায়: ${bengaliAmount} টাকা`;
        bengaliDisplay.classList.remove('hidden');
        
        // Show English representation if input was in Bengali
        if (value !== englishValue) {
            englishDisplay.textContent = `ইংরেজিতে: ${englishValue}`;
            englishDisplay.classList.remove('hidden');
        } else {
            englishDisplay.classList.add('hidden');
        }
        
        // Set border color to green for valid input
        input.style.borderColor = '#10b981';
        input.style.backgroundColor = '#f0fdf4';
        
        // Store the English value for form submission
        input.setAttribute('data-english-value', englishValue);
        
    } else {
        bengaliDisplay.classList.add('hidden');
        englishDisplay.classList.add('hidden');
        input.style.borderColor = '#ef4444';
        input.style.backgroundColor = '#fef2f2';
        input.removeAttribute('data-english-value');
    }
}

window.showBengaliAmount = function(input) {
    const display = document.getElementById('bengaliAmountDisplay');
    if (input.value && !isNaN(input.value) && parseFloat(input.value) > 0) {
        const bengaliAmount = convertToBengaliNumbers(input.value);
        display.textContent = `বাংলায়: ${bengaliAmount} টাকা`;
        display.classList.remove('hidden');
    } else {
        display.classList.add('hidden');
    }
}

window.updateDeleteSelection = function() {
    const checkboxes = document.querySelectorAll('.fee-delete-checkbox');
    const selectedCheckboxes = document.querySelectorAll('.fee-delete-checkbox:checked');
    const bulkControls = document.getElementById('bulkDeleteControls');
    const selectedCount = document.getElementById('selectedCount');
    
    const count = selectedCheckboxes.length;
    
    if (count > 0) {
        bulkControls.classList.remove('hidden');
        selectedCount.textContent = `${count}টি নির্বাচিত`;
    } else {
        bulkControls.classList.add('hidden');
    }
}

window.selectAllFees = function() {
    const checkboxes = document.querySelectorAll('.fee-delete-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateDeleteSelection();
}

window.deselectAllFees = function() {
    const checkboxes = document.querySelectorAll('.fee-delete-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateDeleteSelection();
}

window.deleteSelectedFees = function() {
    const selectedCheckboxes = document.querySelectorAll('.fee-delete-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.getAttribute('data-fee-id'));
    
    if (selectedIds.length === 0) {
        alert('কোন ফি স্ট্রাকচার নির্বাচন করা হয়নি।');
        return;
    }
    
    const message = `আপনি কি নিশ্চিত যে ${selectedIds.length}টি ফি স্ট্রাকচার মুছে ফেলতে চান?`;
    
    showDeleteConfirmation('নির্বাচিত ফি স্ট্রাকচার', message, function() {
        // Delete each selected fee structure
        let deletedCount = 0;
        let totalCount = selectedIds.length;
        
        selectedIds.forEach(feeId => {
            fetch(`/settings/school/fee-structures/${feeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                deletedCount++;
                if (deletedCount === totalCount) {
                    // All deletions completed
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error deleting fee structure:', error);
                deletedCount++;
                if (deletedCount === totalCount) {
                    location.reload();
                }
            });
        });
    });
}

window.deleteFeeStructure = function(feeId) {
    showDeleteConfirmation('ফি স্ট্রাকচার', 'আপনি কি নিশ্চিত যে এই ফি স্ট্রাকচারটি মুছে ফেলতে চান?', function() {
        fetch(`/settings/school/fee-structures/${feeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'ফি স্ট্রাকচার মুছতে সমস্যা হয়েছে।');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ফি স্ট্রাকচার মুছতে সমস্যা হয়েছে।');
        });
    });
}

window.filterFeeStructures = function() {
    const classFilter = document.getElementById('feeFilterClass').value;
    const typeFilter = document.getElementById('feeFilterType').value;
    const sessionFilter = document.getElementById('feeFilterSession').value;
    
    const cards = document.querySelectorAll('.fee-structure-card');
    
    cards.forEach(card => {
        const cardClass = card.getAttribute('data-class');
        const cardType = card.getAttribute('data-type');
        const cardSession = card.getAttribute('data-session');
        
        const matchClass = !classFilter || cardClass === classFilter;
        const matchType = !typeFilter || cardType === typeFilter;
        const matchSession = !sessionFilter || cardSession === sessionFilter;
        
        if (matchClass && matchType && matchSession) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

window.resetFeeFilters = function() {
    document.getElementById('feeFilterClass').value = '';
    document.getElementById('feeFilterType').value = '';
    document.getElementById('feeFilterSession').value = '';
    
    const cards = document.querySelectorAll('.fee-structure-card');
    cards.forEach(card => {
        card.style.display = 'block';
    });
}

function showFeeStructureModal(feeStructure = null) {
    const isEdit = feeStructure !== null;
    const modalTitle = isEdit ? 'ফি স্ট্রাকচার সম্পাদনা করুন' : 'নতুন ফি স্ট্রাকচার যোগ করুন';
    const submitText = isEdit ? 'আপডেট করুন' : 'সংরক্ষণ করুন';
    
    // Get classes from the page data
    const classesData = window.schoolClasses || [];
    let classOptions = '<option value="">নির্বাচন করুন</option>';
    
    classesData.forEach(classItem => {
        const selected = isEdit && feeStructure.class_name === classItem.name ? 'selected' : '';
        classOptions += `<option value="${classItem.name}" ${selected}>${classItem.name} শ্রেণী - ${classItem.section}</option>`;
    });
    
    const modalHtml = `
        <div id="feeStructureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">${modalTitle}</h3>
                    <button type="button" onclick="closeFeeStructureModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="feeStructureForm" onsubmit="submitFeeStructure(event)">
                    <input type="hidden" id="feeStructureId" value="${isEdit ? feeStructure.id : ''}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ফি টাইপ *</label>
                            <select id="feeType" required onchange="toggleFeeTypeDropdowns()" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="">নির্বাচন করুন</option>
                                <option value="admission" ${isEdit && feeStructure.fee_type === 'admission' ? 'selected' : ''}>ভর্তি ফি</option>
                                <option value="monthly" ${isEdit && feeStructure.fee_type === 'monthly' ? 'selected' : ''}>মাসিক বেতন</option>
                                <option value="exam" ${isEdit && feeStructure.fee_type === 'exam' ? 'selected' : ''}>পরীক্ষার ফি</option>
                                <option value="annual" ${isEdit && feeStructure.fee_type === 'annual' ? 'selected' : ''}>বার্ষিক ফি</option>
                                <option value="transport" ${isEdit && feeStructure.fee_type === 'transport' ? 'selected' : ''}>পরিবহন ফি</option>
                                <option value="library" ${isEdit && feeStructure.fee_type === 'library' ? 'selected' : ''}>লাইব্রেরি ফি</option>
                                <option value="sports" ${isEdit && feeStructure.fee_type === 'sports' ? 'selected' : ''}>খেলাধুলা ফি</option>
                                <option value="development" ${isEdit && feeStructure.fee_type === 'development' ? 'selected' : ''}>উন্নয়ন ফি</option>
                                <option value="computer" ${isEdit && feeStructure.fee_type === 'computer' ? 'selected' : ''}>কম্পিউটার ফি</option>
                                <option value="science_lab" ${isEdit && feeStructure.fee_type === 'science_lab' ? 'selected' : ''}>বিজ্ঞানাগার ফি</option>
                                <option value="other" ${isEdit && feeStructure.fee_type === 'other' ? 'selected' : ''}>অন্যান্য ফি</option>
                            </select>
                        </div>

                        <!-- Exam Selection Dropdown (for exam fee type) -->
                        <div id="examSelectionDiv" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষা নির্বাচন করুন</label>
                            <select id="examId" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="">পরীক্ষা নির্বাচন করুন</option>
                            </select>
                        </div>

                        <!-- Month Selection Dropdown (for monthly fee type) -->
                        <div id="monthSelectionDiv" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">মাস নির্বাচন করুন</label>
                            <select id="month" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="">মাস নির্বাচন করুন</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">ফি নাম *</label>
                            <input type="text" id="feeName" value="${isEdit ? feeStructure.fee_name : ''}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl bengali-input" placeholder="যেমন: ভর্তি ফি, মাসিক বেতন" lang="bn" dir="ltr" autocomplete="off">
                            <div class="text-xs text-gray-500 mt-1">বাংলায় টাইপ করুন (Unicode সাপোর্ট)</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস *</label>
                            <select id="className" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                ${classOptions}
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">পরিমাণ (টাকা) *</label>
                            <div class="relative">
                                <input type="text" id="feeAmount" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 bengali-input" placeholder="যেমন: ১০০০ বা 1000" inputmode="numeric" oninput="handleAmountInput(this)" lang="bn">
                                <div id="bengaliAmountDisplay" class="text-sm text-gray-500 mt-1 hidden"></div>
                                <div id="englishAmountDisplay" class="text-xs text-blue-600 mt-1 hidden"></div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ফ্রিকোয়েন্সি</label>
                            <select id="feeFrequency" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="one_time" ${isEdit && feeStructure.frequency === 'one_time' ? 'selected' : ''}>একবার</option>
                                <option value="monthly" ${isEdit && feeStructure.frequency === 'monthly' ? 'selected' : ''}>মাসিক</option>
                                <option value="quarterly" ${isEdit && feeStructure.frequency === 'quarterly' ? 'selected' : ''}>ত্রৈমাসিক</option>
                                <option value="half_yearly" ${isEdit && feeStructure.frequency === 'half_yearly' ? 'selected' : ''}>অর্ধবার্ষিক</option>
                                <option value="yearly" ${isEdit && feeStructure.frequency === 'yearly' ? 'selected' : ''}>বার্ষিক</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">একাডেমিক সেশন</label>
                            <select id="academicSessionId" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="">সব সেশন</option>
                                <!-- Sessions will be populated by JavaScript -->
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিবরণ</label>
                            <textarea id="feeDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl bengali-input" placeholder="এই ফি সম্পর্কে অতিরিক্ত তথ্য লিখুন..." lang="bn" dir="ltr" autocomplete="off">${isEdit ? (feeStructure.description || '') : ''}</textarea>
                            <div class="text-xs text-gray-500 mt-1">বাংলায় বিস্তারিত লিখুন (Unicode সাপোর্ট)</div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="isMandatory" ${isEdit && feeStructure.is_mandatory ? 'checked' : ''} class="rounded">
                                    <span class="text-sm font-medium text-gray-700">বাধ্যতামূলক ফি</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="isActive" ${isEdit ? (feeStructure.is_active ? 'checked' : '') : 'checked'} class="rounded">
                                    <span class="text-sm font-medium text-gray-700">সক্রিয়</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="button" onclick="closeFeeStructureModal()" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                            বাতিল
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-yellow-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">
                            ${submitText}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Initialize Bengali input support
    setTimeout(() => {
        initializeBengaliInput();
    }, 50);
    
    // Ensure the amount input is properly accessible
    setTimeout(() => {
        const amountInput = document.getElementById('feeAmount');
        if (amountInput) {
            // Set the value after modal creation
            if (isEdit && feeStructure && feeStructure.amount) {
                amountInput.value = feeStructure.amount;
                handleAmountInput(amountInput); // Show Bengali amount for existing values
            }
            
            amountInput.focus();
            amountInput.addEventListener('input', function(e) {
                console.log('Amount input value:', e.target.value);
                handleAmountInput(e.target);
            });
            
            // Handle keydown for Bengali numbers
            amountInput.addEventListener('keydown', function(e) {
                console.log('Key pressed in amount input:', e.key);
                
                // Allow Bengali numbers, English numbers, decimal point, backspace, delete, arrow keys
                const allowedKeys = [
                    'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                    'Tab', 'Enter', 'Escape', '.', ','
                ];
                
                const bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                
                if (allowedKeys.includes(e.key) || 
                    bengaliNumbers.includes(e.key) || 
                    englishNumbers.includes(e.key) ||
                    (e.ctrlKey || e.metaKey)) {
                    // Allow the key
                    return true;
                }
                
                // Block other keys
                e.preventDefault();
                return false;
            });
            
            // Handle paste events
            amountInput.addEventListener('paste', function(e) {
                setTimeout(() => {
                    handleAmountInput(e.target);
                }, 10);
            });
            
            console.log('Amount input initialized:', amountInput);
        } else {
            console.error('Amount input not found');
        }
    }, 100);
    
    // Populate academic sessions
    populateAcademicSessions(isEdit ? feeStructure.academic_session_id : null);
}

window.closeFeeStructureModal = function() {
    const modal = document.getElementById('feeStructureModal');
    if (modal) {
        modal.remove();
    }
}

function populateAcademicSessions(selectedSessionId = null) {
    const sessionSelect = document.getElementById('academicSessionId');
    
    fetch('/settings/school/academic-sessions')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.sessions) {
                let options = '<option value="">সব সেশন</option>';
                data.sessions.forEach(session => {
                    const selected = selectedSessionId && session.id === selectedSessionId ? 'selected' : '';
                    options += `<option value="${session.id}" ${selected}>${session.session_name}</option>`;
                });
                sessionSelect.innerHTML = options;
            }
        })
        .catch(error => {
            console.error('Error loading academic sessions:', error);
        });
}

function toggleFeeTypeDropdowns() {
    const feeType = document.getElementById('feeType').value;
    const examSelectionDiv = document.getElementById('examSelectionDiv');
    const monthSelectionDiv = document.getElementById('monthSelectionDiv');
    
    // Hide both first
    examSelectionDiv.classList.add('hidden');
    monthSelectionDiv.classList.add('hidden');
    
    // Show exam dropdown for exam fee type
    if (feeType === 'exam') {
        examSelectionDiv.classList.remove('hidden');
        populateExams();
    }
    
    // Show month dropdown for monthly fee type
    if (feeType === 'monthly') {
        monthSelectionDiv.classList.remove('hidden');
    }
}

function populateExams() {
    const examSelect = document.getElementById('examId');
    
    fetch('/exams/api/list')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.exams) {
                let options = '<option value="">পরীক্ষা নির্বাচন করুন</option>';
                data.exams.forEach(exam => {
                    options += `<option value="${exam.id}">${exam.name}</option>`;
                });
                examSelect.innerHTML = options;
            }
        })
        .catch(error => {
            console.error('Error loading exams:', error);
        });
}

window.submitFeeStructure = function(event) {
    event.preventDefault();
    
    const feeStructureId = document.getElementById('feeStructureId').value;
    const isEdit = feeStructureId !== '';
    
    const formData = {
        fee_type: document.getElementById('feeType').value,
        fee_name: document.getElementById('feeName').value,
        class_name: document.getElementById('className').value,
        amount: parseFloat(document.getElementById('feeAmount').getAttribute('data-english-value') || document.getElementById('feeAmount').value) || 0,
        frequency: document.getElementById('feeFrequency').value,
        academic_session_id: document.getElementById('academicSessionId').value || null,
        description: document.getElementById('feeDescription').value,
        is_mandatory: document.getElementById('isMandatory').checked,
        is_active: document.getElementById('isActive').checked
    };
    
    const url = isEdit ? `/settings/school/fee-structures/${feeStructureId}` : '/settings/school/fee-structures';
    const method = isEdit ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeFeeStructureModal();
            location.reload();
        } else {
            alert(data.message || 'ফি স্ট্রাকচার সংরক্ষণ করতে সমস্যা হয়েছে।');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('ফি স্ট্রাকচার সংরক্ষণ করতে সমস্যা হয়েছে।');
    });
}
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">নিশ্চিতকরণ</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2" id="deleteTitle">আইটেম মুছে ফেলুন</h4>
                <p class="text-gray-600 mb-6" id="deleteMessage">আপনি কি নিশ্চিত যে এটি মুছে ফেলতে চান?</p>
                
                <div class="flex items-center justify-center gap-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                        বাতিল
                    </button>
                    <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-medium">
                        মুছে ফেলুন
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deleteCallback = null;

function showDeleteConfirmation(title, message, callback) {
    document.getElementById('deleteTitle').textContent = title + ' মুছে ফেলুন';
    document.getElementById('deleteMessage').textContent = message;
    deleteCallback = callback;
    
    document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    deleteCallback = null;
}

function confirmDelete() {
    if (deleteCallback) {
        deleteCallback();
    }
    closeDeleteModal();
}

// Close modal when clicking outside
document.getElementById('deleteConfirmationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteConfirmationModal').classList.contains('hidden')) {
        closeDeleteModal();
    }
});
</script>
@endsection

@push('scripts')
<script>
// Pass classes data to JavaScript
window.schoolClasses = @json($classes ?? collect());
console.log('School classes loaded:', window.schoolClasses);

// Bengali number conversion
function convertToBengaliNumbers(num) {
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return num.toString().replace(/\d/g, function(digit) {
        return bengaliDigits[parseInt(digit)];
    });
}

function convertToEnglishNumbers(bengaliNum) {
    const englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    
    let result = bengaliNum.toString();
    bengaliDigits.forEach((bengali, index) => {
        result = result.replace(new RegExp(bengali, 'g'), englishDigits[index]);
    });
    return result;
}

// Ensure all input fields work properly
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bengali input support on page load
    initializeBengaliInput();
    
    // Add event listener for modal inputs
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'feeAmount') {
            console.log('Amount input clicked');
            e.target.focus();
        }
    });
    
    // Fix for number inputs with Bengali support
    document.addEventListener('input', function(e) {
        if (e.target && e.target.type === 'number') {
            console.log('Number input changed:', e.target.id, e.target.value);
            
            // Convert Bengali numbers to English for processing
            if (e.target.id === 'feeAmount') {
                let value = e.target.value;
                let englishValue = convertToEnglishNumbers(value);
                
                // Update the actual input value with English numbers
                if (value !== englishValue) {
                    e.target.value = englishValue;
                }
            }
        }
    });
    
    // Add Bengali number display support
    document.addEventListener('blur', function(e) {
        if (e.target && e.target.id === 'feeAmount' && e.target.value) {
            // Show Bengali numbers in a tooltip or helper text
            const bengaliAmount = convertToBengaliNumbers(e.target.value);
            console.log('Bengali amount:', bengaliAmount);
        }
    });
});
</script>
@endpush

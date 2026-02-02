@extends('layouts.tenant')

@section('content')
@php
// Helper function to convert English numbers to Bengali
function convertToBengaliNumbers($text) {
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($englishNumbers, $bengaliNumbers, $text);
}

// Helper function to convert common English terms to Bengali
function convertToBengali($text) {
    if (!$text || $text === 'N/A') return 'প্রযোজ্য নয়';
    
    $translations = [
        // Gender
        'male' => 'পুরুষ',
        'female' => 'মহিলা',
        'other' => 'অন্যান্য',
        
        // Status
        'active' => 'সক্রিয়',
        'inactive' => 'নিষ্ক্রিয়',
        'suspended' => 'স্থগিত',
        
        // Blood Groups
        'A+' => 'এ+',
        'A-' => 'এ-',
        'B+' => 'বি+',
        'B-' => 'বি-',
        'AB+' => 'এবি+',
        'AB-' => 'এবি-',
        'O+' => 'ও+',
        'O-' => 'ও-',
        
        // Religion
        'islam' => 'ইসলাম',
        'Islam' => 'ইসলাম',
        'hinduism' => 'হিন্দু',
        'Hinduism' => 'হিন্দু',
        'christianity' => 'খ্রিস্টান',
        'Christianity' => 'খ্রিস্টান',
        'buddhism' => 'বৌদ্ধ',
        'Buddhism' => 'বৌদ্ধ',
        'muslim' => 'মুসলিম',
        'Muslim' => 'মুসলিম',
        'hindu' => 'হিন্দু',
        'Hindu' => 'হিন্দু',
        'christian' => 'খ্রিস্টান',
        'Christian' => 'খ্রিস্টান',
        'buddhist' => 'বৌদ্ধ',
        'Buddhist' => 'বৌদ্ধ',
        
        // Yes/No
        'yes' => 'হ্যাঁ',
        'Yes' => 'হ্যাঁ',
        'YES' => 'হ্যাঁ',
        'no' => 'না',
        'No' => 'না',
        'NO' => 'না',
        
        // Nationality
        'bangladeshi' => 'বাংলাদেশী',
        'Bangladeshi' => 'বাংলাদেশী',
        'bangladesh' => 'বাংলাদেশ',
        'Bangladesh' => 'বাংলাদেশ',
        
        // Common terms
        'morning' => 'সকাল',
        'day' => 'দিন',
        'evening' => 'সন্ধ্যা',
        'night' => 'রাত',
        'Morning' => 'সকাল',
        'Day' => 'দিন',
        'Evening' => 'সন্ধ্যা',
        'Night' => 'রাত'
    ];
    
    $converted = $translations[trim($text)] ?? $text;
    return convertToBengaliNumbers($converted);
}
@endphp
<div class="p-4 lg:p-8">
    <div class="max-w-none mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between no-print">
            <a href="{{ route('tenant.students.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
            <div class="flex gap-3">
                <a href="{{ route('tenant.students.edit', $student->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    সম্পাদনা
                </a>
                <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    প্রিন্ট
                </button>
                <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    মুছে ফেলুন
                </button>
            </div>
        </div>

        <!-- Delete Form (Hidden) -->
        <form id="deleteForm" action="{{ route('tenant.students.destroy', $student->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <!-- Student Profile Card -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-8 mb-6 text-white">
            <div class="flex items-start gap-6">
                <div class="w-32 h-32 bg-white rounded-xl overflow-hidden flex-shrink-0">
                    <img src="{{ $student->photo_url }}" alt="{{ $student->name_bn }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">{{ $student->name_bn }}</h1>
                    <p class="text-blue-100 mb-4">{{ $student->name_en }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <p class="text-blue-200 text-sm">শিক্ষার্থী ID</p>
                            <p class="font-semibold">{{ $student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm">রেজিস্ট্রেশন নম্বর</p>
                            <p class="font-semibold">{{ $student->registration_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm">রোল নম্বর</p>
                            <p class="font-semibold">{{ convertToBengali($student->roll) }}</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm">ক্লাস</p>
                            <p class="font-semibold">{{ $student->class }} @if($student->section) - {{ $student->section }} @endif</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm">স্ট্যাটাস</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($student->status == 'active') bg-green-500 text-white
                                @elseif($student->status == 'inactive') bg-gray-500 text-white
                                @else bg-yellow-500 text-white
                                @endif">
                                {{ convertToBengali($student->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <!-- Left Column - Main Content (Compact) -->
            <div class="xl:col-span-3 space-y-4">
                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-md p-4">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b">
                        <div class="bg-blue-100 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">ব্যক্তিগত তথ্য</h2>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <p class="text-xs text-gray-500">জন্ম তারিখ</p>
                            <p class="font-medium text-sm">{{ convertToBengaliNumbers($student->date_of_birth) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">লিঙ্গ</p>
                            <p class="font-medium text-sm">{{ convertToBengali($student->gender) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">রক্তের গ্রুপ</p>
                            <p class="font-medium text-sm">{{ convertToBengali($student->blood_group) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">ধর্ম</p>
                            <p class="font-medium text-sm">{{ convertToBengali($student->religion) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">জাতীয়তা</p>
                            <p class="font-medium text-sm">{{ convertToBengali($student->nationality) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">জন্ম নিবন্ধন নম্বর</p>
                            <p class="font-medium text-sm">{{ convertToBengali($student->birth_certificate_no) }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500">মোবাইল নম্বর</p>
                            <p class="font-medium text-sm">{{ convertToBengaliNumbers($student->phone) ?: 'প্রযোজ্য নয়' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-xl shadow-md p-4">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b">
                        <div class="bg-green-100 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">ঠিকানা</h2>
                    </div>
                    <div class="space-y-4">
                        <!-- Present Address -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex items-start gap-2">
                                <div class="flex-shrink-0 p-1.5 bg-blue-100 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-blue-900 mb-1">বর্তমান ঠিকানা</h3>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ $student->present_address }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Permanent Address -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-3 border border-green-200">
                            <div class="flex items-start gap-2">
                                <div class="flex-shrink-0 p-1.5 bg-green-100 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-green-900 mb-1">স্থায়ী ঠিকানা</h3>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ $student->permanent_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white rounded-xl shadow-md p-4">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b">
                        <div class="bg-purple-100 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">একাডেমিক তথ্য</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-3 border border-indigo-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 0v1m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <p class="text-xs font-semibold text-indigo-800">ভর্তির তারিখ</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengaliNumbers($student->admission_date) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-3 border border-purple-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs font-semibold text-purple-800">শিক্ষাবর্ষ</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengaliNumbers($student->academic_year) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-3 border border-green-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-xs font-semibold text-green-800">শিফট</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengali($student->shift) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-3 border border-yellow-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <p class="text-xs font-semibold text-yellow-800">পূর্ববর্তী স্কুল</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengali($student->previous_school) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <p class="text-xs font-semibold text-blue-800">ট্রান্সপোর্ট</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengali($student->transport) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg p-3 border border-pink-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <p class="text-xs font-semibold text-pink-800">হোস্টেল</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengali($student->hostel) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="bg-white rounded-xl shadow-md p-4">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b">
                        <div class="bg-orange-100 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">অভিভাবকের তথ্য</h2>
                    </div>
                    <div class="space-y-3">
                        <!-- Father Information -->
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex-shrink-0 p-1 bg-blue-100 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-blue-900">পিতার তথ্য</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">নাম</p>
                                    <p class="font-medium text-sm text-gray-900">{{ $student->father_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">মোবাইল</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengaliNumbers($student->father_mobile) ?: 'প্রযোজ্য নয়' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">পেশা</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengali($student->father_occupation) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">এনআইডি</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengaliNumbers($student->father_nid) ?: 'প্রযোজ্য নয়' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mother Information -->
                        <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg p-3 border border-pink-200">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex-shrink-0 p-1 bg-pink-100 rounded-lg">
                                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-pink-900">মাতার তথ্য</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-pink-600 font-medium">নাম</p>
                                    <p class="font-medium text-sm text-gray-900">{{ $student->mother_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-pink-600 font-medium">মোবাইল</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengaliNumbers($student->mother_mobile) ?: 'প্রযোজ্য নয়' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-pink-600 font-medium">পেশা</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengali($student->mother_occupation) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-pink-600 font-medium">এনআইডি</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengaliNumbers($student->mother_nid) ?: 'প্রযোজ্য নয়' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information (if exists) -->
                        @if($student->guardian_name)
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-3 border border-purple-200">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex-shrink-0 p-1 bg-purple-100 rounded-lg">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-purple-900">অভিভাবকের তথ্য</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">নাম</p>
                                    <p class="font-medium text-sm text-gray-900">{{ $student->guardian_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">মোবাইল</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengaliNumbers($student->guardian_mobile) ?: 'প্রযোজ্য নয়' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">সম্পর্ক</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengali($student->guardian_relation) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">ঠিকানা</p>
                                    <p class="font-medium text-sm text-gray-900">{{ convertToBengali($student->guardian_address) }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (Larger) -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">দ্রুত তথ্য</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <span class="text-sm text-gray-700 font-medium">উপস্থিতি</span>
                            <span class="text-base font-bold text-blue-600">৯৫%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                            <span class="text-sm text-gray-700 font-medium">পরীক্ষার ফলাফল</span>
                            <span class="text-base font-bold text-green-600">এ+</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <span class="text-sm text-gray-700 font-medium">বকেয়া ফি</span>
                            <span class="text-base font-bold text-purple-600">০ টাকা</span>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-3 border-b">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">ডকুমেন্ট</h2>
                    </div>
                    
                    <div class="space-y-4">
                        @php
                            $documents = [
                                'birth_certificate_file' => ['name' => 'জন্ম নিবন্ধন সনদ', 'icon' => 'text-red-500', 'bg' => 'bg-red-50', 'border' => 'border-red-200'],
                                'vaccination_card' => ['name' => 'টিকা কার্ড', 'icon' => 'text-blue-500', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200'],
                                'father_nid_file' => ['name' => 'পিতার ভোটার আইডি', 'icon' => 'text-green-500', 'bg' => 'bg-green-50', 'border' => 'border-green-200'],
                                'mother_nid_file' => ['name' => 'মাতার ভোটার আইডি', 'icon' => 'text-purple-500', 'bg' => 'bg-purple-50', 'border' => 'border-purple-200'],
                                'previous_school_certificate' => ['name' => 'পূর্ববর্তী স্কুলের সনদ', 'icon' => 'text-yellow-500', 'bg' => 'bg-yellow-50', 'border' => 'border-yellow-200'],
                                'other_documents' => ['name' => 'অন্যান্য ডকুমেন্ট', 'icon' => 'text-gray-500', 'bg' => 'bg-gray-50', 'border' => 'border-gray-200']
                            ];
                            $hasDocuments = false;
                        @endphp

                        @foreach($documents as $field => $config)
                            @if($student->$field)
                                @php 
                                    $hasDocuments = true;
                                    $docInfo = $student->getDocumentInfo($field);
                                @endphp
                                <div class="group hover:shadow-lg transition-all duration-300 border {{ $config['border'] }} rounded-xl p-4 {{ $config['bg'] }} hover:scale-[1.01]">
                                    <!-- Document Header -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <!-- Document Icon -->
                                        <div class="flex-shrink-0 p-2 bg-white rounded-lg shadow-sm border border-white/50">
                                            @if($docInfo['is_image'])
                                                <svg class="w-6 h-6 {{ $config['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($docInfo['is_pdf'])
                                                <svg class="w-6 h-6 {{ $config['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 {{ $config['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Document Info -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-gray-900 mb-1 text-base">{{ $config['name'] }}</h4>
                                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-white border shadow-sm">
                                                    {{ strtoupper($docInfo['extension']) }}
                                                </span>
                                                @if($docInfo['size'])
                                                    <span class="flex items-center gap-1 font-medium text-xs">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ $docInfo['size'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 truncate">{{ $docInfo['filename'] }}</p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons (Bottom) -->
                                    <div class="flex items-center gap-2 pt-3 border-t border-white/50">
                                        <!-- View Button - Opens in New Tab -->
                                        <a href="{{ route('tenant.students.document.view', [$student->id, $field]) }}" target="_blank"
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border-2 border-blue-300 shadow-sm text-sm font-semibold rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            দেখুন
                                        </a>

                                        <!-- Download Button -->
                                        <a href="{{ route('tenant.students.document.download', [$student->id, $field]) }}" 
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border-2 border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            ডাউনলোড
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        
                        @if(!$hasDocuments)
                            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">কোনো ডকুমেন্ট নেই</h3>
                                <p class="text-sm text-gray-500">এই শিক্ষার্থীর জন্য কোনো ডকুমেন্ট আপলোড করা হয়নি</p>
                                <a href="{{ route('tenant.students.edit', $student->id) }}" class="inline-flex items-center px-4 py-2 mt-4 border border-transparent text-sm font-medium rounded-lg text-indigo-600 bg-indigo-100 hover:bg-indigo-200 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    ডকুমেন্ট যোগ করুন
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-3 border-b">
                        <div class="bg-teal-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">অতিরিক্ত তথ্য</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl p-4 border border-yellow-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-yellow-800">বিশেষ চাহিদা</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengali($student->special_needs) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-green-800">স্বাস্থ্য অবস্থা</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengali($student->health_condition) ?: 'ভালো' }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-red-800">জরুরি যোগাযোগ</p>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ convertToBengaliNumbers($student->emergency_contact) ?: 'প্রযোজ্য নয়' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print-Only Layout -->
<div class="print-only">
    <div class="print-container">
        <!-- Print Header -->
        <div class="print-header">
            <h1>শিক্ষার্থীর তথ্য</h1>
            <h2>{{ config('app.name', 'স্কুল ম্যানেজমেন্ট সিস্টেম') }}</h2>
        </div>

        <!-- Student Profile -->
        <div class="print-profile">
            <div class="print-photo">
                <img src="{{ $student->photo_url }}" alt="{{ $student->name_bn }}">
            </div>
            <div class="print-profile-info">
                <h3>{{ $student->name_bn }}</h3>
                <p style="font-size: 12px; margin: 0 0 8px 0;">{{ $student->name_en }}</p>
                <div class="print-profile-grid">
                    <div class="print-profile-item">
                        <span>শিক্ষার্থী আইডি:</span>
                        <strong>{{ convertToBengaliNumbers($student->student_id) }}</strong>
                    </div>
                    <div class="print-profile-item">
                        <span>রোল নম্বর:</span>
                        <strong>{{ convertToBengali($student->roll) }}</strong>
                    </div>
                    <div class="print-profile-item">
                        <span>ক্লাস:</span>
                        <strong>{{ $student->class }} @if($student->section) - {{ $student->section }} @endif</strong>
                    </div>
                    <div class="print-profile-item">
                        <span>স্ট্যাটাস:</span>
                        <strong>{{ convertToBengali($student->status) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="print-section">
            <div class="print-section-title">ব্যক্তিগত তথ্য</div>
            <div class="print-grid-3">
                <div class="print-item">
                    <span class="print-item-label">জন্ম তারিখ:</span>
                    <span class="print-item-value">{{ convertToBengaliNumbers($student->date_of_birth) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">লিঙ্গ:</span>
                    <span class="print-item-value">{{ convertToBengali($student->gender) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">রক্তের গ্রুপ:</span>
                    <span class="print-item-value">{{ convertToBengali($student->blood_group) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">ধর্ম:</span>
                    <span class="print-item-value">{{ convertToBengali($student->religion) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">জাতীয়তা:</span>
                    <span class="print-item-value">{{ convertToBengali($student->nationality) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">জন্ম নিবন্ধন:</span>
                    <span class="print-item-value">{{ convertToBengali($student->birth_certificate_no) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">মোবাইল:</span>
                    <span class="print-item-value">{{ convertToBengaliNumbers($student->phone) ?: 'প্রযোজ্য নয়' }}</span>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="print-section">
            <div class="print-section-title">ঠিকানা</div>
            <div class="print-address">
                <div class="print-address-title">বর্তমান ঠিকানা</div>
                <div class="print-address-content">{{ $student->present_address }}</div>
            </div>
            <div class="print-address">
                <div class="print-address-title">স্থায়ী ঠিকানা</div>
                <div class="print-address-content">{{ $student->permanent_address }}</div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="print-section">
            <div class="print-section-title">একাডেমিক তথ্য</div>
            <div class="print-grid-3">
                <div class="print-item">
                    <span class="print-item-label">ভর্তির তারিখ:</span>
                    <span class="print-item-value">{{ convertToBengaliNumbers($student->admission_date) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">শিক্ষাবর্ষ:</span>
                    <span class="print-item-value">{{ convertToBengaliNumbers($student->academic_year) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">শিফট:</span>
                    <span class="print-item-value">{{ convertToBengali($student->shift) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">পূর্ববর্তী স্কুল:</span>
                    <span class="print-item-value">{{ convertToBengali($student->previous_school) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">ট্রান্সপোর্ট:</span>
                    <span class="print-item-value">{{ convertToBengali($student->transport) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">হোস্টেল:</span>
                    <span class="print-item-value">{{ convertToBengali($student->hostel) }}</span>
                </div>
            </div>
        </div>

        <!-- Guardian Information -->
        <div class="print-section">
            <div class="print-section-title">অভিভাবকের তথ্য</div>
            
            <!-- Father Info -->
            <div class="print-guardian">
                <div class="print-guardian-title">পিতার তথ্য</div>
                <div class="print-grid">
                    <div class="print-item">
                        <span class="print-item-label">নাম:</span>
                        <span class="print-item-value">{{ $student->father_name }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">মোবাইল:</span>
                        <span class="print-item-value">{{ convertToBengaliNumbers($student->father_mobile) ?: 'প্রযোজ্য নয়' }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">পেশা:</span>
                        <span class="print-item-value">{{ convertToBengali($student->father_occupation) }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">এনআইডি:</span>
                        <span class="print-item-value">{{ convertToBengaliNumbers($student->father_nid) ?: 'প্রযোজ্য নয়' }}</span>
                    </div>
                </div>
            </div>

            <!-- Mother Info -->
            <div class="print-guardian">
                <div class="print-guardian-title">মাতার তথ্য</div>
                <div class="print-grid">
                    <div class="print-item">
                        <span class="print-item-label">নাম:</span>
                        <span class="print-item-value">{{ $student->mother_name }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">মোবাইল:</span>
                        <span class="print-item-value">{{ convertToBengaliNumbers($student->mother_mobile) ?: 'প্রযোজ্য নয়' }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">পেশা:</span>
                        <span class="print-item-value">{{ convertToBengali($student->mother_occupation) }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">এনআইডি:</span>
                        <span class="print-item-value">{{ convertToBengaliNumbers($student->mother_nid) ?: 'প্রযোজ্য নয়' }}</span>
                    </div>
                </div>
            </div>

            <!-- Guardian Info (if exists) -->
            @if($student->guardian_name)
            <div class="print-guardian">
                <div class="print-guardian-title">অভিভাবকের তথ্য</div>
                <div class="print-grid">
                    <div class="print-item">
                        <span class="print-item-label">নাম:</span>
                        <span class="print-item-value">{{ $student->guardian_name }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">মোবাইল:</span>
                        <span class="print-item-value">{{ convertToBengaliNumbers($student->guardian_mobile) ?: 'প্রযোজ্য নয়' }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">সম্পর্ক:</span>
                        <span class="print-item-value">{{ convertToBengali($student->guardian_relation) }}</span>
                    </div>
                    <div class="print-item">
                        <span class="print-item-label">ঠিকানা:</span>
                        <span class="print-item-value">{{ convertToBengali($student->guardian_address) }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Additional Information -->
        <div class="print-section">
            <div class="print-section-title">অতিরিক্ত তথ্য</div>
            <div class="print-grid-3">
                <div class="print-item">
                    <span class="print-item-label">বিশেষ চাহিদা:</span>
                    <span class="print-item-value">{{ convertToBengali($student->special_needs) }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">স্বাস্থ্য অবস্থা:</span>
                    <span class="print-item-value">{{ convertToBengali($student->health_condition) ?: 'ভালো' }}</span>
                </div>
                <div class="print-item">
                    <span class="print-item-label">জরুরি যোগাযোগ:</span>
                    <span class="print-item-value">{{ convertToBengaliNumbers($student->emergency_contact) ?: 'প্রযোজ্য নয়' }}</span>
                </div>
            </div>
        </div>

        <!-- Documents -->
        @php
            $documents = [
                'birth_certificate_file' => 'জন্ম নিবন্ধন সনদ',
                'vaccination_card' => 'টিকা কার্ড',
                'father_nid_file' => 'পিতার ভোটার আইডি',
                'mother_nid_file' => 'মাতার ভোটার আইডি',
                'previous_school_certificate' => 'পূর্ববর্তী স্কুলের সনদ',
                'other_documents' => 'অন্যান্য ডকুমেন্ট'
            ];
            $hasDocuments = false;
            foreach($documents as $field => $name) {
                if($student->$field) {
                    $hasDocuments = true;
                    break;
                }
            }
        @endphp

        @if($hasDocuments)
        <div class="print-section">
            <div class="print-section-title">ডকুমেন্ট</div>
            <div class="print-documents">
                @foreach($documents as $field => $name)
                    @if($student->$field)
                        <div class="print-document-item">
                            <span class="print-item-label">{{ $name }}:</span>
                            <span class="print-item-value">✓ আপলোড করা হয়েছে</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Print Footer -->
        <div class="print-footer">
            <p>প্রিন্ট তারিখ: {{ convertToBengaliNumbers(date('d/m/Y')) }} | সময়: {{ convertToBengaliNumbers(date('h:i A')) }}</p>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
        font-family: 'SolaimanLipi', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
        color: #000;
        background: white;
        margin: 0;
        padding: 0;
    }
    
    /* A4 Page Setup */
    @page {
        size: A4;
        margin: 15mm;
    }
    
    .print-container {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        background: white;
    }
    
    /* Print Header */
    .print-header {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .print-header h1 {
        font-size: 18px;
        font-weight: bold;
        margin: 0 0 5px 0;
        color: #000;
    }
    
    .print-header h2 {
        font-size: 14px;
        margin: 0;
        color: #000;
    }
    
    /* Student Profile Section */
    .print-profile {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #000;
    }
    
    .print-photo {
        width: 80px;
        height: 100px;
        border: 1px solid #000;
        flex-shrink: 0;
    }
    
    .print-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .print-profile-info {
        flex: 1;
    }
    
    .print-profile-info h3 {
        font-size: 16px;
        font-weight: bold;
        margin: 0 0 5px 0;
        color: #000;
    }
    
    .print-profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        font-size: 11px;
    }
    
    .print-profile-item {
        display: flex;
        justify-content: space-between;
    }
    
    .print-profile-item strong {
        font-weight: bold;
    }
    
    /* Print Sections */
    .print-section {
        margin-bottom: 15px;
        page-break-inside: avoid;
    }
    
    .print-section-title {
        font-size: 14px;
        font-weight: bold;
        background: #f0f0f0;
        padding: 5px 8px;
        border: 1px solid #000;
        margin-bottom: 8px;
        color: #000;
    }
    
    .print-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        font-size: 11px;
    }
    
    .print-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
        font-size: 11px;
    }
    
    .print-item {
        display: flex;
        justify-content: space-between;
        padding: 3px 5px;
        border-bottom: 1px dotted #ccc;
    }
    
    .print-item-label {
        font-weight: bold;
        color: #000;
    }
    
    .print-item-value {
        color: #000;
    }
    
    /* Address Section */
    .print-address {
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #ddd;
        background: #f9f9f9;
    }
    
    .print-address-title {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 5px;
        color: #000;
    }
    
    .print-address-content {
        font-size: 11px;
        line-height: 1.3;
        color: #000;
    }
    
    /* Guardian Section */
    .print-guardian {
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #ddd;
    }
    
    .print-guardian-title {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 5px;
        color: #000;
        background: #f0f0f0;
        padding: 3px 5px;
    }
    
    /* Documents Section */
    .print-documents {
        font-size: 11px;
    }
    
    .print-document-item {
        display: flex;
        justify-content: space-between;
        padding: 3px 5px;
        border-bottom: 1px dotted #ccc;
    }
    
    /* Hide elements not needed in print */
    .hover\:shadow-lg,
    .transition-all,
    .hover\:scale-\[1\.01\],
    .shadow-lg,
    .shadow-md,
    .bg-gradient-to-r {
        box-shadow: none !important;
        background: white !important;
        transform: none !important;
        transition: none !important;
    }
    
    /* Ensure proper page breaks */
    .print-page-break {
        page-break-before: always;
    }
    
    /* Footer */
    .print-footer {
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #000;
        text-align: center;
        font-size: 10px;
        color: #666;
    }
}

/* Regular screen styles */
@media screen {
    .print-only {
        display: none;
    }
}

/* Custom Delete Confirmation Modal */
.delete-modal {
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

.delete-modal.active {
    display: flex;
}

.delete-modal-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে এই শিক্ষার্থীকে মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button onclick="submitDelete()" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Delete Modal Functions
function confirmDelete() {
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

function submitDelete() {
    document.getElementById('deleteForm').submit();
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection

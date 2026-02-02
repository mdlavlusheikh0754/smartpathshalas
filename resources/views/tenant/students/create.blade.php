@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">নতুন শিক্ষার্থী যোগ করুন</h1>
                <p class="text-gray-600 mt-1">শিক্ষার্থীর সম্পূর্ণ তথ্য পূরণ করুন</p>
            </div>
            <a href="{{ route('tenant.students.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        <form action="{{ route('tenant.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Student Type Selection -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <label class="block text-base font-semibold text-gray-800 mb-4">শিক্ষার্থীর ধরন নির্বাচন করুন *</label>
                <div class="flex gap-4 p-1.5 bg-gray-100 rounded-xl">
                    <label class="flex-1 cursor-pointer relative group">
                        <input type="radio" name="student_type" value="new" checked class="peer sr-only" onchange="toggleRollNumber()">
                        <div class="text-center py-4 rounded-lg font-bold text-lg transition-all duration-200 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-gray-500 group-hover:text-gray-700 flex items-center justify-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            <span>নতুন শিক্ষার্থী</span>
                        </div>
                        <div class="hidden peer-checked:block absolute top-1/2 -translate-y-1/2 right-4 text-blue-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer relative group">
                        <input type="radio" name="student_type" value="old" class="peer sr-only" onchange="toggleRollNumber()">
                        <div class="text-center py-4 rounded-lg font-bold text-lg transition-all duration-200 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-gray-500 group-hover:text-gray-700 flex items-center justify-center gap-2">
                            <i class="fas fa-user-graduate"></i>
                            <span>পুরাতন শিক্ষার্থী</span>
                        </div>
                        <div class="hidden peer-checked:block absolute top-1/2 -translate-y-1/2 right-4 text-blue-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </label>
                </div>
                <div class="mt-3 flex items-start gap-2 text-sm text-gray-600 bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <p>নতুন শিক্ষার্থীর ক্ষেত্রে রোল নম্বর স্বয়ংক্রিয়ভাবে তৈরি হবে। পুরাতন শিক্ষার্থীর ক্ষেত্রে রোল নম্বর ম্যানুয়ালি দিতে হবে।</p>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ব্যক্তিগত তথ্য</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর ছবি</label>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center overflow-hidden">
                                <img id="preview" src="" alt="" class="w-full h-full object-cover hidden">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="file" name="photo" accept="image/*" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewImage(event)">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম (বাংলা) *</label>
                        <input type="text" name="name_bn" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: মোহাম্মদ রহিম">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম (ইংরেজি) *</label>
                        <input type="text" name="name_en" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g: Mohammad Rahim">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম তারিখ *</label>
                        <input type="date" name="date_of_birth" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">লিঙ্গ *</label>
                        <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="male">ছেলে</option>
                            <option value="female">মেয়ে</option>
                            <option value="other">অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রক্তের গ্রুপ</label>
                        <select name="blood_group" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ধর্ম *</label>
                        <select name="religion" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="islam">ইসলাম</option>
                            <option value="hinduism">হিন্দু</option>
                            <option value="buddhism">বৌদ্ধ</option>
                            <option value="christianity">খ্রিস্টান</option>
                            <option value="other">অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জাতীয়তা *</label>
                        <input type="text" name="nationality" value="বাংলাদেশী" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম নিবন্ধন নম্বর</label>
                        <input type="text" name="birth_certificate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মোবাইল নম্বর</label>
                        <input type="text" name="mobile" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <!-- Present Address -->
                    <div class="md:col-span-3">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">বর্তমান ঠিকানা *</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ *</label>
                        <select name="present_division" id="present_division" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadDistricts('present')">
                            <option value="">নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জেলা *</label>
                        <select name="present_district" id="present_district" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUpazilas('present')">
                            <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা *</label>
                        <select name="present_upazila" id="present_upazila" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUnions('present')">
                            <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইউনিয়ন</label>
                        <select name="present_union" id="present_union" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">প্রথমে উপজেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">গ্রাম/রাস্তা/বাড়ি নম্বর *</label>
                        <input type="text" name="present_address_details" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="বিস্তারিত ঠিকানা লিখুন">
                    </div>

                    <!-- Permanent Address -->
                    <div class="md:col-span-3 mt-6">
                        <div class="flex items-center gap-3 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">স্থায়ী ঠিকানা *</h3>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="sameAsPresent" onchange="copyPresentAddress()" class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm text-gray-600">বর্তমান ঠিকানার মতো</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ *</label>
                        <select name="permanent_division" id="permanent_division" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadDistricts('permanent')">
                            <option value="">নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জেলা *</label>
                        <select name="permanent_district" id="permanent_district" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUpazilas('permanent')">
                            <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা *</label>
                        <select name="permanent_upazila" id="permanent_upazila" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUnions('permanent')">
                            <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইউনিয়ন</label>
                        <select name="permanent_union" id="permanent_union" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">প্রথমে উপজেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">গ্রাম/রাস্তা/বাড়ি নম্বর *</label>
                        <input type="text" name="permanent_address_details" id="permanent_address_details" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="বিস্তারিত ঠিকানা লিখুন">
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">একাডেমিক তথ্য</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ভর্তির তারিখ *</label>
                        <input type="date" name="admission_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রোল নম্বর *</label>
                        <input type="text" name="roll_number" id="roll_number" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed" placeholder="স্বয়ংক্রিয়ভাবে তৈরি হবে">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী আইডি</label>
                        <input type="text" name="student_id" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="{{ $previewStudentId ?? 'স্বয়ংক্রিয়ভাবে তৈরি হবে' }}" value="{{ $previewStudentId ?? '' }}">
                        <p class="text-xs text-gray-500 mt-1">ফরম্যাট: স্কুল Initials + বছর + ক্রমিক নম্বর (যেমন: INA-26-0001)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রেজিস্ট্রেশন নম্বর</label>
                        <input type="text" name="registration_number" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="{{ $previewRegistrationNumber ?? 'স্বয়ংক্রিয়ভাবে তৈরি হবে' }}" value="{{ $previewRegistrationNumber ?? '' }}">
                        <p class="text-xs text-gray-500 mt-1">ফরম্যাট: বছর + স্কুল কোড + ক্রমিক নম্বর (যেমন: {{ $previewRegistrationNumber ?? '20261010001' }})</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস *</label>
                        <select name="class" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">সেকশন *</label>
                        <select name="section" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিফট</label>
                        <select name="shift" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="morning">সকাল</option>
                            <option value="day">দিন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষাবর্ষ *</label>
                        <select name="academic_year" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="২০২৩">২০২৩</option>
                            <option value="২০২৪">২০২৪</option>
                            <option value="২০২৫">২০২৫</option>
                            <option value="২০২৬" selected>২০২৬</option>
                            <option value="২০২৭">২০২৭</option>
                            <option value="২০২৮">২০২৮</option>
                            <option value="২০২৯">২০২৯</option>
                            <option value="২০৩০">২০৩০</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ববর্তী স্কুল</label>
                        <input type="text" name="previous_school" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ট্রান্সপোর্ট সুবিধা</label>
                        <select name="transport" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="no">না</option>
                            <option value="yes">হ্যাঁ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">হোস্টেল সুবিধা</label>
                        <select name="hostel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="no">না</option>
                            <option value="yes">হ্যাঁ</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">অভিভাবকের তথ্য</h2>
                </div>

                <!-- Father's Information -->
                <h3 class="text-lg font-bold text-gray-800 mb-4">পিতার তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার নাম *</label>
                        <input type="text" name="father_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার মোবাইল *</label>
                        <input type="text" name="father_mobile" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার পেশা</label>
                        <input type="text" name="father_occupation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার এনআইডি</label>
                        <input type="text" name="father_nid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার ইমেইল</label>
                        <input type="email" name="father_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার বার্ষিক আয়</label>
                        <input type="text" name="father_income" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Mother's Information -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-8">মাতার তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার নাম *</label>
                        <input type="text" name="mother_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার মোবাইল</label>
                        <input type="text" name="mother_mobile" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার পেশা</label>
                        <input type="text" name="mother_occupation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার এনআইডি</label>
                        <input type="text" name="mother_nid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার ইমেইল</label>
                        <input type="email" name="mother_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Guardian Information (if different) -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-8">অভিভাবকের তথ্য (যদি ভিন্ন হয়)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের নাম</label>
                        <input type="text" name="guardian_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের মোবাইল</label>
                        <input type="text" name="guardian_mobile" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সম্পর্ক</label>
                        <input type="text" name="guardian_relation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: চাচা, মামা">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের ঠিকানা</label>
                        <textarea name="guardian_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-orange-500 to-red-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">অতিরিক্ত তথ্য</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিশেষ চাহিদা</label>
                        <select name="special_needs" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="no">না</option>
                            <option value="yes">হ্যাঁ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">স্বাস্থ্য সমস্যা</label>
                        <input type="text" name="health_condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যদি থাকে">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জরুরি যোগাযোগ নম্বর *</label>
                        <input type="text" name="emergency_contact" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="active">সক্রিয়</option>
                            <option value="inactive">নিষ্ক্রিয়</option>
                            <option value="suspended">স্থগিত</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">মন্তব্য</label>
                        <textarea name="remarks" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="অতিরিক্ত কোনো তথ্য থাকলে লিখুন"></textarea>
                    </div>
                </div>
            </div>

            <!-- Documents Upload -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ডকুমেন্ট আপলোড</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Birth Certificate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম নিবন্ধন সনদ</label>
                        <div class="relative">
                            <input type="file" name="birth_certificate_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'birth_cert_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 2MB)</p>
                        </div>
                        <div id="birth_cert_preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Vaccination Card -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">টিকা কার্ড</label>
                        <div class="relative">
                            <input type="file" name="vaccination_card" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'vaccine_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 2MB)</p>
                        </div>
                        <div id="vaccine_preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Father's NID/Voter ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার ভোটার আইডি কার্ড</label>
                        <div class="relative">
                            <input type="file" name="father_nid_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'father_nid_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 2MB)</p>
                        </div>
                        <div id="father_nid_preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Mother's NID/Voter ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার ভোটার আইডি কার্ড</label>
                        <div class="relative">
                            <input type="file" name="mother_nid_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'mother_nid_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 2MB)</p>
                        </div>
                        <div id="mother_nid_preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Previous School Certificate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ববর্তী স্কুলের সনদপত্র</label>
                        <div class="relative">
                            <input type="file" name="previous_school_certificate" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'prev_school_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 2MB)</p>
                        </div>
                        <div id="prev_school_preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Other Documents -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অন্যান্য ডকুমেন্ট</label>
                        <div class="relative">
                            <input type="file" name="other_documents" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'other_doc_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 2MB)</p>
                        </div>
                        <div id="other_doc_preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">ডকুমেন্ট আপলোড নির্দেশনা:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>সব ডকুমেন্ট স্পষ্ট এবং পাঠযোগ্য হতে হবে</li>
                                <li>প্রতিটি ফাইলের সর্বোচ্চ সাইজ 10MB</li>
                                <li>JPG, PNG, বা PDF ফরম্যাট গ্রহণযোগ্য</li>
                                <li>ছবি এবং PDF স্বয়ংক্রিয়ভাবে compress হবে storage বাঁচাতে</li>
                                <li>ডকুমেন্ট আপলোড ঐচ্ছিক, তবে সুপারিশকৃত</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between">
                    <a href="{{ route('tenant.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        বাতিল করুন
                    </a>
                    <div class="flex gap-4">
                        <button type="button" onclick="resetForm()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            রিসেট করুন
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            সংরক্ষণ করুন
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRollNumber() {
    const studentType = document.querySelector('input[name="student_type"]:checked').value;
    const rollInput = document.getElementById('roll_number');
    
    if (studentType === 'new') {
        rollInput.value = '';
        rollInput.placeholder = 'স্বয়ংক্রিয়ভাবে তৈরি হবে';
        rollInput.readOnly = true;
        rollInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        rollInput.required = false;
    } else {
        rollInput.value = '';
        rollInput.placeholder = 'রোল নম্বর লিখুন';
        rollInput.readOnly = false;
        rollInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        rollInput.required = true;
    }
}

// Bangladesh Address Data - Will be loaded from central database via API
let addressCache = {
    divisions: [],
    districts: {},
    upazilas: {},
    unions: {}
};

// Always use current origin for API calls (fixes 404 on dev/local)
const getApiUrl = (endpoint) => {
    return window.location.origin + endpoint;
};

// Initialize dropdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Loading divisions...');
    loadDivisions();
});

// Load divisions from API
function loadDivisions() {
    const url = getApiUrl('/api/address/divisions');
    console.log('Fetching divisions from:', url);
    
    fetch(url)
        .then(response => {
            console.log('Divisions response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(divisions => {
            console.log('Divisions loaded:', divisions.length);
            addressCache.divisions = divisions;
            
            const presentDiv = document.getElementById('present_division');
            const permanentDiv = document.getElementById('permanent_division');
            
            divisions.forEach(division => {
                const option1 = new Option(division.name_bn, division.id);
                const option2 = new Option(division.name_bn, division.id);
                presentDiv.add(option1);
                permanentDiv.add(option2);
            });
            
            console.log('✅ Divisions loaded successfully!');
        })
        .catch(error => {
            console.error('❌ Error loading divisions:', error);
            alert('বিভাগ লোড করতে সমস্যা হয়েছে: ' + error.message + '\n\nF12 চেপে Console দেখুন।');
        });
}

// Load districts by division
function loadDistricts(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    // Reset dependent dropdowns
    districtSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
    
    const divisionId = divisionSelect.value;
    if (!divisionId) return;

    // Check cache first
    if (addressCache.districts[divisionId]) {
        populateDistricts(addressCache.districts[divisionId], districtSelect);
        return;
    }

    // Fetch from API
    const url = getApiUrl(`/api/address/districts/${divisionId}`);
    console.log('Fetching districts from:', url);
    
    fetch(url)
        .then(response => response.json())
        .then(districts => {
            console.log('Districts loaded:', districts.length);
            addressCache.districts[divisionId] = districts;
            populateDistricts(districts, districtSelect);
        })
        .catch(error => {
            console.error('Error loading districts:', error);
            alert('জেলা লোড করতে সমস্যা হয়েছে।');
        });
}

function populateDistricts(districts, selectElement) {
    districts.forEach(district => {
        const option = new Option(district.name_bn, district.id);
        selectElement.add(option);
    });
}

// Load upazilas by district
function loadUpazilas(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    // Reset dependent dropdowns
    upazilaSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
    
    const districtId = districtSelect.value;
    if (!districtId) return;

    // Check cache first
    if (addressCache.upazilas[districtId]) {
        populateUpazilas(addressCache.upazilas[districtId], upazilaSelect);
        return;
    }

    // Fetch from API
    const url = getApiUrl(`/api/address/upazilas/${districtId}`);
    console.log('Fetching upazilas from:', url);
    
    fetch(url)
        .then(response => response.json())
        .then(upazilas => {
            console.log('Upazilas loaded:', upazilas.length);
            addressCache.upazilas[districtId] = upazilas;
            populateUpazilas(upazilas, upazilaSelect);
        })
        .catch(error => {
            console.error('Error loading upazilas:', error);
            alert('উপজেলা লোড করতে সমস্যা হয়েছে।');
        });
}

function populateUpazilas(upazilas, selectElement) {
    upazilas.forEach(upazila => {
        // Clean upazila name for display (remove "উপজেলা" suffix)
        const displayName = upazila.name_bn.replace(/ উপজেলা$/, '').trim();
        const option = new Option(displayName, upazila.id);
        selectElement.add(option);
    });
}

// Load unions by upazila
function loadUnions(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    // Reset union dropdown
    unionSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    
    const upazilaId = upazilaSelect.value;
    if (!upazilaId) return;

    // Check cache first
    if (addressCache.unions[upazilaId]) {
        populateUnions(addressCache.unions[upazilaId], unionSelect);
        return;
    }

    // Fetch from API
    const url = getApiUrl(`/api/address/unions/${upazilaId}`);
    console.log('Fetching unions from:', url);
    
    fetch(url)
        .then(response => response.json())
        .then(unions => {
            console.log('Unions loaded:', unions.length);
            addressCache.unions[upazilaId] = unions;
            populateUnions(unions, unionSelect);
        })
        .catch(error => {
            console.error('Error loading unions:', error);
            alert('ইউনিয়ন লোড করতে সমস্যা হয়েছে।');
        });
}

function populateUnions(unions, selectElement) {
    if (unions.length === 0) {
        const option = new Option('এই উপজেলায় কোনো ইউনিয়ন নেই', '');
        selectElement.add(option);
        console.log('ℹ️ No unions found for this upazila');
    } else {
        unions.forEach(union => {
            const option = new Option(union.name_bn, union.id);
            selectElement.add(option);
        });
    }
}

function copyPresentAddress() {
    const checkbox = document.getElementById('sameAsPresent');
    
    if (checkbox.checked) {
        // Copy division
        const presentDiv = document.getElementById('present_division').value;
        document.getElementById('permanent_division').value = presentDiv;
        loadDistricts('permanent');
        
        // Wait for districts to load, then copy district
        setTimeout(() => {
            const presentDist = document.getElementById('present_district').value;
            document.getElementById('permanent_district').value = presentDist;
            loadUpazilas('permanent');
            
            // Wait for upazilas to load, then copy upazila
            setTimeout(() => {
                const presentUpa = document.getElementById('present_upazila').value;
                document.getElementById('permanent_upazila').value = presentUpa;
                loadUnions('permanent');
                
                // Wait for unions to load, then copy union
                setTimeout(() => {
                    const presentUnion = document.getElementById('present_union').value;
                    document.getElementById('permanent_union').value = presentUnion;
                    
                    // Copy address details
                    const presentDetails = document.querySelector('input[name="present_address_details"]').value;
                    document.getElementById('permanent_address_details').value = presentDetails;
                }, 200);
            }, 200);
        }, 200);
    } else {
        // Clear permanent address fields
        document.getElementById('permanent_division').value = '';
        document.getElementById('permanent_district').innerHTML = '<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>';
        document.getElementById('permanent_upazila').innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_union').innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_address_details').value = '';
    }
}

function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            preview.parentElement.querySelector('svg').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function previewDocument(event, previewId) {
    const file = event.target.files[0];
    const previewDiv = document.getElementById(previewId);
    const input = event.target;
    
    if (file) {
        // Check file size (10MB = 10 * 1024 * 1024 bytes)
        if (file.size > 10 * 1024 * 1024) {
            alert('ফাইলের সাইজ 10MB এর বেশি হতে পারবে না!');
            event.target.value = '';
            return;
        }
        
        // Show loading message
        previewDiv.classList.remove('hidden');
        previewDiv.querySelector('.file-name').textContent = 'Processing...';
        
        // If it's an image, compress it
        if (file.type.startsWith('image/')) {
            compressImage(file, input, previewDiv);
        } else if (file.type === 'application/pdf') {
            // For PDF, show info and note about server-side compression
            const fileSize = (file.size / 1024).toFixed(2);
            previewDiv.classList.remove('hidden');
            previewDiv.querySelector('.file-name').textContent = 
                `${file.name} (${fileSize}KB) - Server-এ compress হবে`;
        } else {
            // For other files
            previewDiv.classList.remove('hidden');
            previewDiv.querySelector('.file-name').textContent = file.name;
        }
    } else {
        previewDiv.classList.add('hidden');
    }
}

function compressImage(file, input, previewDiv) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Calculate new dimensions (max 1920px width/height)
            let width = img.width;
            let height = img.height;
            const maxSize = 1920;
            
            if (width > maxSize || height > maxSize) {
                if (width > height) {
                    height = (height / width) * maxSize;
                    width = maxSize;
                } else {
                    width = (width / height) * maxSize;
                    height = maxSize;
                }
            }
            
            canvas.width = width;
            canvas.height = height;
            
            // Draw and compress
            ctx.drawImage(img, 0, 0, width, height);
            
            // Convert to blob with compression (0.7 quality)
            canvas.toBlob(function(blob) {
                // Create new file from blob
                const compressedFile = new File([blob], file.name, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });
                
                // Create a new FileList
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                input.files = dataTransfer.files;
                
                // Show preview with compressed size
                const originalSize = (file.size / 1024).toFixed(2);
                const compressedSize = (compressedFile.size / 1024).toFixed(2);
                const savings = ((1 - compressedFile.size / file.size) * 100).toFixed(0);
                
                previewDiv.classList.remove('hidden');
                previewDiv.querySelector('.file-name').textContent = 
                    `${file.name} (${originalSize}KB → ${compressedSize}KB, ${savings}% সাশ্রয়)`;
                
                console.log(`Image compressed: ${originalSize}KB → ${compressedSize}KB (${savings}% savings)`);
            }, 'image/jpeg', 0.7);
        };
        img.src = e.target.result;
    };
    
    reader.readAsDataURL(file);
}

function resetForm() {
    // Reset all form fields except file inputs
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
    
    inputs.forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
    
    // Reset file inputs safely by replacing them
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
        
        // Re-attach event listeners if needed
        if (newInput.name === 'photo') {
            newInput.addEventListener('change', previewImage);
        } else if (newInput.hasAttribute('onchange')) {
            // Re-attach document preview listeners
            const onchangeAttr = newInput.getAttribute('onchange');
            newInput.setAttribute('onchange', onchangeAttr);
        }
    });
    
    // Reset image preview
    const preview = document.getElementById('preview');
    if (preview) {
        preview.classList.add('hidden');
        preview.src = '';
        const placeholder = preview.parentElement.querySelector('svg');
        if (placeholder) {
            placeholder.classList.remove('hidden');
        }
    }
    
    // Hide all document previews
    const documentPreviews = form.querySelectorAll('[id$="_preview"]');
    documentPreviews.forEach(preview => {
        preview.classList.add('hidden');
    });
    
    // Reset student type to 'new'
    const studentTypeRadios = form.querySelectorAll('input[name="student_type"]');
    studentTypeRadios.forEach(radio => {
        if (radio.value === 'new') {
            radio.checked = true;
        }
    });
    
    // Trigger student type change to reset roll field
    toggleStudentType('new');
    
    // Reset default values
    const academicYearInput = form.querySelector('input[name="academic_year"]');
    if (academicYearInput) {
        academicYearInput.value = new Date().getFullYear();
    }
}
</script>
@endsection


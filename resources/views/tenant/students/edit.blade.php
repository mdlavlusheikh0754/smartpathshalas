@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">শিক্ষার্থীর তথ্য সম্পাদনা</h1>
                <p class="text-gray-600 mt-1">শিক্ষার্থীর তথ্য আপডেট করুন</p>
            </div>
            <a href="{{ route('tenant.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        <form action="{{ route('tenant.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                                @if($student->photo)
                                    <img id="preview" src="{{ $student->photo_url }}" alt="" class="w-full h-full object-cover">
                                    <svg id="placeholder" class="w-12 h-12 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @else
                                    <img id="preview" src="" alt="" class="w-full h-full object-cover hidden">
                                    <svg id="placeholder" class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @endif
                            </div>
                            <input type="file" name="photo" accept="image/*" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewImage(event)">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">নতুন ছবি আপলোড করতে চাইলে নির্বাচন করুন</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম (বাংলা) *</label>
                        <input type="text" name="name_bn" value="{{ $student->name_bn }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: মোহাম্মদ রহিম">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম (ইংরেজি) *</label>
                        <input type="text" name="name_en" value="{{ $student->name_en }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g: Mohammad Rahim">
                        <input type="hidden" name="name" value="{{ $student->name_en }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম তারিখ *</label>
                        <input type="date" name="date_of_birth" value="{{ $student->date_of_birth ? date('Y-m-d', strtotime($student->date_of_birth)) : '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">লিঙ্গ *</label>
                        <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="male" {{ $student->gender == 'male' || $student->gender == 'ছেলে' ? 'selected' : '' }}>ছেলে</option>
                            <option value="female" {{ $student->gender == 'female' || $student->gender == 'মেয়ে' ? 'selected' : '' }}>মেয়ে</option>
                            <option value="other" {{ $student->gender == 'other' || $student->gender == 'অন্যান্য' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রক্তের গ্রুপ</label>
                        <select name="blood_group" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="A+" {{ $student->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ $student->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ $student->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ $student->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ $student->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ $student->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="AB+" {{ $student->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ $student->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ধর্ম *</label>
                        <select name="religion" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="islam" {{ $student->religion == 'islam' || $student->religion == 'ইসলাম' ? 'selected' : '' }}>ইসলাম</option>
                            <option value="hinduism" {{ $student->religion == 'hinduism' || $student->religion == 'হিন্দু' ? 'selected' : '' }}>হিন্দু</option>
                            <option value="buddhism" {{ $student->religion == 'buddhism' || $student->religion == 'বৌদ্ধ' ? 'selected' : '' }}>বৌদ্ধ</option>
                            <option value="christianity" {{ $student->religion == 'christianity' || $student->religion == 'খ্রিস্টান' ? 'selected' : '' }}>খ্রিস্টান</option>
                            <option value="other" {{ $student->religion == 'other' || $student->religion == 'অন্যান্য' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জাতীয়তা *</label>
                        <input type="text" name="nationality" value="{{ $student->nationality ?? 'বাংলাদেশী' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম নিবন্ধন নম্বর</label>
                        <input type="text" name="birth_certificate_no" value="{{ $student->birth_certificate_no ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মোবাইল নম্বর</label>
                        <input type="text" name="phone" value="{{ $student->phone ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <!-- Present Address -->
                    <div class="md:col-span-3">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">বর্তমান ঠিকানা *</h3>
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-semibold">ঠিকানা এন্ট্রি সিস্টেম:</p>
                                    <p>প্রথমে নিচের <strong>Manual Entry</strong> ফিল্ডে সম্পূর্ণ ঠিকানা লিখুন। Dropdown গুলো কাজ না করলে Manual Entry ই যথেষ্ট।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">সম্পূর্ণ ঠিকানা (Manual Entry) *</label>
                        <textarea name="present_address_manual" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="সম্পূর্ণ ঠিকানা লিখুন (যেমন: গ্রাম: মিরপুর, ইউনিয়ন: কাশিপুর, উপজেলা: বরিশাল সদর, জেলা: বরিশাল, বিভাগ: বরিশাল)">{{ $student->present_address ?? '' }}</textarea>
                    </div>
                    
                    <div class="md:col-span-3">
                        <p class="text-sm text-gray-500 mb-2">অথবা dropdown ব্যবহার করুন (যদি কাজ করে):</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ</label>
                        <select name="present_division" id="present_division" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadDistricts('present')">
                            <option value="">নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জেলা</label>
                        <select name="present_district" id="present_district" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUpazilas('present')">
                            <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা</label>
                        <select name="present_upazila" id="present_upazila" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUnions('present')">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">গ্রাম/রাস্তা/বাড়ি নম্বর</label>
                        <input type="text" name="present_address_details" value="{{ $student->present_address ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="বিস্তারিত ঠিকানা লিখুন">
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
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p>উপরের checkbox টিক দিলে বর্তমান ঠিকানা কপি হবে। অথবা আলাদাভাবে Manual Entry ব্যবহার করুন।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">সম্পূর্ণ ঠিকানা (Manual Entry) *</label>
                        <textarea name="permanent_address_manual" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="সম্পূর্ণ ঠিকানা লিখুন">{{ $student->permanent_address ?? '' }}</textarea>
                    </div>
                    
                    <div class="md:col-span-3">
                        <p class="text-sm text-gray-500 mb-2">অথবা dropdown ব্যবহার করুন (যদি কাজ করে):</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ</label>
                        <select name="permanent_division" id="permanent_division" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadDistricts('permanent')">
                            <option value="">নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জেলা</label>
                        <select name="permanent_district" id="permanent_district" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUpazilas('permanent')">
                            <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা</label>
                        <select name="permanent_upazila" id="permanent_upazila" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUnions('permanent')">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">গ্রাম/রাস্তা/বাড়ি নম্বর</label>
                        <input type="text" name="permanent_address_details" id="permanent_address_details" value="{{ $student->permanent_address ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="বিস্তারিত ঠিকানা লিখুন">
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
                        <input type="date" name="admission_date" value="{{ $student->admission_date ? date('Y-m-d', strtotime($student->admission_date)) : '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রোল নম্বর *</label>
                        <input type="text" name="roll_number" value="{{ $student->roll_number ?? $student->roll ?? '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী আইডি</label>
                        <input type="text" name="student_id" value="{{ $student->student_id ?? '' }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
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
                                <option value="{{ $class->name }}" {{ ($student->class ?? '') == $class->name ? 'selected' : '' }}>{{ $class->name }} শ্রেণী - {{ $class->section }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সেকশন *</label>
                        <select name="section" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="A" {{ ($student->section ?? '') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ ($student->section ?? '') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ ($student->section ?? '') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ ($student->section ?? '') == 'D' ? 'selected' : '' }}>D</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিফট</label>
                        <select name="shift" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="morning" {{ ($student->shift ?? '') == 'morning' || ($student->shift ?? '') == 'সকাল' ? 'selected' : '' }}>সকাল</option>
                            <option value="day" {{ ($student->shift ?? '') == 'day' || ($student->shift ?? '') == 'দিন' ? 'selected' : '' }}>দিন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষাবর্ষ *</label>
                        <select name="academic_year" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="২০২৩" {{ ($student->academic_year ?? '') == '২০২৩' ? 'selected' : '' }}>২০২৩</option>
                            <option value="২০২৪" {{ ($student->academic_year ?? '') == '২০২৪' ? 'selected' : '' }}>২০২৪</option>
                            <option value="২০২৫" {{ ($student->academic_year ?? '') == '২০২৫' ? 'selected' : '' }}>২০২৫</option>
                            <option value="২০২৬" {{ ($student->academic_year ?? '') == '২০২৬' ? 'selected' : '' }}>২০২৬</option>
                            <option value="২০২৭" {{ ($student->academic_year ?? '') == '২০২৭' ? 'selected' : '' }}>২০২৭</option>
                            <option value="২০২৮" {{ ($student->academic_year ?? '') == '২০২৮' ? 'selected' : '' }}>২০২৮</option>
                            <option value="২০২৯" {{ ($student->academic_year ?? '') == '২০২৯' ? 'selected' : '' }}>২০২৯</option>
                            <option value="২০৩০" {{ ($student->academic_year ?? '') == '২০৩০' ? 'selected' : '' }}>২০৩০</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ববর্তী স্কুল</label>
                        <input type="text" name="previous_school" value="{{ $student->previous_school ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ট্রান্সপোর্ট সুবিধা</label>
                        <select name="transport" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="no" {{ ($student->transport ?? 'no') == 'no' || ($student->transport ?? '') == 'না' ? 'selected' : '' }}>না</option>
                            <option value="yes" {{ ($student->transport ?? '') == 'yes' || ($student->transport ?? '') == 'হ্যাঁ' ? 'selected' : '' }}>হ্যাঁ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">হোস্টেল সুবিধা</label>
                        <select name="hostel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="no" {{ ($student->hostel ?? 'no') == 'no' || ($student->hostel ?? '') == 'না' ? 'selected' : '' }}>না</option>
                            <option value="yes" {{ ($student->hostel ?? '') == 'yes' || ($student->hostel ?? '') == 'হ্যাঁ' ? 'selected' : '' }}>হ্যাঁ</option>
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
                        <input type="text" name="father_name" value="{{ $student->father_name ?? '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার মোবাইল *</label>
                        <input type="text" name="father_mobile" value="{{ $student->father_mobile ?? '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                        <input type="hidden" name="guardian_phone" value="{{ $student->father_mobile ?? $student->guardian_phone ?? '' }}">
                        <input type="hidden" name="parent_phone" value="{{ $student->father_mobile ?? $student->parent_phone ?? '' }}">
                        <input type="hidden" name="address" value="{{ $student->present_address ?? $student->address ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার পেশা</label>
                        <input type="text" name="father_occupation" value="{{ $student->father_occupation ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার এনআইডি</label>
                        <input type="text" name="father_nid" value="{{ $student->father_nid ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার ইমেইল</label>
                        <input type="email" name="father_email" value="{{ $student->father_email ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার বার্ষিক আয়</label>
                        <input type="text" name="father_income" value="{{ $student->father_income ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Mother's Information -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-8">মাতার তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার নাম *</label>
                        <input type="text" name="mother_name" value="{{ $student->mother_name ?? '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার মোবাইল</label>
                        <input type="text" name="mother_mobile" value="{{ $student->mother_mobile ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার পেশা</label>
                        <input type="text" name="mother_occupation" value="{{ $student->mother_occupation ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার এনআইডি</label>
                        <input type="text" name="mother_nid" value="{{ $student->mother_nid ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার ইমেইল</label>
                        <input type="email" name="mother_email" value="{{ $student->mother_email ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Guardian Information (if different) -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-8">অভিভাবকের তথ্য (যদি ভিন্ন হয়)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের নাম</label>
                        <input type="text" name="guardian_name" value="{{ $student->guardian_name ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের মোবাইল</label>
                        <input type="text" name="guardian_mobile" value="{{ $student->guardian_mobile ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সম্পর্ক</label>
                        <input type="text" name="guardian_relation" value="{{ $student->guardian_relation ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: চাচা, মামা">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের ঠিকানা</label>
                        <textarea name="guardian_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $student->guardian_address ?? '' }}</textarea>
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
                            <option value="no" {{ ($student->special_needs ?? 'no') == 'no' || ($student->special_needs ?? '') == 'না' ? 'selected' : '' }}>না</option>
                            <option value="yes" {{ ($student->special_needs ?? '') == 'yes' || ($student->special_needs ?? '') == 'হ্যাঁ' ? 'selected' : '' }}>হ্যাঁ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">স্বাস্থ্য সমস্যা</label>
                        <input type="text" name="health_condition" value="{{ $student->health_condition ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যদি থাকে">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জরুরি যোগাযোগ নম্বর *</label>
                        <input type="text" name="emergency_contact" value="{{ $student->emergency_contact ?? '' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="active" {{ ($student->status ?? 'active') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                            <option value="inactive" {{ ($student->status ?? '') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                            <option value="suspended" {{ ($student->status ?? '') == 'suspended' ? 'selected' : '' }}>স্থগিত</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">মন্তব্য</label>
                        <textarea name="remarks" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="অতিরিক্ত কোনো তথ্য থাকলে লিখুন">{{ $student->remarks ?? '' }}</textarea>
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
                        @if($student->birth_certificate_file)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>বর্তমান ফাইল আপলোড করা আছে</span>
                                    <a href="{{ $student->getDocumentUrl('birth_certificate_file') }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline">দেখুন</a>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="birth_certificate_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'birth_cert_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB) - নতুন ফাইল আপলোড করলে পুরানো ফাইল রিপ্লেস হবে</p>
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
                        @if($student->vaccination_card)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>বর্তমান ফাইল আপলোড করা আছে</span>
                                    <a href="{{ $student->getDocumentUrl('vaccination_card') }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline">দেখুন</a>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="vaccination_card" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'vaccine_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB) - নতুন ফাইল আপলোড করলে পুরানো ফাইল রিপ্লেস হবে</p>
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
                        @if($student->father_nid_file)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>বর্তমান ফাইল আপলোড করা আছে</span>
                                    <a href="{{ $student->getDocumentUrl('father_nid_file') }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline">দেখুন</a>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="father_nid_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'father_nid_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB) - নতুন ফাইল আপলোড করলে পুরানো ফাইল রিপ্লেস হবে</p>
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
                        @if($student->mother_nid_file)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>বর্তমান ফাইল আপলোড করা আছে</span>
                                    <a href="{{ $student->getDocumentUrl('mother_nid_file') }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline">দেখুন</a>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="mother_nid_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'mother_nid_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB) - নতুন ফাইল আপলোড করলে পুরানো ফাইল রিপ্লেস হবে</p>
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
                        @if($student->previous_school_certificate)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>বর্তমান ফাইল আপলোড করা আছে</span>
                                    <a href="{{ $student->getDocumentUrl('previous_school_certificate') }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline">দেখুন</a>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="previous_school_certificate" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'prev_school_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB) - নতুন ফাইল আপলোড করলে পুরানো ফাইল রিপ্লেস হবে</p>
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
                        @if($student->other_documents)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>বর্তমান ফাইল আপলোড করা আছে</span>
                                    <a href="{{ $student->getDocumentUrl('other_documents') }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline">দেখুন</a>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="other_documents" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="previewDocument(event, 'other_doc_preview')">
                            <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB) - নতুন ফাইল আপলোড করলে পুরানো ফাইল রিপ্লেস হবে</p>
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
                                <li>নতুন ফাইল আপলোড করলে পুরানো ফাইল স্বয়ংক্রিয়ভাবে রিপ্লেস হবে</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between">
                    <a href="{{ route('tenant.students.show', $student->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        বাতিল করুন
                    </a>
                    <div class="flex gap-4">
                        <button type="reset" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
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
// Bangladesh Address Data - Will be loaded from central database via API
let addressCache = {
    divisions: [],
    districts: {},
    upazilas: {},
    unions: {}
};

// Get the base URL (works for both central and tenant domains)
const getApiUrl = (endpoint) => {
    // For address API, always use the current domain (tenant or central)
    // The API routes are available on both
    return endpoint;
};

// Initialize dropdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Loading divisions...');
    loadDivisions();
});

// Load divisions from API
function loadDivisions() {
    const url = getApiUrl('/api/locations/divisions');
    console.log('🔄 Fetching divisions from:', url);
    
    fetch(url)
        .then(response => {
            console.log('📡 Divisions response status:', response.status);
            console.log('📡 Divisions response ok:', response.ok);
            console.log('📡 Divisions response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(divisions => {
            console.log('✅ Divisions loaded successfully:', divisions.length, 'items');
            console.log('📋 First division:', divisions[0]);
            
            addressCache.divisions = divisions;
            
            const presentDiv = document.getElementById('present_division');
            const permanentDiv = document.getElementById('permanent_division');
            
            if (!presentDiv || !permanentDiv) {
                console.error('❌ Division select elements not found!');
                return;
            }
            
            divisions.forEach(division => {
                const option1 = new Option(division.name_bn, division.id);
                const option2 = new Option(division.name_bn, division.id);
                presentDiv.add(option1);
                permanentDiv.add(option2);
            });
            
            console.log('✅ Division dropdowns populated successfully!');
        })
        .catch(error => {
            console.error('❌ Error loading divisions:', error);
            console.error('❌ Error details:', {
                message: error.message,
                stack: error.stack,
                url: url
            });
            
            // Fallback: Load static divisions
            console.log('🔄 Loading static divisions as fallback...');
            loadStaticDivisions();
        });
}

// Fallback static divisions
function loadStaticDivisions() {
    console.log('🔄 Loading static divisions as fallback...');
    
    const staticDivisions = [
        {id: 1, name_bn: 'ঢাকা'},
        {id: 2, name_bn: 'চট্টগ্রাম'},
        {id: 3, name_bn: 'রাজশাহী'},
        {id: 4, name_bn: 'খুলনা'},
        {id: 5, name_bn: 'বরিশাল'},
        {id: 6, name_bn: 'সিলেট'},
        {id: 7, name_bn: 'রংপুর'},
        {id: 8, name_bn: 'ময়মনসিংহ'}
    ];
    
    addressCache.divisions = staticDivisions;
    
    const presentDiv = document.getElementById('present_division');
    const permanentDiv = document.getElementById('permanent_division');
    
    if (!presentDiv || !permanentDiv) {
        console.error('❌ Division select elements not found for static fallback!');
        return;
    }
    
    staticDivisions.forEach(division => {
        const option1 = new Option(division.name_bn, division.id);
        const option2 = new Option(division.name_bn, division.id);
        presentDiv.add(option1);
        permanentDiv.add(option2);
    });
    
    console.log('✅ Static divisions loaded successfully!');
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
    const url = getApiUrl(`/api/locations/districts/${divisionId}`);
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
            
            // Fallback: Add a generic option
            const option = new Option('জেলা নির্বাচন করুন (API Error)', '');
            districtSelect.add(option);
            
            // Add manual input option
            const manualOption = new Option('ম্যানুয়াল এন্ট্রি', 'manual');
            districtSelect.add(manualOption);
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
    if (!districtId || districtId === 'manual') {
        // If manual entry, allow free text
        if (districtId === 'manual') {
            upazilaSelect.innerHTML = '<option value="manual">ম্যানুয়াল এন্ট্রি</option>';
        }
        return;
    }

    // Check cache first
    if (addressCache.upazilas[districtId]) {
        populateUpazilas(addressCache.upazilas[districtId], upazilaSelect);
        return;
    }

    // Fetch from API
    const url = getApiUrl(`/api/locations/upazilas/${districtId}`);
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
            
            // Fallback: Add manual entry option
            const manualOption = new Option('ম্যানুয়াল এন্ট্রি', 'manual');
            upazilaSelect.add(manualOption);
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
    if (!upazilaId || upazilaId === 'manual') {
        if (upazilaId === 'manual') {
            unionSelect.innerHTML = '<option value="manual">ম্যানুয়াল এন্ট্রি</option>';
        }
        return;
    }

    // Check cache first
    if (addressCache.unions[upazilaId]) {
        populateUnions(addressCache.unions[upazilaId], unionSelect);
        return;
    }

    // Fetch from API
    const url = getApiUrl(`/api/locations/unions/${upazilaId}`);
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
            
            // Fallback: Add manual entry option
            const manualOption = new Option('ম্যানুয়াল এন্ট্রি', 'manual');
            unionSelect.add(manualOption);
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
        // Copy manual address first (priority)
        const presentManual = document.querySelector('textarea[name="present_address_manual"]').value;
        if (presentManual.trim()) {
            document.querySelector('textarea[name="permanent_address_manual"]').value = presentManual;
        }
        
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
        document.querySelector('textarea[name="permanent_address_manual"]').value = '';
        document.getElementById('permanent_division').value = '';
        document.getElementById('permanent_district').innerHTML = '<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>';
        document.getElementById('permanent_upazila').innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_union').innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_address_details').value = '';
    }
}

function previewImage(event) {
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    const file = event.target.files[0];
    
    if (file) {
        // Check file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('ছবির সাইজ 10MB এর বেশি হতে পারবে না!');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
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
</script>
@endsection





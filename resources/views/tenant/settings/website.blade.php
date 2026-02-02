@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">ওয়েবসাইট সেটিংস</h1>
                <p class="text-gray-600 mt-1">ল্যান্ডিং পেজ সম্পূর্ণ কাস্টমাইজ করুন</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenant.home') }}" target="_blank" class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 px-4 py-2 border border-green-300 rounded-lg hover:bg-green-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    প্রিভিউ দেখুন
                </a>
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
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

        <!-- Instructions Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-blue-600 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">কিভাবে ব্যবহার করবেন?</h3>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• উপরের ট্যাবগুলো ব্যবহার করে বিভিন্ন সেকশন এডিট করুন</li>
                        <li>• তথ্য পূরণ করার পর "সংরক্ষণ করুন" বাটনে ক্লিক করুন</li>
                        <li>• "প্রিভিউ দেখুন" বাটনে ক্লিক করে আপনার ওয়েবসাইট দেখুন</li>
                        <li>• ছবি আপলোড করার সময় সর্বোচ্চ ৫MB সাইজের ফাইল ব্যবহার করুন</li>
                        <li>• আপলোডের সময় ছবি স্বয়ংক্রিয়ভাবে কম্প্রেস হয়ে যাবে</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-lg mb-6 overflow-hidden">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button type="button" onclick="showTab('hero')" class="tab-btn px-6 py-4 font-bold text-white bg-blue-600 border-b-4 border-blue-600 whitespace-nowrap transition-all duration-300 hover:bg-blue-700" id="tab-hero">
                    হিরো সেকশন
                </button>
                <button type="button" onclick="showTab('about')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-about">
                    প্রতিষ্ঠান পরিচিতি
                </button>
                <button type="button" onclick="showTab('administration')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-administration">
                    প্রশাসন
                </button>
                <button type="button" onclick="showTab('admission')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-admission">
                    ভর্তি
                </button>
                <button type="button" onclick="showTab('facilities')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-facilities">
                    সুবিধাসমূহ
                </button>
                <button type="button" onclick="showTab('gallery')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-gallery">
                    গ্যালারি
                </button>
                <button type="button" onclick="showTab('contact')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-contact">
                    যোগাযোগ
                </button>
                <button type="button" onclick="showTab('footer')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-footer">
                    ফুটার
                </button>
            </div>
        </div>

        <form action="{{ route('tenant.settings.website.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Hero Section Tab -->
            <div id="content-hero" class="tab-content">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                            <i class="fas fa-image text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">হিরো সেকশন</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">হিরো সেকশন ইমেজ (একাধিক)</label>
                            <input type="file" name="hero_images[]" multiple accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG (Max: 5MB each, up to 10 images)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">হিরো ব্যাকগ্রাউন্ড</label>
                            <input type="file" name="hero_bg" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                            @if($settings->hero_bg)
                            <div class="mt-2">
                                <img src="{{ $settings->getImageUrl('hero_bg') }}" alt="Current Hero Background" class="w-32 h-20 object-cover rounded-lg shadow-md">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section Tab -->
            <div id="content-about" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-3 rounded-xl">
                            <i class="fas fa-school text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">প্রতিষ্ঠান পরিচিতি</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">প্রতিষ্ঠানের ইতিহাস</label>
                            <textarea name="history_text" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="প্রতিষ্ঠানের ইতিহাস লিখুন...">{{ old('history_text', $settings->history_text) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">লক্ষ্য ও উদ্দেশ্য (Mission)</label>
                            <textarea name="mission_text" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="মিশন লিখুন...">{{ old('mission_text', $settings->mission_text) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ভিশন (Vision)</label>
                            <textarea name="vision_text" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="ভিশন লিখুন...">{{ old('vision_text', $settings->vision_text) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ভৌত অবকাঠামো</label>
                            <textarea name="infrastructure_text" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="অবকাঠামোর বিবরণ লিখুন...">{{ old('infrastructure_text', $settings->infrastructure_text) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Administration Tab -->
            <div id="content-administration" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Managing Committee Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <div class="bg-gradient-to-br from-violet-500 to-purple-600 p-3 rounded-xl">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">ম্যানেজিং কমিটি</h3>
                                <p class="text-sm text-gray-600">কমিটি সদস্যদের তথ্য</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-bold text-gray-700">কমিটি সদস্য</label>
                                <button type="button" onclick="addCommitteeMember()" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    <i class="fas fa-plus"></i>
                                    সদস্য যোগ করুন
                                </button>
                            </div>
                            
                            <div id="committee-container" class="space-y-4">
                                @php
                                    $committeeMembers = $settings->managing_committee ?? [];
                                    if (empty($committeeMembers)) {
                                        $committeeMembers = [
                                            ['name' => $settings->chairman_name ?? '', 'designation' => 'সভাপতি', 'phone' => '', 'email' => '', 'photo' => '']
                                        ];
                                    }
                                @endphp
                                
                                @foreach($committeeMembers as $index => $member)
                                <div class="committee-entry bg-gray-50 p-5 rounded-xl border border-gray-200" data-index="{{ $index }}">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm font-bold text-gray-700">সদস্য #{{ $index + 1 }}</span>
                                        @if($index > 0)
                                        <button type="button" onclick="removeCommitteeMember({{ $index }})" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">নাম</label>
                                            <input type="text" name="committee[{{ $index }}][name]" value="{{ $member['name'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">পদবী</label>
                                            <input type="text" name="committee[{{ $index }}][designation]" value="{{ $member['designation'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">মোবাইল</label>
                                            <input type="tel" name="committee[{{ $index }}][phone]" value="{{ $member['phone'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">ইমেইল</label>
                                            <input type="email" name="committee[{{ $index }}][email]" value="{{ $member['email'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">যোগাযোগ</label>
                                            <input type="text" name="committee[{{ $index }}][address]" value="{{ $member['address'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">ছবি</label>
                                            <input type="file" name="committee[{{ $index }}][photo]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Staff Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-3 rounded-xl">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">স্টাফ</h3>
                                <p class="text-sm text-gray-600">শিক্ষক ও কর্মীদের তথ্য</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-bold text-gray-700">স্টাফ সদস্য</label>
                                <button type="button" onclick="addStaffMember()" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    <i class="fas fa-plus"></i>
                                    স্টাফ যোগ করুন
                                </button>
                            </div>
                            
                            <div id="staff-container" class="space-y-4">
                                @php
                                    $staffMembers = $settings->teachers_staff ?? [];
                                    if (empty($staffMembers)) {
                                        $staffMembers = [
                                            ['name' => $settings->principal_name ?? '', 'designation' => 'প্রধান শিক্ষক', 'phone' => '', 'email' => '', 'photo' => '']
                                        ];
                                    }
                                @endphp
                                
                                @foreach($staffMembers as $index => $member)
                                <div class="staff-entry bg-gray-50 p-5 rounded-xl border border-gray-200" data-index="{{ $index }}">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm font-bold text-gray-700">স্টাফ #{{ $index + 1 }}</span>
                                        @if($index > 0)
                                        <button type="button" onclick="removeStaffMember({{ $index }})" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">নাম *</label>
                                            <input type="text" name="staff[{{ $index }}][name]" value="{{ $member['name'] ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">পদবী *</label>
                                            <input type="text" name="staff[{{ $index }}][designation]" value="{{ $member['designation'] ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">বিষয়</label>
                                            <input type="text" name="staff[{{ $index }}][subject]" value="{{ $member['subject'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">মোবাইল</label>
                                            <input type="tel" name="staff[{{ $index }}][phone]" value="{{ $member['phone'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">ইমেইল</label>
                                            <input type="email" name="staff[{{ $index }}][email]" value="{{ $member['email'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">ছবি</label>
                                            <input type="file" name="staff[{{ $index }}][photo]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Tab -->
            <div id="content-academic" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-xl">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">একাডেমিক</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ক্লাস রুটিন PDF</label>
                            <input type="file" name="class_routine_pdf" accept=".pdf" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                            @if($settings->class_routine_pdf)
                            <div class="mt-2">
                                <a href="{{ url('/files/' . $settings->class_routine_pdf) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">বর্তমান রুটিন দেখুন</a>
                            </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">সিলেবাস ফাইল</label>
                            <input type="file" name="syllabus_files[]" multiple accept=".pdf" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ছুটির তালিকা</label>
                            <textarea name="holiday_list" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="ছুটির তালিকা লিখুন...">{{ old('holiday_list', $settings->holiday_list ? implode("\n", $settings->holiday_list) : '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">একাডেমিক ক্যালেন্ডার PDF</label>
                            <input type="file" name="academic_calendar_pdf" accept=".pdf" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                            @if($settings->academic_calendar_pdf)
                            <div class="mt-2">
                                <a href="{{ url('/files/' . $settings->academic_calendar_pdf) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">বর্তমান ক্যালেন্ডার দেখুন</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admission Tab -->
            <div id="content-admission" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-3 rounded-xl">
                            <i class="fas fa-user-plus text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">ভর্তি</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">গুরুত্বপূর্ণ সময়সূচী</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">আবেদন শুরু</label>
                                    <input type="text" name="admission_start_date" value="{{ old('admission_start_date', $settings->admission_start_date) }}" placeholder="১ নভেম্বর" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">আবেদনের শেষ</label>
                                    <input type="text" name="admission_end_date" value="{{ old('admission_end_date', $settings->admission_end_date) }}" placeholder="২৫ ডিসেম্বর" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">ভর্তি পরীক্ষা</label>
                                    <input type="text" name="admission_exam_date" value="{{ old('admission_exam_date', $settings->admission_exam_date) }}" placeholder="২৮ ডিসেম্বর" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">ক্লাস শুরু</label>
                                    <input type="text" name="class_start_date" value="{{ old('class_start_date', $settings->class_start_date) }}" placeholder="১ জানুয়ারি" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ভর্তি নিয়মাবলি</label>
                            <textarea name="admission_rules" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="ভর্তি নিয়মাবলি লিখুন...">{{ old('admission_rules', $settings->admission_rules) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">প্রয়োজনীয় কাগজপত্র</label>
                            <textarea name="admission_requirements" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="প্রয়োজনীয় কাগজপত্র লিখুন...">{{ old('admission_requirements', $settings->admission_requirements) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ভর্তি ফি</label>
                            <textarea name="admission_fees" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="ভর্তি ফি সম্পর্কে তথ্য লিখুন...">{{ old('admission_fees', $settings->admission_fees) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ভর্তি প্রক্রিয়া</label>
                            <textarea name="admission_process" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="ভর্তি প্রক্রিয়া সম্পর্কে তথ্য লিখুন...">{{ old('admission_process', $settings->admission_process) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">বৈশিষ্ট্যসমূহ</label>
                            <textarea name="admission_features" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="বৈশিষ্ট্যসমূহ সম্পর্কে তথ্য লিখুন...">{{ old('admission_features', $settings->admission_features) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ভর্তি ফর্ম PDF</label>
                            <input type="file" name="admission_form_pdf" accept=".pdf" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                            @if($settings->admission_form_pdf)
                            <div class="mt-2">
                                <a href="{{ url('/files/' . $settings->admission_form_pdf) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">বর্তমান ফর্ম দেখুন</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities Tab -->
            <div id="content-facilities" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-rose-500 to-pink-600 p-3 rounded-xl">
                            <i class="fas fa-building text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">সুবিধাসমূহ</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['লাইব্রেরি', 'বিজ্ঞান ল্যাব', 'কম্পিউটার ল্যাব', 'খেলার মাঠ', 'ক্যান্টিন', 'পরিবহন সুবিধা'] as $facility)
                        <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="facilities[]" value="{{ $facility }}" 
                                   @if(is_array(old('facilities', $settings->facilities ?? [])) && in_array($facility, old('facilities', $settings->facilities ?? []))) checked @endif
                                   class="w-5 h-5 text-blue-600">
                            <span class="font-medium">{{ $facility }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Gallery Tab -->
            <div id="content-gallery" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-br from-fuchsia-500 to-purple-600 p-3 rounded-xl">
                                <i class="fas fa-images text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">গ্যালারি ব্যবস্থাপনা</h3>
                        </div>
                        <button type="button" onclick="openGalleryModal()" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                            <i class="fas fa-plus mr-2"></i>নতুন যোগ করুন
                        </button>
                    </div>

                    <!-- Gallery Preview -->
                    <div id="gallery-preview" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Gallery items will be loaded here -->
                    </div>

                    <!-- Empty State -->
                    <div id="gallery-empty" class="text-center py-12 hidden">
                        <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-images text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">কোনো গ্যালারি আইটেম যোগ করা হয়নি</p>
                        <button type="button" onclick="openGalleryModal()" class="mt-4 px-6 py-2 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 transition">
                            প্রথম আইটেম যোগ করুন
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div id="content-contact" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">যোগাযোগ</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ফোন</label>
                            <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ফোন ২</label>
                            <input type="text" name="phone_2" value="{{ old('phone_2', $settings->phone_2) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ফোন ৩</label>
                            <input type="text" name="phone_3" value="{{ old('phone_3', $settings->phone_3) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ইমেইল</label>
                            <input type="email" name="email" value="{{ old('email', $settings->email) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">ঠিকানা</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl">{{ old('address', $settings->address) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">গুগল ম্যাপ</label>
                            <textarea name="google_map_embed" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="গুগল ম্যাপ এম্বেড কোড লিখুন...">{{ old('google_map_embed', $settings->google_map_embed) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Tab -->
            <div id="content-footer" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-slate-600 to-gray-700 p-3 rounded-xl">
                            <i class="fas fa-copyright text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">ফুটার</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">কপিরাইট টেক্সট</label>
                            <textarea name="copyright_text" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="কপিরাইট টেক্সট লিখুন...">{{ old('copyright_text', $settings->copyright_text) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">কুইক লিংক</label>
                            <textarea name="quick_links" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="কুইক লিংক লিখুন...">{{ old('quick_links', $settings->quick_links ? implode("\n", $settings->quick_links) : '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end mb-8">
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

<script>
function showTab(tabName) {
    // Hide all tab content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    
    // Remove active from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
        btn.classList.add('text-gray-700', 'border-transparent');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Activate selected tab button
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
    activeBtn.classList.remove('text-gray-700', 'border-transparent');
}

// Add Committee Member
function addCommitteeMember() {
    const container = document.getElementById('committee-container');
    const memberCount = container.querySelectorAll('.committee-entry').length;
    
    const memberEntry = document.createElement('div');
    memberEntry.className = 'committee-entry bg-gray-50 p-5 rounded-xl border border-gray-200';
    memberEntry.dataset.index = memberCount;
    memberEntry.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-gray-700">সদস্য #${memberCount + 1}</span>
            <button type="button" onclick="removeCommitteeMember(${memberCount})" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">নাম</label>
                <input type="text" name="committee[${memberCount}][name]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">পদবী</label>
                <input type="text" name="committee[${memberCount}][designation]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">মোবাইল</label>
                <input type="tel" name="committee[${memberCount}][phone]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">ইমেইল</label>
                <input type="email" name="committee[${memberCount}][email]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">যোগাযোগ</label>
                <input type="text" name="committee[${memberCount}][address]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">ছবি</label>
                <input type="file" name="committee[${memberCount}][photo]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
    `;
    
    container.appendChild(memberEntry);
}

// Remove Committee Member
function removeCommitteeMember(index) {
    const container = document.getElementById('committee-container');
    const memberEntry = container.querySelector(`[data-index="${index}"]`);
    
    if (memberEntry) {
        memberEntry.remove();
        // Reindex remaining entries
        const entries = container.querySelectorAll('.committee-entry');
        entries.forEach((entry, newIndex) => {
            entry.dataset.index = newIndex;
            entry.querySelector('span').textContent = `সদস্য #${newIndex + 1}`;
            
            // Update input names
            const inputs = entry.querySelectorAll('input');
            inputs.forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });
        });
    }
}

// Add Staff Member
function addStaffMember() {
    const container = document.getElementById('staff-container');
    const memberCount = container.querySelectorAll('.staff-entry').length;
    
    const memberEntry = document.createElement('div');
    memberEntry.className = 'staff-entry bg-gray-50 p-5 rounded-xl border border-gray-200';
    memberEntry.dataset.index = memberCount;
    memberEntry.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-gray-700">স্টাফ #${memberCount + 1}</span>
            <button type="button" onclick="removeStaffMember(${memberCount})" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">নাম *</label>
                <input type="text" name="staff[${memberCount}][name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">পদবী *</label>
                <input type="text" name="staff[${memberCount}][designation]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">বিষয়</label>
                <input type="text" name="staff[${memberCount}][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">মোবাইল</label>
                <input type="tel" name="staff[${memberCount}][phone]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">ইমেইল</label>
                <input type="email" name="staff[${memberCount}][email]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">ছবি</label>
                <input type="file" name="staff[${memberCount}][photo]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
    `;
    
    container.appendChild(memberEntry);
}

// Remove Staff Member
function removeStaffMember(index) {
    const container = document.getElementById('staff-container');
    const memberEntry = container.querySelector(`[data-index="${index}"]`);
    
    if (memberEntry) {
        memberEntry.remove();
        // Reindex remaining entries
        const entries = container.querySelectorAll('.staff-entry');
        entries.forEach((entry, newIndex) => {
            entry.dataset.index = newIndex;
            entry.querySelector('span').textContent = `স্টাফ #${newIndex + 1}`;
            
            // Update input names
            const inputs = entry.querySelectorAll('input');
            inputs.forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });
        });
    }
}

// Gallery Management
let galleryItems = [];

function loadGalleryItems() {
    fetch('/api/gallery')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            galleryItems = data;
            renderGalleryItems();
        })
        .catch(error => {
            console.error('Error loading gallery:', error);
            // Show empty state on error
            const preview = document.getElementById('gallery-preview');
            const empty = document.getElementById('gallery-empty');
            if (preview && empty) {
                preview.classList.add('hidden');
                empty.classList.remove('hidden');
            }
        });
}

function renderGalleryItems() {
    const preview = document.getElementById('gallery-preview');
    const empty = document.getElementById('gallery-empty');

    if (galleryItems.length === 0) {
        preview.classList.add('hidden');
        empty.classList.remove('hidden');
        return;
    }

    preview.classList.remove('hidden');
    empty.classList.add('hidden');

    preview.innerHTML = galleryItems.map(item => `
        <div class="relative group bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
            ${item.type === 'photo' && item.file_path ? `
                <div class="aspect-video bg-gray-100">
                    <img src="/files/${item.file_path}" alt="${item.title}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\\'flex items-center justify-center h-full bg-gray-200\\'>ছবি লোড হচ্ছে না</div>'">
                </div>
            ` : item.type === 'audio' ? `
                <div class="aspect-video bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                    <i class="fas fa-music text-4xl text-white"></i>
                </div>
            ` : item.type === 'video' ? `
                <div class="aspect-video bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center">
                    <i class="fas fa-video text-4xl text-white"></i>
                </div>
            ` : `
                <div class="aspect-video bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
            `}
            <div class="p-4">
                <h4 class="font-bold text-gray-900 mb-1 truncate">${item.title}</h4>
                ${item.category ? `<span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">${item.category}</span>` : ''}
                <div class="flex items-center justify-end mt-3 space-x-2">
                    <button onclick="editGalleryItem(${item.id})" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteGalleryItem(${item.id})" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function openGalleryModal(item = null) {
    const modal = document.getElementById('gallery-modal');
    const form = document.getElementById('gallery-form');
    
    if (item) {
        document.getElementById('gallery-id').value = item.id;
        document.getElementById('gallery-title').value = item.title;
        document.getElementById('gallery-description').value = item.description || '';
        document.getElementById('gallery-type').value = item.type;
        document.getElementById('gallery-category').value = item.category || '';
        document.getElementById('gallery-sort-order').value = item.sort_order || 0;
        document.getElementById('gallery-is-active').checked = item.is_active;
    } else {
        form.reset();
        document.getElementById('gallery-id').value = '';
    }
    
    modal.classList.remove('hidden');
}

function closeGalleryModal() {
    document.getElementById('gallery-modal').classList.add('hidden');
}

function saveGalleryItem() {
    const form = document.getElementById('gallery-form');
    const formData = new FormData(form);
    const id = document.getElementById('gallery-id').value;
    
    const url = id ? `/api/gallery/${id}` : '/api/gallery';
    const method = id ? 'PUT' : 'POST';
    
    // Add method override for PUT requests
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: method === 'PUT' ? 'POST' : method, // Laravel handles PUT via POST with _method
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeGalleryModal();
            loadGalleryItems();
            showCenteredAlert(data.message, 'success');
        } else {
            showCenteredAlert('Error: ' + (data.message || 'Something went wrong'), 'error');
        }
    })
    .catch(error => {
        console.error('Error saving gallery item:', error);
        showCenteredAlert('Error saving gallery item', 'error');
    });
}

function showCenteredAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed inset-0 flex items-center justify-center z-50';
    alertDiv.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 text-center ${type === 'success' ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500'}">
            <div class="mb-4">
                ${type === 'success' 
                    ? '<i class="fas fa-check-circle text-5xl text-green-500"></i>' 
                    : '<i class="fas fa-exclamation-circle text-5xl text-red-500"></i>'}
            </div>
            <p class="text-lg font-bold text-gray-900 mb-6">${message}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="px-8 py-2 ${type === 'success' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600'} text-white rounded-xl font-bold transition">
                ঠিক আছে
            </button>
        </div>
    `;
    document.body.appendChild(alertDiv);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alertDiv && alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 3000);
}

function editGalleryItem(id) {
    const item = galleryItems.find(g => g.id === id);
    if (item) {
        openGalleryModal(item);
    }
}

function deleteGalleryItem(id) {
    if (confirm('আপনি কি নিশ্চিত যে এই গ্যালারি আইটেম মুছে ফেলতে চান?')) {
        fetch(`/api/gallery/${id}`, {
            method: 'POST', // Laravel handles DELETE via POST with _method
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                '_method': 'DELETE'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadGalleryItems();
                showCenteredAlert(data.message, 'success');
            } else {
                showCenteredAlert('Error: ' + (data.message || 'Something went wrong'), 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting gallery item:', error);
            showCenteredAlert('Error deleting gallery item', 'error');
        });
    }
}

// Load gallery when gallery tab is shown
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
        btn.classList.add('text-gray-700', 'border-transparent');
    });
    
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    document.getElementById(`tab-${tabName}`).classList.add('bg-blue-600', 'text-white', 'border-blue-600');
    document.getElementById(`tab-${tabName}`).classList.remove('text-gray-700', 'border-transparent');
    
    if (tabName === 'gallery') {
        // Add a small delay to ensure DOM is ready
        setTimeout(() => {
            loadGalleryItems();
        }, 100);
    }
}

// Initialize gallery on page load if gallery tab is active
document.addEventListener('DOMContentLoaded', function() {
    // Check if gallery tab is initially active
    const galleryTab = document.getElementById('tab-gallery');
    if (galleryTab && galleryTab.classList.contains('bg-blue-600')) {
        setTimeout(() => {
            loadGalleryItems();
        }, 500);
    }
});
</script>

<!-- Gallery Modal -->
<div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">গ্যালারি আইটেম যোগ করুন</h3>
                <button type="button" onclick="closeGalleryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form id="gallery-form" class="p-6 space-y-4">
            <input type="hidden" id="gallery-id" name="id">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">শিরোনাম *</label>
                <input type="text" id="gallery-title" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-xl">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">বিবরণ</label>
                <textarea id="gallery-description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ধরন *</label>
                <select id="gallery-type" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-xl" onchange="toggleGalleryFields()">
                    <option value="">নির্বাচন করুন</option>
                    <option value="photo">ছবি</option>
                    <option value="audio">অডিও</option>
                    <option value="video">ভিডিও</option>
                </select>
            </div>
            
            <div id="file-field" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-2">ফাইল *</label>
                <input type="file" id="gallery-file" name="file" class="w-full px-4 py-2 border border-gray-300 rounded-xl">
                <p class="text-xs text-gray-500 mt-1">ছবি: PNG, JPG (Max: 10MB) | অডিও: MP3, WAV (Max: 10MB)</p>
            </div>
            
            <div id="video-url-field" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-2">ভিডিও লিংক *</label>
                <input type="url" id="gallery-video-url" name="video_url" class="w-full px-4 py-2 border border-gray-300 rounded-xl" placeholder="YouTube, Vimeo লিংক দিন...">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ক্যাটাগরি</label>
                <input type="text" id="gallery-category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-xl" placeholder="উদাহরণ: স্কুল, ক্রীড়া, সাংস্কৃতিক">
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">সর্ট অর্ডার</label>
                    <input type="number" id="gallery-sort-order" name="sort_order" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-xl">
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" id="gallery-is-active" name="is_active" checked class="w-5 h-5 text-purple-600 rounded">
                    <label for="gallery-is-active" class="ml-2 text-sm font-bold text-gray-700">সক্রিয়</label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeGalleryModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
                    বাতিল
                </button>
                <button type="button" onclick="saveGalleryItem()" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleGalleryFields() {
    const type = document.getElementById('gallery-type').value;
    const fileField = document.getElementById('file-field');
    const videoUrlField = document.getElementById('video-url-field');
    
    if (type === 'video') {
        fileField.classList.add('hidden');
        videoUrlField.classList.remove('hidden');
    } else {
        fileField.classList.remove('hidden');
        videoUrlField.classList.add('hidden');
    }
}
</script>

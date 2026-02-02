@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="w-full">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">স্কুল সেটিংস</h1>
                <p class="text-gray-600 mt-1">স্কুলের মৌলিক তথ্য এবং কনফিগারেশন পরিচালনা করুন</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenant.home') }}" target="_blank" class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 px-4 py-2 border border-green-300 rounded-lg hover:bg-green-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    ল্যান্ডিং পেজ দেখুন
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

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-lg mb-6 overflow-hidden">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button type="button" onclick="showTab('basic')" class="tab-btn px-6 py-4 font-bold text-white bg-blue-600 border-b-4 border-blue-600 whitespace-nowrap transition-all duration-300 hover:bg-blue-700" id="tab-basic">
                    মৌলিক তথ্য
                </button>
                <button type="button" onclick="showTab('contact')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-contact">
                    যোগাযোগের তথ্য
                </button>
                <button type="button" onclick="showTab('principal')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-principal">
                    প্রধান শিক্ষক
                </button>
                <button type="button" onclick="showTab('timing')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-timing">
                    সময়সূচী
                </button>
            </div>
        </div>

        <!-- Basic Information Tab -->
        <div id="content-basic" class="tab-content">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">মৌলিক তথ্য</h2>
                </div>

                <form action="{{ route('tenant.settings.school.basic.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুল লোগো</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center overflow-hidden">
                                    @if($settings->logo)
                                        <img src="{{ $settings->getImageUrl('logo') }}" alt="Logo" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                    <p class="text-sm text-gray-500 mt-1">সর্বোচ্চ ৫MB সাইজের ছবি আপলোড করুন। আপলোডের সময় স্বয়ংক্রিয়ভাবে কম্প্রেস হবে।</p>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">লোগো প্রদর্শনের অবস্থান</label>
                            <select name="logo_position" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="navbar_only" {{ old('logo_position', $settings->logo_position ?? 'navbar_only') == 'navbar_only' ? 'selected' : '' }}>শুধুমাত্র নেভবারে</option>
                                <option value="top_and_navbar" {{ old('logo_position', $settings->logo_position ?? 'navbar_only') == 'top_and_navbar' ? 'selected' : '' }}>উপরে বড় এবং নেভবারে ছোট</option>
                                <option value="top_only" {{ old('logo_position', $settings->logo_position ?? 'navbar_only') == 'top_only' ? 'selected' : '' }}>শুধুমাত্র উপরে বড়</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">লোগো কোথায় প্রদর্শিত হবে তা নির্বাচন করুন</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুলের নাম (বাংলা) *</label>
                            <input type="text" name="school_name_bn" value="{{ old('school_name_bn', $settings->school_name_bn) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুলের নাম (ইংরেজি) *</label>
                            <input type="text" name="school_name_en" value="{{ old('school_name_en', $settings->school_name_en) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">EIIN নম্বর</label>
                            <input type="text" name="eiin" value="{{ old('eiin', $settings->eiin) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুল কোড (শিক্ষার্থী আইডির জন্য) *</label>
                            <input type="text" name="short_code" value="{{ old('short_code', $settings->short_code) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="যেমন: 101" maxlength="10">
                            <p class="text-sm text-gray-500 mt-1">এই কোডটি শিক্ষার্থীর রেজিস্ট্রেশন নম্বর তৈরিতে ব্যবহৃত হবে (যেমন: 2026101001)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুল Initials (শিক্ষার্থী আইডির জন্য)</label>
                            <input type="text" name="school_initials" value="{{ old('school_initials', $settings->school_initials) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="যেমন: INA" maxlength="10" style="text-transform: uppercase;">
                            <p class="text-sm text-gray-500 mt-1">খালি রাখলে স্কুলের ইংরেজি নাম থেকে স্বয়ংক্রিয়ভাবে তৈরি হবে (যেমন: INA-26-0001)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্থাপিত সাল</label>
                            <input type="text" name="established_year" value="{{ old('established_year', $settings->established_year) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুলের ধরন *</label>
                            <select name="school_type" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="government" {{ old('school_type', $settings->school_type) == 'government' ? 'selected' : '' }}>সরকারি</option>
                                <option value="private" {{ old('school_type', $settings->school_type) == 'private' ? 'selected' : '' }}>বেসরকারি</option>
                                <option value="semi_government" {{ old('school_type', $settings->school_type) == 'semi_government' ? 'selected' : '' }}>আধা-সরকারি</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার স্তর *</label>
                            <select name="education_level" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="primary" {{ old('education_level', $settings->education_level) == 'primary' ? 'selected' : '' }}>প্রাথমিক</option>
                                <option value="secondary" {{ old('education_level', $settings->education_level) == 'secondary' ? 'selected' : '' }}>মাধ্যমিক</option>
                                <option value="higher_secondary" {{ old('education_level', $settings->education_level) == 'higher_secondary' ? 'selected' : '' }}>উচ্চ মাধ্যমিক</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষা বোর্ড</label>
                            <input type="text" name="board" value="{{ old('board', $settings->board) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">MPO নম্বর</label>
                            <input type="text" name="mpo_number" value="{{ old('mpo_number', $settings->mpo_number) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">
                            মৌলিক তথ্য সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information Tab -->
        <div id="content-contact" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">যোগাযোগের তথ্য</h2>
                </div>

                <form action="{{ route('tenant.settings.school.contact.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ফোন নম্বর *</label>
                            <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোবাইল নম্বর</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $settings->mobile) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ইমেইল *</label>
                            <input type="email" name="email" value="{{ old('email', $settings->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ওয়েবসাইট</label>
                            <input type="url" name="website" value="{{ old('website', $settings->website) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">পোস্টাল কোড</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $settings->postal_code) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">জেলা</label>
                            <input type="text" name="district" value="{{ old('district', $settings->district) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা</label>
                            <input type="text" name="upazila" value="{{ old('upazila', $settings->upazila) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">ঠিকানা *</label>
                            <textarea name="address" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl">{{ old('address', $settings->address) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">
                            যোগাযোগের তথ্য সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Principal Information Tab -->
        <div id="content-principal" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">প্রধান শিক্ষকের তথ্য</h2>
                </div>

                <form action="{{ route('tenant.settings.school.principal.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">প্রধান শিক্ষকের ছবি</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center overflow-hidden">
                                    @if($settings->principal_photo)
                                        <img src="{{ $settings->getImageUrl('principal_photo') }}" alt="Principal" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="principal_photo" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                    <p class="text-sm text-gray-500 mt-1">সর্বোচ্চ ৫MB সাইজের ছবি আপলোড করুন। আপলোডের সময় স্বয়ংক্রিয়ভাবে কম্প্রেস হবে।</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">প্রধান শিক্ষকের নাম</label>
                            <input type="text" name="principal_name" value="{{ old('principal_name', $settings->principal_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">প্রধান শিক্ষকের মোবাইল</label>
                            <input type="text" name="principal_mobile" value="{{ old('principal_mobile', $settings->principal_mobile) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">প্রধান শিক্ষকের ইমেইল</label>
                            <input type="email" name="principal_email" value="{{ old('principal_email', $settings->principal_email) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">যোগদানের তারিখ</label>
                            <input type="date" name="principal_joining_date" value="{{ old('principal_joining_date', $settings->principal_joining_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষাগত যোগ্যতা</label>
                            <textarea name="principal_qualification" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl">{{ old('principal_qualification', $settings->principal_qualification) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">
                            প্রধান শিক্ষকের তথ্য সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- School Timing Tab -->
        <div id="content-timing" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-orange-500 to-red-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">স্কুলের সময়সূচী</h2>
                </div>

                <form action="{{ route('tenant.settings.school.timing.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুল শুরুর সময়</label>
                            <input type="time" name="school_start_time" value="{{ old('school_start_time', $settings->school_start_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">স্কুল শেষের সময়</label>
                            <input type="time" name="school_end_time" value="{{ old('school_end_time', $settings->school_end_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">সাপ্তাহিক ছুটি *</label>
                            <select name="weekly_holiday" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                                <option value="friday" {{ old('weekly_holiday', $settings->weekly_holiday) == 'friday' ? 'selected' : '' }}>শুক্রবার</option>
                                <option value="saturday" {{ old('weekly_holiday', $settings->weekly_holiday) == 'saturday' ? 'selected' : '' }}>শনিবার</option>
                                <option value="sunday" {{ old('weekly_holiday', $settings->weekly_holiday) == 'sunday' ? 'selected' : '' }}>রবিবার</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">প্রতি ক্লাসের সময়কাল (মিনিট) *</label>
                            <input type="number" name="class_duration" value="{{ old('class_duration', $settings->class_duration ?? 45) }}" min="30" max="120" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিরতি শুরুর সময়</label>
                            <input type="time" name="break_start_time" value="{{ old('break_start_time', $settings->break_start_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিরতি শেষের সময়</label>
                            <input type="time" name="break_end_time" value="{{ old('break_end_time', $settings->break_end_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">
                            সময়সূচী সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    
    // Remove active from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
        btn.classList.add('text-gray-700', 'border-transparent');
    });
    
    // Show selected tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Activate button
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
    activeBtn.classList.remove('text-gray-700', 'border-transparent');
}

// Auto-generate initials from school name
function generateInitials() {
    const schoolNameEn = document.querySelector('input[name="school_name_en"]').value;
    const initialsField = document.querySelector('input[name="school_initials"]');
    
    if (schoolNameEn && !initialsField.value) {
        const words = schoolNameEn.trim().split(' ');
        let initials = '';
        
        words.forEach(word => {
            if (word.length > 0) {
                initials += word.charAt(0).toUpperCase();
            }
        });
        
        initialsField.value = initials;
    }
}

// Add event listener to school name field
document.addEventListener('DOMContentLoaded', function() {
    const schoolNameField = document.querySelector('input[name="school_name_en"]');
    if (schoolNameField) {
        schoolNameField.addEventListener('blur', generateInitials);
    }
});
</script>
@endsection
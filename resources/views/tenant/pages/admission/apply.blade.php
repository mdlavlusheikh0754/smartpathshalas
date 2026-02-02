@extends('tenant.layouts.web')

@section('title', 'অনলাইন আবেদন')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">অনলাইন ভর্তির আবেদন</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">ভর্তির জন্য নিচের ফরমটি সঠিকভাবে পূরণ করুন</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-5xl mx-auto">
        {{-- Success message handled by modal --}}
        @if(session('success'))
            {{-- Hidden trigger for modal --}}
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 mb-8 rounded-2xl shadow-lg animate-pulse">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <h3 class="font-bold text-lg">অনুগ্রহ করে নিচের ত্রুটিগুলো সংশোধন করুন:</h3>
                </div>
                <ul class="list-disc list-inside space-y-1 ml-8">
                    @foreach ($errors->all() as $error)
                        <li class="font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tenant.admission.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Student Type Selection -->
            <div class="bg-white rounded-3xl shadow-xl p-8 mb-10 border border-gray-100 -mt-20 relative z-20">
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
                        <p class="text-xs text-gray-500 mt-1 ml-28">সর্বোচ্চ সাইজ: ২ এমবি (JPG, PNG)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম (বাংলা) *</label>
                        <input type="text" name="name_bn" required value="{{ old('name_bn') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: মোহাম্মদ রহিম">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থীর নাম (ইংরেজি) *</label>
                        <input type="text" name="name_en" required value="{{ old('name_en') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g: Mohammad Rahim">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম তারিখ *</label>
                        <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">লিঙ্গ *</label>
                        <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ছেলে</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>মেয়ে</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ধর্ম *</label>
                        <select name="religion" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="islam" {{ old('religion') == 'islam' ? 'selected' : '' }}>ইসলাম</option>
                            <option value="hinduism" {{ old('religion') == 'hinduism' ? 'selected' : '' }}>হিন্দু</option>
                            <option value="buddhism" {{ old('religion') == 'buddhism' ? 'selected' : '' }}>বৌদ্ধ</option>
                            <option value="christianity" {{ old('religion') == 'christianity' ? 'selected' : '' }}>খ্রিস্টান</option>
                            <option value="other" {{ old('religion') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জাতীয়তা *</label>
                        <input type="text" name="nationality" value="{{ old('nationality', 'বাংলাদেশী') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম নিবন্ধন নম্বর</label>
                        <input type="text" name="birth_certificate_no" value="{{ old('birth_certificate_no') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মোবাইল নম্বর *</label>
                        <input type="text" name="phone" required value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইমেইল</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Present Address -->
                    <div class="md:col-span-3 mt-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">বর্তমান ঠিকানা *</h3>
                    </div>
                    
                    <!-- Hidden inputs for names -->
                    <input type="hidden" name="present_division_name" id="present_division_name">
                    <input type="hidden" name="present_district_name" id="present_district_name">
                    <input type="hidden" name="present_upazila_name" id="present_upazila_name">
                    <input type="hidden" name="present_union_name" id="present_union_name">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ *</label>
                        <select name="present_division" id="present_division" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadDistricts('present'); updateAddressName('present', 'division')">
                            <option value="">নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জেলা *</label>
                        <select name="present_district" id="present_district" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUpazilas('present'); updateAddressName('present', 'district')">
                            <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা *</label>
                        <select name="present_upazila" id="present_upazila" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUnions('present'); updateAddressName('present', 'upazila')">
                            <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইউনিয়ন</label>
                        <select name="present_union" id="present_union" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="updateAddressName('present', 'union')">
                            <option value="">প্রথমে উপজেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">গ্রাম/রাস্তা/বাড়ি নম্বর *</label>
                        <input type="text" name="present_address_details" required value="{{ old('present_address_details') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="বিস্তারিত ঠিকানা লিখুন">
                    </div>

                    <!-- Permanent Address -->
                    <div class="md:col-span-3 mt-6">
                        <div class="flex items-center gap-3 mb-4 border-b pb-2">
                            <h3 class="text-lg font-bold text-gray-800">স্থায়ী ঠিকানা *</h3>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="sameAsPresent" onchange="copyPresentAddress()" class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm text-gray-600">বর্তমান ঠিকানার মতো</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs for names -->
                    <input type="hidden" name="permanent_division_name" id="permanent_division_name">
                    <input type="hidden" name="permanent_district_name" id="permanent_district_name">
                    <input type="hidden" name="permanent_upazila_name" id="permanent_upazila_name">
                    <input type="hidden" name="permanent_union_name" id="permanent_union_name">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ *</label>
                        <select name="permanent_division" id="permanent_division" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadDistricts('permanent'); updateAddressName('permanent', 'division')">
                            <option value="">নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জেলা *</label>
                        <select name="permanent_district" id="permanent_district" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUpazilas('permanent'); updateAddressName('permanent', 'district')">
                            <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">উপজেলা *</label>
                        <select name="permanent_upazila" id="permanent_upazila" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadUnions('permanent'); updateAddressName('permanent', 'upazila')">
                            <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইউনিয়ন</label>
                        <select name="permanent_union" id="permanent_union" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="updateAddressName('permanent', 'union')">
                            <option value="">প্রথমে উপজেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">গ্রাম/রাস্তা/বাড়ি নম্বর *</label>
                        <input type="text" name="permanent_address_details" id="permanent_address_details" required value="{{ old('permanent_address_details') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="বিস্তারিত ঠিকানা লিখুন">
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
                        <input type="text" name="father_name" required value="{{ old('father_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার মোবাইল</label>
                        <input type="text" name="father_mobile" value="{{ old('father_mobile') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার পেশা</label>
                        <input type="text" name="father_occupation" value="{{ old('father_occupation') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার এনআইডি</label>
                        <input type="text" name="father_nid" value="{{ old('father_nid') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার ইমেইল</label>
                        <input type="email" name="father_email" value="{{ old('father_email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পিতার বার্ষিক আয়</label>
                        <input type="text" name="father_income" value="{{ old('father_income') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Mother's Information -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-8">মাতার তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার নাম *</label>
                        <input type="text" name="mother_name" required value="{{ old('mother_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার মোবাইল</label>
                        <input type="text" name="mother_mobile" value="{{ old('mother_mobile') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার পেশা</label>
                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার এনআইডি</label>
                        <input type="text" name="mother_nid" value="{{ old('mother_nid') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাতার ইমেইল</label>
                        <input type="email" name="mother_email" value="{{ old('mother_email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Guardian Information (if different) -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-8">অভিভাবকের তথ্য (যদি ভিন্ন হয়)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের নাম</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের মোবাইল</label>
                        <input type="text" name="guardian_mobile" value="{{ old('guardian_mobile') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="01XXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সম্পর্ক</label>
                        <input type="text" name="guardian_relation" value="{{ old('guardian_relation') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: চাচা, মামা">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিভাবকের ঠিকানা</label>
                        <textarea name="guardian_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('guardian_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Admission Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ভর্তির তথ্য</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রোল নম্বর *</label>
                        <input type="text" name="roll_number" id="roll_number" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed" placeholder="স্বয়ংক্রিয়ভাবে তৈরি হবে">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">কাঙ্খিত শ্রেণী *</label>
                        <select name="class" id="class_select" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="updateSections()">
                            <option value="">নির্বাচন করুন</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->name }}" {{ old('class') == $class->name ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সেকশন *</label>
                        <select name="section" id="section_select" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            @if(old('class') && isset($sections[old('class')]))
                                @foreach($sections[old('class')] as $sec)
                                    <option value="{{ $sec }}" {{ old('section') == $sec ? 'selected' : '' }}>{{ $sec }}</option>
                                @endforeach
                            @else
                                <option value="">প্রথমে শ্রেণী নির্বাচন করুন</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ববর্তী বিদ্যালয়ের নাম</label>
                        <input type="text" name="previous_school" value="{{ old('previous_school') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">মন্তব্য</label>
                        <textarea name="remarks" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="অতিরিক্ত কোনো তথ্য থাকলে লিখুন">{{ old('remarks') }}</textarea>
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
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        আবেদন জমা দিন
                    </button>
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

// Address Handling Script
let addressCache = {
    divisions: [],
    districts: {},
    upazilas: {},
    unions: {}
};

const getApiUrl = (endpoint) => {
    return window.location.origin + endpoint;
};

document.addEventListener('DOMContentLoaded', function() {
    loadDivisions();
});

function updateAddressName(type, level) {
    const select = document.getElementById(`${type}_${level}`);
    const hidden = document.getElementById(`${type}_${level}_name`);
    if (select && hidden && select.selectedIndex >= 0) {
        hidden.value = select.options[select.selectedIndex].text;
    }
}

function loadDivisions() {
    const url = getApiUrl('/api/address/divisions');
    
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load divisions');
            return response.json();
        })
        .then(divisions => {
            addressCache.divisions = divisions;
            const presentDiv = document.getElementById('present_division');
            const permanentDiv = document.getElementById('permanent_division');
            
            divisions.forEach(division => {
                const option1 = new Option(division.name_bn, division.id);
                const option2 = new Option(division.name_bn, division.id);
                presentDiv.add(option1);
                permanentDiv.add(option2);
            });
        })
        .catch(error => {
            console.error('Error loading divisions:', error);
            // Fallback for demo if API fails? No, let's hope it works as user requested same design.
        });
}

function loadDistricts(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    districtSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
    
    const divisionId = divisionSelect.value;
    if (!divisionId) return;

    if (addressCache.districts[divisionId]) {
        populateDistricts(addressCache.districts[divisionId], districtSelect);
        return;
    }

    const url = getApiUrl(`/api/address/districts/${divisionId}`);
    
    fetch(url)
        .then(response => response.json())
        .then(districts => {
            addressCache.districts[divisionId] = districts;
            populateDistricts(districts, districtSelect);
        })
        .catch(error => console.error('Error loading districts:', error));
}

function populateDistricts(districts, selectElement) {
    districts.forEach(district => {
        selectElement.add(new Option(district.name_bn, district.id));
    });
}

function loadUpazilas(type) {
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    upazilaSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
    
    const districtId = districtSelect.value;
    if (!districtId) return;

    if (addressCache.upazilas[districtId]) {
        populateUpazilas(addressCache.upazilas[districtId], upazilaSelect);
        return;
    }

    const url = getApiUrl(`/api/address/upazilas/${districtId}`);
    
    fetch(url)
        .then(response => response.json())
        .then(upazilas => {
            addressCache.upazilas[districtId] = upazilas;
            populateUpazilas(upazilas, upazilaSelect);
        })
        .catch(error => console.error('Error loading upazilas:', error));
}

function populateUpazilas(upazilas, selectElement) {
    upazilas.forEach(upazila => {
        const displayName = upazila.name_bn.replace(/ উপজেলা$/, '').trim();
        selectElement.add(new Option(displayName, upazila.id));
    });
}

function loadUnions(type) {
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    unionSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    
    const upazilaId = upazilaSelect.value;
    if (!upazilaId) return;

    if (addressCache.unions[upazilaId]) {
        populateUnions(addressCache.unions[upazilaId], unionSelect);
        return;
    }

    const url = getApiUrl(`/api/address/unions/${upazilaId}`);
    
    fetch(url)
        .then(response => response.json())
        .then(unions => {
            addressCache.unions[upazilaId] = unions;
            populateUnions(unions, unionSelect);
        })
        .catch(error => console.error('Error loading unions:', error));
}

function populateUnions(unions, selectElement) {
    if (unions.length === 0) {
        selectElement.add(new Option('এই উপজেলায় কোনো ইউনিয়ন নেই', ''));
    } else {
        unions.forEach(union => {
            selectElement.add(new Option(union.name_bn, union.id));
        });
    }
}

function copyPresentAddress() {
    const checkbox = document.getElementById('sameAsPresent');
    
    if (checkbox.checked) {
        // Copy values
        const presentDiv = document.getElementById('present_division').value;
        const permanentDivSelect = document.getElementById('permanent_division');
        permanentDivSelect.value = presentDiv;
        updateAddressName('permanent', 'division');
        
        loadDistricts('permanent');
        
        setTimeout(() => {
            const presentDist = document.getElementById('present_district').value;
            const permanentDistSelect = document.getElementById('permanent_district');
            permanentDistSelect.value = presentDist;
            updateAddressName('permanent', 'district');
            
            loadUpazilas('permanent');
            
            setTimeout(() => {
                const presentUpa = document.getElementById('present_upazila').value;
                const permanentUpaSelect = document.getElementById('permanent_upazila');
                permanentUpaSelect.value = presentUpa;
                updateAddressName('permanent', 'upazila');
                
                loadUnions('permanent');
                
                setTimeout(() => {
                    const presentUnion = document.getElementById('present_union').value;
                    const permanentUnionSelect = document.getElementById('permanent_union');
                    permanentUnionSelect.value = presentUnion;
                    updateAddressName('permanent', 'union');
                    
                    document.getElementById('permanent_address_details').value = 
                        document.querySelector('input[name="present_address_details"]').value;
                }, 200);
            }, 200);
        }, 200);
    } else {
        document.getElementById('permanent_division').value = '';
        document.getElementById('permanent_district').innerHTML = '<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>';
        document.getElementById('permanent_upazila').innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_union').innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_address_details').value = '';
        
        // Clear hidden inputs
        ['division', 'district', 'upazila', 'union'].forEach(level => {
            document.getElementById(`permanent_${level}_name`).value = '';
        });
    }
}

function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        // Compress profile photo too
        if (file.type.startsWith('image/')) {
            const input = event.target;
            const previewDiv = preview.parentElement; // Container div
            
            // Use a temporary preview div structure compatible with compressImage
            // Or just use custom logic here. 
            // Let's use custom logic for profile photo to keep it simple but compressed
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    let width = img.width;
                    let height = img.height;
                    const maxSize = 800; // Smaller for profile photo
                    
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
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    canvas.toBlob(function(blob) {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        input.files = dataTransfer.files;
                        
                        preview.src = URL.createObjectURL(compressedFile);
                        preview.classList.remove('hidden');
                        preview.parentElement.querySelector('svg').classList.add('hidden');
                        
                        console.log(`Profile photo compressed: ${(file.size/1024).toFixed(2)}KB -> ${(compressedFile.size/1024).toFixed(2)}KB`);
                    }, 'image/jpeg', 0.8);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}

function previewDocument(event, previewId) {
    const file = event.target.files[0];
    const previewDiv = document.getElementById(previewId);
    const input = event.target;
    
    if (file) {
        // Check file size (10MB)
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
            // For PDF
            const fileSize = (file.size / 1024).toFixed(2);
            previewDiv.classList.remove('hidden');
            previewDiv.querySelector('.file-name').textContent = 
                `${file.name} (${fileSize}KB)`;
        } else {
            // Other files
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

// Section Handling
const sectionsData = @json($sections);

function updateSections() {
    const classSelect = document.getElementById('class_select');
    const sectionSelect = document.getElementById('section_select');
    const selectedClass = classSelect.value;
    
    sectionSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    
    if (selectedClass && sectionsData[selectedClass]) {
        // Unique sections only
        const uniqueSections = [...new Set(sectionsData[selectedClass])];
        uniqueSections.forEach(section => {
            sectionSelect.add(new Option(section, section));
        });
    } else {
        sectionSelect.innerHTML = '<option value="">প্রথমে শ্রেণী নির্বাচন করুন</option>';
    }
}
</script>
<script>
    // Success Modal Handling
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }, 300);
    }

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('hidden');
        });
    @endif
</script>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 z-[999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeSuccessModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                        সফল হয়েছে
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button" onclick="closeSuccessModal()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                    ঠিক আছে
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
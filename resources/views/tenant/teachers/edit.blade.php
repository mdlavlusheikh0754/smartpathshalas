@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">শিক্ষকের তথ্য সম্পাদনা</h1>
                <p class="text-gray-600 mt-1">শিক্ষকের তথ্য আপডেট করুন</p>
            </div>
            <a href="{{ route('tenant.teachers.show', $id) }}" class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        <form action="{{ route('tenant.teachers.update', $id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ব্যক্তিগত তথ্য</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষকের ছবি</label>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center overflow-hidden">
                                <img id="preview" src="https://ui-avatars.com/api/?name=Teacher&size=128&background=10B981&color=fff" alt="" class="w-full h-full object-cover">
                            </div>
                            <input type="file" name="photo" accept="image/*" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="previewImage(event)">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">নতুন ছবি আপলোড করতে চাইলে নির্বাচন করুন</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষকের নাম (বাংলা) *</label>
                        <input type="text" name="name_bn" value="মোহাম্মদ আব্দুল করিম" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষকের নাম (ইংরেজি) *</label>
                        <input type="text" name="name_en" value="Mohammad Abdul Karim" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষক আইডি</label>
                        <input type="text" name="teacher_id" value="T-{{ str_pad($id, 6, '0', STR_PAD_LEFT) }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জন্ম তারিখ *</label>
                        <input type="date" name="date_of_birth" value="1985-01-15" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">লিঙ্গ *</label>
                        <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="male" selected>পুরুষ</option>
                            <option value="female">মহিলা</option>
                            <option value="other">অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">রক্তের গ্রুপ</label>
                        <select name="blood_group" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">নির্বাচন করুন</option>
                            <option value="A+" selected>A+</option>
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
                        <select name="religion" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="islam" selected>ইসলাম</option>
                            <option value="hinduism">হিন্দু</option>
                            <option value="buddhism">বৌদ্ধ</option>
                            <option value="christianity">খ্রিস্টান</option>
                            <option value="other">অন্যান্য</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">জাতীয়তা *</label>
                        <input type="text" name="nationality" value="বাংলাদেশী" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">এনআইডি নম্বর *</label>
                        <input type="text" name="nid" value="১২৩৪৫৬৭৮৯০১২৩" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মোবাইল নম্বর *</label>
                        <input type="text" name="mobile" value="০১৭১২৩৪৫৬৭৮" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইমেইল</label>
                        <input type="email" name="email" value="teacher@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">একাডেমিক তথ্য</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">বিষয় *</label>
                        <select name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="গণিত" selected>গণিত</option>
                            <option value="বাংলা">বাংলা</option>
                            <option value="ইংরেজি">ইংরেজি</option>
                            <option value="বিজ্ঞান">বিজ্ঞান</option>
                            <option value="সামাজিক বিজ্ঞান">সামাজিক বিজ্ঞান</option>
                            <option value="ইসলাম শিক্ষা">ইসলাম শিক্ষা</option>
                            <option value="কম্পিউটার">কম্পিউটার</option>
                            <option value="শারীরিক শিক্ষা">শারীরিক শিক্ষা</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষাগত যোগ্যতা *</label>
                        <select name="qualification" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="স্নাতকোত্তর" selected>স্নাতকোত্তর</option>
                            <option value="স্নাতক">স্নাতক</option>
                            <option value="এইচএসসি">এইচএসসি</option>
                            <option value="বিএড">বিএড</option>
                            <option value="এমএড">এমএড</option>
                            <option value="পিএইচডি">পিএইচডি</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিজ্ঞতা (বছর) *</label>
                        <input type="number" name="experience" value="10" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পদবী *</label>
                        <select name="designation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="সহকারী শিক্ষক" selected>সহকারী শিক্ষক</option>
                            <option value="প্রধান শিক্ষক">প্রধান শিক্ষক</option>
                            <option value="সহকারী প্রধান শিক্ষক">সহকারী প্রধান শিক্ষক</option>
                            <option value="জুনিয়র শিক্ষক">জুনিয়র শিক্ষক</option>
                            <option value="প্রশিক্ষক">প্রশিক্ষক</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">যোগদানের তারিখ *</label>
                        <input type="date" name="joining_date" value="2020-01-01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">মাসিক বেতন *</label>
                        <input type="number" name="salary" value="25000" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ব্যাংক হিসাব নম্বর</label>
                        <input type="text" name="bank_account" value="১২৩৪৫৬৭৮৯০" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

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
                        <label class="block text-sm font-medium text-gray-700 mb-2">জরুরি যোগাযোগ নম্বর *</label>
                        <input type="text" name="emergency_contact" value="০১৮১২৩৪৫৬৭৮" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="active" selected>সক্রিয়</option>
                            <option value="inactive">নিষ্ক্রিয়</option>
                            <option value="on_leave">ছুটিতে</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">মন্তব্য</label>
                        <textarea name="remarks" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">অভিজ্ঞ শিক্ষক, ভালো পারফরম্যান্স।</textarea>
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
                    <!-- NID Copy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">এনআইডি কপি</label>
                        <input type="file" name="nid_file" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB)</p>
                    </div>

                    <!-- Educational Certificates -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষাগত সনদপত্র</label>
                        <input type="file" name="certificates" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB)</p>
                    </div>

                    <!-- Experience Certificates -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অভিজ্ঞতার সনদপত্র</label>
                        <input type="file" name="experience_certificate" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB)</p>
                    </div>

                    <!-- Other Documents -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">অন্যান্য ডকুমেন্ট</label>
                        <input type="file" name="other_documents" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">PDF বা Image (Max: 10MB)</p>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-green-800">
                            <p class="font-semibold mb-1">ডকুমেন্ট আপলোড নির্দেশনা:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>সব ডকুমেন্ট স্পষ্ট এবং পাঠযোগ্য হতে হবে</li>
                                <li>প্রতিটি ফাইলের সর্বোচ্চ সাইজ 10MB</li>
                                <li>JPG, PNG, বা PDF ফরম্যাট গ্রহণযোগ্য</li>
                                <li>নতুন ফাইল আপলোড করলে পুরাতন ফাইল replace হবে</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between">
                    <a href="{{ route('tenant.teachers.show', $id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        বাতিল করুন
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        আপডেট করুন
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection

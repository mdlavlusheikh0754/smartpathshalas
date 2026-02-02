@extends('layouts.tenant')

@section('content')
@php
    $complainantType = 'other';
    if(auth()->guard('student')->check()) $complainantType = 'student';
    elseif(auth()->guard('guardian')->check()) $complainantType = 'parent';
    elseif(auth()->guard('web')->check()) $complainantType = 'teacher';
@endphp
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">নতুন অভিযোগ দাখিল</h1>
            <p class="text-gray-600 mt-2 bengali-text">আপনার অভিযোগ বা সমস্যা জানাতে নিচের ফর্মটি পূরণ করুন</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('tenant.complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Complainant Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অভিযোগকারীর নাম *</label>
                        <input type="text" name="complainant_name" value="{{ old('complainant_name', auth()->user()->name ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="আপনার পূর্ণ নাম">
                    </div>

                    <!-- Complainant Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অভিযোগকারীর ধরন *</label>
                        <select name="complainant_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="">নির্বাচন করুন</option>
                            <option value="student" {{ old('complainant_type', $complainantType) == 'student' ? 'selected' : '' }}>শিক্ষার্থী</option>
                            <option value="parent" {{ old('complainant_type', $complainantType) == 'parent' ? 'selected' : '' }}>অভিভাবক</option>
                            <option value="teacher" {{ old('complainant_type', $complainantType) == 'teacher' ? 'selected' : '' }}>শিক্ষক</option>
                            <option value="staff" {{ old('complainant_type', $complainantType) == 'staff' ? 'selected' : '' }}>কর্মচারী</option>
                            <option value="other" {{ old('complainant_type', $complainantType) == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">যোগাযোগ নম্বর *</label>
                        <input type="tel" name="contact_number" value="{{ old('contact_number') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="০১৭xxxxxxxx">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">ইমেইল</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="example@email.com">
                    </div>

                    <!-- Complaint Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অভিযোগের ধরন *</label>
                        <select name="complaint_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="">নির্বাচন করুন</option>
                            <option value="academic" {{ old('complaint_type') == 'academic' ? 'selected' : '' }}>শিক্ষাগত</option>
                            <option value="behavioral" {{ old('complaint_type') == 'behavioral' ? 'selected' : '' }}>আচরণগত</option>
                            <option value="facility" {{ old('complaint_type') == 'facility' ? 'selected' : '' }}>সুবিধা</option>
                            <option value="financial" {{ old('complaint_type') == 'financial' ? 'selected' : '' }}>আর্থিক</option>
                            <option value="other" {{ old('complaint_type') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অগ্রাধিকার *</label>
                        <select name="priority" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="">নির্বাচন করুন</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>নিম্ন</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : 'selected' }}>মাধ্যম</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>উচ্চ</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>জরুরি</option>
                        </select>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অভিযোগের বিষয় *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="সংক্ষেপে অভিযোগের বিষয় লিখুন">
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিস্তারিত বিবরণ *</label>
                    <textarea name="description" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="আপনার অভিযোগ বা সমস্যার বিস্তারিত বিবরণ দিন। কী ঘটেছে, কখন ঘটেছে, কারা জড়িত ছিল ইত্যাদি তথ্য দিন...">{{ old('description') }}</textarea>
                </div>

                <!-- Expected Solution -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">প্রত্যাশিত সমাধান</label>
                    <textarea name="expected_solution" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="আপনি কী ধরনের সমাধান প্রত্যাশা করেন?">{{ old('expected_solution') }}</textarea>
                </div>

                <!-- Attachments -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">সংযুক্ত ফাইল</label>
                    <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1 bengali-text">প্রমাণপত্র, ছবি বা অন্যান্য সহায়ক নথি আপলোড করুন (PDF, DOC, JPG, PNG)</p>
                </div>

                <!-- Anonymous Option -->
                <div class="mt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 bengali-text">গোপনীয় অভিযোগ (আপনার নাম প্রকাশ করা হবে না)</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('tenant.complaints.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors bengali-text">বাতিল</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors bengali-text">অভিযোগ জমা দিন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
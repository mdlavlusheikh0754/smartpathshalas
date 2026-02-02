@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">নতুন বিষয় যোগ করুন</h1>
            <p class="text-gray-600 mt-2 bengali-text">স্কুলে নতুন বিষয় যোগ করার জন্য নিচের ফর্মটি পূরণ করুন</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('tenant.subjects.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Subject Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিষয়ের নাম *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="যেমন: বাংলা, ইংরেজি, গণিত">
                    </div>

                    <!-- Subject Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিষয় কোড *</label>
                        <input type="text" name="code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="যেমন: BAN-101, ENG-102">
                    </div>

                    <!-- Subject Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিষয়ের ধরন *</label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="">নির্বাচন করুন</option>
                            <option value="mandatory">বাধ্যতামূলক</option>
                            <option value="optional">ঐচ্ছিক</option>
                        </select>
                    </div>

                    <!-- Full Marks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">পূর্ণমান *</label>
                        <input type="number" name="full_marks" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="১০০">
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিষয়ের বিবরণ</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="বিষয় সম্পর্কে বিস্তারিত তথ্য লিখুন..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('tenant.subjects.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors bengali-text">বাতিল</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors bengali-text">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
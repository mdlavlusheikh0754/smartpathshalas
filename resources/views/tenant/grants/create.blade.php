@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">নতুন অনুদান আবেদন</h1>
            <p class="text-gray-600 mt-2 bengali-text">সরকারি বা বেসরকারি অনুদানের জন্য আবেদন করুন</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('tenant.grants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Grant Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অনুদানের নাম *</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="যেমন: শিক্ষা উন্নয়ন অনুদান">
                    </div>

                    <!-- Organization -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">প্রতিষ্ঠান/সংস্থা *</label>
                        <input type="text" name="organization" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="যেমন: শিক্ষা মন্ত্রণালয়">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অনুদানের পরিমাণ *</label>
                        <input type="number" name="amount" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="২০০০০০">
                    </div>

                    <!-- Grant Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অনুদানের ধরন *</label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="">নির্বাচন করুন</option>
                            <option value="government">সরকারি</option>
                            <option value="private">বেসরকারি</option>
                            <option value="international">আন্তর্জাতিক</option>
                        </select>
                    </div>

                    <!-- Application Deadline -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">আবেদনের শেষ তারিখ</label>
                        <input type="date" name="deadline" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Purpose -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অনুদানের উদ্দেশ্য *</label>
                    <textarea name="purpose" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="অনুদানটি কী কাজে ব্যবহার করা হবে তা বিস্তারিত লিখুন..."></textarea>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিস্তারিত বিবরণ</label>
                    <textarea name="description" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="প্রকল্পের বিস্তারিত বিবরণ, প্রত্যাশিত ফলাফল ইত্যাদি লিখুন..."></textarea>
                </div>

                <!-- Documents -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">সংযুক্ত নথিপত্র</label>
                    <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1 bengali-text">PDF, DOC, DOCX, JPG, PNG ফাইল আপলোড করতে পারেন</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('tenant.grants.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors bengali-text">বাতিল</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors bengali-text">আবেদন জমা দিন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
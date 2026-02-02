@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="w-full">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">একাডেমিক ফাইল ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">সিলেবাস, ক্যালেন্ডার এবং ছুটির দিন পরিচালনা করুন</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2 px-4 py-2 border border-blue-300 rounded-lg hover:bg-blue-50">
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

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
        @endif

        <!-- Academic Files Management Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Academic Syllabus -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl mb-4 inline-block">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">একাডেমিক সিলেবাস</h3>
                <p class="text-gray-600 text-sm mb-4">ক্লাস অনুযায়ী সিলেবাস আপলোড এবং পরিচালনা করুন</p>
                <div class="flex gap-2">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                        আপলোড করুন
                    </button>
                    <button class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                        দেখুন
                    </button>
                </div>
            </div>

            <!-- Academic Calendar -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl mb-4 inline-block">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">একাডেমিক ক্যালেন্ডার</h3>
                <p class="text-gray-600 text-sm mb-4">শিক্ষাবর্ষের ক্যালেন্ডার এবং গুরুত্বপূর্ণ তারিখ</p>
                <div class="flex gap-2">
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">
                        আপলোড করুন
                    </button>
                    <button class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                        দেখুন
                    </button>
                </div>
            </div>

            <!-- Academic Holidays -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl mb-4 inline-block">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">ছুটির দিন</h3>
                <p class="text-gray-600 text-sm mb-4">সরকারি ও স্কুলের ছুটির দিনের তালিকা পরিচালনা</p>
                <div class="flex gap-2">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition">
                        যোগ করুন
                    </button>
                    <button class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                        দেখুন
                    </button>
                </div>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">ফাইল আপলোড</h2>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="mt-4">
                    <label for="file-upload" class="cursor-pointer">
                        <span class="mt-2 block text-sm font-medium text-gray-900">
                            ফাইল আপলোড করতে ক্লিক করুন অথবা এখানে ড্র্যাগ করুন
                        </span>
                        <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </label>
                    <p class="mt-1 text-xs text-gray-500">
                        PDF, DOC, DOCX, JPG, PNG (সর্বোচ্চ 10MB)
                    </p>
                </div>
            </div>
        </div>

        <!-- Current Files Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">বর্তমান ফাইলসমূহ</h2>
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2">কোনো ফাইল আপলোড করা হয়নি</p>
            </div>
        </div>
    </div>
</div>

<script>
// File upload functionality can be added here
document.getElementById('file-upload').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 0) {
        console.log('Files selected:', files);
        // Add file upload logic here
    }
});
</script>
@endsection
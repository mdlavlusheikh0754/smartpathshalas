@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">রিপোর্ট সেন্টার</h1>
        <p class="text-gray-600 mt-1">বিভিন্ন ধরনের রিপোর্ট দেখুন এবং ডাউনলোড করুন</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('tenant.reports.students') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">শিক্ষার্থী রিপোর্ট</h3>
            <p class="text-gray-600 text-sm">শিক্ষার্থীদের সম্পূর্ণ তথ্য এবং পরিসংখ্যান</p>
        </a>

        <a href="{{ route('tenant.reports.attendance') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h6"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">উপস্থিতি রিপোর্ট</h3>
            <p class="text-gray-600 text-sm">দৈনিক এবং মাসিক উপস্থিতি বিশ্লেষণ</p>
        </a>

        <a href="{{ route('tenant.reports.fees') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">ফি রিপোর্ট</h3>
            <p class="text-gray-600 text-sm">ফি সংগ্রহ এবং বকেয়া বিশ্লেষণ</p>
        </a>

        <a href="{{ route('tenant.reports.exams') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">পরীক্ষা রিপোর্ট</h3>
            <p class="text-gray-600 text-sm">পরীক্ষার ফলাফল এবং বিশ্লেষণ</p>
        </a>
    </div>
</div>
@endsection

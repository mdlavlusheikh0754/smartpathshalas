@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">লাইব্রেরি ম্যানেজমেন্ট</h1>
        <p class="text-gray-600 mt-1">বই এবং লাইব্রেরি সংক্রান্ত সকল কার্যক্রম পরিচালনা করুন</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm">মোট বই</p>
            <h3 class="text-3xl font-bold mt-1">{{ $stats['total_books'] }}</h3>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm">ইস্যুকৃত</p>
            <h3 class="text-3xl font-bold mt-1">{{ $stats['issued_books'] }}</h3>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-orange-100 text-sm">বিলম্বিত</p>
            <h3 class="text-3xl font-bold mt-1">{{ $stats['overdue_books'] }}</h3>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-purple-100 text-sm">উপলব্ধ</p>
            <h3 class="text-3xl font-bold mt-1">{{ $stats['available_books'] }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('tenant.library.books') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">বই তালিকা</h3>
            <p class="text-gray-600 text-sm">সকল বইয়ের তথ্য দেখুন এবং পরিচালনা করুন</p>
        </a>

        <a href="{{ route('tenant.library.issue') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">বই ইস্যু</h3>
            <p class="text-gray-600 text-sm">শিক্ষার্থীদের বই ইস্যু করুন</p>
        </a>

        <a href="{{ route('tenant.library.return') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">বই রিটার্ন</h3>
            <p class="text-gray-600 text-sm">ইস্যুকৃত বই ফেরত নিন</p>
        </a>
    </div>
</div>
@endsection

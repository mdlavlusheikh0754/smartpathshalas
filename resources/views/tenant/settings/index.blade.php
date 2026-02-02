@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">সেটিংস</h1>
        <p class="text-gray-600 mt-1">স্কুলের সেটিংস এবং কনফিগারেশন পরিচালনা করুন</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('tenant.settings.school') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">স্কুল সেটিংস</h3>
            <p class="text-gray-600 text-sm">স্কুলের মৌলিক তথ্য এবং কনফিগারেশন</p>
        </a>

        <a href="{{ route('tenant.settings.website') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">ওয়েবসাইট সেটিংস</h3>
            <p class="text-gray-600 text-sm">ল্যান্ডিং পেজ কাস্টমাইজ করুন</p>
        </a>

        <a href="{{ route('tenant.settings.academic') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">একাডেমিক সেশন</h3>
            <p class="text-gray-600 text-sm">শিক্ষাবর্ষ এবং সেশন পরিচালনা</p>
        </a>

        <a href="{{ route('tenant.settings.users') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">ইউজার ম্যানেজমেন্ট</h3>
            <p class="text-gray-600 text-sm">ইউজার এবং পারমিশন পরিচালনা</p>
        </a>

        <a href="{{ route('tenant.settings.feeStructure') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">ফি স্ট্রাকচার</h3>
            <p class="text-gray-600 text-sm">ফি এর ধরন এবং পরিমাণ নির্ধারণ</p>
        </a>

        <a href="{{ route('tenant.settings.grade') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">গ্রেড সেটিংস</h3>
            <p class="text-gray-600 text-sm">গ্রেড এবং মার্কস কনফিগারেশন</p>
        </a>

        <a href="{{ route('tenant.settings.notification') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-pink-500 to-rose-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">নোটিফিকেশন</h3>
            <p class="text-gray-600 text-sm">নোটিফিকেশন সেটিংস</p>
        </a>

        <a href="{{ route('tenant.settings.smsGateway') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">এসএমএস গেটওয়ে</h3>
            <p class="text-gray-600 text-sm">এসএমএস সার্ভিস কনফিগারেশন</p>
        </a>

        <a href="{{ route('tenant.settings.paymentGateway') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">পেমেন্ট গেটওয়ে</h3>
            <p class="text-gray-600 text-sm">অনলাইন পেমেন্ট সেটআপ</p>
        </a>

        <a href="{{ route('tenant.settings.backup') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">ব্যাকআপ ও রিস্টোর</h3>
            <p class="text-gray-600 text-sm">ডেটা ব্যাকআপ পরিচালনা</p>
        </a>

        <a href="{{ route('tenant.settings.security') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">নিরাপত্তা</h3>
            <p class="text-gray-600 text-sm">সিকিউরিটি সেটিংস</p>
        </a>

        <a href="{{ route('tenant.settings.academic-files.index') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="bg-gradient-to-br from-blue-400 to-cyan-500 p-4 rounded-xl mb-4 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">একাডেমিক ফাইল ম্যানেজমেন্ট</h3>
            <p class="text-gray-600 text-sm">সিলেবাস, ক্যালেন্ডার এবং ছুটির দিন পরিচালনা</p>
        </a>
    </div>
</div>
@endsection

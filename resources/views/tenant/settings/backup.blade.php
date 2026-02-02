@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">ব্যাকআপ ও রিস্টোর</h1>
                <p class="text-gray-600 mt-1">ডেটাবেস ব্যাকআপ পরিচালনা করুন</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
                <button class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    নতুন ব্যাকআপ তৈরি করুন
                </button>
            </div>
        </div>

        <!-- Backup Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-blue-100 text-sm">মোট ব্যাকআপ</p>
                <h3 class="text-3xl font-bold mt-1">১৫</h3>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-green-100 text-sm">সর্বশেষ ব্যাকআপ</p>
                <h3 class="text-xl font-bold mt-1">২ ঘন্টা আগে</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-purple-100 text-sm">মোট সাইজ</p>
                <h3 class="text-3xl font-bold mt-1">২.৫ GB</h3>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-orange-100 text-sm">স্বয়ংক্রিয় ব্যাকআপ</p>
                <h3 class="text-xl font-bold mt-1">সক্রিয়</h3>
            </div>
        </div>

        <!-- Auto Backup Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">স্বয়ংক্রিয় ব্যাকআপ সেটিংস</h3>
            <form action="#" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ব্যাকআপ ফ্রিকোয়েন্সি</label>
                        <select name="frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="daily">প্রতিদিন</option>
                            <option value="weekly">সাপ্তাহিক</option>
                            <option value="monthly">মাসিক</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ব্যাকআপ সময়</label>
                        <input type="time" name="backup_time" value="02:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সংরক্ষণ সময়কাল (দিন)</label>
                        <input type="number" name="retention_days" value="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition-colors">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>

        <!-- Backup List -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">ব্যাকআপ তালিকা</h3>
            <div class="space-y-3">
                @for($i = 1; $i <= 10; $i++)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition-all">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">backup_{{ date('Y_m_d', strtotime("-$i days")) }}.sql</p>
                            <p class="text-sm text-gray-600">{{ date('d F Y, h:i A', strtotime("-$i days")) }} | সাইজ: {{ rand(50, 200) }} MB</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-4 py-2 rounded-lg font-medium transition-colors">ডাউনলোড</button>
                        <button class="bg-green-100 hover:bg-green-200 text-green-600 px-4 py-2 rounded-lg font-medium transition-colors">রিস্টোর</button>
                        <button class="bg-red-100 hover:bg-red-200 text-red-600 px-4 py-2 rounded-lg font-medium transition-colors">মুছুন</button>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection

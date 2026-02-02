@extends('layouts.tenant')

@section('title', 'ড্যাশবোর্ড')

@section('content')
<div class="p-8 min-h-screen bg-gray-50">
    
    <!-- Welcome Banner -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-4xl font-bold mb-2">স্বাগতম, {{ auth()->user()->name }}!</h2>
            <p class="text-blue-100 text-lg">{{ tenant('data')['school_name'] ?? tenant('id') }} - স্কুল ম্যানেজমেন্ট সিস্টেম</p>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        
        <!-- Total Students Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">মোট</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">০</h3>
                <p class="text-blue-100 text-sm font-medium">সক্রিয় শিক্ষার্থী</p>
            </div>
        </div>

        <!-- Active Classes Card -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">সক্রিয়</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">০</h3>
                <p class="text-green-100 text-sm font-medium">চলমান ক্লাস</p>
            </div>
        </div>

        <!-- Attendance Rate Card -->
        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">হার</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">০%</h3>
                <p class="text-orange-100 text-sm font-medium">উপস্থিতি হার</p>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">মোট</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">০</h3>
                <p class="text-purple-100 text-sm font-medium">সক্রিয় শিক্ষক</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">সাম্প্রতিক কার্যকলাপ</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-lg font-medium">কোনো সাম্প্রতিক কার্যকলাপ নেই</p>
                    <p class="text-sm text-gray-400 mt-1">সিস্টেমে কার্যক্রম শুরু করলে এখানে দেখা যাবে</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-6">দ্রুত ক্রিয়া</h3>
            <div class="space-y-3">
                <button class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    নতুন ক্লাস যোগ করুন
                </button>
                <button class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    শিক্ষার্থী নিবন্ধন করুন
                </button>
                <button class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    পরীক্ষা সময়সূচী তৈরি করুন
                </button>
                <button class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    রিপোর্ট ডাউনলোড করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="mt-8 bg-white rounded-3xl shadow-xl p-8 border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            আসন্ন ইভেন্ট
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center py-8 text-gray-500 md:col-span-3">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-lg font-medium">কোনো আসন্ন ইভেন্ট নেই</p>
                <p class="text-sm text-gray-400 mt-1">ইভেন্ট যোগ করলে এখানে দেখা যাবে</p>
            </div>
        </div>
    </div>
</div>
@endsection

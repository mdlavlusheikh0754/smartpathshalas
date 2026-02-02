@extends('layouts.admin')

@section('title', 'স্কুল বিস্তারিত')

@section('content')
<div class="p-8">
    <!-- Header with Back Button -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('central.schools') }}" class="p-3 bg-white hover:bg-gray-50 rounded-xl shadow-md transition-all duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $tenant->data['school_name'] ?? $tenant->id }}</h1>
                <p class="text-gray-600">স্কুল বিস্তারিত তথ্য</p>
            </div>
        </div>
        
        <!-- Lock/Unlock Button -->
        <button onclick="toggleLock()" id="lockButton" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105 {{ $tenant->is_locked ? 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white' : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white' }}">
            <svg id="lockIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($tenant->is_locked)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                @endif
            </svg>
            <span id="lockText" class="text-base">{{ $tenant->is_locked ? 'স্কুল লক করা আছে' : 'স্কুল আনলক আছে' }}</span>
        </button>
    </div>

    <!-- Status Alert -->
    @if($tenant->is_locked)
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p class="font-bold text-red-800">এই স্কুল বর্তমানে লক করা আছে</p>
                    <p class="text-sm text-red-600">স্কুলের ব্যবহারকারীরা লগইন করতে পারবে না</p>
                </div>
            </div>
        </div>
    @else
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-bold text-green-800">এই স্কুল সক্রিয় আছে</p>
                    <p class="text-sm text-green-600">স্কুলের ব্যবহারকারীরা স্বাভাবিকভাবে লগইন করতে পারবে</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
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
                <h3 class="text-5xl font-bold mb-2">{{ $tenant->data['total_students'] ?? '০' }}</h3>
                <p class="text-blue-100 text-sm font-medium">সক্রিয় শিক্ষার্থী</p>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">শিক্ষক</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">{{ $tenant->data['total_teachers'] ?? '০' }}</h3>
                <p class="text-green-100 text-sm font-medium">সক্রিয় শিক্ষক</p>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">আয়</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">৳{{ number_format($tenant->data['revenue'] ?? 0) }}</h3>
                <p class="text-orange-100 text-sm font-medium">মোট আয়</p>
            </div>
        </div>

        <!-- Package Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">প্ল্যান</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">{{ $tenant->data['package'] ?? 'বেসিক' }}</h3>
                <p class="text-purple-100 text-sm font-medium">সক্রিয় প্যাকেজ</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    মূল তথ্য
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-start py-4 border-b border-gray-100">
                        <div class="w-1/3 font-semibold text-gray-700">স্কুলের নাম:</div>
                        <div class="w-2/3 text-gray-900">{{ $tenant->data['school_name'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="flex items-start py-4 border-b border-gray-100">
                        <div class="w-1/3 font-semibold text-gray-700">স্কুল আইডি:</div>
                        <div class="w-2/3 text-gray-900 font-mono bg-gray-50 px-3 py-1 rounded">{{ $tenant->id }}</div>
                    </div>
                    
                    <div class="flex items-start py-4 border-b border-gray-100">
                        <div class="w-1/3 font-semibold text-gray-700">ইমেইল:</div>
                        <div class="w-2/3 text-gray-900">{{ $tenant->data['email'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="flex items-start py-4 border-b border-gray-100">
                        <div class="w-1/3 font-semibold text-gray-700">ফোন:</div>
                        <div class="w-2/3 text-gray-900">{{ $tenant->data['phone'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="flex items-start py-4 border-b border-gray-100">
                        <div class="w-1/3 font-semibold text-gray-700">প্রধান শিক্ষক:</div>
                        <div class="w-2/3 text-gray-900">{{ $tenant->data['principal_name'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="flex items-start py-4 border-b border-gray-100">
                        <div class="w-1/3 font-semibold text-gray-700">ছাত্র ধারণক্ষমতা:</div>
                        <div class="w-2/3 text-gray-900">{{ $tenant->data['capacity'] ?? 'N/A' }} জন</div>
                    </div>
                    
                    <div class="flex items-start py-4">
                        <div class="w-1/3 font-semibold text-gray-700">ঠিকানা:</div>
                        <div class="w-2/3 text-gray-900">{{ $tenant->data['address'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Domain Card -->
            <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    ডোমেইন
                </h3>
                @foreach($tenant->domains as $domain)
                    <a href="http://{{ $domain->domain }}" target="_blank" class="block bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 p-4 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-indigo-600 break-all">{{ $domain->domain }}</span>
                            <svg class="w-5 h-5 text-indigo-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    স্ট্যাটাস
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">অবস্থা:</span>
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $tenant->is_locked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $tenant->is_locked ? 'লক করা' : 'সক্রিয়' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">তৈরির তারিখ:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $tenant->created_at->format('d M, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">সময়:</span>
                        <span class="text-sm text-gray-600">{{ $tenant->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    অ্যাকশন
                </h3>
                <div class="space-y-2">
                    <button class="w-full flex items-center gap-2 px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        এডিট করুন
                    </button>
                    <button onclick="printSchoolInfo()" class="w-full flex items-center gap-2 px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        প্রিন্ট করুন
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- School Admin Section -->
    <div class="mt-8 bg-white rounded-3xl shadow-xl p-8 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                স্কুল অ্যাডমিন
            </h2>
            <button onclick="openAdminModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>নতুন অ্যাডমিন তৈরি করুন</span>
            </button>
        </div>

        <!-- Admin List -->
        <div id="adminList" class="space-y-3">
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="font-semibold">কোনো অ্যাডমিন পাওয়া যায়নি</p>
                <p class="text-sm mt-1">এই স্কুলের জন্য অ্যাডমিন তৈরি করুন</p>
            </div>
        </div>
    </div>

    <!-- Admin Creation Modal -->
    <div id="adminModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white px-8 py-6 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        নতুন স্কুল অ্যাডমিন তৈরি করুন
                    </h3>
                    <button onclick="closeAdminModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                <form id="createAdminForm" class="space-y-5">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">নাম *</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ইমেইল *</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">পাসওয়ার্ড *</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">পাসওয়ার্ড নিশ্চিত করুন *</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">ফোন</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>অ্যাডমিন তৈরি করুন</span>
                        </button>
                        <button type="button" onclick="closeAdminModal()" class="px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition-all duration-300">
                            বাতিল
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let isLocked = {{ $tenant->is_locked ? 'true' : 'false' }};
const tenantId = '{{ $tenant->id }}';

function openAdminModal() {
    document.getElementById('adminModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAdminModal() {
    document.getElementById('adminModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('createAdminForm').reset();
}

// Close modal when clicking outside
document.getElementById('adminModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAdminModal();
    }
});

// Create Admin
document.getElementById('createAdminForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        password_confirmation: formData.get('password_confirmation'),
        phone: formData.get('phone'),
        tenant_id: tenantId
    };
    
    // Disable submit button
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    
    fetch(`/admin/api/schools/${tenantId}/create-admin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Show login credentials in custom modal
            if (data.admin) {
                showLoginCredentials(data.admin);
            }
            
            closeAdminModal();
            loadAdminList();
        } else {
            showNotification(data.message || 'সমস্যা হয়েছে', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    });
});

// Load Admin List
function loadAdminList() {
    fetch(`/admin/api/schools/${tenantId}/admins`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const adminList = document.getElementById('adminList');
        
        if (data.success && data.admins.length > 0) {
            adminList.innerHTML = data.admins.map(admin => `
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg flex-shrink-0">
                                ${admin.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="font-bold text-gray-900 text-lg">${admin.name}</h4>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                        অ্যাডমিন
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="break-all">${admin.email}</span>
                                    </p>
                                    ${admin.phone ? `
                                        <p class="text-sm text-gray-600 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span>${admin.phone}</span>
                                        </p>
                                    ` : ''}
                                    <p class="text-sm text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                        <span class="font-mono bg-purple-50 px-2 py-1 rounded">${admin.plain_password || 'N/A'}</span>
                                        ${admin.plain_password && admin.plain_password !== 'N/A' ? `
                                            <button onclick="copyPassword('${admin.plain_password}')" class="text-purple-600 hover:text-purple-700" title="কপি করুন">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        ` : ''}
                                    </p>
                                    <p class="text-xs text-gray-500 flex items-center gap-2 mt-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        তৈরি: ${admin.created_at}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button onclick="resetPassword(${admin.id}, '${admin.name}')" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200" title="পাসওয়ার্ড রিসেট">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteAdmin(${admin.id}, '${admin.name}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" title="ডিলিট">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            adminList.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="font-semibold">কোনো অ্যাডমিন পাওয়া যায়নি</p>
                    <p class="text-sm mt-1">এই স্কুলের জন্য অ্যাডমিন তৈরি করুন</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading admins:', error);
    });
}

// Delete Admin
function deleteAdmin(adminId, adminName) {
    showDeleteConfirmation(adminId, adminName);
}

function showDeleteConfirmation(adminId, adminName) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    overlay.id = 'deleteConfirmModal';
    
    // Create modal content
    overlay.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full animate-fade-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-6 rounded-t-3xl">
                <div class="flex items-center gap-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold">নিশ্চিত করুন</h3>
                </div>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <p class="text-lg text-gray-900 mb-6">
                    আপনি কি নিশ্চিত যে <span class="font-bold text-red-600">"${adminName}"</span> কে ডিলিট করতে চান?
                </p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm text-yellow-800">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="confirmDeleteAdmin(${adminId})" class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        হ্যাঁ, ডিলিট করুন
                    </button>
                    <button onclick="closeDeleteConfirmation()" class="flex-1 px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition-all duration-300">
                        বাতিল
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
}

function closeDeleteConfirmation() {
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

function confirmDeleteAdmin(adminId) {
    closeDeleteConfirmation();
    
    fetch(`/admin/api/schools/${tenantId}/admins/${adminId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            loadAdminList();
        } else {
            showNotification(data.message || 'ডিলিট করতে সমস্যা হয়েছে', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন', 'error');
    });
}

// Reset Password
function resetPassword(adminId, adminName) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    overlay.id = 'resetPasswordModal';
    
    // Create modal content
    overlay.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full animate-fade-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-6 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold">পাসওয়ার্ড রিসেট</h3>
                    </div>
                    <button onclick="closeResetPasswordModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <p class="text-gray-700 mb-4">
                    <span class="font-bold">${adminName}</span> এর জন্য নতুন পাসওয়ার্ড সেট করুন
                </p>
                
                <form id="resetPasswordForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">নতুন পাসওয়ার্ড *</label>
                        <input type="password" id="newPassword" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">পাসওয়ার্ড নিশ্চিত করুন *</label>
                        <input type="password" id="confirmPassword" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            পাসওয়ার্ড রিসেট করুন
                        </button>
                        <button type="button" onclick="closeResetPasswordModal()" class="px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition-all duration-300">
                            বাতিল
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    
    // Handle form submission
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            showNotification('পাসওয়ার্ড মিলছে না', 'error');
            return;
        }
        
        if (newPassword.length < 6) {
            showNotification('পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে', 'error');
            return;
        }
        
        // Disable submit button
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        fetch(`/admin/api/schools/${tenantId}/admins/${adminId}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: newPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                closeResetPasswordModal();
            } else {
                showNotification(data.message || 'পাসওয়ার্ড রিসেট করতে সমস্যা হয়েছে', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    });
}

function closeResetPasswordModal() {
    const modal = document.getElementById('resetPasswordModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

function copyPassword(password) {
    navigator.clipboard.writeText(password).then(() => {
        showNotification('পাসওয়ার্ড কপি করা হয়েছে', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showNotification('কপি করতে সমস্যা হয়েছে', 'error');
    });
}

// Load admin list on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAdminList();
});

function toggleLock() {
    const button = document.getElementById('lockButton');
    const icon = document.getElementById('lockIcon');
    const text = document.getElementById('lockText');
    
    // Disable button during request
    button.disabled = true;
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    fetch(`/admin/api/schools/${tenantId}/toggle-lock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            isLocked = data.is_locked;
            
            // Update button appearance
            if (isLocked) {
                button.classList.remove('from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700');
                button.classList.add('from-red-500', 'to-red-600', 'hover:from-red-600', 'hover:to-red-700');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>';
                text.textContent = 'স্কুল লক করা আছে';
            } else {
                button.classList.remove('from-red-500', 'to-red-600', 'hover:from-red-600', 'hover:to-red-700');
                button.classList.add('from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>';
                text.textContent = 'স্কুল আনলক আছে';
            }
            
            // Show notification
            showNotification(data.message, 'success');
            
            // Reload page after 1 second to update status alert
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showLoginCredentials(admin) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    overlay.id = 'credentialsModal';
    
    // Create modal content
    overlay.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full animate-fade-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-6 rounded-t-3xl">
                <div class="flex items-center gap-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold">অ্যাডমিন তৈরি সফল হয়েছে!</h3>
                </div>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <p class="text-lg font-bold text-gray-900 mb-6">লগইন তথ্য:</p>
                
                <div class="space-y-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-600">ইমেইল:</p>
                            <p class="text-lg font-bold text-gray-900 break-all">${admin.email}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-600">নাম:</p>
                            <p class="text-lg font-bold text-gray-900">${admin.name}</p>
                        </div>
                    </div>
                </div>
                
                <p class="text-sm text-gray-600 mt-6 text-center bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <svg class="w-5 h-5 text-yellow-600 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    এই তথ্য দিয়ে স্কুলে লগইন করতে পারবেন।
                </p>
                
                <button onclick="closeCredentialsModal()" class="w-full mt-6 px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    ঠিক আছে
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
}

function closeCredentialsModal() {
    const modal = document.getElementById('credentialsModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function printSchoolInfo() {
    window.print();
}
</script>

<style>
@media print {
    .no-print, nav, aside, button {
        display: none !important;
    }
}
</style>
@endpush
@endsection

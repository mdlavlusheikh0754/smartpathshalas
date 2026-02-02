<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\SchoolSetting::getSettings()->school_name_bn ?? (tenancy()->initialized && tenant('data') ? tenant('data')['school_name'] : tenant('id')) }} - {{ config('app.name', 'Smart Pathshala') }}</title>

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bengali Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Additional Bengali Fonts for Premium Look -->
        <link href="https://fonts.maateen.me/adorsho-lipi/font.css" rel="stylesheet">
        <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
        <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            /* Default font - SolaimanLipi for general text */
            * { 
                font-family: 'SolaimanLipi', 'Noto Sans Bengali', 'Figtree', sans-serif; 
            }
            
            /* Banner & Title Font - Bold and Premium Look */
            h1, h2, h3, .banner-title, .page-title, .hero-title, .section-title {
                font-family: 'AdorshoLipi', 'Noto Sans Bengali', sans-serif !important;
                font-weight: 700;
            }
            
            /* Result & ID Card Font - Clear at small sizes */
            .result-text, .id-card-text, .certificate-text, .mark-sheet, table.result-table {
                font-family: 'SolaimanLipi', 'Nikosh', 'Noto Sans Bengali', sans-serif !important;
                font-weight: 400;
            }
            
            /* General Text & Notice Font - Easy to read for long text */
            .notice-text, .description, .content-text, p, .long-text {
                font-family: 'SolaimanLipi', 'Noto Sans Bengali', sans-serif !important;
                font-weight: 400;
                line-height: 1.7;
            }
            
            /* Bengali text support */
            .bengali-text, [lang="bn"] {
                font-family: 'SolaimanLipi', 'Noto Sans Bengali', 'Kalpurush', 'Nikosh', 'Siyam Rupali', 'Mukti', Arial, sans-serif !important;
                font-feature-settings: "liga" 1, "calt" 1, "kern" 1;
                text-rendering: optimizeLegibility;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                unicode-bidi: bidi-override;
                direction: ltr;
                writing-mode: horizontal-tb;
            }
            
            /* Unicode normalization for Bengali input */
            input[lang="bn"], textarea[lang="bn"], .bengali-text {
                ime-mode: active;
                -ms-ime-mode: active;
                font-variant-ligatures: normal;
                font-feature-settings: "liga" 1, "calt" 1;
            }
            
            /* Better Bengali rendering */
            .bengali-text::placeholder {
                font-family: 'SolaimanLipi', 'Noto Sans Bengali', 'Kalpurush', Arial, sans-serif;
                opacity: 0.6;
            }
            
            /* Font size adjustments for better readability */
            .result-text, .id-card-text {
                font-size: 0.875rem; /* 14px */
            }
            
            .notice-text, .description {
                font-size: 1rem; /* 16px */
            }
            
            h1 { font-size: 2.25rem; } /* 36px */
            h2 { font-size: 1.875rem; } /* 30px */
            h3 { font-size: 1.5rem; } /* 24px */
        </style>
        
        @stack('styles')
    </head>
    <body class="bg-gray-50">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-gradient-to-b from-indigo-600 to-purple-700 text-white flex-shrink-0 flex flex-col">
                <!-- Logo -->
                <div class="p-6 border-b border-indigo-500">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-sm font-bold leading-tight">{{ \App\Models\SchoolSetting::getSettings()->school_name_bn ?? (tenant('data')['school_name'] ?? tenant('id')) }}</h1>
                            <p class="text-xs text-indigo-200">স্কুল ম্যানেজমেন্ট</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('tenant.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.dashboard') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">ড্যাশবোর্ড</span>
                    </a>

                    <!-- Students -->
                    <a href="{{ route('tenant.students.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.students.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">শিক্ষার্থী</span>
                    </a>

                    <!-- Teachers -->
                    <a href="{{ route('tenant.teachers.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.teachers.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">শিক্ষক</span>
                    </a>

                    <!-- Classes -->
                    <a href="{{ route('tenant.classes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.classes.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium">ক্লাস</span>
                    </a>

                    <!-- Subjects -->
                    <a href="{{ route('tenant.subjects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.subjects.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="font-medium">বিষয়</span>
                    </a>

                    <!-- Routine -->
                    <a href="{{ route('tenant.routine.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.routine.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">রুটিন</span>
                    </a>

                    <!-- Homework -->
                    <a href="{{ route('tenant.homework.manage') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.homework.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">বাড়ির কাজ</span>
                    </a>

                    <!-- Exams -->
                    <a href="{{ route('tenant.exams.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.exams.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="font-medium">পরীক্ষা</span>
                    </a>

                    <!-- Attendance -->
                    <a href="{{ route('tenant.attendance.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.attendance.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h6"></path>
                        </svg>
                        <span class="font-medium">উপস্থিতি</span>
                    </a>

                    <!-- Fee Management -->
                    <a href="{{ route('tenant.fees.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.fees.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">ফি ম্যানেজমেন্ট</span>
                    </a>

                    <!-- Inventory -->
                    <a href="{{ route('tenant.inventory.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.inventory.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="font-medium">ইনভেন্টরি</span>
                    </a>

                    <!-- Notice -->
                    <a href="{{ route('tenant.notices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.notices.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="font-medium">নোটিশ</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('tenant.reports.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.reports.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">রিপোর্ট</span>
                    </a>

                    <!-- Library -->
                    <a href="{{ route('tenant.library.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.library.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="font-medium">লাইব্রেরি</span>
                    </a>

                    <!-- Hostel -->
                    <a href="{{ route('tenant.hostel.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.hostel.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">হোস্টেল</span>
                    </a>

                    <!-- Transport -->
                    <a href="{{ route('tenant.transport.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.transport.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span class="font-medium">ট্রান্সপোর্ট</span>
                    </a>

                    <!-- Grants -->
                    <a href="{{ route('tenant.grants.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.grants.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">অনুদান</span>
                    </a>

                    <!-- Complaints -->
                    <a href="{{ route('tenant.complaints.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.complaints.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="font-medium">অভিযোগ</span>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('tenant.settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenant.settings.*') ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }} transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">সেটিংস</span>
                    </a>
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-indigo-500">
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all w-full text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="font-medium">লগআউট</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-8 py-4">
                        <!-- Mobile Menu Button -->
                        <button class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Search Bar -->
                        <div class="hidden md:flex flex-1 max-w-md">
                            <div class="relative w-full">
                                <input type="text" placeholder="অনুসন্ধান করুন..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="relative p-2 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- User Menu -->
                            <div class="flex items-center space-x-3">
                                <div class="text-right hidden md:block">
                                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">স্কুল অ্যাডমিন</p>
                                </div>
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50">
                    <!-- Flash Messages -->
                    @if(session('success'))
                    <div class="mx-8 mt-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md" id="successMessage">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            <button onclick="document.getElementById('successMessage').remove()" class="ml-auto text-green-500 hover:text-green-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mx-8 mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md" id="errorMessage">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            <button onclick="document.getElementById('errorMessage').remove()" class="ml-auto text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mx-8 mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md" id="validationErrors">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-red-800 font-medium mb-2">দয়া করে নিম্নলিখিত ত্রুটিগুলি সংশোধন করুন:</p>
                                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button onclick="document.getElementById('validationErrors').remove()" class="ml-3 text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Bengali Utilities -->
        <script src="/js/bengali-utils.js"></script>
        
        @stack('scripts')
        
        <!-- Auto-hide flash messages after 5 seconds -->
        <script>
            setTimeout(function() {
                const successMsg = document.getElementById('successMessage');
                if (successMsg) {
                    successMsg.style.transition = 'opacity 0.5s';
                    successMsg.style.opacity = '0';
                    setTimeout(() => successMsg.remove(), 500);
                }
            }, 5000);
        </script>

        <!-- Bangla Number Conversion Script -->
        <script>
            // English to Bangla number mapping
            const englishToBangla = {
                '0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪',
                '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'
            };

            // Bangla to English number mapping
            const banglaToEnglish = {
                '০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4',
                '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'
            };

            // Convert English numbers to Bangla
            function convertToBangla(text) {
                return text.replace(/[0-9]/g, function(match) {
                    return englishToBangla[match] || match;
                });
            }

            // Convert Bangla numbers to English (for form submission)
            function convertToEnglish(text) {
                return text.replace(/[০-৯]/g, function(match) {
                    return banglaToEnglish[match] || match;
                });
            }

            // Add event listeners to input fields
            document.addEventListener('DOMContentLoaded', function() {
                // Convert type="number" fields to text type for Bangla support
                const numberFields = document.querySelectorAll('input[type="number"]:not([data-no-bangla])');
                numberFields.forEach(function(field) {
                    // Skip if it's for dates, times, years, counts, settings, or other non-bangla numbers
                    if (field.name.includes('date') || 
                        field.name.includes('time') || 
                        field.name.includes('year') ||
                        field.name.includes('academic_year') ||
                        field.name.includes('students') ||
                        field.name.includes('teachers') ||
                        field.name.includes('subjects') ||
                        field.name.includes('participants') ||
                        field.name.includes('count') ||
                        field.name.includes('marks') ||
                        field.name.includes('gpa') ||
                        field.name.includes('length') ||
                        field.name.includes('expiry') ||
                        field.name.includes('timeout') ||
                        field.name.includes('attempts') ||
                        field.name.includes('duration') ||
                        field.name.includes('retention') ||
                        field.hasAttribute('data-no-bangla')) {
                        return;
                    }
                    
                    // Store original attributes
                    const min = field.getAttribute('min');
                    const max = field.getAttribute('max');
                    const step = field.getAttribute('step');
                    const placeholder = field.getAttribute('placeholder');
                    
                    // Change type to text to support Bangla numbers
                    field.type = 'text';
                    field.inputMode = 'numeric'; // Show numeric keyboard on mobile
                    
                    // Restore numeric attributes as data attributes for validation
                    if (min !== null) field.setAttribute('data-min', min);
                    if (max !== null) field.setAttribute('data-max', max);
                    if (step !== null) field.setAttribute('data-step', step);
                    
                    console.log('Converted number field to text:', field.name);
                });

                // Find all fields that should support Bangla numbers
                const banglaNumberFields = document.querySelectorAll(`
                    input[name*="mobile"], 
                    input[name*="phone"], 
                    input[name*="nid"], 
                    input[name*="birth_certificate"], 
                    input[name*="roll"], 
                    input[name*="student_id"], 
                    input[name*="teacher_id"], 
                    input[name*="account"],
                    input[inputmode="numeric"],
                    input[name*="salary"],
                    input[name*="experience"],
                    input[name*="income"]
                `);
                
                banglaNumberFields.forEach(function(field) {
                    // Skip if it's still a number type input or has no-bangla attribute
                    if (field.type === 'number' || field.hasAttribute('data-no-bangla')) {
                        return;
                    }
                    
                    // Convert to Bangla on input
                    field.addEventListener('input', function(e) {
                        const cursorPosition = e.target.selectionStart;
                        let value = e.target.value;
                        
                        // For numeric fields, remove non-numeric characters
                        if (e.target.inputMode === 'numeric' || 
                            e.target.name.includes('mobile') || 
                            e.target.name.includes('phone') || 
                            e.target.name.includes('nid') || 
                            e.target.name.includes('roll') || 
                            e.target.name.includes('salary') || 
                            e.target.name.includes('experience') || 
                            e.target.name.includes('income')) {
                            value = value.replace(/[^0-9০-৯]/g, '');
                        }
                        
                        const banglaValue = convertToBangla(value);
                        
                        if (e.target.value !== banglaValue) {
                            e.target.value = banglaValue;
                            // Restore cursor position safely
                            try {
                                if (e.target.setSelectionRange && 
                                    (e.target.type === 'text' || e.target.type === 'tel')) {
                                    e.target.setSelectionRange(cursorPosition, cursorPosition);
                                }
                            } catch (err) {
                                // Ignore cursor position errors
                                console.log('Cursor position error (ignored):', err.message);
                            }
                        }
                    });

                    // Handle paste events
                    field.addEventListener('paste', function(e) {
                        setTimeout(function() {
                            let value = field.value;
                            // Clean and convert for numeric fields
                            if (field.inputMode === 'numeric' || 
                                field.name.includes('mobile') || 
                                field.name.includes('phone') || 
                                field.name.includes('nid') || 
                                field.name.includes('roll') || 
                                field.name.includes('salary') || 
                                field.name.includes('experience') || 
                                field.name.includes('income')) {
                                value = value.replace(/[^0-9০-৯]/g, '');
                            }
                            field.value = convertToBangla(value);
                        }, 10);
                    });

                    console.log('Added Bangla number support to:', field.name);
                });

                // Convert to English before form submission
                document.querySelectorAll('form').forEach(function(form) {
                    form.addEventListener('submit', function() {
                        banglaNumberFields.forEach(function(field) {
                            if (field.value) {
                                field.value = convertToEnglish(field.value);
                            }
                        });
                    });
                });

                console.log('✅ Bangla number conversion initialized');
            });

            // Global functions for manual conversion
            window.toBanglaNumber = convertToBangla;
            window.toEnglishNumber = convertToEnglish;
        </script>
    </body>
</html>

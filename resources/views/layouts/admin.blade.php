<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'স্মার্ট পাঠশালা') }} - অ্যাডমিন</title>

    <!-- SolaimanLipi Font -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    
    <style>
        *:not(i):not(.fa*):not(.fab):not(.fas):not(.far):not(.fal):not(.fad):not(.fat) {
            font-family: 'SolaimanLipi', sans-serif !important;
            font-weight: 400;
        }
        
        h1, h2, h3, h4, h5, h6, strong, b, .font-bold, .font-semibold {
            font-weight: 700 !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-purple-50 to-pink-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white flex-shrink-0 flex flex-col shadow-2xl relative overflow-hidden transition-all duration-300 transform">
            <!-- Modern Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12 blur-xl"></div>
            <div class="absolute top-1/4 right-0 w-16 h-16 bg-white/5 rounded-full -mr-8 blur-lg"></div>
            
            <!-- Modern Logo -->
            <div class="p-6 relative z-10 border-b border-white/10">
                <a href="{{ route('central.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-all duration-300 group-hover:bg-white/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">স্মার্ট পাঠশালা</h1>
                        <p class="text-xs text-white/80 font-medium">Admin Dashboard</p>
                    </div>
                </a>
            </div>

            <!-- Modern Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto relative z-10">
                <a href="{{ route('central.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('central.dashboard') ? 'bg-white/20 backdrop-blur-md text-white shadow-lg font-semibold border border-white/10' : 'text-white/90 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md' }} transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">ড্যাশবোর্ড</span>
                </a>

                <a href="{{ route('central.schools') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('central.schools') ? 'bg-white/20 backdrop-blur-md text-white shadow-lg font-semibold border border-white/10' : 'text-white/90 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md' }} transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-medium">স্কুল ম্যানেজমেন্ট</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">অভিভাবক</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="font-medium">লাইব্রেরি</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">বিলিং</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="font-medium">রিপোর্ট</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="font-medium">নোটিশ</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium">সেটিংস</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-white/80 hover:bg-white/10 hover:backdrop-blur-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">সাহায্য সেন্টার</span>
                </a>
            </nav>

            <!-- Modern User Info -->
            <div class="p-4 border-t border-white/10 relative z-10">
                <div class="flex items-center space-x-3 px-4 py-3 bg-white/10 backdrop-blur-md rounded-2xl hover:bg-white/20 transition-all duration-300 cursor-pointer group">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg ring-2 ring-white/30 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-white/80 truncate flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                            সুপার এডমিন
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white/80 backdrop-blur-xl shadow-lg border-b border-purple-100 z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Sidebar toggle button -->
                        <button onclick="toggleSidebarDesktop()" class="hidden md:block text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <!-- Mobile menu button -->
                        <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">@yield('title', 'ড্যাশবোর্ড')</h2>
                            <p class="text-sm text-gray-600 font-medium">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-300 group">
                            <svg class="w-6 h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full animate-pulse ring-2 ring-white"></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative" id="profileDropdown">
                            <button onclick="toggleDropdown()" class="flex items-center space-x-3 p-2 hover:bg-purple-50 rounded-xl transition-all duration-300 group">
                                <div class="w-9 h-9 bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-purple-200 group-hover:ring-purple-300 transform group-hover:scale-105 transition-all">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-600 transform group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="dropdownMenu" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl py-2 z-50 border border-purple-100" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    প্রোফাইল
                                </a>
                                <a href="/" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    মেইন সাইট
                                </a>
                                <hr class="my-2 border-purple-100">
                                
                                <!-- Simple Logout Form -->
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        লগ আউট
                                    </button>
                                </form>
                                
                                <!-- Alternative Direct Logout Link -->
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('directLogoutForm').submit();" class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    লগ আউট (Direct)
                                </a>
                                <form id="directLogoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 via-purple-50 to-pink-50">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        function toggleSidebarDesktop() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            
            // Toggle collapsed state
            const isCollapsed = sidebar.classList.contains('w-20');
            
            // Hide/show all text elements
            const allTexts = sidebar.querySelectorAll('span:not(.sr-only), p, h1');
            allTexts.forEach(element => {
                if (isCollapsed) {
                    element.classList.add('hidden');
                } else {
                    element.classList.remove('hidden');
                }
            });
            
            // Adjust navigation items for collapsed state
            const navItems = sidebar.querySelectorAll('nav a');
            navItems.forEach(item => {
                if (isCollapsed) {
                    item.classList.add('justify-center');
                    item.classList.remove('space-x-3');
                } else {
                    item.classList.remove('justify-center');
                    item.classList.add('space-x-3');
                }
            });
            
            // Adjust logo for collapsed state
            const logo = sidebar.querySelector('.flex.items-center.space-x-3');
            if (logo) {
                if (isCollapsed) {
                    logo.classList.add('justify-center');
                    logo.classList.remove('space-x-3');
                } else {
                    logo.classList.remove('justify-center');
                    logo.classList.add('space-x-3');
                }
            }
            
            // Adjust user info for collapsed state
            const userInfo = sidebar.querySelector('.flex.items-center.space-x-3');
            if (userInfo) {
                if (isCollapsed) {
                    userInfo.classList.add('justify-center');
                    userInfo.classList.remove('space-x-3');
                } else {
                    userInfo.classList.remove('justify-center');
                    userInfo.classList.add('space-x-3');
                }
            }
        }

        // Profile dropdown toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const isVisible = dropdown.style.display === 'block';
            
            // Close all dropdowns
            document.querySelectorAll('[id$="Menu"]').forEach(menu => {
                menu.style.display = 'none';
            });
            
            // Toggle current dropdown
            dropdown.style.display = isVisible ? 'none' : 'block';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (!dropdown.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        // Confirm logout
        function confirmLogout() {
            return confirm('আপনি কি নিশ্চিত লগ আউট করতে চান?');
        }
    </script>
    
    @stack('scripts')
</body>
</html>

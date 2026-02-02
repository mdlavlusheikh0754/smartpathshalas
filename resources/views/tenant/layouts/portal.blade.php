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
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            * { font-family: 'Hind Siliguri', 'Figtree', sans-serif; }
            
            /* Bengali text support */
            .bengali-text, [lang="bn"] {
                font-family: 'Hind Siliguri', 'Noto Sans Bengali', 'SolaimanLipi', 'Kalpurush', 'Nikosh', 'Siyam Rupali', 'Mukti', Arial, sans-serif !important;
                font-feature-settings: "liga" 1, "calt" 1, "kern" 1;
                text-rendering: optimizeLegibility;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                unicode-bidi: bidi-override;
                direction: ltr;
                writing-mode: horizontal-tb;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="bg-gray-50">
        @php
            $isStudent = request()->routeIs('student.*') || Auth::guard('student')->check();
            $isGuardian = request()->routeIs('guardian.*') || Auth::guard('guardian')->check();

            $userType = $isStudent ? 'student' : ($isGuardian ? 'guardian' : 'user');
            $user = $userType == 'student' ? Auth::guard('student')->user() : ($userType == 'guardian' ? Auth::guard('guardian')->user() : Auth::user());
            
            $dashboardRoute = $userType == 'student' ? 'student.dashboard' : ($userType == 'guardian' ? 'guardian.dashboard' : 'tenant.dashboard');
            $logoutUrl = $userType == 'student' ? route('student.logout') : ($userType == 'guardian' ? route('guardian.logout') : url('/logout'));
            
            $headerColor = $userType == 'student' ? 'from-blue-600 to-indigo-700' : ($userType == 'guardian' ? 'from-green-600 to-emerald-700' : 'from-indigo-600 to-purple-700');
        @endphp

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-gradient-to-b {{ $headerColor }} text-white flex-shrink-0 hidden md:flex flex-col">
                <!-- Logo -->
                <div class="p-6 border-b border-white/20">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-indigo-600">
                            @if($userType == 'student')
                                <i class="fas fa-user-graduate text-xl"></i>
                            @elseif($userType == 'guardian')
                                <i class="fas fa-users text-xl text-green-600"></i>
                            @else
                                <i class="fas fa-school text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-sm font-bold leading-tight">{{ \App\Models\SchoolSetting::getSettings()->school_name_bn ?? (tenant('data')['school_name'] ?? tenant('id')) }}</h1>
                            <p class="text-xs text-indigo-100 opacity-80">
                                @if($userType == 'student') শিক্ষার্থী প্যানেল @elseif($userType == 'guardian') অভিভাবক প্যানেল @else স্কুল ম্যানেজমেন্ট @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route($dashboardRoute) }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white/20 border-l-4 border-white transition-all">
                        <i class="fas fa-home w-5"></i>
                        <span class="font-medium">ড্যাশবোর্ড</span>
                    </a>

                    <!-- Specific Links based on User Type -->
                    @if($userType == 'student')
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all opacity-75 hover:opacity-100">
                            <i class="fas fa-book-reader w-5"></i>
                            <span class="font-medium">আমার ক্লাস</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all opacity-75 hover:opacity-100">
                            <i class="fas fa-clipboard-list w-5"></i>
                            <span class="font-medium">রুটিন</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all opacity-75 hover:opacity-100">
                            <i class="fas fa-poll w-5"></i>
                            <span class="font-medium">ফলাফল</span>
                        </a>
                    @elseif($userType == 'guardian')
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all opacity-75 hover:opacity-100">
                            <i class="fas fa-child w-5"></i>
                            <span class="font-medium">সন্তানদের তথ্য</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all opacity-75 hover:opacity-100">
                            <i class="fas fa-money-bill-wave w-5"></i>
                            <span class="font-medium">পেমেন্ট</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('tenant.home') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all opacity-75 hover:opacity-100 mt-4 border-t border-white/20">
                        <i class="fas fa-globe w-5"></i>
                        <span class="font-medium">ওয়েবসাইট ভিজিট</span>
                    </a>
                </nav>

                <!-- User Profile -->
                <div class="p-4 border-t border-white/20 bg-black/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">
                                {{ $user->name ?? ($user->student_id ?? $user->phone) }}
                            </p>
                            <p class="text-xs text-indigo-200 truncate">
                                @if($userType == 'student') ID: {{ $user->student_id }} @elseif($userType == 'guardian') {{ $user->phone }} @endif
                            </p>
                        </div>
                        <form method="POST" action="{{ $logoutUrl }}">
                            @csrf
                            <button type="submit" class="text-white hover:text-red-200 transition-colors" title="লগআউট">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 relative">
                <!-- Mobile Header -->
                <div class="md:hidden bg-gradient-to-r {{ $headerColor }} text-white p-4 flex justify-between items-center sticky top-0 z-50">
                    <div class="font-bold text-lg">{{ \App\Models\SchoolSetting::getSettings()->school_name_bn ?? (tenant('data')['school_name'] ?? tenant('id')) }}</div>
                    <button class="text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                @yield('content')
            </main>
        </div>

        @stack('scripts')
    </body>
</html>

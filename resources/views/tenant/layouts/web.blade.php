<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Additional Bengali Fonts for Premium Look -->
    <link href="https://fonts.maateen.me/adorsho-lipi/font.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Default font - SolaimanLipi for general text */
        * { font-family: 'SolaimanLipi', 'Noto Sans Bengali', 'Poppins', sans-serif; }
        
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
        
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .notice-scroll { animation: scroll 20s linear infinite; }
        @keyframes scroll { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
        
        .text-shadow-lg {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
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
        
        @yield('styles')
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <!-- Top Logo Section (conditionally displayed) -->
    @if($schoolSettings->logo && in_array($schoolSettings->logo_position ?? 'navbar_only', ['top_and_navbar', 'top_only']))
    <div class="bg-white py-4 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden mb-3 shadow-lg border-4 border-white">
                    <img src="{{ $schoolSettings->getImageUrl('logo') }}" alt="School Logo" class="w-full h-full object-cover rounded-full" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full flex items-center justify-center rounded-full" style="display: none;">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}</h1>
                <p class="text-sm text-gray-600 mt-1">EIIN: {{ $schoolSettings->eiin ?? $websiteSettings->eiin ?? '123456' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    @if($schoolSettings->logo && in_array($schoolSettings->logo_position ?? 'navbar_only', ['navbar_only', 'top_and_navbar']))
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-lg">
                        <img src="{{ $schoolSettings->getImageUrl('logo') }}" alt="School Logo" class="w-full h-full object-cover rounded-full" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full flex items-center justify-center rounded-full" style="display: none;">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    @elseif($websiteSettings->logo)
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-lg">
                        <img src="{{ $websiteSettings->getImageUrl('logo') }}" alt="School Logo" class="w-full h-full object-cover rounded-full" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full flex items-center justify-center rounded-full" style="display: none;">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    @else
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    @endif
                    <h1 class="text-xl font-bold text-gray-900">{{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}</h1>
                </div>
                <div class="hidden lg:flex space-x-1 xl:space-x-4">
                    <!-- Home -->
                    <a href="{{ route('tenant.home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">হোম</a>

                    <!-- About Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">
                            পরিচিতি <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left z-50 border border-gray-100">
                            <a href="{{ route('tenant.about') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">পরিচিতি</a>
                        </div>
                    </div>

                    <!-- Administration Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">
                            প্রশাসন <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left z-50 border border-gray-100">
                            <a href="{{ route('tenant.administration.committee') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">ম্যানেজিং কমিটি</a>
                            <a href="{{ route('tenant.administration.staff') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">শিক্ষক-কর্মচারী</a>
                        </div>
                    </div>

                    <!-- Academic Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">
                            একাডেমিক <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left z-50 border border-gray-100">
                            <a href="{{ route('tenant.academic.routine') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">ক্লাস রুটিন</a>
                            <a href="{{ route('tenant.academic.syllabus') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">সিলেবাস</a>
                            <a href="{{ route('tenant.academic.holidays') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">ছুটির তালিকা</a>
                            <a href="{{ route('tenant.academic.calendar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">একাডেমিক ক্যালেন্ডার</a>
                        </div>
                    </div>

                    <!-- Admission Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">
                            ভর্তি <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left z-50 border border-gray-100">
                            <a href="{{ route('tenant.admission.apply') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">অনলাইন আবেদন</a>
                            <a href="{{ route('tenant.admission.rules') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">ভর্তি সংক্রান্ত নিয়মাবলি</a>
                        </div>
                    </div>

                    <!-- Result -->
                    <a href="{{ route('public.result.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">ফলাফল</a>

                    <!-- Gallery -->
                    <a href="{{ route('tenant.gallery') }}" class="text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">গ্যালারি</a>

                    <!-- Contact -->
                    <a href="{{ route('tenant.contact') }}" class="text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">যোগাযোগ</a>

                    <!-- Homework -->
                    <a href="{{ route('homework.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1 whitespace-nowrap">বাড়ির কাজ</a>

                    <!-- Notice -->
                    <a href="{{ route('tenant.notice') }}" class="text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1">নোটিশ</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('tenant.dashboard') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition font-semibold">ড্যাশবোর্ড</a>
                    @else
                        <!-- Login Dropdown -->
                        <div class="relative group">
                            <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition font-semibold flex items-center gap-2">
                                লগইন <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right z-50 border border-gray-100">
                                <a href="{{ url('/login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                                    <i class="fas fa-user-tie mr-2 text-blue-600"></i>শিক্ষক/অ্যাডমিন লগইন
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ url('/student/login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium">
                                    <i class="fas fa-user-graduate mr-2 text-green-600"></i>শিক্ষার্থী লগইন
                                </a>
                                <a href="{{ url('/guardian/login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 font-medium">
                                    <i class="fas fa-users mr-2 text-purple-600"></i>অভিভাবক লগইন
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('tenant.login.info') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 font-medium">
                                    <i class="fas fa-info-circle mr-2 text-orange-600"></i>লগইন সহায়তা
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Notice Board (Scrolling) -->
    <section class="bg-gradient-to-r from-gray-100 via-white to-gray-100 text-gray-900 py-2 sticky {{ $schoolSettings->logo && in_array($schoolSettings->logo_position ?? 'navbar_only', ['top_and_navbar', 'top_only']) ? 'top-16' : 'top-16' }} z-40 shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white px-3 py-1 rounded-lg font-bold flex-shrink-0 text-sm">
                    <i class="fas fa-bullhorn mr-1"></i>নোটিশ
                </div>
                <div class="overflow-hidden flex-1">
                    <div class="notice-scroll whitespace-nowrap text-sm font-medium">
                        @if($websiteSettings->notice_1)<span class="inline-block mr-8">{{ $websiteSettings->notice_1 }}</span>@endif
                        @if($websiteSettings->notice_2)<span class="inline-block mr-8">{{ $websiteSettings->notice_2 }}</span>@endif
                        @if($websiteSettings->notice_3)<span class="inline-block mr-8">{{ $websiteSettings->notice_3 }}</span>@endif
                        @if($websiteSettings->notice_4)<span class="inline-block mr-8">{{ $websiteSettings->notice_4 }}</span>@endif
                        @if(!$websiteSettings->notice_1 && !$websiteSettings->notice_2 && !$websiteSettings->notice_3 && !$websiteSettings->notice_4)
                        <span class="inline-block mr-8">📢 বার্ষিক পরীক্ষার রুটিন প্রকাশিত হয়েছে</span>
                        <span class="inline-block mr-8">📚 নতুন শিক্ষাবর্ষের ভর্তি চলছে</span>
                        <span class="inline-block mr-8">🏆 বার্ষিক ক্রীড়া প্রতিযোগিতা আগামীকাল</span>
                        <span class="inline-block mr-8">📅 শিক্ষক দিবস উদযাপন</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h5 class="text-white font-bold text-lg mb-4">{{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}</h5>
                    <p class="text-sm text-gray-400">স্মার্ট পাঠশালা ম্যানেজমেন্ট সিস্টেম</p>
                    <p class="text-sm text-gray-400 mt-2">EIIN: {{ $schoolSettings->eiin ?? $websiteSettings->eiin ?? '123456' }}</p>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">দ্রুত লিংক</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('tenant.about') }}" class="hover:text-white transition">পরিচিতি</a></li>
                        <li><a href="{{ route('tenant.administration') }}" class="hover:text-white transition">প্রশাসন</a></li>
                        <li><a href="{{ route('tenant.academic') }}" class="hover:text-white transition">একাডেমিক</a></li>
                        <li><a href="{{ route('tenant.notice') }}" class="hover:text-white transition">নোটিশ</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">গুরুত্বপূর্ণ লিংক</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('public.result.index') }}" class="hover:text-white transition">ফলাফল</a></li>
                        <li><a href="{{ route('tenant.gallery') }}" class="hover:text-white transition">গ্যালারি</a></li>
                        <li><a href="{{ url('/login') }}" class="hover:text-white transition">লগইন</a></li>
                        <li><a href="{{ route('tenant.contact') }}" class="hover:text-white transition">যোগাযোগ</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">সোশ্যাল মিডিয়া</h5>
                    <div class="flex gap-3">
                        @if($websiteSettings->facebook)
                            <a href="{{ $websiteSettings->facebook }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white hover:bg-blue-700 transition"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($websiteSettings->youtube)
                            <a href="{{ $websiteSettings->youtube }}" target="_blank" class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white hover:bg-red-700 transition"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}. সর্বস্বত্ব সংরক্ষিত।</p>
                <p class="mt-2 text-gray-500">Powered by SmartPathshala</p>
            </div>
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
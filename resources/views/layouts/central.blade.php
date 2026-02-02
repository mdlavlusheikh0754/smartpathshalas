<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'স্মার্ট পাঠশালা') }}</title>

    <!-- SolaimanLipi Font -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    
    <style>
        *:not(i):not([class*='fa-']):not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not(.fat) { 
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
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold text-blue-600 tracking-tighter">স্মার্ট পাঠশালা</a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-8 font-medium text-gray-700">
                    <a href="/" class="hover:text-blue-600 transition">হোম</a>
                    <a href="#features" class="hover:text-blue-600 transition">ফিচার</a>
                    <a href="#pricing" class="hover:text-blue-600 transition">প্রাইসিং</a>
                </div>
                
                <!-- Desktop Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('central.dashboard') }}" class="text-gray-700 font-semibold hover:text-blue-600 transition">অ্যাডমিন প্যানেল</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="text-gray-700 font-semibold hover:text-blue-600 transition">ড্যাশবোর্ড</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 font-semibold hover:text-red-600 transition">লগ আউট</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 font-semibold hover:text-blue-600 transition">লগইন</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-full font-bold hover:bg-blue-700 shadow-md transition">রেজিস্টার করুন</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" onclick="toggleMobileMenu()" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition">হোম</a>
                <a href="#features" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition">ফিচার</a>
                <a href="#pricing" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition">প্রাইসিং</a>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('central.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition">অ্যাডমিন প্যানেল</a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition">ড্যাশবোর্ড</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-red-500 hover:bg-red-50 rounded-md transition">লগ আউট</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition">লগইন</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 bg-blue-600 text-white text-center rounded-md hover:bg-blue-700 transition">রেজিস্টার করুন</a>
                @endauth
            </div>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-blue-400 mb-4">স্মার্ট পাঠশালা</h3>
                    <p class="text-gray-400 text-sm">আধুনিক স্কুল ম্যানেজমেন্ট সিস্টেম</p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">দ্রুত লিংক</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition">হোম</a></li>
                        <li><a href="#features" class="hover:text-white transition">ফিচার</a></li>
                        <li><a href="#pricing" class="hover:text-white transition">প্রাইসিং</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">সাপোর্ট</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition">হেল্প সেন্টার</a></li>
                        <li><a href="#" class="hover:text-white transition">যোগাযোগ</a></li>
                        <li><a href="#" class="hover:text-white transition">প্রাইভেসি পলিসি</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">যোগাযোগ</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>ইমেইল: info@smartpathshala.com</li>
                        <li>ফোন: +৮৮০ ১৭xxxxxxxx</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} স্মার্ট পাঠশালা। সর্বস্বত্ব সংরক্ষিত।</p>
            </div>
        </div>
    </footer>
</body>
</html>

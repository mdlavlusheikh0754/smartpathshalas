<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>স্মার্টপাঠশালা - ডিজিটাল শিক্ষা প্ল্যাটফর্ম</title>
    
    <!-- SolaimanLipi Font -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        *:not(i):not([class*='fa-']):not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not(.fat) { 
            font-family: 'SolaimanLipi', sans-serif !important;
            font-weight: 400;
        }
        
        h1, h2, h3, h4, h5, h6, strong, b, .font-bold, .font-semibold {
            font-weight: 700 !important;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📚</span>
                        </div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">স্মার্টপাঠশালা</h1>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium transition">বৈশিষ্ট্য</a>
                    <a href="#pricing" class="text-gray-700 hover:text-blue-600 font-medium transition">মূল্য নির্ধারণ</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 font-medium transition">সম্পর্কে</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium transition">যোগাযোগ</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('central.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">অ্যাডমিন প্যানেল</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">ড্যাশবোর্ড</a>
                        @endif
                    @else
                        <a href="/login" class="text-gray-700 hover:text-blue-600 font-medium transition">লগইন</a>
                        <a href="/register" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition font-semibold">স্কুল নিবন্ধন</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-6xl font-bold mb-6 leading-tight">শিক্ষাকে রূপান্তরিত করুন<br>স্মার্টপাঠশালার সাথে</h2>
                    <p class="text-xl mb-8 text-blue-100">একটি সম্পূর্ণ ডিজিটাল শিক্ষা প্ল্যাটফর্ম যা আপনার স্কুলকে আধুনিক যুগে এগিয়ে নিয়ে যায়। ক্লাস, শিক্ষার্থী এবং শিক্ষা সামগ্রী সবকিছু এক জায়গায় পরিচালনা করুন।</p>
                    <div class="flex gap-4">
                        <a href="/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">এখনই শুরু করুন</a>
                        <a href="#features" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">আরও জানুন</a>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl h-80 flex items-center justify-center shadow-2xl">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📊</div>
                        <span class="text-white text-lg font-semibold">ড্যাশবোর্ড প্রিভিউ</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-5xl font-bold mb-4">আমাদের বৈশিষ্ট্যসমূহ</h3>
                <p class="text-xl text-gray-600">স্মার্টপাঠশালা আপনার স্কুলের জন্য সম্পূর্ণ সমাধান প্রদান করে</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">ক্লাস ব্যবস্থাপনা</h4>
                    <p class="text-gray-600">ক্লাস, বিভাগ এবং বিষয় দক্ষতার সাথে সংগঠিত করুন আমাদের স্বজ্ঞাত ইন্টারফেসের মাধ্যমে।</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h6"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">শিক্ষার্থী ট্র্যাকিং</h4>
                    <p class="text-gray-600">শিক্ষার্থীদের অগ্রগতি, উপস্থিতি এবং কর্মক্ষমতা রিয়েল-টাইমে পর্যবেক্ষণ করুন।</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">বিশ্লেষণ ও রিপোর্ট</h4>
                    <p class="text-gray-600">বিস্তারিত রিপোর্ট এবং অন্তর্দৃষ্টি তৈরি করুন শিক্ষার ফলাফল উন্নত করতে।</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-5xl font-bold mb-4">সহজ মূল্য নির্ধারণ</h3>
                <p class="text-xl text-gray-600">আপনার স্কুলের আকার অনুযায়ী সঠিক পরিকল্পনা বেছে নিন</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-blue-600 hover:shadow-lg transition">
                    <h4 class="text-2xl font-bold mb-4">স্টার্টার</h4>
                    <p class="text-4xl font-bold text-blue-600 mb-2">৳৯,৯৯০<span class="text-lg text-gray-600">/মাস</span></p>
                    <p class="text-gray-500 text-sm mb-6">ছোট স্কুলের জন্য আদর্শ</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> ১০০ জন শিক্ষার্থী পর্যন্ত</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> মৌলিক রিপোর্টিং</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> ইমেইল সহায়তা</li>
                    </ul>
                    <button class="w-full border-2 border-blue-600 text-blue-600 py-2 rounded-lg hover:bg-blue-50 font-semibold">পরিকল্পনা বেছে নিন</button>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-600 rounded-xl p-8 shadow-lg transform scale-105">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-1 inline-block rounded-full mb-4 text-sm font-bold">জনপ্রিয়</div>
                    <h4 class="text-2xl font-bold mb-4">পেশাদার</h4>
                    <p class="text-4xl font-bold text-blue-600 mb-2">৳২৯,৯৯০<span class="text-lg text-gray-600">/মাস</span></p>
                    <p class="text-gray-500 text-sm mb-6">মাঝারি স্কুলের জন্য সেরা</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> ৫০০ জন শিক্ষার্থী পর্যন্ত</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> উন্নত বিশ্লেষণ</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> অগ্রাধিকার সহায়তা</li>
                    </ul>
                    <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 rounded-lg hover:shadow-lg font-semibold">এখনই শুরু করুন</button>
                </div>
                <div class="bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-blue-600 hover:shadow-lg transition">
                    <h4 class="text-2xl font-bold mb-4">এন্টারপ্রাইজ</h4>
                    <p class="text-4xl font-bold text-blue-600 mb-2">কাস্টম</p>
                    <p class="text-gray-500 text-sm mb-6">বড় স্কুলের জন্য</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> সীমাহীন শিক্ষার্থী</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> কাস্টম বৈশিষ্ট্য</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> নিবেদিত সহায়তা</li>
                    </ul>
                    <button class="w-full border-2 border-blue-600 text-blue-600 py-2 rounded-lg hover:bg-blue-50 font-semibold">বিক্রয়ের সাথে যোগাযোগ করুন</button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-5xl font-bold mb-6">আপনার স্কুলকে রূপান্তরিত করতে প্রস্তুত?</h3>
            <p class="text-xl mb-8 text-blue-100">শত শত স্কুল ইতিমধ্যে স্মার্টপাঠশালা ব্যবহার করছে এবং তাদের শিক্ষা ব্যবস্থা উন্নত করছে</p>
            <a href="/register" class="inline-block bg-white text-blue-600 px-10 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">এখনই আপনার স্কুল নিবন্ধন করুন</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">📚</span>
                        </div>
                        <h5 class="text-white font-bold text-lg">স্মার্টপাঠশালা</h5>
                    </div>
                    <p class="text-sm text-gray-400">আধুনিক স্কুলের জন্য ডিজিটাল শিক্ষা প্ল্যাটফর্ম।</p>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">পণ্য</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">বৈশিষ্ট্য</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition">মূল্য নির্ধারণ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">নিরাপত্তা</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">কোম্পানি</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/about" class="text-gray-400 hover:text-white transition">সম্পর্কে</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">ব্লগ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">ক্যারিয়ার</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">আইনি</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">গোপনীয়তা নীতি</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">শর্তাবলী</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">যোগাযোগ</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                <p>&copy; ২০২৪ স্মার্টপাঠশালা। সর্বাধিকার সংরক্ষিত।</p>
            </div>
        </div>
    </footer>
</body>
</html>

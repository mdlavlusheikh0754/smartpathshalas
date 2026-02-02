<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আপনার স্কুল নিবন্ধন করুন - স্মার্টপাঠশালা</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📚</span>
                        </div>
                        <a href="/" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">স্মার্টপাঠশালা</a>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600 font-medium transition">হোম</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 font-medium transition">সম্পর্কে</a>
                    <a href="/register" class="text-gray-700 hover:text-blue-600 font-medium transition font-semibold">নিবন্ধন</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Registration Form Section -->
    <section class="py-20">
        <div class="max-w-2xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Form Column -->
                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    <h1 class="text-4xl font-bold text-center mb-2 text-gray-800">আপনার স্কুল নিবন্ধন করুন</h1>
                    <p class="text-center text-gray-600 mb-8">মিনিটের মধ্যে আপনার স্কুলের ডিজিটাল প্ল্যাটফর্ম তৈরি করুন</p>

                    <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- School ID Input -->
                        <div>
                            <label for="school_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                স্কুল আইডি (সাবডোমেইন নাম)
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="school_id"
                                    name="school_id" 
                                    placeholder="যেমন: iqra" 
                                    required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    value="{{ old('school_id') }}"
                                >
                                <span class="absolute right-4 top-3 text-gray-500 text-sm font-medium">.smartpathshala.test</span>
                            </div>
                            @error('school_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-2">আপনার স্কুল এখানে অ্যাক্সেসযোগ্য হবে: [স্কুল_আইডি].smartpathshala.test</p>
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                ইমেইল ঠিকানা
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                placeholder="আপনার@ইমেইল.com"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition duration-200 transform hover:scale-105"
                        >
                            স্কুল নিবন্ধন করুন
                        </button>
                    </form>

                    <!-- Already Registered -->
                    <p class="text-center text-gray-600 text-sm mt-6">
                        ইতিমধ্যে নিবন্ধিত? 
                        <a href="/" class="text-blue-600 hover:underline font-semibold">হোমে ফিরে যান</a>
                    </p>
                </div>

                <!-- Benefits Column -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition">
                        <div class="text-5xl mb-4">⚡</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">তাৎক্ষণিক সেটআপ</h3>
                        <p class="text-gray-600">কয়েক মিনিটের মধ্যে আপনার স্কুল চালু করুন</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition">
                        <div class="text-5xl mb-4">🔒</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">নিরাপদ</h3>
                        <p class="text-gray-600">আপনার ডেটা সর্বোচ্চ নিরাপত্তায় সুরক্ষিত</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition">
                        <div class="text-5xl mb-4">📊</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">শক্তিশালী</h3>
                        <p class="text-gray-600">সম্পূর্ণ শিক্ষা ব্যবস্থাপনা সমাধান</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16 mt-20">
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
                        <li><a href="#" class="text-gray-400 hover:text-white transition">বৈশিষ্ট্য</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">মূল্য নির্ধারণ</a></li>
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

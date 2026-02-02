<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>স্কুল লক করা আছে</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .lock-animation {
            animation: shake 0.5s ease-in-out infinite alternate;
        }
        @keyframes shake {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(5deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Lock Icon Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <div class="lock-animation inline-block mb-6">
                <div class="bg-gradient-to-r from-red-500 to-orange-500 p-6 rounded-full shadow-lg">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">স্কুল লক করা আছে</h1>
            
            <p class="text-gray-600 mb-6 leading-relaxed">
                দুঃখিত, এই স্কুল বর্তমানে লক করা আছে। সাময়িকভাবে স্কুলের সকল সেবা স্থগিত রয়েছে।
            </p>
            
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-red-800">কি করণীয়?</p>
                        <p class="text-sm text-red-600">অনুগ্রহ করে স্কুল প্রশাসনের সাথে যোগাযোগ করুন</p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <button onclick="history.back()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                    পেছনে যান
                </button>
                
                @if(tenant())
                    <a href="http://{{ tenant()->domains->first()->domain ?? 'smartpathshala.test' }}/admin" class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 text-center">
                        স্কুল অ্যাডমিন
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} স্মার্ট পাঠশালা - সর্বস্বত্ব সংরক্ষিত
            </p>
        </div>
    </div>
</body>
</html>

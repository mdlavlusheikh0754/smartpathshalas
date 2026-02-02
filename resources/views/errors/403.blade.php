<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাক্সেস নিষিদ্ধ - স্মার্ট পাঠশালা</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-red-100">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-800 mb-4">৪০৩</h1>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">অ্যাক্সেস নিষিদ্ধ</h2>
            
            <p class="text-gray-600 mb-8">
                {{ $exception->getMessage() ?: 'আপনার এই পেজে প্রবেশের অনুমতি নেই।' }}
            </p>
            
            <div class="space-y-3">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('central.dashboard') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg">
                            অ্যাডমিন ড্যাশবোর্ডে ফিরুন
                        </a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg">
                            ড্যাশবোর্ডে ফিরুন
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg">
                        লগইন করুন
                    </a>
                @endauth
                
                <a href="/" class="block w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200">
                    হোমে ফিরুন
                </a>
            </div>
        </div>
        
        <p class="text-gray-500 text-sm mt-6">
            সাহায্যের প্রয়োজন? <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">সাপোর্টে যোগাযোগ করুন</a>
        </p>
    </div>
</body>
</html>

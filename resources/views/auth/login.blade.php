<x-guest-layout>
    <div class="p-8">
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">স্বাগতম!</h2>
            <p class="text-gray-600 text-sm">আপনার অ্যাকাউন্টে লগইন করুন</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">ইমেইল</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">পাসওয়ার্ড</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-gray-700">আমাকে মনে রাখুন</label>
            </div>

            <div class="flex items-center justify-between pt-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                        পাসওয়ার্ড ভুলে গেছেন?
                    </a>
                @endif

                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                    লগইন করুন
                </button>
            </div>

            <div class="text-center pt-4 border-t border-gray-200">
                @if(!tenancy()->initialized)
                    <p class="text-sm text-gray-600">
                        নতুন ইউজার? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">
                            রেজিস্টার করুন
                        </a>
                    </p>
                @else
                    <p class="text-sm text-gray-600">
                        অ্যাকাউন্ট নেই? অনুগ্রহ করে স্কুল প্রশাসকের সাথে যোগাযোগ করুন
                    </p>
                @endif
            </div>
        </form>
    </div>
</x-guest-layout>

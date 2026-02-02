<x-guest-layout>
    <div class="p-8">
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
            <p class="text-gray-600 text-sm">আপনার স্কুল ম্যানেজমেন্ট শুরু করুন</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <p class="text-sm text-blue-800 font-medium">📚 আপনার স্কুলের তথ্য দিয়ে রেজিস্ট্রেশন করুন</p>
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">আপনার নাম *</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                        placeholder="মোঃ রহিম উদ্দিন">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">ইমেইল *</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                        placeholder="example@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- School Information -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-4">🏫 স্কুলের তথ্য</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- School Name -->
                    <div>
                        <label for="school_name" class="block text-sm font-semibold text-gray-700 mb-2">স্কুলের নাম *</label>
                        <input id="school_name" type="text" name="school_name" value="{{ old('school_name') }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('school_name') border-red-500 @enderror"
                            placeholder="আদর্শ উচ্চ বিদ্যালয়">
                        @error('school_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">ফোন নম্বর *</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                            placeholder="01XXXXXXXXX">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- School ID -->
                    <div>
                        <label for="school_id" class="block text-sm font-semibold text-gray-700 mb-2">স্কুল আইডি (ইংরেজিতে) *</label>
                        <input id="school_id" type="text" name="school_id" value="{{ old('school_id') }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('school_id') border-red-500 @enderror"
                            placeholder="adarsha-school" pattern="[a-z0-9-]+" title="শুধুমাত্র ছোট হাতের ইংরেজি অক্ষর, সংখ্যা এবং হাইফেন (-) ব্যবহার করুন">
                        <p class="mt-1 text-xs text-gray-500">শুধুমাত্র ছোট হাতের ইংরেজি অক্ষর, সংখ্যা এবং হাইফেন (-)</p>
                        @error('school_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Domain -->
                    <div>
                        <label for="domain" class="block text-sm font-semibold text-gray-700 mb-2">ডোমেইন নাম *</label>
                        <div class="flex items-center gap-2">
                            <input id="domain" type="text" name="domain" value="{{ old('domain') }}" required 
                                class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('domain') border-red-500 @enderror"
                                placeholder="adarsha" pattern="[a-z0-9-]+" title="শুধুমাত্র ছোট হাতের ইংরেজি অক্ষর, সংখ্যা এবং হাইফেন (-) ব্যবহার করুন">
                            <span class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-3 rounded-xl">.smartpathshala.test</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">আপনার স্কুলের ওয়েবসাইট: domain.smartpathshala.test</p>
                        @error('domain')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-4">🔒 পাসওয়ার্ড সেট করুন</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">পাসওয়ার্ড *</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">পাসওয়ার্ড নিশ্চিত করুন *</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            @if($errors->has('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <p class="text-sm text-red-800">{{ $errors->first('error') }}</p>
                </div>
            @endif

            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                    ← ইতিমধ্যে রেজিস্টার্ড?
                </a>

                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                    রেজিস্টার করুন →
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

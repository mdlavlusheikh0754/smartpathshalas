@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">নোটিফিকেশন সেটিংস</h1>
                <p class="text-gray-600 mt-1">নোটিফিকেশন এবং এলার্ট কনফিগার করুন</p>
            </div>
            <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">সফল!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <form action="{{ route('tenant.settings.notification.update') }}" method="POST">
            @csrf
            
            <!-- Email Notifications -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ইমেইল নোটিফিকেশন</h2>
                </div>

                <div class="space-y-4">
                    @foreach([
                        ['id' => 'email_admission', 'label' => 'নতুন ভর্তি', 'desc' => 'নতুন শিক্ষার্থী ভর্তি হলে ইমেইল পাঠান'],
                        ['id' => 'email_fee', 'label' => 'ফি পেমেন্ট', 'desc' => 'ফি পরিশোধ হলে ইমেইল পাঠান'],
                        ['id' => 'email_exam', 'label' => 'পরীক্ষার ফলাফল', 'desc' => 'পরীক্ষার ফলাফল প্রকাশ হলে ইমেইল পাঠান'],
                        ['id' => 'email_attendance', 'label' => 'উপস্থিতি', 'desc' => 'শিক্ষার্থী অনুপস্থিত থাকলে ইমেইল পাঠান']
                    ] as $notification)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div>
                            <p class="font-bold text-gray-900">{{ $notification['label'] }}</p>
                            <p class="text-sm text-gray-600">{{ $notification['desc'] }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="{{ $notification['id'] }}" class="sr-only peer" {{ $settings->{$notification['id']} ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- SMS Notifications -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">এসএমএস নোটিফিকেশন</h2>
                </div>

                <div class="space-y-4">
                    @foreach([
                        ['id' => 'sms_admission', 'label' => 'নতুন ভর্তি', 'desc' => 'নতুন শিক্ষার্থী ভর্তি হলে এসএমএস পাঠান'],
                        ['id' => 'sms_fee', 'label' => 'ফি পেমেন্ট', 'desc' => 'ফি পরিশোধ হলে এসএমএস পাঠান'],
                        ['id' => 'sms_exam', 'label' => 'পরীক্ষার ফলাফল', 'desc' => 'পরীক্ষার ফলাফল প্রকাশ হলে এসএমএস পাঠান'],
                        ['id' => 'sms_attendance', 'label' => 'উপস্থিতি', 'desc' => 'শিক্ষার্থী অনুপস্থিত থাকলে এসএমএস পাঠান'],
                        ['id' => 'sms_notice', 'label' => 'নোটিশ', 'desc' => 'নতুন নোটিশ প্রকাশ হলে এসএমএস পাঠান']
                    ] as $notification)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div>
                            <p class="font-bold text-gray-900">{{ $notification['label'] }}</p>
                            <p class="text-sm text-gray-600">{{ $notification['desc'] }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="{{ $notification['id'] }}" class="sr-only peer" {{ $settings->{$notification['id']} ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Push Notifications -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">পুশ নোটিফিকেশন</h2>
                </div>

                <div class="space-y-4">
                    @foreach([
                        ['id' => 'push_admission', 'label' => 'নতুন ভর্তি', 'desc' => 'নতুন শিক্ষার্থী ভর্তি হলে পুশ নোটিফিকেশন পাঠান'],
                        ['id' => 'push_fee', 'label' => 'ফি পেমেন্ট', 'desc' => 'ফি পরিশোধ হলে পুশ নোটিফিকেশন পাঠান'],
                        ['id' => 'push_notice', 'label' => 'নোটিশ', 'desc' => 'নতুন নোটিশ প্রকাশ হলে পুশ নোটিফিকেশন পাঠান'],
                        ['id' => 'push_exam', 'label' => 'পরীক্ষা', 'desc' => 'পরীক্ষা সংক্রান্ত আপডেট পুশ নোটিফিকেশন পাঠান'],
                        ['id' => 'push_event', 'label' => 'ইভেন্ট', 'desc' => 'নতুন ইভেন্ট যুক্ত হলে পুশ নোটিফিকেশন পাঠান']
                    ] as $notification)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div>
                            <p class="font-bold text-gray-900">{{ $notification['label'] }}</p>
                            <p class="text-sm text-gray-600">{{ $notification['desc'] }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="{{ $notification['id'] }}" class="sr-only peer" {{ $settings->{$notification['id']} ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-end gap-4">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold transition-colors">
                        রিসেট করুন
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        সংরক্ষণ করুন
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

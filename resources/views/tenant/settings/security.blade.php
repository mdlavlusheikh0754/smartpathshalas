@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">নিরাপত্তা সেটিংস</h1>
                <p class="text-gray-600 mt-1">সিস্টেম সিকিউরিটি কনফিগার করুন</p>
            </div>
            <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        <form action="#" method="POST">
            @csrf
            
            <!-- Password Policy -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-gradient-to-br from-red-500 to-rose-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">পাসওয়ার্ড পলিসি</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সর্বনিম্ন দৈর্ঘ্য</label>
                        <input type="number" name="min_password_length" value="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পাসওয়ার্ড মেয়াদ (দিন)</label>
                        <input type="number" name="password_expiry" value="90" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        @foreach([
                            ['id' => 'require_uppercase', 'label' => 'বড় হাতের অক্ষর প্রয়োজন'],
                            ['id' => 'require_lowercase', 'label' => 'ছোট হাতের অক্ষর প্রয়োজন'],
                            ['id' => 'require_numbers', 'label' => 'সংখ্যা প্রয়োজন'],
                            ['id' => 'require_special', 'label' => 'বিশেষ চিহ্ন প্রয়োজন']
                        ] as $policy)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-900 font-medium">{{ $policy['label'] }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="{{ $policy['id'] }}" class="sr-only peer" checked>
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Session Settings -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">সেশন সেটিংস</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সেশন টাইমআউট (মিনিট)</label>
                        <input type="number" name="session_timeout" value="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সর্বোচ্চ লগইন প্রচেষ্টা</label>
                        <input type="number" name="max_login_attempts" value="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Two-Factor Authentication -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">টু-ফ্যাক্টর অথেন্টিকেশন</h3>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-bold text-gray-900">টু-ফ্যাক্টর অথেন্টিকেশন সক্রিয় করুন</p>
                        <p class="text-sm text-gray-600">অতিরিক্ত নিরাপত্তার জন্য 2FA সক্রিয় করুন</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_2fa" class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>
            </div>

            <!-- IP Whitelist -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">IP হোয়াইটলিস্ট</h3>
                <div class="space-y-3">
                    <div class="flex gap-3">
                        <input type="text" placeholder="IP Address (e.g., 192.168.1.1)" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">যোগ করুন</button>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">অনুমোদিত IP তালিকা:</p>
                        <div class="space-y-2">
                            @foreach(['192.168.1.1', '103.45.67.89'] as $ip)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="font-mono text-sm">{{ $ip }}</span>
                                <button class="text-red-600 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-end gap-4">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold transition-colors">রিসেট করুন</button>
                    <button type="submit" class="bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
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

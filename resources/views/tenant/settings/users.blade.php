@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">ইউজার ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">সিস্টেম ইউজার এবং পারমিশন পরিচালনা করুন</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
                <button onclick="openAddUserModal()" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    নতুন ইউজার যোগ করুন
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-blue-100 text-sm">মোট ইউজার</p>
                <h3 class="text-3xl font-bold mt-1">১৫</h3>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-green-100 text-sm">অ্যাডমিন</p>
                <h3 class="text-3xl font-bold mt-1">৩</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-purple-100 text-sm">শিক্ষক</p>
                <h3 class="text-3xl font-bold mt-1">১০</h3>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-orange-100 text-sm">অন্যান্য</p>
                <h3 class="text-3xl font-bold mt-1">২</h3>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">সকল ইউজার</h3>
                    <div class="flex items-center gap-4">
                        <input type="text" placeholder="খুঁজুন..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">সকল রোল</option>
                            <option value="admin">অ্যাডমিন</option>
                            <option value="teacher">শিক্ষক</option>
                            <option value="accountant">হিসাবরক্ষক</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold">নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">ইমেইল</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">মোবাইল</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">রোল</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @for($i = 1; $i <= 10; $i++)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr('ইউজার ' . $i, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">ইউজার {{ $i }}</p>
                                        <p class="text-xs text-gray-500">যোগদান: {{ date('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">user{{ $i }}@example.com</td>
                            <td class="px-6 py-4 text-sm text-gray-700">০১৭০০০০০০{{ $i }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-{{ ['blue', 'green', 'purple'][rand(0, 2)] }}-100 text-{{ ['blue', 'green', 'purple'][rand(0, 2)] }}-600 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ ['অ্যাডমিন', 'শিক্ষক', 'হিসাবরক্ষক'][rand(0, 2)] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-bold">সক্রিয়</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-colors" title="সম্পাদনা">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-lg transition-colors" title="পাসওয়ার্ড রিসেট">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </button>
                                    <button class="bg-red-100 hover:bg-red-200 text-red-600 p-2 rounded-lg transition-colors" title="মুছে ফেলুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function openAddUserModal() {
    alert('নতুন ইউজার যোগ করার ফর্ম শীঘ্রই যুক্ত করা হবে');
}
</script>
@endsection

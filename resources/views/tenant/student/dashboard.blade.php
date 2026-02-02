@extends('tenant.layouts.portal')

@section('title', 'শিক্ষার্থী ড্যাশবোর্ড')

@section('content')
<div class="p-6 md:p-8 min-h-screen bg-gray-50">
    <!-- Welcome Banner -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2">স্বাগতম, {{ $student->name_bn ?? $student->name_en ?? $student->student_id }}!</h2>
            <p class="text-blue-100 text-lg">স্টুডেন্ট আইডি: {{ $student->student_id }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Attendance -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">এই মাস</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-1">উপস্থিতি</h3>
            <p class="text-sm text-gray-500">আজকের অবস্থা: <span class="text-green-600 font-medium">উপস্থিত</span></p>
        </div>

        <!-- Result -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-50 p-3 rounded-xl text-purple-600">
                    <i class="fas fa-poll text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">সর্বশেষ পরীক্ষা</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-1">ফলাফল</h3>
            <p class="text-sm text-gray-500">শীঘ্রই আসছে...</p>
        </div>

        <!-- Due -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-red-50 p-3 rounded-xl text-red-600">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">বকেয়া</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-1">৳ ০.০০</h3>
            <p class="text-sm text-gray-500">কোনো বকেয়া নেই</p>
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-white">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-bell text-indigo-600"></i>
                সাম্প্রতিক নোটিফিকেশন
            </h3>
        </div>
        <div class="p-0">
            @forelse($notifications as $notification)
                <div class="p-6 border-b border-gray-50 hover:bg-gray-50 transition-colors last:border-0">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-1 mb-1">
                                <h4 class="font-bold text-gray-800">{{ $notification->title }}</h4>
                                <span class="text-[10px] font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                    {{ toBengaliNumber($notification->created_at->diffForHumans()) }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $notification->message }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
                        <i class="fas fa-bell-slash text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">কোনো নতুন নোটিফিকেশন নেই</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

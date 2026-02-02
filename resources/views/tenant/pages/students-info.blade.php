@extends('tenant.layouts.web')

@section('title', 'শিক্ষার্থী ও অভিভাবক লগইন তথ্য')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">লগইন তথ্য</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">শিক্ষার্থী ও অভিভাবকদের জন্য লগইন নির্দেশনা ও তথ্য</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Login Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Student Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center gap-6 relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">শিক্ষার্থী লগইন</h2>
                        <p class="text-emerald-50 text-sm">Student Login Portal</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-link text-emerald-600 mr-2"></i> লগইন লিংক
                    </h3>
                    <a href="{{ url('/student/login') }}" class="text-emerald-700 hover:text-emerald-800 font-bold break-all hover:underline decoration-2">
                        {{ url('/student/login') }}
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">ইউজারনেম</h3>
                            <p class="text-gray-600 text-sm">আপনার <span class="font-bold text-emerald-600">Student ID</span> ব্যবহার করুন</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">পাসওয়ার্ড</h3>
                            <p class="text-gray-600 text-sm">প্রদত্ত পাসওয়ার্ড প্রথমবার লগইনের পর পরিবর্তন করুন</p>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/student/login') }}" class="block w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-center py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                    লগইন করুন
                </a>
            </div>
        </div>

        <!-- Guardian Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center gap-6 relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">অভিভাবক লগইন</h2>
                        <p class="text-indigo-50 text-sm">Guardian Login Portal</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-link text-indigo-600 mr-2"></i> লগইন লিংক
                    </h3>
                    <a href="{{ url('/guardian/login') }}" class="text-indigo-700 hover:text-indigo-800 font-bold break-all hover:underline decoration-2">
                        {{ url('/guardian/login') }}
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">ইউজারনেম</h3>
                            <p class="text-gray-600 text-sm">নিবন্ধিত <span class="font-bold text-indigo-600">মোবাইল নম্বর</span> ব্যবহার করুন</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">পাসওয়ার্ড</h3>
                            <p class="text-gray-600 text-sm">প্রদত্ত পাসওয়ার্ড গোপন রাখুন ও নিয়মিত পরিবর্তন করুন</p>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/guardian/login') }}" class="block w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-center py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                    লগইন করুন
                </a>
            </div>
        </div>
    </div>

    <!-- Important Information Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 mb-12">
        <div class="flex items-center mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl mr-4 shadow-lg">
                <i class="fas fa-info-circle text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">গুরুত্বপূর্ণ তথ্যাবলি</h2>
                <p class="text-gray-500 text-sm">লগইন করার পূর্বে অবশ্যই পড়ুন</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
                <i class="fas fa-check-circle text-indigo-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold text-gray-900">লগইন তথ্য সংগ্রহ</h4>
                    <p class="text-sm text-gray-600">আপনার আইডি ও পাসওয়ার্ড পেতে অফিস কাউন্টারে যোগাযোগ করুন।</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
                <i class="fas fa-shield-alt text-emerald-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold text-gray-900">পাসওয়ার্ড নিরাপত্তা</h4>
                    <p class="text-sm text-gray-600">পাসওয়ার্ড কাউকে শেয়ার করবেন না এবং নিয়মিত পরিবর্তন করুন।</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
                <i class="fas fa-mobile-alt text-purple-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold text-gray-900">যেকোনো ডিভাইসে অ্যাক্সেস</h4>
                    <p class="text-sm text-gray-600">মোবাইল, ট্যাবলেট বা কম্পিউটার থেকে সব সুবিধা পাওয়া যাবে।</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
                <i class="fas fa-exclamation-triangle text-orange-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold text-gray-900">লগইন সমস্যা</h4>
                    <p class="text-sm text-gray-600">ভুল পাসওয়ার্ড দিলে আইডি সাময়িকভাবে ব্লক হতে পারে।</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
                <i class="fas fa-history text-blue-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold text-gray-900">২৪ ঘণ্টা সার্ভিস</h4>
                    <p class="text-sm text-gray-600">যেকোনো সময় অনলাইন ফলাফল ও পেমেন্ট তথ্য দেখা যাবে।</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
                <i class="fas fa-user-tie text-rose-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold text-gray-900">শিক্ষক লগইন</h4>
                    <p class="text-sm text-gray-600">শিক্ষক ও কর্মচারীদের জন্য আলাদা প্যানেল প্রযোজ্য।</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Help & Contact -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-10 text-white text-center shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative z-10">
            <h3 class="text-3xl font-bold mb-4">সাহায্য প্রয়োজন?</h3>
            <p class="text-indigo-100 mb-8 max-w-xl mx-auto">লগইন সংক্রান্ত যেকোনো জটিলতায় সরাসরি আমাদের সাপোর্ট সেন্টারে যোগাযোগ করুন অথবা অফিসে উপস্থিত হোন।</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="tel:{{ $schoolSettings->phone ?? '' }}" class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-3">
                    <i class="fas fa-phone-alt"></i> {{ $schoolSettings->phone ?? '০১৭XXXXXXXX' }}
                </a>
                <a href="mailto:{{ $schoolSettings->email ?? '' }}" class="bg-indigo-700/30 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-white hover:text-indigo-600 transition-all flex items-center gap-3">
                    <i class="fas fa-envelope"></i> {{ $schoolSettings->email ?? 'info@...' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

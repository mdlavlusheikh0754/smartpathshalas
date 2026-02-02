@extends('tenant.layouts.web')

@section('title', 'একাডেমিক')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">একাডেমিক তথ্য</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের শিক্ষা বিষয়ক সাধারণ তথ্যাবলি</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Student Statistics -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl mr-4 shadow-lg">
                    <i class="fas fa-user-graduate text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">শিক্ষার্থী পরিসংখ্যান</h2>
                    <p class="text-gray-500 text-sm">আমাদের শিক্ষার্থীদের বর্তমান তথ্য</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100 text-center">
                    <p class="text-sm text-gray-600 font-semibold mb-2">মোট ছাত্র</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $websiteSettings->male_students ?? '২৫০' }} জন</p>
                </div>
                <div class="bg-gradient-to-br from-pink-50 to-rose-50 p-6 rounded-xl border border-pink-100 text-center">
                    <p class="text-sm text-gray-600 font-semibold mb-2">মোট ছাত্রী</p>
                    <p class="text-3xl font-bold text-pink-600">{{ $websiteSettings->female_students ?? '২৫০' }} জন</p>
                </div>
            </div>
        </div>

        <!-- Academic Calendar Summary -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-2xl mr-4 shadow-lg">
                    <i class="fas fa-calendar-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">একাডেমিক ক্যালেন্ডার</h2>
                    <p class="text-gray-500 text-sm">বার্ষিক শিক্ষা কার্যক্রমের রূপরেখা</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-100">
                <ul class="space-y-4">
                    <li class="flex items-center justify-between text-gray-700">
                        <span class="flex items-center"><i class="fas fa-star text-purple-500 mr-3"></i> শিক্ষাবর্ষ শুরু</span>
                        <span class="font-bold">জানুয়ারি</span>
                    </li>
                    <li class="flex items-center justify-between text-gray-700">
                        <span class="flex items-center"><i class="fas fa-edit text-purple-500 mr-3"></i> প্রথম সাময়িক</span>
                        <span class="font-bold">এপ্রিল</span>
                    </li>
                    <li class="flex items-center justify-between text-gray-700">
                        <span class="flex items-center"><i class="fas fa-edit text-purple-500 mr-3"></i> দ্বিতীয় সাময়িক</span>
                        <span class="font-bold">আগস্ট</span>
                    </li>
                    <li class="flex items-center justify-between text-gray-700">
                        <span class="flex items-center"><i class="fas fa-file-alt text-purple-500 mr-3"></i> বার্ষিক পরীক্ষা</span>
                        <span class="font-bold">নভেম্বর</span>
                    </li>
                    <li class="flex items-center justify-between text-gray-700">
                        <span class="flex items-center"><i class="fas fa-graduation-cap text-purple-500 mr-3"></i> ফলাফল প্রকাশ</span>
                        <span class="font-bold">ডিসেম্বর</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
        <a href="{{ route('tenant.academic.syllabus') }}" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center hover:scale-105 transition-all duration-300 group">
            <div class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600 transition-colors duration-300">
                <i class="fas fa-book-open text-2xl text-blue-600 group-hover:text-white"></i>
            </div>
            <h4 class="font-bold text-gray-900">সিলেবাস</h4>
        </a>
        <a href="{{ route('tenant.academic.routine') }}" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center hover:scale-105 transition-all duration-300 group">
            <div class="bg-emerald-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-600 transition-colors duration-300">
                <i class="fas fa-clock text-2xl text-emerald-600 group-hover:text-white"></i>
            </div>
            <h4 class="font-bold text-gray-900">ক্লাস রুটিন</h4>
        </a>
        <a href="{{ route('tenant.academic.calendar') }}" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center hover:scale-105 transition-all duration-300 group">
            <div class="bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-600 transition-colors duration-300">
                <i class="fas fa-calendar-check text-2xl text-purple-600 group-hover:text-white"></i>
            </div>
            <h4 class="font-bold text-gray-900">ক্যালেন্ডার</h4>
        </a>
        <a href="{{ route('tenant.academic.holidays') }}" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center hover:scale-105 transition-all duration-300 group">
            <div class="bg-rose-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-rose-600 transition-colors duration-300">
                <i class="fas fa-umbrella-beach text-2xl text-rose-600 group-hover:text-white"></i>
            </div>
            <h4 class="font-bold text-gray-900">ছুটির তালিকা</h4>
        </a>
    </div>
</div>
@endsection

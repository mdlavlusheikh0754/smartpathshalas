@extends('tenant.layouts.web')

@section('title', 'প্রশাসন')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">প্রশাসনিক কাঠামো</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের দক্ষ ও অভিজ্ঞ প্রশাসনিক পরিষদ</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Chairman -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 text-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="w-32 h-32 mx-auto mb-6 rounded-2xl p-1 bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg transform group-hover:rotate-3 transition-transform duration-300">
                    <img src="{{ $websiteSettings->getImageUrl('chairman_image') }}" alt="Chairman" class="w-full h-full object-cover rounded-xl bg-white" onerror="this.src='https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'">
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $websiteSettings->chairman_name ?? 'সভাপতি মহোদয়' }}</h4>
                <div class="inline-block px-4 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold mb-4">সভাপতি</div>
                <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 italic text-gray-600 text-sm">
                    "{{ $websiteSettings->chairman_message_short ?? 'আমাদের লক্ষ্য একটি আধুনিক ও মানসম্মত শিক্ষা প্রতিষ্ঠান গড়ে তোলা।' }}"
                </div>
            </div>
        </div>

        <!-- Principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 text-center relative overflow-hidden group scale-105 z-10">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="w-32 h-32 mx-auto mb-6 rounded-2xl p-1 bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg transform group-hover:-rotate-3 transition-transform duration-300">
                    <img src="{{ $websiteSettings->getImageUrl('principal_image') }}" alt="Principal" class="w-full h-full object-cover rounded-xl bg-white" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'">
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $websiteSettings->principal_name ?? 'অধ্যক্ষ মহোদয়' }}</h4>
                <div class="inline-block px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-bold mb-4">অধ্যক্ষ</div>
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 italic text-gray-600 text-sm">
                    "{{ $websiteSettings->principal_message_short ?? 'শিক্ষাই জাতির মেরুদণ্ড। আমরা সুশিক্ষায় শিক্ষিত জাতি গঠনে অঙ্গীকারবদ্ধ।' }}"
                </div>
            </div>
        </div>

        <!-- Vice Principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 text-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="w-32 h-32 mx-auto mb-6 rounded-2xl p-1 bg-gradient-to-br from-indigo-500 to-blue-600 shadow-lg transform group-hover:rotate-3 transition-transform duration-300">
                    <img src="{{ $websiteSettings->getImageUrl('vice_principal_image') }}" alt="Vice Principal" class="w-full h-full object-cover rounded-xl bg-white" onerror="this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'">
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $websiteSettings->vice_principal_name ?? 'উপাধ্যক্ষ মহোদয়' }}</h4>
                <div class="inline-block px-4 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold mb-4">উপাধ্যক্ষ</div>
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 italic text-gray-600 text-sm">
                    "{{ $websiteSettings->vice_principal_message_short ?? 'নিয়মানুবর্তিতা ও অধ্যবসায় সাফল্যের চাবিকাঠি।' }}"
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="{{ route('tenant.administration.committee') }}" class="group bg-white p-8 rounded-2xl shadow-xl border border-gray-100 flex items-center hover:shadow-2xl transition-all duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl mr-6 shadow-lg relative z-10">
                <i class="fas fa-users text-3xl text-white"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-gray-900 mb-1">পরিচালনা পর্ষদ</h3>
                <p class="text-gray-500">আমাদের পরিচালনা কমিটির সদস্যদের তথ্য</p>
            </div>
            <div class="ml-auto relative z-10">
                <i class="fas fa-arrow-right text-emerald-500 transform group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <a href="{{ route('tenant.administration.staff') }}" class="group bg-white p-8 rounded-2xl shadow-xl border border-gray-100 flex items-center hover:shadow-2xl transition-all duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl mr-6 shadow-lg relative z-10">
                <i class="fas fa-chalkboard-teacher text-3xl text-white"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-gray-900 mb-1">শিক্ষক ও কর্মচারী</h3>
                <p class="text-gray-500">আমাদের দক্ষ শিক্ষক ও কর্মচারীবৃন্দ</p>
            </div>
            <div class="ml-auto relative z-10">
                <i class="fas fa-arrow-right text-orange-500 transform group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>
    </div>
</div>
@endsection

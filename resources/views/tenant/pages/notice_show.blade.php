@extends('tenant.layouts.web')

@section('title', $notice->title)

@section('content')
<!-- Hero Section (Simplified for Detail Page) -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-12 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $notice->title }}</h1>
            <div class="flex items-center justify-center gap-6 text-sm opacity-90">
                <span class="flex items-center"><i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($notice->publish_date ?? $notice->created_at)->format('d M, Y') }}</span>
                <span class="flex items-center"><i class="far fa-user mr-2"></i> অ্যাডমিন</span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <!-- Badge Section -->
        <div class="px-8 pt-8 flex justify-between items-center">
            @php
                $priorityLabels = [
                    'low' => 'সাধারণ',
                    'normal' => 'সাধারণ',
                    'high' => 'গুরুত্বপূর্ণ',
                    'urgent' => 'জরুরি'
                ];
                $priorityColors = [
                    'low' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    'normal' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    'high' => 'bg-orange-50 text-orange-600 border-orange-100',
                    'urgent' => 'bg-red-50 text-red-600 border-red-100'
                ];
            @endphp
            <div class="{{ $priorityColors[$notice->priority] ?? $priorityColors['normal'] }} px-5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border">
                {{ $priorityLabels[$notice->priority] ?? 'সাধারণ' }}
            </div>
            
            <a href="{{ route('tenant.notice') }}" class="text-gray-400 hover:text-indigo-600 transition-colors flex items-center gap-2 font-bold text-sm">
                <i class="fas fa-arrow-left"></i> সকল নোটিশ
            </a>
        </div>

        <!-- Content -->
        <div class="p-8 md:p-12">
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-8 rounded-2xl border border-gray-100 italic">
                {{ $notice->content }}
            </div>

            @if($notice->attachment)
            <div class="mt-12 group bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl shadow-inner group-hover:rotate-12 transition-transform">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <h5 class="text-xl font-bold">সংযুক্ত ফাইল</h5>
                        <p class="text-indigo-100 text-sm">নোটিশের সাথে একটি ফাইল সংযুক্ত আছে</p>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-indigo-50 transition-all flex items-center gap-3">
                    <i class="fas fa-download"></i> ফাইলটি ডাউনলোড করুন
                </a>
            </div>
            @endif
        </div>

        <!-- Meta info -->
        <div class="bg-gray-50 px-12 py-8 border-t border-gray-100">
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                    <span>স্মার্ট পাঠশালা সিস্টেম দ্বারা প্রকাশিত</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

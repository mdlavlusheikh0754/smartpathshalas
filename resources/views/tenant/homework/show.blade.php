@php
    $schoolSettings = \App\Models\SchoolSetting::getSettings();
    $websiteSettings = \App\Models\WebsiteSetting::getSettings();
@endphp

@extends('tenant.layouts.web')

@section('title', 'বাড়ির কাজের বিস্তারিত - ' . $homework->title)

@section('content')
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">বাড়ির কাজের বিস্তারিত</h1>
                    <p class="text-blue-100 flex items-center gap-2">
                        <span class="bg-white/20 px-2 py-1 rounded text-sm">{{ $homework->subject }}</span>
                        <i class="fas fa-chevron-right text-xs opacity-50"></i>
                        <span class="bg-white/20 px-2 py-1 rounded text-sm">{{ $homework->class }}{{ $homework->section ? ' (' . $homework->section . ')' : '' }}</span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('homework.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-white transition-colors backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $homework->title }}</h2>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center gap-1"><i class="far fa-calendar-alt text-blue-500"></i> {{ $homework->assigned_date->format('d M, Y') }}</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span class="flex items-center gap-1"><i class="far fa-user text-purple-500"></i> {{ $homework->teacher->name ?? 'শিক্ষক' }}</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $homework->isOverdue() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $homework->isOverdue() ? 'সময় শেষ' : 'চলমান' }}
                        </span>
                    </div>

                    <div class="prose max-w-none text-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">বিবরণ</h3>
                        <div class="whitespace-pre-line mb-8">{{ $homework->description }}</div>

                        @if($homework->instructions)
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-8">
                                <h3 class="text-lg font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> বিশেষ নির্দেশনা
                                </h3>
                                <div class="whitespace-pre-line text-blue-800">{{ $homework->instructions }}</div>
                            </div>
                        @endif
                    </div>

                    @if($homework->attachment)
                        <div class="border-t border-gray-100 pt-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">সংযুক্তি</h3>
                            <a href="{{ $homework->getAttachmentUrl() }}" target="_blank" class="group flex items-center p-4 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors border border-gray-100 hover:border-blue-100">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm text-blue-600 text-xl group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-download"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900 group-hover:text-blue-700">ফাইল ডাউনলোড করুন</p>
                                    <p class="text-sm text-gray-500 group-hover:text-blue-600">ক্লিক করে দেখুন অথবা সেভ করুন</p>
                                </div>
                                <i class="fas fa-external-link-alt ml-auto text-gray-400 group-hover:text-blue-500"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Due Date Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">জমা দেওয়ার শেষ সময়</h3>
                    
                    <div class="w-20 h-20 bg-{{ $homework->isOverdue() ? 'red' : 'blue' }}-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="far fa-clock text-3xl text-{{ $homework->isOverdue() ? 'red' : 'blue' }}-500"></i>
                    </div>
                    
                    <div class="text-2xl font-bold text-gray-900 mb-1">
                        {{ $homework->due_date->format('d M, Y') }}
                    </div>
                    <div class="text-gray-500 text-sm mb-4">
                        {{ $homework->due_date->format('l') }}
                    </div>

                    @if($homework->isOverdue())
                        <div class="inline-flex items-center gap-2 text-red-600 bg-red-50 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-triangle"></i> সময় পার হয়েছে
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 text-blue-600 bg-blue-50 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-hourglass-half"></i> {{ $homework->due_date->diffForHumans() }} বাকি
                        </div>
                    @endif
                </div>

                <!-- Teacher Actions (Only for Teachers) -->
                @auth
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">অ্যাকশন</h3>
                        <div class="space-y-3">
                            <a href="{{ route('tenant.homework.edit', $homework->id) }}" class="flex items-center justify-center w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                                <i class="fas fa-edit mr-2"></i>সম্পাদনা করুন
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection

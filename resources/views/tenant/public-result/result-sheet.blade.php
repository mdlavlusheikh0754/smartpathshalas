@php
    $schoolSettings = \App\Models\SchoolSetting::getSettings();
    $websiteSettings = \App\Models\WebsiteSetting::getSettings();
@endphp

@extends('tenant.layouts.web')

@section('title', 'একাডেমিক ট্রান্সক্রিপ্ট - ' . $student->name)

@section('styles')
    <style>
        @media print {
            nav, footer, .sticky, .no-print { display: none !important; }
            body { background: white !important; }
            .print-shadow-none { box-shadow: none !important; }
            .print-border { border: 2px solid #000 !important; }
            .main-content { padding-top: 0 !important; }
        }
    </style>
@endsection

@section('content')
    <div class="bg-gray-100 min-h-screen py-8 px-4 main-content">
        <div class="max-w-4xl mx-auto">
            <!-- Action Buttons -->
            <div class="mb-6 flex justify-between items-center no-print">
                <a href="{{ route('public.result.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition font-medium bg-white px-4 py-2 rounded-lg shadow-sm">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
                <button onclick="window.print()" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow-md font-medium">
                    <i class="fas fa-print"></i> প্রিন্ট করুন
                </button>
            </div>

            <!-- Result Card -->
            <div class="bg-white p-8 md:p-12 rounded-xl shadow-xl print:shadow-none print:p-0">
                
                <!-- School Header -->
                <div class="text-center border-b-2 border-gray-200 pb-6 mb-8">
                    <div class="flex flex-col items-center justify-center gap-4 mb-4">
                        @if($schoolSettings->logo)
                            <img src="{{ $schoolSettings->getImageUrl('logo') }}" alt="Logo" class="h-24 w-auto">
                        @elseif($websiteSettings->logo)
                            <img src="{{ $websiteSettings->getImageUrl('logo') }}" alt="Logo" class="h-24 w-auto">
                        @endif
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-wide">{{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}</h1>
                            <p class="text-gray-600 mt-1">{{ $schoolSettings->address ?? $websiteSettings->address ?? '' }}</p>
                        </div>
                    </div>
                    <div class="inline-block bg-blue-50 px-6 py-2 rounded-full border border-blue-100">
                        <h2 class="text-xl font-bold text-blue-800">{{ $exam->name }} - {{ date('Y') }}</h2>
                    </div>
                </div>

                <!-- Student Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-3">
                        <div class="flex">
                            <span class="w-32 text-gray-500 font-medium">শিক্ষার্থীর নাম:</span>
                            <span class="font-bold text-gray-800">{{ $student->name_bn ?? $student->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-gray-500 font-medium">স্টুডেন্ট আইডি:</span>
                            <span class="font-bold text-gray-800">{{ $student->student_id }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-gray-500 font-medium">রোল নম্বর:</span>
                            <span class="font-bold text-gray-800">{{ $student->roll }}</span>
                        </div>
                    </div>
                    <div class="space-y-3 md:text-right">
                        <div class="flex md:justify-end">
                            <span class="w-32 text-gray-500 font-medium md:text-right md:mr-4">শ্রেণী:</span>
                            <span class="font-bold text-gray-800">{{ $class->name }}</span>
                        </div>
                        <div class="flex md:justify-end">
                            <span class="w-32 text-gray-500 font-medium md:text-right md:mr-4">সেকশন:</span>
                            <span class="font-bold text-gray-800">{{ $class->section }}</span>
                        </div>
                        <div class="flex md:justify-end">
                            <span class="w-32 text-gray-500 font-medium md:text-right md:mr-4">ফলাফল প্রকাশের তারিখ:</span>
                            <span class="font-bold text-gray-800">{{ date('d M, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Result Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">মোট নম্বর</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalMarks }} / {{ $totalPossible }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">GPA</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $overallGPA }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">গ্রেড</p>
                        <p class="text-2xl font-bold {{ $overallGrade == 'F' ? 'text-red-600' : 'text-green-600' }}">{{ $overallGrade }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">ফলাফল</p>
                        <p class="text-2xl font-bold {{ $overallResult == 'ফেল' ? 'text-red-600' : 'text-green-600' }}">{{ $overallResult }}</p>
                    </div>
                </div>

                <!-- Marks Table -->
                <div class="overflow-x-auto mb-8">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিষয়</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">মোট নম্বর</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">প্রাপ্ত নম্বর</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">গ্রেড</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($studentResults as $result)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $result['subject_name'] }}
                                        @if($result['is_group'])
                                            <span class="text-xs text-blue-600 ml-1">(গ্রুপ)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ $result['total_marks'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold {{ $result['status'] == 'fail' ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $result['obtained_marks'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                        {{ $result['grade'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                        {{ number_format($result['gpa'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Signatures -->
                <div class="grid grid-cols-3 gap-8 mt-16 pt-8">
                    <div class="text-center">
                        <div class="border-t border-gray-400 w-3/4 mx-auto"></div>
                        <p class="mt-2 text-sm text-gray-600">অভিভাবকের স্বাক্ষর</p>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-400 w-3/4 mx-auto"></div>
                        <p class="mt-2 text-sm text-gray-600">শ্রেণী শিক্ষকের স্বাক্ষর</p>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-400 w-3/4 mx-auto"></div>
                        <p class="mt-2 text-sm text-gray-600">প্রধান শিক্ষকের স্বাক্ষর</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8 no-print">
                <p class="text-sm text-gray-500">Powered by SmartPathshala</p>
            </div>
        </div>
    </div>
@endsection

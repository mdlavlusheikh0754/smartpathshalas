@extends('layouts.tenant')

@section('title', $notice->title)

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">নোটিশের বিস্তারিত</h1>
                <p class="text-gray-600 mt-1">নোটিশটি প্রিভিউ করুন</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('tenant.notices.edit', $notice->id) }}" class="bg-indigo-50 text-indigo-600 px-6 py-3 rounded-xl font-bold border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-all flex items-center gap-2">
                    <i class="fas fa-edit"></i> এডিট করুন
                </a>
                <a href="{{ route('tenant.notices.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex justify-between items-start mb-6">
                    @php
                        $priorityClasses = [
                            'low' => 'bg-gray-100 text-gray-700',
                            'normal' => 'bg-blue-100 text-blue-700',
                            'high' => 'bg-orange-100 text-orange-700',
                            'urgent' => 'bg-red-100 text-red-700'
                        ];
                        $priorityLabels = [
                            'low' => 'নিম্ন',
                            'normal' => 'সাধারণ',
                            'high' => 'উচ্চ',
                            'urgent' => 'জরুরি'
                        ];
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold {{ $priorityClasses[$notice->priority] ?? 'bg-blue-100 text-blue-700' }}">
                        {{ $priorityLabels[$notice->priority] ?? 'সাধারণ' }}
                    </span>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">প্রকাশের তারিখ</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($notice->publish_date ?? $notice->created_at)->format('d M, Y') }}</p>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">{{ $notice->title }}</h2>
            </div>

            <div class="p-8">
                <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $notice->content }}
                </div>

                @if($notice->attachment)
                <div class="mt-8 p-6 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600 text-xl border border-gray-100">
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-900">সংযুক্ত ফাইল</h5>
                            <p class="text-xs text-gray-500">নোটিশের সাথে একটি ফাইল সংযুক্ত আছে</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition-all shadow-md flex items-center gap-2">
                        <i class="fas fa-external-link-alt"></i> ফাইল দেখুন
                    </a>
                </div>
                @endif
            </div>

            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">প্রকাশক</p>
                        <p class="text-sm font-bold text-gray-900">অ্যাডমিন প্যানেল</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div>
                        <p class="text-xs text-gray-500">স্ট্যাটাস</p>
                        <p class="text-sm font-bold {{ $notice->status == 'active' ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $notice->status == 'active' ? 'সক্রিয়' : 'খসড়া' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('tenant.layouts.web')

@section('title', 'ছুটির তালিকা')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">ছুটির তালিকা {{ date('Y') }}</h1>
            <p class="text-xl text-emerald-100 max-w-2xl mx-auto">সরকারি ও বিশেষ ছুটির বিস্তারিত সময়সূচী</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($holidays->isEmpty())
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-16 text-center -mt-20 relative z-20">
            <div class="w-24 h-24 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-calendar-xmark text-4xl text-emerald-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">কোনো ছুটির দিন যোগ করা হয়নি</h3>
            <p class="text-gray-600">শীঘ্রই ছুটির দিন যোগ করা হবে। অনুগ্রহ করে পরে আসুন।</p>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 hover:shadow-emerald-100/50 transition-all duration-300 -mt-20 relative z-20">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-10 py-8 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-white/20 p-4 rounded-2xl mr-5 backdrop-blur-md shadow-lg">
                        <i class="fas fa-calendar-alt text-2xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white">বার্ষিক ছুটির পরিকল্পনা</h2>
                        <p class="text-emerald-100 text-sm mt-1">শিক্ষাবর্ষ {{ date('Y') }}</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span class="bg-white/10 text-white px-6 py-2 rounded-full border border-white/20 text-sm font-bold backdrop-blur-sm">
                        মোট {{ $holidays->count() }}টি ছুটি
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-10 py-6 font-bold text-gray-800 uppercase tracking-wider text-xs">ছুটির কারণ ও বিবরণ</th>
                            <th class="px-10 py-6 font-bold text-gray-800 uppercase tracking-wider text-xs">সময়কাল</th>
                            <th class="px-10 py-6 font-bold text-gray-800 uppercase tracking-wider text-xs text-center">মোট দিন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($holidays as $holiday)
                        <tr class="hover:bg-emerald-50/30 transition-all group">
                            <td class="px-10 py-8">
                                <div class="flex items-center">
                                    <div class="w-2 h-10 bg-emerald-500 rounded-full mr-4 scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                                    <div>
                                        <span class="font-bold text-gray-900 block text-lg group-hover:text-emerald-700 transition-colors">{{ $holiday->holiday_name }}</span>
                                        @if($holiday->description)
                                            <span class="text-sm text-gray-500 block mt-1 leading-relaxed max-w-md">{{ $holiday->description }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="space-y-2">
                                    <div class="flex items-center text-gray-700 font-medium">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 text-emerald-600 text-xs">
                                            <i class="fas fa-play"></i>
                                        </div>
                                        <span>{{ $holiday->start_date->translatedFormat('d F, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-700 font-medium">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3 text-red-500 text-xs">
                                            <i class="fas fa-stop"></i>
                                        </div>
                                        <span>{{ $holiday->end_date->translatedFormat('d F, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-3xl font-black text-emerald-600">{{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }}</span>
                                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">দিন</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

@extends('tenant.layouts.web')

@section('title', 'একাডেমিক ক্যালেন্ডার')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">একাডেমিক ক্যালেন্ডার {{ date('Y') }}</h1>
            <p class="text-xl text-violet-100 max-w-2xl mx-auto">পুরো বছরের শিক্ষা কার্যক্রমের বিস্তারিত পরিকল্পনা</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($calendars->isEmpty())
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-16 text-center -mt-20 relative z-20">
            <div class="w-24 h-24 bg-violet-50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-calendar-alt text-4xl text-violet-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">কোনো ক্যালেন্ডার আপলোড করা হয়নি</h3>
            <p class="text-gray-600">শীঘ্রই ক্যালেন্ডার আপলোড করা হবে। অনুগ্রহ করে পরে আসুন।</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 -mt-20 relative z-20">
            @foreach($calendars as $calendar)
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col">
                <div class="bg-gradient-to-r from-violet-600 to-purple-700 px-8 py-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <h3 class="text-2xl font-black text-white">{{ $calendar->academicSession->session_name }}</h3>
                            <p class="text-violet-100 text-xs font-bold uppercase tracking-widest mt-1">Academic Session</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-md shadow-lg">
                            <i class="fas fa-calendar-day text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="p-8 flex-1 flex flex-col">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600 shadow-inner group-hover:bg-violet-600 group-hover:text-white transition-colors flex-shrink-0">
                            <i class="fas fa-file-pdf text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">File Name</p>
                            <span class="font-bold text-gray-900 leading-tight block">{{ $calendar->file_name }}</span>
                        </div>
                    </div>
                    
                    @if($calendar->description)
                        <div class="bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-100 flex-1">
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $calendar->description }}</p>
                        </div>
                    @endif
                    
                    <a href="{{ route('tenant.academic.calendar.download', $calendar->id) }}" class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-black py-4 px-8 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-violet-200 group/btn">
                        <i class="fas fa-download group-hover/btn:translate-y-1 transition-transform"></i> 
                        ডাউনলোড করুন
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

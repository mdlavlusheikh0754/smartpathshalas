@extends('tenant.layouts.web')

@section('title', 'ম্যানেজিং কমিটি')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">ম্যানেজিং কমিটি</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের দক্ষ ও দূরদর্শী পরিচালনা পর্ষদ</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10 -mt-20 relative z-20">
        @php
            $committee = [
                ['name' => 'আব্দুর রহমান', 'role' => 'সভাপতি', 'image' => null],
                ['name' => 'মোঃ শাহিন আলম', 'role' => 'সহ-সভাপতি', 'image' => null],
                ['name' => 'মোঃ হাফিজুর রহমান', 'role' => 'সদস্য সচিব', 'image' => null],
                ['name' => 'জনাব আলী আহমেদ', 'role' => 'সদস্য', 'image' => null],
                ['name' => 'মোসাম্মাৎ ফাতেমা খাতুন', 'role' => 'সদস্য', 'image' => null],
                ['name' => 'মোঃ আব্দুল কুদ্দুস', 'role' => 'অভিভাবক সদস্য', 'image' => null],
            ];
        @endphp

        @foreach($committee as $member)
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="h-72 bg-gray-100 relative overflow-hidden">
                @if($member['image'])
                    <img src="{{ $member['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-200">
                        <i class="fas fa-user-tie text-7xl text-gray-300 group-hover:scale-110 transition-transform duration-500"></i>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 transform group-hover:-translate-y-2 transition-transform">
                    <p class="text-white font-bold text-2xl mb-1">{{ $member['name'] }}</p>
                    <span class="inline-block bg-blue-500 text-white text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">
                        {{ $member['role'] }}
                    </span>
                </div>
            </div>
            <div class="p-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-0.5">Contact Number</p>
                            <p class="text-gray-800 font-bold">০১৭XXXXXXXX</p>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-300 group-hover:border-blue-200 group-hover:text-blue-500 transition-all">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

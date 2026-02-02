@extends('tenant.layouts.web')

@section('title', 'নোটিশ')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">নোটিশ বোর্ড</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">প্রতিষ্ঠানের সকল সাম্প্রতিক ঘোষণা ও সংবাদ</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($notices as $notice)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 relative group overflow-hidden">
            <!-- Priority Indicator -->
            <div class="absolute top-0 left-0 w-2 h-full {{ $notice->priority == 'urgent' ? 'bg-red-500' : ($notice->priority == 'high' ? 'bg-orange-500' : 'bg-indigo-500') }}"></div>
            
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center">
                    <div class="{{ $notice->priority == 'urgent' ? 'bg-red-50 text-red-600' : ($notice->priority == 'high' ? 'bg-orange-50 text-orange-600' : 'bg-indigo-50 text-indigo-600') }} px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        @php
                            $priorityLabels = [
                                'low' => 'সাধারণ',
                                'normal' => 'সাধারণ',
                                'high' => 'গুরুত্বপূর্ণ',
                                'urgent' => 'জরুরি'
                            ];
                        @endphp
                        {{ $priorityLabels[$notice->priority] ?? 'সাধারণ' }}
                    </div>
                </div>
                <div class="flex items-center text-gray-400 text-sm">
                    <i class="far fa-calendar-alt mr-2"></i>
                    {{ \Carbon\Carbon::parse($notice->publish_date ?? $notice->created_at)->format('d M, Y') }}
                </div>
            </div>

            <h4 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-indigo-600 transition-colors">{{ $notice->title }}</h4>
            <p class="text-gray-600 leading-relaxed mb-6 line-clamp-3">
                {{ Str::limit(strip_tags($notice->content), 150) }}
            </p>
            
            <div class="flex flex-wrap items-center justify-between gap-4 mt-auto pt-6 border-t border-gray-50">
                <a href="{{ route('tenant.notice.show', $notice->id) }}" class="inline-flex items-center text-indigo-600 font-bold hover:text-indigo-700 transition-colors">
                    বিস্তারিত পড়ুন 
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                
                @if($notice->attachment)
                    <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" class="inline-flex items-center bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl font-bold text-sm hover:bg-emerald-100 transition-colors">
                        <i class="fas fa-paperclip mr-2"></i> ফাইল দেখুন
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="md:col-span-2 text-center py-20 bg-white rounded-3xl shadow-xl border border-gray-100">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-bullhorn text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">বর্তমানে কোনো নোটিশ নেই</h3>
            <p class="text-gray-500">নতুন কোনো নোটিশ প্রকাশ করা হলে এখানে দেখা যাবে।</p>
        </div>
        @endforelse
    </div>

    @if(method_exists($notices, 'links'))
    <div class="mt-12">
        {{ $notices->links() }}
    </div>
    @endif
</div>
@endsection

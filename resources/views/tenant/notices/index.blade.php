@extends('layouts.tenant')

@section('title', 'নোটিশ বোর্ড')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">নোটিশ বোর্ড</h1>
            <p class="text-gray-600 mt-1">সকল নোটিশ দেখুন এবং পরিচালনা করুন</p>
        </div>
        <a href="{{ route('tenant.notices.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            নতুন নোটিশ প্রকাশ করুন
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">তারিখ</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">শিরোনাম</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">গুরুত্ব</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($notices as $notice)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($notice->publish_date ?? $notice->created_at)->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $notice->title }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($notice->content, 60) }}</div>
                        </td>
                        <td class="px-6 py-4">
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
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $priorityClasses[$notice->priority] ?? $priorityClasses['normal'] }}">
                                {{ $priorityLabels[$notice->priority] ?? 'সাধারণ' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($notice->status == 'active')
                                <span class="flex items-center gap-1.5 text-emerald-600 text-xs font-bold">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> সক্রিয়
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-gray-500 text-xs font-bold">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span> খসড়া
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('tenant.notices.show', $notice->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="দেখুন">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('tenant.notices.edit', $notice->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="সম্পাদনা করুন">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('tenant.notices.destroy', $notice->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই নোটিশটি মুছে ফেলতে চান?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="মুছে ফেলুন">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9.586 12l-2.293 2.293a1 1 0 101.414 1.414L10 13.414l2.293 2.293a1 1 0 001.414-1.414L11.414 12l2.293-2.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="text-lg font-medium">কোনো নোটিশ পাওয়া যায়নি</p>
                            <p class="text-sm text-gray-400 mt-1">নতুন নোটিশ যোগ করতে "নতুন নোটিশ প্রকাশ করুন" বাটনে ক্লিক করুন</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notices->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $notices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

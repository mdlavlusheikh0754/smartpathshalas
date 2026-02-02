@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-4xl">
        <!-- Header -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('tenant.support.index') }}" class="bg-white p-2 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 bengali-text">মেসেজ ডিটেইলস</h1>
                <p class="text-gray-600 mt-1 bengali-text">প্রেরক: {{ $message->name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2 bengali-text">প্রেরকের তথ্য</h4>
                        <div class="space-y-3">
                            <p class="text-gray-900 font-medium bengali-text">নাম: {{ $message->name }}</p>
                            <p class="text-gray-900">ইমেইল: <a href="mailto:{{ $message->email }}" class="text-indigo-600 hover:underline">{{ $message->email }}</a></p>
                            @if($message->phone)
                                <p class="text-gray-900">ফোন: <a href="tel:{{ $message->phone }}" class="text-indigo-600 hover:underline">{{ $message->phone }}</a></p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2 bengali-text">মেসেজ তথ্য</h4>
                        <div class="space-y-3">
                            <p class="text-gray-900 font-medium bengali-text">বিষয়: {{ $message->subject }}</p>
                            <p class="text-gray-900 bengali-text">তারিখ: {{ \App\Helpers\BengaliHelper::toBengaliDateTime($message->created_at) }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 bengali-text">মেসেজ</h4>
                    <div class="bg-gray-50 rounded-xl p-6 text-gray-800 leading-relaxed bengali-text">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <form action="{{ route('tenant.support.destroy', $message->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি এই মেসেজটি মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-6 py-3 rounded-xl font-bold transition-all flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="bengali-text">মেসেজটি মুছে ফেলুন</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

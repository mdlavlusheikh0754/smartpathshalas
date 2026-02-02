@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">হোস্টেল ম্যানেজমেন্ট</h1>
        <p class="text-gray-600 mt-1">হোস্টেল এবং রুম পরিচালনা করুন</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm">মোট রুম</p>
            <h3 class="text-3xl font-bold mt-1">০</h3>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm">বরাদ্দকৃত</p>
            <h3 class="text-3xl font-bold mt-1">০</h3>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-orange-100 text-sm">খালি</p>
            <h3 class="text-3xl font-bold mt-1">০</h3>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-purple-100 text-sm">মোট শিক্ষার্থী</p>
            <h3 class="text-3xl font-bold mt-1">০</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">হোস্টেল তালিকা</h3>
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">কোনো হোস্টেল নেই</h3>
            <p class="text-gray-600 mb-4">এখনো কোনো হোস্টেল যোগ করা হয়নি।</p>
            <a href="{{ route('tenant.hostel.rooms') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                রুম ম্যানেজমেন্ট
            </a>
        </div>
    </div>
</div>
@endsection

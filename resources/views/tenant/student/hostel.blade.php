@extends('tenant.layouts.portal')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">আমার হোস্টেল তথ্য</h1>
        <p class="text-gray-600">আপনার হোস্টেল ও রুম বরাদ্দের বিস্তারিত</p>
    </div>

    @if($allocation)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                <i class="fas fa-hotel text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">হোস্টেলের নাম</p>
                <h3 class="text-xl font-bold text-gray-900">{{ $allocation->room->hostel->name }}</h3>
                <p class="text-xs text-gray-400 mt-1">{{ $allocation->room->hostel->address }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                <i class="fas fa-door-open text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">রুম ও বেড তথ্য</p>
                <h3 class="text-xl font-bold text-gray-900">রুম নং: {{ $allocation->room->room_no }}</h3>
                <p class="text-xs text-indigo-600 font-bold mt-1">ধরন: {{ $allocation->room->room_type }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">বরাদ্দ তারিখ</p>
                <h3 class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('d M, Y') }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center text-yellow-600">
                <i class="fas fa-money-bill-wave text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">মাসিক কস্ট</p>
                <h3 class="text-xl font-bold text-gray-900">৳{{ number_format($allocation->room->cost_per_bed, 2) }}</h3>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
            <i class="fas fa-hotel text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">কোন রুম বরাদ্দ নেই</h3>
        <p class="text-gray-500 mt-2">বর্তমানে আপনার নামে কোন হোস্টেল রুম বরাদ্দ করা হয়নি।</p>
    </div>
    @endif
</div>
@endsection

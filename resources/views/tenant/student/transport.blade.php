@extends('tenant.layouts.portal')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">আমার পরিবহন তথ্য</h1>
        <p class="text-gray-600">আপনার স্কুল ট্রান্সপোর্ট ও রুটের বিস্তারিত</p>
    </div>

    @if($allocation)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                <i class="fas fa-route text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">নির্ধারিত রুট</p>
                <h3 class="text-xl font-bold text-gray-900">{{ $allocation->route->route_title }}</h3>
                <p class="text-xs text-gray-400 mt-1">{{ $allocation->route->start_point }} - {{ $allocation->route->end_point }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                <i class="fas fa-bus text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">যানবাহন তথ্য</p>
                <h3 class="text-xl font-bold text-gray-900">নম্বর: {{ $allocation->vehicle->vehicle_number }}</h3>
                <p class="text-xs text-indigo-600 font-bold mt-1">মডেল: {{ $allocation->vehicle->vehicle_model }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                <i class="fas fa-user-tie text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">চালক ও যোগাযোগ</p>
                <h3 class="text-xl font-bold text-gray-900">{{ $allocation->vehicle->driver_name }}</h3>
                <p class="text-xs text-gray-600 font-bold mt-1">ফোন: {{ $allocation->vehicle->driver_contact }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center text-yellow-600">
                <i class="fas fa-map-marker-alt text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">পিকআপ পয়েন্ট ও ফি</p>
                <h3 class="text-xl font-bold text-gray-900">{{ $allocation->pickup_point ?? 'নির্ধারিত নয়' }}</h3>
                <p class="text-xs text-yellow-700 font-bold mt-1">মাসিক ফি: ৳{{ number_format($allocation->monthly_fee, 2) }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
            <i class="fas fa-bus text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">কোন পরিবহন বরাদ্দ নেই</h3>
        <p class="text-gray-500 mt-2">বর্তমানে আপনার জন্য কোন স্কুল ট্রান্সপোর্ট বরাদ্দ করা হয়নি।</p>
    </div>
    @endif
</div>
@endsection

@extends('layouts.tenant')

@section('title', 'পরিবহন ব্যবস্থাপনা')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12M8 7a2 2 0 100-4 2 2 0 000 4zm0 0H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-4m0 0V5a2 2 0 10-4 0v2m0 0H8m8 0h4"></path>
                        </svg>
                        পরিবহন ব্যবস্থাপনা
                    </h1>
                    <p class="text-gray-600 mt-2 text-lg">শিক্ষার্থীদের পরিবহন বরাদ্দ পরিচালনা করুন</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-blue-600">{{ $allocations->count() }}</div>
                    <p class="text-gray-600">মোট বরাদ্দ</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-8 sticky top-24 h-fit">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        নতুন বরাদ্দ
                    </h2>
                    <form action="{{ route('tenant.transport.allocations.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">শিক্ষার্থী</label>
                            <select name="student_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
                                <option value="">সিলেক্ট করুন</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name_bn ?? $student->name_en ?? 'Unknown' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">রুট</label>
                            <select name="route_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
                                <option value="">সিলেক্ট করুন</option>
                                @foreach($routes as $route)
                                <option value="{{ $route->id }}">{{ $route->route_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">যানবাহন</label>
                            <select name="vehicle_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
                                <option value="">সিলেক্ট করুন</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">পিকআপ পয়েন্ট</label>
                            <input type="text" name="pickup_point" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="যেমন: স্কুলের সামনে">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">মাসিক ফি (টাকা)</label>
                            <input type="number" step="0.01" name="monthly_fee" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required placeholder="0.00">
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                            বরাদ্দ করুন
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-8 py-6 border-b-2 border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            বরাদ্দ তালিকা
                        </h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        @if($allocations->count() > 0)
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">শিক্ষার্থী</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">রুট</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">যানবাহন</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">পিকআপ</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">ফি</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($allocations as $allocation)
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $allocation->student->name_bn ?? $allocation->student->name_en ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $allocation->route->route_title }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $allocation->vehicle->vehicle_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $allocation->pickup_point ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">৳ {{ number_format($allocation->monthly_fee, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('tenant.transport.allocations.destroy', $allocation->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium text-sm" onclick="return confirm('আপনি কি এই বরাদ্দ মুছে ফেলতে চান?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                মুছুন
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="px-8 py-16 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-600 font-semibold text-lg">কোনো বরাদ্দ নেই</p>
                            <p class="text-gray-500 mt-2">বাম দিকের ফর্ম থেকে নতুন বরাদ্দ যোগ করুন</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

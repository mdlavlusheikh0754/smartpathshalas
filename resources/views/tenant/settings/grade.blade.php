@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">গ্রেড সেটিংস</h1>
                <p class="text-gray-600 mt-1">গ্রেড এবং মার্কস কনফিগারেশন পরিচালনা করুন</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
                <button onclick="openAddGradeModal()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    নতুন গ্রেড যোগ করুন
                </button>
            </div>
        </div>

        <!-- Grade System Overview -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">বর্তমান গ্রেডিং সিস্টেম</h2>
                    <p class="text-purple-100">মোট গ্রেড: <span class="font-bold">৫টি</span> | পূর্ণমান: <span class="font-bold">১০০</span> | পাসমার্ক: <span class="font-bold">৩৩</span></p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grade Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">গ্রেড তালিকা</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold">গ্রেড</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">গ্রেড পয়েন্ট</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">মার্কস রেঞ্জ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">বিবরণ</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach([
                            ['grade' => 'A+', 'point' => '5.00', 'min' => '80', 'max' => '100', 'desc' => 'অসাধারণ', 'color' => 'green'],
                            ['grade' => 'A', 'point' => '4.00', 'min' => '70', 'max' => '79', 'desc' => 'অতি উত্তম', 'color' => 'blue'],
                            ['grade' => 'A-', 'point' => '3.50', 'min' => '60', 'max' => '69', 'desc' => 'উত্তম', 'color' => 'indigo'],
                            ['grade' => 'B', 'point' => '3.00', 'min' => '50', 'max' => '59', 'desc' => 'ভালো', 'color' => 'purple'],
                            ['grade' => 'C', 'point' => '2.00', 'min' => '40', 'max' => '49', 'desc' => 'গড়', 'color' => 'yellow'],
                            ['grade' => 'D', 'point' => '1.00', 'min' => '33', 'max' => '39', 'desc' => 'পাস', 'color' => 'orange'],
                            ['grade' => 'F', 'point' => '0.00', 'min' => '0', 'max' => '32', 'desc' => 'অকৃতকার্য', 'color' => 'red']
                        ] as $grade)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="bg-{{ $grade['color'] }}-100 text-{{ $grade['color'] }}-600 px-4 py-2 rounded-lg text-lg font-bold">{{ $grade['grade'] }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $grade['point'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $grade['min'] }} - {{ $grade['max'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $grade['desc'] }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-colors" title="সম্পাদনা">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    @if($grade['grade'] != 'F')
                                    <button class="bg-red-100 hover:bg-red-200 text-red-600 p-2 rounded-lg transition-colors" title="মুছুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Marks Configuration -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">মার্কস কনফিগারেশন</h3>
            <form action="#" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ণমান *</label>
                        <input type="number" name="total_marks" value="100" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পাসমার্ক *</label>
                        <input type="number" name="pass_marks" value="33" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">সর্বোচ্চ GPA</label>
                        <input type="number" step="0.01" name="max_gpa" value="5.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddGradeModal() {
    alert('নতুন গ্রেড যোগ করার ফর্ম শীঘ্রই যুক্ত করা হবে');
}
</script>
@endsection

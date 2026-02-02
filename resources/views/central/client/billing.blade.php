<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    বিলিং ও পেমেন্ট
                </h2>
                <p class="text-sm text-gray-600 mt-1">আপনার পেমেন্ট হিস্ট্রি এবং বিলিং তথ্য</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ড্যাশবোর্ডে ফিরুন
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Current Plan -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-2">বর্তমান প্ল্যান</p>
                        <h3 class="text-4xl font-bold mb-2">প্রিমিয়াম প্ল্যান</h3>
                        <p class="text-blue-100 text-lg">৳৫,০০০/মাস</p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 text-sm mb-2">পরবর্তী পেমেন্ট</p>
                        <p class="text-2xl font-bold">১৫ ফেব্রুয়ারি, ২০২৬</p>
                        <p class="text-blue-100 text-sm mt-2">৩১ দিন বাকি</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">পেমেন্ট করুন</h3>
                        
                        <form action="{{ route('client.payment') }}" method="POST">
                            @csrf
                            
                            <!-- Payment Method -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">পেমেন্ট মেথড নির্বাচন করুন</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                                        <input type="radio" name="payment_method" value="bkash" class="mr-3" checked>
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-pink-600 font-bold text-lg">bK</span>
                                            </div>
                                            <span class="font-semibold text-gray-800">বিকাশ</span>
                                        </div>
                                    </label>

                                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                                        <input type="radio" name="payment_method" value="nagad" class="mr-3">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-orange-600 font-bold text-lg">N</span>
                                            </div>
                                            <span class="font-semibold text-gray-800">নগদ</span>
                                        </div>
                                    </label>

                                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                                        <input type="radio" name="payment_method" value="card" class="mr-3">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <span class="font-semibold text-gray-800">কার্ড</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">পেমেন্ট পরিমাণ</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-500 font-semibold">৳</span>
                                    <input type="number" name="amount" value="5000" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold text-lg" readonly>
                                </div>
                            </div>

                            <!-- Phone Number (for mobile banking) -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">মোবাইল নম্বর</label>
                                <input type="tel" name="phone" placeholder="০১৭xxxxxxxx" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                পেমেন্ট সম্পন্ন করুন
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Billing Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">বিলিং সামারি</h4>
                        
                        <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">মাসিক চার্জ</span>
                                <span class="font-semibold text-gray-800">৳৫,০০০</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">স্কুল সংখ্যা</span>
                                <span class="font-semibold text-gray-800">{{ $tenants->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">ভ্যাট (১৫%)</span>
                                <span class="font-semibold text-gray-800">৳৭৫০</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">মোট</span>
                            <span class="text-2xl font-bold text-blue-600">৳৫,৭৫০</span>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h5 class="font-semibold text-blue-900 mb-2">পেমেন্ট সম্পর্কে</h5>
                                <p class="text-sm text-blue-800">পেমেন্ট সম্পন্ন হওয়ার পর আপনার অ্যাকাউন্ট স্বয়ংক্রিয়ভাবে আপডেট হবে।</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-8">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">পেমেন্ট হিস্ট্রি</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    তারিখ
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    ট্রানজেকশন আইডি
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    পেমেন্ট মেথড
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    পরিমাণ
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    স্ট্যাটাস
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    অ্যাকশন
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample Payment History -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">১৫ জানুয়ারি, ২০২৬</div>
                                    <div class="text-xs text-gray-500">১০:৩০ AM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono text-gray-900">TXN123456789</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-2">
                                            <span class="text-pink-600 font-bold text-xs">bK</span>
                                        </div>
                                        <span class="text-sm text-gray-900">বিকাশ</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">৳৫,৭৫০</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        সফল
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium">
                                        রিসিট ডাউনলোড
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">১৫ ডিসেম্বর, ২০২৫</div>
                                    <div class="text-xs text-gray-500">০৯:১৫ AM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono text-gray-900">TXN123456788</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-2">
                                            <span class="text-orange-600 font-bold text-xs">N</span>
                                        </div>
                                        <span class="text-sm text-gray-900">নগদ</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">৳৫,৭৫০</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        সফল
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium">
                                        রিসিট ডাউনলোড
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

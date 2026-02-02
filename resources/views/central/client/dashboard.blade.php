<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    স্বাগতম, {{ auth()->user()->name }}!
                </h2>
                <p class="text-sm text-gray-600 mt-1">আপনার স্কুল ম্যানেজমেন্ট প্যানেল</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    স্ট্যাটাস: অ্যাক্টিভ
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Schools Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">মোট স্কুল</p>
                            <h3 class="text-4xl font-bold mt-2">{{ $tenants->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Domains Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">সক্রিয় ডোমেইন</p>
                            <h3 class="text-4xl font-bold mt-2">{{ $tenants->sum(fn($t) => $t->domains->count()) }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Account Status Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">অ্যাকাউন্ট স্ট্যাটাস</p>
                            <h3 class="text-2xl font-bold mt-2">সক্রিয়</h3>
                            <p class="text-purple-100 text-xs mt-1">পরবর্তী পেমেন্ট: ১৫ ফেব্রুয়ারি</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- School Portal Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-bold text-gray-800">আপনার স্কুল পোর্টাল</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">আপনার স্কুলের যাবতীয় কাজ পরিচালনা করুন এখান থেকে।</p>
                    @if($tenants->isNotEmpty() && $tenants->first()->domains->isNotEmpty())
                        <a href="http://{{ $tenants->first()->domains->first()->domain }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold text-sm group">
                            স্কুলে প্রবেশ করুন
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @else
                        <span class="text-gray-400 text-sm">কোনো স্কুল পাওয়া যায়নি</span>
                    @endif
                </div>

                <!-- Billing Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-bold text-gray-800">বিলিং ও পেমেন্ট</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">পরবর্তী পেমেন্টের তারিখ:</p>
                    <p class="text-sm font-semibold text-gray-800 mb-4">১৫ ফেব্রুয়ারি, ২০২৬</p>
                    <a href="{{ route('client.billing') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        পেমেন্ট করুন
                    </a>
                </div>

                <!-- Support Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-bold text-gray-800">সাহায্য প্রয়োজন?</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">যেকোনো সমস্যায় আমাদের সাপোর্ট টিমে কথা বলুন।</p>
                    <button class="inline-flex items-center px-4 py-2 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors">
                        টিকেট ওপেন করুন
                    </button>
                </div>
            </div>

            <!-- Schools List -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">আপনার স্কুলসমূহ</h3>
                </div>

                <div class="p-6">
                    @forelse($tenants as $tenant)
                        <div class="mb-4 last:mb-0 p-6 border border-gray-200 rounded-xl hover:border-blue-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 h-14 w-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($tenant->id, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">{{ $tenant->id }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">তৈরি হয়েছে: {{ $tenant->created_at->format('d M, Y') }}</p>
                                        
                                        <div class="mt-3 space-y-2">
                                            @foreach($tenant->domains as $domain)
                                                <a href="http://{{ $domain->domain }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline group">
                                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    {{ $domain->domain }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        সক্রিয়
                                    </span>
                                    @if($tenant->domains->isNotEmpty())
                                        <a href="http://{{ $tenant->domains->first()->domain }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            প্রবেশ করুন
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">আপনার কোনো স্কুল নেই</p>
                            <p class="text-gray-400 text-sm mt-2">নতুন স্কুল তৈরি করতে অ্যাডমিনের সাথে যোগাযোগ করুন</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

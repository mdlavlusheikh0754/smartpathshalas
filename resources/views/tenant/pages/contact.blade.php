@extends('tenant.layouts.web')

@section('title', 'যোগাযোগ')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">যোগাযোগ</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">যেকোনো প্রয়োজনে আমাদের সাথে যোগাযোগ করুন</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Information -->
        <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <div class="flex items-center mb-8">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-headset text-2xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">যোগাযোগের তথ্য</h2>
                        <p class="text-gray-500 text-sm">সরাসরি আমাদের সাথে কথা বলুন</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 group">
                        <div class="bg-white p-3 rounded-xl mr-4 shadow-md text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">ঠিকানা</h4>
                            <p class="text-gray-700">{{ $websiteSettings->address ?? 'ঢাকা, বাংলাদেশ' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-100 group">
                        <div class="bg-white p-3 rounded-xl mr-4 shadow-md text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-phone-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">ফোন</h4>
                            <p class="text-gray-700">{{ $websiteSettings->phone ?? '০১XXXXXXXXX' }}</p>
                            @if($websiteSettings->phone_2)
                                <p class="text-gray-700">{{ $websiteSettings->phone_2 }}</p>
                            @endif
                            @if($websiteSettings->phone_3)
                                <p class="text-gray-700">{{ $websiteSettings->phone_3 }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100 group">
                        <div class="bg-white p-3 rounded-xl mr-4 shadow-md text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">ইমেইল</h4>
                            <p class="text-gray-700">{{ $websiteSettings->email ?? 'info@school.com' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google Map -->
            @if($websiteSettings->google_map_embed)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden min-h-[300px] relative">
                <div class="aspect-video">
                    {!! $websiteSettings->google_map_embed !!}
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden min-h-[300px] relative group">
                <div class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-map-marked-alt text-4xl text-blue-500"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">Google Map</h4>
                        <p class="text-gray-500 mt-2">গুগল ম্যাপ এম্বেড কোড সেটিংস পেজ থেকে যোগ করুন</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Contact Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-8">
                <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl mr-4 shadow-lg">
                    <i class="fas fa-paper-plane text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">মেসেজ পাঠান</h2>
                    <p class="text-gray-500 text-sm">আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">সফল!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('tenant.contact.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">আপনার নাম</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="নাম">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">আপনার ইমেইল</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="ইমেইল">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">আপনার ফোন নম্বর</label>
                    <input type="tel" name="phone" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="ফোন নম্বর">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">বিষয়</label>
                    <input type="text" name="subject" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="বিষয়">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">আপনার মেসেজ</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="বিস্তারিত লিখুন..."></textarea>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-300">
                    মেসেজ পাঠান
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

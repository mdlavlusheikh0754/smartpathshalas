@extends('tenant.layouts.web')

@section('title', 'শিক্ষক-কর্মচারী')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">শিক্ষক-কর্মচারী</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের অভিজ্ঞ ও নিবেদিতপ্রাণ শিক্ষকমন্ডলী</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Teachers Section -->
    @if($teachers && $teachers->count() > 0)
    <div class="mb-16 -mt-20 relative z-20">
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-10 border border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">শিক্ষকবৃন্দ</h2>
            </div>
            <div class="hidden md:block">
                <span class="bg-blue-50 text-blue-600 px-5 py-2 rounded-full text-sm font-bold border border-blue-100">
                    মোট {{ $teachers->count() }} জন
                </span>
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($teachers as $teacher)
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 group text-center flex flex-col">
                <div class="p-8 flex-1">
                    <div class="relative inline-block mb-6">
                        @if($teacher->photo)
                            <img src="{{ route('tenant.files', ['path' => $teacher->photo]) }}" alt="{{ $teacher->name }}" class="w-28 h-28 rounded-full object-cover border-4 border-blue-50 shadow-inner group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-600 group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner" style="display: none;">
                                <i class="fas fa-user-tie text-5xl"></i>
                            </div>
                        @else
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-600 group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                                <i class="fas fa-user-tie text-5xl"></i>
                            </div>
                        @endif
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-2xl shadow-lg flex items-center justify-center text-blue-600 border border-gray-100">
                            <i class="fas fa-award text-sm"></i>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-black text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $teacher->name }}</h3>
                    <p class="text-blue-500 font-bold text-sm mb-3 uppercase tracking-wider">{{ $teacher->designation ?? 'শিক্ষক' }}</p>
                    
                    @if($teacher->subject)
                        <div class="inline-block bg-gray-50 px-4 py-1.5 rounded-full border border-gray-100 mb-4">
                            <p class="text-gray-500 text-[10px] uppercase tracking-widest font-black">{{ $teacher->subject }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-8 py-5 border-t border-gray-50 group-hover:bg-blue-50 transition-colors">
                    <div class="flex justify-center gap-4">
                        @if($teacher->email)
                        <a href="mailto:{{ $teacher->email }}" class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-envelope"></i>
                        </a>
                        @endif
                        @if($teacher->phone)
                        <a href="tel:{{ $teacher->phone }}" class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-gray-400 hover:bg-emerald-500 hover:text-white transition-all shadow-sm hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-phone-alt"></i>
                        </a>
                        @endif
                        <a href="#" class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Staff Section -->
    @if($staffFromSettings && count($staffFromSettings) > 0)
    <div class="@if($teachers && $teachers->count() > 0) mt-24 @else -mt-20 relative z-20 @endif">
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-10 border border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">অন্যান্য কর্মচারী</h2>
            </div>
            <div class="hidden md:block">
                @php
                    $validStaffCount = collect($staffFromSettings)->filter(fn($m) => !empty($m['name']))->count();
                @endphp
                <span class="bg-emerald-50 text-emerald-600 px-5 py-2 rounded-full text-sm font-bold border border-emerald-100">
                    মোট {{ $validStaffCount }} জন
                </span>
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($staffFromSettings as $member)
            @if(isset($member['name']) && !empty($member['name']))
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 group text-center flex flex-col">
                <div class="p-8 flex-1">
                    <div class="relative inline-block mb-6">
                        @if(isset($member['photo']) && !empty($member['photo']))
                            <img src="{{ tenant_storage_url($member['photo']) }}" alt="{{ $member['name'] }}" class="w-28 h-28 rounded-full object-cover border-4 border-emerald-50 shadow-inner group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center text-emerald-600 group-hover:from-emerald-600 group-hover:to-teal-600 group-hover:text-white transition-all duration-500 shadow-inner">
                                <i class="fas fa-user-tie text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-black text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors">{{ $member['name'] }}</h3>
                    <p class="text-emerald-500 font-bold text-sm mb-3 uppercase tracking-wider">{{ $member['designation'] ?? 'কর্মচারী' }}</p>
                    
                    @if(isset($member['subject']) && !empty($member['subject']))
                        <div class="inline-block bg-gray-50 px-4 py-1.5 rounded-full border border-gray-100 mb-4">
                            <p class="text-gray-500 text-[10px] uppercase tracking-widest font-black">{{ $member['subject'] }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-8 py-5 border-t border-gray-50 group-hover:bg-emerald-50 transition-colors">
                    <div class="flex justify-center gap-4">
                        @if(isset($member['email']) && !empty($member['email']))
                        <a href="mailto:{{ $member['email'] }}" class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-gray-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-envelope"></i>
                        </a>
                        @endif
                        @if(isset($member['phone']) && !empty($member['phone']))
                        <a href="tel:{{ $member['phone'] }}" class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-gray-400 hover:bg-teal-500 hover:text-white transition-all shadow-sm hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-phone-alt"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    @if(($teachers && $teachers->count() > 0) || ($staffFromSettings && count($staffFromSettings) > 0))
    @else
    <div class="text-center py-16">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-users text-3xl text-gray-400"></i>
        </div>
        <p class="text-gray-500 text-lg mb-4">কোনো শিক্ষক বা কর্মচারী যোগ করা হয়নি</p>
        <a href="{{ route('tenant.settings.website') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all">
            <i class="fas fa-cog"></i>
            সেটিংসে যান
        </a>
    </div>
    @endif
</div>
@endsection

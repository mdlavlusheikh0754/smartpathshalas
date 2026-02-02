@extends('tenant.layouts.web')

@section('title', 'গ্যালারি')

@section('content')
@php
    $galleries = \App\Models\Gallery::active()->ordered()->get();
    $photos = $galleries->where('type', 'photo');
    $audios = $galleries->where('type', 'audio');
    $videos = $galleries->where('type', 'video');
@endphp

<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">গ্যালারি</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের স্মরণীয় কিছু মুহূর্ত</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Photos Section -->
    @if($photos->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                <i class="fas fa-images text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">ফটো গ্যালারি</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($photos as $index => $item)
            @php
                $titles = ['স্কুল ক্যাম্পাস', 'ক্লাসরুম', 'বিজ্ঞান ল্যাব', 'কম্পিউটার ল্যাব', 'লাইব্রেরি', 'ক্রীড়া প্রতিযোগিতা', 'সাংস্কৃতিক অনুষ্ঠান', 'পুরস্কার বিতরণী', 'শিক্ষা সফর'];
                $icons = ['fa-school', 'fa-chalkboard-teacher', 'fa-flask', 'fa-desktop', 'fa-book', 'fa-running', 'fa-music', 'fa-award', 'fa-bus'];
                $colors = ['blue', 'indigo', 'emerald', 'purple', 'orange', 'rose', 'cyan', 'teal', 'violet'];
                $colorClasses = [
                    'blue' => 'from-blue-500 to-indigo-600',
                    'indigo' => 'from-indigo-500 to-purple-600',
                    'emerald' => 'from-emerald-500 to-teal-600',
                    'purple' => 'from-purple-500 to-pink-600',
                    'orange' => 'from-orange-500 to-red-600',
                    'rose' => 'from-rose-500 to-red-600',
                    'cyan' => 'from-cyan-500 to-blue-600',
                    'teal' => 'from-teal-500 to-green-600',
                    'violet' => 'from-violet-500 to-purple-600'
                ];
                $title = $item->title ?? $titles[$index % count($titles)] ?? 'ফটো';
                $icon = $icons[$index % count($icons)] ?? 'fa-image';
                $color = $colors[$index % count($colors)] ?? 'blue';
            @endphp
            <div class="group relative overflow-hidden rounded-3xl shadow-xl aspect-[4/3] bg-gray-200 border-4 border-white hover:border-indigo-100 transition-all duration-300">
                <img src="/files/{{ $item->file_path }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-300"></div>
                
                <div class="absolute inset-0 p-8 flex flex-col justify-end transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $colorClasses[$color] }} flex items-center justify-center text-white shadow-lg transform group-hover:rotate-6 transition-transform">
                            <i class="fas {{ $icon }} text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">{{ $title }}</h3>
                    </div>
                    <p class="text-gray-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                        প্রতিষ্ঠানের কার্যক্রমের চিত্র দেখুন
                    </p>
                </div>
                
                <!-- Expand Button -->
                <div class="absolute top-6 right-6 w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:scale-100 scale-50">
                    <i class="fas fa-expand-alt text-xl"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Audio Section -->
    @if($audios->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                <i class="fas fa-music text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">অডিও গ্যালারি</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($audios as $audio)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-music text-2xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 truncate">{{ $audio->title }}</h3>
                        @if($audio->category)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full mt-1">{{ $audio->category }}</span>
                        @endif
                    </div>
                </div>
                <audio controls class="w-full">
                    <source src="/files/{{ $audio->file_path }}" type="audio/mpeg">
                    আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                </audio>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Video Section -->
    @if($videos->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-gradient-to-br from-red-500 to-pink-600 p-3 rounded-xl">
                <i class="fas fa-video text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">ভিডিও গ্যালারি</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($videos as $video)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="aspect-video bg-gray-900">
                    <iframe 
                        src="{{ $video->video_url }}" 
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-1">{{ $video->title }}</h3>
                    @if($video->category)
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">{{ $video->category }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($galleries->count() == 0)
    <div class="text-center py-20">
        <div class="bg-white px-8 py-10 rounded-3xl shadow-xl border border-gray-100 max-w-2xl mx-auto">
            <div class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-images text-3xl text-indigo-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">গ্যালারি শীঘ্রই আসছে...</h3>
            <p class="text-gray-500 mb-8 leading-relaxed">আমাদের বার্ষিক ক্রীড়া প্রতিযোগিতা, সাংস্কৃতিক অনুষ্ঠান এবং অন্যান্য শিক্ষা সফরের ছবিগুলো শীঘ্রই গ্যালারিতে যুক্ত করা হবে।</p>
            <a href="{{ route('tenant.home') }}" class="inline-block px-8 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                হোম পেজে যান
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

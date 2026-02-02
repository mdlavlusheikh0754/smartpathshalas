@extends('tenant.layouts.web')

@section('title', 'পরিচিতি')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">পরিচিতি</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের সম্পর্কে বিস্তারিত জানুন</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- History Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl mr-4 shadow-lg">
                <i class="fas fa-landmark text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">প্রতিষ্ঠানের ইতিহাস</h2>
                <p class="text-gray-500 text-sm">আমাদের যাত্রা ও ঐতিহ্য</p>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 md:p-10 rounded-xl border border-blue-100">
            @if($websiteSettings->history_text)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    @php
                        $paragraphs = preg_split('/\n\s*\n/', $websiteSettings->history_text);
                    @endphp
                    @foreach($paragraphs as $paragraph)
                        @if(trim($paragraph))
                            <p class="mb-6 last:mb-0">{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-info-circle text-3xl text-blue-400"></i>
                    </div>
                    <p class="text-gray-500 text-lg mb-4">প্রতিষ্ঠানের ইতিহাস সম্পর্কে তথ্য প্রদান করা হয়নি</p>
                    <a href="{{ route('tenant.settings.website') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-cog"></i>
                        সেটিংসে যান
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Vision & Mission Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Vision -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-2xl mr-4 shadow-lg">
                    <i class="fas fa-eye text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">আমাদের ভিশন</h2>
                    <p class="text-gray-500 text-sm">Vision</p>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-100 h-full">
                @if($websiteSettings->vision_text)
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        @php
                            $paragraphs = preg_split('/\n\s*\n/', $websiteSettings->vision_text);
                        @endphp
                        @foreach($paragraphs as $paragraph)
                            @if(trim($paragraph))
                                <p class="mb-4 last:mb-0">{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">ভিশন সম্পর্কে তথ্য প্রদান করা হয়নি</p>
                @endif
            </div>
        </div>

        <!-- Mission -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl mr-4 shadow-lg">
                    <i class="fas fa-bullseye text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">আমাদের মিশন</h2>
                    <p class="text-gray-500 text-sm">Mission</p>
                </div>
            </div>
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-6 rounded-xl border border-emerald-100 h-full">
                @if($websiteSettings->mission_text)
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        @php
                            $paragraphs = preg_split('/\n\s*\n/', $websiteSettings->mission_text);
                        @endphp
                        @foreach($paragraphs as $paragraph)
                            @if(trim($paragraph))
                                <p class="mb-4 last:mb-0">{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">মিশন সম্পর্কে তথ্য প্রদান করা হয়নি</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Infrastructure Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center mb-6">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl mr-4 shadow-lg">
                <i class="fas fa-building text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">ভৌত অবকাঠামো</h2>
                <p class="text-gray-500 text-sm">আমাদের সুযোগ-সুবিধাসমূহ</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 md:p-10 rounded-xl border border-orange-100">
            @if($websiteSettings->infrastructure_text)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    @php
                        $paragraphs = preg_split('/\n\s*\n/', $websiteSettings->infrastructure_text);
                    @endphp
                    @foreach($paragraphs as $paragraph)
                        @if(trim($paragraph))
                            <p class="mb-6 last:mb-0">{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">ভৌত অবকাঠামো সম্পর্কে তথ্য প্রদান করা হয়নি</p>
            @endif
        </div>
    </div>

    <!-- Facilities Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center mb-8">
            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 p-4 rounded-2xl mr-4 shadow-lg">
                <i class="fas fa-star text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">সুবিধাদি</h2>
                <p class="text-gray-500 text-sm">আমাদের শিক্ষার্থীদের জন্য বিশেষ সুবিধাসমূহ</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $facilityIcons = [
                    'লাইব্রেরি' => ['icon' => 'fa-book-reader', 'color' => 'blue', 'desc' => '৫০০০+ বই'],
                    'বিজ্ঞান ল্যাব' => ['icon' => 'fa-flask', 'color' => 'emerald', 'desc' => 'আধুনিক যন্ত্রপাতি'],
                    'কম্পিউটার ল্যাব' => ['icon' => 'fa-desktop', 'color' => 'purple', 'desc' => '৫০টি কম্পিউটার'],
                    'খেলার মাঠ' => ['icon' => 'fa-running', 'color' => 'orange', 'desc' => '২ একর'],
                    'ক্যান্টিন' => ['icon' => 'fa-utensils', 'color' => 'red', 'desc' => 'স্বাস্থ্যকর খাবার'],
                    'পরিবহন সুবিধা' => ['icon' => 'fa-bus', 'color' => 'indigo', 'desc' => 'নিরাপদ পরিবহন']
                ];
                
                $facilities = $websiteSettings->facilities ?? ['লাইব্রেরি', 'বিজ্ঞান ল্যাব', 'কম্পিউটার ল্যাব', 'খেলার মাঠ'];
            @endphp
            
            @foreach($facilities as $facility)
                @php
                    $facilityData = $facilityIcons[$facility] ?? ['icon' => 'fa-star', 'color' => 'slate', 'desc' => 'উপলব্ধ'];
                    $colorMap = [
                        'blue' => 'from-blue-500 to-indigo-600',
                        'emerald' => 'from-emerald-500 to-teal-600',
                        'purple' => 'from-purple-500 to-pink-600',
                        'orange' => 'from-orange-500 to-red-600',
                        'red' => 'from-red-500 to-rose-600',
                        'indigo' => 'from-indigo-500 to-purple-600',
                        'slate' => 'from-slate-500 to-gray-600'
                    ];
                    $gradientClass = $colorMap[$facilityData['color']] ?? $colorMap['slate'];
                @endphp
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-br {{ $gradientClass }} p-3 rounded-xl mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas {{ $facilityData['icon'] }} text-xl text-white"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">{{ $facility }}</h4>
                        </div>
                        <p class="text-gray-600">{{ $facilityData['desc'] }}</p>
                    </div>
                    <div class="h-1 bg-gradient-to-r {{ $gradientClass }} opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

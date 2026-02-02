@extends('tenant.layouts.web')

@section('title', 'সিলেবাস')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">পাঠ্যক্রম ও সিলেবাস</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের আধুনিক ও যুগোপযোগী শিক্ষাক্রম</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($syllabuses->isEmpty())
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-16 text-center -mt-20 relative z-20">
            <div class="w-24 h-24 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-file-pdf text-4xl text-blue-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">কোনো সিলেবাস আপলোড করা হয়নি</h3>
            <p class="text-gray-600">শীঘ্রই সিলেবাস আপলোড করা হবে। অনুগ্রহ করে পরে আসুন।</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 -mt-20 relative z-20">
            @foreach($syllabusesByClass as $classId => $classSyllabuses)
                @php
                    $class = $classSyllabuses->first()->schoolClass;
                @endphp
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <h3 class="text-2xl font-black text-white leading-tight">{{ $class->name }}</h3>
                                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">{{ $class->section }}</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-md shadow-lg">
                                <i class="fas fa-book-open text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="space-y-4">
                            @foreach($classSyllabuses as $syllabus)
                                <a href="{{ route('tenant.academic.syllabus.download', $syllabus->id) }}" class="flex items-center justify-between p-5 bg-gray-50 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 border border-gray-100 group/item hover:shadow-lg hover:-translate-y-1">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4 shadow-sm group-hover/item:text-blue-600 transition-colors">
                                            <i class="fas fa-file-pdf text-xl"></i>
                                        </div>
                                        <div>
                                            <span class="block font-black text-gray-900 group-hover/item:text-white transition-colors">{{ $syllabus->exam ? $syllabus->exam->name : 'সাধারণ সিলেবাস' }}</span>
                                            @if($syllabus->subject)
                                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest group-hover/item:text-blue-100 transition-colors">{{ $syllabus->subject->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl shadow-sm text-gray-400 group-hover/item:bg-white group-hover/item:text-blue-600 transition-all">
                                        <i class="fas fa-download text-sm"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

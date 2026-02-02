@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 bengali-text">অভিযোগের বিবরণ</h1>
                <p class="text-gray-600 mt-2 bengali-text">অভিযোগ নং: #C{{ str_pad($complaint->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tenant.complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors bengali-text">তালিকায় ফিরে যান</a>
                <a href="{{ route('tenant.complaints.edit', $complaint->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors bengali-text">এডিট করুন</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 bengali-text">{{ $complaint->subject }}</h2>
                    <div class="prose max-w-none text-gray-700 bengali-text whitespace-pre-line">
                        {{ $complaint->description }}
                    </div>
                    
                    @if($complaint->expected_solution)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 bengali-text">প্রত্যাশিত সমাধান</h3>
                        <p class="text-gray-700 bengali-text">{{ $complaint->expected_solution }}</p>
                    </div>
                    @endif
                </div>

                @if($complaint->attachments && count($complaint->attachments) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 bengali-text">সংযুক্ত ফাইলসমূহ</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($complaint->attachments as $attachment)
                        <div class="flex items-center p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-indigo-50 rounded flex items-center justify-center text-indigo-600 mr-3">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment['name'] }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($complaint->status === 'resolved')
                <div class="bg-green-50 rounded-xl border border-green-100 p-6">
                    <h3 class="text-lg font-bold text-green-900 mb-2 bengali-text">সমাধানের বিবরণ</h3>
                    <p class="text-green-800 bengali-text mb-4">{{ $complaint->resolution_notes }}</p>
                    <div class="flex items-center text-sm text-green-700">
                        <span class="mr-4 bengali-text">সমাধান করেছেন: {{ $complaint->resolver->name ?? 'অ্যাডমিন' }}</span>
                        <span class="bengali-text">তারিখ: {{ toBengaliDate($complaint->resolved_at, 'd F, Y') }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 bengali-text">অভিযোগকারীর তথ্য</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">নাম</p>
                            <p class="text-sm font-bold text-gray-900 bengali-text">{{ $complaint->is_anonymous ? 'গোপনীয়' : $complaint->complainant_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">ধরন</p>
                            @php
                                $complainantTypes = [
                                    'student' => 'শিক্ষার্থী',
                                    'parent' => 'অভিভাবক',
                                    'teacher' => 'শিক্ষক',
                                    'staff' => 'কর্মচারী',
                                    'other' => 'অন্যান্য'
                                ];
                            @endphp
                            <p class="text-sm font-bold text-gray-900 bengali-text">{{ $complainantTypes[$complaint->complainant_type] ?? $complaint->complainant_type }}</p>
                        </div>
                        @if(!$complaint->is_anonymous)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">যোগাযোগ নম্বর</p>
                            <p class="text-sm font-bold text-gray-900">{{ toBengaliNumber($complaint->contact_number) }}</p>
                        </div>
                        @if($complaint->email)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">ইমেইল</p>
                            <p class="text-sm font-bold text-gray-900">{{ $complaint->email }}</p>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 bengali-text">স্ট্যাটাস ও অগ্রাধিকার</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1 bengali-text">অবস্থা</p>
                            @php
                                $statuses = [
                                    'new' => ['label' => 'নতুন', 'class' => 'bg-indigo-100 text-indigo-800'],
                                    'pending' => ['label' => 'বিচারাধীন', 'class' => 'bg-yellow-100 text-yellow-800'],
                                    'resolved' => ['label' => 'সমাধানকৃত', 'class' => 'bg-green-100 text-green-800'],
                                    'cancelled' => ['label' => 'বাতিল', 'class' => 'bg-red-100 text-red-800']
                                ];
                                $sData = $statuses[$complaint->status] ?? ['label' => 'নতুন', 'class' => 'bg-indigo-100 text-indigo-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sData['class'] }} bengali-text">{{ $sData['label'] }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1 bengali-text">অগ্রাধিকার</p>
                            @php
                                $priorities = [
                                    'low' => ['label' => 'নিম্ন', 'class' => 'bg-gray-100 text-gray-800'],
                                    'medium' => ['label' => 'মাধ্যম', 'class' => 'bg-blue-100 text-blue-800'],
                                    'high' => ['label' => 'উচ্চ', 'class' => 'bg-orange-100 text-orange-800'],
                                    'urgent' => ['label' => 'জরুরি', 'class' => 'bg-red-100 text-red-800']
                                ];
                                $pData = $priorities[$complaint->priority] ?? ['label' => 'মাধ্যম', 'class' => 'bg-blue-100 text-blue-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pData['class'] }} bengali-text">{{ $pData['label'] }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">তারিখ</p>
                            <p class="text-sm font-bold text-gray-900 bengali-text">{{ toBengaliDate($complaint->created_at, 'd F, Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">অভিযোগ ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-2 bengali-text">শিক্ষার্থী, অভিভাবক ও শিক্ষকদের অভিযোগ পরিচালনা</p>
        </div>
        <a href="{{ route('tenant.complaints.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="bengali-text">নতুন অভিযোগ দাখিল</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">মোট অভিযোগ</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">{{ toBengaliNumber($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">সমাধানকৃত</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">{{ toBengaliNumber($stats['resolved']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">বিচারাধীন</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">{{ toBengaliNumber($stats['pending']) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 bengali-text">জরুরি</p>
                    <p class="text-2xl font-bold text-gray-900 bengali-text">{{ toBengaliNumber($stats['urgent']) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <form action="{{ route('tenant.complaints.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-900 bengali-text">অভিযোগের তালিকা</h2>
                <div class="flex flex-wrap md:flex-nowrap space-x-3">
                    <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                        <option value="">সকল অবস্থা</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>নতুন</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>বিচারাধীন</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>সমাধানকৃত</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>বাতিল</option>
                    </select>
                    <select name="type" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                        <option value="">সকল ধরন</option>
                        <option value="academic" {{ request('type') == 'academic' ? 'selected' : '' }}>শিক্ষাগত</option>
                        <option value="behavioral" {{ request('type') == 'behavioral' ? 'selected' : '' }}>আচরণগত</option>
                        <option value="facility" {{ request('type') == 'facility' ? 'selected' : '' }}>সুবিধা</option>
                        <option value="financial" {{ request('type') == 'financial' ? 'selected' : '' }}>আর্থিক</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                    </select>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="অভিযোগ অনুসন্ধান করুন..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors bengali-text">ফিল্টার</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">অভিযোগ নং</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">বিষয়</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">অভিযোগকারী</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">ধরন</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">তারিখ</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">অগ্রাধিকার</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">অবস্থা</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($complaints as $complaint)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#C{{ str_pad($complaint->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 bengali-text">{{ $complaint->subject }}</div>
                            <div class="text-sm text-gray-500 bengali-text">{{ Str::limit($complaint->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bengali-text">{{ $complaint->is_anonymous ? 'গোপনীয়' : $complaint->complainant_name }}</div>
                            <div class="text-sm text-gray-500 bengali-text">
                                @php
                                    $types = [
                                        'student' => 'শিক্ষার্থী',
                                        'parent' => 'অভিভাবক',
                                        'teacher' => 'শিক্ষক',
                                        'staff' => 'কর্মচারী',
                                        'other' => 'অন্যান্য'
                                    ];
                                @endphp
                                {{ $types[$complaint->complainant_type] ?? $complaint->complainant_type }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $complaintTypes = [
                                    'academic' => ['label' => 'শিক্ষাগত', 'class' => 'bg-red-100 text-red-800'],
                                    'behavioral' => ['label' => 'আচরণগত', 'class' => 'bg-orange-100 text-orange-800'],
                                    'facility' => ['label' => 'সুবিধা', 'class' => 'bg-blue-100 text-blue-800'],
                                    'financial' => ['label' => 'আর্থিক', 'class' => 'bg-emerald-100 text-emerald-800'],
                                    'other' => ['label' => 'অন্যান্য', 'class' => 'bg-gray-100 text-gray-800']
                                ];
                                $typeData = $complaintTypes[$complaint->complaint_type] ?? ['label' => 'অন্যান্য', 'class' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeData['class'] }} bengali-text">{{ $typeData['label'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bengali-text">{{ toBengaliDate($complaint->created_at, 'd F, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('tenant.complaints.show', $complaint->id) }}" class="text-blue-600 hover:text-blue-900" title="দেখুন">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('tenant.complaints.edit', $complaint->id) }}" class="text-indigo-600 hover:text-indigo-900" title="এডিট">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('tenant.complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি এই অভিযোগটি মুছে ফেলতে চান?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="মুছে ফেলুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-500 bengali-text">
                            কোন অভিযোগ পাওয়া যায়নি
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($complaints->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $complaints->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

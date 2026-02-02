@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">উপস্থিতি ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">RFID, QR Code বা Manual - তিনটি পদ্ধতিতে উপস্থিতি নিন</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tenant.attendance.zkteco.index') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                ZKTeco ডিভাইস
            </a>
            <a href="{{ route('tenant.attendance.settings') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                QR/RFID সেটিংস
            </a>
            <a href="{{ route('tenant.attendance.take') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h6"></path>
                </svg>
                উপস্থিতি নিন
            </a>
            <a href="{{ route('tenant.attendance.id-cards') }}" class="bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.896 1.75-2 2.167a11.97 11.97 0 00-4 0c-1.104-.417-2-1.283-2-2.167"></path>
                </svg>
                ID কার্ড
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm">আজকের উপস্থিতি</p>
            <h3 class="text-3xl font-bold mt-1">{{ $percentage }}%</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm">উপস্থিত</p>
            <h3 class="text-3xl font-bold mt-1">{{ $present }}</h3>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-red-100 text-sm">অনুপস্থিত</p>
            <h3 class="text-3xl font-bold mt-1">{{ $absent }}</h3>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-yellow-100 text-sm">ছুটি</p>
            <h3 class="text-3xl font-bold mt-1">{{ $leave }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">আজকের উপস্থিতি রেকর্ড ({{ $recentAttendance->count() }})</h3>
            <a href="{{ route('tenant.attendance.report') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">সকল রিপোর্ট দেখুন &rarr;</a>
        </div>
        
        <div class="space-y-3">
            @forelse($recentAttendance as $attendance)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-200 border-2 border-white shadow-sm ring-1 ring-gray-100">
                            @if($attendance->student && $attendance->student->photo)
                                <img src="{{ route('tenant.files', ['path' => $attendance->student->photo]) }}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 font-bold text-lg">
                                    {{ substr(optional($attendance->student)->name_en ?? 'S', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ optional($attendance->student)->name_en ?? optional($attendance->student)->name_bn ?? 'Unknown Student' }}</h4>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    ID: {{ optional($attendance->student)->student_id ?? 'N/A' }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    Class: {{ $attendance->class }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    Roll: {{ optional($attendance->student)->roll ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm
                            {{ $attendance->status == 'present' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                            {{ $attendance->status == 'absent' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                            {{ $attendance->status == 'leave' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : '' }}
                            {{ $attendance->status == 'late' ? 'bg-orange-100 text-orange-700 border border-orange-200' : '' }}
                        ">
                            <span class="w-2 h-2 mr-1.5 rounded-full 
                                {{ $attendance->status == 'present' ? 'bg-green-500' : '' }}
                                {{ $attendance->status == 'absent' ? 'bg-red-500' : '' }}
                                {{ $attendance->status == 'leave' ? 'bg-yellow-500' : '' }}
                                {{ $attendance->status == 'late' ? 'bg-orange-500' : '' }}
                            "></span>
                            {{ $attendance->status }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1 font-medium flex items-center justify-end gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ date('h:i A', strtotime($attendance->created_at)) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div class="bg-white rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h6"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 font-medium text-lg">এখনো কোনো উপস্থিতি রেকর্ড করা হয়নি</p>
                    <p class="text-gray-400 text-sm mt-1">আজকের উপস্থিতি শুরু করতে উপরের "উপস্থিতি নিন" বাটনে ক্লিক করুন</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

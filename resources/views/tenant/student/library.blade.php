@extends('tenant.layouts.portal')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">আমার লাইব্রেরি তথ্য</h1>
        <p class="text-gray-600">আপনার ইস্যু করা বইয়ের তালিকা</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-sm font-bold text-gray-700">বইয়ের নাম</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-700">ইস্যুর তারিখ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-700">ফেরত তারিখ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-700 text-center">স্ট্যাটাস</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-700 text-right">জরিমানা</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($issues as $issue)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $issue->book->title }}</div>
                        <div class="text-xs text-gray-500">{{ $issue->book->author }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $issue->issue_date->format('d M, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $issue->due_date->format('d M, Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusClass = [
                                'issued' => 'bg-blue-100 text-blue-700',
                                'returned' => 'bg-green-100 text-green-700',
                                'lost' => 'bg-red-100 text-red-700',
                            ][$issue->status] ?? 'bg-gray-100 text-gray-700';
                            
                            $statusText = [
                                'issued' => 'ইস্যু করা',
                                'returned' => 'ফেরত দেওয়া হয়েছে',
                                'lost' => 'হারিয়ে গেছে',
                            ][$issue->status] ?? $issue->status;
                        @endphp
                        <span class="px-3 py-1 {{ $statusClass }} rounded-full text-xs font-bold">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                        {{ $issue->fine_amount > 0 ? '৳' . number_format($issue->fine_amount, 2) : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">কোন বই ইস্যু করা নেই</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

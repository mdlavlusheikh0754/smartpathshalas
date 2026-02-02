@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.library.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-colors duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">বই ইস্যু</h1>
                    <p class="text-gray-600 mt-1">শিক্ষার্থীদের বই প্রদান করুন</p>
                </div>
            </div>
            <button onclick="openIssueModal()" class="bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                নতুন ইস্যু করুন
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">সফল!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">ত্রুটি!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Issues Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শিক্ষার্থীর নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বইয়ের নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">ইস্যুর তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">ফেরত তারিখ (আশা করা)</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($issues as $issue)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $issue->student->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $issue->student->student_id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $issue->book->title }}</div>
                                    <div class="text-xs text-gray-500">Author: {{ $issue->book->author }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $issue->issue_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $issue->due_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = [
                                            'issued' => 'bg-blue-100 text-blue-700',
                                            'returned' => 'bg-green-100 text-green-700',
                                            'lost' => 'bg-red-100 text-red-700',
                                        ][$issue->status] ?? 'bg-gray-100 text-gray-700';
                                        
                                        $statusText = [
                                            'issued' => 'ইস্যু করা',
                                            'returned' => 'ফেরত এসেছে',
                                            'lost' => 'হারিয়ে গেছে',
                                        ][$issue->status] ?? $issue->status;
                                    @endphp
                                    <span class="px-3 py-1 {{ $statusClass }} rounded-full text-xs font-bold">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($issue->status === 'issued')
                                        <a href="{{ route('tenant.library.return') }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-4 py-2 rounded-lg text-xs font-bold transition-colors">
                                            ফেরত নিন
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">সম্পন্ন</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">কোন রেকর্ড পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($issues->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $issues->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Issue Book Modal -->
<div id="issueModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">নতুন বই ইস্যু করুন</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('tenant.library.issue.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী নির্বাচন করুন *</label>
                    <select name="student_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent select2">
                        <option value="">শিক্ষার্থী বাছাই করুন</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বই নির্বাচন করুন *</label>
                    <select name="book_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent select2">
                        <option value="">বই বাছাই করুন</option>
                        @foreach($availableBooks as $book)
                            <option value="{{ $book->id }}">{{ $book->title }} (উপলব্ধ: {{ $book->available_quantity }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ইস্যুর তারিখ *</label>
                        <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ফেরতের শেষ তারিখ *</label>
                        <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">নোট (ঐচ্ছিক)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition-all">বাতিল</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl font-bold shadow-lg transition-all">ইস্যু করুন</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openIssueModal() {
        document.getElementById('issueModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('issueModal').classList.add('hidden');
    }
</script>
@endsection

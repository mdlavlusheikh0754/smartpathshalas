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
                    <h1 class="text-3xl font-bold text-gray-900">বই ফেরত</h1>
                    <p class="text-gray-600 mt-1">ইস্যু করা বইগুলো ফেরত নিন</p>
                </div>
            </div>
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

        <!-- Return Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">শিক্ষার্থীর নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বইয়ের নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">ইস্যুর তারিখ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">ফেরত তারিখ (আশা করা)</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">বিলম্ব (দিন)</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($issuedItems as $issue)
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
                                        $overdueDays = now()->diffInDays($issue->due_date, false);
                                        $isOverdue = $overdueDays < 0;
                                    @endphp
                                    @if($isOverdue)
                                        <span class="text-red-600 font-bold">{{ abs($overdueDays) }} দিন বাকি</span>
                                    @else
                                        <span class="text-green-600 font-medium">সময় আছে</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="openReturnModal({{ $issue }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors">
                                        ফেরত নিন
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">বর্তমানে কোন ইস্যু করা বই নেই</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($issuedItems->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $issuedItems->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Return Book Modal -->
<div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">বই ফেরত প্রসেস</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="returnForm" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <div class="text-sm text-blue-800 font-bold mb-1">বইয়ের নাম: <span id="modalBookTitle" class="font-normal"></span></div>
                    <div class="text-sm text-blue-800 font-bold">শিক্ষার্থী: <span id="modalStudentName" class="font-normal"></span></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ফেরতের তারিখ *</label>
                    <input type="date" name="return_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="returned">ফেরত এসেছে (Returned)</option>
                        <option value="lost">হারিয়ে গেছে (Lost)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">জরিমানা (যদি থাকে)</label>
                    <input type="number" name="fine_amount" value="0" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">নোট (ঐচ্ছিক)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition-all">বাতিল</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl font-bold shadow-lg transition-all">সাবমিট করুন</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(issue) {
        document.getElementById('modalBookTitle').innerText = issue.book.title;
        document.getElementById('modalStudentName').innerText = issue.student.name;
        document.getElementById('returnForm').action = `/library/return/${issue.id}`;
        document.getElementById('returnModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('returnModal').classList.add('hidden');
    }
</script>
@endsection

@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">সাপোর্ট মেসেজ</h1>
            <p class="text-gray-600 mt-2 bengali-text">কন্টাক্ট পেজ থেকে আসা মেসেজগুলো পরিচালনা করুন</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">সফল!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Messages Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 bengali-text">মেসেজের তালিকা</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">নাম</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">ইমেইল</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">বিষয়</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">তারিখ</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">অবস্থা</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($messages as $message)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 bengali-text">{{ $message->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $message->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 bengali-text">{{ $message->subject }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 bengali-text">
                            {{ \App\Helpers\BengaliHelper::toBengaliDateTime($message->created_at) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'read' => 'bg-blue-100 text-blue-800',
                                    'replied' => 'bg-green-100 text-green-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'নতুন',
                                    'read' => 'পঠিত',
                                    'replied' => 'উত্তর দেওয়া হয়েছে',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$message->status] }} bengali-text">
                                {{ $statusLabels[$message->status] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <button type="button" onclick="openMessageModal({{ $message->id }}, '{{ addslashes($message->name) }}', '{{ $message->email }}', '{{ $message->phone }}', '{{ addslashes($message->subject) }}', '{{ str_replace("'", "\\'", $message->message) }}', '{{ \App\Helpers\BengaliHelper::toBengaliDateTime($message->created_at) }}')" class="text-indigo-600 hover:text-indigo-900" title="দেখুন">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('tenant.support.destroy', $message->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি এই মেসেজটি মুছে ফেলতে চান?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="মুছে ফেলুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 bengali-text">কোন মেসেজ পাওয়া যায়নি</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Message Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeMessageModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between border-b border-gray-200">
            <h2 class="text-2xl font-bold text-white bengali-text">মেসেজ বিস্তারিত</h2>
            <button type="button" onclick="closeMessageModal()" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-8 space-y-6">
            <!-- Sender Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 bengali-text">প্রেরকের তথ্য</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">নাম</p>
                            <p id="modalName" class="text-lg font-medium text-gray-900 bengali-text"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">ইমেইল</p>
                            <p id="modalEmail" class="text-gray-700">
                                <a id="modalEmailLink" href="#" class="text-indigo-600 hover:underline"></a>
                            </p>
                        </div>
                        <div id="phoneDiv" class="hidden">
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">ফোন</p>
                            <p id="modalPhone" class="text-gray-700">
                                <a id="modalPhoneLink" href="#" class="text-indigo-600 hover:underline"></a>
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 bengali-text">মেসেজ তথ্য</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">বিষয়</p>
                            <p id="modalSubject" class="text-lg font-medium text-gray-900 bengali-text"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider bengali-text">তারিখ</p>
                            <p id="modalDate" class="text-gray-700 bengali-text"></p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Message Body -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 bengali-text">মেসেজ</h3>
                <div id="modalMessage" class="bg-gray-50 rounded-xl p-6 text-gray-800 leading-relaxed whitespace-pre-wrap bengali-text border border-gray-200"></div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-end gap-4">
            <button type="button" onclick="closeMessageModal()" class="px-6 py-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-medium bengali-text">
                বন্ধ করুন
            </button>
        </div>
    </div>
</div>

<script>
function openMessageModal(id, name, email, phone, subject, message, date) {
    // Populate modal fields
    document.getElementById('modalName').textContent = name;
    
    const emailLink = document.getElementById('modalEmailLink');
    emailLink.href = 'mailto:' + email;
    emailLink.textContent = email;
    
    const phoneDiv = document.getElementById('phoneDiv');
    if (phone && phone.trim()) {
        const phoneLink = document.getElementById('modalPhoneLink');
        phoneLink.href = 'tel:' + phone;
        phoneLink.textContent = phone;
        phoneDiv.classList.remove('hidden');
    } else {
        phoneDiv.classList.add('hidden');
    }
    
    document.getElementById('modalSubject').textContent = subject;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('modalDate').textContent = date;
    
    // Show modal
    document.getElementById('messageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMessageModal();
    }
});
</script>

@endsection

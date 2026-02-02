@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between no-print">
            <a href="{{ route('tenant.teachers.index') }}" class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
            <div class="flex gap-3">
                <a href="{{ route('tenant.teachers.edit', $id) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    সম্পাদনা
                </a>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    প্রিন্ট
                </button>
                <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    মুছে ফেলুন
                </button>
            </div>
        </div>

        <!-- Delete Form (Hidden) -->
        <form id="deleteForm" action="{{ route('tenant.teachers.destroy', $id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <!-- Teacher Profile Card -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-lg p-8 mb-6 text-white">
            <div class="flex items-start gap-6">
                <div class="w-32 h-32 bg-white rounded-xl overflow-hidden flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name=Teacher&size=128&background=10B981&color=fff" alt="Teacher Photo" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">মোহাম্মদ আব্দুল করিম</h1>
                    <p class="text-green-100 mb-4">Mohammad Abdul Karim</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-green-200 text-sm">শিক্ষক ID</p>
                            <p class="font-semibold">T-{{ str_pad($id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-green-200 text-sm">বিষয়</p>
                            <p class="font-semibold">গণিত</p>
                        </div>
                        <div>
                            <p class="text-green-200 text-sm">যোগদানের তারিখ</p>
                            <p class="font-semibold">০১ জানুয়ারি, ২০২০</p>
                        </div>
                        <div>
                            <p class="text-green-200 text-sm">স্ট্যাটাস</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-green-600">
                                সক্রিয়
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">ব্যক্তিগত তথ্য</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">জন্ম তারিখ</p>
                            <p class="font-medium">১৫ জানুয়ারি, ১৯৮৫</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">লিঙ্গ</p>
                            <p class="font-medium">পুরুষ</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">রক্তের গ্রুপ</p>
                            <p class="font-medium">A+</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">ধর্ম</p>
                            <p class="font-medium">ইসলাম</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">জাতীয়তা</p>
                            <p class="font-medium">বাংলাদেশী</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">এনআইডি নম্বর</p>
                            <p class="font-medium">১২৩৪৫৬৭৮৯০১২৩</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">মোবাইল নম্বর</p>
                            <p class="font-medium">০১৭১২৩৪৫৬৭৮</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">ইমেইল</p>
                            <p class="font-medium">teacher@example.com</p>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">একাডেমিক তথ্য</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">বিষয়</p>
                            <p class="font-medium">গণিত</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">যোগ্যতা</p>
                            <p class="font-medium">M.Sc in Mathematics</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">অভিজ্ঞতা</p>
                            <p class="font-medium">১০ বছর</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">পদবী</p>
                            <p class="font-medium">সহকারী শিক্ষক</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">ঠিকানা</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">বর্তমান ঠিকানা</p>
                            <p class="text-gray-600">গ্রাম: মিরপুর, ইউনিয়ন: কাশিপুর, উপজেলা: বরিশাল সদর, জেলা: বরিশাল, বিভাগ: বরিশাল</p>
                        </div>
                        <div class="border-t pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">স্থায়ী ঠিকানা</p>
                            <p class="text-gray-600">গ্রাম: মিরপুর, ইউনিয়ন: কাশিপুর, উপজেলা: বরিশাল সদর, জেলা: বরিশাল, বিভাগ: বরিশাল</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4">দ্রুত তথ্য</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-gray-600">মোট ক্লাস</span>
                            <span class="font-bold text-green-600">২৫</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-600">শিক্ষার্থী সংখ্যা</span>
                            <span class="font-bold text-blue-600">৩৫০</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm text-gray-600">মাসিক বেতন</span>
                            <span class="font-bold text-purple-600">২৫,০০০ টাকা</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4">অতিরিক্ত তথ্য</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">জরুরি যোগাযোগ</p>
                            <p class="font-medium">০১৮১২৩৪৫৬৭৮</p>
                        </div>
                        <div>
                            <p class="text-gray-500">ব্যাংক একাউন্ট</p>
                            <p class="font-medium">১২৩৪৫৬৭৮৯০</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে এই শিক্ষককে মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button onclick="submitDelete()" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}

/* Custom Delete Confirmation Modal */
.delete-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.delete-modal.active {
    display: flex;
}

.delete-modal-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

function submitDelete() {
    document.getElementById('deleteForm').submit();
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection

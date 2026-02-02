@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.hostel.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-colors duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">রুম ম্যানেজমেন্ট</h1>
                    <p class="text-gray-600 mt-1">হোস্টেলের সকল রুম দেখুন এবং পরিচালনা করুন</p>
                </div>
            </div>
            <button id="addNewRoomBtn" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                নতুন রুম যোগ করুন
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট রুম</p>
                        <p id="totalRoomsCount" class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">বরাদ্দকৃত</p>
                        <p id="occupiedRoomsCount" class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1H21l-1 6H4l-1-6h7.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">খালি</p>
                        <p id="availableRoomsCount" class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">মোট শিক্ষার্থী</p>
                        <p id="totalStudentsCount" class="text-3xl font-bold text-gray-900">০</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rooms Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">রুম নম্বর</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">হোস্টেল</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">ধারণক্ষমতা</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বর্তমান শিক্ষার্থী</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="roomsTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">কোন রুম পাওয়া যায়নি</p>
                                    <p class="text-gray-500">নতুন রুম যোগ করতে উপরের বাটনে ক্লিক করুন</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div id="roomModal" class="modal">
    <div class="modal-content max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-blue-600">নতুন রুম যোগ করুন</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="roomForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">রুম নম্বর *</label>
                    <input type="text" id="roomNumber" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="রুম নম্বর লিখুন">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">হোস্টেল নাম *</label>
                    <input type="text" id="hostelName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="হোস্টেল নাম লিখুন">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ধারণক্ষমতা *</label>
                    <input type="number" id="roomCapacity" required min="1" max="10" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="ধারণক্ষমতা">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">রুমের ধরন *</label>
                    <select id="roomType" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">রুমের ধরন নির্বাচন করুন</option>
                        <option value="single">একক</option>
                        <option value="double">দ্বিক</option>
                        <option value="triple">ত্রিক</option>
                        <option value="quad">চতুর্থক</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">মাসিক ভাড়া (টাকা)</label>
                    <input type="number" id="monthlyRent" min="0" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="মাসিক ভাড়া">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">তলা</label>
                    <select id="roomFloor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">তলা নির্বাচন করুন</option>
                        <option value="ground">নিচতলা</option>
                        <option value="1st">১ম তলা</option>
                        <option value="2nd">২য় তলা</option>
                        <option value="3rd">৩য় তলা</option>
                        <option value="4th">৪র্থ তলা</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">সুবিধাসমূহ</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="hasAC" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">এয়ার কন্ডিশন</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="hasFan" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">ফ্যান</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="hasAttachedBath" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">সংযুক্ত বাথরুম</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="hasBalcony" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">বারান্দা</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">বিবরণ</label>
                <textarea id="roomDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="রুম সম্পর্কে বিস্তারিত লিখুন"></textarea>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-bold transition-colors">
                    বাতিল করুন
                </button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold transition-colors">
                    সেভ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">সফল!</h3>
            <p id="successMessage" class="text-gray-600 mb-6">রুম সফলভাবে যোগ করা হয়েছে!</p>
            <button onclick="closeModal()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                ঠিক আছে
            </button>
        </div>
    </div>
</div>

<style>
.modal {
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

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
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
// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Rooms page loaded');
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    const addBtn = document.getElementById('addNewRoomBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            openAddModal();
        });
    }
    
    const roomForm = document.getElementById('roomForm');
    if (roomForm) {
        roomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveRoom();
        });
    }
}

// Open add modal
function openAddModal() {
    const modal = document.getElementById('roomModal');
    const form = document.getElementById('roomForm');
    
    if (form) form.reset();
    
    if (modal) {
        modal.classList.add('active');
    }
}

// Close modal
function closeModal() {
    const modal = document.getElementById('roomModal');
    const successModal = document.getElementById('successModal');
    
    if (modal) modal.classList.remove('active');
    if (successModal) successModal.classList.remove('active');
}

// Save room
async function saveRoom() {
    const roomNumber = document.getElementById('roomNumber').value;
    if (!roomNumber) {
        showErrorMessage('রুম নম্বর প্রয়োজন');
        return;
    }
    
    const hostelName = document.getElementById('hostelName').value;
    if (!hostelName) {
        showErrorMessage('হোস্টেল নাম প্রয়োজন');
        return;
    }
    
    const capacity = document.getElementById('roomCapacity').value;
    if (!capacity) {
        showErrorMessage('ধারণক্ষমতা প্রয়োজন');
        return;
    }
    
    const roomType = document.getElementById('roomType').value;
    if (!roomType) {
        showErrorMessage('রুমের ধরন নির্বাচন করুন');
        return;
    }
    
    const roomData = {
        room_number: roomNumber,
        hostel_name: hostelName,
        capacity: parseInt(capacity),
        room_type: roomType,
        monthly_rent: parseFloat(document.getElementById('monthlyRent').value || '0'),
        floor: document.getElementById('roomFloor').value || '',
        has_ac: document.getElementById('hasAC').checked,
        has_fan: document.getElementById('hasFan').checked,
        has_attached_bath: document.getElementById('hasAttachedBath').checked,
        has_balcony: document.getElementById('hasBalcony').checked,
        description: document.getElementById('roomDescription').value || ''
    };
    
    console.log('Room data:', roomData);
    
    // For now, just show success message since backend is not implemented
    showSuccessMessage('রুম সফলভাবে যোগ করা হয়েছে!');
    closeModal();
}

// Show success message
function showSuccessMessage(message) {
    const successMessageElement = document.getElementById('successMessage');
    const successModalElement = document.getElementById('successModal');
    
    if (successMessageElement && successModalElement) {
        successMessageElement.textContent = message;
        successModalElement.classList.add('active');
        
        setTimeout(() => {
            successModalElement.classList.remove('active');
        }, 3000);
    } else {
        alert(message);
    }
}

// Show error message
function showErrorMessage(message) {
    alert(message);
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        closeModal();
    }
});

// Keyboard support
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
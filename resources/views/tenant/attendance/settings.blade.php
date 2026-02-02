@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">QR Code & RFID সেটিংস</h1>
            <p class="text-gray-600 mt-1">শিক্ষার্থীদের জন্য QR Code এবং RFID কার্ড সেট করুন</p>
        </div>
        <a href="{{ route('tenant.attendance.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            ফিরে যান
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস</label>
                <select id="filterClass" onchange="loadStudents()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">সকল ক্লাস</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}">{{ $class }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শাখা</label>
                <select id="filterSection" onchange="loadStudents()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">সকল শাখা</option>
                    @foreach($sections as $section)
                        <option value="{{ $section }}">{{ $section }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                <select id="filterStatus" onchange="loadStudents()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="configured">সেটআপ সম্পন্ন</option>
                    <option value="qr_only">শুধুমাত্র QR</option>
                    <option value="rfid_only">শুধুমাত্র RFID</option>
                    <option value="not_configured">সেটআপ করা হয়নি</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadStudents()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    খুঁজুন
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">একসাথে QR Code তৈরি করুন</h3>
        <div class="flex gap-3">
            <button onclick="generateAllQR()" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                সকল শিক্ষার্থীর QR Code তৈরি করুন
            </button>
            <button onclick="downloadAllQR()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                সকল QR Code ডাউনলোড করুন
            </button>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">শিক্ষার্থী তালিকা</h3>
        <div id="studentsList" class="space-y-4">
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p>শিক্ষার্থী দেখতে ফিল্টার ব্যবহার করুন</p>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">QR Code</h3>
            <button onclick="closeQRModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="text-center">
            <div id="qrCodeContainer" class="bg-gray-100 p-6 rounded-xl mb-4 flex items-center justify-center">
                <!-- QR Code will be inserted here -->
            </div>
            <p id="studentNameQR" class="text-lg font-bold text-gray-900 mb-2"></p>
            <p id="studentIdQR" class="text-sm text-gray-600 mb-4"></p>
            <button onclick="downloadQRCode()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                ডাউনলোড করুন
            </button>
        </div>
    </div>
</div>

<!-- RFID Modal -->
<div id="rfidModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">RFID সেটআপ</h3>
            <button onclick="closeRFIDModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                <img id="rfidStudentPhoto" src="" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <h4 id="rfidStudentName" class="font-bold text-gray-900"></h4>
                    <p id="rfidStudentId" class="text-sm text-gray-600"></p>
                </div>
            </div>

            <label class="block text-sm font-medium text-gray-700 mb-2">RFID কার্ড নম্বর</label>
            <div class="relative">
                <input type="text" id="rfidInput" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="কার্ড স্ক্যান করুন বা নম্বর লিখুন" autofocus>
                <div class="absolute right-3 top-3 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">RFID রিডার ব্যবহার করে কার্ড স্ক্যান করলে স্বয়ংক্রিয়ভাবে নম্বর ইনপুট হবে।</p>
        </div>

        <div class="flex justify-end gap-3">
            <button onclick="closeRFIDModal()" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                বাতিল
            </button>
            <button onclick="saveRFID()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                সংরক্ষণ করুন
            </button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center">
        <div class="mb-4 text-yellow-500">
             <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
        <p class="text-gray-600 mb-6">সকল শিক্ষার্থীর জন্য QR Code তৈরি করবেন?<br>এতে কিছু সময় লাগতে পারে।</p>
        
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                না, বাতিল
            </button>
            <button id="confirmActionBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                হ্যাঁ, শুরু করুন
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center">
        <div class="mb-4 text-green-500">
             <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">সফল!</h3>
        <p id="successMessage" class="text-gray-600 mb-6"></p>
        
        <button onclick="closeSuccessModal()" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
            ঠিক আছে
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.1/qrcode.min.js" integrity="sha512-PEhlWBZBrQL7flpJPY8lXx8tIN7HWX912GzGhFTDqA3iWFrakVH3lVHomCoU9BhfKzgxfEk6EG2C3xej+9srOQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Fallback if CDN fails
    if (typeof QRCode === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
        document.head.appendChild(script);
    }
</script>
<script>
let currentStudent = null;
let allStudents = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // No mock data, just wait for user action or load defaults if needed
    // loadStudents(); // Uncomment if you want to load data on page load
});

// Load students
async function loadStudents() {
    const classFilter = document.getElementById('filterClass').value;
    const sectionFilter = document.getElementById('filterSection').value;
    const statusFilter = document.getElementById('filterStatus').value;

    const container = document.getElementById('studentsList');
    container.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-500">লোডিং...</p></div>';

    try {
        const response = await fetch('/api/tenant/students/qr-rfid-list', {
            method: 'POST', // Changed to POST as per controller but GET is standard for fetching list. Controller handles request body so POST is likely intended or we should switch to GET with query params. Controller code shows request body usage.
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                class: classFilter,
                section: sectionFilter,
                status: statusFilter
            })
        });

        const data = await response.json();
        
        if (data.success) {
            allStudents = data.students;
            displayStudents(data.students);
        } else {
             container.innerHTML = `<div class="text-center text-red-500 py-8">${data.message}</div>`;
        }
    } catch (error) {
        console.error('Error loading students:', error);
        container.innerHTML = '<div class="text-center text-red-500 py-8">তথ্য লোড করতে ত্রুটি হয়েছে</div>';
    }
}


// Display students
function displayStudents(students) {
    const container = document.getElementById('studentsList');
    
    if (students.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <p>কোন শিক্ষার্থী পাওয়া যায়নি</p>
            </div>
        `;
        return;
    }

    container.innerHTML = students.map(student => `
        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="${student.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=4F46E5&color=fff&size=64'}" 
                         alt="${student.name}" 
                         class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h4 class="font-bold text-gray-900">${student.name}</h4>
                        <p class="text-sm text-gray-600">ID: ${student.student_id}</p>
                        <p class="text-sm text-gray-600">ক্লাস: ${student.class} ${student.section ? '- ' + student.section : ''}</p>
                        <p class="text-sm text-gray-600">রোল: ${student.roll || 'N/A'}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    ${student.qr_code ? `
                        <button onclick="viewQRCode(${student.id})" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            QR দেখুন
                        </button>
                        <a href="/students/${student.id}/id-card" target="_blank" class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.896 1.75-2 2.167a11.97 11.97 0 00-4 0c-1.104-.417-2-1.283-2-2.167"></path>
                            </svg>
                            ID কার্ড
                        </a>
                        <button onclick="downloadStudentQR(${student.id}, '${student.name.replace(/'/g, "\\'")}', '${student.student_id}')" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            ডাউনলোড
                        </button>
                        <button onclick="generateQRForStudent(${student.id})" class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            QR জেনারেট
                        </button>
                    ` : `
                        <button onclick="generateQRForStudent(${student.id})" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                            QR তৈরি করুন
                        </button>
                        <a href="/students/${student.id}/id-card" target="_blank" class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.896 1.75-2 2.167a11.97 11.97 0 00-4 0c-1.104-.417-2-1.283-2-2.167"></path>
                            </svg>
                            ID কার্ড
                        </a>
                    `}
                    <button onclick="openRFIDModal(${student.id})" class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-200 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        ${student.rfid_card ? 'RFID আপডেট' : 'RFID সেট'}
                    </button>
                    ${student.rfid_card ? `<p class="col-span-2 text-xs text-gray-500 text-center">RFID: ${student.rfid_card}</p>` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

// Generate QR Code for single student
async function generateQRForStudent(studentId) {
    try {
        const response = await fetch('/api/tenant/students/generate-qr/' + studentId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showSuccessModal('QR Code সফলভাবে তৈরি হয়েছে');
            loadStudents(); // Reload the list
        } else {
            alert('ত্রুটি: ' + data.message);
        }
    } catch (error) {
        console.error('Error generating QR:', error);
        alert('QR Code তৈরি করতে ত্রুটি হয়েছে: ' + (error.message || error));
    }
}

// View QR Code
async function viewQRCode(studentId) {
    if (typeof QRCode === 'undefined') {
        alert('QR Code লাইব্রেরি লোড হয়নি। অনুগ্রহ করে ইন্টারনেট সংযোগ চেক করুন এবং পেজ রিফ্রেশ করুন।');
        return;
    }
    const student = allStudents.find(s => s.id === studentId);
    if (!student || !student.qr_code) return;

    currentStudent = student;
    
    // Generate QR Code
    const qrContainer = document.getElementById('qrCodeContainer');
    if (qrContainer) {
        qrContainer.innerHTML = '';
        
        await QRCode.toCanvas(student.qr_code, { width: 256, margin: 2 }, (error, canvas) => {
            if (error) {
                console.error(error);
                return;
            }
            qrContainer.appendChild(canvas);
        });
    }

    const nameEl = document.getElementById('studentNameQR');
    if (nameEl) nameEl.textContent = student.name;
    
    const idEl = document.getElementById('studentIdQR');
    if (idEl) idEl.textContent = 'ID: ' + student.student_id;
    
    const modalEl = document.getElementById('qrModal');
    if (modalEl) modalEl.classList.remove('hidden');
}

// Download QR Code for single student
async function downloadStudentQR(studentId, name, studentIdStr) {
    if (typeof QRCode === 'undefined') {
        alert('QR Code লাইব্রেরি লোড হয়নি। অনুগ্রহ করে ইন্টারনেট সংযোগ চেক করুন এবং পেজ রিফ্রেশ করুন।');
        return;
    }

    const student = allStudents.find(s => s.id === studentId);
    if (!student || !student.qr_code) {
        alert('এই শিক্ষার্থীর QR Code পাওয়া যায়নি');
        return;
    }

    try {
        // Create a temporary canvas
        const canvas = document.createElement('canvas');
        
        // Generate high quality QR code
        QRCode.toCanvas(canvas, student.qr_code, { 
            width: 512,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            },
            errorCorrectionLevel: 'H'
        }, function(error) {
            if (error) {
                console.error('QR generation error:', error);
                alert('QR Code তৈরিতে সমস্যা হয়েছে');
                return;
            }

            // Convert to Blob and download
            canvas.toBlob(function(blob) {
                if (!blob) {
                    alert('ডাউনলোড করতে সমস্যা হয়েছে');
                    return;
                }
                
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `QR_${studentIdStr}_${name}.png`;
                link.style.display = 'none';
                
                document.body.appendChild(link);
                link.click();
                
                // Cleanup
                setTimeout(() => {
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                }, 100);
            }, 'image/png', 1.0);
        });

    } catch (error) {
        console.error('Error downloading QR:', error);
        alert('QR Code ডাউনলোড করতে সমস্যা হয়েছে: ' + error.message);
    }
}

// Close QR Modal
function closeQRModal() {
    document.getElementById('qrModal').classList.add('hidden');
    currentStudent = null;
}

// Download QR Code
function downloadQRCode() {
    if (!currentStudent) return;

    const canvas = document.querySelector('#qrCodeContainer canvas');
    if (!canvas) {
        alert('QR Code পাওয়া যায়নি');
        return;
    }

    try {
        // Use toBlob for better browser compatibility
        canvas.toBlob(function(blob) {
            if (!blob) {
                alert('QR Code ডাউনলোড করতে সমস্যা হয়েছে');
                return;
            }
            
            // Create download link
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.download = `QR_${currentStudent.student_id}_${currentStudent.name}.png`;
            link.href = url;
            link.style.display = 'none';
            
            document.body.appendChild(link);
            link.click();
            
            // Cleanup
            setTimeout(() => {
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }, 100);
        }, 'image/png', 1.0);
    } catch (error) {
        console.error('Download error:', error);
        // Fallback to dataURL method
        try {
            const link = document.createElement('a');
            link.download = `QR_${currentStudent.student_id}_${currentStudent.name}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (e) {
            alert('QR Code ডাউনলোড করতে ত্রুটি হয়েছে');
        }
    }
}

let currentRfidStudentId = null;

// Open RFID Modal
async function openRFIDModal(studentId) {
    const student = allStudents.find(s => s.id === studentId);
    if (!student) return;

    currentRfidStudentId = studentId;
    
    // Populate modal data
    const nameEl = document.getElementById('rfidStudentName');
    if (nameEl) nameEl.textContent = student.name;
    
    const idEl = document.getElementById('rfidStudentId');
    if (idEl) idEl.textContent = 'ID: ' + student.student_id;
    
    const photoEl = document.getElementById('rfidStudentPhoto');
    if (photoEl) photoEl.src = student.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=4F46E5&color=fff&size=64';
    
    const input = document.getElementById('rfidInput');
    if (input) input.value = student.rfid_card || '';
    
    // Show modal
    const modalEl = document.getElementById('rfidModal');
    if (modalEl) modalEl.classList.remove('hidden');
    
    // Focus input after a slight delay to ensure modal is visible
    if (input) {
        setTimeout(() => {
            input.focus();
        }, 100);
    }
}

// Close RFID Modal
function closeRFIDModal() {
    document.getElementById('rfidModal').classList.add('hidden');
    currentRfidStudentId = null;
    document.getElementById('rfidInput').value = '';
}

// Save RFID
async function saveRFID() {
    if (!currentRfidStudentId) return;
    
    const rfid = document.getElementById('rfidInput').value.trim();
    if (!rfid) {
        alert('অনুগ্রহ করে RFID কার্ড নম্বর দিন');
        return;
    }

    try {
        const response = await fetch('/api/tenant/students/set-rfid/' + currentRfidStudentId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ rfid_card: rfid })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('RFID সফলভাবে সেট করা হয়েছে');
            closeRFIDModal();
            loadStudents(); // Reload the list
        } else {
            alert('ত্রুটি: ' + data.message);
        }
    } catch (error) {
        console.error('Error setting RFID:', error);
        alert('RFID সেট করতে ত্রুটি হয়েছে');
    }
}

// Add Enter key support for RFID input
document.getElementById('rfidInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        saveRFID();
    }
});


// Generate QR for all students
function generateAllQR() {
    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmActionBtn').onclick = processGenerateAllQR;
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function showSuccessModal(message) {
    const messageEl = document.getElementById('successMessage');
    const modalEl = document.getElementById('successModal');
    
    if (messageEl) messageEl.textContent = message;
    if (modalEl) modalEl.classList.remove('hidden');
    
    if (!messageEl || !modalEl) {
        // Fallback if modal elements are missing
        alert(message);
    }
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

async function processGenerateAllQR() {
    closeConfirmModal();
    
    // Show loading state if needed, or just let the user wait (maybe add a toast later)
    
    try {
        const response = await fetch('/api/tenant/students/generate-all-qr', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        // Check if response is JSON
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            const data = await response.json();
            if (data.success) {
                showSuccessModal(`${data.count} টি QR Code সফলভাবে তৈরি/আপডেট করা হয়েছে`);
                loadStudents(); 
            } else {
                alert('ত্রুটি: ' + data.message);
            }
        } else {
            // Not JSON (likely HTML error page)
            const text = await response.text();
            console.error('Server Error (Non-JSON):', text);
            // Create a temporary element to strip HTML tags for alert
            const div = document.createElement('div');
            div.innerHTML = text;
            const cleanText = div.textContent || div.innerText || "";
            alert('সার্ভার ত্রুটি (বিস্তারিত কনসোলে দেখুন): ' + cleanText.substring(0, 100) + '...');
        }
    } catch (error) {
        console.error('Error generating all QR:', error);
        alert('QR Code তৈরি করতে ত্রুটি হয়েছে: ' + error.message);
    }
}

// Download all QR codes
async function downloadAllQR() {
    alert('এই ফিচারটি শীঘ্রই আসছে। আপাতত একটি একটি করে ডাউনলোড করুন।');
}

// Load students on page load
document.addEventListener('DOMContentLoaded', () => {
    loadStudents();
});
</script>
@endsection

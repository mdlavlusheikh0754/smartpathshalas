@extends('layouts.tenant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Toast Notification -->
<div id="toast" class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 hidden transition-all duration-300">
    <div class="bg-gray-800 text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="toastMessage" class="font-medium"></span>
    </div>
</div>

<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">উপস্থিতি নিন</h1>
            <p class="text-gray-600 mt-1">পদ্ধতি নির্বাচন করুন এবং উপস্থিতি রেকর্ড করুন</p>
        </div>
        <a href="{{ route('tenant.attendance.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            ফিরে যান
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b">
            <button onclick="switchMethod('rfid')" id="rfidTab" class="flex-1 py-4 text-center font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition-colors active-tab">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    RFID Scan
                </div>
            </button>
            <button onclick="switchMethod('qr')" id="qrTab" class="flex-1 py-4 text-center font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition-colors">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4c1 0 2 1 2 2v2h4m-4-9v2m-6-4 7-2"></path> <!-- Simplified QR Icon -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z"></path>
                    </svg>
                    QR Code
                </div>
            </button>
            <button onclick="switchMethod('manual')" id="manualTab" class="flex-1 py-4 text-center font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition-colors">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h6"></path>
                    </svg>
                    Manual
                </div>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- RFID Section -->
            <div id="rfidMethod" class="method-content">
                <div class="max-w-xl mx-auto text-center py-12">
                    <div class="mb-8 relative">
                        <div class="w-48 h-48 bg-blue-50 rounded-full flex items-center justify-center mx-auto animate-pulse">
                            <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">RFID কার্ড স্ক্যান করুন</h3>
                    <p class="text-gray-500 mb-8">কার্ড রিডারে কার্ড ধরুন, সিস্টেম স্বয়ংক্রিয়ভাবে উপস্থিতি রেকর্ড করবে</p>
                    <input type="text" id="rfidInput" class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl text-center text-xl focus:border-blue-500 focus:ring-0" placeholder="কার্ড স্ক্যান করার জন্য এখানে ক্লিক করুন" autofocus>
                    
                    <div id="rfidResult" class="mt-8 hidden">
                        <!-- Result will be shown here -->
                    </div>
                </div>
            </div>

            <!-- QR Section -->
            <div id="qrMethod" class="method-content hidden">
                <div class="max-w-xl mx-auto text-center py-12">
                    <div id="qrReader" class="mb-8 bg-gray-100 rounded-lg overflow-hidden" style="width: 100%; min-height: 300px;"></div>
                    <button id="startQrBtn" onclick="startQRScan()" class="bg-purple-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-purple-700 transition-colors">
                        ক্যামেরা চালু করুন
                    </button>
                    <button id="stopQrBtn" onclick="stopQRScan()" class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition-colors hidden">
                        বন্ধ করুন
                    </button>
                    <div id="qrResult" class="mt-8 hidden"></div>
                </div>
            </div>

            <!-- Manual Section -->
            <div id="manualMethod" class="method-content hidden">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাস নির্বাচন করুন</label>
                            <select id="manualClass" onchange="loadManualStudents()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">নির্বাচন করুন...</option>
                                @foreach(\App\Models\SchoolClass::active()->ordered()->get() as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->section }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ</label>
                            <input type="date" id="attendanceDate" value="{{ date('Y-m-d') }}" onchange="loadManualStudents()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button onclick="saveManualAttendance()" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition-colors">
                                সংরক্ষণ করুন
                            </button>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">রোল</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">নাম</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-center">উপস্থিতি</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">মন্তব্য</th>
                                </tr>
                            </thead>
                            <tbody id="manualStudentList" class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        অনুগ্রহ করে একটি ক্লাস নির্বাচন করুন
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.active-tab {
    color: #2563eb;
    border-bottom-color: #2563eb;
}
</style>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrcodeScanner = null;

function switchMethod(method) {
    // Stop QR scanner if switching away from QR
    if (html5QrcodeScanner && method !== 'qr') {
        stopQRScan();
    }
    
    // Hide all contents
    document.querySelectorAll('.method-content').forEach(el => el.classList.add('hidden'));
    // Show selected content
    document.getElementById(method + 'Method').classList.remove('hidden');
    
    // Update tabs
    document.querySelectorAll('button[id$="Tab"]').forEach(el => el.classList.remove('active-tab'));
    document.getElementById(method + 'Tab').classList.add('active-tab');

    // Focus input if RFID
    if (method === 'rfid') {
        setTimeout(() => document.getElementById('rfidInput').focus(), 100);
    }
    
    // Auto-start QR scanner when QR tab is clicked
    if (method === 'qr') {
        setTimeout(() => startQRScan(), 300);
    }
}

// Shared Scan Logic
async function markAttendanceByScan(code, resultContainerId) {
    if (!code) return;
    
    const resultDiv = document.getElementById(resultContainerId);
    
    try {
        const response = await fetch("{{ route('tenant.attendance.mark-scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                code: code
            })
        });

        const result = await response.json();
        showScanResult(result, resultContainerId);
        
    } catch (error) {
        console.error('Error:', error);
        showScanResult({
            success: false,
            message: 'সার্ভার এরর: ' + error.message
        }, resultContainerId);
    }
}

function showScanResult(data, containerId) {
    const resultDiv = document.getElementById(containerId);
    resultDiv.classList.remove('hidden');
    
    if (data.success) {
        resultDiv.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-4">
                <div class="bg-green-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-green-900">${data.student.name}</h4>
                    <p class="text-green-700 text-sm">Class: ${data.student.class} | Roll: ${data.student.roll}</p>
                    <p class="text-green-600 text-xs mt-1">${data.message}</p>
                </div>
            </div>
        `;
        
        // Play success sound (optional)
        // new Audio('/sounds/success.mp3').play().catch(e => {});
        
    } else {
        // Check if it's "Already Attended" warning (yellow) or Error (red)
        // Backend returns success: false for already attended too, but we can check message content or add a flag
        // For now, let's treat "Already Attended" as warning if possible, but the backend sends success: false.
        // Let's check if message contains "Already Attended"
        
        const isWarning = data.message && (data.message.includes('Already Attended') || data.message.includes('ইতিমধ্যে'));
        
        if (isWarning && data.student) {
             resultDiv.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-center gap-4">
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-yellow-900">${data.student.name}</h4>
                         <p class="text-yellow-700 text-sm">Class: ${data.student.class} | Roll: ${data.student.roll}</p>
                        <p class="text-yellow-600 text-sm">${data.message}</p>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-4">
                    <div class="bg-red-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-900">Error</h4>
                        <p class="text-red-600 text-sm">${data.message}</p>
                    </div>
                </div>
            `;
        }
    }
    
    // Hide after 3 seconds
    setTimeout(() => {
        resultDiv.classList.add('hidden');
    }, 3000);
}

// RFID Logic
document.getElementById('rfidInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && this.value.trim()) {
        const code = this.value.trim();
        this.value = ''; // Clear input immediately
        
        markAttendanceByScan(code, 'rfidResult');
    }
});

// QR Logic
let lastQrCode = null;
let lastQrTime = 0;

async function startQRScan() {
    // If already running, don't start again
    if (html5QrcodeScanner && html5QrcodeScanner.getState() === 2) {
        return;
    }
    
    document.getElementById('startQrBtn').classList.add('hidden');
    document.getElementById('stopQrBtn').classList.remove('hidden');
    
    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("qrReader");
    }
    
    try {
        // Get available cameras
        const devices = await Html5Qrcode.getCameras();
        console.log('Available cameras:', devices);
        
        if (devices && devices.length > 0) {
            // Use the first available camera (usually back camera on mobile)
            const cameraId = devices[devices.length > 1 ? 1 : 0].id;
            
            await html5QrcodeScanner.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    // Prevent duplicate scans within 2 seconds
                    const now = Date.now();
                    if (decodedText === lastQrCode && (now - lastQrTime) < 2000) {
                        return;
                    }
                    
                    lastQrCode = decodedText;
                    lastQrTime = now;
                    
                    console.log(`Code matched = ${decodedText}`, decodedResult);
                    markAttendanceByScan(decodedText, 'qrResult');
                },
                (errorMessage) => {
                    // parse error, ignore it.
                }
            );
            console.log('Camera started successfully');
        } else {
            throw new Error('No cameras found');
        }
    } catch (err) {
        console.error('Camera error:', err);
        document.getElementById('startQrBtn').classList.remove('hidden');
        document.getElementById('stopQrBtn').classList.add('hidden');
        
        let errorMsg = 'ক্যামেরা চালু করতে সমস্যা হয়েছে।\n\n';
        
        if (err.name === 'NotAllowedError') {
            errorMsg += 'ক্যামেরা অনুমতি দিতে হবে। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।';
        } else if (err.name === 'NotFoundError') {
            errorMsg += 'কোন ক্যামেরা পাওয়া যায়নি।';
        } else if (err.name === 'NotReadableError') {
            errorMsg += 'ক্যামেরা অন্য অ্যাপ ব্যবহার করছে।';
        } else if (err.name === 'NotSecureError' || err.message.includes('secure')) {
            errorMsg += 'HTTPS সংযোগ প্রয়োজন। HTTP তে কাজ করবে না।';
        } else {
            errorMsg += 'Error: ' + err.message;
        }
        
        alert(errorMsg);
    }
}

function stopQRScan() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then((ignore) => {
            document.getElementById('startQrBtn').classList.remove('hidden');
            document.getElementById('stopQrBtn').classList.add('hidden');
        }).catch((err) => {
            console.error(err);
            document.getElementById('startQrBtn').classList.remove('hidden');
            document.getElementById('stopQrBtn').classList.add('hidden');
        });
    }
}

// Manual Logic
async function loadManualStudents() {
    const classId = document.getElementById('manualClass').value;
    const date = document.getElementById('attendanceDate').value;
    const tbody = document.getElementById('manualStudentList');
    
    if (!classId) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">অনুগ্রহ করে একটি ক্লাস নির্বাচন করুন</td></tr>';
        return;
    }

    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">লোড হচ্ছে...</td></tr>';

    try {
        const url = `{{ route('tenant.attendance.students', ':classId') }}`.replace(':classId', classId) + `?date=${date}`;
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success && data.students.length > 0) {
            tbody.innerHTML = data.students.map(student => {
                const status = student.attendance_status; 
                const hasStatus = !!status;
                const disabled = hasStatus ? 'disabled' : '';
                
                // Visual state logic
                const isP = !hasStatus || status === 'present';
                const isA = status === 'absent';
                const isL = status === 'leave';

                const pClass = isP 
                    ? 'bg-green-600 text-white hover:bg-green-700' 
                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100';
                
                const aClass = isA
                    ? 'bg-red-600 text-white hover:bg-red-700'
                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100';
                    
                const lClass = isL
                    ? 'bg-yellow-500 text-white hover:bg-yellow-600'
                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100';

                const statusMsg = hasStatus 
                    ? `<div class="text-xs mt-1 font-semibold ${status==='present'?'text-green-600':(status==='absent'?'text-red-600':'text-yellow-600')}">ইতিমধ্যে সংরক্ষিত (${status.toUpperCase()})</div>` 
                    : '';

                return `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${student.roll_number}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center gap-3">
                        <img src="${student.photo_url}" class="w-8 h-8 rounded-full object-cover">
                        ${student.name}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <input type="hidden" name="attendance[${student.id}]" value="${status || 'present'}" class="attendance-value">
                            <button type="button" ${disabled} onclick="setManualStatus(this, 'present')" class="px-4 py-2 text-sm font-medium rounded-l-lg border ${pClass}">P</button>
                            <button type="button" ${disabled} onclick="setManualStatus(this, 'absent')" class="px-4 py-2 text-sm font-medium border-t border-b ${aClass}">A</button>
                            <button type="button" ${disabled} onclick="setManualStatus(this, 'leave')" class="px-4 py-2 text-sm font-medium rounded-r-lg border ${lClass}">L</button>
                        </div>
                        ${statusMsg}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input type="text" ${disabled} class="w-full border-gray-300 rounded-md text-sm" placeholder="মন্তব্য (ঐচ্ছিক)">
                    </td>
                </tr>
            `}).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">কোন ছাত্র পাওয়া যায়নি</td></tr>';
        }
    } catch (error) {
        console.error(error);
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-red-500">তথ্য লোড করতে সমস্যা হয়েছে</td></tr>';
    }
}

function setManualStatus(btn, status) {
    // Reset all buttons in the group
    const group = btn.parentElement;
    const buttons = group.querySelectorAll('button');
    buttons.forEach(b => {
        b.className = 'px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:bg-gray-100 first:rounded-l-lg last:rounded-r-lg first:border-r-0 last:border-l-0';
    });

    // Set active style
    if (status === 'present') {
        btn.className = 'px-4 py-2 text-sm font-medium bg-green-600 text-white border border-green-600 hover:bg-green-700 first:rounded-l-lg last:rounded-r-lg';
    } else if (status === 'absent') {
        btn.className = 'px-4 py-2 text-sm font-medium bg-red-600 text-white border border-red-600 hover:bg-red-700 first:rounded-l-lg last:rounded-r-lg';
    } else if (status === 'leave') {
        btn.className = 'px-4 py-2 text-sm font-medium bg-yellow-500 text-white border border-yellow-500 hover:bg-yellow-600 first:rounded-l-lg last:rounded-r-lg';
    }

    // Update hidden input
    const row = group.closest('tr');
    row.querySelector('.attendance-value').value = status;
}

async function saveManualAttendance() {
    // Collect data and send to server
    const classId = document.getElementById('manualClass').value;
    if (!classId) return alert('ক্লাস নির্বাচন করুন');

    const date = document.getElementById('attendanceDate').value;
    const attendanceData = [];
    
    document.querySelectorAll('#manualStudentList tr').forEach(row => {
        const studentIdInput = row.querySelector('.attendance-value');
        if (studentIdInput) {
            // Extract student ID from input name "attendance[ID]"
            const match = studentIdInput.name.match(/\[(\d+)\]/);
            if (match) {
                const studentId = match[1];
                const status = studentIdInput.value;
                attendanceData.push({ student_id: studentId, status: status });
            }
        }
    });

    if (attendanceData.length === 0) {
        return alert('কোন ছাত্র/ছাত্রী পাওয়া যায়নি');
    }

    try {
        const response = await fetch("{{ route('tenant.attendance.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                class_id: classId,
                attendance_date: date,
                attendances: attendanceData
            })
        });

        const result = await response.json();

        if (result.success) {
            showToast(result.message);
        } else {
            alert(result.message || 'সংরক্ষণ করতে সমস্যা হয়েছে');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('সার্ভার এরর: ' + error.message);
    }
}

function setManualStatus(btn, status) {
    // Reset all buttons in the group
    const group = btn.parentElement;
    const buttons = group.querySelectorAll('button');
    buttons.forEach(b => {
        b.className = 'px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:bg-gray-100 first:rounded-l-lg last:rounded-r-lg first:border-r-0 last:border-l-0';
    });

    // Set active style
    if (status === 'present') {
        btn.className = 'px-4 py-2 text-sm font-medium bg-green-600 text-white border border-green-600 hover:bg-green-700 first:rounded-l-lg last:rounded-r-lg';
    } else if (status === 'absent') {
        btn.className = 'px-4 py-2 text-sm font-medium bg-red-600 text-white border border-red-600 hover:bg-red-700 first:rounded-l-lg last:rounded-r-lg';
    } else if (status === 'leave') {
        btn.className = 'px-4 py-2 text-sm font-medium bg-yellow-500 text-white border border-yellow-500 hover:bg-yellow-600 first:rounded-l-lg last:rounded-r-lg';
    }

    // Update hidden input
    const row = group.closest('tr');
    row.querySelector('.attendance-value').value = status;
    
    // Auto Save
    saveSingleAttendance(row);
}

async function saveSingleAttendance(row) {
    const classId = document.getElementById('manualClass').value;
    const date = document.getElementById('attendanceDate').value;
    const studentIdInput = row.querySelector('.attendance-value');
    
    if (!classId) {
        showToast('অনুগ্রহ করে ক্লাস নির্বাচন করুন', 'error');
        return;
    }
    if (!date) {
        showToast('অনুগ্রহ করে তারিখ নির্বাচন করুন', 'error');
        return;
    }
    if (!studentIdInput) return;

    // Extract student ID from input name "attendance[ID]"
    const match = studentIdInput.name.match(/\[(\d+)\]/);
    if (!match) return;
    
    const studentId = match[1];
    const status = studentIdInput.value;
    
    try {
        const response = await fetch("{{ route('tenant.attendance.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                class_id: classId,
                attendance_date: date,
                attendances: [{
                    student_id: studentId,
                    status: status
                }]
            })
        });

        const result = await response.json();

        if (result.success) {
            showToast('উপস্থিতি সংরক্ষিত হয়েছে');
        } else {
            showToast(result.message || 'সংরক্ষণ ব্যর্থ হয়েছে', 'error');
            console.error('Server Error:', result);
        }
    } catch (error) {
        console.error('Auto save error:', error);
        showToast('সার্ভার এরর: ' + error.message, 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = toast.querySelector('svg'); // Assuming there's an icon, or we can change background
    
    toastMessage.textContent = message;
    
    // Change style based on type
    if (type === 'error') {
        toast.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-red-50 rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 transition-all duration-300 z-50 border border-red-200';
    } else {
        toast.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 transition-all duration-300 z-50 border border-gray-100';
    }

    toast.classList.remove('hidden');
    toast.classList.add('opacity-100', 'translate-y-0');
    toast.classList.remove('opacity-0', '-translate-y-full');
    
    setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-full');
        toast.classList.remove('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);
    }, 3000);
}
</script>
@endsection

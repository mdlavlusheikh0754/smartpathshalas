@extends('layouts.tenant')

@section('content')
<div class="p-8 no-print">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ID কার্ড ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">শিক্ষার্থীদের আইডি কার্ড দেখুন এবং প্রিন্ট করুন (Front & Back)</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tenant.attendance.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                ফিরে যান
            </a>
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                প্রিন্ট করুন
            </button>
        </div>
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
                    <option value="active" selected>সক্রিয়</option>
                    <option value="inactive">নিষ্ক্রিয়</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadStudents()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    খুঁজুন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Students List (ID Card View) -->
<div id="studentsList" class="p-8 pt-0 print:p-0">
    <div class="col-span-full text-center py-8 text-gray-500">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.896 1.75-2 2.167a11.97 11.97 0 00-4 0c-1.104-.417-2-1.283-2-2.167"></path>
        </svg>
        <p>শিক্ষার্থী দেখতে ফিল্টার ব্যবহার করুন</p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadStudents();
});

const schoolLogo = "{{ $schoolSettings->logo ? route('tenant.files', ['path' => $schoolSettings->logo]) : 'https://ui-avatars.com/api/?name=' . urlencode($schoolSettings->school_name_en ?? 'School') . '&background=fff&color=1d4ed8&size=128' }}";
const schoolPhone = "{{ $schoolSettings->mobile ?? $schoolSettings->phone ?? '' }}";

// Load students
async function loadStudents() {
    const classFilter = document.getElementById('filterClass').value;
    const sectionFilter = document.getElementById('filterSection').value;
    const statusFilter = document.getElementById('filterStatus').value;

    const container = document.getElementById('studentsList');
    // Show loading only if not printing
    if (!window.matchMedia('print').matches) {
        container.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-500">লোডিং...</p></div>';
    }

    try {
        const response = await fetch('/api/tenant/students/qr-rfid-list', {
            method: 'POST',
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
            <div class="text-center py-8 text-gray-500 no-print">
                <p>কোন শিক্ষার্থী পাওয়া যায়নি</p>
            </div>
        `;
        return;
    }

    // Container for cards
    let html = '<div class="flex flex-wrap gap-4 justify-center print:gap-0 print:block">';
    
    students.forEach(student => {
        // Front Side
        const frontHtml = `
            <div class="id-card-container">
                <div class="id-card">
                    <!-- Front Side -->
                    <div class="id-card-content relative bg-white overflow-hidden h-full flex flex-col">
                        <!-- Header -->
                        <div class="bg-blue-700 p-2 text-white relative z-10 print:bg-blue-700 flex items-center justify-center gap-2">
                            <img src="${schoolLogo}" class="h-8 w-8 bg-white rounded-full p-0.5 object-contain">
                            <div class="text-center">
                                <h2 class="text-[10px] font-bold leading-tight">{{ $schoolSettings->school_name_bn ?? 'ইকরা নূরানী একাডেমী' }}</h2>
                                <p class="text-[8px] opacity-90">শিক্ষার্থী পরিচয়পত্র</p>
                            </div>
                        </div>
                        
                        <!-- Curve decoration -->
                        <div class="absolute top-[48px] left-0 w-full h-6 bg-blue-700 rounded-b-[50%] z-0 print:bg-blue-700"></div>

                        <!-- Photo -->
                        <div class="relative mt-1 text-center z-10">
                            <div class="w-[70px] h-[70px] mx-auto rounded-full border-2 border-blue-700 p-0.5 bg-white">
                                <img src="${student.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=4F46E5&color=fff&size=64'}" 
                                     alt="${student.name}" 
                                     class="w-full h-full object-cover rounded-full">
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="px-3 pt-1 text-center flex-grow flex flex-col items-center">
                            <h3 class="text-xs font-bold text-gray-800 leading-tight mb-0.5">${student.name}</h3>
                            <p class="text-[9px] text-gray-500 font-mono mb-1">ID: ${student.student_id}</p>

                            <table class="w-full text-[9px] text-left ml-4">
                                <tr>
                                    <td class="text-gray-500 py-0.5 w-14">শ্রেণী</td>
                                    <td class="font-bold text-gray-800 py-0.5">: ${student.class}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 py-0.5">শাখা</td>
                                    <td class="font-bold text-gray-800 py-0.5">: ${student.section || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 py-0.5">রোল</td>
                                    <td class="font-bold text-gray-800 py-0.5">: ${student.roll || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 py-0.5">সেশন</td>
                                    <td class="font-bold text-gray-800 py-0.5">: ${student.session || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 py-0.5">রক্তের গ্রুপ</td>
                                    <td class="font-bold text-red-600 py-0.5">: ${student.blood_group || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 py-0.5">মোবাইল</td>
                                    <td class="font-bold text-gray-800 py-0.5">: ${student.parent_mobile || '-'}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Footer Bar -->
                        <div class="bg-blue-700 w-full mt-auto print:bg-blue-700 text-white text-[9px] text-center py-1.5 flex justify-center items-center gap-1">
                             <span>জরুরি প্রয়োজনে:</span>
                             <span class="font-bold">{{ $schoolSettings->mobile ?? $schoolSettings->phone ?? '01799663210' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Back Side
        const backHtml = `
            <div class="id-card-container">
                <div class="id-card">
                    <!-- Back Side -->
                    <div class="id-card-content relative bg-white overflow-hidden h-full flex flex-col items-center justify-between p-2">
                        <div class="text-center w-full">
                            <h2 class="text-[10px] font-bold text-blue-700 mb-0.5">{{ $schoolSettings->school_name_bn ?? 'ইকরা নূরানী একাডেমী' }}</h2>
                            <div class="h-0.5 w-16 bg-blue-700 mx-auto mb-1"></div>
                            <p class="text-[8px] text-gray-500 leading-tight">এই কার্ডটি প্রতিষ্ঠানের সম্পত্তি। কার্ডটি হারিয়ে গেলে অবিলম্বে অফিসে যোগাযোগ করুন।</p>
                        </div>

                        <!-- QR Code -->
                        <div class="my-1 flex-grow flex items-center justify-center flex-col">
                             <div id="qrcode-${student.id}" class="bg-white p-1 mb-1"></div>
                        </div>
                        
                        <div class="w-full">
                            <div class="flex justify-center items-end w-full px-2 mb-2">
                                <div class="text-center">
                                    <div class="h-8 border-b border-gray-400 w-24 mb-1"></div>
                                    <p class="text-[8px] text-gray-500">অধ্যক্ষের স্বাক্ষর</p>
                                </div>
                            </div>
                            
                            <div class="text-[9px] font-medium text-center text-white bg-blue-700 py-1.5 -mx-2 mb-[-0.5rem] print:bg-blue-700 leading-tight min-h-[25px] flex items-center justify-center">
                                {{ $schoolSettings->address ?? 'ঠিকানা: আপনার স্কুলের ঠিকানা এখানে প্রদর্শিত হবে' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        html += `<div class="card-pair">${frontHtml}${backHtml}</div>`;
    });

    html += '</div>';
    container.innerHTML = html;

    // Generate QR Codes
    students.forEach(student => {
        if (student.qr_code) {
            new QRCode(document.getElementById(`qrcode-${student.id}`), {
                text: student.qr_code,
                width: 180,
                height: 180,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });
}
</script>

<style>
    /* Screen View */
    .id-card-container {
        margin: 5px;
    }
    
    .card-pair {
        display: flex;
        flex-direction: row;
        gap: 10px;
        margin-bottom: 20px;
        border: 1px dashed #e5e7eb;
        padding: 10px;
        border-radius: 8px;
    }

    .id-card {
        width: 54mm;
        height: 86mm;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        background: white;
        overflow: hidden;
    }

    /* Print View */
    @media print {
        @page {
            margin: 0.5cm;
            size: A4;
        }
        
        body {
            background: white;
            margin: 0;
            padding: 0;
        }
        
        .no-print {
            display: none !important;
        }
        
        #studentsList {
            display: block !important;
            padding: 0 !important;
        }

        .flex-wrap {
            display: block !important;
        }

        .card-pair {
            display: inline-block !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            page-break-inside: avoid;
            width: 110mm; /* 54mm * 2 + gap */
            margin-bottom: 5mm !important;
        }

        .id-card-container {
            display: inline-block !important;
            margin: 0 2mm 0 0 !important;
            vertical-align: top;
        }

        .id-card {
            width: 54mm !important;
            height: 86mm !important;
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            border-radius: 4px !important;
        }

        /* Force Background Colors */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Ensure specific colors print */
        .bg-blue-700 { background-color: #1d4ed8 !important; }
        .text-white { color: #ffffff !important; }
        .border-blue-700 { border-color: #1d4ed8 !important; }
    }
</style>
@endsection

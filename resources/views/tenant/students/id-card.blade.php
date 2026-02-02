@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8 no-print">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">আইডি কার্ড (ID Card)</h1>
            <p class="text-gray-600 mt-1">শিক্ষার্থীর আইডি কার্ড প্রিভিউ এবং ডাউনলোড</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                ফিরে যান
            </a>
            <button onclick="downloadIdCard()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                ডাউনলোড করুন
            </button>
        </div>
    </div>

    <div class="flex justify-center items-center min-h-[60vh] bg-gray-50 p-8 rounded-xl" id="cardContainer">
        <!-- ID Card Design -->
        <div id="idCard" class="relative w-[350px] h-[550px] bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            <!-- Header Pattern -->
            <div class="absolute top-0 w-full h-32 bg-gradient-to-r from-blue-600 to-indigo-700">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
            </div>

            <!-- Content -->
            <div class="relative flex flex-col items-center pt-8 px-6 h-full">
                <!-- School Logo/Name -->
                <div class="text-center mb-6">
                    <h2 class="text-white text-xl font-bold tracking-wide drop-shadow-md">{{ $schoolSettings->school_name_bn ?? config('app.name') }}</h2>
                    <p class="text-blue-100 text-xs mt-1">স্টুডেন্ট আইডি কার্ড</p>
                </div>

                <!-- Photo -->
                <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden mb-4 bg-gray-100 relative z-10">
                    <img src="{{ $student->photo_url ?? 'https://ui-avatars.com/api/?name=' . encodeURIComponent($student->name) . '&background=4F46E5&color=fff&size=128' }}" 
                         alt="{{ $student->name }}" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Student Info -->
                <div class="text-center w-full mt-2">
                    <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $student->name }}</h3>
                    <p class="text-gray-500 font-medium mb-6">ID: {{ $student->student_id }}</p>

                    <div class="space-y-3 text-left bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">শ্রেণী</span>
                            <span class="font-bold text-gray-800">{{ $student->class }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">শাখা</span>
                            <span class="font-bold text-gray-800">{{ $student->section ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">রোল</span>
                            <span class="font-bold text-gray-800">{{ $student->roll ?? $student->roll_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">রক্তের গ্রুপ</span>
                            <span class="font-bold text-gray-800">{{ $student->blood_group ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- QR Code (Optional) -->
                @if($student->qr_code)
                <div class="mt-auto mb-6">
                    <div id="qrcode" class="opacity-80"></div>
                </div>
                @endif
                
                <!-- Footer -->
                <div class="absolute bottom-0 w-full h-2 bg-blue-600"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for QR Code and Download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($student->qr_code)
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $student->qr_code }}",
                width: 64,
                height: 64,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        @endif
    });

    function downloadIdCard() {
        const card = document.getElementById('idCard');
        const btn = document.querySelector('button[onclick="downloadIdCard()"]');
        
        // Show loading state
        const originalText = btn.innerHTML;
        btn.innerHTML = 'প্রসেসিং...';
        btn.disabled = true;

        html2canvas(card, {
            scale: 3, // Higher resolution
            useCORS: true,
            backgroundColor: null
        }).then(canvas => {
            // Convert to Blob and download
            canvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.download = 'ID_Card_{{ $student->student_id }}.png';
                link.href = url;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                
                // Reset button
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 'image/png');
        }).catch(err => {
            console.error(err);
            alert('ডাউনলোড করতে সমস্যা হয়েছে');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        #cardContainer { align-items: flex-start; padding: 0; }
        #idCard { box-shadow: none; border: 1px solid #ddd; }
    }
</style>
@endsection
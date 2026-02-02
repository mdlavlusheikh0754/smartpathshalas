@extends('layouts.tenant')

@section('title', 'লগইন ম্যানেজমেন্ট')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">লগইন ম্যানেজমেন্ট</h1>
                <p class="text-gray-600">শিক্ষার্থী ও অভিভাবকদের লগইন তথ্য পরিচালনা করুন</p>
            </div>
            <a href="{{ route('tenant.students.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>
    </div>

    <!-- Success Messages -->
    @if(isset($passwordsGenerated) && $passwordsGenerated > 0)
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
            <div>
                <p class="font-semibold text-green-800">সফলভাবে {{ $passwordsGenerated }} জন শিক্ষার্থীর পাসওয়ার্ড তৈরি করা হয়েছে!</p>
            </div>
        </div>
    </div>
    @endif

    @if(isset($guardiansCreated) && $guardiansCreated > 0)
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <i class="fas fa-user-plus text-blue-600 text-xl"></i>
            <div>
                <p class="font-semibold text-blue-800">সফলভাবে {{ $guardiansCreated }} জন অভিভাবকের অ্যাকাউন্ট তৈরি করা হয়েছে!</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Info Cards -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <!-- Student Login Info -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">শিক্ষার্থী লগইন</h3>
                    <p class="text-green-100 text-sm">Student Login Credentials</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <p><i class="fas fa-check-circle mr-2"></i>ইউজারনেম: Student ID</p>
                <p><i class="fas fa-check-circle mr-2"></i>ডিফল্ট পাসওয়ার্ড: জন্ম তারিখ (ddmmyyyy)</p>
                <p><i class="fas fa-link mr-2"></i>লগইন URL: <span class="font-mono">{{ url('/student/login') }}</span></p>
            </div>
        </div>

        <!-- Guardian Login Info -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">অভিভাবক লগইন</h3>
                    <p class="text-purple-100 text-sm">Guardian Login Credentials</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <p><i class="fas fa-check-circle mr-2"></i>ইউজারনেম: মোবাইল নম্বর</p>
                <p><i class="fas fa-check-circle mr-2"></i>ডিফল্ট পাসওয়ার্ড: জন্ম তারিখ (ddmmyyyy)</p>
                <p><i class="fas fa-link mr-2"></i>লগইন URL: <span class="font-mono">{{ url('/guardian/login') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white">শিক্ষার্থীদের লগইন তথ্য</h2>
        </div>

        <div class="p-6">
            <!-- Search and Filter -->
            <div class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="নাম, Student ID, বা মোবাইল নম্বর দিয়ে খুঁজুন..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="w-64">
                    <select id="classFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল ক্লাস</option>
                        @php
                            $classes = $students->pluck('class')->unique()->sort()->values();
                        @endphp
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="printLoginInfo()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i>প্রিন্ট করুন
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="loginTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">শিক্ষার্থী</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ক্লাস</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">শিক্ষার্থী পাসওয়ার্ড</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">অভিভাবক মোবাইল</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">অভিভাবক পাসওয়ার্ড</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($student->photo)
                                        <img src="{{ $student->photo_url }}" alt="{{ $student->name_bn }}" class="flex-shrink-0 h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($student->name_bn ?? 'S', 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $student->name_bn }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->name_en }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $student->class }}</div>
                                <div class="text-xs text-gray-500">{{ $student->section }} শাখা</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="text-sm font-mono font-bold text-blue-600" id="student-id-{{ $student->id }}">{{ $student->student_id }}</div>
                                    <button onclick="copyPassword('student-id-{{ $student->id }}')" class="text-gray-400 hover:text-blue-600 transition-colors" title="কপি করুন">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->date_of_birth)
                                    <div class="flex items-center gap-2">
                                        <code class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded font-mono" id="student-pass-{{ $student->id }}">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('dmY') }}</code>
                                        <button onclick="copyPassword('student-pass-{{ $student->id }}')" class="text-gray-400 hover:text-blue-600 transition-colors" title="কপি করুন">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button onclick="regeneratePassword({{ $student->id }}, 'student')" class="text-gray-400 hover:text-green-600 transition-colors" title="পাসওয়ার্ড পুনরায় তৈরি করুন">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-400">N/A</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->guardian && $student->guardian->phone)
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-mono text-gray-900" id="guardian-phone-{{ $student->id }}">{{ $student->guardian->phone }}</div>
                                        <button onclick="copyPassword('guardian-phone-{{ $student->id }}')" class="text-gray-400 hover:text-purple-600 transition-colors" title="কপি করুন">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @elseif($student->phone)
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-mono text-gray-900" id="guardian-phone-{{ $student->id }}">{{ $student->phone }}</div>
                                        <button onclick="copyPassword('guardian-phone-{{ $student->id }}')" class="text-gray-400 hover:text-purple-600 transition-colors" title="কপি করুন">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-400">N/A</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->date_of_birth)
                                    <div class="flex items-center gap-2">
                                        <code class="text-sm bg-purple-100 text-purple-800 px-3 py-1 rounded font-mono" id="guardian-pass-{{ $student->id }}">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('dmY') }}</code>
                                        <button onclick="copyPassword('guardian-pass-{{ $student->id }}')" class="text-gray-400 hover:text-purple-600 transition-colors" title="কপি করুন">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button onclick="regeneratePassword({{ $student->id }}, 'guardian')" class="text-gray-400 hover:text-orange-600 transition-colors" title="পাসওয়ার্ড পুনরায় তৈরি করুন">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-400">N/A</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="sendLoginInfo('{{ $student->id }}')" class="text-blue-600 hover:text-blue-700 font-medium text-sm" title="লগইন তথ্য পাঠান">
                                    <i class="fas fa-paper-plane mr-1"></i>পাঠান
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-users text-5xl mb-4"></i>
                                    <p class="text-lg font-medium">কোনো শিক্ষার্থী পাওয়া যায়নি</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1"></i>
            <div>
                <h3 class="font-bold text-gray-900 mb-2">গুরুত্বপূর্ণ নোট:</h3>
                <ul class="space-y-1 text-sm text-gray-700">
                    <li>• ডিফল্ট পাসওয়ার্ড হল শিক্ষার্থীর জন্ম তারিখ (ddmmyyyy ফরম্যাটে)</li>
                    <li>• শিক্ষার্থী এবং অভিভাবক উভয়ের জন্য একই পাসওয়ার্ড ব্যবহার করা হয়</li>
                    <li>• প্রথমবার লগইনের পর পাসওয়ার্ড পরিবর্তন করার পরামর্শ দেওয়া হয়</li>
                    <li>• লগইন তথ্য SMS এর মাধ্যমে অভিভাবকদের কাছে পাঠানো যাবে</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const classFilter = document.getElementById('classFilter').value;
    const table = document.getElementById('loginTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    let visibleCount = 0;
    
    for (let row of rows) {
        // Skip empty state row
        if (row.cells.length === 1) {
            continue;
        }
        
        const text = row.textContent.toLowerCase();
        const classCell = row.cells[1]; // Class column is index 1
        const classText = classCell ? classCell.textContent.trim().split('\n')[0].trim() : '';
        
        // Check search term
        const matchesSearch = searchTerm === '' || text.includes(searchTerm);
        
        // Check class filter
        const matchesClass = classFilter === '' || classText === classFilter;
        
        // Show row if both conditions match
        if (matchesSearch && matchesClass) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    }
    
    // Update count display if exists
    updateFilterCount(visibleCount);
}

// Update filter count
function updateFilterCount(count) {
    let countElement = document.getElementById('filterCount');
    if (!countElement) {
        // Create count element if it doesn't exist
        const filterContainer = document.querySelector('.mb-6.flex.gap-4');
        if (filterContainer) {
            countElement = document.createElement('div');
            countElement.id = 'filterCount';
            countElement.className = 'text-sm text-gray-600 self-center';
            filterContainer.appendChild(countElement);
        }
    }
    if (countElement) {
        countElement.textContent = `${count} জন শিক্ষার্থী`;
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', filterTable);

// Class filter functionality
document.getElementById('classFilter').addEventListener('change', filterTable);

// Initialize count on page load
window.addEventListener('load', function() {
    const table = document.getElementById('loginTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    let count = 0;
    for (let row of rows) {
        if (row.cells.length > 1 && row.style.display !== 'none') {
            count++;
        }
    }
    updateFilterCount(count);
});

// Copy password to clipboard
function copyPassword(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        showToast('কপি করতে ব্যর্থ হয়েছে', 'error');
        return;
    }
    
    const text = element.textContent.trim();
    if (!text || text === 'N/A') {
        showToast('কপি করার জন্য কোনো তথ্য নেই', 'error');
        return;
    }
    
    // Create temporary input
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    
    try {
        document.execCommand('copy');
        showToast('কপি হয়েছে: ' + text, 'success');
    } catch (err) {
        // Fallback to clipboard API
        navigator.clipboard.writeText(text).then(() => {
            showToast('কপি হয়েছে: ' + text, 'success');
        }).catch(() => {
            showToast('কপি করতে ব্যর্থ হয়েছে', 'error');
        });
    } finally {
        document.body.removeChild(tempInput);
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
    toast.innerHTML = `<i class="fas ${icon} mr-2"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Regenerate password
function regeneratePassword(studentId, type) {
    if (!confirm(`এই ${type === 'student' ? 'শিক্ষার্থীর' : 'অভিভাবকের'} পাসওয়ার্ড পুনরায় তৈরি করতে চান?`)) {
        return;
    }
    
    // Show loading
    showToast('পাসওয়ার্ড তৈরি হচ্ছে...', 'success');
    
    // Send AJAX request
    fetch(`/students/${studentId}/regenerate-password`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('পাসওয়ার্ড সফলভাবে পুনরায় তৈরি করা হয়েছে!', 'success');
            // Reload page to show new password
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'পাসওয়ার্ড তৈরি করতে ব্যর্থ হয়েছে', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('পাসওয়ার্ড তৈরি করতে ব্যর্থ হয়েছে', 'error');
    });
}

// Send login info
function sendLoginInfo(studentId) {
    if (confirm('এই শিক্ষার্থীর লগইন তথ্য SMS এর মাধ্যমে পাঠাতে চান?')) {
        // TODO: Implement SMS sending functionality
        alert('SMS পাঠানোর ফিচার শীঘ্রই যুক্ত করা হবে');
    }
}

// Print login info
function printLoginInfo() {
    window.print();
}
</script>

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
</style>
@endsection

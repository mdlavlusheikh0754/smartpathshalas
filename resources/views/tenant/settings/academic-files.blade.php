@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">একাডেমিক ফাইল ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">সিলেবাস, ছুটির দিন এবং একাডেমিক ক্যালেন্ডার পরিচালনা করুন</p>
            </div>
            <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 text-center">
            {{ session('success') }}
        </div>
        @endif

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-lg mb-6 overflow-hidden">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button type="button" onclick="showTab('syllabus')" class="tab-btn px-6 py-4 font-bold text-white bg-blue-600 border-b-4 border-blue-600 whitespace-nowrap transition-all duration-300 hover:bg-blue-700" id="tab-syllabus">
                    <i class="fas fa-book mr-2"></i>সিলেবাস
                </button>
                <button type="button" onclick="showTab('holidays')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-holidays">
                    <i class="fas fa-calendar-xmark mr-2"></i>ছুটির দিন
                </button>
                <button type="button" onclick="showTab('calendar')" class="tab-btn px-6 py-4 font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b-4 border-transparent whitespace-nowrap transition-all duration-300" id="tab-calendar">
                    <i class="fas fa-calendar-alt mr-2"></i>একাডেমিক ক্যালেন্ডার
                </button>
            </div>
        </div>

        <!-- Syllabus Tab -->
        <div id="syllabus-tab" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">সিলেবাস আপলোড করুন</h3>
                        <form id="syllabus-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">শ্রেণী <span class="text-red-500">*</span></label>
                                <select name="class_id" id="syllabus-class" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">শ্রেণী নির্বাচন করুন</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->section }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">পরীক্ষা</label>
                                <select name="exam_id" id="syllabus-exam" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">পরীক্ষা নির্বাচন করুন (ঐচ্ছিক)</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">বিষয়</label>
                                <select name="subject_id" id="syllabus-subject" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">বিষয় নির্বাচন করুন (ঐচ্ছিক)</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">ফাইল <span class="text-red-500">*</span></label>
                                <input type="file" name="file" accept=".pdf" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <p class="text-xs text-gray-500 mt-1">শুধুমাত্র PDF ফাইল (সর্বোচ্চ 10MB)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">বর্ণনা</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="সিলেবাস সম্পর্কে বর্ণনা লিখুন..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                <i class="fas fa-upload mr-2"></i>আপলোড করুন
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">আপলোড করা সিলেবাস</h3>
                        <div id="syllabus-list" class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($syllabuses as $syllabus)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $syllabus->schoolClass->name }} - {{ $syllabus->schoolClass->section }}</p>
                                        <p class="text-sm text-gray-600">
                                            @if($syllabus->exam)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-2">{{ $syllabus->exam->name }}</span>
                                            @endif
                                            @if($syllabus->subject)
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs">{{ $syllabus->subject->name }}</span>
                                            @endif
                                        </p>
                                        @if($syllabus->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $syllabus->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $syllabus->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-700 p-2">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" onclick="deleteSyllabus({{ $syllabus->id }})" class="text-red-600 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">কোনো সিলেবাস আপলোড করা হয়নি</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Holidays Tab -->
        <div id="holidays-tab" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">ছুটির দিন যোগ করুন</h3>
                        <form id="holiday-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">ছুটির নাম <span class="text-red-500">*</span></label>
                                <input type="text" name="holiday_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: পহেলা বৈশাখ" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">শুরু তারিখ <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">শেষ তারিখ <span class="text-red-500">*</span></label>
                                <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">বর্ণনা</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="ছুটির দিন সম্পর্কে বর্ণনা..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                <i class="fas fa-plus mr-2"></i>যোগ করুন
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">ছুটির দিনের তালিকা</h3>
                        <div id="holiday-list" class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($holidays as $holiday)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $holiday->holiday_name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $holiday->start_date->format('d M Y') }} থেকে {{ $holiday->end_date->format('d M Y') }}
                                        </p>
                                        @if($holiday->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $holiday->description }}</p>
                                        @endif
                                    </div>
                                    <button type="button" onclick="deleteHoliday({{ $holiday->id }})" class="text-red-600 hover:text-red-700 p-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">কোনো ছুটির দিন যোগ করা হয়নি</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Tab -->
        <div id="calendar-tab" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">ক্যালেন্ডার আপলোড করুন</h3>
                        <form id="calendar-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">একাডেমিক সেশন <span class="text-red-500">*</span></label>
                                <select name="academic_session_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">সেশন নির্বাচন করুন</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->session_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">ফাইল <span class="text-red-500">*</span></label>
                                <input type="file" name="file" accept=".pdf" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <p class="text-xs text-gray-500 mt-1">শুধুমাত্র PDF ফাইল (সর্বোচ্চ 10MB)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">বর্ণনা</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="ক্যালেন্ডার সম্পর্কে বর্ণনা..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                <i class="fas fa-upload mr-2"></i>আপলোড করুন
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">আপলোড করা ক্যালেন্ডার</h3>
                        <div id="calendar-list" class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($calendars as $calendar)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $calendar->academicSession->session_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $calendar->file_name }}</p>
                                        @if($calendar->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $calendar->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $calendar->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-700 p-2">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" onclick="deleteCalendar({{ $calendar->id }})" class="text-red-600 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">কোনো ক্যালেন্ডার আপলোড করা হয়নি</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Update button styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-white', 'bg-blue-600', 'border-b-blue-600');
        btn.classList.add('text-gray-700', 'hover:bg-blue-50', 'hover:text-blue-600', 'border-b-transparent');
    });
    
    document.getElementById('tab-' + tabName).classList.remove('text-gray-700', 'hover:bg-blue-50', 'hover:text-blue-600', 'border-b-transparent');
    document.getElementById('tab-' + tabName).classList.add('text-white', 'bg-blue-600', 'border-b-blue-600');
}

// Syllabus Form
document.getElementById('syllabus-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("tenant.settings.academic-files.syllabus.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('ত্রুটি: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Holiday Form
document.getElementById('holiday-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("tenant.settings.academic-files.holiday.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('ত্রুটি: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Calendar Form
document.getElementById('calendar-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("tenant.settings.academic-files.calendar.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('ত্রুটি: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Delete Functions
function deleteSyllabus(id) {
    if (confirm('এই সিলেবাস মুছে ফেলতে চান?')) {
        fetch('{{ route("tenant.settings.academic-files.syllabus.delete", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('ত্রুটি: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function deleteHoliday(id) {
    if (confirm('এই ছুটির দিন মুছে ফেলতে চান?')) {
        fetch('{{ route("tenant.settings.academic-files.holiday.delete", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('ত্রুটি: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function deleteCalendar(id) {
    if (confirm('এই ক্যালেন্ডার মুছে ফেলতে চান?')) {
        fetch('{{ route("tenant.settings.academic-files.calendar.delete", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('ত্রুটি: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection

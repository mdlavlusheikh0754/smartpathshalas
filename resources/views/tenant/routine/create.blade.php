@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 bengali-text">নতুন রুটিন তৈরি করুন</h1>
            <p class="text-gray-600 mt-2 bengali-text">ক্লাসের জন্য সাপ্তাহিক রুটিন তৈরি করুন</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('tenant.routine.store') }}" method="POST">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">ক্লাস নির্বাচন *</label>
                        <select name="class_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" id="classSelect">
                            <option value="">ক্লাস নির্বাচন করুন</option>
                            <option value="6">৬ষ্ঠ শ্রেণি</option>
                            <option value="7">৭ম শ্রেণি</option>
                            <option value="8">৮ম শ্রেণি</option>
                            <option value="9">৯ম শ্রেণি</option>
                            <option value="10">১০ম শ্রেণি</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">শাখা</label>
                        <select name="section" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="A">শাখা - ক</option>
                            <option value="B">শাখা - খ</option>
                            <option value="C">শাখা - গ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">শিক্ষাবর্ষ</label>
                        <select name="academic_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="2026">২০২৬</option>
                            <option value="2025">২০২৫</option>
                        </select>
                    </div>
                </div>

                <!-- Time Slots -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 bengali-text">সময়সূচি নির্ধারণ</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="timeSlots">
                        <div class="time-slot">
                            <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">১ম পিরিয়ড</label>
                            <div class="flex space-x-2">
                                <input type="time" name="periods[1][start]" value="08:00" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <input type="time" name="periods[1][end]" value="08:45" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="time-slot">
                            <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">২য় পিরিয়ড</label>
                            <div class="flex space-x-2">
                                <input type="time" name="periods[2][start]" value="08:45" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <input type="time" name="periods[2][end]" value="09:30" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="time-slot">
                            <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">বিরতি</label>
                            <div class="flex space-x-2">
                                <input type="time" name="break[start]" value="09:30" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <input type="time" name="break[end]" value="09:45" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="time-slot">
                            <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">৩য় পিরিয়ড</label>
                            <div class="flex space-x-2">
                                <input type="time" name="periods[3][start]" value="09:45" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <input type="time" name="periods[3][end]" value="10:30" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mt-4 text-indigo-600 hover:text-indigo-800 text-sm bengali-text" onclick="addTimePeriod()">+ আরো পিরিয়ড যোগ করুন</button>
                </div>

                <!-- Weekly Schedule -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 bengali-text">সাপ্তাহিক রুটিন</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200" id="routineTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">সময়</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">শনিবার</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">রবিবার</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">সোমবার</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">মঙ্গলবার</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r bengali-text">বুধবার</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bengali-text">বৃহস্পতিবার</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r bengali-text">৮:০০ - ৮:৪৫</td>
                                    <td class="px-4 py-3 text-sm border-r">
                                        <div class="space-y-2">
                                            <select name="schedule[1][saturday][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">বিষয় নির্বাচন</option>
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">ইংরেজি</option>
                                                <option value="math">গণিত</option>
                                                <option value="science">বিজ্ঞান</option>
                                                <option value="islam">ইসলাম শিক্ষা</option>
                                                <option value="arabic">আরবি</option>
                                                <option value="quran">কুরআন মজিদ</option>
                                                <option value="hadith">হাদিস শরিফ</option>
                                            </select>
                                            <select name="schedule[1][saturday][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">শিক্ষক নির্বাচন</option>
                                                <option value="1">মোঃ আব্দুল করিম</option>
                                                <option value="2">ফাতেমা খাতুন</option>
                                                <option value="3">রহিমা বেগম</option>
                                                <option value="4">হাফেজ সাহেব</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-r">
                                        <div class="space-y-2">
                                            <select name="schedule[1][sunday][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">বিষয় নির্বাচন</option>
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">ইংরেজি</option>
                                                <option value="math">গণিত</option>
                                                <option value="science">বিজ্ঞান</option>
                                                <option value="islam">ইসলাম শিক্ষা</option>
                                                <option value="arabic">আরবি</option>
                                                <option value="quran">কুরআন মজিদ</option>
                                                <option value="hadith">হাদিস শরিফ</option>
                                            </select>
                                            <select name="schedule[1][sunday][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">শিক্ষক নির্বাচন</option>
                                                <option value="1">মোঃ আব্দুল করিম</option>
                                                <option value="2">ফাতেমা খাতুন</option>
                                                <option value="3">রহিমা বেগম</option>
                                                <option value="4">হাফেজ সাহেব</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-r">
                                        <div class="space-y-2">
                                            <select name="schedule[1][monday][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">বিষয় নির্বাচন</option>
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">ইংরেজি</option>
                                                <option value="math">গণিত</option>
                                                <option value="science">বিজ্ঞান</option>
                                                <option value="islam">ইসলাম শিক্ষা</option>
                                                <option value="arabic">আরবি</option>
                                                <option value="quran">কুরআন মজিদ</option>
                                                <option value="hadith">হাদিস শরিফ</option>
                                            </select>
                                            <select name="schedule[1][monday][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">শিক্ষক নির্বাচন</option>
                                                <option value="1">মোঃ আব্দুল করিম</option>
                                                <option value="2">ফাতেমা খাতুন</option>
                                                <option value="3">রহিমা বেগম</option>
                                                <option value="4">হাফেজ সাহেব</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-r">
                                        <div class="space-y-2">
                                            <select name="schedule[1][tuesday][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">বিষয় নির্বাচন</option>
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">ইংরেজি</option>
                                                <option value="math">গণিত</option>
                                                <option value="science">বিজ্ঞান</option>
                                                <option value="islam">ইসলাম শিক্ষা</option>
                                                <option value="arabic">আরবি</option>
                                                <option value="quran">কুরআন মজিদ</option>
                                                <option value="hadith">হাদিস শরিফ</option>
                                            </select>
                                            <select name="schedule[1][tuesday][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">শিক্ষক নির্বাচন</option>
                                                <option value="1">মোঃ আব্দুল করিম</option>
                                                <option value="2">ফাতেমা খাতুন</option>
                                                <option value="3">রহিমা বেগম</option>
                                                <option value="4">হাফেজ সাহেব</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-r">
                                        <div class="space-y-2">
                                            <select name="schedule[1][wednesday][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">বিষয় নির্বাচন</option>
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">ইংরেজি</option>
                                                <option value="math">গণিত</option>
                                                <option value="science">বিজ্ঞান</option>
                                                <option value="islam">ইসলাম শিক্ষা</option>
                                                <option value="arabic">আরবি</option>
                                                <option value="quran">কুরআন মজিদ</option>
                                                <option value="hadith">হাদিস শরিফ</option>
                                            </select>
                                            <select name="schedule[1][wednesday][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">শিক্ষক নির্বাচন</option>
                                                <option value="1">মোঃ আব্দুল করিম</option>
                                                <option value="2">ফাতেমা খাতুন</option>
                                                <option value="3">রহিমা বেগম</option>
                                                <option value="4">হাফেজ সাহেব</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="space-y-2">
                                            <select name="schedule[1][thursday][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">বিষয় নির্বাচন</option>
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">ইংরেজি</option>
                                                <option value="math">গণিত</option>
                                                <option value="science">বিজ্ঞান</option>
                                                <option value="islam">ইসলাম শিক্ষা</option>
                                                <option value="arabic">আরবি</option>
                                                <option value="quran">কুরআন মজিদ</option>
                                                <option value="hadith">হাদিস শরিফ</option>
                                            </select>
                                            <select name="schedule[1][thursday][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                                                <option value="">শিক্ষক নির্বাচন</option>
                                                <option value="1">মোঃ আব্দুল করিম</option>
                                                <option value="2">ফাতেমা খাতুন</option>
                                                <option value="3">রহিমা বেগম</option>
                                                <option value="4">হাফেজ সাহেব</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <!-- More rows will be added dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('tenant.routine.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors bengali-text">বাতিল</a>
                    <button type="button" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors bengali-text" onclick="previewRoutine()">প্রিভিউ</button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors bengali-text">রুটিন সংরক্ষণ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let periodCount = 3;

function addTimePeriod() {
    periodCount++;
    const timeSlotsContainer = document.getElementById('timeSlots');
    
    const newTimeSlot = document.createElement('div');
    newTimeSlot.className = 'time-slot';
    newTimeSlot.innerHTML = `
        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">${periodCount}য় পিরিয়ড</label>
        <div class="flex space-x-2">
            <input type="time" name="periods[${periodCount}][start]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <input type="time" name="periods[${periodCount}][end]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
    `;
    
    timeSlotsContainer.appendChild(newTimeSlot);
    
    // Add corresponding row to routine table
    addRoutineRow(periodCount);
}

function addRoutineRow(periodNum) {
    const tableBody = document.querySelector('#routineTable tbody');
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r bengali-text">${periodNum}য় পিরিয়ড</td>
        ${['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'].map(day => `
            <td class="px-4 py-3 text-sm ${day !== 'thursday' ? 'border-r' : ''}">
                <div class="space-y-2">
                    <select name="schedule[${periodNum}][${day}][subject]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                        <option value="">বিষয় নির্বাচন</option>
                        <option value="bangla">বাংলা</option>
                        <option value="english">ইংরেজি</option>
                        <option value="math">গণিত</option>
                        <option value="science">বিজ্ঞান</option>
                        <option value="islam">ইসলাম শিক্ষা</option>
                        <option value="arabic">আরবি</option>
                        <option value="quran">কুরআন মজিদ</option>
                        <option value="hadith">হাদিস শরিফ</option>
                    </select>
                    <select name="schedule[${periodNum}][${day}][teacher]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text text-sm">
                        <option value="">শিক্ষক নির্বাচন</option>
                        <option value="1">মোঃ আব্দুল করিম</option>
                        <option value="2">ফাতেমা খাতুন</option>
                        <option value="3">রহিমা বেগম</option>
                        <option value="4">হাফেজ সাহেব</option>
                    </select>
                </div>
            </td>
        `).join('')}
    `;
    
    tableBody.appendChild(newRow);
}

function previewRoutine() {
    // This would open a modal or new window with routine preview
    alert('রুটিন প্রিভিউ ফিচার শীঘ্রই আসছে!');
}

// Auto-populate teacher based on subject selection
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[subject]')) {
        const teacherSelect = e.target.parentNode.querySelector('select[name*="[teacher]"]');
        const subject = e.target.value;
        
        // Auto-select appropriate teacher based on subject
        const subjectTeacherMap = {
            'bangla': '1',
            'english': '2', 
            'math': '3',
            'science': '2',
            'islam': '4',
            'arabic': '4',
            'quran': '4',
            'hadith': '4'
        };
        
        if (subjectTeacherMap[subject]) {
            teacherSelect.value = subjectTeacherMap[subject];
        }
    }
});
</script>
@endpush
@endsection

@extends('tenant.layouts.web')

@section('title', 'ক্লাস রুটিন')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">ক্লাস রুটিন</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের নিয়মিত ক্লাস সমূহের সময়সূচী</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filters/Selection -->
    <div class="bg-white rounded-3xl shadow-xl p-8 mb-12 border border-gray-100 -mt-20 relative z-20">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-1">শ্রেণী নির্বাচন করুন</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <select id="class-select" class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none font-medium text-gray-800">
                        <option value="">শ্রেণী নির্বাচন করুন</option>
                        @php
                            $uniqueClassNames = $classes->pluck('name')->unique()->sort();
                        @endphp
                        @foreach($uniqueClassNames as $className)
                            <option value="{{ $className }}">{{ $className }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-1">শাখা নির্বাচন করুন</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <select id="section-select" class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none appearance-none font-medium text-gray-800" disabled>
                        <option value="">প্রথমে শ্রেণী নির্বাচন করুন</option>
                    </select>
                </div>
            </div>

            <div class="flex items-end">
                <button id="search-btn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-2xl transition-all shadow-lg hover:shadow-blue-200 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed group" disabled>
                    <i class="fas fa-search group-hover:scale-110 transition-transform"></i> রুটিন দেখুন
                </button>
            </div>
        </div>
    </div>

    <!-- Routine Display Area -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 px-10 py-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">সাপ্তাহিক ক্লাস রুটিন</h2>
                    <p class="text-gray-500 text-sm">বিস্তারিত সময়সূচী নিচে দেখুন</p>
                </div>
            </div>
            
            @if($websiteSettings && $websiteSettings->class_routine_pdf)
            <a href="{{ asset('storage/' . $websiteSettings->class_routine_pdf) }}" target="_blank" class="flex items-center gap-3 bg-red-50 text-red-600 px-6 py-3 rounded-xl font-bold hover:bg-red-600 hover:text-white transition-all group">
                <i class="fas fa-file-pdf text-xl"></i> 
                <span>পিডিএফ ডাউনলোড</span>
                <i class="fas fa-download text-sm group-hover:translate-y-1 transition-transform"></i>
            </a>
            @endif
        </div>
        
        <div id="routine-content" class="flex flex-col items-center justify-center">
            <div id="routine-placeholder" class="flex-1 flex flex-col items-center justify-center p-12 text-center w-full">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-calendar-days text-4xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">রুটিন নির্বাচন করুন</h3>
                <p class="text-gray-500 max-w-md mx-auto">নির্দিষ্ট শ্রেণীর রুটিন দেখতে উপরে শ্রেণী এবং শাখা নির্বাচন করে সার্চ করুন।</p>
            </div>
            
            <div id="routine-table" class="hidden overflow-x-auto w-full flex justify-center">
                <!-- Routine table will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
    #routine-table {
        width: 100%;
        overflow-x: auto;
        display: flex;
        justify-content: center;
    }
    
    table.landscape-routine {
        width: auto;
        border-collapse: collapse;
        min-width: 1200px;
        margin: 0 auto;
    }
    
    table.landscape-routine thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    table.landscape-routine thead th {
        color: white;
        padding: 14px 12px;
        text-align: center;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    table.landscape-routine th.time-col {
        width: 90px;
        text-align: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    table.landscape-routine th.day-col {
        min-width: 140px;
        width: 140px;
    }
    
    table.landscape-routine tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }
    
    table.landscape-routine tbody tr:hover {
        background-color: #f9fafb;
    }
    
    table.landscape-routine tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }
    
    table.landscape-routine td {
        padding: 12px 8px;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    table.landscape-routine td.time-cell {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        font-weight: 700;
        color: #374151;
        text-align: center;
        width: 90px;
        min-width: 90px;
        position: sticky;
        left: 0;
        z-index: 5;
        font-size: 13px;
    }
    
    table.landscape-routine td.routine-cell {
        text-align: center;
        min-width: 140px;
        width: 140px;
        padding: 8px 6px;
    }
    
    table.landscape-routine td.empty-cell {
        background-color: #fafbfc;
    }
    
    table.landscape-routine td.class-cell {
        background: linear-gradient(135deg, #f0f4ff 0%, #f5f3ff 100%);
    }
    
    table.landscape-routine td.break-cell {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }
    
    .routine-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 11px;
    }
    
    .routine-content .subject {
        font-weight: 700;
        color: #1f2937;
        font-size: 14px;
        line-height: 1.3;
    }
    
    .routine-content .teacher {
        color: #6b7280;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }
    
    .routine-content .teacher i {
        font-size: 9px;
        opacity: 0.7;
    }
    
    .routine-content .room {
        color: #6b7280;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }
    
    .routine-content .room i {
        font-size: 9px;
        opacity: 0.7;
    }
    
    .break-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(217, 119, 6, 0.1);
        color: #b45309;
        padding: 6px 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 11px;
        border: 1px solid rgba(217, 119, 6, 0.3);
    }
    
    .break-badge i {
        font-size: 12px;
    }
    
    @media (max-width: 1024px) {
        table.landscape-routine {
            min-width: 900px;
            font-size: 11px;
        }
        
        table.landscape-routine th.day-col {
            min-width: 120px;
            width: 120px;
        }
        
        table.landscape-routine td.routine-cell {
            min-width: 120px;
            width: 120px;
            padding: 6px 4px;
        }
        
        .routine-content {
            font-size: 10px;
            gap: 2px;
        }
        
        .routine-content .subject {
            font-size: 11px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Routine page loaded');
    
    const classSelect = document.getElementById('class-select');
    const sectionSelect = document.getElementById('section-select');
    const searchBtn = document.getElementById('search-btn');
    const routinePlaceholder = document.getElementById('routine-placeholder');
    const routineTable = document.getElementById('routine-table');
    
    console.log('Elements found:', {
        classSelect: !!classSelect,
        sectionSelect: !!sectionSelect,
        searchBtn: !!searchBtn,
        routinePlaceholder: !!routinePlaceholder,
        routineTable: !!routineTable
    });
    
    // Enable/disable search button
    function updateSearchButton() {
        searchBtn.disabled = !(classSelect.value && sectionSelect.value);
    }
    
    classSelect.addEventListener('change', function() {
        console.log('Class selected:', this.value);
        const selectedClassName = this.value;
        
        // Clear and disable section select
        sectionSelect.innerHTML = '<option value="">শাখা নির্বাচন করুন</option>';
        sectionSelect.disabled = true;
        
        if (selectedClassName) {
            // Get sections for selected class name
            const allClassData = {!! json_encode($classes->toArray()) !!};
            const sections = allClassData.filter(c => c.name === selectedClassName);
            
            console.log('Available sections:', sections);
            
            if (sections.length > 0) {
                sections.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.id;
                    option.textContent = c.section + (c.section.length === 1 ? ' শাখা' : '');
                    sectionSelect.appendChild(option);
                });
                sectionSelect.disabled = false;
            }
        }
        
        updateSearchButton();
    });
    
    sectionSelect.addEventListener('change', function() {
        console.log('Class ID selected:', this.value);
        updateSearchButton();
    });
    
    searchBtn.addEventListener('click', function() {
        const classId = sectionSelect.value;
        
        console.log('Search clicked - Class ID:', classId);
        
        if (!classId) return;
        
        // Show loading state
        routinePlaceholder.classList.add('hidden');
        routineTable.classList.remove('hidden');
        routineTable.innerHTML = '<div class="flex items-center justify-center p-12"><i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i></div>';
        
        // Fetch routine data
        const apiUrl = '/api/v1/routine/class-routine?class_id=' + classId;
        console.log('Fetching from:', apiUrl);
        
        fetch(apiUrl)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Routine data:', data);
                if (data.success && data.routines && data.routines.length > 0) {
                    displayRoutine(data.routines);
                } else {
                    routineTable.innerHTML = `
                        <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">রুটিন পাওয়া যায়নি</h3>
                            <p class="text-gray-500">এই শ্রেণীর জন্য কোনো রুটিন তৈরি করা হয়নি।</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                routineTable.innerHTML = `
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                        <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-exclamation-circle text-4xl text-red-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">ত্রুটি হয়েছে</h3>
                        <p class="text-gray-500">রুটিন লোড করতে সমস্যা হয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।</p>
                        <p class="text-sm text-gray-400 mt-2">Error: ${error.message}</p>
                    </div>
                `;
            });
    });
    
    function displayRoutine(routines) {
        const days = ['শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার'];
        
        console.log('Displaying routines:', routines);
        
        // Group routines by day
        const routinesByDay = {};
        days.forEach(day => {
            routinesByDay[day] = [];
        });
        
        routines.forEach(routine => {
            const dayIndex = parseInt(routine.day) - 1; // day is 1-7
            const dayName = days[dayIndex] || 'Unknown';
            if (routinesByDay[dayName]) {
                routinesByDay[dayName].push(routine);
            }
        });
        
        // Get all unique time slots
        const timeSlots = new Set();
        routines.forEach(routine => {
            timeSlots.add(routine.start_time);
        });
        const sortedTimeSlots = Array.from(timeSlots).sort();
        
        let html = '<table class="landscape-routine">';
        
        // Header with days
        html += '<thead><tr>';
        html += '<th class="time-col">সময়</th>';
        days.forEach(day => {
            html += `<th class="day-col">${day}</th>`;
        });
        html += '</tr></thead>';
        
        // Body with time slots
        html += '<tbody>';
        sortedTimeSlots.forEach(timeSlot => {
            html += '<tr>';
            html += `<td class="time-cell"><strong>${timeSlot}</strong></td>`;
            
            days.forEach(day => {
                const dayRoutines = routinesByDay[day];
                const routine = dayRoutines.find(r => r.start_time === timeSlot);
                
                if (routine) {
                    if (routine.is_break) {
                        html += `<td class="routine-cell break-cell">
                                    <div class="break-badge">
                                        <i class="fas fa-mug-hot"></i>
                                        <span>${routine.break_name || 'বিরতি'}</span>
                                    </div>
                                 </td>`;
                    } else {
                        html += `<td class="routine-cell class-cell">
                                    <div class="routine-content">
                                        <div class="subject"><strong>${routine.subject_name || 'N/A'}</strong></div>
                                        <div class="teacher"><i class="fas fa-user-tie"></i> ${routine.teacher_name || 'N/A'}</div>
                                        <div class="room"><i class="fas fa-door-open"></i> ${routine.room_no || 'N/A'}</div>
                                    </div>
                                 </td>`;
                    }
                } else {
                    html += '<td class="routine-cell empty-cell"></td>';
                }
            });
            
            html += '</tr>';
        });
        html += '</tbody></table>';
        
        routineTable.innerHTML = html;
    }
});
</script>
@endsection

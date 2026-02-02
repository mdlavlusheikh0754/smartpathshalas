@php
    $schoolSettings = \App\Models\SchoolSetting::getSettings();
    $websiteSettings = \App\Models\WebsiteSetting::getSettings();
@endphp

@extends('tenant.layouts.web')

@section('title', 'আজকের পড়া ও আগামীকালের পড়া')

@section('styles')
<style>
    /* Smooth transitions for search results */
    .homework-card {
        transition: all 0.3s ease;
    }
    
    .homework-card.hidden {
        display: none !important;
    }
    
    /* Search input focus styles */
    #homeworkSearch:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">আজকের পড়া ও আগামীকালের পড়া</h1>
            <p class="text-xl text-blue-100">শিক্ষার্থীদের জন্য দৈনিক পড়া ও নির্দেশনা</p>
            <div class="flex justify-center items-center gap-8 mt-6">
                <div class="flex items-center gap-2 bg-white bg-opacity-20 px-4 py-2 rounded-full">
                    <i class="fas fa-home text-blue-200"></i>
                    <span class="text-sm">আজকের পড়া: বাসায় পড়বে</span>
                </div>
                <div class="flex items-center gap-2 bg-white bg-opacity-20 px-4 py-2 rounded-full">
                    <i class="fas fa-chalkboard-teacher text-green-200"></i>
                    <span class="text-sm">আগামীকালের পড়া: ক্লাসে দিবে</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Search Section -->
        <div class="mb-8">
            <div class="max-w-6xl mx-auto">
                <!-- Search Input and Filters in Same Line -->
                <div class="flex flex-col lg:flex-row gap-4 mb-4">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            id="homeworkSearch" 
                            placeholder="বিষয় বা পড়ার শিরোনাম দিয়ে খুঁজুন..." 
                            class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none transition-colors"
                        >
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Class Filter -->
                    <div class="w-full lg:w-48 relative">
                        <select id="classFilter" class="w-full pl-10 pr-8 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none appearance-none bg-white cursor-pointer transition-colors">
                            <option value="">সকল শ্রেণী</option>
                            @foreach($availableClasses as $class)
                                <option value="{{ strtolower($class) }}">{{ $class }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    </div>

                    <!-- Subject Filter -->
                    <div class="w-full lg:w-48 relative">
                        <select id="subjectFilter" class="w-full pl-10 pr-8 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none appearance-none bg-white cursor-pointer transition-colors">
                            <option value="">সকল বিষয়</option>
                            @foreach($availableSubjects as $subject)
                                <option value="{{ strtolower($subject) }}">{{ $subject }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-book absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Homework Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-blue-600 pl-3">আজকের পড়া</h2>
                <span class="text-sm text-gray-500 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                    <i class="fas fa-home mr-1 text-blue-600"></i>বাসায় পড়ার জন্য
                </span>
            </div>
            
            @if($recentHomework->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="recentHomeworkGrid">
                    @foreach($recentHomework as $hw)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 overflow-hidden homework-card group"
                             data-class="{{ strtolower($hw->class) }}"
                             data-subject="{{ strtolower($hw->subject) }}"
                             data-title="{{ strtolower($hw->title) }}">
                            <!-- Card Header with Date -->
                            <div class="bg-gray-50 px-5 py-3 border-b border-gray-100 flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 bg-white px-2 py-1 rounded border border-gray-200 shadow-sm">
                                    <i class="far fa-calendar-alt mr-1 text-blue-500"></i>
                                    {{ $hw->assigned_date->format('d M, Y') }}
                                </span>
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $hw->isOverdue() ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $hw->isOverdue() ? 'সময় শেষ' : 'চলমান' }}
                                </span>
                            </div>
                            
                            <!-- Card Body -->
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded uppercase tracking-wider">
                                        {{ $hw->class }}
                                    </span>
                                    <span class="px-2 py-1 bg-purple-50 text-purple-600 text-xs font-bold rounded uppercase tracking-wider">
                                        {{ $hw->subject }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                    {{ $hw->title }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm line-clamp-2 mb-4 h-10">
                                    {{ Str::limit($hw->description, 100) }}
                                </p>
                                
                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-calendar-check mr-1 text-gray-400"></i>
                                        জমা: {{ $hw->due_date->format('d M') }}
                                    </div>
                                    <button onclick="showHomeworkDetails({{ $hw->id }})" class="text-blue-600 hover:text-blue-700 text-sm font-semibold flex items-center gap-1 group/btn">
                                        বিস্তারিত
                                        <i class="fas fa-arrow-right transform group-hover/btn:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-blue-50 rounded-xl border-2 border-dashed border-blue-200">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-3xl text-blue-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">আজকের কোন পড়া নেই</h3>
                    <p class="text-gray-500 mt-1">আজ বাসায় পড়ার জন্য কোন নতুন পড়া দেওয়া হয়নি</p>
                </div>
            @endif
        </div>

        <!-- Upcoming Homework Section -->
        @if($upcomingHomework->count() > 0)
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-green-600 pl-3">আগামীকালের পড়া</h2>
                <span class="text-sm text-gray-500 bg-green-50 px-3 py-1 rounded-full border border-green-200">
                    <i class="fas fa-chalkboard-teacher mr-1 text-green-600"></i>কাল ক্লাসে দিতে হবে
                </span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-green-200 overflow-hidden">
                <div class="divide-y divide-green-100">
                    @foreach($upcomingHomework as $hw)
                        <div class="p-4 hover:bg-gray-50 transition-colors homework-card"
                             data-class="{{ strtolower($hw->class) }}"
                             data-subject="{{ strtolower($hw->subject) }}"
                             data-title="{{ strtolower($hw->title) }}">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 border border-green-200">
                                        <span class="text-green-600 font-bold text-sm">{{ $hw->due_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 mb-1">{{ $hw->title }}</h4>
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-layer-group text-gray-400 text-xs"></i>
                                                {{ $hw->class }}
                                            </span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-book text-gray-400 text-xs"></i>
                                                {{ $hw->subject }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between md:justify-end gap-4 w-full md:w-auto pl-16 md:pl-0">
                                    <div class="text-right hidden md:block">
                                        <div class="text-xs text-gray-500">জমা দেওয়ার তারিখ</div>
                                        <div class="text-sm font-semibold text-gray-800">{{ $hw->due_date->format('d M, Y') }}</div>
                                    </div>
                                    <button onclick="showHomeworkDetails({{ $hw->id }})" class="px-4 py-2 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100 hover:border-green-300 transition-all shadow-sm">
                                        দেখুন
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Homework Details Modal -->
    <div id="homeworkDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-green-500 text-white rounded-t-lg">
                    <h3 class="text-xl font-semibold">
                        <i class="fas fa-book-reader mr-2"></i>
                        পড়ার বিস্তারিত
                    </h3>
                    <button onclick="closeHomeworkModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div id="modalContent" class="p-6">
                    <!-- Loading State -->
                    <div id="modalLoading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">তথ্য লোড হচ্ছে...</p>
                    </div>
                    
                    <!-- Content will be loaded here -->
                    <div id="modalData" class="hidden">
                        <!-- Header Info -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex flex-wrap items-center gap-4 mb-3">
                                <span id="modalClass" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full"></span>
                                <span id="modalSubject" class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full"></span>
                                <span id="modalStatus" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                            </div>
                            <h4 id="modalTitle" class="text-xl font-bold text-gray-800 mb-2"></h4>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>
                                    <span>দেওয়া হয়েছে: <span id="modalAssignedDate" class="font-medium"></span></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                                    <span>জমা দিতে হবে: <span id="modalDueDate" class="font-medium"></span></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-align-left mr-2 text-gray-500"></i>
                                বিবরণ
                            </h5>
                            <div id="modalDescription" class="text-gray-700 leading-relaxed bg-white border border-gray-200 rounded-lg p-4"></div>
                        </div>
                        
                        <!-- Instructions -->
                        <div id="instructionsSection" class="mb-6 hidden">
                            <h5 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                                নির্দেশনা
                            </h5>
                            <div id="modalInstructions" class="text-gray-700 leading-relaxed bg-yellow-50 border border-yellow-200 rounded-lg p-4"></div>
                        </div>
                        
                        <!-- Attachment -->
                        <div id="attachmentSection" class="mb-6 hidden">
                            <h5 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-paperclip mr-2 text-gray-500"></i>
                                সংযুক্তি
                            </h5>
                            <a id="modalAttachment" href="#" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                ফাইল ডাউনলোড করুন
                            </a>
                        </div>
                        
                        <!-- Time Remaining -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-blue-600"></i>
                                <span class="text-blue-800 font-medium">সময়: <span id="modalTimeRemaining"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-4 p-6 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button onclick="closeHomeworkModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('homeworkSearch');
        const classFilter = document.getElementById('classFilter');
        const subjectFilter = document.getElementById('subjectFilter');
        const homeworkCards = document.querySelectorAll('.homework-card');
        const noResultsMsg = document.createElement('div');
        
        // Setup No Results Message
        noResultsMsg.className = 'text-center py-12 hidden w-full col-span-3';
        noResultsMsg.innerHTML = `
            <div class="inline-block p-4 rounded-full bg-gray-50 mb-3">
                <i class="fas fa-search text-gray-400 text-xl"></i>
            </div>
            <p class="text-gray-500 font-medium">কোন ফলাফল পাওয়া যায়নি</p>
            <button onclick="resetFilters()" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2">ফিল্টার রিসেট করুন</button>
        `;
        
        // Insert message after grid
        const grid = document.getElementById('recentHomeworkGrid');
        if(grid) {
            grid.appendChild(noResultsMsg);
        }

        function filterHomework() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedClass = classFilter.value.toLowerCase();
            const selectedSubject = subjectFilter.value.toLowerCase();
            let hasVisibleItems = false;

            homeworkCards.forEach(card => {
                const title = card.dataset.title || '';
                const className = card.dataset.class || '';
                const subject = card.dataset.subject || '';

                const matchesSearch = title.includes(searchTerm) || 
                                    className.includes(searchTerm) || 
                                    subject.includes(searchTerm);
                const matchesClass = selectedClass === '' || className === selectedClass;
                const matchesSubject = selectedSubject === '' || subject === selectedSubject;

                if (matchesSearch && matchesClass && matchesSubject) {
                    card.classList.remove('hidden');
                    hasVisibleItems = true;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Show/Hide No Results Message
            if (!hasVisibleItems && (searchTerm || selectedClass || selectedSubject)) {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
            }
        }

        // Event Listeners
        searchInput.addEventListener('input', filterHomework);
        classFilter.addEventListener('change', function() {
            updateSubjectFilter();
            filterHomework();
        });
        subjectFilter.addEventListener('change', filterHomework);
        
        // Update subject filter based on selected class
        function updateSubjectFilter() {
            const selectedClass = classFilter.value;
            const subjectFilter = document.getElementById('subjectFilter');
            
            if (!selectedClass) {
                // Reset to show all subjects
                subjectFilter.innerHTML = '<option value="">সকল বিষয়</option>';
                @foreach($availableSubjects as $subject)
                    subjectFilter.innerHTML += '<option value="{{ strtolower($subject) }}">{{ $subject }}</option>';
                @endforeach
                return;
            }
            
            // Show loading
            subjectFilter.innerHTML = '<option value="">লোড হচ্ছে...</option>';
            subjectFilter.disabled = true;
            
            // Fetch subjects for selected class
            fetch(`{{ route('homework.subjects-for-class') }}?class=${encodeURIComponent(selectedClass)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(subjects => {
                subjectFilter.innerHTML = '<option value="">সকল বিষয়</option>';
                
                if (subjects.length > 0) {
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.toLowerCase();
                        option.textContent = subject;
                        subjectFilter.appendChild(option);
                    });
                } else {
                    subjectFilter.innerHTML += '<option value="" disabled>এই ক্লাসের কোন বিষয় নেই</option>';
                }
                
                subjectFilter.disabled = false;
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectFilter.innerHTML = '<option value="">সকল বিষয়</option>';
                @foreach($availableSubjects as $subject)
                    subjectFilter.innerHTML += '<option value="{{ strtolower($subject) }}">{{ $subject }}</option>';
                @endforeach
                subjectFilter.disabled = false;
            });
        }
        
        // Global reset function
        window.resetFilters = function() {
            searchInput.value = '';
            classFilter.value = '';
            subjectFilter.value = '';
            filterHomework();
        }
    });

    // Homework Details Modal Functions
    function showHomeworkDetails(homeworkId) {
        const modal = document.getElementById('homeworkDetailsModal');
        const loading = document.getElementById('modalLoading');
        const data = document.getElementById('modalData');
        
        // Show modal and loading state
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loading.classList.remove('hidden');
        data.classList.add('hidden');
        
        // Fetch homework details
        fetch(`{{ url('/homework-details') }}/${homeworkId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(homework => {
            // Hide loading and show data
            loading.classList.add('hidden');
            data.classList.remove('hidden');
            
            // Populate modal with homework data
            document.getElementById('modalTitle').textContent = homework.title;
            document.getElementById('modalClass').textContent = homework.class + (homework.section ? ' - ' + homework.section : '');
            document.getElementById('modalSubject').textContent = homework.subject;
            document.getElementById('modalAssignedDate').textContent = homework.assigned_date;
            document.getElementById('modalDueDate').textContent = homework.due_date;
            document.getElementById('modalDescription').innerHTML = homework.description.replace(/\n/g, '<br>');
            document.getElementById('modalTimeRemaining').textContent = homework.days_remaining;
            
            // Set status with appropriate color
            const statusElement = document.getElementById('modalStatus');
            statusElement.textContent = homework.status;
            statusElement.className = `px-3 py-1 text-sm font-semibold rounded-full ${homework.status_color}`;
            
            // Handle instructions
            const instructionsSection = document.getElementById('instructionsSection');
            if (homework.instructions && homework.instructions.trim()) {
                document.getElementById('modalInstructions').innerHTML = homework.instructions.replace(/\n/g, '<br>');
                instructionsSection.classList.remove('hidden');
            } else {
                instructionsSection.classList.add('hidden');
            }
            
            // Handle attachment
            const attachmentSection = document.getElementById('attachmentSection');
            if (homework.attachment_url) {
                document.getElementById('modalAttachment').href = homework.attachment_url;
                attachmentSection.classList.remove('hidden');
            } else {
                attachmentSection.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading homework details:', error);
            loading.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
                    <p class="text-red-600 font-medium">তথ্য লোড করতে সমস্যা হয়েছে</p>
                    <button onclick="closeHomeworkModal()" class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        বন্ধ করুন
                    </button>
                </div>
            `;
        });
    }

    function closeHomeworkModal() {
        document.getElementById('homeworkDetailsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('homeworkDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeHomeworkModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('homeworkDetailsModal').classList.contains('hidden')) {
            closeHomeworkModal();
        }
    });
</script>
@endsection

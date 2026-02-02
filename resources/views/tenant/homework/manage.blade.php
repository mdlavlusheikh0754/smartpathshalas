@extends('layouts.tenant')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">বাড়ির কাজ ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">সকল বাড়ির কাজ দেখুন এবং পরিচালনা করুন</p>
        </div>
        <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-plus mr-2"></i>
            নতুন বাড়ির কাজ
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Homework List -->
    <div class="bg-white rounded-lg shadow">
        @if($homework->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শিরোনাম</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিষয়</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ক্লাস</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">দেওয়ার তারিখ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">জমার তারিখ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">অবস্থা</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($homework as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->subject }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->class }}{{ $item->section ? ' - ' . $item->section : '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->assigned_date?->format('d M, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->due_date?->format('d M, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($item->status == 'active') bg-green-100 text-green-800
                                        @elseif($item->status == 'overdue') bg-red-100 text-red-800
                                        @elseif($item->status == 'completed') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $item->getStatusText() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('homework.show', $item->id) }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-900" title="পাবলিক ভিউ">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tenant.homework.edit', $item->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="সম্পাদনা">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('tenant.homework.destroy', $item->id) }}" method="POST" 
                                              data-title="{{ $item->title }}"
                                              onsubmit="return showDeleteConfirmation(event, this.getAttribute('data-title'))" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="মুছে ফেলুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $homework->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-open text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">কোনো বাড়ির কাজ নেই</h3>
                <p class="text-gray-600 mb-4">এখনো কোনো বাড়ির কাজ যোগ করা হয়নি।</p>
                <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    প্রথম বাড়ির কাজ যোগ করুন
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Homework Modal -->
<div id="createHomeworkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">নতুন বাড়ির কাজ যোগ করুন</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="createHomeworkForm" action="{{ route('tenant.homework.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Loading indicator -->
                    <div id="formLoading" class="hidden">
                        <div class="flex items-center justify-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">সংরক্ষণ করা হচ্ছে...</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="modal_title" class="block text-sm font-medium text-gray-700 mb-1">শিরোনাম *</label>
                                <input type="text" id="modal_title" name="title" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="বাড়ির কাজের শিরোনাম লিখুন" required>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="modal_subject" class="block text-sm font-medium text-gray-700 mb-1">বিষয় *</label>
                                <select id="modal_subject" name="subject" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">বিষয় নির্বাচন করুন</option>
                                    @if(isset($subjects) && $subjects->count() > 0)
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->name_bn ?? $subject->name }}">{{ $subject->name_bn ?? $subject->name }}</option>
                                        @endforeach
                                    @else
                                        <!-- Fallback options if no subjects found -->
                                        <option value="বাংলা">বাংলা</option>
                                        <option value="ইংরেজি">ইংরেজি</option>
                                        <option value="গণিত">গণিত</option>
                                        <option value="বিজ্ঞান">বিজ্ঞান</option>
                                        <option value="সমাজবিজ্ঞান">সমাজবিজ্ঞান</option>
                                        <option value="ইসলাম ধর্ম">ইসলাম ধর্ম</option>
                                        <option value="হিন্দু ধর্ম">হিন্দু ধর্ম</option>
                                        <option value="খ্রিস্ট ধর্ম">খ্রিস্ট ধর্ম</option>
                                        <option value="বৌদ্ধ ধর্ম">বৌদ্ধ ধর্ম</option>
                                        <option value="শারীরিক শিক্ষা">শারীরিক শিক্ষা</option>
                                        <option value="চারু ও কারুকলা">চারু ও কারুকলা</option>
                                    @endif
                                </select>
                                <p id="subjectStatus" class="text-sm mt-1">
                                    @if(isset($subjects) && $subjects->count() > 0)
                                        <span class="text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $subjects->count() }}টি বিষয় subjects পেজ থেকে লোড করা হয়েছে
                                        </span>
                                    @else
                                        <span class="text-amber-600">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            কোনো বিষয় পাওয়া যায়নি। <a href="{{ route('tenant.subjects.index') }}" class="text-blue-600 hover:underline">বিষয় যোগ করুন</a>
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <!-- Class and Section -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="modal_class" class="block text-sm font-medium text-gray-700 mb-1">ক্লাস *</label>
                                    <select id="modal_class" name="class" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                        <option value="">ক্লাস নির্বাচন করুন</option>
                                        @php
                                            try {
                                                $classes = \App\Models\SchoolClass::active()->ordered()->get();
                                            } catch (\Exception $e) {
                                                $classes = collect();
                                            }
                                        @endphp
                                        @foreach($classes as $class)
                                            <option value="{{ $class->name }}">{{ $class->name }} শ্রেণী - {{ $class->section }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="modal_section" class="block text-sm font-medium text-gray-700 mb-1">শাখা</label>
                                    <select id="modal_section" name="section" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">শাখা নির্বাচন করুন</option>
                                        <option value="ক">ক</option>
                                        <option value="খ">খ</option>
                                        <option value="গ">গ</option>
                                        <option value="ঘ">ঘ</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="modal_assigned_date" class="block text-sm font-medium text-gray-700 mb-1">দেওয়ার তারিখ *</label>
                                    <input type="date" id="modal_assigned_date" name="assigned_date" value="{{ date('Y-m-d') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label for="modal_due_date" class="block text-sm font-medium text-gray-700 mb-1">জমার তারিখ *</label>
                                    <input type="date" id="modal_due_date" name="due_date" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>

                            <!-- Attachment -->
                            <div>
                                <label for="modal_attachment" class="block text-sm font-medium text-gray-700 mb-1">সংযুক্তি</label>
                                <input type="file" id="modal_attachment" name="attachment" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG ফাইল আপলোড করতে পারেন (সর্বোচ্চ ৫MB)</p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Description -->
                            <div>
                                <label for="modal_description" class="block text-sm font-medium text-gray-700 mb-1">বিবরণ *</label>
                                <textarea id="modal_description" name="description" rows="8" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="বাড়ির কাজের বিস্তারিত বিবরণ লিখুন" required></textarea>
                            </div>

                            <!-- Instructions -->
                            <div>
                                <label for="modal_instructions" class="block text-sm font-medium text-gray-700 mb-1">নির্দেশনা</label>
                                <textarea id="modal_instructions" name="instructions" rows="6" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="শিক্ষার্থীদের জন্য বিশেষ নির্দেশনা (ঐচ্ছিক)"></textarea>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="modal_status" class="block text-sm font-medium text-gray-700 mb-1">অবস্থা *</label>
                                <select id="modal_status" name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="active" selected>সক্রিয়</option>
                                    <option value="inactive">নিষ্ক্রিয়</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                            বাতিল
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                            বাড়ির কাজ সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createHomeworkModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Load subjects dynamically
    loadSubjects();
}

// Function to load subjects from API
function loadSubjects() {
    const subjectSelect = document.getElementById('modal_subject');
    const originalHTML = subjectSelect.innerHTML;
    
    // Show loading
    subjectSelect.innerHTML = '<option value="">বিষয় লোড হচ্ছে...</option>';
    subjectSelect.disabled = true;
    
    fetch('{{ route("tenant.homework.api.subjects") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.subjects.length > 0) {
            // Clear and rebuild options
            subjectSelect.innerHTML = '<option value="">বিষয় নির্বাচন করুন</option>';
            
            data.subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.name_bn || subject.name;
                option.textContent = subject.name_bn || subject.name;
                subjectSelect.appendChild(option);
            });
            
            // Show success message
            const statusMsg = document.getElementById('subjectStatus');
            if (statusMsg) {
                statusMsg.innerHTML = `<i class="fas fa-check-circle mr-1"></i>${data.count}টি বিষয় subjects পেজ থেকে লোড করা হয়েছে`;
                statusMsg.className = 'text-sm text-green-600 mt-1';
            }
        } else {
            // Fallback to original options
            subjectSelect.innerHTML = originalHTML;
            
            const statusMsg = document.getElementById('subjectStatus');
            if (statusMsg) {
                statusMsg.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>কোনো বিষয় পাওয়া যায়নি। <a href="{{ route("tenant.subjects.index") }}" class="text-blue-600 hover:underline">বিষয় যোগ করুন</a>';
                statusMsg.className = 'text-sm text-amber-600 mt-1';
            }
        }
    })
    .catch(error => {
        console.error('Error loading subjects:', error);
        // Restore original options on error
        subjectSelect.innerHTML = originalHTML;
        
        const statusMsg = document.getElementById('subjectStatus');
        if (statusMsg) {
            statusMsg.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>বিষয় লোড করতে সমস্যা হয়েছে। ডিফল্ট বিষয়গুলো দেখানো হচ্ছে।';
            statusMsg.className = 'text-sm text-red-600 mt-1';
        }
    })
    .finally(() => {
        subjectSelect.disabled = false;
    });
}

function closeCreateModal() {
    document.getElementById('createHomeworkModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('createHomeworkForm').reset();
    document.getElementById('modal_assigned_date').value = "{{ date('Y-m-d') }}";
    
    // Hide loading indicator
    document.getElementById('formLoading').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('createHomeworkModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
    }
});

// Set minimum due date to assigned date
document.addEventListener('DOMContentLoaded', function() {
    const assignedDate = document.getElementById('modal_assigned_date');
    const dueDate = document.getElementById('modal_due_date');
    
    assignedDate.addEventListener('change', function() {
        dueDate.min = this.value;
        if (dueDate.value && dueDate.value < this.value) {
            dueDate.value = this.value;
        }
    });
    
    if (assignedDate.value) {
        dueDate.min = assignedDate.value;
    }
});

// Handle form submission
document.getElementById('createHomeworkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading indicator
    document.getElementById('formLoading').classList.remove('hidden');
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit form via fetch
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (response.ok) {
            // Success - reload page to show new homework
            window.location.reload();
        } else {
            throw new Error('Form submission failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('বাড়ির কাজ সংরক্ষণে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        document.getElementById('formLoading').classList.add('hidden');
    });
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">নিশ্চিতকরণ</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">বাড়ির কাজ মুছে ফেলুন</h4>
                <p class="text-gray-600 mb-4">আপনি কি নিশ্চিত যে এই বাড়ির কাজটি মুছে ফেলতে চান?</p>
                <p class="text-sm text-gray-500 mb-6 font-medium" id="homeworkTitle"></p>
                
                <div class="flex items-center justify-center gap-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                        বাতিল
                    </button>
                    <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-medium">
                        মুছে ফেলুন
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deleteForm = null;

function showDeleteConfirmation(event, homeworkTitle) {
    event.preventDefault();
    deleteForm = event.target;
    
    // Set homework title in modal
    document.getElementById('homeworkTitle').textContent = '"' + homeworkTitle + '"';
    
    // Show modal
    document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    return false;
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    deleteForm = null;
}

function confirmDelete() {
    if (deleteForm) {
        deleteForm.submit();
    }
}

// Close modal when clicking outside
document.getElementById('deleteConfirmationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteConfirmationModal').classList.contains('hidden')) {
        closeDeleteModal();
    }
});
</script>
@endsection

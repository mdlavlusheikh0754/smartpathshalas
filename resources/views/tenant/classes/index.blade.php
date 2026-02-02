@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ক্লাস ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">সকল ক্লাস এবং সেকশন পরিচালনা করুন</p>
        </div>
        <div class="flex gap-3">
            <button id="bulkDeleteBtn" onclick="bulkDeleteClasses()" class="hidden bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                নির্বাচিত মুছে ফেলুন (<span id="selectedCount">0</span>)
            </button>
            <button onclick="openAddModal()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                নতুন ক্লাস যোগ করুন
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                <span class="text-sm font-medium text-gray-700">সব নির্বাচন করুন</span>
            </label>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-3 rounded-xl">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">ক্লাস সমূহ</h2>
        </div>
    </div>

    <div id="classList" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($classes as $class)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 relative" data-class-id="{{ $class->id }}">
                <input type="checkbox" class="class-checkbox absolute top-4 left-4 w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500" value="{{ $class->id }}" onchange="updateSelectedCount()">
                <div class="flex items-center justify-between mb-4 pl-8">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-bold">{{ $class->section }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $class->name }} শ্রেণী</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <p>শিক্ষার্থী: <span class="font-bold text-gray-900">{{ $class->students }}</span></p>
                    <p>শিক্ষক: <span class="font-bold text-gray-900">{{ $class->teachers }}</span></p>
                </div>
                @if($class->description)
                    <p class="text-sm text-gray-500 mt-2">{{ Str::limit($class->description, 50) }}</p>
                @endif
                <div class="mt-4 flex gap-2">
                    <button onclick="editClass({{ $class->id }})" class="flex-1 bg-purple-100 hover:bg-purple-200 text-purple-600 py-2 rounded-lg text-center font-medium transition-colors">সম্পাদনা</button>
                    <button onclick="deleteClass({{ $class->id }})" class="flex-1 bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg text-center font-medium transition-colors">মুছে ফেলুন</button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-900 mb-2">কোনো ক্লাস পাওয়া যায়নি</p>
                <p class="text-gray-500">নতুন ক্লাস যোগ করতে উপরের বাটনে ক্লিক করুন</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="classModal" class="modal">
    <div class="modal-content">
        <div class="flex items-center justify-between mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">নতুন ক্লাস যোগ করুন</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="classForm" onsubmit="saveClass(event)">
            <input type="hidden" id="classId" name="id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ক্লাসের নাম *</label>
                    <input type="text" id="className" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="যেমন: প্রথম, দ্বিতীয়, তৃতীয়">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">সেকশন *</label>
                    <select id="classSection" name="section" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">নির্বাচন করুন</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষার্থী সংখ্যা</label>
                    <input type="number" id="classStudents" name="students" min="0" value="0" data-no-bangla class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষক সংখ্যা</label>
                    <input type="number" id="classTeachers" name="teachers" min="0" value="0" data-no-bangla class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিবরণ</label>
                    <textarea id="classDescription" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="ক্লাস সম্পর্কে অতিরিক্ত তথ্য (ঐচ্ছিক)"></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-colors">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে এই ক্লাসটি মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button onclick="confirmDelete()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Message Modal -->
<div id="successModal" class="modal">
    <div class="modal-content max-w-md mx-auto">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">সফল!</h3>
            <p id="successMessage" class="text-gray-600 mb-6 text-center"></p>
            <button onclick="closeSuccessModal()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                ঠিক আছে
            </button>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" class="modal">
    <div class="modal-content max-w-md mx-auto">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-gray-600 mb-6"><span id="bulkDeleteCount">0</span>টি ক্লাস মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
            <div class="flex gap-3">
                <button onclick="closeBulkDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button onclick="confirmBulkDelete()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modalSlideIn 0.3s ease-out;
    transform: translate(-50%, -50%);
    position: absolute;
    top: 50%;
    left: 50%;
}

@keyframes modalSlideIn {
    from {
        transform: translate(-50%, -60%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, -50%);
        opacity: 1;
    }
}
</style>

<script>
// Global variables
let csrfToken = null;
let deleteId = null;

// Bulk delete functions (defined globally for onchange attributes)
window.toggleSelectAll = function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.class-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateSelectedCount();
};

window.updateSelectedCount = function() {
    const checkboxes = document.querySelectorAll('.class-checkbox');
    const checkedBoxes = document.querySelectorAll('.class-checkbox:checked');
    const count = checkedBoxes.length;
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');
    
    if (selectedCount) selectedCount.textContent = count;
    
    if (bulkDeleteBtn) {
        if (count > 0) {
            bulkDeleteBtn.classList.remove('hidden');
            if (count === checkboxes.length) {
                selectAll.checked = true;
            } else {
                selectAll.checked = false;
            }
        } else {
            bulkDeleteBtn.classList.add('hidden');
            selectAll.checked = false;
        }
    }
};

window.bulkDeleteClasses = function() {
    const checkboxes = document.querySelectorAll('.class-checkbox:checked');
    const count = checkboxes.length;
    
    if (count === 0) {
        showErrorMessage('অন্তত কমপক্ষ একটি ক্লাস নির্বাচন করুন');
        return;
    }
    
    document.getElementById('bulkDeleteCount').textContent = count;
    document.getElementById('bulkDeleteModal').classList.add('active');
};

window.confirmBulkDelete = function() {
    const checkboxes = document.querySelectorAll('.class-checkbox:checked');
    const classIds = Array.from(checkboxes).map(cb => cb.value);
    
    console.log('Starting bulk delete process');
    console.log('Selected class IDs:', classIds);
    console.log('Current URL:', window.location.href);
    console.log('Target URL:', '{{ route("tenant.classes.bulk-delete") }}');
    
    if (classIds.length === 0) {
        showErrorMessage('কোনো ক্লাস নির্বাচিত করা হয়নি');
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', csrfToken);
    
    // Append each class ID as an array element
    classIds.forEach(id => {
        formData.append('class_ids[]', id);
    });
    
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    const bulkDeleteUrl = window.location.pathname.endsWith('/') 
        ? window.location.pathname + 'bulk-delete' 
        : window.location.pathname + '/bulk-delete';
    
    console.log('Bulk delete URL:', bulkDeleteUrl);
    
    fetch(bulkDeleteUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response received:', response);
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.log('Error response text:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Success response data:', data);
        if (data.success) {
            closeBulkDeleteModal();
            showSuccessMessage(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showErrorMessage(data.message || 'বাল্ক মুছে ফেলতে সমস্যা হয়েছে');
        }
    })
    .catch(error => {
        console.error('Bulk delete error:', error);
        showErrorMessage('বাল্ক মুছে ফেলতে সমস্যা হয়েছে: ' + error.message);
    });
};

window.closeBulkDeleteModal = function() {
    document.getElementById('bulkDeleteModal').classList.remove('active');
};

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing classes page');
    
    // CSRF token for AJAX requests
    csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found');
        // Try to get it from a form if meta tag is not available
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) {
            csrfToken = tokenInput.value;
            console.log('CSRF token found in form input');
        }
    } else {
        console.log('CSRF token found in meta tag');
    }

    let deleteId = null;

    // Edit class
    window.editClass = function(id) {
        console.log('Editing class:', id);
        // Fetch class data from server
        fetch(`{{ url('/classes') }}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Failed to fetch class data');
            }
        })
        .then(classData => {
            console.log('Class data received:', classData);
            document.getElementById('modalTitle').textContent = 'ক্লাস সম্পাদনা করুন';
            document.getElementById('classId').value = classData.id;
            document.getElementById('className').value = classData.name;
            document.getElementById('classSection').value = classData.section;
            document.getElementById('classStudents').value = classData.students;
            document.getElementById('classTeachers').value = classData.teachers;
            document.getElementById('classDescription').value = classData.description || '';
            document.getElementById('classModal').classList.add('active');
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('ক্লাসের তথ্য লোড করতে সমস্যা হয়েছে');
        });
    };

    // Open add modal
    window.openAddModal = function() {
        console.log('Opening add modal');
        document.getElementById('modalTitle').textContent = 'নতুন ক্লাস যোগ করুন';
        document.getElementById('classForm').reset();
        document.getElementById('classId').value = '';
        document.getElementById('classModal').classList.add('active');
    };

    // Save class
    window.saveClass = function(event) {
        event.preventDefault();
        console.log('Saving class');
        
        const id = document.getElementById('classId').value;
        const formData = new FormData();
        
        formData.append('name', document.getElementById('className').value);
        formData.append('section', document.getElementById('classSection').value);
        formData.append('students', document.getElementById('classStudents').value || '0');
        formData.append('teachers', document.getElementById('classTeachers').value || '0');
        formData.append('description', document.getElementById('classDescription').value || '');
        formData.append('_token', csrfToken);
        
        const url = id ? `{{ url('/classes') }}/${id}` : '{{ route("tenant.classes.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        if (id) {
            formData.append('_method', 'PUT');
        }
        
        console.log('Sending request to:', url, 'Method:', method);
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(data => {
                    throw new Error(JSON.stringify(data));
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                closeModal();
                showSuccessMessage(data.message);
                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showErrorMessage(data.message || 'একটি ত্রুটি ঘটেছে');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            try {
                const errorData = JSON.parse(error.message);
                if (errorData.errors) {
                    // Handle validation errors
                    let errorMessage = 'ভ্যালিডেশন এরর:\n';
                    for (const field in errorData.errors) {
                        errorMessage += errorData.errors[field].join(', ') + '\n';
                    }
                    showErrorMessage(errorMessage);
                } else {
                    showErrorMessage(errorData.message || 'সংরক্ষণ করতে সমস্যা হয়েছে');
                }
            } catch (e) {
                showErrorMessage('সংরক্ষণ করতে সমস্যা হয়েছে');
            }
        });
    };

    // Delete class
    window.deleteClass = function(id) {
        console.log('Deleting class:', id);
        deleteId = id;
        document.getElementById('deleteModal').classList.add('active');
    };

    window.confirmDelete = function() {
        console.log('Confirming delete for:', deleteId);
        if (deleteId) {
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('_method', 'DELETE');
            
            fetch(`{{ url('/classes') }}/${deleteId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Delete response:', data);
                if (data.success) {
                    closeDeleteModal();
                    showSuccessMessage(data.message);
                    // Reload page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorMessage(data.message || 'মুছে ফেলতে সমস্যা হয়েছে');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('মুছে ফেলতে সমস্যা হয়েছে');
            });
        }
    };

    // Close modals
    window.closeModal = function() {
        document.getElementById('classModal').classList.remove('active');
    };

    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').classList.remove('active');
        deleteId = null;
    };

    window.closeSuccessModal = function() {
        document.getElementById('successModal').classList.remove('active');
    };

    // Show success message
    window.showSuccessMessage = function(message) {
        console.log('Showing success message:', message);
        
        // First try to find existing modal
        let successModalElement = document.getElementById('successModal');
        let successMessageElement = document.getElementById('successMessage');
        
        console.log('Initial search - Success modal elements:', {
            successMessage: !!successMessageElement,
            successModal: !!successModalElement
        });
        
        // If elements don't exist, create them dynamically
        if (!successModalElement || !successMessageElement) {
            console.log('Creating success modal dynamically');
            
            // Remove any existing success modal first
            const existingModal = document.getElementById('successModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Create new success modal
            const modalHTML = `
                <div id="successModal" class="modal active">
                    <div class="modal-content max-w-md mx-auto">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">সফল!</h3>
                            <p id="successMessage" class="text-gray-600 mb-6 text-center">${message}</p>
                            <button onclick="closeSuccessModal()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                                ঠিক আছে
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Auto close after 3 seconds
            setTimeout(function() {
                const modal = document.getElementById('successModal');
                if (modal) {
                    modal.remove();
                }
            }, 3000);
            
        } else {
            // Use existing modal
            console.log('Using existing success modal');
            successMessageElement.textContent = message;
            successModalElement.classList.add('active');
            
            // Auto close after 3 seconds
            setTimeout(function() {
                closeSuccessModal();
            }, 3000);
        }
    };

    // Show error message
    window.showErrorMessage = function(message) {
        console.log('Showing error message:', message);
        // Create error modal dynamically
        const errorModal = document.createElement('div');
        errorModal.className = 'modal active';
        errorModal.innerHTML = `
            <div class="modal-content max-w-md">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">ত্রুটি!</h3>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <button onclick="this.closest('.modal').remove()" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors">
                        ঠিক আছে
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(errorModal);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (errorModal.parentNode) {
                errorModal.remove();
            }
        }, 5000);
    };

    // Close modal when clicking outside
    document.getElementById('classModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) closeSuccessModal();
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeDeleteModal();
            closeSuccessModal();
            closeBulkDeleteModal();
        }
    });

    console.log('Classes page initialized successfully');
});
</script>
@endsection

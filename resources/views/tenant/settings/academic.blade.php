@extends('layouts.tenant')

@section('content')
<style>
    #sessionModal {
        backdrop-filter: blur(4px);
    }
    
    .session-card {
        transition: all 0.3s ease;
    }
    
    .session-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    input:focus, textarea:focus, select:focus {
        ring: 2px;
        ring-color: rgb(59 130 246);
        border-color: rgb(59 130 246);
    }
</style>

<div class="p-8">
    <div class="w-full">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">একাডেমিক সেশন ম্যানেজমেন্ট</h1>
                <p class="text-gray-600 mt-1">শিক্ষাবর্ষ এবং সেশন পরিচালনা করুন</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2 px-4 py-2 border border-blue-300 rounded-lg hover:bg-blue-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ফিরে যান
                </a>
                <button type="button" onclick="addNewSession()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2 rounded-xl font-bold hover:shadow-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    নতুন সেশন যোগ করুন
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">একাডেমিক সেশন সমূহ</h2>
                </div>
            </div>

            <div id="sessionsList" class="space-y-6 mb-6">
                @php
                    try {
                        $sessions = \App\Models\AcademicSession::getActiveSessions();
                    } catch (\Exception $e) {
                        $sessions = collect();
                    }
                @endphp
                
                @forelse($sessions as $session)
                <div class="session-card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6" data-session-id="{{ $session->id }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $session->session_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $session->getFormattedDuration() }}</p>
                            </div>
                            @if($session->is_current)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">বর্তমান সেশন</span>
                            @endif
                            @if($session->isOngoing())
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">চলমান</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="editSession({{ $session->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            @if(!$session->is_current)
                            <button type="button" onclick="setCurrentSession({{ $session->id }})" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-100 transition" title="বর্তমান সেশন হিসেবে সেট করুন">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            @endif
                            <button type="button" onclick="deleteSession({{ $session->id }})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-600">শিক্ষার্থী</p>
                            <p class="text-lg font-bold text-blue-600">{{ $session->total_students }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-600">শিক্ষক</p>
                            <p class="text-lg font-bold text-green-600">{{ $session->total_teachers }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-600">কর্মচারী</p>
                            <p class="text-lg font-bold text-purple-600">{{ $session->total_staff }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-600">শ্রেণীকক্ষ</p>
                            <p class="text-lg font-bold text-orange-600">{{ $session->total_classrooms }}</p>
                        </div>
                    </div>
                    
                    @if($session->description)
                    <div class="mt-4 p-3 bg-white rounded-lg">
                        <p class="text-sm text-gray-700">{{ $session->description }}</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">কোন একাডেমিক সেশন নেই</h3>
                    <p class="text-gray-600 mb-4">প্রথম একাডেমিক সেশন যোগ করুন</p>
                    <button type="button" onclick="addNewSession()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:shadow-lg transition">
                        নতুন সেশন যোগ করুন
                    </button>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function addNewSession() {
    showSessionModal();
}

function editSession(sessionId) {
    fetch(`/settings/school/sessions/${sessionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSessionModal(data.session);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('সেশন লোড করতে সমস্যা হয়েছে।');
        });
}

function deleteSession(sessionId) {
    showDeleteConfirmation('একাডেমিক সেশন', 'আপনি কি নিশ্চিত যে এই সেশনটি মুছে ফেলতে চান?', function() {
        fetch(`/settings/school/sessions/${sessionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'সেশন মুছতে সমস্যা হয়েছে।');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('সেশন মুছতে সমস্যা হয়েছে।');
        });
    });
}

function setCurrentSession(sessionId) {
    showDeleteConfirmation('বর্তমান সেশন সেট করুন', 'এই সেশনটিকে বর্তমান সেশন হিসেবে সেট করতে চান?', function() {
        fetch(`/settings/school/sessions/${sessionId}/set-current`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'বর্তমান সেশন সেট করতে সমস্যা হয়েছে।');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('বর্তমান সেশন সেট করতে সমস্যা হয়েছে।');
        });
    });
}

function showSessionModal(session = null) {
    const isEdit = session !== null;
    const modalTitle = isEdit ? 'সেশন সম্পাদনা করুন' : 'নতুন সেশন যোগ করুন';
    const submitText = isEdit ? 'আপডেট করুন' : 'সংরক্ষণ করুন';
    
    const modalHtml = `
        <div id="sessionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">${modalTitle}</h3>
                    <button type="button" onclick="closeSessionModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="sessionForm" onsubmit="submitSession(event)">
                    <input type="hidden" id="sessionId" value="${isEdit ? session.id : ''}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">সেশনের নাম *</label>
                            <input type="text" id="sessionName" value="${isEdit ? session.session_name : ''}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="২০২৬ বা ২০২৫-২০২৬">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">শুরুর তারিখ *</label>
                            <input type="date" id="startDate" value="${isEdit ? session.start_date : ''}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">শেষের তারিখ *</label>
                            <input type="date" id="endDate" value="${isEdit ? session.end_date : ''}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোট শিক্ষার্থী</label>
                            <input type="number" id="totalStudents" value="${isEdit ? session.total_students : '0'}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোট শিক্ষক</label>
                            <input type="number" id="totalTeachers" value="${isEdit ? session.total_teachers : '0'}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোট কর্মচারী</label>
                            <input type="number" id="totalStaff" value="${isEdit ? session.total_staff : '0'}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">মোট শ্রেণীকক্ষ</label>
                            <input type="number" id="totalClassrooms" value="${isEdit ? session.total_classrooms : '0'}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">বিবরণ</label>
                            <textarea id="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl" placeholder="সেশন সম্পর্কে অতিরিক্ত তথ্য...">${isEdit ? (session.description || '') : ''}</textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" id="isCurrent" ${isEdit && session.is_current ? 'checked' : ''} class="rounded">
                                <span class="text-sm font-medium text-gray-700">এটি বর্তমান সেশন</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="button" onclick="closeSessionModal()" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                            বাতিল
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">
                            ${submitText}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function closeSessionModal() {
    const modal = document.getElementById('sessionModal');
    if (modal) {
        modal.remove();
    }
}

function submitSession(event) {
    event.preventDefault();
    
    const sessionId = document.getElementById('sessionId').value;
    const isEdit = sessionId !== '';
    
    const formData = {
        session_name: document.getElementById('sessionName').value,
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        total_students: parseInt(document.getElementById('totalStudents').value) || 0,
        total_teachers: parseInt(document.getElementById('totalTeachers').value) || 0,
        total_staff: parseInt(document.getElementById('totalStaff').value) || 0,
        total_classrooms: parseInt(document.getElementById('totalClassrooms').value) || 0,
        description: document.getElementById('description').value,
        is_current: document.getElementById('isCurrent').checked
    };
    
    const url = isEdit ? `/settings/school/sessions/${sessionId}` : '/settings/school/sessions';
    const method = isEdit ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeSessionModal();
            location.reload();
        } else {
            alert(data.message || 'সেশন সংরক্ষণ করতে সমস্যা হয়েছে।');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('সেশন সংরক্ষণ করতে সমস্যা হয়েছে।');
    });
}

let deleteCallback = null;

function showDeleteConfirmation(title, message, callback) {
    document.getElementById('deleteTitle').textContent = title + ' মুছে ফেলুন';
    document.getElementById('deleteMessage').textContent = message;
    deleteCallback = callback;
    
    document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    deleteCallback = null;
}

function confirmDelete() {
    if (deleteCallback) {
        deleteCallback();
    }
    closeDeleteModal();
}

const deleteModal = document.getElementById('deleteConfirmationModal');
if (deleteModal) {
    deleteModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('deleteConfirmationModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    }
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">নিশ্চিতকরণ</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2" id="deleteTitle">আইটেম মুছে ফেলুন</h4>
                <p class="text-gray-600 mb-6" id="deleteMessage">আপনি কি নিশ্চিত যে এটি মুছে ফেলতে চান?</p>
                
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
@endsection


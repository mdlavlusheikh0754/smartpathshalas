@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">পরীক্ষা ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">পরীক্ষা সময়সূচী এবং ফলাফল পরিচালনা করুন</p>
        </div>
        <button onclick="openAddModal()" class="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            নতুন পরীক্ষা যোগ করুন
        </button>
    </div>

    <div id="examList" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Exams will be rendered here by JavaScript -->
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="examModal" class="modal">
    <div class="modal-content">
        <div class="flex items-center justify-between mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">নতুন পরীক্ষা যোগ করুন</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="examForm" onsubmit="saveExam(event)">
            <input type="hidden" id="examId" name="id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষার নাম *</label>
                    <input type="text" id="examName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="যেমন: প্রথম সাময়িক">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শুরুর তারিখ *</label>
                        <input type="date" id="examStartDate" name="start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শেষ তারিখ *</label>
                        <input type="date" id="examEndDate" name="end_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">মোট বিষয়</label>
                    <input type="number" id="examSubjects" name="subjects" min="0" value="0" data-no-bangla class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">অংশগ্রহণকারী</label>
                    <input type="number" id="examParticipants" name="participants" min="0" value="0" data-no-bangla class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস *</label>
                    <select id="examStatus" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="আসন্ন">আসন্ন</option>
                        <option value="চলমান">চলমান</option>
                        <option value="সম্পন্ন">সম্পন্ন</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                    বাতিল করুন
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white py-3 rounded-lg font-medium transition-colors">
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
            <p class="text-gray-600 mb-6">আপনি কি নিশ্চিত যে এই পরীক্ষাটি মুছে ফেলতে চান?<br><span class="text-red-600 font-medium">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</span></p>
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
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">সফল!</h3>
            <p id="successMessage" class="text-gray-600 mb-6"></p>
            <button onclick="closeSuccessModal()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                ঠিক আছে
            </button>
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
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
// Exam data - will be populated from database
let exams = [];

let deleteId = null;

// Format date to Bengali
function formatDateBengali(dateStr) {
    const months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    const date = new Date(dateStr);
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

// Get status color
function getStatusColor(status) {
    const colors = {
        'আসন্ন': 'bg-blue-100 text-blue-600',
        'চলমান': 'bg-green-100 text-green-600',
        'সম্পন্ন': 'bg-gray-100 text-gray-600'
    };
    return colors[status] || 'bg-gray-100 text-gray-600';
}

// Render exams
function renderExams() {
    const container = document.getElementById('examList');
    
    if (exams.length === 0) {
        container.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500"><p>কোনো পরীক্ষা পাওয়া যায়নি।</p><p class="mt-2">নতুন পরীক্ষা যোগ করতে "নতুন পরীক্ষা যোগ করুন" বাটনে ক্লিক করুন।</p></div>';
        return;
    }
    
    container.innerHTML = exams.map(exam => `
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">${exam.name}</h3>
                    <p class="text-sm text-gray-600 mt-1">${formatDateBengali(exam.start_date)} - ${formatDateBengali(exam.end_date)}</p>
                </div>
                <span class="${getStatusColor(exam.status)} px-3 py-1 rounded-full text-xs font-bold">${exam.status}</span>
            </div>
            <div class="space-y-2 text-sm text-gray-600 mb-4">
                <p>মোট বিষয়: <span class="font-bold text-gray-900">${exam.subjects}</span></p>
                <p>অংশগ্রহণকারী: <span class="font-bold text-gray-900">${exam.participants.toLocaleString('bn-BD')} জন</span></p>
            </div>
            <div class="flex gap-2">
                <button onclick="editExam(${exam.id})" class="flex-1 bg-orange-100 hover:bg-orange-200 text-orange-600 py-2 rounded-lg text-center font-medium transition-colors">সম্পাদনা</button>
                <button onclick="deleteExam(${exam.id})" class="flex-1 bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg text-center font-medium transition-colors">মুছে ফেলুন</button>
            </div>
        </div>
    `).join('');
}

// Open add modal
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'নতুন পরীক্ষা যোগ করুন';
    document.getElementById('examForm').reset();
    document.getElementById('examId').value = '';
    document.getElementById('examModal').classList.add('active');
}

// Edit exam
function editExam(id) {
    const exam = exams.find(e => e.id === id);
    if (!exam) return;
    
    document.getElementById('modalTitle').textContent = 'পরীক্ষা সম্পাদনা করুন';
    document.getElementById('examId').value = exam.id;
    document.getElementById('examName').value = exam.name;
    document.getElementById('examStartDate').value = exam.start_date;
    document.getElementById('examEndDate').value = exam.end_date;
    document.getElementById('examSubjects').value = exam.subjects;
    document.getElementById('examParticipants').value = exam.participants;
    document.getElementById('examStatus').value = exam.status;
    document.getElementById('examModal').classList.add('active');
}

// Save exam
function saveExam(event) {
    event.preventDefault();
    
    const id = document.getElementById('examId').value;
    const data = {
        name: document.getElementById('examName').value,
        start_date: document.getElementById('examStartDate').value,
        end_date: document.getElementById('examEndDate').value,
        subjects: parseInt(document.getElementById('examSubjects').value) || 0,
        participants: parseInt(document.getElementById('examParticipants').value) || 0,
        status: document.getElementById('examStatus').value
    };
    
    if (id) {
        // Update existing
        const index = exams.findIndex(e => e.id === parseInt(id));
        if (index !== -1) {
            exams[index] = {...exams[index], ...data};
        }
    } else {
        // Add new
        const newId = Math.max(...exams.map(e => e.id), 0) + 1;
        exams.push({id: newId, ...data});
    }
    
    renderExams();
    closeModal();
    showSuccessMessage(id ? 'পরীক্ষা সফলভাবে আপডেট করা হয়েছে' : 'পরীক্ষা সফলভাবে যোগ করা হয়েছে');
}

// Delete exam
function deleteExam(id) {
    deleteId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function confirmDelete() {
    if (deleteId) {
        exams = exams.filter(e => e.id !== deleteId);
        renderExams();
        closeDeleteModal();
        showSuccessMessage('পরীক্ষা সফলভাবে মুছে ফেলা হয়েছে');
    }
}

// Close modals
function closeModal() {
    document.getElementById('examModal').classList.remove('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteId = null;
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.remove('active');
}

// Show success message
function showSuccessMessage(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').classList.add('active');
    
    // Auto close after 3 seconds
    setTimeout(function() {
        closeSuccessModal();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('examModal').addEventListener('click', function(e) {
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
    }
});

// Initial render
renderExams();
</script>
@endsection

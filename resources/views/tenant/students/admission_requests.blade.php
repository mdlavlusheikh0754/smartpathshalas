@extends('layouts.tenant')

@section('content')
@php
// Helper function to convert English numbers to Bengali
function convertToBengaliNumbers($text) {
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($englishNumbers, $bengaliNumbers, $text);
}
@endphp
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ভর্তির আবেদন</h1>
            <p class="text-gray-600 mt-1">অনলাইন থেকে আসা ভর্তির আবেদনসমূহ পরিচালনা করুন</p>
        </div>
        <a href="{{ route('tenant.students.index') }}" class="bg-white border border-gray-200 text-gray-600 hover:text-gray-800 hover:border-gray-300 px-6 py-3 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            শিক্ষার্থী তালিকায় ফিরে যান
        </a>
    </div>

    <!-- Admissions Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">ছবি</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">আবেদনকারীর নাম</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">শ্রেণী</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">পিতা ও মোবাইল</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">আবেদনের তারিখ</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if(isset($admissionApplications) && $admissionApplications->count() > 0)
                        @foreach($admissionApplications as $application)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                @if($application->photo)
                                    <img src="{{ tenant_asset($application->photo) }}" alt="Photo" class="w-12 h-12 rounded-full object-cover border-2 border-purple-100">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xl">
                                        {{ mb_substr($application->name_bn, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $application->name_bn }}</p>
                                    <p class="text-xs text-gray-500">{{ $application->name_en }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $application->class }}
                                @if($application->section)
                                    <span class="text-xs text-gray-500 block">সেকশন: {{ $application->section }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $application->father_name }}</p>
                                    <p class="text-xs text-gray-500">{{ convertToBengaliNumbers($application->father_mobile) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ convertToBengaliNumbers($application->created_at->format('d/m/Y')) }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openModal(@json($application))' class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors" title="বিস্তারিত দেখুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('tenant.students.approve-admission', $application->id) }}" method="POST" onsubmit="openConfirmationModal(event)">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm hover:shadow-md" title="অনুমোদন করুন">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">কোনো নতুন ভর্তির আবেদন নেই</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
</div>

<!-- Admission Details Modal -->
<div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    আবেদনের বিস্তারিত তথ্য
                </h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none bg-white/20 hover:bg-white/30 rounded-full p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left Column: Photo & Basic Info -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                            <div class="relative w-40 h-40 mx-auto mb-4">
                                <img id="modal_photo" src="" alt="Student Photo" class="w-full h-full object-cover rounded-full border-4 border-white shadow-lg">
                                <div id="modal_photo_placeholder" class="hidden w-full h-full rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-4xl border-4 border-white shadow-lg"></div>
                            </div>
                            <h2 id="modal_name_bn" class="text-xl font-bold text-gray-900 mb-1"></h2>
                            <p id="modal_name_en" class="text-sm text-gray-500 font-medium uppercase tracking-wide mb-4"></p>
                            
                            <div class="space-y-3 text-left">
                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                                    <span class="text-xs text-gray-500 block mb-1">শ্রেণী</span>
                                    <span id="modal_class" class="font-semibold text-gray-800"></span>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                                    <span class="text-xs text-gray-500 block mb-1">আবেদন আইডি</span>
                                    <span id="modal_application_id" class="font-semibold text-gray-800 font-mono"></span>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                                    <span class="text-xs text-gray-500 block mb-1">আবেদনের তারিখ</span>
                                    <span id="modal_date" class="font-semibold text-gray-800"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Details -->
                    <div class="md:col-span-2">
                        <div class="space-y-6">
                            <!-- Personal Info -->
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2 border-b pb-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </span>
                                    ব্যক্তিগত তথ্য
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="group">
                                        <label class="text-xs text-gray-500">জন্ম তারিখ</label>
                                        <p id="modal_dob" class="font-medium text-gray-900"></p>
                                    </div>
                                    <div class="group">
                                        <label class="text-xs text-gray-500">লিঙ্গ</label>
                                        <p id="modal_gender" class="font-medium text-gray-900 capitalize"></p>
                                    </div>
                                    <div class="group">
                                        <label class="text-xs text-gray-500">ধর্ম</label>
                                        <p id="modal_religion" class="font-medium text-gray-900 capitalize"></p>
                                    </div>
                                    <div class="group">
                                        <label class="text-xs text-gray-500">মোবাইল নম্বর</label>
                                        <p id="modal_phone" class="font-medium text-gray-900"></p>
                                    </div>
                                    <div class="group col-span-2">
                                        <label class="text-xs text-gray-500">ঠিকানা</label>
                                        <p id="modal_address" class="font-medium text-gray-900"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Parents Info -->
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2 border-b pb-2">
                                    <span class="bg-purple-100 text-purple-600 p-1.5 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </span>
                                    অভিভাবকের তথ্য
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="text-xs text-gray-500 block mb-1">পিতার নাম</label>
                                        <p id="modal_father_name" class="font-medium text-gray-900"></p>
                                        <p id="modal_father_mobile" class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            <span id="modal_father_mobile_text"></span>
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="text-xs text-gray-500 block mb-1">মাতার নাম</label>
                                        <p id="modal_mother_name" class="font-medium text-gray-900"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Documents Link -->
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2 border-b pb-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </span>
                                    সংযুক্ত ডকুমেন্ট
                                </h4>
                                <div class="grid grid-cols-2 gap-3" id="modal_documents">
                                    <!-- Documents will be populated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white text-gray-700 text-base font-medium rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    বন্ধ করুন
                </button>
                <form id="modal_approve_form" method="POST" onsubmit="openConfirmationModal(event)">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        অনুমোদন করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeConfirmationModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            নিশ্চিতকরণ
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 font-medium">
                                আপনি কি নিশ্চিত যে আপনি এই আবেদনটি অনুমোদন করতে চান? <br>এটি একটি নতুন শিক্ষার্থী তৈরি করবে।
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="submitApprovalForm()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm transition-colors">
                    হ্যাঁ, অনুমোদন করুন
                </button>
                <button type="button" onclick="closeConfirmationModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                    না, বাতিল করুন
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let formToSubmit = null;

    function openConfirmationModal(event) {
        event.preventDefault();
        formToSubmit = event.target;
        const modal = document.getElementById('confirmationModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.transform').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            modal.querySelector('.transform').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        }, 10);
    }

    function closeConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        modal.querySelector('.transform').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        modal.querySelector('.transform').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        formToSubmit = null;
    }

    function submitApprovalForm() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    }

    function openModal(data) {
        // Basic Info
        document.getElementById('modal_name_bn').textContent = data.name_bn || 'N/A';
        document.getElementById('modal_name_en').textContent = data.name_en || 'N/A';
        document.getElementById('modal_class').textContent = data.class + (data.section ? ' (' + data.section + ')' : '');
        document.getElementById('modal_application_id').textContent = data.application_id || '#' + data.id;
        document.getElementById('modal_date').textContent = new Date(data.created_at).toLocaleDateString('bn-BD');

        // Photo
        const photoImg = document.getElementById('modal_photo');
        const photoPlaceholder = document.getElementById('modal_photo_placeholder');
        
        if (data.photo) {
            photoImg.src = "{{ tenant_asset('placeholder') }}".replace('placeholder', data.photo);
            photoImg.classList.remove('hidden');
            photoPlaceholder.classList.add('hidden');
        } else {
            photoImg.classList.add('hidden');
            photoPlaceholder.textContent = (data.name_bn || '?').charAt(0);
            photoPlaceholder.classList.remove('hidden');
        }

        // Personal Info
        document.getElementById('modal_dob').textContent = data.date_of_birth ? new Date(data.date_of_birth).toLocaleDateString('bn-BD') : 'N/A';
        document.getElementById('modal_gender').textContent = data.gender || 'N/A';
        document.getElementById('modal_religion').textContent = data.religion || 'N/A';
        document.getElementById('modal_phone').textContent = data.phone || 'N/A';
        document.getElementById('modal_address').textContent = data.present_address || 'N/A';

        // Parents Info
        document.getElementById('modal_father_name').textContent = data.father_name || 'N/A';
        document.getElementById('modal_father_mobile_text').textContent = data.father_mobile || 'N/A';
        document.getElementById('modal_mother_name').textContent = data.mother_name || 'N/A';

        // Documents
        const docsContainer = document.getElementById('modal_documents');
        docsContainer.innerHTML = '';
        
        const documents = [
            { key: 'birth_certificate_file', label: 'জন্ম নিবন্ধন' },
            { key: 'father_nid_file', label: 'পিতার এনআইডি' },
            { key: 'mother_nid_file', label: 'মাতার এনআইডি' },
            { key: 'previous_school_certificate', label: 'ছাড়পত্র' },
        ];

        let hasDocs = false;
        documents.forEach(doc => {
            if (data[doc.key]) {
                hasDocs = true;
                const link = document.createElement('a');
                link.href = "{{ tenant_asset('placeholder') }}".replace('placeholder', data[doc.key]);
                link.target = '_blank';
                link.className = 'flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-blue-300 transition-all group';
                link.innerHTML = `
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600 group-hover:text-blue-600">${doc.label}</span>
                `;
                docsContainer.appendChild(link);
            }
        });

        if (!hasDocs) {
            docsContainer.innerHTML = '<p class="text-sm text-gray-500 col-span-2 italic">কোনো ডকুমেন্ট সংযুক্ত করা হয়নি</p>';
        }

        // Approve Form Action
        const form = document.getElementById('modal_approve_form');
        const baseUrl = "{{ route('tenant.students.approve-admission', 0) }}";
        form.action = baseUrl.replace('/0/approve-admission', '/' + data.id + '/approve-admission');

        // Show Modal
        const modal = document.getElementById('viewModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.transform').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            modal.querySelector('.transform').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('viewModal');
        modal.classList.add('hidden');
    }
</script>
@endsection

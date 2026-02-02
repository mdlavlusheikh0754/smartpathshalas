@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">এসএমএস গেটওয়ে সেটিংস</h1>
                <p class="text-gray-600 mt-1">এসএমএস সার্ভিস কনফিগার করুন</p>
            </div>
            <a href="{{ route('tenant.settings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                ফিরে যান
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">সফল!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <!-- API Configuration Section -->
        <form id="apiConfigForm" class="api-section">
            @csrf
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">API কনফিগারেশন</h2>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>সংরক্ষণ করুন</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API URL *</label>
                        <input type="url" name="api_url" value="{{ $settings->api_url }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://api.example.com/sms">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Key *</label>
                        <input type="text" name="api_key" value="{{ $settings->api_key }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                        <input type="password" name="api_secret" value="{{ $settings->api_secret }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID *</label>
                        <input type="text" name="sender_id" value="{{ $settings->sender_id }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="SCHOOL">
                    </div>
                </div>
            </div>
        </form>

        <!-- SMS Templates Section -->
        <form id="smsTemplatesForm" class="templates-section">
            @csrf
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">এসএমএস টেমপ্লেট</h3>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>সংরক্ষণ করুন</span>
                    </button>
                </div>
                
                <!-- Available Variables -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">উপলব্ধ ভেরিয়েবল (ক্লিক করে যোগ করুন):</p>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $variables = [
                                ['name' => '{name}', 'label' => 'অভিভাবকের নাম'],
                                ['name' => '{student_name}', 'label' => 'শিক্ষার্থীর নাম'],
                                ['name' => '{class}', 'label' => 'ক্লাস'],
                                ['name' => '{roll}', 'label' => 'রোল নম্বর'],
                                ['name' => '{amount}', 'label' => 'টাকার পরিমাণ'],
                                ['name' => '{date}', 'label' => 'তারিখ'],
                                ['name' => '{month}', 'label' => 'মাস'],
                                ['name' => '{phone}', 'label' => 'ফোন নম্বর'],
                            ];
                        @endphp
                        @foreach($variables as $var)
                        <button type="button" class="variable-btn px-3 py-2 bg-white border border-blue-300 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariable(this, '{{ $var['name'] }}')" title="{{ $var['label'] }}">
                            {{ $var['name'] }}
                        </button>
                        @endforeach
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach([
                        ['name' => 'admission', 'label' => 'ভর্তি নিশ্চিতকরণ'],
                        ['name' => 'fee_payment', 'label' => 'ফি পেমেন্ট'],
                        ['name' => 'absent', 'label' => 'অনুপস্থিতি']
                    ] as $template)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ $template['label'] }}</label>
                        <textarea name="template_{{ $template['name'] }}" rows="3" class="template-textarea w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" data-template="{{ $template['name'] }}">{{ $settings->{'template_' . $template['name']} }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>

        <!-- Custom SMS Templates -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">কাস্টম এসএমএস টেমপ্লেট</h3>
                    <button type="button" onclick="toggleCustomTemplateForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        নতুন টেমপ্লেট যোগ করুন
                    </button>
                </div>

                <!-- Add Custom Template Form -->
                <div id="customTemplateForm" class="hidden bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">নতুন কাস্টম টেমপ্লেট তৈরি করুন</h4>
                    <form id="addTemplateForm" onsubmit="handleAddCustomTemplate(event)">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">টেমপ্লেটের নাম *</label>
                                <input type="text" id="templateName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="যেমন: জন্মদিনের অভিনন্দন">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">বর্ণনা</label>
                                <input type="text" id="templateDescription" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="এই টেমপ্লেটের উদ্দেশ্য লিখুন">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">টেমপ্লেট টেক্সট *</label>
                                
                                <!-- Clickable Variables for Custom Template -->
                                <div class="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-xs font-medium text-gray-600 mb-2">ভেরিয়েবল যোগ করুন:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{name}')">
                                            {name}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{student_name}')">
                                            {student_name}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{class}')">
                                            {class}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{roll}')">
                                            {roll}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{amount}')">
                                            {amount}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{date}')">
                                            {date}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{month}')">
                                            {month}
                                        </button>
                                        <button type="button" class="variable-btn px-2 py-1 bg-white border border-blue-300 rounded text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors" onclick="insertVariableToCustom(this, '{phone}')">
                                            {phone}
                                        </button>
                                    </div>
                                </div>
                                
                                <textarea id="templateText" name="template" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="টেমপ্লেট লিখুন। উপরের বাটন থেকে ভেরিয়েবল ক্লিক করে যোগ করুন।&#10;উদাহরণ: প্রিয় {name}, আপনার সন্তান {student_name} অসুস্থ। ডাক্তারকে দেখান।"></textarea>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">সংরক্ষণ করুন</button>
                                <button type="button" onclick="toggleCustomTemplateForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">বাতিল করুন</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Custom Templates List -->
                <div id="customTemplatesList" class="space-y-3">
                    @forelse($customTemplates ?? [] as $template)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-900">{{ $template->name }}</h5>
                                @if($template->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $template->description }}</p>
                                @endif
                                <p class="text-sm text-gray-700 mt-2 p-3 bg-white rounded border border-gray-200">{{ $template->template }}</p>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button type="button" onclick="editCustomTemplate({{ $template->id }}, '{{ addslashes($template->name) }}', '{{ addslashes($template->description) }}', '{{ addslashes($template->template) }}')" class="text-blue-600 hover:text-blue-900 p-2" title="সম্পাদনা করুন">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l4-4m0 0l4-4m-4 4L9 4"></path>
                                    </svg>
                                </button>
                                <button type="button" onclick="deleteCustomTemplate({{ $template->id }})" class="text-red-600 hover:text-red-900 p-2" title="মুছে ফেলুন">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">কোন কাস্টম টেমপ্লেট নেই। একটি নতুন টেমপ্লেট যোগ করুন।</p>
                    @endforelse
                </div>
            </div>

            <!-- Test SMS -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">টেস্ট এসএমএস পাঠান</h3>
                <div class="flex gap-4">
                    <input type="text" id="test_phone" placeholder="মোবাইল নম্বর (01XXXXXXXXX)" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="send_test_sms" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <span class="btn-text">টেস্ট এসএমএস পাঠান</span>
                        <div class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
                <div id="test_response" class="mt-4 hidden"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Insert variable into default templates
function insertVariable(btn, variable) {
    event.preventDefault();
    
    // Find the focused textarea or the last one
    let activeTextarea = document.activeElement;
    if (!activeTextarea || activeTextarea.tagName !== 'TEXTAREA') {
        // If no textarea is focused, find the nearest one
        const textareas = document.querySelectorAll('.template-textarea');
        activeTextarea = textareas[textareas.length - 1] || null;
    }
    
    if (activeTextarea && activeTextarea.tagName === 'TEXTAREA') {
        const start = activeTextarea.selectionStart;
        const end = activeTextarea.selectionEnd;
        const text = activeTextarea.value;
        
        activeTextarea.value = text.substring(0, start) + variable + text.substring(end);
        activeTextarea.selectionStart = activeTextarea.selectionEnd = start + variable.length;
        activeTextarea.focus();
    }
}

// Insert variable into custom template textarea
function insertVariableToCustom(btn, variable) {
    event.preventDefault();
    
    const textarea = document.getElementById('templateText');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + variable.length;
    textarea.focus();
}

// Handle API Configuration Form Submission
document.getElementById('apiConfigForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    
    try {
        const response = await fetch('{{ route("tenant.settings.smsGateway.update") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: data
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('API কনফিগারেশন সফলভাবে সংরক্ষিত হয়েছে।', 'success');
        } else {
            showNotification('ত্রুটি: ' + (result.message || 'সংরক্ষণ ব্যর্থ হয়েছে'), 'error');
        }
    } catch (error) {
        showNotification('একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।', 'error');
        console.error(error);
    }
});

// Handle SMS Templates Form Submission
document.getElementById('smsTemplatesForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    
    try {
        const response = await fetch('{{ route("tenant.settings.smsGateway.update") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: data
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('এসএমএস টেমপ্লেট সফলভাবে সংরক্ষিত হয়েছে।', 'success');
        } else {
            showNotification('ত্রুটি: ' + (result.message || 'সংরক্ষণ ব্যর্থ হয়েছে'), 'error');
        }
    } catch (error) {
        showNotification('একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।', 'error');
        console.error(error);
    }
});

// Show notification toast
function showNotification(message, type = 'success') {
    const existingNotif = document.querySelector('.notification-toast');
    if (existingNotif) {
        existingNotif.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg font-medium z-50 animate-fade-in ${
        type === 'success' 
            ? 'bg-green-100 text-green-700 border border-green-500' 
            : 'bg-red-100 text-red-700 border border-red-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function toggleCustomTemplateForm() {
    const form = document.getElementById('customTemplateForm');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        document.getElementById('templateName').focus();
    }
}

async function handleAddCustomTemplate(e) {
    e.preventDefault();
    const form = document.getElementById('addTemplateForm');
    const data = new FormData(form);
    
    try {
        const response = await fetch('{{ route("tenant.settings.custom-sms-template.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: data
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('ত্রুটি: ' + (result.message || 'কাস্টম টেমপ্লেট যোগ করতে ব্যর্থ হয়েছে'));
        }
    } catch (error) {
        alert('একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        console.error(error);
    }
}

async function editCustomTemplate(id, name, description, template) {
    document.getElementById('templateName').value = name;
    document.getElementById('templateDescription').value = description || '';
    document.getElementById('templateText').value = template;
    
    const form = document.getElementById('addTemplateForm');
    
    // Remove old submit handler and add new one for update
    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);
    
    document.getElementById('addTemplateForm').onsubmit = async function(e) {
        e.preventDefault();
        const data = new FormData(this);
        
        try {
            const response = await fetch('{{ route("tenant.settings.custom-sms-template.update", ":id") }}'.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT',
                },
                body: data
            });
            
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('ত্রুটি: ' + (result.message || 'টেমপ্লেট আপডেট করতে ব্যর্থ হয়েছে'));
            }
        } catch (error) {
            alert('একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।');
            console.error(error);
        }
    };
    
    toggleCustomTemplateForm();
}

async function deleteCustomTemplate(id) {
    if (!confirm('আপনি কি এই টেমপ্লেটটি মুছে ফেলতে চান?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("tenant.settings.custom-sms-template.destroy", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('ত্রুটি: ' + (result.message || 'টেমপ্লেট মুছে ফেলতে ব্যর্থ হয়েছে'));
        }
    } catch (error) {
        alert('একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        console.error(error);
    }
}

document.getElementById('send_test_sms').addEventListener('click', async function() {
    const phone = document.getElementById('test_phone').value;
    const responseDiv = document.getElementById('test_response');
    const btn = this;
    const btnText = btn.querySelector('.btn-text');
    const spinner = btn.querySelector('.loading-spinner');

    if (!phone) {
        alert('অনুগ্রহ করে একটি মোবাইল নম্বর দিন');
        return;
    }

    // Reset UI
    responseDiv.classList.add('hidden');
    responseDiv.innerHTML = '';
    btnText.classList.add('hidden');
    spinner.classList.remove('hidden');
    btn.disabled = true;

    try {
        const response = await fetch('{{ route("tenant.settings.smsGateway.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        });

        const result = await response.json();

        responseDiv.classList.remove('hidden');
        if (result.success) {
            responseDiv.innerHTML = `<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">${result.message}</div>`;
        } else {
            responseDiv.innerHTML = `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">${result.message}</div>`;
        }
    } catch (error) {
        responseDiv.classList.remove('hidden');
        responseDiv.innerHTML = `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।</div>`;
    } finally {
        btnText.classList.remove('hidden');
        spinner.classList.add('hidden');
        btn.disabled = false;
    }
});
</script>
@endpush
@endsection

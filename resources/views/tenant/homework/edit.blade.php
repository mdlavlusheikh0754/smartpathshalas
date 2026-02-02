@extends('layouts.tenant')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('tenant.homework.manage') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">বাড়ির কাজ সম্পাদনা করুন</h1>
                <p class="text-gray-600 mt-1">বাড়ির কাজের তথ্য আপডেট করুন</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('tenant.homework.update', $homework->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">শিরোনাম *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $homework->title) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="বাড়ির কাজের শিরোনাম লিখুন" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">বিষয় *</label>
                        <select id="subject" name="subject" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">বিষয় নির্বাচন করুন</option>
                            <option value="বাংলা" {{ old('subject', $homework->subject) == 'বাংলা' ? 'selected' : '' }}>বাংলা</option>
                            <option value="ইংরেজি" {{ old('subject', $homework->subject) == 'ইংরেজি' ? 'selected' : '' }}>ইংরেজি</option>
                            <option value="গণিত" {{ old('subject', $homework->subject) == 'গণিত' ? 'selected' : '' }}>গণিত</option>
                            <option value="বিজ্ঞান" {{ old('subject', $homework->subject) == 'বিজ্ঞান' ? 'selected' : '' }}>বিজ্ঞান</option>
                            <option value="সমাজবিজ্ঞান" {{ old('subject', $homework->subject) == 'সমাজবিজ্ঞান' ? 'selected' : '' }}>সমাজবিজ্ঞান</option>
                            <option value="ইসলাম ধর্ম" {{ old('subject', $homework->subject) == 'ইসলাম ধর্ম' ? 'selected' : '' }}>ইসলাম ধর্ম</option>
                            <option value="হিন্দু ধর্ম" {{ old('subject', $homework->subject) == 'হিন্দু ধর্ম' ? 'selected' : '' }}>হিন্দু ধর্ম</option>
                            <option value="খ্রিস্ট ধর্ম" {{ old('subject', $homework->subject) == 'খ্রিস্ট ধর্ম' ? 'selected' : '' }}>খ্রিস্ট ধর্ম</option>
                            <option value="বৌদ্ধ ধর্ম" {{ old('subject', $homework->subject) == 'বৌদ্ধ ধর্ম' ? 'selected' : '' }}>বৌদ্ধ ধর্ম</option>
                            <option value="শারীরিক শিক্ষা" {{ old('subject', $homework->subject) == 'শারীরিক শিক্ষা' ? 'selected' : '' }}>শারীরিক শিক্ষা</option>
                            <option value="চারু ও কারুকলা" {{ old('subject', $homework->subject) == 'চারু ও কারুকলা' ? 'selected' : '' }}>চারু ও কারুকলা</option>
                        </select>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class and Section -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="class" class="block text-sm font-medium text-gray-700 mb-1">ক্লাস *</label>
                            <select id="class" name="class" 
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
                                    <option value="{{ $class->name }}" {{ old('class', $homework->class) == $class->name ? 'selected' : '' }}>{{ $class->name }} শ্রেণী - {{ $class->section }}</option>
                                @endforeach
                            </select>
                            @error('class')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700 mb-1">শাখা</label>
                            <select id="section" name="section" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">শাখা নির্বাচন করুন</option>
                                <option value="ক" {{ old('section', $homework->section) == 'ক' ? 'selected' : '' }}>ক</option>
                                <option value="খ" {{ old('section', $homework->section) == 'খ' ? 'selected' : '' }}>খ</option>
                                <option value="গ" {{ old('section', $homework->section) == 'গ' ? 'selected' : '' }}>গ</option>
                                <option value="ঘ" {{ old('section', $homework->section) == 'ঘ' ? 'selected' : '' }}>ঘ</option>
                            </select>
                            @error('section')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="assigned_date" class="block text-sm font-medium text-gray-700 mb-1">দেওয়ার তারিখ *</label>
                            <input type="date" id="assigned_date" name="assigned_date" value="{{ old('assigned_date', $homework->assigned_date?->format('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('assigned_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">জমার তারিখ *</label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $homework->due_date?->format('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('due_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Attachment -->
                    @if($homework->attachment)
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <h4 class="font-medium text-blue-900 mb-2">বর্তমান সংযুক্তি:</h4>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-paperclip text-blue-600"></i>
                            <a href="{{ $homework->getAttachmentUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                {{ basename($homework->attachment) }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- New Attachment -->
                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $homework->attachment ? 'নতুন সংযুক্তি (ঐচ্ছিক)' : 'সংযুক্তি' }}
                        </label>
                        <input type="file" id="attachment" name="attachment" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG ফাইল আপলোড করতে পারেন (সর্বোচ্চ ৫MB)</p>
                        @error('attachment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">বিবরণ *</label>
                        <textarea id="description" name="description" rows="8" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="বাড়ির কাজের বিস্তারিত বিবরণ লিখুন" required>{{ old('description', $homework->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">নির্দেশনা</label>
                        <textarea id="instructions" name="instructions" rows="6" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="শিক্ষার্থীদের জন্য বিশেষ নির্দেশনা (ঐচ্ছিক)">{{ old('instructions', $homework->instructions) }}</textarea>
                        @error('instructions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">অবস্থা *</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="active" {{ old('status', $homework->status) == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                            <option value="inactive" {{ old('status', $homework->status) == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                            <option value="overdue" {{ old('status', $homework->status) == 'overdue' ? 'selected' : '' }}>সময় শেষ</option>
                            <option value="completed" {{ old('status', $homework->status) == 'completed' ? 'selected' : '' }}>সম্পন্ন</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('tenant.homework.manage') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                    বাতিল
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                    পরিবর্তন সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Set minimum due date to assigned date
document.addEventListener('DOMContentLoaded', function() {
    const assignedDate = document.getElementById('assigned_date');
    const dueDate = document.getElementById('due_date');
    
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
</script>
@endsection

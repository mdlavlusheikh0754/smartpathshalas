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
                <h1 class="text-2xl font-bold text-gray-900">নতুন বাড়ির কাজ যোগ করুন</h1>
                <p class="text-gray-600 mt-1">শিক্ষার্থীদের জন্য নতুন বাড়ির কাজ তৈরি করুন</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('tenant.homework.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">শিরোনাম *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="বাড়ির কাজের শিরোনাম লিখুন" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">বিষয় *</label>
                        <select id="subject" name="subject" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">বিষয় নির্বাচন করুন</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->name }}" {{ old('subject') == $subject->name ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if($subjects->isEmpty())
                            <p class="text-orange-600 text-sm mt-2">
                                কোনো বিষয় পাওয়া যায়নি। 
                                <a href="{{ route('tenant.subjects.index') }}" class="underline hover:text-orange-800">বিষয় যোগ করুন</a>
                            </p>
                        @endif
                        <p id="subject-warning" class="text-orange-600 text-sm mt-2" style="display:none;"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="class" class="block text-sm font-medium text-gray-700 mb-1">ক্লাস *</label>
                            <select id="class" name="class" onchange="updateSections(); updateSubjects();"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">ক্লাস নির্বাচন করুন</option>
                                @php
                                    $uniqueClasses = $classes->unique('name')->pluck('name');
                                    $classSections = $classes->groupBy('name')->map(function ($items) {
                                        return $items->pluck('section')->unique()->values();
                                    });
                                    $classIdMap = $classes->groupBy('name')->map(function ($items) {
                                        return optional($items->first())->id;
                                    });
                                @endphp
                                @foreach($uniqueClasses as $className)
                                    <option value="{{ $className }}" {{ old('class') == $className ? 'selected' : '' }}>
                                        {{ $className }}
                                    </option>
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
                            <input type="date" id="assigned_date" name="assigned_date" value="{{ old('assigned_date', date('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('assigned_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">জমার তারিখ *</label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('due_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Attachment -->
                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">সংযুক্তি</label>
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
                                  placeholder="বাড়ির কাজের বিস্তারিত বিবরণ লিখুন" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">নির্দেশনা</label>
                        <textarea id="instructions" name="instructions" rows="6" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="শিক্ষার্থীদের জন্য বিশেষ নির্দেশনা (ঐচ্ছিক)">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">অবস্থা *</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
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
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                    বাড়ির কাজ সংরক্ষণ করুন
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

@section('scripts')
<script>
    const classSections = @json($classSections);
    const oldSection = "{{ old('section') }}";
    const classIdMap = @json($classIdMap);
    const subjectApi = "{{ route('tenant.subjects.api.subjects') }}";
    const oldSubject = "{{ old('subject') }}";

    function updateSections() {
        const classSelect = document.getElementById('class');
        const sectionSelect = document.getElementById('section');
        const selectedClass = classSelect.value;

        sectionSelect.innerHTML = '<option value="">শাখা নির্বাচন করুন</option>';

        if (selectedClass && classSections[selectedClass]) {
            classSections[selectedClass].forEach(section => {
                const option = document.createElement('option');
                option.value = section;
                option.textContent = section;
                if (section === oldSection) {
                    option.selected = true;
                }
                sectionSelect.appendChild(option);
            });
        }
    }

    function updateSubjects() {
        const classSelect = document.getElementById('class');
        const subjectSelect = document.getElementById('subject');
        const warning = document.getElementById('subject-warning');
        const selectedClassName = classSelect.value;
        subjectSelect.innerHTML = '<option value="">বিষয় নির্বাচন করুন</option>';
        if (warning) warning.style.display = 'none';
        if (!selectedClassName || !classIdMap[selectedClassName]) {
            return;
        }
        const classId = classIdMap[selectedClassName];
        fetch(subjectApi + '?class_id=' + classId)
            .then(res => res.json())
            .then(items => {
                if (!items || items.length === 0) {
                    if (warning) {
                        warning.textContent = 'কোনো বিষয় পাওয়া যায়নি। বিষয় যোগ করুন';
                        warning.style.display = 'block';
                    }
                    return;
                }
                items.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.name;
                    opt.textContent = s.name;
                    if (s.name === oldSubject) opt.selected = true;
                    subjectSelect.appendChild(opt);
                });
            })
            .catch(() => {
                if (warning) {
                    warning.textContent = 'বিষয় লোড করতে সমস্যা হচ্ছে';
                    warning.style.display = 'block';
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('class').value) {
            updateSections();
            updateSubjects();
        }
    });
</script>
@endsection

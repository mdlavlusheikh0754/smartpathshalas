@extends('layouts.tenant')

@section('title', 'নতুন নোটিশ')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">নতুন নোটিশ তৈরি করুন</h1>
                <p class="text-gray-600 mt-1">সবাইকে জানানোর জন্য নোটিশের তথ্য পূরণ করুন</p>
            </div>
            <a href="{{ route('tenant.notices.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> ফিরে যান
            </a>
        </div>

        <form action="{{ route('tenant.notices.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">নোটিশের শিরোনাম <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="যেমন: বার্ষিক পরীক্ষার রুটিন প্রকাশ">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">গুরুত্ব (Priority) <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>সাধারণ</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>নিম্ন</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>উচ্চ</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>জরুরি</option>
                    </select>
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">স্ট্যাটাস <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>সক্রিয় (প্রকাশিত)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (খসড়া)</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">নোটিশের বিস্তারিত <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="নোটিশের বিস্তারিত এখানে লিখুন...">{{ old('content') }}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Attachment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">সংযুক্ত ফাইল (PDF/Image)</label>
                    <input type="file" name="attachment" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                    <p class="text-gray-400 text-xs mt-1">সর্বোচ্চ ৫ মেগাবাইট (PDF, JPG, PNG)</p>
                    @error('attachment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">প্রকাশের তারিখ</label>
                        <input type="date" name="publish_date" value="{{ old('publish_date', date('Y-m-d')) }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('publish_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">মেয়াদ শেষ (ঐচ্ছিক)</label>
                        <input type="date" name="expire_date" value="{{ old('expire_date') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('expire_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-4">
                <button type="button" onclick="resetNoticeForm()" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all">রিসেট করুন</button>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">প্রকাশ করুন</button>
            </div>
        </form>
    </div>
</div>

<script>
function resetNoticeForm() {
    // Reset all form fields except file inputs
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
    
    inputs.forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
    
    // Reset file inputs safely by replacing them
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
    });
}
</script>
@endsection

@extends('layouts.tenant')

@section('title', 'নোটিশ সম্পাদনা')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">নোটিশ সম্পাদনা করুন</h1>
                <p class="text-gray-600 mt-1">নোটিশের তথ্য আপডেট করুন</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('tenant.notices.show', $notice->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-2">
                    <i class="fas fa-eye"></i> প্রিভিউ
                </a>
                <a href="{{ route('tenant.notices.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>

        <form action="{{ route('tenant.notices.update', $notice->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">নোটিশের শিরোনাম <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $notice->title) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="যেমন: বার্ষিক পরীক্ষার রুটিন প্রকাশ">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">গুরুত্ব (Priority) <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="normal" {{ old('priority', $notice->priority) == 'normal' ? 'selected' : '' }}>সাধারণ</option>
                        <option value="low" {{ old('priority', $notice->priority) == 'low' ? 'selected' : '' }}>নিম্ন</option>
                        <option value="high" {{ old('priority', $notice->priority) == 'high' ? 'selected' : '' }}>উচ্চ</option>
                        <option value="urgent" {{ old('priority', $notice->priority) == 'urgent' ? 'selected' : '' }}>জরুরি</option>
                    </select>
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">স্ট্যাটাস <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="active" {{ old('status', $notice->status) == 'active' ? 'selected' : '' }}>সক্রিয় (প্রকাশিত)</option>
                        <option value="inactive" {{ old('status', $notice->status) == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (খসড়া)</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">নোটিশের বিস্তারিত <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="নোটিশের বিস্তারিত এখানে লিখুন...">{{ old('content', $notice->content) }}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Current Attachment -->
                @if($notice->attachment)
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">বর্তমান সংযুক্ত ফাইল</label>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center text-indigo-600 border border-gray-100">
                                <i class="fas fa-paperclip"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">সংযুক্ত ফাইল</p>
                                <p class="text-xs text-gray-500">বর্তমানে একটি ফাইল সংযুক্ত আছে</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center gap-1">
                            <i class="fas fa-external-link-alt"></i> দেখুন
                        </a>
                    </div>
                </div>
                @endif

                <!-- New Attachment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $notice->attachment ? 'নতুন ফাইল আপলোড করুন (ঐচ্ছিক)' : 'সংযুক্ত ফাইল (PDF/Image)' }}
                    </label>
                    <input type="file" name="attachment" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                    <p class="text-gray-400 text-xs mt-1">
                        {{ $notice->attachment ? 'নতুন ফাইল আপলোড করলে পুরানো ফাইল প্রতিস্থাপিত হবে।' : '' }}
                        সর্বোচ্চ ৫ মেগাবাইট (PDF, JPG, PNG)
                    </p>
                    @error('attachment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">প্রকাশের তারিখ</label>
                        <input type="date" name="publish_date" value="{{ old('publish_date', $notice->publish_date ? $notice->publish_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('publish_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">মেয়াদ শেষ (ঐচ্ছিক)</label>
                        <input type="date" name="expire_date" value="{{ old('expire_date', $notice->expire_date ? $notice->expire_date->format('Y-m-d') : '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('expire_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('tenant.notices.show', $notice->id) }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all">বাতিল করুন</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">আপডেট করুন</button>
            </div>
        </form>
    </div>
</div>
@endsection
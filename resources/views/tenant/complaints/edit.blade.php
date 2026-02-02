@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 bengali-text">অভিযোগ এডিট করুন</h1>
                <p class="text-gray-600 mt-2 bengali-text">অভিযোগ নং: #C{{ str_pad($complaint->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('tenant.complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors bengali-text">তালিকায় ফিরে যান</a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('tenant.complaints.update', $complaint->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অভিযোগের অবস্থা *</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="new" {{ $complaint->status == 'new' ? 'selected' : '' }}>নতুন</option>
                            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>বিচারাধীন</option>
                            <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>সমাধানকৃত</option>
                            <option value="cancelled" {{ $complaint->status == 'cancelled' ? 'selected' : '' }}>বাতিল</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">অগ্রাধিকার *</label>
                        <select name="priority" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text">
                            <option value="low" {{ $complaint->priority == 'low' ? 'selected' : '' }}>নিম্ন</option>
                            <option value="medium" {{ $complaint->priority == 'medium' ? 'selected' : '' }}>মাধ্যম</option>
                            <option value="high" {{ $complaint->priority == 'high' ? 'selected' : '' }}>উচ্চ</option>
                            <option value="urgent" {{ $complaint->priority == 'urgent' ? 'selected' : '' }}>জরুরি</option>
                        </select>
                    </div>
                </div>

                <!-- Resolution Notes -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 bengali-text">সমাধানের বিবরণ / নোট</label>
                    <textarea name="resolution_notes" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bengali-text" placeholder="অভিযোগটি কীভাবে সমাধান করা হয়েছে বা কেন বাতিল করা হয়েছে তার বিবরণ লিখুন...">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 bg-gray-50 -mx-6 -mb-6 p-6 rounded-b-xl">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider bengali-text">অভিযোগের তথ্য (শুধুমাত্র পাঠযোগ্য)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 bengali-text">বিষয়: <span class="text-gray-900 font-medium">{{ $complaint->subject }}</span></p>
                        </div>
                        <div>
                            <p class="text-gray-500 bengali-text">অভিযোগকারী: <span class="text-gray-900 font-medium">{{ $complaint->is_anonymous ? 'গোপনীয়' : $complaint->complainant_name }}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('tenant.complaints.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors bengali-text">বাতিল</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors bengali-text">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

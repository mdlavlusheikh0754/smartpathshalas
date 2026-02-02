@php
    $schoolSettings = \App\Models\SchoolSetting::getSettings();
    $websiteSettings = \App\Models\WebsiteSetting::getSettings();
@endphp

@extends('tenant.layouts.web')

@section('title', 'ফলাফল')

@section('content')
    <!-- Main Content -->
    <main class="flex-grow pt-16 pb-16 px-4">
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 mb-6 rounded text-sm" role="alert">
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('public.result.search') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Examination -->
                    <div class="flex items-center">
                        <label class="w-32 text-sm font-medium text-gray-800">পরীক্ষা</label>
                        <span class="mx-3 text-gray-600">:</span>
                        <select name="exam_id" id="exam_id" required class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-gray-50">
                            <option value="">একটি নির্বাচন করুন</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year -->
                    <div class="flex items-center">
                        <label class="w-32 text-sm font-medium text-gray-800">সাল</label>
                        <span class="mx-3 text-gray-600">:</span>
                        <select name="year" id="year" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-gray-50">
                            <option value="">একটি নির্বাচন করুন</option>
                            <option value="2026" {{ old('year') == '2026' ? 'selected' : '' }}>২০২৬</option>
                            <option value="2025" {{ old('year') == '2025' ? 'selected' : '' }}>২০২৫</option>
                            <option value="2024" {{ old('year') == '2024' ? 'selected' : '' }}>২০২৪</option>
                        </select>
                    </div>

                    <!-- Board -->
                    <div class="flex items-center">
                        <label class="w-32 text-sm font-medium text-gray-800">বোর্ড</label>
                        <span class="mx-3 text-gray-600">:</span>
                        <select name="board" id="board" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-gray-50">
                            <option value="">একটি নির্বাচন করুন</option>
                            @if($schoolSettings->board)
                                <option value="{{ $schoolSettings->board }}" selected>{{ $schoolSettings->board }}</option>
                            @else
                                <option value="ঢাকা">ঢাকা</option>
                                <option value="চট্টগ্রাম">চট্টগ্রাম</option>
                                <option value="রাজশাহী">রাজশাহী</option>
                                <option value="যশোর">যশোর</option>
                                <option value="কুমিল্লা">কুমিল্লা</option>
                                <option value="বরিশাল">বরিশাল</option>
                                <option value="সিলেট">সিলেট</option>
                                <option value="দিনাজপুর">দিনাজপুর</option>
                                <option value="মাদ্রাসা">মাদ্রাসা</option>
                                <option value="কারিগরি">কারিগরি</option>
                            @endif
                        </select>
                    </div>

                    <!-- Roll -->
                    <div class="flex items-center">
                        <label class="w-32 text-sm font-medium text-gray-800">রোল</label>
                        <span class="mx-3 text-gray-600">:</span>
                        <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" required class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-gray-50">
                    </div>

                    <!-- Registration Number -->
                    <div class="flex items-center">
                        <label class="w-32 text-sm font-medium text-gray-800">রেজি: নং</label>
                        <span class="mx-3 text-gray-600">:</span>
                        <input type="text" name="registration_no" id="registration_no" value="{{ old('registration_no') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-gray-50">
                    </div>

                    <!-- Simple Math Captcha -->
                    @php
                        $num1 = rand(1, 9);
                        $num2 = rand(1, 9);
                        $answer = $num1 + $num2;
                    @endphp
                    <div class="flex items-center">
                        <label class="w-32 text-sm font-medium text-gray-800">{{ $num1 }} + {{ $num2 }}</label>
                        <span class="mx-3 text-gray-600">=</span>
                        <input type="text" name="captcha_answer" id="captcha_answer" required class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-gray-50">
                        <input type="hidden" name="captcha_correct" value="{{ $answer }}">
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4 justify-center">
                        <button type="reset" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium shadow-sm">
                            রিসেট
                        </button>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                            জমা দিন
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="text-center mt-6">
                <a href="{{ route('tenant.home') }}" class="text-gray-500 hover:text-blue-600 transition text-sm">
                    ← হোম পেজে ফিরে যান
                </a>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const formToken = document.querySelector('input[name="_token"]')?.value;
    
    console.log('CSRF Debug:', {
        metaToken: csrfToken,
        formToken: formToken,
        tokensMatch: csrfToken === formToken,
        sessionCookie: document.cookie.includes('laravel_session') || document.cookie.includes('smartpathshala-session')
    });
    
    // Add form submission handler for debugging
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const formData = new FormData(this);
            console.log('Form submission data:', {
                hasToken: formData.has('_token'),
                tokenValue: formData.get('_token'),
                examId: formData.get('exam_id'),
                studentId: formData.get('student_id')
            });
        });
    }
});
</script>
@endsection

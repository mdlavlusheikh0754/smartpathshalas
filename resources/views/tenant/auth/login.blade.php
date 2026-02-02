@php
    $schoolSettings = \App\Models\SchoolSetting::getSettings();
    $websiteSettings = \App\Models\WebsiteSetting::getSettings();
    $schoolName = $schoolSettings->school_name_bn ?? (tenant('data')['school_name'] ?? tenant('id'));
@endphp

@extends('tenant.layouts.web')

@section('title', 'লগইন')

@section('content')
    <!-- Login Section -->
    <div class="min-h-[calc(100vh-200px)] bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 flex items-center">
        <div class="max-w-md mx-auto px-4 w-full">
            <!-- School Info Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-graduation-cap text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $schoolName }}</h1>
                <p class="text-blue-600 font-medium">স্বাগতম!</p>
                <p class="text-gray-600 text-sm">আপনার অ্যাকাউন্টে লগইন করুন</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 card-shadow">
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2 text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2 text-green-600 text-sm">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ url('/login') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope text-blue-500 mr-1"></i>ইমেইল
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            autocomplete="email"
                            autofocus
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all"
                            placeholder="আপনার ইমেইল লিখুন"
                        >
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock text-blue-500 mr-1"></i>পাসওয়ার্ড
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required
                                autocomplete="current-password"
                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all"
                                placeholder="আপনার পাসওয়ার্ড লিখুন"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 top-2 text-gray-500 hover:text-blue-600 transition-colors"
                            >
                                <i id="passwordToggle" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="ml-2 text-gray-600">আমাকে মনে রাখুন</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ url('/forgot-password') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                                পাসওয়ার্ড ভুলে গেছেন?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:-translate-y-0.5"
                    >
                        লগইন করুন
                    </button>
                </form>
            </div>
            
            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('tenant.home') }}" class="text-gray-500 hover:text-blue-600 text-sm font-medium transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    হোম পেজে ফিরে যান
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.classList.remove('fa-eye');
            passwordToggle.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordToggle.classList.remove('fa-eye-slash');
            passwordToggle.classList.add('fa-eye');
        }
    }
</script>
@endsection

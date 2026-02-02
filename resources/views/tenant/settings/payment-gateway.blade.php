@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">পেমেন্ট গেটওয়ে সেটিংস</h1>
                <p class="text-gray-600 mt-1">অনলাইন পেমেন্ট মেথড কনফিগার করুন</p>
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

        <form action="{{ route('tenant.settings.payment-gateway.update') }}" method="POST">
            @csrf
            
            <!-- SSLCommerz -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-3 rounded-xl shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">SSLCommerz</h2>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ssl_active" class="sr-only peer" {{ $settings->ssl_active ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">সক্রিয়</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Store ID</label>
                        <input type="text" name="ssl_store_id" value="{{ $settings->ssl_store_id }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Store Password</label>
                        <input type="password" name="ssl_store_password" value="{{ $settings->ssl_store_password }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                        <select name="ssl_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="sandbox" {{ $settings->ssl_mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $settings->ssl_mode == 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Shurjopay -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-red-500 to-orange-600 p-3 rounded-xl shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Shurjopay</h2>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="shurjopay_active" class="sr-only peer" {{ $settings->shurjopay_active ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">সক্রিয়</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="shurjopay_username" value="{{ $settings->shurjopay_username }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="shurjopay_password" value="{{ $settings->shurjopay_password }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prefix</label>
                        <input type="text" name="shurjopay_prefix" value="{{ $settings->shurjopay_prefix }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                        <select name="shurjopay_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="sandbox" {{ $settings->shurjopay_mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $settings->shurjopay_mode == 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- bKash -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-pink-500 to-rose-600 p-3 rounded-xl shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">bKash</h2>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="bkash_active" class="sr-only peer" {{ $settings->bkash_active ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-pink-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">সক্রিয়</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">App Key</label>
                        <input type="text" name="bkash_app_key" value="{{ $settings->bkash_app_key }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">App Secret</label>
                        <input type="password" name="bkash_app_secret" value="{{ $settings->bkash_app_secret }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="bkash_username" value="{{ $settings->bkash_username }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="bkash_password" value="{{ $settings->bkash_password }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                        <select name="bkash_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="sandbox" {{ $settings->bkash_mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $settings->bkash_mode == 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Nagad -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-3 rounded-xl shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Nagad</h2>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="nagad_active" class="sr-only peer" {{ $settings->nagad_active ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">সক্রিয়</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                            <input type="text" name="nagad_merchant_id" value="{{ $settings->nagad_merchant_id }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                            <select name="nagad_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="sandbox" {{ $settings->nagad_mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                <option value="live" {{ $settings->nagad_mode == 'live' ? 'selected' : '' }}>Live</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Public Key</label>
                        <textarea name="nagad_public_key" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ $settings->nagad_public_key }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Private Key</label>
                        <textarea name="nagad_private_key" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ $settings->nagad_private_key }}</textarea>
                    </div>
                </div>
            </div>

            <!-- AmarPay -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-3 rounded-xl shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">AmarPay</h2>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="amarpay_active" class="sr-only peer" {{ $settings->amarpay_active ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">সক্রিয়</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Store ID</label>
                        <input type="text" name="amarpay_store_id" value="{{ $settings->amarpay_store_id }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Signature Key</label>
                        <input type="password" name="amarpay_signature_key" value="{{ $settings->amarpay_signature_key }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                        <select name="amarpay_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="sandbox" {{ $settings->amarpay_mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $settings->amarpay_mode == 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Custom Payment Methods Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-purple-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        কাস্টম পেমেন্ট মেথড
                    </h3>
                    <button type="button" onclick="togglePaymentMethodForm()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        নতুন যোগ করুন
                    </button>
                </div>

                <!-- Add Payment Method Form -->
                <div id="paymentMethodForm" class="hidden bg-purple-50 rounded-2xl p-6 mb-8 border-2 border-purple-200">
                    <form id="addPaymentMethodForm" onsubmit="handleAddPaymentMethod(event)">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Provider Dropdown -->
                            <div>
                                <label class="block text-gray-700 font-bold mb-3">
                                    পেমেন্ট প্রোভাইডার <span class="text-red-500">*</span>
                                </label>
                                <select name="provider" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200" required>
                                    <option value="">-- নির্বাচন করুন --</option>
                                    <option value="bKash">bKash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Ufone">Ufone</option>
                                    <option value="Banglalink">Banglalink</option>
                                    <option value="Teletalk">Teletalk</option>
                                    <option value="Others">অন্যান্য</option>
                                </select>
                            </div>

                            <!-- Account Number -->
                            <div>
                                <label class="block text-gray-700 font-bold mb-3">
                                    অ্যাকাউন্ট নম্বর <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="account_number" placeholder="যেমন: 01700000000" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200" required>
                            </div>

                            <!-- Display Name -->
                            <div>
                                <label class="block text-gray-700 font-bold mb-3">
                                    প্রদর্শন নাম (ঐচ্ছিক)
                                </label>
                                <input type="text" name="display_name" placeholder="যেমন: প্রধান অ্যাকাউন্ট" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200">
                            </div>

                            <!-- QR Code Upload -->
                            <div>
                                <label class="block text-gray-700 font-bold mb-3">
                                    QR কোড (ঐচ্ছিক)
                                </label>
                                <input type="file" name="qr_code" accept="image/*" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200" onchange="previewQRCode(this)">
                                <div id="qrPreview" class="mt-3 hidden">
                                    <img id="qrImage" src="" alt="QR Preview" class="w-24 h-24 border-2 border-purple-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <button type="button" onclick="togglePaymentMethodForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-xl font-bold transition-colors">
                                বাতিল করুন
                            </button>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                যোগ করুন
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Methods List -->
                <div id="paymentMethodsList" class="space-y-4">
                    @if($customPaymentMethods && count($customPaymentMethods) > 0)
                        @foreach($customPaymentMethods as $method)
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border-2 border-purple-200 flex items-center justify-between hover:shadow-lg transition-shadow">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4">
                                        <div class="text-3xl">
                                            @switch($method->provider)
                                                @case('bKash')
                                                    <span class="text-red-600">🏦</span>
                                                    @break
                                                @case('Nagad')
                                                    <span class="text-orange-600">📱</span>
                                                    @break
                                                @case('Rocket')
                                                    <span class="text-blue-600">⚡</span>
                                                    @break
                                                @case('Ufone')
                                                    <span class="text-orange-700">📲</span>
                                                    @break
                                                @case('Banglalink')
                                                    <span class="text-pink-600">💳</span>
                                                    @break
                                                @case('Teletalk')
                                                    <span class="text-cyan-600">📞</span>
                                                    @break
                                                @default
                                                    <span class="text-purple-600">💰</span>
                                            @endswitch
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-lg">{{ $method->provider }}</h4>
                                            <p class="text-gray-600">
                                                অ্যাকাউন্ট: <span class="font-mono font-bold">{{ substr($method->account_number, 0, 5) }}****{{ substr($method->account_number, -2) }}</span>
                                            </p>
                                            @if($method->display_name)
                                                <p class="text-gray-600 text-sm">{{ $method->display_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    @if($method->qr_code)
                                        <img src="{{ asset('storage/' . $method->qr_code) }}" alt="QR Code" class="w-20 h-20 border-2 border-purple-300 rounded-lg">
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="editPaymentMethod({{ $method->id }}, '{{ $method->provider }}', '{{ $method->account_number }}', '{{ $method->display_name }}')" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="deletePaymentMethod({{ $method->id }})" class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-gray-100 rounded-xl p-8 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-600 font-bold">কোনো কাস্টম পেমেন্ট মেথড যোগ করা হয়নি</p>
                            <p class="text-gray-500 text-sm mt-2">আপনার প্রথম পেমেন্ট মেথড যোগ করতে উপরের বাটন ব্যবহার করুন</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-end gap-4">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold transition-colors">
                        রিসেট করুন
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        সংরক্ষণ করুন
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePaymentMethodForm() {
        const form = document.getElementById('paymentMethodForm');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            document.getElementById('addPaymentMethodForm').reset();
            document.getElementById('qrPreview').classList.add('hidden');
        }
    }

    function previewQRCode(input) {
        const preview = document.getElementById('qrPreview');
        const qrImage = document.getElementById('qrImage');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                qrImage.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleAddPaymentMethod(event) {
        event.preventDefault();
        const form = document.getElementById('addPaymentMethodForm');
        const formData = new FormData(form);

        fetch("{{ route('tenant.settings.custom-payment-method.store') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                form.reset();
                document.getElementById('qrPreview').classList.add('hidden');
                togglePaymentMethodForm();
                // Reload payment methods list
                location.reload();
            } else {
                showNotification(data.message || 'ত্রুটি ঘটেছে', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('অনুরোধ প্রক্রিয়াকরণে ত্রুটি', 'error');
        });
    }

    function editPaymentMethod(id, provider, accountNumber, displayName) {
        alert('এডিট ফিচার শীঘ্রই আসছে');
        // TODO: Implement edit functionality
    }

    function deletePaymentMethod(id) {
        if (confirm('এই পেমেন্ট মেথড মুছে ফেলতে চান?')) {
            fetch(`/settings/custom-payment-method/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message || 'ত্রুটি ঘটেছে', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('অনুরোধ প্রক্রিয়াকরণে ত্রুটি', 'error');
            });
        }
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg font-bold z-50 ${
            type === 'success' ? 'bg-green-100 text-green-700 border-2 border-green-400' : 'bg-red-100 text-red-700 border-2 border-red-400'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
</script>
@endsection

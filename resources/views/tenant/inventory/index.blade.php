@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ইনভেন্টরি ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">সকল পণ্য ও সরঞ্জাম দেখুন এবং পরিচালনা করুন</p>
        </div>
        <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            নতুন আইটেম যোগ করুন
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">মোট আইটেম</p>
                    <h3 class="text-3xl font-bold mt-1" id="totalItems">{{ count($items) }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">স্টকে আছে</p>
                    <h3 class="text-3xl font-bold mt-1" id="inStockCount">{{ $items->where('status', 'in_stock')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">কম স্টক</p>
                    <h3 class="text-3xl font-bold mt-1" id="lowStockCount">{{ $items->where('status', 'low_stock')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">মোট মূল্য</p>
                    <h3 class="text-3xl font-bold mt-1" id="totalValue">৳ {{ number_format($items->sum(fn($item) => ($item['price'] ?? 0) * ($item['stock'] ?? 0))) }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" id="searchInput" placeholder="নাম দিয়ে খুঁজুন..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" oninput="filterItems()">
            </div>
            <div>
                <select id="categoryFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterItems()">
                    <option value="">সকল ক্যাটাগরি</option>
                    <option value="স্টেশনারি">স্টেশনারি</option>
                    <option value="আসবাবপত্র">আসবাবপত্র</option>
                    <option value="ইলেকট্রনিক্স">ইলেকট্রনিক্স</option>
                    <option value="খেলাধুলা">খেলাধুলা</option>
                    <option value="অন্যান্য">অন্যান্য</option>
                </select>
            </div>
            <div>
                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterItems()">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="in_stock">স্টকে আছে</option>
                    <option value="low_stock">কম স্টক</option>
                    <option value="out_of_stock">স্টক শেষ</option>
                </select>
            </div>
            <div>
                <button onclick="resetFilters()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    রিসেট করুন
                </button>
            </div>
        </div>
        <div id="searchResults" class="mt-3 text-sm text-gray-600"></div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">কোড</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">নাম</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">ক্যাটাগরি</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">মূল্য</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">স্টক</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">ইউনিট</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="inventoryTableBody">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors inventory-row" 
                        data-name="{{ $item['name'] ?? '' }}"
                        data-category="{{ $item['category'] ?? '' }}"
                        data-status="{{ $item['status'] ?? '' }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['item_code'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($item['name'] ?? 'I', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item['name'] ?? 'N/A' }}</p>
                                    @if(!empty($item['description']))
                                    <p class="text-xs text-gray-500">{{ Str::limit($item['description'], 30) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $item['category_name'] ?? $item['category'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">৳ {{ number_format($item['price'] ?? 0) }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item['stock'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $item['unit_name'] ?? $item['unit'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusBadge = [
                                    'in_stock' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'স্টকে আছে'],
                                    'low_stock' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'কম স্টক'],
                                    'out_of_stock' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'স্টক শেষ'],
                                ];
                                $status = $item['status'] ?? 'in_stock';
                                $badge = $statusBadge[$status] ?? $statusBadge['in_stock'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badge['bg'] }} {{ $badge['text'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" data-item='@json($item)' onclick="viewItem(JSON.parse(this.getAttribute('data-item')))" class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-colors" title="বিস্তারিত দেখুন">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button type="button" data-item='@json($item)' onclick="editItem(JSON.parse(this.getAttribute('data-item')))" class="bg-green-100 hover:bg-green-200 text-green-600 p-2 rounded-lg transition-colors" title="সম্পাদনা করুন">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button" data-id="{{ $item['id'] ?? 0 }}" onclick="confirmDelete(this.getAttribute('data-id'))" class="bg-red-100 hover:bg-red-200 text-red-600 p-2 rounded-lg transition-colors" title="মুছে ফেলুন">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">কোনো আইটেম পাওয়া যায়নি</p>
                                <p class="text-gray-400 mt-2">নতুন আইটেম যোগ করতে উপরের বাটনে ক্লিক করুন</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Item Modal -->
<div id="itemModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-2xl rounded-3xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">নতুন আইটেম যোগ করুন</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="itemForm" onsubmit="saveItem(event)">
            <input type="hidden" id="itemId" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">আইটেমের নাম *</label>
                    <select id="itemName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="toggleCustomItemName()">
                        <option value="">নির্বাচন করুন</option>
                        <option value="বই">বই</option>
                        <option value="খাতা">খাতা</option>
                        <option value="কলম">কলম</option>
                        <option value="পেন্সিল">পেন্সিল</option>
                        <option value="রাবার">রাবার</option>
                        <option value="স্কুল ব্যাগ">স্কুল ব্যাগ</option>
                        <option value="ইউনিফর্ম">ইউনিফর্ম</option>
                        <option value="জুতা">জুতা</option>
                        <option value="টাই">টাই</option>
                        <option value="বেল্ট">বেল্ট</option>
                        <option value="আইডি কার্ড">আইডি কার্ড</option>
                        <option value="অন্যান্য">অন্যান্য</option>
                        <option value="__custom">অন্যান্য (নিজে লিখুন)</option>
                    </select>
                    <div id="customItemNameWrapper" class="mt-2 hidden">
                        <input type="text" id="customItemName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="আইটেমের নাম লিখুন">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ক্যাটাগরি *</label>
                    <select id="itemCategory" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">নির্বাচন করুন</option>
                        <option value="stationery">স্টেশনারি</option>
                        <option value="furniture">আসবাবপত্র</option>
                        <option value="electronics">ইলেকট্রনিক্স</option>
                        <option value="sports">খেলাধুলা</option>
                        <option value="others">অন্যান্য</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">মূল্য (টাকা) *</label>
                    <input type="number" id="itemPrice" name="price" min="0" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">স্টক পরিমাণ *</label>
                    <input type="number" id="itemStock" name="stock" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">মিনিমাম স্টক</label>
                    <input type="number" id="itemMinStock" name="min_stock" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ইউনিট *</label>
                    <select id="itemUnit" name="unit" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">নির্বাচন করুন</option>
                        <option value="piece">পিস</option>
                        <option value="box">বক্স</option>
                        <option value="packet">প্যাকেট</option>
                        <option value="set">সেট</option>
                        <option value="kg">কেজি</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">বিবরণ</label>
                    <textarea id="itemDescription" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex gap-4 mt-8">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300">
                    সংরক্ষণ করুন
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-xl font-bold transition-all duration-300">
                    বাতিল করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Item Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-2xl rounded-3xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">আইটেম বিস্তারিত</h3>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="viewContent" class="space-y-4">
            <!-- Content will be populated dynamically -->
        </div>
        
        <div class="mt-8">
            <button onclick="closeViewModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-xl font-bold transition-all duration-300">
                বন্ধ করুন
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-96 shadow-2xl rounded-3xl bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-gray-600 mb-6">এই আইটেমটি স্থায়ীভাবে মুছে ফেলা হবে।</p>
            
            <div class="flex gap-4">
                <button onclick="deleteItem()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold transition-all duration-300">
                    হ্যাঁ, মুছে ফেলুন
                </button>
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-xl font-bold transition-all duration-300">
                    না, বাতিল করুন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Message Modal -->
<div id="inventorySuccessModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center h-full w-full z-50">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 p-8">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">সফল!</h3>
            <p id="inventorySuccessMessage" class="text-gray-700 mb-6"></p>
            <button onclick="closeInventorySuccessModal()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full transition-colors">
                ঠিক আছে
            </button>
        </div>
    </div>
</div>

<script>
let currentItemId = null;
let isEditMode = false;

function normalizeNumberInput(value) {
    if (!value) return '';
    const banglaDigits = {
        '০': '0',
        '১': '1',
        '২': '2',
        '৩': '3',
        '৪': '4',
        '৫': '5',
        '৬': '6',
        '৭': '7',
        '৮': '8',
        '৯': '9'
    };
    let result = '';
    for (const ch of value.toString()) {
        result += banglaDigits[ch] ?? ch;
    }
    result = result.replace(/[^\d.-]/g, '');
    return result;
}

function showInventorySuccess(message) {
    const modal = document.getElementById('inventorySuccessModal');
    const text = document.getElementById('inventorySuccessMessage');
    if (!modal || !text) return;
    text.textContent = message || 'নতুন আইটেম সফলভাবে যোগ করা হয়েছে!';
    modal.classList.remove('hidden');
}

function closeInventorySuccessModal() {
    const modal = document.getElementById('inventorySuccessModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    location.reload();
}

function toggleCustomItemName() {
    const select = document.getElementById('itemName');
    const wrapper = document.getElementById('customItemNameWrapper');
    const customInput = document.getElementById('customItemName');
    if (!select || !wrapper || !customInput) return;
    if (select.value === '__custom') {
        wrapper.classList.remove('hidden');
        customInput.focus();
    } else {
        wrapper.classList.add('hidden');
        customInput.value = '';
    }
}

// Open Add Modal
function openAddModal() {
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'নতুন আইটেম যোগ করুন';
    document.getElementById('itemForm').reset();
    document.getElementById('itemId').value = '';
    document.getElementById('itemModal').classList.remove('hidden');
}

// Edit Item
function editItem(item) {
    isEditMode = true;
    document.getElementById('modalTitle').textContent = 'আইটেম সম্পাদনা করুন';
    document.getElementById('itemId').value = item.id || '';
    const nameSelect = document.getElementById('itemName');
    const customWrapper = document.getElementById('customItemNameWrapper');
    const customInput = document.getElementById('customItemName');
    if (nameSelect && customWrapper && customInput) {
        const predefinedValues = Array.from(nameSelect.options).map(o => o.value);
        if (item.name && predefinedValues.includes(item.name)) {
            nameSelect.value = item.name;
            customWrapper.classList.add('hidden');
            customInput.value = '';
        } else if (item.name) {
            nameSelect.value = '__custom';
            customWrapper.classList.remove('hidden');
            customInput.value = item.name;
        } else {
            nameSelect.value = '';
            customWrapper.classList.add('hidden');
            customInput.value = '';
        }
    }
    document.getElementById('itemCategory').value = item.category || '';
    document.getElementById('itemPrice').value = item.price || '';
    document.getElementById('itemStock').value = item.stock || '';
    document.getElementById('itemMinStock').value = item.min_stock || '';
    document.getElementById('itemUnit').value = item.unit || '';
    document.getElementById('itemDescription').value = item.description || '';
    document.getElementById('itemModal').classList.remove('hidden');
}

// View Item
function viewItem(item) {
    const categoryNames = {
        'stationery': 'স্টেশনারি',
        'furniture': 'আসবাবপত্র',
        'electronics': 'ইলেকট্রনিক্স',
        'sports': 'খেলাধুলা',
        'others': 'অন্যান্য'
    };
    
    const unitNames = {
        'piece': 'পিস',
        'box': 'বক্স',
        'packet': 'প্যাকেট',
        'set': 'সেট',
        'kg': 'কেজি'
    };
    
    const statusNames = {
        'in_stock': 'স্টকে আছে',
        'low_stock': 'কম স্টক',
        'out_of_stock': 'স্টক শেষ'
    };
    
    const content = `
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">আইটেম কোড</p>
                <p class="text-lg font-bold text-gray-900">${item.item_code || 'N/A'}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">নাম</p>
                <p class="text-lg font-bold text-gray-900">${item.name || 'N/A'}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">ক্যাটাগরি</p>
                <p class="text-lg font-bold text-gray-900">${item.category_name || categoryNames[item.category] || 'N/A'}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">মূল্য</p>
                <p class="text-lg font-bold text-gray-900">৳ ${Number(item.price || 0).toLocaleString()}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">স্টক</p>
                <p class="text-lg font-bold text-gray-900">${item.stock || 0} ${item.unit_name || unitNames[item.unit] || ''}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">মিনিমাম স্টক</p>
                <p class="text-lg font-bold text-gray-900">${item.min_stock || 0}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                <p class="text-sm text-gray-600 mb-1">স্ট্যাটাস</p>
                <p class="text-lg font-bold text-gray-900">${item.status_name || statusNames[item.status] || 'N/A'}</p>
            </div>
            ${item.description ? `
            <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                <p class="text-sm text-gray-600 mb-1">বিবরণ</p>
                <p class="text-gray-900">${item.description}</p>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').classList.remove('hidden');
}

// Close Modals
function closeModal() {
    document.getElementById('itemModal').classList.add('hidden');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentItemId = null;
}

// Confirm Delete
function confirmDelete(id) {
    currentItemId = Number(id);
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Save Item (Add/Edit)
async function saveItem(event) {
    event.preventDefault();
    const formElement = event.target;
    const idField = document.getElementById('itemId');
    const itemId = idField ? idField.value : '';
    const nameSelect = formElement.name;
    let itemName = nameSelect ? nameSelect.value : '';
    if (itemName === '__custom') {
        const customInput = document.getElementById('customItemName');
        const customValue = customInput ? customInput.value.trim() : '';
        if (!customValue) {
            alert('অনুগ্রহ করে আইটেমের নাম লিখুন');
            if (customInput) customInput.focus();
            return;
        }
        itemName = customValue;
    }
    const priceValue = normalizeNumberInput(formElement.price.value);
    const stockValue = normalizeNumberInput(formElement.stock.value);
    const minStockValue = normalizeNumberInput(formElement.min_stock.value);
    const data = {
        name: itemName,
        category: formElement.category.value,
        price: priceValue !== '' ? parseFloat(priceValue) : null,
        stock: stockValue !== '' ? parseInt(stockValue) : null,
        min_stock: minStockValue !== '' ? parseInt(minStockValue) : null,
        unit: formElement.unit.value,
        description: formElement.description.value.trim()
    };
    
    if (isEditMode && itemId) {
        data.id = itemId;
    }
    
    if (data.min_stock === null) delete data.min_stock;
    if (!data.description) delete data.description;
    
    console.log('Sending data:', data);
    
    const url = isEditMode && itemId 
        ? `{{ route('tenant.inventory.index') }}/${itemId}` 
        : '{{ route("tenant.inventory.store") }}';
    
    const method = isEditMode && itemId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            // Handle validation errors
            if (response.status === 422 && result.errors) {
                let errorMsg = 'Validation Errors:\n';
                for (let field in result.errors) {
                    errorMsg += `- ${field}: ${result.errors[field].join(', ')}\n`;
                }
                alert(errorMsg);
                return;
            }
            throw new Error(result.message || `HTTP error! status: ${response.status}`);
        }
        
        if (result.success) {
            closeModal();
            showInventorySuccess(result.message);
        } else {
            alert(result.message || 'একটি ত্রুটি ঘটেছে!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('একটি ত্রুটি ঘটেছে: ' + error.message);
    }
}

// Delete Item
async function deleteItem() {
    if (!currentItemId) return;
    
    try {
        const response = await fetch(`{{ route('tenant.inventory.index') }}/${currentItemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeDeleteModal();
            location.reload();
        } else {
            alert('একটি ত্রুটি ঘটেছে!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('একটি ত্রুটি ঘটেছে: ' + error.message);
    }
}

// Filter Items
function filterItems() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('.inventory-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const category = row.getAttribute('data-category');
        const status = row.getAttribute('data-status');
        
        const matchesSearch = name.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesCategory && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('searchResults').textContent = 
        visibleCount > 0 ? `${visibleCount} টি আইটেম পাওয়া গেছে` : 'কোনো আইটেম পাওয়া যায়নি';
}

// Reset Filters
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterItems();
}
</script>

<style>
.delete-modal {
    display: none;
    position: fixed;
    z-index: 50;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.delete-modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border-radius: 1.5rem;
    width: 90%;
    max-width: 400px;
}
</style>
@endsection

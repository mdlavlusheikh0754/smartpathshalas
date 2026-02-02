@extends('layouts.admin')

@section('title', 'সকল স্কুলের তালিকা')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">স্কুল ব্যবস্থাপনা</h1>
        <p class="text-gray-600">সিস্টেমে নিবন্ধিত সকল স্কুলের তালিকা এবং ব্যবস্থাপনা</p>
    </div>

    <!-- Search and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="স্কুল খুঁজুন...">
                </div>
            </div>
            <button onclick="openAddSchoolModal()" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                নতুন স্কুল যোগ করুন
            </button>
        </div>
    </div>

    <!-- Schools Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">সকল স্কুলের তালিকা</h2>
            <p class="text-sm text-gray-500 mt-1">মোট {{ $tenants->total() }} টি স্কুল</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ক্রমিক</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">স্কুলের নাম</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ডোমেইন</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাডমিন ইমেইল</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তৈরির তারিখ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody id="schoolsTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($tenants as $index => $tenant)
                        <tr class="hover:bg-gray-50 hover:shadow-sm hover:-translate-y-px transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tenants->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr($tenant->id, 0, 2)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $tenant->id }}</div>
                                        <div class="text-[10px] text-gray-400">ID: {{ $tenant->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($tenant->domains as $domain)
                                    <a href="http://{{ $domain->domain }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-900 font-medium hover:underline decoration-2 underline-offset-4 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        {{ $domain->domain }}
                                    </a>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $tenant->data['email'] ?? 'N/A' }}
                                    </div>
                                    @if(isset($tenant->data['password']))
                                        <button onclick="copyCredentials('{{ $tenant->data['email'] }}', '{{ $tenant->data['password'] }}')" class="ml-2 p-1 text-gray-400 hover:text-indigo-600 transition-colors" title="ক্রেডেনশিয়ালস কপি করুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $tenant->created_at->format('d M, Y') }}
                                <div class="text-xs">{{ $tenant->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    সক্রিয়
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Button -->
                                    <a href="/admin/schools/{{ $tenant->id }}/view" class="inline-flex items-center p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200" title="বিস্তারিত দেখুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <button onclick="editSchool('{{ $tenant->id }}')" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-all duration-200" title="সম্পাদনা করুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Access Button -->
                                    <a href="http://{{ $tenant->domains->first()->domain ?? '#' }}" target="_blank" class="inline-flex items-center gap-1 p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-all duration-200" title="স্কুলে প্রবেশ করুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- View Button -->
                                    <a href="{{ route('central.schools.view', $tenant->id) }}" class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200" title="বিস্তারিত দেখুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="deleteSchool(this, '{{ $tenant->id }}')" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200" title="ডিলিট করুন">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p class="mt-2">কোনো স্কুল পাওয়া যায়নি</p>
                                    <p class="text-sm">নতুন স্কুল যোগ করতে "নতুন স্কুয় যোগ করুন" বাটনে ক্লিক করুন</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tenants->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add School Modal -->
<div id="addSchoolModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform transition-all scale-95">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-1">নতুন স্কুল যোগ করুন</h3>
                    <p class="text-indigo-100 text-sm">নতুন স্কুলের তথ্য দিয়ে সিস্টেমে যোগ করুন</p>
                </div>
                <button onclick="closeAddSchoolModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-lg transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="addSchoolForm" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">স্কুলের নাম *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <input type="text" name="school_name" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="স্কুলের নাম লিখুন">
                    </div>
                </div>

                <!-- Domain Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ডোমেইন নাম *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <input type="text" name="domain" required class="w-full pl-10 pr-32 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="school-name">
                        <span class="absolute right-3 top-3 text-sm text-gray-500 font-medium">.smartpathshala.test</span>
                    </div>
                </div>

                <!-- School ID -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">স্কুল আইডি *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                        </div>
                        <input type="text" name="school_id" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="unique-school-id">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ইমেইল *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="email" name="email" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="admin@school.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">অ্যাডমিন পাসওয়ার্ড *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" required minlength="6" class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="passwordToggle" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">সর্বনিম্ন ৬ অক্ষরের পাসওয়ার্ড</p>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ফোন নম্বর *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input type="tel" name="phone" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="01XXXXXXXXX">
                    </div>
                </div>

                <!-- Principal Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">প্রধান শিক্ষকের নাম *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" name="principal_name" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="প্রধান শিক্ষকের নাম">
                    </div>
                </div>

                <!-- Capacity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ছাত্র ধারণক্ষমতা *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <input type="number" name="capacity" required min="1" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="500">
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">ঠিকানা *</label>
                <div class="relative">
                    <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <textarea name="address" required rows="3" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="স্কুলের সম্পূর্ণ ঠিকানা লিখুন"></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeAddSchoolModal()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                    বাতিল করুন
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    স্কুল যোগ করুন
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddSchoolModal() {
    const modal = document.getElementById('addSchoolModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // যদি আপনার মডাল ফ্লেক্স ব্যবহার করে
    } else {
        console.error("মডাল আইডি খুঁজে পাওয়া যায়নি!");
    }
}

function closeAddSchoolModal() {
    const modal = document.getElementById('addSchoolModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Form submission
document.getElementById('addSchoolForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'যোগ করা হচ্ছে...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('/admin/api/schools', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        // Check if response is HTML (login page or error page)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('text/html')) {
            const text = await response.text();
            console.error('HTML response (likely login page):', text.substring(0, 200));
            showNotification('আপনি লগইন করেননি বা সেশন শেষ হয়ে গেছে। দয়া করে আবার লগইন করুন।', 'error');
            // Redirect to login after 2 seconds
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }
        
        // Check if response is not JSON
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response:', text);
            showNotification('সার্ভার থেকে ভুল রেসপন্স এসেছে। দয়া করে লগইন চেক করুন।', 'error');
            return;
        }
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(result.message, 'success');
            closeAddSchoolModal();
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Show detailed validation errors or server errors
            if (result.errors) {
                let errorMessage = result.message + '\n\n';
                for (const [field, errors] of Object.entries(result.errors)) {
                    errorMessage += `${field}: ${errors.join(', ')}\n`;
                }
                showNotification(errorMessage, 'error');
            } else if (result.debug_info) {
                // Show debug info for 500 errors
                let errorMessage = result.message + '\n\n';
                errorMessage += `File: ${result.debug_info.file}\n`;
                errorMessage += `Line: ${result.debug_info.line}\n`;
                if (result.debug_info.trace && result.debug_info.trace.length > 0) {
                    errorMessage += `Error in: ${result.debug_info.trace[0].function || 'Unknown function'}`;
                }
                showNotification(errorMessage, 'error');
                console.error('Full error details:', result);
            } else {
                showNotification(result.message || 'স্কুল যোগ করতে সমস্যা হয়েছে', 'error');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('সার্ভারে সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

async function deleteSchool(button, schoolId) {
    if (!confirm('আপনি কি নিশ্চিত যে এই স্কুলটি ডিলিট করতে চান?')) {
        return;
    }
    
    const row = button.closest('tr');
    
    try {
        const response = await fetch(`/admin/api/schools/${schoolId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        // Check if response is HTML (login page or error page)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('text/html')) {
            const text = await response.text();
            console.error('HTML response (likely login page):', text.substring(0, 200));
            showNotification('আপনি লগইন করেননি বা সেশন শেষ হয়ে গেছে। দয়া করে আবার লগইন করুন।', 'error');
            // Redirect to login after 2 seconds
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }
        
        // Check if response is not JSON
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response:', text);
            showNotification('সার্ভার থেকে ভুল রেসপন্স এসেছে। দয়া করে লগইন চেক করুন।', 'error');
            return;
        }
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            row.remove();
            showNotification(result.message, 'success');
        } else {
            showNotification(result.message || 'ডিলিট করতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('সার্ভারে সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন', 'error');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('schoolsTableBody');
    const rows = tableBody.getElementsByTagName('tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});

// Password toggle function
function togglePassword() {
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordToggle = document.getElementById('passwordToggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
        `;
    } else {
        passwordInput.type = 'password';
        passwordToggle.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

// Copy credentials function
function copyCredentials(email, password) {
    const text = `Email: ${email}\nPassword: ${password}`;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('ক্রেডেনশিয়ালস ক্লিপবোর্ডে কপি হয়েছে!', 'success');
        }).catch(() => {
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showNotification('ক্রেডেনশিয়ালস ক্লিপবোর্ডে কপি হয়েছে!', 'success');
    } catch (err) {
        showNotification('কপি করতে সমস্যা হয়েছে', 'error');
    }
    
    document.body.removeChild(textArea);
}

// View school details
function viewSchool(event, schoolId) {
    console.log('Viewing school:', schoolId); // Debug log
    
    // Prevent default behavior
    event.preventDefault();
    event.stopPropagation();
    
    fetch(`/admin/api/schools/${schoolId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        console.log('API Response:', result); // Debug log
        if (result.success) {
            showSchoolDetails(result.data);
        } else {
            showNotification(result.message || 'স্কুলের তথ্য পেতে সমস্যা হয়েছে', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('সার্ভারে সমস্যা হয়েছে', 'error');
    });
}

// Edit school
async function editSchool(schoolId) {
    try {
        const response = await fetch(`/admin/api/schools/${schoolId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const school = await response.json();
            showEditSchoolModal(school);
        } else {
            showNotification('স্কুলের তথ্য পেতে সমস্যা হয়েছে', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('সার্ভারে সমস্যা হয়েছে', 'error');
    }
}

// Show school details modal
function showSchoolDetails(school) {
    console.log('School data:', school); // Debug log
    
    // Safety checks
    if (!school) {
        showNotification('স্কুলের তথ্য পাওয়া যায়নি', 'error');
        return;
    }
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white">স্কুলের বিস্তারিত তথ্য</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">স্কুলের নাম</label>
                        <p class="text-lg font-medium text-gray-900">${(school.data && school.data.school_name) ? school.data.school_name : 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">স্কুল আইডি</label>
                        <p class="text-lg font-medium text-gray-900">${school.id || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">ডোমেইন</label>
                        <p class="text-lg font-medium text-blue-600">${(school.domains && school.domains[0]) ? school.domains[0].domain : 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">অ্যাডমিন ইমেইল</label>
                        <p class="text-lg font-medium text-gray-900">${(school.data && school.data.email) ? school.data.email : 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">ফোন নম্বর</label>
                        <p class="text-lg font-medium text-gray-900">${(school.data && school.data.phone) ? school.data.phone : 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">প্রধান শিক্ষক</label>
                        <p class="text-lg font-medium text-gray-900">${(school.data && school.data.principal_name) ? school.data.principal_name : 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">ছাত্র ধারণক্ষমতা</label>
                        <p class="text-lg font-medium text-gray-900">${(school.data && school.data.capacity) ? school.data.capacity : 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">তৈরির তারিখ</label>
                        <p class="text-lg font-medium text-gray-900">${school.created_at || 'N/A'}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="text-sm font-semibold text-gray-600">ঠিকানা</label>
                    <p class="text-lg font-medium text-gray-900">${(school.data && school.data.address) ? school.data.address : 'N/A'}</p>
                </div>
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="http://${(school.domains && school.domains[0]) ? school.domains[0].domain : '#'}" target="_blank" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        স্কুলে প্রবেশ করুন
                    </a>
                    <button onclick="this.closest('.fixed').remove()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Show edit school modal
function showEditSchoolModal(school) {
    console.log('Edit school data:', school); // Debug log
    
    // Safety checks
    if (!school) {
        showNotification('স্কুলের তথ্য পাওয়া যায়নি', 'error');
        return;
    }
    
    // Extract data safely
    const schoolData = school.data || {};
    const domain = school.domains && school.domains[0] ? school.domains[0].domain : '';
    
    console.log('Extracted data:', {
        schoolName: schoolData.school_name,
        email: schoolData.email,
        phone: schoolData.phone,
        principal: schoolData.principal_name,
        capacity: schoolData.capacity,
        address: schoolData.address,
        domain: domain
    });
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white">স্কুলের তথ্য সম্পাদনা</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="editSchoolForm" class="p-8">
                <input type="hidden" name="school_id" value="${school.id}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">স্কুলের নাম *</label>
                        <input type="text" name="school_name" required value="${schoolData.school_name || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ইমেইল *</label>
                        <input type="email" name="email" required value="${schoolData.email || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ফোন নম্বর *</label>
                        <input type="tel" name="phone" required value="${schoolData.phone || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Principal Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">প্রধান শিক্ষকের নাম *</label>
                        <input type="text" name="principal_name" required value="${schoolData.principal_name || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ছাত্র ধারণক্ষমতা *</label>
                        <input type="number" name="capacity" required min="1" value="${schoolData.capacity || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Domain (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ডোমেইন</label>
                        <input type="text" value="${domain}" readonly class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600">
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ঠিকানা *</label>
                    <textarea name="address" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">${schoolData.address || ''}</textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="this.closest('.fixed').remove()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add form submit handler
    document.getElementById('editSchoolForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        const schoolId = data.school_id;
        delete data.school_id;
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'আপডেট হচ্ছে...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(`/admin/api/schools/${schoolId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                showNotification(result.message, 'success');
                this.closest('.fixed').remove();
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showNotification(result.message || 'আপডেট করতে সমস্যা হয়েছে', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('সার্ভারে সমস্যা হয়েছে', 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });
}

// Make functions globally available
window.openAddSchoolModal = openAddSchoolModal;
window.closeAddSchoolModal = closeAddSchoolModal;
window.togglePassword = togglePassword;
window.copyCredentials = copyCredentials;
window.viewSchool = viewSchool;
window.editSchool = editSchool;
window.showEditSchoolModal = showEditSchoolModal;
</script>
@endpush

@endsection

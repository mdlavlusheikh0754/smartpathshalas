@extends('layouts.admin')

@section('title', 'অ্যাডমিন ড্যাশবোর্ড')

@section('content')
<div class="p-8 min-h-screen bg-gray-50">
    
    <!-- Welcome Banner -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-4xl font-bold mb-2">স্বাগতম, {{ auth()->user()->name }}!</h2>
            <p class="text-blue-100 text-lg">আপনার স্কুল ম্যানেজমেন্ট সিস্টেম সম্পূর্ণভাবে নিয়ন্ত্রণ করুন</p>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        
        <!-- Total Schools Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">মোট স্কুল</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">{{ $tenantCount }}</h3>
                <p class="text-blue-100 text-sm font-medium">সক্রিয় প্রতিষ্ঠান</p>
            </div>
        </div>

        <!-- Active Schools Card -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">সক্রিয়</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">{{ $tenants->count() }}</h3>
                <p class="text-green-100 text-sm font-medium">চলমান সেবা</p>
            </div>
        </div>

        <!-- New This Month Card -->
        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">নতুন</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">{{ $tenants->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                <p class="text-orange-100 text-sm font-medium">এই মাসে নতুন</p>
            </div>
        </div>

        <!-- Total Domains Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl shadow-xl p-6 text-white transform hover:scale-105 hover:-translate-y-2 transition-all duration-300">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">মোট</span>
                </div>
                <h3 class="text-5xl font-bold mb-2">{{ $tenants->sum(fn($t) => $t->domains->count()) }}</h3>
                <p class="text-purple-100 text-sm font-medium">সংযুক্ত ডোমেইন</p>
            </div>
        </div>
    </div>

    <!-- Schools List Table -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">
        
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">সকল স্কুলের তালিকা</h3>
                    <p class="text-indigo-100 text-xs opacity-90">সিস্টেমে নিবন্ধিত মোট {{ $tenantCount }} টি স্কুল পরিচালনা করুন</p>
                </div>
                <a href="{{ route('central.schools') }}" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white text-white hover:text-indigo-600 font-bold px-6 py-3 rounded-2xl transition-all duration-300 backdrop-blur-md border border-white/30">
                    <span>সব দেখুন</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">স্কুলের নাম</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">ডোমেইন</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">অ্যাডমিন ইমেইল</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">তৈরির তারিখ</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($tenants->take(10) as $tenant)
                        <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 h-14 w-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($tenant->id, 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $tenant->id }}</div>
                                        <div class="text-xs text-gray-500 font-medium">ID: {{ $tenant->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @foreach($tenant->domains as $domain)
                                    <a href="http://{{ $domain->domain }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        {{ $domain->domain }}
                                    </a>
                                @endforeach
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $tenant->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $tenant->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    সক্রিয়
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="printSchoolInfo('{{ $tenant->id }}')" class="p-2.5 text-purple-600 hover:text-purple-700 hover:bg-purple-50 rounded-lg transition-all duration-200" title="প্রিন্ট">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-2.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200" title="বিস্তারিত">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-2.5 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-all duration-200" title="এডিট">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-2.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200" title="ডিলিট">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 text-lg font-semibold mb-1">কোনো স্কুল পাওয়া যায়নি</p>
                                    <p class="text-slate-400 text-sm">নতুন স্কুল যোগ করুন</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        @if($tenants->count() > 10)
            <div class="bg-slate-50 px-6 py-5 border-t border-gray-200">
                <div class="text-center">
                    <a href="{{ route('central.schools') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-slate-700 to-slate-900 hover:from-slate-800 hover:to-black text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <span>আরও {{ $tenants->count() - 10 }}টি স্কুল দেখুন</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function printSchoolInfo(tenantId) {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get school data from the table
    const row = event.target.closest('tr');
    const schoolName = row.querySelector('td:nth-child(1) .text-sm').textContent;
    const domain = row.querySelector('td:nth-child(2) a').textContent.trim();
    const createdDate = row.querySelector('td:nth-child(4) .text-sm').textContent;
    
    // Create print content
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>স্কুল তথ্য - ${schoolName}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; }
                .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #667eea; padding-bottom: 20px; }
                .header h1 { color: #667eea; font-size: 32px; margin-bottom: 10px; }
                .header p { color: #666; font-size: 14px; }
                .info-section { margin: 30px 0; }
                .info-row { display: flex; padding: 15px; border-bottom: 1px solid #eee; }
                .info-label { font-weight: bold; width: 200px; color: #333; }
                .info-value { color: #666; flex: 1; }
                .footer { margin-top: 50px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
                @media print {
                    body { padding: 20px; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>স্মার্ট পাঠশালা</h1>
                <p>স্কুল ম্যানেজমেন্ট সিস্টেম</p>
            </div>
            
            <div class="info-section">
                <h2 style="color: #667eea; margin-bottom: 20px; font-size: 24px;">স্কুল তথ্য</h2>
                <div class="info-row">
                    <div class="info-label">স্কুলের নাম:</div>
                    <div class="info-value">${schoolName}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">স্কুল আইডি:</div>
                    <div class="info-value">${tenantId}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">ডোমেইন:</div>
                    <div class="info-value">${domain}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">তৈরির তারিখ:</div>
                    <div class="info-value">${createdDate}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">স্ট্যাটাস:</div>
                    <div class="info-value">সক্রিয়</div>
                </div>
            </div>
            
            <div class="footer">
                <p>প্রিন্ট করার তারিখ: ${new Date().toLocaleDateString('bn-BD')}</p>
                <p>© ${new Date().getFullYear()} স্মার্ট পাঠশালা - সর্বস্বত্ব সংরক্ষিত</p>
            </div>
            
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() {
                        window.close();
                    }
                }
            <\/script>
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
}
</script>
@endpush
@endsection

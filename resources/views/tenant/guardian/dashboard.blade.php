@extends('tenant.layouts.portal')

@section('page_title', 'অভিভাবক ড্যাশবোর্ড')

@section('content')
<div class="p-8 space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">স্বাগতম, {{ $guardian->name }}!</h1>
            <p class="text-slate-500 font-medium mt-1">আপনার সন্তানদের শিক্ষা কার্যক্রমের সংক্ষিপ্ত বিবরণ একনজরে দেখে নিন।</p>
        </div>
        <div class="flex items-center space-x-3 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="pr-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">আজকের তারিখ</p>
                <p class="text-sm font-bold text-slate-700">{{ toBengaliDate(now()) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students Count -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">মোট সন্তান</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ toBengaliNumber($guardian->students->count()) }} জন</h3>
                </div>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Average Attendance -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">গড় উপস্থিতি</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ toBengaliNumber(92) }}%</h3>
                </div>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 92%"></div>
            </div>
        </div>

        <!-- Pending Fees -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">বকেয়া ফি</p>
                    <h3 class="text-2xl font-black text-slate-800">৳{{ toBengaliNumber(0) }}</h3>
                </div>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <!-- Recent Notices -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">নতুন নোটিশ</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ toBengaliNumber($notices->count()) }} টি</h3>
                </div>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Students) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-sm italic font-serif">S</span>
                    আমার সন্তানরা
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($guardian->students as $student)
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-6 relative overflow-hidden group hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-[5rem] -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
                    
                    <div class="relative flex items-center space-x-4 mb-8">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-3xl overflow-hidden border-4 border-white shadow-lg">
                                <img src="{{ $student->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=6366f1&color=fff' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 text-white rounded-lg flex items-center justify-center border-2 border-white shadow-sm">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-slate-800 leading-tight">{{ $student->name }}</h4>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">ID: {{ toBengaliNumber($student->student_id) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">শ্রেণি</p>
                            <p class="text-base font-black text-slate-700">{{ $student->class }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">রোল নম্বর</p>
                            <p class="text-base font-black text-slate-700">{{ toBengaliNumber($student->roll) }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="#" class="flex items-center justify-center space-x-2 w-full py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all group/btn">
                            <i class="fas fa-id-card group-hover/btn:scale-110 transition-transform"></i>
                            <span>প্রোফাইল দেখুন</span>
                        </a>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="#" class="flex items-center justify-center space-x-2 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                                <i class="fas fa-calendar-alt"></i>
                                <span>উপস্থিতি</span>
                            </a>
                            <a href="#" class="flex items-center justify-center space-x-2 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                                <i class="fas fa-graduation-cap"></i>
                                <span>ফলাফল</span>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-2 py-12 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                        <i class="fas fa-user-slash text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800">কোনো শিক্ষার্থী পাওয়া যায়নি</h3>
                    <p class="text-sm text-slate-500 mt-2 px-12">আপনার অভিভাবক প্রোফাইলের সাথে কোনো শিক্ষার্থী যুক্ত করা হয়নি। স্কুলের সঙ্গে যোগাযোগ করুন।</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar (Notices & Actions) -->
        <div class="space-y-8">
            <!-- Notifications -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-black text-slate-800">সর্বশেষ নোটিফিকেশন</h2>
                    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-bold uppercase tracking-wider">নতুন</span>
                </div>
                <div class="space-y-4">
                    @forelse($guardian->notifications as $notification)
                    <div class="relative pl-6 pb-4 border-l-2 border-slate-100 last:pb-0 last:border-l-0">
                        <div class="absolute left-[-9px] top-0 w-4 h-4 rounded-full bg-white border-4 {{ $notification->read_at ? 'border-slate-200' : 'border-indigo-600' }}"></div>
                        <div>
                            <h5 class="text-sm font-bold text-slate-700 leading-tight">{{ $notification->title }}</h5>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $notification->message }}</p>
                            <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter italic">
                                <i class="far fa-clock mr-1"></i>{{ toBengaliNumber($notification->created_at->diffForHumans()) }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-2">
                            <i class="fas fa-bell-slash text-base"></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400">কোনো নতুন নোটিফিকেশন নেই</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Notices -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-black text-slate-800">সাম্প্রতিক নোটিশ</h2>
                    <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors uppercase tracking-wider">সবগুলো</a>
                </div>
                <div class="space-y-1">
                    @forelse($notices as $notice)
                    <a href="#" class="group block p-3 rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex flex-col items-center justify-center flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <span class="text-[10px] font-bold uppercase">{{ $notice->created_at->format('M') }}</span>
                                <span class="text-lg font-black leading-none">{{ toBengaliNumber($notice->created_at->format('d')) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-sm font-bold text-slate-700 truncate group-hover:text-indigo-600 transition-colors">{{ $notice->title }}</h5>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-1 leading-relaxed">{{ Str::limit(strip_tags($notice->content), 60) }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-10">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-3">
                            <i class="fas fa-inbox text-xl"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-400">নতুন কোনো নোটিশ নেই</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-[2.5rem] p-8 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-700"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-tr-full -ml-12 -mb-12 group-hover:scale-110 transition-transform duration-500"></div>
                
                <h2 class="text-lg font-black mb-6 relative z-10">কুইক অ্যাকশন</h2>
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <a href="{{ route('tenant.complaints.create') }}" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm p-5 rounded-3xl text-center transition-all group/item border border-white/10 hover:border-white/20">
                        <i class="fas fa-comment-dots text-2xl mb-3 block group-hover/item:scale-110 transition-transform text-white"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white/80 group-hover/item:text-white transition-colors">অভিযোগ</span>
                    </a>
                    <a href="#" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm p-5 rounded-3xl text-center transition-all group/item border border-white/10 hover:border-white/20">
                        <i class="fas fa-file-invoice-dollar text-2xl mb-3 block group-hover/item:scale-110 transition-transform text-white"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white/80 group-hover/item:text-white transition-colors">পেমেন্ট</span>
                    </a>
                    <a href="#" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm p-5 rounded-3xl text-center transition-all group/item border border-white/10 hover:border-white/20">
                        <i class="fas fa-calendar-day text-2xl mb-3 block group-hover/item:scale-110 transition-transform text-white"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white/80 group-hover/item:text-white transition-colors">রুটিন</span>
                    </a>
                    <a href="#" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm p-5 rounded-3xl text-center transition-all group/item border border-white/10 hover:border-white/20">
                        <i class="fas fa-headset text-2xl mb-3 block group-hover/item:scale-110 transition-transform text-white"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white/80 group-hover/item:text-white transition-colors">সাপোর্ট</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

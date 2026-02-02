@extends('tenant.layouts.web')

@section('title', 'ভর্তি সংক্রান্ত নিয়মাবলি')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">ভর্তি সংক্রান্ত নিয়মাবলি</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">ভর্তির যোগ্যতা ও শর্তাবলী সম্পর্কে বিস্তারিত তথ্য</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Important Dates (Highlighted) -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-calendar-alt text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">গুরুত্বপূর্ণ সময়সূচী</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100 text-center hover:shadow-lg transition-all duration-300">
                        <span class="block text-sm text-gray-600 mb-2 font-medium">আবেদন শুরু</span>
                        <span class="block text-2xl font-bold text-indigo-600">{{ $websiteSettings->admission_start_date ?? '১ নভেম্বর' }}</span>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 p-5 rounded-xl border border-red-100 text-center hover:shadow-lg transition-all duration-300">
                        <span class="block text-sm text-gray-600 mb-2 font-medium">আবেদনের শেষ</span>
                        <span class="block text-2xl font-bold text-red-600">{{ $websiteSettings->admission_end_date ?? '২৫ ডিসেম্বর' }}</span>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-5 rounded-xl border border-purple-100 text-center hover:shadow-lg transition-all duration-300">
                        <span class="block text-sm text-gray-600 mb-2 font-medium">ভর্তি পরীক্ষা</span>
                        <span class="block text-2xl font-bold text-purple-600">{{ $websiteSettings->admission_exam_date ?? '২৮ ডিসেম্বর' }}</span>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-xl border border-green-100 text-center hover:shadow-lg transition-all duration-300">
                        <span class="block text-sm text-gray-600 mb-2 font-medium">ক্লাস শুরু</span>
                        <span class="block text-2xl font-bold text-green-600">{{ $websiteSettings->class_start_date ?? '১ জানুয়ারি' }}</span>
                    </div>
                </div>
            </div>

            <!-- Introduction -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-info-circle text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ভর্তির সাধারণ তথ্য</h2>
                </div>
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100">
                    <div class="text-gray-700 leading-relaxed space-y-6">
                        <!-- Welcome Message -->
                        <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-blue-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-3">ইকরা নূরানী একাডেমিতে আপনাকে স্বাগতম!</h3>
                            <p class="text-gray-700">আমরা শিক্ষার্থীদের মেধা ও মনন বিকাশে প্রতিশ্রুতিবদ্ধ একটি আধুনিক ইসলামিক শিক্ষা প্রতিষ্ঠান।</p>
                        </div>

                        <!-- Key Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Academic Year -->
                            <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                <div class="bg-blue-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-xl text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">শিক্ষাবর্ষ</h4>
                                    <p class="text-sm text-gray-600">প্রতি বছর জানুয়ারি মাসে নতুন শিক্ষাবর্ষ শুরু হয় এবং ডিসেম্বর মাসে সমাপ্ত হয়।</p>
                                </div>
                            </div>

                            <!-- Class Levels -->
                            <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                <div class="bg-green-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                    <i class="fas fa-layer-group text-xl text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">শ্রেণিসমূহ</h4>
                                    <p class="text-sm text-gray-600">প্লে-গ্রুপ থেকে দশম শ্রেণি পর্যন্ত সকল স্তরে ভর্তির সুবিধা রয়েছে।</p>
                                </div>
                            </div>

                            <!-- Merit Based -->
                            <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                <div class="bg-purple-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                    <i class="fas fa-medal text-xl text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">মেধাভিত্তিক নির্বাচন</h4>
                                    <p class="text-sm text-gray-600">ভর্তির ক্ষেত্রে মেধা ও যোগ্যতাকে সর্বোচ্চ অগ্রাধিকার দেওয়া হয়।</p>
                                </div>
                            </div>

                            <!-- Seat Availability -->
                            <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                <div class="bg-orange-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                    <i class="fas fa-chair text-xl text-orange-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">আসন সংখ্যা</h4>
                                    <p class="text-sm text-gray-600">প্রতিটি শ্রেণিতে সীমিত সংখ্যক আসন রয়েছে। আসন খালি থাকা সাপেক্ষে ভর্তি করা হয়।</p>
                                </div>
                            </div>
                        </div>

                        <!-- Mission Statement -->
                        <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-bullseye text-indigo-600 mr-3 text-xl"></i>
                                <h4 class="font-bold text-gray-900">আমাদের লক্ষ্য</h4>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                আমাদের প্রতিষ্ঠানের মূল লক্ষ্য হলো প্রতিটি শিক্ষার্থীকে একজন আদর্শ মুসলিম ও দক্ষ নাগরিক হিসেবে গড়ে তোলা। 
                                আমরা আধুনিক শিক্ষার পাশাপাশি ইসলামিক মূল্যবোধ ও নৈতিক চরিত্র গঠনে বিশেষ গুরুত্ব দিয়ে থাকি।
                            </p>
                        </div>

                        <!-- Special Features -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="bg-red-100 p-3 rounded-full inline-block mb-3">
                                    <i class="fas fa-heart text-red-600 text-lg"></i>
                                </div>
                                <h5 class="font-bold text-gray-900 text-sm mb-1">যত্নশীল পরিবেশ</h5>
                                <p class="text-xs text-gray-600">প্রতিটি শিক্ষার্থীর প্রতি ব্যক্তিগত যত্ন ও নজর</p>
                            </div>
                            
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="bg-green-100 p-3 rounded-full inline-block mb-3">
                                    <i class="fas fa-graduation-cap text-green-600 text-lg"></i>
                                </div>
                                <h5 class="font-bold text-gray-900 text-sm mb-1">মানসম্পন্ন শিক্ষা</h5>
                                <p class="text-xs text-gray-600">অভিজ্ঞ শিক্ষকমণ্ডলী দ্বারা উন্নত পাঠদান</p>
                            </div>
                            
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="bg-blue-100 p-3 rounded-full inline-block mb-3">
                                    <i class="fas fa-mosque text-blue-600 text-lg"></i>
                                </div>
                                <h5 class="font-bold text-gray-900 text-sm mb-1">ইসলামিক পরিবেশ</h5>
                                <p class="text-xs text-gray-600">ধর্মীয় মূল্যবোধ ও আদর্শ চর্চার পরিবেশ</p>
                            </div>
                        </div>

                        <!-- Contact for Information -->
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                                <h5 class="font-bold text-gray-900">আরো তথ্যের জন্য:</h5>
                            </div>
                            <p class="text-gray-700 text-sm">
                                ভর্তি সংক্রান্ত যেকোনো প্রশ্ন বা তথ্যের জন্য আমাদের অফিসে যোগাযোগ করুন অথবা 
                                <a href="{{ route('tenant.contact') }}" class="text-blue-600 hover:text-blue-800 font-semibold">যোগাযোগ পাতা</a> দেখুন।
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admission Requirements -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-clipboard-list text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ভর্তির নিয়মাবলি</h2>
                </div>
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-6 rounded-xl border border-emerald-100">
                    <p class="text-gray-700 font-semibold mb-6">ইকরা নূরানী একাডেমি-তে নতুন শিক্ষাবর্ষে শিক্ষার্থী ভর্তির ক্ষেত্রে নিম্নলিখিত নিয়মাবলি কঠোরভাবে অনুসরণ করা হয়:</p>
                    
                    <div class="space-y-6">
                        <!-- Rule 1 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-emerald-500">
                            <div class="bg-emerald-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-file-alt text-xl text-emerald-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ভর্তি ফরম সংগ্রহ ও জমা</h4>
                                <p class="text-gray-700 leading-relaxed">অভিভাবককে প্রতিষ্ঠানের অফিস থেকে নির্ধারিত মূল্যে ভর্তি ফরম সংগ্রহ করতে হবে। যথাযথভাবে পূরণকৃত ফরমটি নির্দিষ্ট সময়ের মধ্যে প্রয়োজনীয় কাগজপত্রসহ অফিসে জমা দিতে হবে।</p>
                            </div>
                        </div>

                        <!-- Rule 2 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-blue-500">
                            <div class="bg-blue-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-brain text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">মেধা যাচাই ও নির্বাচন</h4>
                                <div class="text-gray-700 leading-relaxed space-y-2">
                                    <p><strong>প্লে ও নার্সারি:</strong> শিশুদের সাধারণ মেধা ও মৌখিক সাক্ষাৎকারের মাধ্যমে শিক্ষার্থী নির্বাচন করা হয়।</p>
                                    <p><strong>অন্যান্য শ্রেণি:</strong> আসন খালি থাকা সাপেক্ষে পূর্ববর্তী শ্রেণির জ্ঞান যাচাইয়ের জন্য একটি সংক্ষিপ্ত লিখিত পরীক্ষা নেওয়া হতে পারে। পরীক্ষার তারিখ ও সময় ফরম জমা দেওয়ার সময় জানিয়ে দেওয়া হবে।</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rule 3 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-purple-500">
                            <div class="bg-purple-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-birthday-cake text-xl text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">বয়স ও যোগ্যতার শর্ত</h4>
                                <p class="text-gray-700 leading-relaxed">সরকারি নীতিমালা অনুযায়ী প্লে-গ্রুপে ভর্তির জন্য শিক্ষার্থীর বয়স ন্যূনতম ৪+ বছর হতে হবে। অন্যান্য শ্রেণিতে ভর্তির ক্ষেত্রে শিক্ষার্থীর বয়স ও মেধার সমন্বয় দেখা হবে।</p>
                            </div>
                        </div>

                        <!-- Rule 4 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-orange-500">
                            <div class="bg-orange-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-folder-open text-xl text-orange-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">প্রয়োজনীয় ডকুমেন্টস</h4>
                                <p class="text-gray-700 leading-relaxed mb-3">ভর্তির সময় অবশ্যই নিচের কাগজপত্রগুলো সঙ্গে আনতে হবে:</p>
                                <ul class="text-gray-700 space-y-1 text-sm">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> শিক্ষার্থীর অনলাইন জন্ম নিবন্ধন সনদের ফটোকপি</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> শিক্ষার্থীর ৩ কপি পাসপোর্ট সাইজের রঙিন ছবি</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> পিতা ও মাতার জাতীয় পরিচয়পত্রের (NID) ফটোকপি</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> পূর্ববর্তী প্রতিষ্ঠানের ছাড়পত্র বা মার্কশিট (যদি প্রযোজ্য হয়)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Rule 5 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
                            <div class="bg-green-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-money-check-alt text-xl text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ভর্তি নিশ্চিতকরণ ও ফি প্রদান</h4>
                                <p class="text-gray-700 leading-relaxed">মেধা তালিকায় নাম আসার পর নির্ধারিত সময়ের মধ্যে ভর্তি ফি এবং আনুষঙ্গিক অন্যান্য ফি জমা দিয়ে ভর্তি নিশ্চিত করতে হবে। নির্দিষ্ট সময়ের মধ্যে ফি জমা না দিলে ভর্তির আবেদন বাতিল বলে গণ্য হবে এবং অপেক্ষমাণ তালিকা থেকে শিক্ষার্থী নেওয়া হবে।</p>
                            </div>
                        </div>

                        <!-- Rule 6 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-red-500">
                            <div class="bg-red-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-gavel text-xl text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">চূড়ান্ত সিদ্ধান্ত</h4>
                                <p class="text-gray-700 leading-relaxed">ভর্তি সংক্রান্ত যেকোনো বিষয়ে প্রতিষ্ঠানের পরিচালনা পর্ষদ বা কর্তৃপক্ষের সিদ্ধান্তই চূড়ান্ত বলে গণ্য হবে।</p>
                            </div>
                        </div>
                    </div>

                    <!-- Important Note -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                            <h5 class="font-bold text-gray-900">গুরুত্বপূর্ণ নোট:</h5>
                        </div>
                        <p class="text-gray-700 text-sm">সকল নিয়মাবলি কঠোরভাবে অনুসরণ করা বাধ্যতামূলক। কোনো ধরনের অনিয়ম বা মিথ্যা তথ্য প্রদান করলে ভর্তি বাতিল করা হবে।</p>
                    </div>
                </div>
            </div>

            <!-- Fees and Costs -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-money-bill-wave text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ভর্তি ফি ও আনুষঙ্গিক খরচ</h2>
                </div>
                @if($feeStructures->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <th class="border-b-2 border-gray-300 px-4 py-4 text-left font-bold text-gray-800 text-sm uppercase tracking-wider">ক্লাস</th>
                                    <th class="border-b-2 border-gray-300 px-4 py-4 text-left font-bold text-gray-800 text-sm uppercase tracking-wider">ভর্তি ফি</th>
                                    <th class="border-b-2 border-gray-300 px-4 py-4 text-left font-bold text-gray-800 text-sm uppercase tracking-wider">সেশন ফি</th>
                                    <th class="border-b-2 border-gray-300 px-4 py-4 text-left font-bold text-gray-800 text-sm uppercase tracking-wider">মাসিক বেতন</th>
                                    <th class="border-b-2 border-gray-300 px-4 py-4 text-left font-bold text-gray-800 text-sm uppercase tracking-wider">অন্যান্য ফি</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $feesByClass = [];
                                    foreach($feeStructures as $fee) {
                                        if(!isset($feesByClass[$fee->class_name])) {
                                            $feesByClass[$fee->class_name] = [];
                                        }
                                        $feesByClass[$fee->class_name][$fee->fee_type] = $fee;
                                    }
                                @endphp
                                @foreach($feesByClass as $className => $fees)
                                    <tr class="hover:bg-blue-50 transition-colors">
                                        <td class="border-b border-gray-200 px-4 py-4 font-semibold text-gray-900">{{ $fees['admission']->class_name_bengali ?? $className }}</td>
                                        <td class="border-b border-gray-200 px-4 py-3 text-green-600 font-bold text-lg">{{ $fees['admission']->formatted_amount ?? '-' }}</td>
                                        <td class="border-b border-gray-200 px-4 py-3 text-green-600 font-bold text-lg">{{ $fees['session']->formatted_amount ?? '-' }}</td>
                                        <td class="border-b border-gray-200 px-4 py-3 text-green-600 font-bold text-lg">{{ $fees['monthly']->formatted_amount ?? '-' }}</td>
                                        <td class="border-b border-gray-200 px-4 py-3 text-sm text-gray-700">
                                            @if(isset($fees['exam']))
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">পরীক্ষা ফি: {{ $fees['exam']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['annual']))
                                                <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">বার্ষিক ফি: {{ $fees['annual']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['development']))
                                                <span class="inline-block bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">উন্নয়ন ফি: {{ $fees['development']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['computer']))
                                                <span class="inline-block bg-cyan-100 text-cyan-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">কম্পিউটার ফি: {{ $fees['computer']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['library']))
                                                <span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">লাইব্রেরি ফি: {{ $fees['library']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['sports']))
                                                <span class="inline-block bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">খেলাধুলা ফি: {{ $fees['sports']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['science_lab']))
                                                <span class="inline-block bg-teal-100 text-teal-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">বিজ্ঞানাগার ফি: {{ $fees['science_lab']->formatted_amount }}</span>
                                            @endif
                                            @if(isset($fees['transport']))
                                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">পরিবহন ফি: {{ $fees['transport']->formatted_amount }}</span>
                                            @endif
                                            @if(!isset($fees['exam']) && !isset($fees['annual']) && !isset($fees['development']) && !isset($fees['computer']) && !isset($fees['library']) && !isset($fees['sports']) && !isset($fees['science_lab']) && !isset($fees['transport']))
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="prose max-w-none text-gray-600">
                        <p>ভর্তি ফি সম্পর্কে তথ্য এখানে আপডেট করা হবে। অনুগ্রহ করে ওয়েবসাইট সেটিংসে ভর্তি ফি আপডেট করুন।</p>
                    </div>
                @endif
            </div>

            <!-- Process -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-cyan-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-tasks text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">ভর্তি প্রক্রিয়া</h2>
                </div>
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 p-6 rounded-xl border border-cyan-100">
                    <p class="text-gray-700 font-semibold mb-6">ইকরা নূরানী একাডেমি-তে নতুন শিক্ষার্থী ভর্তির ক্ষেত্রে আমরা একটি স্বচ্ছ ও সুশৃঙ্খল পদ্ধতি অনুসরণ করি। ভর্তি প্রক্রিয়াটি মূলত নিম্নলিখিত ৫টি ধাপে সম্পন্ন হয়:</p>
                    
                    <div class="space-y-6">
                        <!-- Step 1 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-blue-500">
                            <div class="bg-blue-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <span class="text-blue-600 font-bold text-lg">১</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">তথ্য সংগ্রহ ও ভর্তি ফরম</h4>
                                <p class="text-gray-700 leading-relaxed">অভিভাবকগণ প্রতিষ্ঠানের অফিস চলাকালীন সময়ে সরাসরি অফিস থেকে অথবা আমাদের অনলাইন পোর্টাল (যদি চালু থাকে) থেকে নির্ধারিত ফি দিয়ে ভর্তি ফরম সংগ্রহ করবেন।</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
                            <div class="bg-green-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <span class="text-green-600 font-bold text-lg">২</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ফরম জমা ও প্রয়োজনীয় কাগজপত্র</h4>
                                <p class="text-gray-700 leading-relaxed">যথাযথভাবে পূরণকৃত ভর্তি ফরমের সাথে শিক্ষার্থীর জন্ম নিবন্ধন, ছবি এবং পিতা-মাতার এনআইডি (NID)-র ফটোকপি সংযুক্ত করে অফিসে জমা দিতে হবে।</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-purple-500">
                            <div class="bg-purple-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <span class="text-purple-600 font-bold text-lg">৩</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">মেধা যাচাই ও সাক্ষাৎকার</h4>
                                <p class="text-gray-700 leading-relaxed">ফরম জমা দেওয়ার পর শিক্ষার্থীকে একটি সংক্ষিপ্ত মেধা যাচাই পরীক্ষায় অংশগ্রহণ করতে হবে। প্লে ও নার্সারি শ্রেণির জন্য মূলত সাধারণ মৌখিক সাক্ষাৎকার নেওয়া হয় এবং উচ্চতর শ্রেণির জন্য সংক্ষিপ্ত লিখিত পরীক্ষা গ্রহণ করা হয়।</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-orange-500">
                            <div class="bg-orange-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <span class="text-orange-600 font-bold text-lg">৪</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ফলাফল ও শিক্ষার্থী নির্বাচন</h4>
                                <p class="text-gray-700 leading-relaxed">মেধা যাচাই ও সাক্ষাৎকারের ভিত্তিতে চূড়ান্তভাবে নির্বাচিত শিক্ষার্থীদের তালিকা প্রতিষ্ঠানের নোটিশ বোর্ডে এবং (প্রযোজ্য ক্ষেত্রে) এসএমএস-এর মাধ্যমে অভিভাবককে জানিয়ে দেওয়া হবে।</p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-red-500">
                            <div class="bg-red-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <span class="text-red-600 font-bold text-lg">৫</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ফি প্রদান ও ভর্তি নিশ্চিতকরণ</h4>
                                <p class="text-gray-700 leading-relaxed">নির্বাচিত শিক্ষার্থীদের অভিভাবকগণ নির্দিষ্ট সময়ের মধ্যে ভর্তি ফি, সেশন ফি এবং অন্যান্য আনুষঙ্গিক ফি পরিশোধ করে ভর্তি প্রক্রিয়া সম্পন্ন করবেন। নির্ধারিত সময়ের মধ্যে ভর্তি না হলে আসনটি শূন্য ঘোষণা করা হবে।</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-phone-alt text-indigo-600 mr-2"></i>
                            <h5 class="font-bold text-gray-900">যোগাযোগ:</h5>
                        </div>
                        <p class="text-gray-700">ভর্তি সংক্রান্ত যেকোনো তথ্যের জন্য প্রতিদিন <strong>সকাল ৮:০০টা থেকে দুপুর ২:০০টার</strong> মধ্যে একাডেমি কার্যালয়ে সরাসরি যোগাযোগ করুন।</p>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-star text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">আমাদের বৈশিষ্ট্যসমূহ</h2>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-100">
                    <p class="text-gray-700 font-semibold mb-6">ইকরা নূরানী একাডেমি একটি আধুনিক ও মানসম্পন্ন শিক্ষা প্রতিষ্ঠান যা শিক্ষার্থীদের সর্বাঙ্গীণ উন্নয়নে প্রতিশ্রুতিবদ্ধ:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Feature 1 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-blue-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-chalkboard-teacher text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">অভিজ্ঞ শিক্ষকমণ্ডলী</h4>
                                <p class="text-sm text-gray-600">উচ্চ শিক্ষিত ও প্রশিক্ষণপ্রাপ্ত শিক্ষক দ্বারা মানসম্পন্ন পাঠদান</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-green-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-quran text-xl text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ইসলামিক শিক্ষা</h4>
                                <p class="text-sm text-gray-600">কুরআন-হাদিসের আলোকে নৈতিক চরিত্র গঠন ও ধর্মীয় শিক্ষা</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-purple-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-laptop-code text-xl text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">আধুনিক প্রযুক্তি</h4>
                                <p class="text-sm text-gray-600">কম্পিউটার ল্যাব ও ডিজিটাল ক্লাসরুমের মাধ্যমে আধুনিক শিক্ষা</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-orange-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-shield-alt text-xl text-orange-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">নিরাপদ পরিবেশ</h4>
                                <p class="text-sm text-gray-600">সিসিটিভি নিরাপত্তা ব্যবস্থা ও সম্পূর্ণ নিরাপদ ক্যাম্পাস</p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-red-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-microscope text-xl text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">বিজ্ঞান গবেষণাগার</h4>
                                <p class="text-sm text-gray-600">হাতে-কলমে শিক্ষার জন্য সুসজ্জিত বিজ্ঞান ল্যাবরেটরি</p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-cyan-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-book-reader text-xl text-cyan-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">সমৃদ্ধ লাইব্রেরি</h4>
                                <p class="text-sm text-gray-600">বিভিন্ন বিষয়ের হাজারো বই সমৃদ্ধ আধুনিক লাইব্রেরি</p>
                            </div>
                        </div>

                        <!-- Feature 7 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-yellow-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-running text-xl text-yellow-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">খেলাধুলা ও সাংস্কৃতিক</h4>
                                <p class="text-sm text-gray-600">শারীরিক ও মানসিক বিকাশের জন্য খেলাধুলা ও সাংস্কৃতিক কার্যক্রম</p>
                            </div>
                        </div>

                        <!-- Feature 8 -->
                        <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-indigo-100 p-3 rounded-xl mr-4 flex-shrink-0">
                                <i class="fas fa-user-friends text-xl text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">ব্যক্তিগত যত্ন</h4>
                                <p class="text-sm text-gray-600">প্রতিটি শিক্ষার্থীর ব্যক্তিগত উন্নয়নে বিশেষ নজর ও যত্ন</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-award text-purple-600 mr-2"></i>
                            <h5 class="font-bold text-gray-900">আমাদের লক্ষ্য:</h5>
                        </div>
                        <p class="text-gray-700">প্রতিটি শিক্ষার্থীকে একজন আদর্শ মুসলিম ও দক্ষ নাগরিক হিসেবে গড়ে তোলা এবং দুনিয়া ও আখিরাতের সফলতার জন্য প্রস্তুত করা।</p>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl shadow-xl border border-indigo-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-4 rounded-2xl mr-4 shadow-lg">
                        <i class="fas fa-thumbs-up text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">কেন আমাদের প্রতিষ্ঠানে ভর্তি করবেন?</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-blue-100 p-3 rounded-xl mr-4 flex-shrink-0">
                            <i class="fas fa-chalkboard-teacher text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">অভিজ্ঞ শিক্ষক মন্ডলী</h4>
                            <p class="text-sm text-gray-600">উচ্চ শিক্ষিত ও প্রশিক্ষণপ্রাপ্ত শিক্ষক দ্বারা পাঠদান</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-green-100 p-3 rounded-xl mr-4 flex-shrink-0">
                            <i class="fas fa-quran text-xl text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">ধর্মীয় ও নৈতিক শিক্ষা</h4>
                            <p class="text-sm text-gray-600">পুথিগত শিক্ষার পাশাপাশি নৈতিক চরিত্র গঠন</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-purple-100 p-3 rounded-xl mr-4 flex-shrink-0">
                            <i class="fas fa-laptop-code text-xl text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">আধুনিক কম্পিউটার ল্যাব</h4>
                            <p class="text-sm text-gray-600">শিক্ষার্থীদের জন্য আধুনিক তথ্যপ্রযুক্তি শিক্ষার ব্যবস্থা</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-orange-100 p-3 rounded-xl mr-4 flex-shrink-0">
                            <i class="fas fa-shield-alt text-xl text-orange-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">নিরাপদ পরিবেশ</h4>
                            <p class="text-sm text-gray-600">সিসিটিভি ক্যামেরা দ্বারা নিয়ন্ত্রিত সম্পূর্ণ নিরাপদ ক্যাম্পাস</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Apply Now CTA -->
            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-2xl p-8 text-center hover:shadow-3xl transition-all duration-300">
                <div class="bg-white bg-opacity-20 p-4 rounded-full inline-block mb-4">
                    <i class="fas fa-graduation-cap text-4xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">এখনই আবেদন করুন</h3>
                <p class="text-white opacity-90 mb-6">অনলাইনে খুব সহজেই ভর্তির আবেদন করতে পারবেন।</p>
                <a href="{{ route('tenant.admission.apply') }}" class="inline-block bg-white text-indigo-600 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                    আবেদন করুন
                </a>
            </div>

            <!-- Downloads -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-red-500 to-orange-600 p-3 rounded-xl mr-3 shadow-lg">
                        <i class="fas fa-download text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">ডাউনলোড কর্নার</h3>
                </div>
                <ul class="space-y-3">
                    @if($websiteSettings->admission_form_pdf)
                    <li>
                        <a href="{{ asset('storage/' . $websiteSettings->admission_form_pdf) }}" target="_blank" class="flex items-center text-gray-700 hover:text-indigo-600 transition-colors group p-3 bg-red-50 rounded-xl hover:bg-red-100">
                            <div class="bg-red-100 p-2 rounded-lg group-hover:bg-red-200 transition-colors mr-3">
                                <i class="fas fa-file-pdf text-red-600"></i>
                            </div>
                            <span class="font-medium">ভর্তি ফরম (পিডিএফ)</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('tenant.academic.syllabus') }}" class="flex items-center text-gray-700 hover:text-indigo-600 transition-colors group p-3 bg-green-50 rounded-xl hover:bg-green-100">
                            <div class="bg-green-100 p-2 rounded-lg group-hover:bg-green-200 transition-colors mr-3">
                                <i class="fas fa-book-open text-green-600"></i>
                            </div>
                            <span class="font-medium">পাঠ্যক্রম / সিলেবাস</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Help Contact -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl mr-3 shadow-lg">
                        <i class="fas fa-headset text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">সাহায্য প্রয়োজন?</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-blue-50 rounded-xl">
                        <i class="fas fa-phone-alt w-8 text-blue-600 mr-3"></i>
                        <span class="text-gray-700 font-medium">{{ $websiteSettings->phone ?? '০যকোন ফোন নম্বার নেই...' }}</span>
                    </div>
                    <div class="flex items-center p-3 bg-purple-50 rounded-xl">
                        <i class="fas fa-envelope w-8 text-purple-600 mr-3"></i>
                        <span class="text-gray-700 font-medium">{{ $websiteSettings->email ?? 'info@...' }}</span>
                    </div>
                    <div class="flex items-start p-3 bg-green-50 rounded-xl">
                        <i class="fas fa-map-marker-alt w-8 text-green-600 mr-3 mt-1"></i>
                        <span class="text-gray-700 font-medium">{{ $websiteSettings->address ?? 'ঠিকানা...' }}</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
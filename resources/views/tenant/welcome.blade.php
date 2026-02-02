@extends('tenant.layouts.web')

@section('title', 'হোম')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="relative h-screen overflow-hidden">
        <!-- Hero Slider -->
        <div class="hero-slider relative w-full h-full">
            @php
                $heroImages = [];
                
                // Add multiple hero images if uploaded (priority)
                $heroImagesData = $websiteSettings->getHeroImagesArray();
                if(count($heroImagesData) > 0) {
                    foreach($heroImagesData as $heroImage) {
                        $heroImages[] = tenant_asset($heroImage);
                    }
                } else {
                    // Fallback to single hero background image
                    if($websiteSettings->hero_bg) {
                        $heroImages[] = $websiteSettings->getImageUrl('hero_bg');
                    }
                    
                    // Add logo as hero image if uploaded (for backward compatibility)
                    if($websiteSettings->logo) {
                        $heroImages[] = $websiteSettings->getImageUrl('logo');
                    }
                    
                    // Add gallery images if available
                    if($websiteSettings->gallery_images && is_array($websiteSettings->gallery_images)) {
                        foreach($websiteSettings->gallery_images as $galleryImage) {
                            $heroImages[] = tenant_asset($galleryImage);
                        }
                    }
                }
                
                // Add some default images if no images uploaded
                if(empty($heroImages)) {
                    $heroImages = [
                        'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                        'https://images.unsplash.com/photo-1580582932707-520aed937b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                        'https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'
                    ];
                }
            @endphp
            
            <!-- Slider Container -->
            <div class="slider-container flex transition-transform duration-700 ease-in-out h-full" style="width: {{ count($heroImages) * 100 }}%; transform: translateX(0%);" id="sliderContainer">
                @foreach($heroImages as $index => $image)
                <div class="hero-slide flex-shrink-0 relative" style="width: {{ 100 / count($heroImages) }}%;" data-slide="{{ $index }}">
                    <!-- Background Image -->
                    <div class="absolute inset-0 z-0">
                        <img src="{{ $image }}" alt="Hero Slide {{ $index + 1 }}" class="w-full h-full object-cover" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700" style="display: none;"></div>
                    </div>
                    
                    <!-- Hero Content - Only show on first slide -->
                    @if($index === 0)
                    <div class="relative z-10 flex items-center justify-center h-full text-white">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                            <!-- School Logo -->
                            <div class="mb-8">
                                <div class="w-32 h-32 mx-auto mb-6 bg-white/20 backdrop-blur-md rounded-full overflow-hidden shadow-2xl border-4 border-white/30">
                                    @if($schoolSettings->logo)
                                        <img src="{{ $schoolSettings->getImageUrl('logo') }}" alt="School Logo" class="w-full h-full object-cover rounded-full" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center rounded-full" style="display: none;">
                                            <i class="fas fa-school text-4xl text-white"></i>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-school text-4xl text-white" width="1em" height="1em" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M22 10.5V7.27a2 2 0 0 0-1.18-1.82l-7-3.11a2 2 0 0 0-1.64 0l-7 3.11A2 2 0 0 0 2 7.27V10.5M22 10.5v2.23M22 10.5l-10 4.44m0 0L2 10.5m0 0v2.23m0 0l10 4.44m0 0v2.39M8 18h8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- School Name -->
                            <h1 class="text-6xl md:text-7xl font-bold mb-4 text-shadow-lg">
                                {{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}
                            </h1>
                            
                            <!-- Slogan -->
                            <p class="text-2xl md:text-3xl mb-8 text-white font-light">
                                {{ $websiteSettings->slogan ?? 'জ্ঞানই শক্তি - শিক্ষাই আলো' }}
                            </p>
                            
                            <!-- School Info Cards -->
                            <div class="flex gap-6 justify-center flex-wrap mb-8">
                                <div class="bg-white/20 backdrop-blur-md px-8 py-4 rounded-2xl border border-white/30 shadow-xl">
                                    <p class="text-sm opacity-90 mb-1">EIIN</p>
                                    <p class="text-2xl font-bold">{{ $schoolSettings->eiin ?? $websiteSettings->eiin ?? '১০২৩৩০' }}</p>
                                </div>
                                <div class="bg-white/20 backdrop-blur-md px-8 py-4 rounded-2xl border border-white/30 shadow-xl">
                                    <p class="text-sm opacity-90 mb-1">প্রতিষ্ঠা</p>
                                    <p class="text-2xl font-bold">{{ $schoolSettings->established_year ?? $websiteSettings->established ?? '১৯৮৫' }}</p>
                                </div>
                                <div class="bg-white/20 backdrop-blur-md px-8 py-4 rounded-2xl border border-white/30 shadow-xl">
                                    <p class="text-sm opacity-90 mb-1">বোর্ড</p>
                                    <p class="text-2xl font-bold">{{ $schoolSettings->board ?? $websiteSettings->board ?? 'ঢাকা' }}</p>
                                </div>
                            </div>
                            
                            <!-- CTA Buttons -->
                            <div class="flex gap-4 justify-center flex-wrap">
                                @auth
                                    <a href="{{ route('tenant.dashboard') }}" class="bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-blue-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                        <i class="fas fa-gauge-high mr-2"></i>ড্যাশবোর্ড
                                    </a>
                                @else
                                    <a href="{{ url('/student/login') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                        <i class="fas fa-graduation-cap mr-2"></i>শিক্ষার্থী লগইন
                                    </a>
                                    <a href="{{ url('/guardian/login') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                        <i class="fas fa-user-group mr-2"></i>অভিভাবক লগইন
                                    </a>
                                    <a href="{{ url('/login') }}" class="bg-white text-gray-800 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-gray-100">
                                        <i class="fas fa-chalkboard-user mr-2 text-indigo-600"></i>শিক্ষক লগইন
                                    </a>
                                @endauth
                                <a href="{{ route('tenant.about') }}" class="bg-white/20 backdrop-blur-md text-white px-8 py-4 rounded-2xl font-bold text-lg border border-white/30 hover:bg-white/30 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                    <i class="fas fa-circle-info mr-2"></i>আরও জানুন
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            
            <!-- Slider Navigation Dots -->
            @if(count($heroImages) > 1)
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
                <div class="flex space-x-3">
                    @foreach($heroImages as $index => $image)
                    <button class="slider-dot w-4 h-4 rounded-full border-2 border-white/50 transition-all duration-300 {{ $index === 0 ? 'bg-white' : 'bg-white/20' }}" data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Slider Arrows -->
            @if(count($heroImages) > 1)
            <button class="slider-prev absolute left-8 top-1/2 transform -translate-y-1/2 z-20 w-12 h-12 bg-white/20 backdrop-blur-md rounded-full border border-white/30 text-white hover:bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-next absolute right-8 top-1/2 transform -translate-y-1/2 z-20 w-12 h-12 bg-white/20 backdrop-blur-md rounded-full border border-white/30 text-white hover:bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-right"></i>
            </button>
            @endif
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">সুবিধাদি</h3>
                <div class="w-24 h-1 hero-gradient mx-auto rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-4 gap-6">
                @php
                    $facilityIcons = [
                        'লাইব্রেরি' => ['icon' => 'book-open', 'color' => '#2563eb', 'bg' => '#eff6ff', 'desc' => '৫০০০+ বই'],
                        'বিজ্ঞান ল্যাব' => ['icon' => 'microscope', 'color' => '#16a34a', 'bg' => '#f0fdf4', 'desc' => 'আধুনিক যন্ত্রপাতি'],
                        'কম্পিউটার ল্যাব' => ['icon' => 'monitor', 'color' => '#7c3aed', 'bg' => '#f5f3ff', 'desc' => '৫০টি কম্পিউটার'],
                        'খেলার মাঠ' => ['icon' => 'map-pin', 'color' => '#ea580c', 'bg' => '#fff7ed', 'desc' => '২ একর'],
                        'ক্যান্টিন' => ['icon' => 'utensils', 'color' => '#dc2626', 'bg' => '#fef2f2', 'desc' => 'স্বাস্থ্যকর খাবার'],
                        'পরিবহন সুবিধা' => ['icon' => 'bus', 'color' => '#4f46e5', 'bg' => '#eef2ff', 'desc' => 'নিরাপদ পরিবহন']
                    ];
                    
                    $facilities = $websiteSettings->facilities ?? ['লাইব্রেরি', 'বিজ্ঞান ল্যাব', 'কম্পিউটার ল্যাব', 'খেলার মাঠ'];
                @endphp
                
                @foreach($facilities as $facility)
                    @php
                        $facilityData = $facilityIcons[$facility] ?? ['icon' => 'star', 'color' => '#4b5563', 'bg' => '#f3f4f6', 'desc' => 'উপলব্ধ'];
                    @endphp
                    <div class="rounded-xl p-6 text-center card-hover shadow-sm border border-gray-100" style="background-color: {{ $facilityData['bg'] }}">
                        <div class="flex justify-center mb-4">
                            <i data-lucide="{{ $facilityData['icon'] }}" style="color: {{ $facilityData['color'] }}; width: 40px; height: 40px;"></i>
                        </div>
                        <h4 class="font-bold text-gray-800">{{ $facility }}</h4>
                        <p class="text-sm text-gray-600 mt-2">{{ $facilityData['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliderContainer = document.getElementById('sliderContainer');
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.slider-dot');
            const prevBtn = document.querySelector('.slider-prev');
            const nextBtn = document.querySelector('.slider-next');
            let currentSlide = 0;
            let slideInterval;
            const totalSlides = slides.length;
            
            // Only initialize slider if there are multiple slides
            if (slides.length <= 1) {
                // Hide navigation elements for single slide
                if (dots.length > 0) dots.forEach(dot => dot.style.display = 'none');
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                return;
            }
            
            function showSlide(index) {
                // Ensure index is within bounds
                if (index >= totalSlides) index = 0;
                if (index < 0) index = totalSlides - 1;
                
                // Calculate the transform percentage
                const translateX = -(index * (100 / totalSlides));
                
                // Apply the transform to slide the container
                if (sliderContainer) {
                    sliderContainer.style.transform = `translateX(${translateX}%)`;
                }
                
                // Update dots
                dots.forEach((dot, i) => {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/20');
                    if (i === index) {
                        dot.classList.remove('bg-white/20');
                        dot.classList.add('bg-white');
                    }
                });
                
                currentSlide = index;
            }
            
            function nextSlide() {
                const next = (currentSlide + 1) % totalSlides;
                showSlide(next);
            }
            
            function prevSlide() {
                const prev = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(prev);
            }
            
            function startAutoSlide() {
                if(totalSlides > 1) {
                    slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
                }
            }
            
            function stopAutoSlide() {
                clearInterval(slideInterval);
            }
            
            // Event listeners
            if(nextBtn) nextBtn.addEventListener('click', () => { 
                stopAutoSlide(); 
                nextSlide(); 
                startAutoSlide(); 
            });
            
            if(prevBtn) prevBtn.addEventListener('click', () => { 
                stopAutoSlide(); 
                prevSlide(); 
                startAutoSlide(); 
            });
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    stopAutoSlide();
                    showSlide(index);
                    startAutoSlide();
                });
            });
            
            // Pause on hover
            const heroSlider = document.querySelector('.hero-slider');
            if(heroSlider) {
                heroSlider.addEventListener('mouseenter', stopAutoSlide);
                heroSlider.addEventListener('mouseleave', startAutoSlide);
            }
            
            // Handle image load errors
            const heroImages = document.querySelectorAll('.hero-slide img');
            heroImages.forEach(img => {
                img.addEventListener('error', function() {
                    // Replace with a clean gradient background without overlay
                    this.parentElement.innerHTML = '<div class="w-full h-full bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700"></div>';
                });
            });
            
            // Touch/Swipe support for mobile
            let startX = 0;
            let endX = 0;
            
            if(heroSlider) {
                heroSlider.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                });
                
                heroSlider.addEventListener('touchend', (e) => {
                    endX = e.changedTouches[0].clientX;
                    handleSwipe();
                });
            }
            
            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = startX - endX;
                
                if (Math.abs(diff) > swipeThreshold) {
                    stopAutoSlide();
                    if (diff > 0) {
                        // Swipe left - next slide
                        nextSlide();
                    } else {
                        // Swipe right - previous slide
                        prevSlide();
                    }
                    startAutoSlide();
                }
            }
            
            // Start auto slide
            startAutoSlide();
            
            console.log('Horizontal hero slider initialized with', totalSlides, 'slides');
        });
    </script>
@endsection

@extends('tenant.layouts.web')

@section('title', 'গ্যালারি')

@section('styles')
<style>
    .lightbox-modal {
        display: none;
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999999;
        overflow: hidden;
        cursor: pointer;
        align-items: center;
        justify-content: center;
    }

    .lightbox-modal.active {
        display: flex !important;
    }

    .lightbox-content {
        position: relative;
        width: 95vw;
        height: 90vh;
        max-width: 1100px;
        max-height: 850px;
        background: white;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        cursor: default;
        z-index: 10000000;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .lightbox-image-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1a1a1a;
        position: relative;
        overflow: hidden;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .lightbox-controls {
        background: white;
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
        overflow-y: auto;
        flex-shrink: 0;
        max-height: 35%;
    }

    .lightbox-nav-btn {
        position: absolute;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .lightbox-nav-btn:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: translateY(-50%) scale(1.15);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .lightbox-nav-btn.prev {
        left: 15px;
    }

    .lightbox-nav-btn.next {
        right: 15px;
    }

    .lightbox-close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }

    .lightbox-close-btn:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.15);
        border-color: rgba(255, 255, 255, 0.4);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .likes-count {
        font-weight: 600;
        color: #ef4444;
    }

    .like-btn.liked i {
        color: #ef4444;
    }
</style>
@endsection

@section('content')
@php
    $galleries = \App\Models\Gallery::active()->ordered()->get();
    $photos = $galleries->where('type', 'photo');
    $audios = $galleries->where('type', 'audio');
    $videos = $galleries->where('type', 'video');
@endphp

<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">গ্যালারি</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">আমাদের প্রতিষ্ঠানের স্মরণীয় কিছু মুহূর্ত</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Photos Section -->
    @if($photos->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                <i class="fas fa-images text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">ফটো গ্যালারি</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($photos as $index => $item)
            @php
                $titles = ['স্কুল ক্যাম্পাস', 'ক্লাসরুম', 'বিজ্ঞান ল্যাব', 'কম্পিউটার ল্যাব', 'লাইব্রেরি', 'ক্রীড়া প্রতিযোগিতা', 'সাংস্কৃতিক অনুষ্ঠান', 'পুরস্কার বিতরণী', 'শিক্ষা সফর'];
                $icons = ['fa-school', 'fa-chalkboard-teacher', 'fa-flask', 'fa-desktop', 'fa-book', 'fa-running', 'fa-music', 'fa-award', 'fa-bus'];
                $colors = ['blue', 'indigo', 'emerald', 'purple', 'orange', 'rose', 'cyan', 'teal', 'violet'];
                $colorClasses = [
                    'blue' => 'from-blue-500 to-indigo-600',
                    'indigo' => 'from-indigo-500 to-purple-600',
                    'emerald' => 'from-emerald-500 to-teal-600',
                    'purple' => 'from-purple-500 to-pink-600',
                    'orange' => 'from-orange-500 to-red-600',
                    'rose' => 'from-rose-500 to-red-600',
                    'cyan' => 'from-cyan-500 to-blue-600',
                    'teal' => 'from-teal-500 to-green-600',
                    'violet' => 'from-violet-500 to-purple-600'
                ];
                $title = $item->title ?? $titles[$index % count($titles)] ?? 'ফটো';
                $icon = $icons[$index % count($icons)] ?? 'fa-image';
                $color = $colors[$index % count($colors)] ?? 'blue';
            @endphp
            <div class="group relative overflow-hidden rounded-3xl shadow-xl aspect-[4/3] bg-gray-200 border-4 border-white hover:border-indigo-100 transition-all duration-300 cursor-pointer gallery-item" data-id="{{ $item->id }}" data-image="/files/{{ $item->file_path }}" data-title="{{ $title }}">
                <img src="/files/{{ $item->file_path }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-300"></div>
                
                <div class="absolute inset-0 p-8 flex flex-col justify-end transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $colorClasses[$color] }} flex items-center justify-center text-white shadow-lg transform group-hover:rotate-6 transition-transform">
                            <i class="fas {{ $icon }} text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">{{ $title }}</h3>
                    </div>
                    <p class="text-gray-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                        ক্লিক করে বড় করে দেখুন
                    </p>
                </div>
                
                <!-- Expand Button -->
                <div class="absolute top-6 right-6 w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:scale-100 scale-50" onclick="this.parentElement.click()">
                    <i class="fas fa-expand-alt text-xl"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Audio Section -->
    @if($audios->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                <i class="fas fa-music text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">অডিও গ্যালারি</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($audios as $audio)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-music text-2xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 truncate">{{ $audio->title }}</h3>
                        @if($audio->category)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full mt-1">{{ $audio->category }}</span>
                        @endif
                    </div>
                </div>
                <audio controls class="w-full">
                    <source src="/files/{{ $audio->file_path }}" type="audio/mpeg">
                    আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                </audio>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Video Section -->
    @if($videos->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-gradient-to-br from-red-500 to-pink-600 p-3 rounded-xl">
                <i class="fas fa-video text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">ভিডিও গ্যালারি</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($videos as $video)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="aspect-video bg-gray-900">
                    <iframe 
                        src="{{ $video->video_url }}" 
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-1">{{ $video->title }}</h3>
                    @if($video->category)
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">{{ $video->category }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($galleries->count() == 0)
    <div class="text-center py-20">
        <div class="bg-white px-8 py-10 rounded-3xl shadow-xl border border-gray-100 max-w-2xl mx-auto">
            <div class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-images text-3xl text-indigo-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">গ্যালারি শীঘ্রই আসছে...</h3>
            <p class="text-gray-500 mb-8 leading-relaxed">আমাদের বার্ষিক ক্রীড়া প্রতিযোগিতা, সাংস্কৃতিক অনুষ্ঠান এবং অন্যান্য শিক্ষা সফরের ছবিগুলো শীঘ্রই গ্যালারিতে যুক্ত করা হবে।</p>
            <a href="{{ route('tenant.home') }}" class="inline-block px-8 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                হোম পেজে যান
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="lightbox-modal">
    <div class="lightbox-content">
        <!-- Image Container -->
        <div class="lightbox-image-container">
            <img id="lightboxImage" src="" alt="" class="lightbox-image">
            
            <!-- Close Button -->
            <button class="lightbox-close-btn" onclick="closeLightbox()">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Previous Button -->
            <button class="lightbox-nav-btn prev" onclick="previousImage()">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <!-- Next Button -->
            <button class="lightbox-nav-btn next" onclick="nextImage()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Controls Section -->
        <div class="lightbox-controls">
            <div class="max-w-2xl mx-auto">
                <!-- Title and Info -->
                <div class="mb-4">
                    <h2 id="lightboxTitle" class="text-2xl font-bold text-gray-900 mb-2"></h2>
                </div>
                
                <!-- Like and Download Buttons -->
                <div class="flex gap-4 mb-6 pb-6 border-b border-gray-200">
                    <button onclick="toggleLike()" class="like-btn flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-600 font-medium transition-all">
                        <i class="far fa-heart"></i>
                        <span class="likes-count">0</span>
                    </button>
                    
                    <button onclick="downloadImage()" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium transition-all">
                        <i class="fas fa-download"></i>
                        ডাউনলোড
                    </button>
                </div>
                
                <!-- Comments Section -->
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-900">মন্তব্য</h3>
                    
                    <!-- Comments List -->
                    <div id="commentsList" class="space-y-3 max-h-40 overflow-y-auto">
                        <!-- Comments will be dynamically added here -->
                    </div>
                    
                    <!-- Add Comment Form -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex gap-2">
                            <input type="text" id="commentInput" placeholder="আপনার মন্তব্য লিখুন..." class="flex-1 px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addComment()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let currentImageIndex = 0;
    let galleryItems = [];
    let galleryLikes = JSON.parse(localStorage.getItem('galleryLikes') || '{}');
    let galleryComments = JSON.parse(localStorage.getItem('galleryComments') || '{}');

    // Initialize gallery items
    function initGallery() {
        const items = document.querySelectorAll('.gallery-item');
        console.log('Gallery initialization: found ' + items.length + ' items');
        galleryItems = Array.from(items).map(item => ({
            id: item.getAttribute('data-id'),
            image: item.getAttribute('data-image'),
            title: item.getAttribute('data-title'),
            element: item
        }));

        // Add click listeners to gallery items
        items.forEach((item, index) => {
            item.onclick = function() {
                console.log('Item clicked, index: ' + index);
                currentImageIndex = index;
                openLightbox();
            };
        });
    }

    function openLightbox() {
        console.log('Opening lightbox, index: ' + currentImageIndex);
        const modal = document.getElementById('lightboxModal');
        
        if (galleryItems.length === 0) {
            initGallery();
        }

        if (!modal || !galleryItems[currentImageIndex]) {
            console.error('Modal or item not found', { modal: !!modal, item: !!galleryItems[currentImageIndex] });
            return;
        }

        const item = galleryItems[currentImageIndex];
        
        document.getElementById('lightboxImage').src = item.image;
        document.getElementById('lightboxTitle').textContent = item.title;
        
        // Add classes to disable scrolling
        document.body.style.overflow = 'hidden';
        
        // Show modal using direct style for maximum compatibility
        modal.style.setProperty('display', 'flex', 'important');
        modal.classList.add('active');
        
        // Update navigation buttons
        updateNavButtons();
        
        // Load likes and comments for this image
        loadLikesAndComments(item.id);
        
        // Handle keyboard navigation
        document.addEventListener('keydown', handleKeyPress);
    }

    function updateNavButtons() {
        const prevBtn = document.querySelector('.lightbox-nav-btn.prev');
        const nextBtn = document.querySelector('.lightbox-nav-btn.next');
        
        if (galleryItems.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        }
    }

    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('active');
        }
        
        // Remove scroll lock
        document.body.style.overflow = '';
        
        document.removeEventListener('keydown', handleKeyPress);
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % galleryItems.length;
        openLightbox();
    }

    function previousImage() {
        currentImageIndex = (currentImageIndex - 1 + galleryItems.length) % galleryItems.length;
        openLightbox();
    }

    function handleKeyPress(e) {
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') previousImage();
        if (e.key === 'Escape') closeLightbox();
    }

    function downloadImage() {
        const item = galleryItems[currentImageIndex];
        const link = document.createElement('a');
        link.href = item.image;
        link.download = item.title || 'image.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function toggleLike() {
        const item = galleryItems[currentImageIndex];
        const likeBtn = document.querySelector('.like-btn');
        
        if (!galleryLikes[item.id]) {
            galleryLikes[item.id] = { count: 0, liked: false };
        }
        
        if (galleryLikes[item.id].liked) {
            galleryLikes[item.id].count--;
            galleryLikes[item.id].liked = false;
            likeBtn.classList.remove('liked');
        } else {
            galleryLikes[item.id].count++;
            galleryLikes[item.id].liked = true;
            likeBtn.classList.add('liked');
        }
        
        localStorage.setItem('galleryLikes', JSON.stringify(galleryLikes));
        updateLikesDisplay();
    }

    function updateLikesDisplay() {
        const item = galleryItems[currentImageIndex];
        const likeCount = document.querySelector('.likes-count');
        const likeBtn = document.querySelector('.like-btn');
        const likes = galleryLikes[item.id] || { count: 0, liked: false };
        
        likeCount.textContent = likes.count;
        
        if (likes.liked) {
            likeBtn.classList.add('liked');
            likeBtn.querySelector('i').classList.remove('far');
            likeBtn.querySelector('i').classList.add('fas');
        } else {
            likeBtn.classList.remove('liked');
            likeBtn.querySelector('i').classList.remove('fas');
            likeBtn.querySelector('i').classList.add('far');
        }
    }

    function addComment() {
        const item = galleryItems[currentImageIndex];
        const input = document.getElementById('commentInput');
        const commentText = input.value.trim();
        
        if (!commentText) return;
        
        if (!galleryComments[item.id]) {
            galleryComments[item.id] = [];
        }
        
        galleryComments[item.id].push({
            text: commentText,
            date: new Date().toLocaleString('bn-BD')
        });
        
        localStorage.setItem('galleryComments', JSON.stringify(galleryComments));
        input.value = '';
        loadLikesAndComments(item.id);
    }

    function loadLikesAndComments(itemId) {
        // Update likes
        const likes = galleryLikes[itemId] || { count: 0, liked: false };
        document.querySelector('.likes-count').textContent = likes.count;
        
        if (likes.liked) {
            document.querySelector('.like-btn').classList.add('liked');
            document.querySelector('.like-btn i').classList.remove('far');
            document.querySelector('.like-btn i').classList.add('fas');
        } else {
            document.querySelector('.like-btn').classList.remove('liked');
            document.querySelector('.like-btn i').classList.remove('fas');
            document.querySelector('.like-btn i').classList.add('far');
        }
        
        // Update comments
        const comments = galleryComments[itemId] || [];
        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = '';
        
        if (comments.length === 0) {
            commentsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-2">কোনো মন্তব্য নেই</p>';
        } else {
            comments.forEach(comment => {
                const commentEl = document.createElement('div');
                commentEl.className = 'bg-gray-50 p-3 rounded-lg text-sm';
                commentEl.innerHTML = `
                    <p class="text-gray-800">${comment.text}</p>
                    <p class="text-gray-500 text-xs mt-1">${comment.date}</p>
                `;
                commentsList.appendChild(commentEl);
            });
        }
    }

    // Close modal when clicking outside
    document.getElementById('lightboxModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'lightboxModal') {
            closeLightbox();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initGallery);
</script>
@endsection

@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @if(tenancy()->initialized)
                {{ tenant('data')['school_name'] ?? tenant('id') }} - লগইন
            @else
                {{ config('app.name', 'স্মার্ট পাঠশালা') }}
            @endif
        </title>

        <!-- PERMANENT TAILWIND CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <style>
            * { 
                font-family: 'Hind Siliguri', 'Poppins', sans-serif !important; 
            }
            
            .gradient-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .hero-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="mb-8">
                @if(tenancy()->initialized)
                    <div class="text-center">
                        <div class="w-20 h-20 hero-gradient rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl transform hover:scale-105 transition-transform">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <a href="/" class="text-3xl font-bold gradient-text tracking-tight hover:opacity-80 transition block">
                            {{ tenant('data')['school_name'] ?? tenant('id') }}
                        </a>
                        <p class="text-sm text-gray-600 mt-1">স্মার্ট পাঠশালা ম্যানেজমেন্ট সিস্টেম</p>
                    </div>
                @else
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl transform hover:scale-105 transition-transform">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <a href="/" class="text-4xl font-bold text-blue-600 tracking-tight hover:text-blue-700 transition block">
                            স্মার্ট পাঠশালা
                        </a>
                    </div>
                @endif
            </div>

            <div class="w-full sm:max-w-md bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center text-sm text-gray-600">
                <a href="/" class="hover:text-blue-600 transition">হোমে ফিরুন</a>
            </div>
        </div>
    </body>
</html>

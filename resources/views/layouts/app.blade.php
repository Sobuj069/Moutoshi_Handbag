<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Moutoshi Handbag - Handbag Haven | Premium Ladies Bags BD')</title>
    <meta name="description" content="@yield('meta_description', 'Discover stylish tote bags, college bags, canvas totes and luxury handbags at Moutoshi Handbag BD. Handbag Haven with fast delivery across Bangladesh.')">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}">

    <!-- Google Fonts: Jost (The signature font of thepatchee.com Prestige theme) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-white text-[#1c1c1c] font-sans antialiased selection:bg-[#c9a66b] selection:text-white flex flex-col min-h-screen">
    
    <!-- Top Announcement Bar -->
    <x-announcement-bar />

    <!-- Main Navigation Header -->
    <x-header />

    <!-- Main Content Area -->
    <main class="flex-1">
        @if(session('success'))
            <div class="bg-green-50 border-b border-green-200 text-green-800 text-xs px-4 py-3 text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-b border-red-200 text-red-800 text-xs px-4 py-3 text-center font-medium">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Slide-out Cart Drawer -->
    <x-cart-drawer />

    <!-- Search Overlay Modal -->
    <x-search-modal />

    <!-- 1-Click Cash on Delivery Quick Order Modal -->
    <x-quick-order-modal />

    <!-- Footer -->
    <x-footer />

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Moutoshi Handbag Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mobile drawer styling (< 1024px) */
        @media (max-width: 1023px) {
            #admin-sidebar {
                position: fixed !important;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 50;
                width: 16rem !important;
                height: 100vh !important;
                transform: translateX(-100%) !important;
                transition: transform 0.25s ease-in-out;
                display: flex !important;
            }
            #admin-sidebar.sidebar-open {
                transform: translateX(0) !important;
            }
            .admin-drawer-backdrop {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.65);
                z-index: 45;
                backdrop-filter: blur(2px);
            }
        }

        /* Desktop permanent collapsible sidebar (>= 1024px) */
        @media (min-width: 1024px) {
            #admin-sidebar {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                bottom: auto !important;
                height: 100vh !important;
                transform: none !important;
                flex-shrink: 0 !important;
                display: flex !important;
                transition: width 0.25s ease-in-out, margin-left 0.25s ease-in-out;
            }
            #admin-sidebar.sidebar-open {
                width: 16rem !important;
                margin-left: 0 !important;
                visibility: visible !important;
            }
            #admin-sidebar:not(.sidebar-open) {
                width: 0 !important;
                margin-left: -16rem !important;
                visibility: hidden !important;
                overflow: hidden !important;
            }
            .admin-drawer-backdrop {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-neutral-100 font-sans text-neutral-900 antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    
    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Drawer Backdrop -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-cloak
             class="admin-drawer-backdrop"></div>

        <!-- Sidebar (Permanent on Desktop >= 1024px, Slide drawer on Mobile < 1024px) -->
        <aside id="admin-sidebar"
               :class="sidebarOpen ? 'sidebar-open' : ''"
               class="w-64 bg-[#1c1c1c] text-white flex flex-col justify-between shrink-0 shadow-2xl">
            <div>
                <!-- Admin Header -->
                <div class="h-16 lg:h-20 flex items-center justify-between px-6 border-b border-neutral-800">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <span class="text-lg font-bold tracking-[0.15em] uppercase text-white font-sans">MOUTOSHI</span>
                        <span class="px-1.5 py-0.5 bg-[#c9a66b] text-white text-[9px] font-bold rounded-xs uppercase">ADMIN</span>
                    </a>

                    <!-- Mobile Close Button -->
                    <button type="button" @click="sidebarOpen = false" class="lg:hidden text-neutral-400 hover:text-white p-1 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1 text-xs font-semibold uppercase tracking-wider">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#c9a66b] text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center justify-between px-4 py-3 rounded-sm transition {{ request()->routeIs('admin.orders*') ? 'bg-[#c9a66b] text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Web Orders</span>
                        </div>
                        @php $pendingCount = \App\Models\Order::where('order_status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="px-2 py-0.5 bg-red-500 text-white text-[10px] rounded-full font-bold">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-sm transition {{ request()->routeIs('admin.products*') ? 'bg-[#c9a66b] text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Products
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-sm transition {{ request()->routeIs('admin.categories*') ? 'bg-[#c9a66b] text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.banners.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-sm transition {{ request()->routeIs('admin.banners*') ? 'bg-[#c9a66b] text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Hero Sliders
                    </a>

                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-sm transition {{ request()->routeIs('admin.settings*') ? 'bg-[#c9a66b] text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Settings
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-neutral-800 space-y-2">
                <a href="{{ route('home') }}" target="_blank" 
                   class="flex items-center gap-2 px-3 py-2 text-xs text-neutral-400 hover:text-white hover:bg-neutral-800 transition rounded-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Visit Storefront</span>
                </a>
                
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-400 hover:text-red-300 hover:bg-neutral-800 transition rounded-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            
            <!-- Top Bar -->
            <header class="h-16 lg:h-20 bg-white border-b border-neutral-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Always Visible Menu Toggle Button (Desktop & Mobile) -->
                    <button type="button" @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 px-3 text-neutral-800 hover:text-black hover:bg-neutral-100 rounded-xs border border-neutral-300 shadow-2xs focus:outline-none flex items-center gap-2 cursor-pointer transition mr-2 bg-white shrink-0"
                            title="Toggle Sidebar Menu (সাইডবার মেনু দেখুন)">
                        <svg class="w-5 h-5 text-neutral-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="text-xs font-black uppercase tracking-wider font-sans text-neutral-900">MENU</span>
                    </button>
                    <h1 class="text-sm sm:text-lg font-bold uppercase tracking-wider text-neutral-900 font-sans truncate">
                        @yield('header_title', 'Overview')
                    </h1>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-4 text-xs font-semibold text-neutral-700 shrink-0">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="hidden sm:inline">Logged in as:</span>
                    <span class="truncate max-w-[100px] sm:max-w-none">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-xs font-semibold rounded-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>

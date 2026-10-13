<header x-data="{ mobileMenuOpen: false, searchOpen: false }" 
        class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-neutral-200 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Left: Mobile menu button & Secondary actions -->
            <div class="flex items-center lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 text-neutral-800 hover:text-black focus:outline-none"
                        aria-label="Toggle Navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <button @click="$dispatch('open-search')" class="p-2 text-neutral-800 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex-1 lg:flex-none text-center lg:text-left">
                <a href="{{ route('home') }}" class="inline-block group py-1">
                    <img src="{{ asset('assets/images/logo.png') }}" 
                         alt="Moutoshi Handbag" 
                         class="h-10 sm:h-12 w-auto object-contain mx-auto lg:mx-0 group-hover:opacity-90 transition">
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center space-x-7 text-[13px] font-medium tracking-[0.12em] uppercase text-neutral-800">
                <a href="{{ route('shop.index', 'women') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Women
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'tote-bags') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Tote Bag
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'shoulder-bags') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Shoulder Bag
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'crossbody-bags') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Crossbody
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'backpacks') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Backpacks
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'wallets-card-holders') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Wallets
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'duffle-bags') }}" class="py-2 hover:text-[#c9a66b] transition-colors relative group">
                    Duffles
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#c9a66b] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('shop.index', 'all') }}" class="py-2 text-[#c9a66b] font-semibold hover:text-black transition-colors relative group">
                    Sale 75% Off
                    <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-black transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>

            <!-- Right Actions: Search, Track, Admin, Cart -->
            <div class="flex items-center space-x-3 sm:space-x-5">
                <!-- Search Button -->
                <button @click="$dispatch('open-search')" 
                        class="hidden lg:flex items-center gap-1.5 text-neutral-700 hover:text-black transition text-xs tracking-wider uppercase font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Search</span>
                </button>

                <!-- Track Order Link -->
                <a href="{{ route('order.track') }}" 
                   title="Track Order"
                   class="hidden sm:flex text-neutral-700 hover:text-black transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                    </svg>
                </a>

                <!-- Admin Link -->
                <a href="{{ route('admin.dashboard') }}" 
                   title="Admin Panel"
                   class="text-neutral-700 hover:text-black transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>

                <!-- Cart Button with Counter -->
                <button @click="$store.cart.toggleDrawer()" 
                        class="flex items-center text-neutral-900 hover:text-[#c9a66b] transition relative p-1"
                        aria-label="View Cart">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span x-text="$store.cart.count" 
                          x-show="$store.cart.count > 0"
                          class="absolute -top-1 -right-1 bg-[#1c1c1c] text-[#c9a66b] text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white">
                        0
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-0 z-50 flex lg:hidden" 
         x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="mobileMenuOpen = false"></div>
        
        <div class="relative w-4/5 max-w-sm bg-white h-full shadow-2xl flex flex-col justify-between py-6 px-6 z-10 overflow-y-auto">
            <div>
                <div class="flex items-center justify-between border-b pb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Moutoshi Handbag" class="h-8 w-auto object-contain">
                    <button @click="mobileMenuOpen = false" class="p-2 text-neutral-500 hover:text-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-6 flex flex-col space-y-4 text-sm font-semibold tracking-wider uppercase">
                    <a href="{{ route('shop.index', 'women') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Women</a>
                    <a href="{{ route('shop.index', 'tote-bags') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Tote Bags</a>
                    <a href="{{ route('shop.index', 'shoulder-bags') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Shoulder Bags</a>
                    <a href="{{ route('shop.index', 'crossbody-bags') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Crossbody Bags</a>
                    <a href="{{ route('shop.index', 'backpacks') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Backpacks</a>
                    <a href="{{ route('shop.index', 'wallets-card-holders') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Wallets & Clutches</a>
                    <a href="{{ route('shop.index', 'duffle-bags') }}" @click="mobileMenuOpen = false" class="py-2 border-b border-neutral-100 hover:text-[#c9a66b]">Duffle Bags</a>
                    <a href="{{ route('shop.index', 'all') }}" @click="mobileMenuOpen = false" class="py-2 text-[#c9a66b] font-bold">Clearance Sale 75% Off</a>
                </div>
            </div>

            <div class="border-t pt-6 space-y-3 text-xs uppercase tracking-wider text-neutral-600">
                <a href="{{ route('order.track') }}" class="flex items-center gap-2 py-1 hover:text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Track My Order
                </a>
                <a href="{{ route('admin.login') }}" class="flex items-center gap-2 py-1 hover:text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Admin Login
                </a>
            </div>
        </div>
    </div>
</header>

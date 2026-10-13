<div x-data="{ searchModalOpen: false }" 
     @open-search.window="searchModalOpen = true; $nextTick(() => $refs.searchInput?.focus())"
     x-show="searchModalOpen" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     x-cloak>
    <!-- Backdrop -->
    <div x-show="searchModalOpen" 
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="searchModalOpen = false" 
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <div class="relative min-h-screen flex items-start justify-center pt-20 px-4">
        <div x-show="searchModalOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="relative bg-white w-full max-w-2xl shadow-2xl p-6 sm:p-8 border border-neutral-200">
            
            <div class="flex items-center justify-between pb-4 border-b border-neutral-200">
                <span class="text-xs font-bold tracking-[0.2em] uppercase text-neutral-500 font-sans">
                    SEARCH STORE
                </span>
                <button @click="searchModalOpen = false" class="text-neutral-400 hover:text-black p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('shop.search') }}" method="GET" class="mt-6">
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           x-ref="searchInput"
                           placeholder="Type to search tote bags, backpacks, clutches..." 
                           class="w-full bg-neutral-50 border-b-2 border-neutral-900 px-4 py-3 text-sm focus:outline-none focus:bg-white text-neutral-900 tracking-wide font-medium placeholder-neutral-400">
                    <button type="submit" class="absolute right-2 top-3 text-neutral-800 hover:text-[#c9a66b]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <span class="text-[11px] font-bold tracking-wider uppercase text-neutral-400 block mb-3">Popular Searches</span>
                <div class="flex flex-wrap gap-2 text-xs">
                    <a href="{{ route('shop.search', ['q' => 'Shoulder Bag']) }}" class="px-3 py-1.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition">Shoulder Bag</a>
                    <a href="{{ route('shop.search', ['q' => 'Tote']) }}" class="px-3 py-1.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition">Tote Bag</a>
                    <a href="{{ route('shop.search', ['q' => 'Crossbody']) }}" class="px-3 py-1.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition">Crossbody</a>
                    <a href="{{ route('shop.search', ['q' => 'Backpack']) }}" class="px-3 py-1.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition">Backpack</a>
                    <a href="{{ route('shop.search', ['q' => 'Wallet']) }}" class="px-3 py-1.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition">RFID Wallet</a>
                </div>
            </div>

        </div>
    </div>
</div>

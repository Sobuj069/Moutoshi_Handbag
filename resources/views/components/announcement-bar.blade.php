@php
    $announcements = [
        \App\Models\Setting::get('announcement_1', 'No Tricks, Just BOO-tiful Deals 👻 | Up to 75% Off'),
        \App\Models\Setting::get('announcement_2', 'Free Delivery on prepaid orders over ৳2,500'),
        \App\Models\Setting::get('announcement_3', 'Cash On Delivery Available All Over Bangladesh 🇧🇩'),
    ];
@endphp

<div x-data="{
    active: 0,
    messages: {{ json_encode($announcements) }},
    init() {
        setInterval(() => {
            this.active = (this.active + 1) % this.messages.length;
        }, 4000);
    }
}" class="bg-[#4a306f] text-white text-[11px] md:text-xs font-medium tracking-wider uppercase py-2 px-4 transition-colors">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="hidden md:flex items-center space-x-4 text-white/80 text-[10px]">
            <span>HOTLINE: {{ \App\Models\Setting::get('phone', '+880 1711-223344') }}</span>
        </div>
        
        <div class="flex-1 text-center overflow-hidden h-5 flex items-center justify-center">
            <template x-for="(msg, index) in messages" :key="index">
                <div x-show="active === index" 
                     x-transition:enter="transition ease-out duration-300 transform opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300 transform opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="truncate font-semibold tracking-wide">
                    <span x-html="msg"></span>
                </div>
            </template>
        </div>

        <div class="hidden md:flex items-center space-x-4 text-[10px] text-white/80">
            <a href="{{ route('order.track') }}" class="hover:text-white transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                TRACK ORDER
            </a>
            <span>|</span>
            <a href="{{ route('shop.index', 'clearance-sale') }}" class="text-[#f5c66b] font-bold hover:underline">
                SALE UP TO 75% OFF
            </a>
        </div>
    </div>
</div>

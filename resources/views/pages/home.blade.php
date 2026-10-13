@extends('layouts.app')

@section('title', 'Moutoshi Handbag | Handbag Haven Bangladesh')
@section('meta_description', 'Discover your style at Handbag Haven! Premium canvas tote bags, college varsity bags, and stylish handbags from Moutoshi Handbag.')

@section('content')

    <!-- 1. HERO SLIDESHOW CAROUSEL -->
    <section x-data="{
        currentSlide: 0,
        slides: {{ json_encode($banners) }},
        autoPlayTimer: null,
        init() {
            if (this.slides.length > 1) {
                this.autoPlayTimer = setInterval(() => {
                    this.nextSlide();
                }, 6000);
            }
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        }
    }" class="relative w-full overflow-hidden bg-neutral-900 text-white min-h-[420px] sm:min-h-[550px] lg:min-h-[680px] flex items-center">
        
        <template x-for="(slide, index) in slides" :key="slide.id">
            <div x-show="currentSlide === index" 
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full">
                
                <!-- Background Image -->
                <img :src="slide.image_url" 
                     :alt="slide.title" 
                     class="w-full h-full object-cover object-center brightness-75">
                
                <!-- Overlay Gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/30 flex items-center justify-center text-center p-6">
                    <div class="max-w-3xl space-y-4">
                        <span x-show="slide.badge" 
                              x-text="slide.badge"
                              class="inline-block bg-[#c9a66b] text-white text-[11px] font-bold tracking-[0.25em] uppercase px-4 py-1.5 shadow-sm">
                        </span>
                        
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold uppercase tracking-[0.15em] text-white font-sans leading-tight"
                            x-text="slide.title">
                        </h1>
                        
                        <p class="text-sm sm:text-base text-neutral-200 tracking-wide font-light max-w-xl mx-auto"
                           x-text="slide.subtitle">
                        </p>
                        
                        <div class="pt-4">
                            <a :href="slide.button_url || '/collections/all'" 
                               class="inline-block bg-white text-[#1c1c1c] text-xs font-bold tracking-[0.2em] uppercase py-3.5 px-8 hover:bg-[#c9a66b] hover:text-white transition duration-300 shadow-lg">
                                <span x-text="slide.button_text || 'SHOP NOW'"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Carousel Controls -->
        <button @click="prevSlide()" 
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 text-white/70 hover:text-white p-2 rounded-full bg-black/30 hover:bg-black/60 transition hidden sm:block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 text-white/70 hover:text-white p-2 rounded-full bg-black/30 hover:bg-black/60 transition hidden sm:block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-6 inset-x-0 flex justify-center gap-2 z-20">
            <template x-for="(slide, index) in slides" :key="'dot-' + index">
                <button @click="currentSlide = index" 
                        class="h-1.5 transition-all duration-300 rounded-full"
                        :class="currentSlide === index ? 'w-8 bg-white' : 'w-2 bg-white/40'"></button>
            </template>
        </div>
    </section>

    <!-- 2. INFINITE MARQUEE TICKER RIBBON -->
    <div class="bg-[#1c1c1c] text-white py-3.5 overflow-hidden border-y border-neutral-800">
        <div class="animate-marquee whitespace-nowrap text-xs font-semibold tracking-[0.25em] uppercase flex items-center">
            <span class="mx-6">HANDBAG HAVEN</span>
            <span class="text-[#c9a66b]">★</span>
            <span class="mx-6">MOUTOSHI HANDBAG BANGLADESH</span>
            <span class="text-[#c9a66b]">★</span>
            <span class="mx-6">PREMIUM CANVAS TOTE &amp; FASHION BAGS</span>
            <span class="text-[#c9a66b]">★</span>
            <span class="mx-6">CASH ON DELIVERY ALL OVER BANGLADESH</span>
            <span class="text-[#c9a66b]">★</span>
            <span class="mx-6">7 DAYS HASSLE-FREE EXCHANGE</span>
            <span class="text-[#c9a66b]">★</span>
            <span class="mx-6">MOUTOSHI HANDBAG BANGLADESH</span>
            <span class="text-[#c9a66b]">★</span>
            <span class="mx-6">DISCOVER YOUR STYLE AT HANDBAG HAVEN</span>
            <span class="text-[#c9a66b]">★</span>
        </div>
    </div>

    <!-- 3. SHOP BY CATEGORY (CIRCLE / GRID HIGHLIGHTS) -->
    <section class="py-14 sm:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-1">CURATED SELECTIONS</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                EXPLORE CATEGORIES
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 sm:gap-6 text-center">
            @foreach($categories as $cat)
                <a href="{{ route('shop.index', $cat->slug) }}" class="group block">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full overflow-hidden border-2 border-neutral-200 group-hover:border-[#c9a66b] transition-all duration-300 shadow-sm p-0.5">
                        <img src="{{ $cat->image ?? 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=200' }}" 
                             alt="{{ $cat->name }}" 
                             class="w-full h-full object-cover object-center rounded-full group-hover:scale-110 transition duration-500">
                    </div>
                    <span class="mt-3 block text-xs font-semibold uppercase tracking-wider text-neutral-800 group-hover:text-[#c9a66b] transition">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 4. JUST LANDED (FEATURED / NEW ARRIVALS) -->
    <section class="py-12 bg-[#faf9f7] border-y border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-10 gap-4">
                <div>
                    <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-1">AUTUMN / WINTER 2026</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                        JUST LANDED
                    </h2>
                </div>
                <a href="{{ route('shop.index', 'all') }}" 
                   class="text-xs font-bold tracking-[0.18em] uppercase text-neutral-900 hover:text-[#c9a66b] transition flex items-center gap-1.5 pb-1 border-b-2 border-neutral-900 hover:border-[#c9a66b]">
                    <span>VIEW ALL PRODUCTS</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($justLanded as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. EDITORIAL SPLIT PROMO BANNER (BAG THE VIBE) -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Left Banner -->
            <div class="relative group overflow-hidden bg-neutral-900 min-h-[380px] sm:min-h-[460px] flex items-end p-8 text-white">
                <img src="https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=1000" 
                     alt="Shoulder Bags" 
                     class="absolute inset-0 w-full h-full object-cover object-center brightness-75 group-hover:scale-105 transition duration-700">
                <div class="relative z-10 space-y-3">
                    <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block">ICONIC ESSENTIALS</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-white font-sans">
                        THE CROISSANT & SHOULDER DROP
                    </h3>
                    <p class="text-xs text-neutral-300 max-w-md font-light leading-relaxed">
                        Soft dumpling silhouettes, lightweight waterproof leather, crafted for all day comfort.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('shop.index', 'shoulder-bags') }}" 
                           class="inline-block bg-white text-[#1c1c1c] text-xs font-bold tracking-[0.18em] uppercase py-3 px-6 hover:bg-[#c9a66b] hover:text-white transition">
                            SHOP SHOULDER BAGS
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Banner -->
            <div class="relative group overflow-hidden bg-neutral-900 min-h-[380px] sm:min-h-[460px] flex items-end p-8 text-white">
                <img src="https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=800" 
                     alt="Executive Wallets" 
                     class="absolute inset-0 w-full h-full object-cover object-center brightness-75 group-hover:scale-105 transition duration-700">
                <div class="relative z-10 space-y-3">
                    <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block">SMART ACCESSORIES</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-white font-sans">
                        RFID POP-UP WALLETS & CLUTCHES
                    </h3>
                    <p class="text-xs text-neutral-300 max-w-md font-light leading-relaxed">
                        Anti-skimming aluminum safety with 1-click staircase card pop-up mechanism.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('shop.index', 'wallets-card-holders') }}" 
                           class="inline-block bg-white text-[#1c1c1c] text-xs font-bold tracking-[0.18em] uppercase py-3 px-6 hover:bg-[#c9a66b] hover:text-white transition">
                            EXPLORE WALLETS
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 6. BEST SELLERS / SALE GRID -->
    <section class="py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-neutral-200">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-1">MOST LOVED BY CUSTOMERS</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                BEST SELLING BAGS
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 7. VALUE PROPOSITIONS & TRUST BADGES (TEXT WITH ICONS) -->
    <section class="bg-[#faf8f5] py-14 border-y border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center sm:text-left">
                
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#1c1c1c] text-[#c9a66b] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">FAST NATIONWIDE DELIVERY</h4>
                        <p class="text-xs text-neutral-500 mt-1">24-48 hours inside Dhaka, 48-72 hours across Bangladesh.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#1c1c1c] text-[#c9a66b] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">CASH ON DELIVERY</h4>
                        <p class="text-xs text-neutral-500 mt-1">Check your parcel in front of the delivery person before paying.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#1c1c1c] text-[#c9a66b] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">7-DAY EASY EXCHANGE</h4>
                        <p class="text-xs text-neutral-500 mt-1">Found any size or color mismatch? Instant replacement guaranteed.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#1c1c1c] text-[#c9a66b] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">100% QUALITY PROMISE</h4>
                        <p class="text-xs text-neutral-500 mt-1">Premium vegan leather, metallic zippers, and reinforced straps.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 8. COMMUNITY & INSTAGRAM LOOKBOOK FEED -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-1">#MOUTOSHIHANDBAG</span>
            <h2 class="text-2xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                DISCOVER YOUR STYLE AT HANDBAG HAVEN
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="aspect-square overflow-hidden bg-neutral-100 group">
                <img src="{{ asset('assets/images/bags/canvas-tote-black.jpg') }}" alt="Moutoshi Canvas Tote" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="aspect-square overflow-hidden bg-neutral-100 group">
                <img src="{{ asset('assets/images/bags/canvas-tote-beige.jpg') }}" alt="Moutoshi Beige Tote" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="aspect-square overflow-hidden bg-neutral-100 group">
                <img src="{{ asset('assets/images/bags/canvas-college-varsity.jpg') }}" alt="Moutoshi Varsity Bag" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="aspect-square overflow-hidden bg-neutral-100 group">
                <img src="{{ asset('assets/images/bags/minimalist-shoulder-bag.jpg') }}" alt="Moutoshi Minimalist Bag" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="aspect-square overflow-hidden bg-neutral-100 group">
                <img src="{{ asset('assets/images/bags/olive-green-tote.jpg') }}" alt="Moutoshi Olive Tote" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="aspect-square overflow-hidden bg-neutral-100 group">
                <img src="{{ asset('assets/images/bags/croissant-dumpling-bag.jpg') }}" alt="Moutoshi Croissant Bag" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
        </div>
    </section>

@endsection

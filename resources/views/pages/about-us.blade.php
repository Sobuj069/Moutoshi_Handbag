@extends('layouts.app')

@section('title', 'About Us | Handbag Haven | Moutoshi Handbag BD')
@section('meta_description', 'Moutoshi Handbag was created to bring something extraordinary to fashion — bags that elevate your style and empower you to lead with confidence.')

@section('content')

    <!-- Hero Section -->
    <div class="relative bg-neutral-900 text-white py-24 sm:py-32 overflow-hidden text-center">
        <div class="absolute inset-0 opacity-40">
            <img src="{{ asset('assets/images/bags/canvas-tote-black.jpg') }}" 
                 alt="Moutoshi Handbag Story" 
                 class="w-full h-full object-cover object-center filter blur-xs">
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6">
            <span class="text-xs font-bold tracking-[0.3em] uppercase text-[#c9a66b] block mb-3">
                🌟 HANDBAG HAVEN • EST. DHAKA, BANGLADESH 🌟
            </span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold uppercase tracking-[0.15em] text-white font-sans leading-tight">
                DISCOVER YOUR STYLE. OWN YOUR MOMENT.
            </h1>
            <p class="mt-4 text-sm sm:text-base text-neutral-200 max-w-2xl mx-auto font-light leading-relaxed">
                Moutoshi Handbag was created to bring something extraordinary to fashion — bags that elevate your everyday style and offer the perfect accessory for every occasion.
            </p>
        </div>
    </div>

    <!-- Section 1: Where It All Began -->
    <section class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6">
                <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block">WHERE IT ALL BEGAN</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold uppercase tracking-[0.12em] text-neutral-900 font-sans leading-tight">
                    CONFIDENCE &amp; CRAFTSMANSHIP
                </h2>
                <p class="text-xs sm:text-sm text-neutral-600 font-light leading-relaxed">
                    With thousands of designs and fabric choices, we carefully handpick, test, and curate only the finest pieces that marry everyday utility with aesthetic charm.
                </p>
                <p class="text-xs sm:text-sm text-neutral-600 font-light leading-relaxed">
                    We noticed how women and students in Bangladesh were searching for washable, heavy-duty canvas bags and chic handbags that look stunning anywhere from university campuses to office meetings and weekend outings. Moutoshi Handbag bridges that divide with meticulously crafted pieces.
                </p>
            </div>
            <div class="aspect-[4/3] bg-neutral-100 overflow-hidden shadow-xl">
                <img src="{{ asset('assets/images/bags/canvas-tote-beige.jpg') }}" 
                     alt="Craftsmanship" class="w-full h-full object-cover">
            </div>
        </div>
    </section>

    <!-- Section 2: Why Moutoshi Handbag? -->
    <section class="bg-[#faf8f5] py-20 border-y border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">OUR PHILOSOPHY</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                    WHY MOUTOSHI HANDBAG?
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center sm:text-left">
                <div class="bg-white p-8 border border-neutral-200 shadow-2xs space-y-3">
                    <span class="text-2xl text-[#c9a66b] font-bold">01</span>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">BEST FROM THOUSANDS</h3>
                    <p class="text-xs text-neutral-600 font-light leading-relaxed">
                        We reject 95% of prototypes. Only pieces that pass scratch resistance, weight distribution, and durable stitching earn the Patchee label.
                    </p>
                </div>

                <div class="bg-white p-8 border border-neutral-200 shadow-2xs space-y-3">
                    <span class="text-2xl text-[#c9a66b] font-bold">02</span>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">STYLE WITH PURPOSE</h3>
                    <p class="text-xs text-neutral-600 font-light leading-relaxed">
                        Every zipper, compartment, and magnetic snap is engineered so your phone, wallet, cosmetics, and tech gadgets stay organized without clutter.
                    </p>
                </div>

                <div class="bg-white p-8 border border-neutral-200 shadow-2xs space-y-3">
                    <span class="text-2xl text-[#c9a66b] font-bold">03</span>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">WE SPARK TRENDS</h3>
                    <p class="text-xs text-neutral-600 font-light leading-relaxed">
                        We don’t follow conventional fast fashion. We introduce avant-garde silhouettes like our viral croissant dumpling bags to set trends across South Asia.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Our Journey -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center space-y-6">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block">MILESTONES</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                A GROWING MOVEMENT
            </h2>
            <p class="text-xs sm:text-sm text-neutral-600 font-light leading-relaxed">
                What began as a passionate dream in Dhaka, Bangladesh, has now served over 50,000 satisfied customers nationwide and is expanding internationally to Sri Lanka, Maldives, and beyond.
            </p>
            <div class="pt-6">
                <a href="{{ route('shop.index', 'all') }}" 
                   class="inline-block bg-[#1c1c1c] text-white text-xs font-bold tracking-[0.2em] uppercase py-4 px-8 hover:bg-black transition shadow-md">
                    DISCOVER THE COLLECTION
                </a>
            </div>
        </div>
    </section>

@endsection

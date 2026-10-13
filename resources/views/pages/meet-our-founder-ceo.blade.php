@extends('layouts.app')

@section('title', 'Meet Our Founder & CEO | Moutoshi Handbag BD')
@section('meta_description', 'Discover the vision, journey, and story behind Moutoshi Handbag - Handbag Haven.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                LEADERSHIP & VISION
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                MEET OUR FOUNDER & CEO
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-lg mx-auto font-light leading-relaxed">
                Building Handbag Haven — a homegrown fashion brand rooted in quality, student and professional empowerment, and authentic style.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 aspect-[3/4] bg-neutral-100 overflow-hidden shadow-2xl">
                <img src="{{ asset('assets/images/fb_profile.jpg') }}" 
                     alt="Founder & CEO" class="w-full h-full object-cover">
            </div>

            <div class="lg:col-span-7 space-y-6 text-xs sm:text-sm text-neutral-600 font-light leading-relaxed">
                <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block">A LETTER FROM THE FOUNDER</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wide text-neutral-900 font-sans leading-tight">
                    "WE BELIEVE CONFIDENCE IS THE MOST POWERFUL ACCESSORY YOU CAN WEAR."
                </h2>
                
                <p>
                    When I founded Moutoshi Handbag (Handbag Haven), our dream was simple: to design canvas tote bags, college varsity shoulder bags, and luxury handbags that combine durability, supreme aesthetics, and everyday functionality without extreme price tags.
                </p>

                <p>
                    Every tote and bag from Moutoshi Handbag is crafted with heavy-duty fabrics, robust zippers, and meticulous stitching tested to effortlessly carry laptops, books, makeup, and daily essentials.
                </p>

                <p>
                    Thank you for welcoming Moutoshi Handbag into your everyday lifestyle. Seeing confident students, professionals, and travelers across Bangladesh carrying our bags with joy is our ultimate motivation.
                </p>

                <div class="pt-4 border-t border-neutral-200">
                    <span class="font-bold text-neutral-900 uppercase tracking-wider block text-xs">FOUNDER & CHIEF EXECUTIVE OFFICER</span>
                    <span class="text-neutral-500 text-xs">Moutoshi Handbag BD • Handbag Haven</span>
                </div>
            </div>

        </div>
    </div>

@endsection

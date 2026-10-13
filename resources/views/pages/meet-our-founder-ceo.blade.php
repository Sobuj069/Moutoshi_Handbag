@extends('layouts.app')

@section('title', 'Meet Our Founder & CEO | The Patchee BD')
@section('meta_description', 'Discover the vision, journey, and story behind The Patchee BD from our Founder & CEO.')

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
                Building a modern homegrown fashion brand rooted in quality, empowerment, and uncompromising style.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 aspect-[3/4] bg-neutral-100 overflow-hidden shadow-2xl">
                <img src="https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=800" 
                     alt="Founder & CEO" class="w-full h-full object-cover">
            </div>

            <div class="lg:col-span-7 space-y-6 text-xs sm:text-sm text-neutral-600 font-light leading-relaxed">
                <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block">A LETTER FROM THE FOUNDER</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wide text-neutral-900 font-sans leading-tight">
                    "WE BELIEVE CONFIDENCE IS THE MOST POWERFUL ACCESSORY YOU CAN WEAR."
                </h2>
                
                <p>
                    When I started The Patchee in Dhaka, I noticed a significant void in Bangladesh’s retail landscape: international designer bags were either prohibitively expensive or cumbersome to import, while local alternatives lacked the sleek silhouettes and longevity that contemporary women deserved.
                </p>

                <p>
                    Our mission was straightforward yet uncompromising — to deliver bespoke European-inspired craftsmanship directly to Bangladesh at honest, accessible prices. Every bag you touch from The Patchee features hand-finished edges, robust metallic alloys, and premium scratch-resistant vegan leather tested to last through daily commutes.
                </p>

                <p>
                    Thank you for welcoming The Patchee into your daily life. Seeing thousands of empowered students, entrepreneurs, executives, and travelers carry our bags with pride across Bangladesh and internationally is what drives our creative studio every single day.
                </p>

                <div class="pt-4 border-t border-neutral-200">
                    <span class="font-bold text-neutral-900 uppercase tracking-wider block text-xs">FOUNDER & CHIEF EXECUTIVE OFFICER</span>
                    <span class="text-neutral-500 text-xs">The Patchee Bangladesh</span>
                </div>
            </div>

        </div>
    </div>

@endsection

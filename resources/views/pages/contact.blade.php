@extends('layouts.app')

@section('title', 'Contact Us | The Patchee BD')
@section('meta_description', 'Get in touch with The Patchee BD customer support team. Reach us via phone, email, or visit us in Banani, Dhaka.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                GET IN TOUCH
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                CONTACT US
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-lg mx-auto font-light leading-relaxed">
                Have a question about an order, styling advice, or product availability? We are here to help.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            
            <!-- Contact Form -->
            <div class="bg-white border border-neutral-200 p-8 shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200 mb-6">
                    SEND US A MESSAGE
                </h2>

                <form action="{{ route('page.contact.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Your Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Tanzina Islam"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Email Address *</label>
                            <input type="email" name="email" required placeholder="you@email.com"
                                   class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" placeholder="01XXXXXXXXX"
                                   class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Message *</label>
                        <textarea name="message" required rows="5" placeholder="How can we assist you today?..."
                                  class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition"></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-sm">
                        SEND MESSAGE
                    </button>
                </form>
            </div>

            <!-- Contact Information & Perks -->
            <div class="space-y-8 flex flex-col justify-between">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200 mb-6">
                        HEADQUARTERS & CUSTOMER CARE
                    </h2>

                    <div class="space-y-5 text-xs text-neutral-600 font-light">
                        <div class="flex items-start gap-4">
                            <span class="text-base text-[#c9a66b]">📍</span>
                            <div>
                                <strong class="font-bold text-neutral-900 uppercase block mb-0.5">Showroom / Office</strong>
                                <p>{{ \App\Models\Setting::get('address', 'Banani, Road 11, Block D, Dhaka, Bangladesh') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="text-base text-[#c9a66b]">📞</span>
                            <div>
                                <strong class="font-bold text-neutral-900 uppercase block mb-0.5">Hotline</strong>
                                <p>{{ \App\Models\Setting::get('phone', '+880 1711-223344') }} (10:00 AM – 10:00 PM, Daily)</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="text-base text-[#c9a66b]">✉️</span>
                            <div>
                                <strong class="font-bold text-neutral-900 uppercase block mb-0.5">Customer Support Email</strong>
                                <p>{{ \App\Models\Setting::get('email', 'support@moutoshihandbag.shop') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="text-base text-[#1877F2]">🌐</span>
                            <div>
                                <strong class="font-bold text-neutral-900 uppercase block mb-0.5">Official Facebook Page</strong>
                                <a href="https://www.facebook.com/profile.php?id=61559952171891" target="_blank" class="text-[#1877F2] hover:underline font-medium">
                                    facebook.com/MoutoshiHandbag (Profile ID: 61559952171891)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Perks Grid -->
                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-neutral-200">
                    <div class="p-4 bg-[#faf8f5] border border-neutral-200">
                        <span class="text-lg text-[#c9a66b] block mb-1">🚚</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 font-sans">Fast Delivery</h4>
                        <p class="text-[11px] text-neutral-500 mt-0.5">24-48h Dhaka | 48-72h Nationwide</p>
                    </div>

                    <div class="p-4 bg-[#faf8f5] border border-neutral-200">
                        <span class="text-lg text-[#c9a66b] block mb-1">🔄</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 font-sans">7 Days Return</h4>
                        <p class="text-[11px] text-neutral-500 mt-0.5">Hassle-free exchange policy</p>
                    </div>

                    <div class="p-4 bg-[#faf8f5] border border-neutral-200">
                        <span class="text-lg text-[#c9a66b] block mb-1">✨</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 font-sans">Emerging Trends</h4>
                        <p class="text-[11px] text-neutral-500 mt-0.5">Stay on-trend with us</p>
                    </div>

                    <div class="p-4 bg-[#faf8f5] border border-neutral-200">
                        <span class="text-lg text-[#c9a66b] block mb-1">💎</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 font-sans">Premium Quality</h4>
                        <p class="text-[11px] text-neutral-500 mt-0.5">Luxury with every purchase</p>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

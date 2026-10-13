<footer class="bg-[#1c1c1c] text-white pt-16 pb-12 border-t border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Top Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-neutral-800">
            
            <!-- Brand Column (2 cols on desktop) -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="inline-block">
                    <span class="text-2xl font-extrabold tracking-[0.25em] uppercase text-white font-sans block">
                        PATCHEE
                    </span>
                    <span class="block text-[8px] tracking-[0.4em] uppercase text-neutral-400 font-medium">
                        BANGLADESH
                    </span>
                </a>
                <p class="text-xs text-neutral-400 leading-relaxed font-light max-w-sm">
                    The Patchee Bangladesh is your premier fashion destination for modern, versatile, and high-quality tote bags, side bags, shoulder bags, and luxury accessories. Designed with passion for everyday elegance.
                </p>
                <div class="text-xs text-neutral-400 space-y-1.5 pt-1">
                    <p>📍 {{ \App\Models\Setting::get('address', 'Banani, Road 11, Block D, Dhaka, Bangladesh') }}</p>
                    <p>📞 {{ \App\Models\Setting::get('phone', '+880 1711-223344') }} (Daily 10 AM - 10 PM)</p>
                    <p>✉️ {{ \App\Models\Setting::get('email', 'support@thepatchee.com') }}</p>
                </div>
            </div>

            <!-- Customer Care -->
            <div>
                <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-white mb-4 font-sans">
                    CUSTOMER CARE
                </h4>
                <ul class="space-y-2.5 text-xs text-neutral-400 font-light tracking-wide uppercase">
                    <li><a href="{{ route('order.track') }}" class="hover:text-white transition">Track Your Order</a></li>
                    <li><a href="{{ route('page.exchange-policy') }}" class="hover:text-white transition">Exchange & Return</a></li>
                    <li><a href="{{ route('page.faqs') }}" class="hover:text-white transition">Help & FAQs</a></li>
                    <li><a href="{{ route('page.complaint') }}" class="text-red-400 hover:text-white transition">Complaint Portal</a></li>
                    <li><a href="{{ route('page.contact') }}" class="hover:text-white transition">Contact Us</a></li>
                    <li><a href="{{ route('page.privacy-policy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('page.terms') }}" class="hover:text-white transition">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Company & Journal -->
            <div>
                <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-white mb-4 font-sans">
                    ABOUT PATCHEE
                </h4>
                <ul class="space-y-2.5 text-xs text-neutral-400 font-light tracking-wide uppercase">
                    <li><a href="{{ route('page.about') }}" class="hover:text-white transition">Our Story (About Us)</a></li>
                    <li><a href="{{ route('page.founder') }}" class="hover:text-white transition">Meet Founder & CEO</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">Bag Talks Journal</a></li>
                    <li><a href="{{ route('page.b2b') }}" class="text-[#c9a66b] hover:text-white transition">Corporate & B2B</a></li>
                    <li><a href="{{ route('customer.login') }}" class="hover:text-white transition">My Account</a></li>
                </ul>
            </div>

            <!-- Newsletter Subscription -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-white font-sans">
                    NEWSLETTER
                </h4>
                <p class="text-xs text-neutral-400 font-light leading-relaxed">
                    Subscribe to receive secret discount drops, restock alerts, and VIP fashion updates.
                </p>
                <form @submit.prevent="alert('Thank you for subscribing to The Patchee BD!')" class="space-y-2">
                    <div class="relative">
                        <input type="email" required placeholder="Enter your email" 
                               class="w-full bg-neutral-900 border border-neutral-700 px-4 py-2.5 text-xs text-white placeholder-neutral-500 focus:outline-none focus:border-[#c9a66b]">
                        <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 bg-[#c9a66b] text-white text-[10px] font-bold uppercase tracking-wider hover:bg-[#b08f57] transition">
                            JOIN
                        </button>
                    </div>
                </form>

                <!-- Social Icons -->
                <div class="pt-2 flex items-center space-x-4 text-neutral-400">
                    <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" target="_blank" class="hover:text-white transition" aria-label="Facebook">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}" target="_blank" class="hover:text-white transition" aria-label="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Bottom Footer (Copyright & Payment Badges) -->
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-neutral-500 font-light gap-4">
            <p>© {{ date('Y') }} The Patchee Bangladesh. All rights reserved. Crafted with luxury aesthetics.</p>
            <div class="flex items-center gap-3 text-neutral-400 text-[11px] font-medium uppercase tracking-wider">
                <span class="px-2 py-1 bg-neutral-900 rounded-sm">Cash on Delivery</span>
                <span class="px-2 py-1 bg-neutral-900 rounded-sm">bKash</span>
                <span class="px-2 py-1 bg-neutral-900 rounded-sm">Nagad</span>
                <span class="px-2 py-1 bg-neutral-900 rounded-sm">Visa / Master</span>
            </div>
        </div>

    </div>
</footer>

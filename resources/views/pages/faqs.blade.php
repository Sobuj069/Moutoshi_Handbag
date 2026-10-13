@extends('layouts.app')

@section('title', 'Frequently Asked Questions | The Patchee BD')
@section('meta_description', 'Find quick answers to common questions about ordering, delivery, tracking, cash on delivery, and exchange policies.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                SUPPORT DESK
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                FREQUENTLY ASKED QUESTIONS
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-lg mx-auto font-light leading-relaxed">
                Find quick answers to the most common questions about orders, shipping, payment, and quality assurance.
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16"
         x-data="{
             activeCategory: 'orders',
             activeFaq: null,
             toggle(id) {
                 this.activeFaq = (this.activeFaq === id ? null : id);
             }
         }">
        
        <!-- Category Filter Tabs -->
        <div class="flex items-center justify-center gap-2 flex-wrap mb-12 text-xs uppercase font-bold tracking-wider">
            <button type="button" 
                    @click="activeCategory = 'orders'"
                    class="px-4 py-2 border transition rounded-xs"
                    :class="activeCategory === 'orders' ? 'bg-[#1c1c1c] text-white border-[#1c1c1c]' : 'bg-white text-neutral-700 border-neutral-300 hover:border-black'">
                📦 Orders & Tracking
            </button>
            <button type="button" 
                    @click="activeCategory = 'shipping'"
                    class="px-4 py-2 border transition rounded-xs"
                    :class="activeCategory === 'shipping' ? 'bg-[#1c1c1c] text-white border-[#1c1c1c]' : 'bg-white text-neutral-700 border-neutral-300 hover:border-black'">
                🚚 Shipping & Delivery
            </button>
            <button type="button" 
                    @click="activeCategory = 'returns'"
                    class="px-4 py-2 border transition rounded-xs"
                    :class="activeCategory === 'returns' ? 'bg-[#1c1c1c] text-white border-[#1c1c1c]' : 'bg-white text-neutral-700 border-neutral-300 hover:border-black'">
                🔄 Returns & Exchanges
            </button>
            <button type="button" 
                    @click="activeCategory = 'payment'"
                    class="px-4 py-2 border transition rounded-xs"
                    :class="activeCategory === 'payment' ? 'bg-[#1c1c1c] text-white border-[#1c1c1c]' : 'bg-white text-neutral-700 border-neutral-300 hover:border-black'">
                💳 Payment & COD
            </button>
            <button type="button" 
                    @click="activeCategory = 'products'"
                    class="px-4 py-2 border transition rounded-xs"
                    :class="activeCategory === 'products' ? 'bg-[#1c1c1c] text-white border-[#1c1c1c]' : 'bg-white text-neutral-700 border-neutral-300 hover:border-black'">
                👜 Bag Materials & Quality
            </button>
        </div>

        <!-- 1. Orders FAQs -->
        <div x-show="activeCategory === 'orders'" class="space-y-4">
            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('ord-1')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>How do I check the status of my order?</span>
                    <span x-text="activeFaq === 'ord-1' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'ord-1'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    Visit our <a href="{{ route('order.track') }}" class="font-bold underline text-black">Track Order</a> page. Enter your Order ID (e.g. PTC-88210) and your mobile number to view live courier tracking.
                </div>
            </div>

            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('ord-2')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>Can I cancel or modify my order after placing it?</span>
                    <span x-text="activeFaq === 'ord-2' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'ord-2'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    Yes! You can modify your address, bag color, or cancel your order when our customer support team calls you for verification before dispatch.
                </div>
            </div>
        </div>

        <!-- 2. Shipping FAQs -->
        <div x-show="activeCategory === 'shipping'" class="space-y-4" x-cloak>
            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('ship-1')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>What are your delivery charges and timeframes?</span>
                    <span x-text="activeFaq === 'ship-1' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'ship-1'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    Inside Dhaka delivery charge is <strong>৳ 60</strong> (delivered within 24–48 hours). Outside Dhaka delivery charge is <strong>৳ 120</strong> (delivered within 48–72 hours across Bangladesh). Orders over ৳ 2,500 enjoy completely FREE delivery!
                </div>
            </div>

            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('ship-2')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>Can I check the parcel in front of the delivery rider?</span>
                    <span x-text="activeFaq === 'ship-2' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'ship-2'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    Yes! We actively encourage all our valued customers to open the poly parcel and verify the bag, stitching, and color in front of the delivery rider before handing over payment.
                </div>
            </div>
        </div>

        <!-- 3. Returns FAQs -->
        <div x-show="activeCategory === 'returns'" class="space-y-4" x-cloak>
            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('ret-1')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>What is your exchange policy?</span>
                    <span x-text="activeFaq === 'ret-1' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'ret-1'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    We offer a 7-day exchange guarantee. If you notice any manufacturer defect or want to change colors, contact us within 48 hours and we will arrange replacement.
                </div>
            </div>
        </div>

        <!-- 4. Payment FAQs -->
        <div x-show="activeCategory === 'payment'" class="space-y-4" x-cloak>
            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('pay-1')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>Which payment methods are accepted?</span>
                    <span x-text="activeFaq === 'pay-1' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'pay-1'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    We accept Cash on Delivery (COD) all over Bangladesh, as well as bKash, Nagad, Visa, and Mastercard prepaid options.
                </div>
            </div>
        </div>

        <!-- 5. Products FAQs -->
        <div x-show="activeCategory === 'products'" class="space-y-4" x-cloak>
            <div class="border border-neutral-200 bg-white">
                <button type="button" @click="toggle('prd-1')" class="w-full text-left p-5 flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    <span>What materials are used for Patchee bags?</span>
                    <span x-text="activeFaq === 'prd-1' ? '−' : '+'" class="text-base text-neutral-500"></span>
                </button>
                <div x-show="activeFaq === 'prd-1'" class="px-5 pb-5 text-xs text-neutral-600 font-light leading-relaxed border-t border-neutral-100 pt-3" x-cloak>
                    Our bags are crafted using ultra-premium scratch-resistant vegan PU leather, waterproof ballistic nylon, high-grade electroplated metallic zippers, and reinforced ergonomic shoulder straps.
                </div>
            </div>
        </div>

        <!-- Still have questions footer card -->
        <div class="mt-16 bg-[#faf8f5] border border-neutral-200 p-8 text-center space-y-3">
            <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">STILL HAVE QUESTIONS?</h3>
            <p class="text-xs text-neutral-500 max-w-md mx-auto">Can’t find what you’re looking for? Reach out directly to our dedicated customer support team.</p>
            <div class="pt-2">
                <a href="{{ route('page.contact') }}" class="inline-block bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-[0.2em] py-3 px-6 hover:bg-black transition">
                    CONTACT SUPPORT
                </a>
            </div>
        </div>

    </div>

@endsection

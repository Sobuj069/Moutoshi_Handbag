@extends('layouts.app')

@section('title', 'B2B & Corporate Queries | Moutoshi Handbag BD')
@section('meta_description', 'Wholesale, custom corporate gifting, canvas totes, executive bag orders, and branded merchandise from Moutoshi Handbag BD.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                CORPORATE & WHOLESALE
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                B2B & CORPORATE GIFTING
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-xl mx-auto font-light leading-relaxed">
                Elevate your company gifting with customized executive leather wallets, commuter backpacks, and luxury bags featuring your brand's custom embossing.
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white border border-neutral-200 p-8 sm:p-12 shadow-sm">
            
            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200 mb-6">
                SUBMIT A CORPORATE INQUIRY
            </h2>

            <form action="{{ route('page.b2b.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Company / Organization Name *
                        </label>
                        <input type="text" name="company_name" required placeholder="e.g. BRAC / Grameenphone / Tech Corp"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Contact Person Name *
                        </label>
                        <input type="text" name="contact_person" required placeholder="e.g. Asif Mahmud (HR Director)"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Corporate Email *
                        </label>
                        <input type="email" name="email" required placeholder="asif@company.com"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Phone Number *
                        </label>
                        <input type="tel" name="phone" required placeholder="01XXXXXXXXX"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                        Estimated Order Quantity (Units) *
                    </label>
                    <input type="number" name="quantity" min="10" placeholder="Minimum 10 units"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                        Requirement Details / Preferred Bag Models *
                    </label>
                    <textarea name="details" required rows="4" 
                              placeholder="Please specify bag types (Executive Wallets, Laptop Backpacks, Tote Bags), customization requirements (Logo debossing, custom packaging boxes), and target delivery deadline..."
                              class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition"></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-[#1c1c1c] text-white py-4 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-md">
                    SUBMIT B2B INQUIRY
                </button>
            </form>

        </div>
    </div>

@endsection

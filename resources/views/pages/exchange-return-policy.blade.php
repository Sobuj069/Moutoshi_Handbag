@extends('layouts.app')

@section('title', 'Exchange & Return Policy | The Patchee BD')
@section('meta_description', 'Everything you need to know about The Patchee BD 7-day hassle-free return and exchange policy.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                CUSTOMER CARE
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                EXCHANGE & RETURN POLICY
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-xl mx-auto font-light leading-relaxed">
                We want you to love every Patchee piece. Here's everything you need to know about our straightforward returns and exchanges.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- 3-Step Visual Process -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="bg-white p-6 border border-neutral-200 shadow-2xs space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#1c1c1c] text-white flex items-center justify-center font-bold text-sm mx-auto sm:mx-0">
                    1
                </div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">INITIATE REQUEST</h3>
                <p class="text-xs text-neutral-600 font-light leading-relaxed">
                    Contact us within 48 hours of parcel delivery via email or phone with your Order ID and photos.
                </p>
            </div>

            <div class="bg-white p-6 border border-neutral-200 shadow-2xs space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#1c1c1c] text-white flex items-center justify-center font-bold text-sm mx-auto sm:mx-0">
                    2
                </div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">SHIP IT BACK</h3>
                <p class="text-xs text-neutral-600 font-light leading-relaxed">
                    Pack the bag securely in its original packaging/dust bag and hand it over to our courier partner.
                </p>
            </div>

            <div class="bg-white p-6 border border-neutral-200 shadow-2xs space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#1c1c1c] text-white flex items-center justify-center font-bold text-sm mx-auto sm:mx-0">
                    3
                </div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">GET RESOLVED</h3>
                <p class="text-xs text-neutral-600 font-light leading-relaxed">
                    Once received and inspected, your replacement item or refund is dispatched within 3–5 business days.
                </p>
            </div>
        </div>

        <!-- Policy Content Sections -->
        <div class="space-y-10 text-xs text-neutral-600 font-light leading-relaxed">
            
            <div class="border-b border-neutral-200 pb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans mb-3">
                    📅 RETURN & EXCHANGE WINDOW
                </h2>
                <p>
                    Items must be requested for exchange within <strong>7 calendar days</strong> from the date of physical delivery. Requests made past 7 days cannot be accepted.
                </p>
            </div>

            <div class="border-b border-neutral-200 pb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans mb-3">
                    👜 WHAT CAN BE RETURNED OR EXCHANGED?
                </h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>The item must be completely unused, unworn, and unwashed.</li>
                    <li>All original brand tags, charms, and dust bags must remain intact.</li>
                    <li>Items showing signs of wear, makeup stains, or perfume scents are ineligible.</li>
                    <li>Items bought under "Final Clearance Sale (Up to 80% Off)" are exchangeable for defects only.</li>
                </ul>
            </div>

            <div class="border-b border-neutral-200 pb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans mb-3">
                    🚚 RETURN SHIPPING COSTS
                </h2>
                <p>
                    If you received an incorrect color, damaged parcel, or wrong item, <strong>The Patchee BD covers 100% of return shipping costs</strong>. If you wish to exchange due to personal preference (e.g. you prefer another color), standard return courier fees (৳60 Dhaka / ৳120 Outside) apply.
                </p>
            </div>

            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans mb-3">
                    💬 NEED IMMEDIATE ASSISTANCE?
                </h2>
                <p>
                    Our customer care team is available daily from 10:00 AM to 10:00 PM. Call us at <strong>{{ \App\Models\Setting::get('phone', '+880 1711-223344') }}</strong> or visit our <a href="{{ route('page.complaint') }}" class="text-black font-bold underline">Complaint Portal</a>.
                </p>
            </div>

        </div>

    </div>

@endsection

@extends('layouts.app')

@section('title', 'Customer Complaint Portal | The Patchee BD')
@section('meta_description', 'Direct escalation portal for customer complaints, damaged parcel reports, and delayed delivery resolutions.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-red-600 block mb-2">
                CUSTOMER ESCALATION DESK
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                COMPLAINT & RESOLUTION PORTAL
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-xl mx-auto font-light leading-relaxed">
                Your satisfaction is our absolute priority. If something went wrong with your order or delivery, tell us directly. Every ticket submitted here is reviewed by our Senior Operations Team.
            </p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white border border-neutral-200 p-8 sm:p-12 shadow-sm">
            
            <form action="{{ route('page.complaint.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Order Number / আইডি *
                        </label>
                        <input type="text" name="order_number" required placeholder="e.g. PTC-88210"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Customer Name *
                        </label>
                        <input type="text" name="customer_name" required placeholder="Your full name"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Mobile Phone Number *
                        </label>
                        <input type="tel" name="customer_phone" required placeholder="01XXXXXXXXX"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Nature of Issue *
                        </label>
                        <select name="issue_type" required class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black bg-white">
                            <option value="">Select Issue Category</option>
                            <option value="damaged_parcel">Product Damaged / Stitching Defect</option>
                            <option value="wrong_item">Received Wrong Color or Model</option>
                            <option value="missing_part">Missing Strap / Bag Charm / Dust Bag</option>
                            <option value="delivery_delay">Delivery Unusually Delayed</option>
                            <option value="rider_behavior">Delivery Rider Behavior Issue</option>
                            <option value="other">Other Inquiry / Feedback</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                        Please Describe What Happened *
                    </label>
                    <textarea name="details" required rows="5" 
                              placeholder="Please provide details of the issue so we can verify with our packing camera or courier partner..."
                              class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition"></textarea>
                </div>

                <div class="bg-amber-50 p-4 border border-amber-200 text-xs text-amber-900 space-y-1">
                    <p class="font-bold">⚡ Quick Resolution Guarantee:</p>
                    <p class="font-light">Our Operations Team verifies complaints against CCTV packing recordings and calls back within 12 hours with free exchange or courier redelivery.</p>
                </div>

                <button type="submit" 
                        class="w-full bg-[#1c1c1c] text-white py-4 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-md">
                    REGISTER FORMAL COMPLAINT
                </button>
            </form>

        </div>
    </div>

@endsection

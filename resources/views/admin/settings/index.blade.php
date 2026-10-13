@extends('admin.layouts.admin')

@section('title', 'Store Settings')
@section('header_title', 'Store & Delivery Settings')

@section('content')

    <div class="max-w-4xl bg-white border border-neutral-200 rounded-xs shadow-2xs p-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- 1. Store Identity -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    1. STORE INFORMATION
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Store Name</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'The Patchee Bangladesh' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Store Tagline / Title</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'Stylish Tote Bags & Side Bags for Ladies | Patchee BD' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Customer Care Phone</label>
                        <input type="text" name="phone" value="{{ $settings['phone'] ?? '+880 1711-223344' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Support Email</label>
                        <input type="email" name="email" value="{{ $settings['email'] ?? 'support@thepatchee.com' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Physical Store / Office Address</label>
                        <input type="text" name="address" value="{{ $settings['address'] ?? 'Banani, Road 11, Block D, Dhaka, Bangladesh' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 2. Delivery Rates -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    2. SHIPPING CHARGES & THRESHOLDS (BANGLADESH)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Inside Dhaka Rate (৳)</label>
                        <input type="number" step="1" name="delivery_inside_dhaka" value="{{ $settings['delivery_inside_dhaka'] ?? 60 }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Outside Dhaka Rate (৳)</label>
                        <input type="number" step="1" name="delivery_outside_dhaka" value="{{ $settings['delivery_outside_dhaka'] ?? 120 }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Free Delivery Min Spend (৳)</label>
                        <input type="number" step="1" name="free_delivery_threshold" value="{{ $settings['free_delivery_threshold'] ?? 2500 }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 3. Announcement Bar -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    3. TOP ANNOUNCEMENT BAR MESSAGES
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Announcement Message 1</label>
                        <input type="text" name="announcement_1" value="{{ $settings['announcement_1'] ?? 'No Tricks, Just BOO-tiful Deals 👻 | Up to 75% Off' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Announcement Message 2</label>
                        <input type="text" name="announcement_2" value="{{ $settings['announcement_2'] ?? 'Free Delivery on prepaid orders over ৳2,500' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Announcement Message 3</label>
                        <input type="text" name="announcement_3" value="{{ $settings['announcement_3'] ?? 'Cash On Delivery Available All Over Bangladesh 🇧🇩' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 4. Social Links -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    4. SOCIAL MEDIA
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'https://facebook.com/thepatchee' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://instagram.com/thepatchee' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-neutral-200">
                <button type="submit" class="px-8 py-3.5 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition rounded-xs shadow-sm">
                    Save Store Settings
                </button>
            </div>
        </form>
    </div>

@endsection

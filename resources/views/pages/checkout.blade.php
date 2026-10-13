@extends('layouts.app')

@section('title', 'Checkout | The Patchee BD')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16"
         x-data="{
             cityZone: 'inside_dhaka',
             division: 'dhaka',
             subtotal: {{ $subtotal }},
             insideRate: {{ ($insideDhaka == 60 || !$insideDhaka) ? 80 : $insideDhaka }},
             outsideRate: {{ ($outsideDhaka == 120 || !$outsideDhaka) ? 130 : $outsideDhaka }},
             onDivisionChange() {
                 if (this.division === 'dhaka') {
                     this.cityZone = 'inside_dhaka';
                 } else {
                     this.cityZone = 'outside_dhaka';
                 }
             },
             onZoneChange(zone) {
                 this.cityZone = zone;
                 if (zone === 'inside_dhaka') {
                     this.division = 'dhaka';
                 } else if (this.division === 'dhaka') {
                     this.division = 'chattogram';
                 }
             },
             get deliveryCharge() {
                 return (this.cityZone === 'inside_dhaka') ? this.insideRate : this.outsideRate;
             },
             get grandTotal() {
                 return this.subtotal + this.deliveryCharge;
             }
         }">
        
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                CHECKOUT
            </h1>
            <p class="text-xs text-neutral-500 uppercase tracking-wider mt-1">Please enter your shipping address to place the order</p>
        </div>

        @if(empty($cart) || count($cart) === 0)
            <div class="max-w-md mx-auto text-center py-16 bg-white border border-neutral-200 p-8 shadow-xs">
                <div class="w-16 h-16 mx-auto mb-4 text-neutral-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h2 class="text-lg font-bold uppercase tracking-wider text-neutral-900 mb-2">Your Shopping Bag is Empty</h2>
                <p class="text-xs text-neutral-500 mb-6">Please select and add a handbag to your bag before proceeding to checkout.</p>
                <a href="{{ route('shop.index', 'all') }}" class="inline-block bg-[#1c1c1c] text-white text-xs font-bold tracking-[0.2em] uppercase py-3.5 px-8 hover:bg-neutral-800 transition">
                    EXPLORE BAGS (ব্যাগ দেখুন)
                </a>
            </div>
        @else
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
                
                <!-- Left: Shipping & Payment Details (7 cols) -->
                <div class="lg:col-span-7 space-y-8">
                    
                    <!-- 1. Customer Information -->
                    <div class="bg-white border border-neutral-200 p-6 sm:p-8">
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#1c1c1c] text-white flex items-center justify-center text-xs">1</span>
                            CUSTOMER INFORMATION (গ্রাহকের তথ্য)
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                                    Full Name / আপনার পুরো নাম *
                                </label>
                                <input type="text" name="customer_name" required value="{{ old('customer_name') }}"
                                       placeholder="e.g. Nusrat Jahan"
                                       class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                                @error('customer_name') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                                        Mobile Number / মোবাইল নাম্বার *
                                    </label>
                                    <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}"
                                           placeholder="01XXXXXXXXX"
                                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                                    @error('customer_phone') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                                        Email / ইমেইল (Optional)
                                    </label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                           placeholder="you@email.com"
                                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Delivery Location -->
                    <div class="bg-white border border-neutral-200 p-6 sm:p-8">
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#1c1c1c] text-white flex items-center justify-center text-xs">2</span>
                            DELIVERY ZONE & ADDRESS (ডেলিভারি ঠিকানা)
                        </h2>

                        <!-- 2.1 Division Selector (Auto Calculates Delivery Charge) -->
                        <div class="mb-5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5 flex items-center justify-between">
                                <span>Select Division / বিভাগ নির্বাচন করুন *</span>
                                <span class="text-[11px] font-semibold text-neutral-500 font-sans" x-text="division === 'dhaka' ? 'Inside Dhaka (৳ ' + insideRate + ')' : 'Outside Dhaka (৳ ' + outsideRate + ')'"></span>
                            </label>
                            <div class="relative">
                                <select name="division" x-model="division" @change="onDivisionChange()" 
                                        class="w-full text-xs px-4 py-3 border border-neutral-300 bg-white font-medium focus:outline-none focus:border-black transition appearance-none cursor-pointer">
                                    <option value="dhaka">Dhaka Division (ঢাকা বিভাগ) — Delivery ৳ 80</option>
                                    <option value="chattogram">Chattogram / Chittagong (চট্টগ্রাম বিভাগ) — Delivery ৳ 130</option>
                                    <option value="rajshahi">Rajshahi (রাজশাহী বিভাগ) — Delivery ৳ 130</option>
                                    <option value="khulna">Khulna (খুলনা বিভাগ) — Delivery ৳ 130</option>
                                    <option value="barishal">Barishal (বরিশাল বিভাগ) — Delivery ৳ 130</option>
                                    <option value="sylhet">Sylhet (সিলেট বিভাগ) — Delivery ৳ 130</option>
                                    <option value="rangpur">Rangpur (রংপুর বিভাগ) — Delivery ৳ 130</option>
                                    <option value="mymensingh">Mymensingh (ময়মনসিংহ বিভাগ) — Delivery ৳ 130</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-neutral-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-[11px] text-neutral-500 mt-1.5 flex items-center gap-1.5">
                                <span class="text-green-600 font-bold">✓</span>
                                <span>ঢাকা বিভাগ নির্বাচন করলে ডেলিভারি চার্জ অটোমেটিক <strong>৳ ৮০</strong>, ঢাকার বাইরের যেকোনো বিভাগে <strong>৳ ১৩০</strong> হবে।</span>
                            </p>
                        </div>

                        <!-- 2.2 Zone Toggle / Visual Area Cards -->
                        <div class="mb-5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                Delivery Area (ডেলিভারি এরিয়া) *
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <label class="flex items-center justify-between p-4 border cursor-pointer transition"
                                       :class="cityZone === 'inside_dhaka' ? 'border-[#1c1c1c] bg-neutral-50 shadow-xs ring-1 ring-black' : 'border-neutral-200 text-neutral-600'">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="city_zone" value="inside_dhaka" x-model="cityZone" @change="onZoneChange('inside_dhaka')" class="text-black">
                                        <div>
                                            <span class="font-bold block">Inside Dhaka (ঢাকার ভেতর)</span>
                                            <span class="text-[11px] text-neutral-500">Delivery in 24-48 Hours</span>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-neutral-900 text-sm">৳ <span x-text="insideRate">80</span></span>
                                </label>

                                <label class="flex items-center justify-between p-4 border cursor-pointer transition"
                                       :class="cityZone === 'outside_dhaka' ? 'border-[#1c1c1c] bg-neutral-50 shadow-xs ring-1 ring-black' : 'border-neutral-200 text-neutral-600'">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="city_zone" value="outside_dhaka" x-model="cityZone" @change="onZoneChange('outside_dhaka')" class="text-black">
                                        <div>
                                            <span class="font-bold block">Outside Dhaka (ঢাকার বাইরে)</span>
                                            <span class="text-[11px] text-neutral-500">Delivery in 48-72 Hours</span>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-neutral-900 text-sm">৳ <span x-text="outsideRate">130</span></span>
                                </label>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                                Full Address / সম্পূর্ণ ডেলিভারি ঠিকানা *
                            </label>
                            <textarea name="delivery_address" required rows="3" 
                                      placeholder="House number, Road number, Area / Thana, City / District"
                                      class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                                Order Notes / বিশেষ নির্দেশনা (Optional)
                            </label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                   placeholder="e.g. Please deliver after 4 PM"
                                   class="w-full text-xs px-4 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                        </div>
                    </div>

                    <!-- 3. Payment Method -->
                    <div class="bg-white border border-neutral-200 p-6 sm:p-8">
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#1c1c1c] text-white flex items-center justify-center text-xs">3</span>
                            PAYMENT METHOD (পেমেন্ট পদ্ধতি)
                        </h2>

                        <div class="space-y-3 text-xs">
                            <label class="flex items-center gap-3 p-4 border border-black bg-neutral-50 cursor-pointer">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked class="text-black">
                                <div>
                                    <span class="font-bold text-neutral-900 block">Cash on Delivery (ক্যাশ অন ডেলিভারি)</span>
                                    <span class="text-[11px] text-neutral-500">পণ্য ডেলিভারি পাওয়ার পর সম্পূর্ণ টাকা পরিশোধ করুন।</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 border border-neutral-200 cursor-pointer hover:bg-neutral-50">
                                <input type="radio" name="payment_method" value="bkash" class="text-black">
                                <div>
                                    <span class="font-bold text-neutral-900 block">bKash / Nagad Send Money</span>
                                    <span class="text-[11px] text-neutral-500">বিকাশ বা নগদ-এ সেন্ড মানি করে অর্ডার কনফার্ম করতে পারেন।</span>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Right: Order Summary (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-[#faf8f5] border border-neutral-200 p-6 sm:p-8 sticky top-28">
                        <h3 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200">
                            YOUR ORDER SUMMARY
                        </h3>

                        <!-- Items list -->
                        <div class="py-4 divide-y divide-neutral-200 max-h-72 overflow-y-auto">
                            @foreach($cart as $item)
                                <div class="py-3 flex items-center gap-3">
                                    <div class="relative w-14 h-16 bg-white border border-neutral-200 shrink-0">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] ?? $item['name'] ?? 'Product' }}" class="w-full h-full object-cover">
                                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-neutral-800 text-white text-[10px] font-bold flex items-center justify-center">
                                            {{ $item['quantity'] }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-semibold uppercase text-neutral-900 truncate">{{ $item['title'] ?? $item['name'] ?? 'Product' }}</h4>
                                        @if(!empty($item['variant']))
                                            <p class="text-[11px] text-neutral-500 uppercase">{{ $item['variant'] }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold text-neutral-900 shrink-0">
                                        ৳ {{ number_format($item['price'] * $item['quantity'], 0) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Calculations -->
                        <div class="pt-4 border-t border-neutral-200 space-y-2 text-xs">
                            <div class="flex justify-between text-neutral-600">
                                <span>Subtotal:</span>
                                <span class="font-bold text-neutral-900">৳ {{ number_format($subtotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-neutral-600">
                                <span>Delivery Charge:</span>
                                <span class="font-bold text-neutral-900" x-text="'৳ ' + deliveryCharge">৳ {{ ($insideDhaka == 60 || !$insideDhaka) ? 80 : $insideDhaka }}</span>
                            </div>
                            <div class="flex justify-between text-base font-extrabold text-neutral-950 pt-3 border-t border-neutral-200">
                                <span>Total Payable:</span>
                                <span>৳ <span x-text="grandTotal.toLocaleString()"></span></span>
                            </div>
                        </div>

                        <!-- Submit Order Button -->
                        <div class="pt-6">
                            <button type="submit" 
                                    class="w-full bg-[#1c1c1c] text-white py-4 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition shadow-md">
                                PLACE ORDER (অর্ডার সম্পূর্ণ করুন)
                            </button>
                            <p class="text-[10px] text-center text-neutral-500 mt-3">
                                🔒 Secure SSL checkout with instant order confirmation
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
        @endif

    </div>

@endsection

<div x-show="$store.cart.buyNowModalOpen" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     x-cloak>
    <!-- Backdrop -->
    <div x-show="$store.cart.buyNowModalOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.closeBuyNow()" 
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="$store.cart.buyNowModalOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-data="{
                 cityZone: 'inside_dhaka',
                 insideDhakaRate: {{ \App\Models\Setting::get('delivery_inside_dhaka', 60) }},
                 outsideDhakaRate: {{ \App\Models\Setting::get('delivery_outside_dhaka', 120) }},
                 qty: 1,
                 get deliveryCharge() {
                     return this.cityZone === 'inside_dhaka' ? this.insideDhakaRate : this.outsideDhakaRate;
                 },
                 get grandTotal() {
                     const price = $store.cart.buyNowProduct ? $store.cart.buyNowProduct.price : 0;
                     return (price * this.qty) + this.deliveryCharge;
                 }
             }"
             class="relative bg-white w-full max-w-lg shadow-2xl p-6 md:p-8 border border-neutral-200 max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-neutral-200">
                <div>
                    <h3 class="text-sm font-bold tracking-[0.15em] uppercase text-neutral-900 font-sans">
                        QUICK CASH ON DELIVERY
                    </h3>
                    <p class="text-[11px] text-neutral-500">অর্ডার করতে নিচের তথ্যগুলো পূরণ করুন</p>
                </div>
                <button @click="$store.cart.closeBuyNow()" class="text-neutral-400 hover:text-black p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Product Summary -->
            <template x-if="$store.cart.buyNowProduct">
                <div class="flex items-center gap-4 py-4 border-b border-neutral-100 bg-[#faf8f5] p-3 my-4">
                    <img :src="$store.cart.buyNowProduct.primary_image || $store.cart.buyNowProduct.image" 
                         :alt="$store.cart.buyNowProduct.title" 
                         class="w-16 h-16 object-cover border border-neutral-200">
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-neutral-900 uppercase" x-text="$store.cart.buyNowProduct.title"></h4>
                        <p class="text-[11px] text-neutral-500" x-show="$store.cart.buyNowProduct.selectedVariant" x-text="'Color: ' + $store.cart.buyNowProduct.selectedVariant"></p>
                        <div class="text-xs font-extrabold text-neutral-900 mt-1">
                            ৳ <span x-text="$store.cart.buyNowProduct.price.toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Quick Checkout Form -->
            <form action="{{ route('checkout.quick') }}" method="POST" class="space-y-4">
                @csrf
                <template x-if="$store.cart.buyNowProduct">
                    <div>
                        <input type="hidden" name="product_id" :value="$store.cart.buyNowProduct.id">
                        <input type="hidden" name="variant" :value="$store.cart.buyNowProduct.selectedVariant || ''">
                    </div>
                </template>

                <!-- Quantity -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Quantity / পরিমাণ</label>
                    <div class="flex items-center w-28 border border-neutral-300">
                        <button type="button" @click="if (qty > 1) qty--" class="px-3 py-1 text-sm bg-neutral-100 hover:bg-neutral-200">-</button>
                        <input type="number" name="quantity" x-model="qty" readonly class="w-full text-center text-xs font-bold py-1 border-none focus:outline-none">
                        <button type="button" @click="qty++" class="px-3 py-1 text-sm bg-neutral-100 hover:bg-neutral-200">+</button>
                    </div>
                </div>

                <!-- Customer Name -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Your Full Name / আপনার নাম *</label>
                    <input type="text" name="customer_name" required placeholder="e.g. Tanzina Akter" 
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Mobile Number / মোবাইল নাম্বার *</label>
                    <input type="tel" name="customer_phone" required placeholder="01XXXXXXXXX" 
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Delivery Zone -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-2">Delivery Area / ডেলিভারি এরিয়া *</label>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <label class="flex items-center gap-2 p-3 border cursor-pointer transition"
                               :class="cityZone === 'inside_dhaka' ? 'border-black bg-neutral-50 font-bold' : 'border-neutral-200 text-neutral-600'">
                            <input type="radio" name="city_zone" value="inside_dhaka" x-model="cityZone" class="text-black">
                            <span>Inside Dhaka (৳ <span x-text="insideDhakaRate"></span>)</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 border cursor-pointer transition"
                               :class="cityZone === 'outside_dhaka' ? 'border-black bg-neutral-50 font-bold' : 'border-neutral-200 text-neutral-600'">
                            <input type="radio" name="city_zone" value="outside_dhaka" x-model="cityZone" class="text-black">
                            <span>Outside Dhaka (৳ <span x-text="outsideDhakaRate"></span>)</span>
                        </label>
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Full Delivery Address / সম্পূর্ণ ঠিকানা *</label>
                    <textarea name="delivery_address" required rows="2" placeholder="House, Road, Area, District" 
                              class="w-full text-xs px-3.5 py-2 border border-neutral-300 focus:outline-none focus:border-black"></textarea>
                </div>

                <!-- Price Breakdown -->
                <div class="bg-neutral-50 p-3 text-xs space-y-1.5 border border-neutral-200">
                    <div class="flex justify-between text-neutral-600">
                        <span>Delivery Charge:</span>
                        <span>৳ <span x-text="deliveryCharge"></span></span>
                    </div>
                    <div class="flex justify-between font-extrabold text-neutral-900 text-sm pt-1 border-t border-neutral-200">
                        <span>Total Payable:</span>
                        <span>৳ <span x-text="grandTotal.toLocaleString()"></span></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition">
                    CONFIRM ORDER (অর্ডার নিশ্চিত করুন)
                </button>
                <p class="text-[10px] text-center text-neutral-500">💵 পণ্য হাতে পেয়ে টাকা পরিশোধ করুন (Cash on Delivery)</p>
            </form>

        </div>
    </div>
</div>

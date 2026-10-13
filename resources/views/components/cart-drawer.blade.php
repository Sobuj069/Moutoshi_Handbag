<div x-show="$store.cart.drawerOpen" 
     class="fixed inset-0 z-50 overflow-hidden" 
     x-cloak>
    <!-- Background overlay -->
    <div x-show="$store.cart.drawerOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.closeDrawer()" 
         class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div x-show="$store.cart.drawerOpen" 
             x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
            
            <!-- Drawer Header -->
            <div class="p-5 border-b border-neutral-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold tracking-[0.15em] uppercase text-neutral-900 font-sans">
                        SHOPPING BAG
                    </h2>
                    <span class="text-xs font-semibold px-2 py-0.5 bg-neutral-100 text-neutral-700 rounded-full" 
                          x-text="$store.cart.count">0</span>
                </div>
                <button @click="$store.cart.closeDrawer()" 
                        class="text-neutral-400 hover:text-black p-1 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Free Delivery Progress Bar -->
            <div class="bg-[#faf8f5] px-6 py-3 border-b border-neutral-200">
                <template x-if="$store.cart.subtotal >= $store.cart.freeShippingThreshold">
                    <div class="text-xs font-semibold text-green-700 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Congratulations! You've unlocked FREE Delivery! 🎉</span>
                    </div>
                </template>
                <template x-if="$store.cart.subtotal < $store.cart.freeShippingThreshold">
                    <div>
                        <div class="flex justify-between text-[11px] font-medium text-neutral-700 mb-1">
                            <span>Add <strong class="text-black">৳ <span x-text="($store.cart.freeShippingThreshold - $store.cart.subtotal).toLocaleString()"></span></strong> more for Free Delivery</span>
                            <span x-text="Math.round(($store.cart.subtotal / $store.cart.freeShippingThreshold) * 100) + '%'"></span>
                        </div>
                        <div class="w-full bg-neutral-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-[#c9a66b] h-full transition-all duration-500" 
                                 :style="`width: ${Math.min(100, ($store.cart.subtotal / $store.cart.freeShippingThreshold) * 100)}%`"></div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Items List (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <template x-if="$store.cart.items.length === 0">
                    <div class="text-center py-16">
                        <div class="w-16 h-16 mx-auto mb-4 text-neutral-300">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="text-neutral-500 text-sm tracking-wide mb-6">Your shopping bag is currently empty.</p>
                        <a href="{{ route('shop.index', 'all') }}" 
                           @click="$store.cart.closeDrawer()"
                           class="inline-block bg-[#1c1c1c] text-white text-xs font-semibold tracking-[0.15em] uppercase py-3.5 px-7 hover:bg-neutral-800 transition">
                            EXPLORE BAGS
                        </a>
                    </div>
                </template>

                <template x-for="item in $store.cart.items" :key="item.cart_key">
                    <div class="flex gap-4 pb-5 border-b border-neutral-100 last:border-b-0">
                        <img :src="item.image" :alt="item.title" class="w-20 h-24 object-cover object-center bg-neutral-100 rounded-none shrink-0 border border-neutral-200">
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-neutral-900 line-clamp-2">
                                        <a :href="'/products/' + item.slug" x-text="item.title" class="hover:text-[#c9a66b] transition"></a>
                                    </h3>
                                    <button @click="$store.cart.removeItem(item.cart_key)" 
                                            class="text-neutral-400 hover:text-red-600 transition ml-2 p-0.5" 
                                            title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                <p class="text-[11px] text-neutral-500 mt-1 uppercase" x-show="item.variant" x-text="'Color: ' + item.variant"></p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs font-bold text-neutral-900">৳ <span x-text="item.price.toLocaleString()"></span></span>
                                    <span class="text-[10px] text-neutral-400 line-through" x-show="item.compare_at_price > item.price" x-text="'৳ ' + item.compare_at_price.toLocaleString()"></span>
                                </div>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="flex items-center mt-3">
                                <div class="flex items-center border border-neutral-300">
                                    <button @click="$store.cart.updateQuantity(item.cart_key, item.quantity - 1)" 
                                            class="px-2.5 py-1 text-xs text-neutral-600 hover:bg-neutral-100 transition">-</button>
                                    <span class="px-3 py-1 text-xs font-semibold text-neutral-900" x-text="item.quantity"></span>
                                    <button @click="$store.cart.updateQuantity(item.cart_key, item.quantity + 1)" 
                                            class="px-2.5 py-1 text-xs text-neutral-600 hover:bg-neutral-100 transition">+</button>
                                </div>
                                <span class="ml-auto text-xs font-bold text-neutral-900">
                                    ৳ <span x-text="(item.price * item.quantity).toLocaleString()"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Drawer Footer (Subtotal & Checkout) -->
            <div class="p-6 border-t border-neutral-200 bg-[#fafafa]" x-show="$store.cart.items.length > 0">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs uppercase font-semibold tracking-wider text-neutral-600">Subtotal</span>
                    <span class="text-base font-extrabold text-neutral-950">৳ <span x-text="$store.cart.subtotal.toLocaleString()"></span></span>
                </div>
                <p class="text-[11px] text-neutral-500 mb-4">Taxes and shipping calculated at checkout. Cash on Delivery available.</p>

                <div class="space-y-2">
                    <a href="{{ route('checkout.index') }}" 
                       class="w-full block text-center bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition shadow-sm">
                        PROCEED TO CHECKOUT
                    </a>
                    <a href="{{ route('cart.index') }}" 
                       @click="$store.cart.closeDrawer()"
                       class="w-full block text-center border border-neutral-300 text-neutral-800 py-2.5 px-6 text-xs font-semibold tracking-[0.15em] uppercase hover:bg-white transition">
                        VIEW BAG
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

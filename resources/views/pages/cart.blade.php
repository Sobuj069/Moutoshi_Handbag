@extends('layouts.app')

@section('title', 'Your Shopping Bag | The Patchee BD')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                SHOPPING BAG
            </h1>
            <p class="text-xs text-neutral-500 uppercase tracking-wider mt-1">Review your selected items before checkout</p>
        </div>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Items Table -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="border border-neutral-200 divide-y divide-neutral-200">
                        @foreach($cart as $item)
                            <div class="p-4 sm:p-6 flex gap-4 sm:gap-6 items-center">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-20 h-24 sm:w-24 sm:h-28 object-cover bg-neutral-100 border border-neutral-200 shrink-0">
                                <div class="flex-1">
                                    <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">
                                        <a href="{{ route('product.show', $item['slug']) }}" class="hover:text-[#c9a66b] transition">
                                            {{ $item['title'] }}
                                        </a>
                                    </h3>
                                    @if(!empty($item['variant']))
                                        <p class="text-xs text-neutral-500 uppercase mt-0.5">Color: {{ $item['variant'] }}</p>
                                    @endif
                                    <div class="text-xs font-bold text-neutral-900 mt-1">
                                        ৳ {{ number_format($item['price'], 0) }}
                                    </div>

                                    <!-- Quantity Adjustment -->
                                    <div class="flex items-center gap-4 mt-3">
                                        <div class="flex items-center border border-neutral-300">
                                            <button type="button" 
                                                    @click="$store.cart.updateQuantity('{{ $item['cart_key'] }}', {{ $item['quantity'] - 1 }}); window.location.reload();"
                                                    class="px-2.5 py-1 text-xs hover:bg-neutral-100">-</button>
                                            <span class="px-3 py-1 text-xs font-bold">{{ $item['quantity'] }}</span>
                                            <button type="button" 
                                                    @click="$store.cart.updateQuantity('{{ $item['cart_key'] }}', {{ $item['quantity'] + 1 }}); window.location.reload();"
                                                    class="px-2.5 py-1 text-xs hover:bg-neutral-100">+</button>
                                        </div>

                                        <button type="button" 
                                                @click="$store.cart.removeItem('{{ $item['cart_key'] }}'); window.location.reload();"
                                                class="text-xs text-red-500 hover:underline uppercase tracking-wider">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="text-right text-sm font-extrabold text-neutral-900 shrink-0">
                                    ৳ {{ number_format($item['price'] * $item['quantity'], 0) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <a href="{{ route('shop.index', 'all') }}" class="text-xs font-semibold uppercase tracking-wider text-neutral-700 hover:text-black">
                            ← Continue Shopping
                        </a>
                        <a href="{{ route('cart.clear') }}" class="text-xs font-semibold uppercase tracking-wider text-red-600 hover:underline">
                            Clear Entire Bag
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-[#faf8f5] p-6 sm:p-8 border border-neutral-200 h-fit space-y-6">
                    <h3 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-4 border-b border-neutral-200">
                        BAG SUMMARY
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-neutral-600">
                            <span>Subtotal:</span>
                            <span class="font-bold text-neutral-900">৳ {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-neutral-600">
                            <span>Estimated Delivery:</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-neutral-950 text-base pt-3 border-t border-neutral-200">
                            <span>Subtotal:</span>
                            <span>৳ {{ number_format($subtotal, 0) }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2">
                        <a href="{{ route('checkout.index') }}" 
                           class="w-full block text-center bg-[#1c1c1c] text-white py-4 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition shadow-sm">
                            PROCEED TO CHECKOUT
                        </a>
                    </div>

                    <div class="pt-4 border-t border-neutral-200 text-[11px] text-neutral-500 space-y-1">
                        <p>✓ Cash on Delivery available across Bangladesh</p>
                        <p>✓ Fast delivery inside Dhaka (৳80) and outside (৳130)</p>
                    </div>
                </div>

            </div>
        @else
            <div class="text-center py-20 max-w-md mx-auto">
                <div class="w-16 h-16 mx-auto mb-4 text-neutral-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h2 class="text-lg font-bold uppercase tracking-wider text-neutral-900">Your bag is empty</h2>
                <p class="text-xs text-neutral-500 mt-1 mb-6">Looks like you haven't added any bags to your shopping bag yet.</p>
                <a href="{{ route('shop.index', 'all') }}" class="inline-block bg-[#1c1c1c] text-white text-xs font-bold tracking-[0.2em] uppercase py-3.5 px-8 hover:bg-neutral-800 transition">
                    START SHOPPING
                </a>
            </div>
        @endif

    </div>

@endsection

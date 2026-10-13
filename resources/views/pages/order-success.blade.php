@extends('layouts.app')

@section('title', 'Order Confirmed #' . $order->order_number . ' | Moutoshi Handbag BD')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 text-center">
        <!-- Success Icon -->
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-5 shadow-xs">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">ORDER CONFIRMED</span>
        <h1 class="text-2xl sm:text-4xl font-extrabold uppercase tracking-[0.12em] text-neutral-900 font-sans mb-3">
            THANK YOU, {{ $order->customer_name }}!
        </h1>
        <p class="text-xs sm:text-sm text-neutral-600 max-w-lg mx-auto font-light leading-relaxed">
            Your order has been placed successfully. Our customer support team will call you shortly on <strong>{{ $order->customer_phone }}</strong> to verify before dispatch.
        </p>

        <!-- Order Number Card -->
        <div class="mt-8 bg-[#faf8f5] border border-neutral-200 p-6 sm:p-8 text-left max-w-2xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-4 border-b border-neutral-200 gap-2">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">ORDER NUMBER</span>
                    <h2 class="text-xl font-extrabold text-neutral-900 font-sans tracking-wider">#{{ $order->order_number }}</h2>
                </div>
                <div class="text-right sm:text-right">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">PAYMENT METHOD</span>
                    <p class="text-xs font-bold text-neutral-800 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                </div>
            </div>

            <!-- Delivery Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-neutral-700">
                <div>
                    <span class="font-bold uppercase tracking-wider text-neutral-500 block text-[10px] mb-1">SHIPPING ADDRESS</span>
                    <p class="font-medium">{{ $order->customer_name }}</p>
                    <p>{{ $order->delivery_address }}</p>
                    <p class="font-semibold mt-1">Zone: {{ $order->city_zone === 'inside_dhaka' ? 'Inside Dhaka (৳ ' . number_format($order->delivery_charge, 0) . ')' : 'Outside Dhaka (৳ ' . number_format($order->delivery_charge, 0) . ')' }}</p>
                </div>
                <div>
                    <span class="font-bold uppercase tracking-wider text-neutral-500 block text-[10px] mb-1">CONTACT INFO</span>
                    <p>Phone: {{ $order->customer_phone }}</p>
                    @if($order->customer_email)
                        <p>Email: {{ $order->customer_email }}</p>
                    @endif
                    <p class="text-neutral-500 mt-1">Status: <span class="uppercase font-bold text-blue-600">{{ $order->order_status }}</span></p>
                </div>
            </div>

            <!-- Ordered Items Table -->
            <div class="pt-4 border-t border-neutral-200">
                <span class="font-bold uppercase tracking-wider text-neutral-500 block text-[10px] mb-3">ORDER ITEMS</span>
                <div class="divide-y divide-neutral-200">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-12 h-14 object-cover border border-neutral-200">
                                @endif
                                <div>
                                    <h4 class="font-bold uppercase text-neutral-900">{{ $item->product_name }}</h4>
                                    @if($item->variant_name)
                                        <p class="text-[11px] text-neutral-500 uppercase">Color: {{ $item->variant_name }}</p>
                                    @endif
                                    <p class="text-neutral-600">Qty: {{ $item->quantity }} × ৳ {{ number_format($item->price, 0) }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-neutral-900">৳ {{ number_format($item->total, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Totals -->
            <div class="pt-4 border-t border-neutral-200 space-y-1.5 text-xs">
                <div class="flex justify-between text-neutral-600">
                    <span>Subtotal:</span>
                    <span>৳ {{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div class="flex justify-between text-neutral-600">
                    <span>Delivery Charge:</span>
                    <span>৳ {{ number_format($order->delivery_charge, 0) }}</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-neutral-900 pt-2 border-t border-neutral-200">
                    <span>Grand Total:</span>
                    <span>৳ {{ number_format($order->total, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" 
               class="bg-[#1c1c1c] text-white py-3.5 px-8 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition">
                TRACK THIS ORDER
            </a>
            <a href="{{ route('home') }}" 
               class="border border-neutral-300 text-neutral-800 py-3.5 px-8 text-xs font-semibold tracking-[0.15em] uppercase hover:bg-neutral-100 transition">
                CONTINUE SHOPPING
            </a>
        </div>

    </div>

@endsection

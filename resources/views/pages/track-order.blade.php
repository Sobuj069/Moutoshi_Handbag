@extends('layouts.app')

@section('title', 'Track Your Order | Moutoshi Handbag BD')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">LIVE TRACKING</span>
            <h1 class="text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                TRACK YOUR ORDER
            </h1>
            <p class="text-xs text-neutral-500 uppercase tracking-wider mt-1">Enter your Order ID and Mobile number to view real-time delivery status</p>
        </div>

        <!-- Track Form -->
        <div class="max-w-xl mx-auto bg-white border border-neutral-200 p-6 sm:p-8 shadow-xs">
            <form action="{{ route('order.track') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                        Order Number / অর্ডার নাম্বার *
                    </label>
                    <input type="text" name="order_number" required value="{{ request('order_number') }}"
                           placeholder="e.g. MH-88210"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                        Mobile Phone / মোবাইল নাম্বার (Optional)
                    </label>
                    <input type="tel" name="phone" value="{{ request('phone') }}"
                           placeholder="01XXXXXXXXX"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <button type="submit" 
                        class="w-full bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition">
                    TRACK NOW (ট্র্যাক করুন)
                </button>
            </form>
        </div>

        <!-- Tracking Results -->
        @if($searched)
            @if($order)
                <div class="mt-12 bg-[#faf8f5] border border-neutral-200 p-6 sm:p-10 text-left">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 border-b border-neutral-200 gap-2">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-500">Order ID: #{{ $order->order_number }}</span>
                            <h2 class="text-xl font-extrabold text-neutral-900 font-sans mt-0.5">Customer: {{ $order->customer_name }}</h2>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-sm {{ $order->status_badge_class }} self-start sm:self-auto">
                            STATUS: {{ $order->order_status }}
                        </span>
                    </div>

                    <!-- Visual Progress Stepper -->
                    <div class="py-8">
                        @php
                            $steps = [
                                'pending' => 'Order Placed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped / In Transit',
                                'delivered' => 'Delivered'
                            ];
                            $statusRank = [
                                'pending' => 1,
                                'processing' => 2,
                                'shipped' => 3,
                                'delivered' => 4,
                                'cancelled' => 0
                            ];
                            $currentRank = $statusRank[$order->order_status] ?? 1;
                        @endphp

                        @if($order->order_status === 'cancelled')
                            <div class="p-4 bg-red-100 text-red-800 text-xs font-semibold rounded-sm text-center">
                                ✕ This order has been cancelled. Please contact support if you need assistance.
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                                @php $stepNum = 1; @endphp
                                @foreach($steps as $key => $label)
                                    @php $isDone = ($currentRank >= $stepNum); @endphp
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold mb-2 transition {{ $isDone ? 'bg-[#1c1c1c] text-[#c9a66b]' : 'bg-neutral-200 text-neutral-400' }}">
                                            @if($isDone) ✓ @else {{ $stepNum }} @endif
                                        </div>
                                        <span class="text-xs font-bold uppercase tracking-wider {{ $isDone ? 'text-neutral-900' : 'text-neutral-400' }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                    @php $stepNum++; @endphp
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($order->consignment_id)
                        <!-- Pathao Live Tracking Info -->
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xs flex flex-col sm:flex-row justify-between sm:items-center gap-3 text-xs">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-red-600 animate-pulse"></span>
                                <div>
                                    <span class="font-bold text-red-900 block uppercase">Delivered via Pathao Courier</span>
                                    <span class="text-red-700 text-[11px]">Consignment ID: <strong class="font-mono">{{ $order->consignment_id }}</strong> | Status: <strong class="uppercase">{{ $order->courier_status ?? 'In Transit' }}</strong></span>
                                </div>
                            </div>
                            @if($order->pathao_tracking_url)
                                <a href="{{ $order->pathao_tracking_url }}" target="_blank"
                                   class="px-3 py-1.5 bg-red-600 text-white font-bold uppercase tracking-wider text-[10px] rounded-xs hover:bg-red-700 transition self-start sm:self-auto flex items-center gap-1.5">
                                    <span>Track on Pathao</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Details Card -->
                    <div class="pt-6 border-t border-neutral-200 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-neutral-700">
                        <div>
                            <span class="font-bold uppercase tracking-wider text-neutral-500 block text-[10px] mb-1">DELIVERY ADDRESS</span>
                            <p>{{ $order->delivery_address }}</p>
                            <p class="font-semibold mt-1">Zone: {{ $order->city_zone === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}</p>
                            <p class="mt-1">Phone: {{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <span class="font-bold uppercase tracking-wider text-neutral-500 block text-[10px] mb-1">ORDER TOTAL</span>
                            <p class="text-base font-extrabold text-neutral-900">৳ {{ number_format($order->total, 0) }}</p>
                            <p class="text-neutral-500 mt-1">Payment Method: {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-8 text-center p-8 bg-red-50 border border-red-200 text-red-700 text-xs">
                    No order found with the provided details. Please verify your Order Number and Mobile Number.
                </div>
            @endif
        @endif

    </div>

@endsection

@extends('admin.layouts.admin')

@section('title', 'Order #' . $order->order_number)
@section('header_title', 'Order Details #' . $order->order_number)

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-neutral-500 hover:text-black uppercase tracking-wider font-bold">
                ← Back to Orders
            </a>
            <h2 class="text-xl font-extrabold text-neutral-900 font-sans mt-1">
                Order #{{ $order->order_number }}
            </h2>
            <span class="text-xs text-neutral-400">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</span>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
               class="px-4 py-2 bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition rounded-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Ordered Items & Costs (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Items Table -->
            <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                        ITEMS IN THIS ORDER ({{ $order->items->count() }})
                    </h3>
                </div>

                <div class="p-6 divide-y divide-neutral-200">
                    @foreach($order->items as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-4">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" 
                                         class="w-14 h-16 object-cover border border-neutral-200 shrink-0">
                                @endif
                                <div>
                                    <h4 class="font-bold text-neutral-900 uppercase">{{ $item->product_name }}</h4>
                                    @if($item->variant_name)
                                        <p class="text-[11px] text-neutral-500 uppercase">Variant: {{ $item->variant_name }}</p>
                                    @endif
                                    <p class="text-neutral-500 mt-1">
                                        Qty: {{ $item->quantity }} × ৳ {{ number_format($item->price, 0) }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-extrabold text-neutral-900 text-sm">
                                ৳ {{ number_format($item->total, 0) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="p-6 bg-neutral-50 border-t border-neutral-200 space-y-2 text-xs">
                    <div class="flex justify-between text-neutral-600">
                        <span>Items Subtotal:</span>
                        <span class="font-bold text-neutral-900">৳ {{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-neutral-600">
                        <span>Delivery Fee:</span>
                        <span class="font-bold text-neutral-900">৳ {{ number_format($order->delivery_charge, 0) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-700">
                            <span>Discount:</span>
                            <span class="font-bold">- ৳ {{ number_format($order->discount, 0) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-extrabold text-neutral-950 pt-3 border-t border-neutral-200">
                        <span>Total Due:</span>
                        <span>৳ {{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->notes)
                <div class="bg-amber-50 border border-amber-200 p-5 rounded-xs text-xs">
                    <span class="font-bold uppercase tracking-wider text-amber-900 block mb-1">Customer Delivery Instructions:</span>
                    <p class="text-amber-800">{{ $order->notes }}</p>
                </div>
            @endif

        </div>

        <!-- Right: Status Update & Customer Info (1 col) -->
        <div class="space-y-6">
            
            <!-- Status Update Box -->
            <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    UPDATE ORDER STATUS
                </h3>

                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-600 mb-1">Fulfillment Status</label>
                        <select name="order_status" class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black uppercase font-bold">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-600 mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black uppercase font-bold">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition rounded-xs">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Customer Details Box -->
            <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs p-6 space-y-4 text-xs">
                <h3 class="font-bold uppercase tracking-wider text-neutral-900 font-sans pb-3 border-b border-neutral-200">
                    CUSTOMER DETAILS
                </h3>

                <div>
                    <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Customer Name</span>
                    <p class="font-bold text-neutral-900 text-sm mt-0.5">{{ $order->customer_name }}</p>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Contact Phone</span>
                    <a href="tel:{{ $order->customer_phone }}" class="font-bold text-blue-600 hover:underline">
                        {{ $order->customer_phone }}
                    </a>
                </div>

                @if($order->customer_email)
                    <div>
                        <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Email</span>
                        <p class="text-neutral-700">{{ $order->customer_email }}</p>
                    </div>
                @endif

                <div>
                    <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Shipping Address</span>
                    <p class="text-neutral-800 font-medium leading-relaxed mt-0.5">{{ $order->delivery_address }}</p>
                    <span class="inline-block px-2 py-0.5 bg-neutral-100 text-neutral-700 font-bold uppercase text-[10px] rounded-xs mt-1">
                        {{ $order->city_zone === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}
                    </span>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Payment Method</span>
                    <p class="font-semibold text-neutral-800 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                </div>
            </div>

        </div>

    </div>

@endsection

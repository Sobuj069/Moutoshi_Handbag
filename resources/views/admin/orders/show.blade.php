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

            <!-- Pathao Courier Dispatch / Tracking Box -->
            <div class="bg-white border-2 {{ $order->isDispatchedToCourier() ? 'border-green-300' : 'border-red-200' }} rounded-xs shadow-2xs p-6 space-y-4 text-xs">
                <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $order->isDispatchedToCourier() ? 'bg-green-500' : 'bg-red-600' }}"></span>
                        <h3 class="font-bold uppercase tracking-wider text-neutral-900 font-sans">
                            PATHAO COURIER (পাঠাও)
                        </h3>
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-xs {{ \App\Models\Setting::get('pathao_environment') === 'production' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ strtoupper(\App\Models\Setting::get('pathao_environment', 'sandbox')) }}
                    </span>
                </div>

                @if($order->isDispatchedToCourier())
                    <!-- Already Dispatched View -->
                    <div class="space-y-3 bg-neutral-50 p-4 border border-neutral-200 rounded-xs">
                        <div>
                            <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Consignment ID</span>
                            <div class="flex items-center justify-between mt-1">
                                <span class="font-mono font-extrabold text-neutral-900 text-sm select-all">#{{ $order->consignment_id }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-xs border {{ $order->courier_status_badge_class }}">
                                    {{ $order->courier_status ?? 'Pending' }}
                                </span>
                            </div>
                        </div>

                        @if($order->shipped_at)
                            <div>
                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Dispatched Date</span>
                                <span class="text-neutral-700 font-medium">{{ $order->shipped_at->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif

                        <div class="pt-2 flex flex-col gap-2">
                            <!-- Track & Refresh Status Button -->
                            <form action="{{ route('admin.orders.track-pathao', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full py-2 bg-neutral-800 text-white font-bold uppercase tracking-wider hover:bg-black transition rounded-xs flex items-center justify-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Sync Live Status
                                </button>
                            </form>

                            @if($order->pathao_tracking_url)
                                <a href="{{ $order->pathao_tracking_url }}" target="_blank" 
                                   class="w-full py-2 border border-neutral-300 text-center font-bold text-neutral-800 uppercase tracking-wider hover:bg-neutral-100 transition rounded-xs flex items-center justify-center gap-1.5">
                                    <span>Track on Pathao Portal</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible API Response -->
                    @if($order->courier_response)
                        <details class="text-[11px] text-neutral-600 bg-white border border-neutral-200 p-3 rounded-xs cursor-pointer">
                            <summary class="font-bold text-neutral-700 uppercase cursor-pointer">Pathao API Response Details</summary>
                            <pre class="mt-2 p-2 bg-neutral-900 text-green-400 overflow-x-auto text-[10px] rounded-xs font-mono">{{ json_encode($order->courier_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @endif

                @else
                    <!-- Send to Pathao Form -->
                    @if(!$pathaoConfigured)
                        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xs space-y-2">
                            <p class="font-bold">⚠ Pathao API is not configured yet.</p>
                            <p class="text-[11px] text-amber-800">
                                Please set your Client ID, Secret, Email, and Password in 
                                <a href="{{ route('admin.settings.index') }}#pathao-section" class="underline font-bold text-amber-950">Store Settings</a> 
                                to send orders directly to Pathao.
                            </p>
                        </div>
                    @else
                        <form action="{{ route('admin.orders.send-pathao', $order->id) }}" method="POST" class="space-y-3" id="formSendPathao">
                            @csrf

                            <!-- Store Selector -->
                            @if(!empty($pathaoStores))
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Pickup Store *</label>
                                    <select name="store_id" class="w-full text-xs px-3 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black font-semibold">
                                        @foreach($pathaoStores as $store)
                                            <option value="{{ $store['store_id'] }}" {{ (\App\Models\Setting::get('pathao_store_id') == $store['store_id']) ? 'selected' : '' }}>
                                                {{ $store['store_name'] }} (ID: {{ $store['store_id'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- City & Zone -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-600 mb-1">City *</label>
                                    <select name="recipient_city" id="pathao_city_select" class="w-full text-xs px-2.5 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black">
                                        @if(!empty($pathaoCities))
                                            @foreach($pathaoCities as $city)
                                                <option value="{{ $city['city_id'] }}" {{ ($city['city_id'] == 1) ? 'selected' : '' }}>
                                                    {{ $city['city_name'] }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="1" selected>Dhaka (Default)</option>
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Zone *</label>
                                    <select name="recipient_zone" id="pathao_zone_select" class="w-full text-xs px-2.5 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black">
                                        <option value="1">Loading zones...</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Weight & Collection Amount -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Weight (KG)</label>
                                    <input type="number" step="0.1" name="item_weight" value="0.5" min="0.5"
                                           class="w-full text-xs px-2.5 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Collect ৳ (COD)</label>
                                    <input type="number" step="1" name="amount_to_collect" 
                                           value="{{ $order->payment_status === 'paid' ? 0 : round($order->total) }}"
                                           class="w-full text-xs px-2.5 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black font-bold">
                                </div>
                            </div>

                            <!-- Special Instructions -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-600 mb-1">Special Instruction</label>
                                <input type="text" name="special_instruction" value="{{ $order->notes ?: 'Handle with care - Moutoshi Handbag' }}"
                                       class="w-full text-xs px-2.5 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black">
                            </div>

                            <button type="submit" 
                                    class="w-full py-3 bg-red-600 text-white font-bold uppercase tracking-wider hover:bg-red-700 transition rounded-xs flex items-center justify-center gap-2 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Send to Pathao Courier
                            </button>
                        </form>

                        <script>
                            // Load zones when city changes
                            function loadPathaoZones(cityId) {
                                const zoneSelect = document.getElementById('pathao_zone_select');
                                if (!zoneSelect) return;
                                zoneSelect.innerHTML = '<option value="">Loading zones...</option>';

                                fetch(`{{ route('admin.pathao.zones') }}?city_id=${cityId}`)
                                    .then(r => r.json())
                                    .then(zones => {
                                        zoneSelect.innerHTML = '';
                                        if (Array.isArray(zones) && zones.length > 0) {
                                            zones.forEach(z => {
                                                const opt = document.createElement('option');
                                                opt.value = z.zone_id;
                                                opt.innerText = z.zone_name;
                                                zoneSelect.appendChild(opt);
                                            });
                                        } else {
                                            zoneSelect.innerHTML = '<option value="1">Zone 1 (Default)</option>';
                                        }
                                    })
                                    .catch(() => {
                                        zoneSelect.innerHTML = '<option value="1">Zone 1 (Default)</option>';
                                    });
                            }

                            const citySelect = document.getElementById('pathao_city_select');
                            if (citySelect) {
                                citySelect.addEventListener('change', function() {
                                    loadPathaoZones(this.value);
                                });
                                // Initial load
                                loadPathaoZones(citySelect.value || 1);
                            }
                        </script>
                    @endif
                @endif
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

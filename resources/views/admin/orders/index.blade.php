@extends('admin.layouts.admin')

@section('title', 'Orders Management')
@section('header_title', 'Customer Orders')

@section('content')

    <!-- Filters & Search Bar -->
    <div class="mb-5 space-y-3">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <!-- Search input -->
            <form method="GET" class="flex-1 max-w-md">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('courier'))
                    <input type="hidden" name="courier" value="{{ request('courier') }}">
                @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Order ID, Consignment, Phone..." 
                           class="w-full text-xs pl-9 pr-4 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </form>

            <!-- Dropdown Filters for Status & Courier -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Status Filter Dropdown -->
                <form method="GET" class="shrink-0">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('courier')) <input type="hidden" name="courier" value="{{ request('courier') }}"> @endif
                    <select name="status" onchange="this.form.submit()" 
                            class="text-xs px-3 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black cursor-pointer font-medium">
                        <option value="">All Order Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Orders</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing Orders</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped Orders</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered Orders</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled Orders</option>
                    </select>
                </form>

                <!-- Courier Filter Dropdown -->
                <form method="GET" class="shrink-0">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    <select name="courier" onchange="this.form.submit()" 
                            class="text-xs px-3 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black cursor-pointer font-medium">
                        <option value="">All Courier Statuses</option>
                        <option value="pathao" {{ request('courier') === 'pathao' ? 'selected' : '' }}>Pathao Dispatched</option>
                        <option value="unassigned" {{ request('courier') === 'unassigned' ? 'selected' : '' }}>Not Sent Yet</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="overflow-x-auto pb-1">
            <div class="flex items-center gap-1.5 text-xs uppercase font-bold tracking-wider whitespace-nowrap min-w-max">
                @php 
                    $currentStatus = request('status'); 
                    $currentCourier = request('courier');
                @endphp
                <a href="{{ route('admin.orders.index') }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ (!$currentStatus && !$currentCourier) ? 'bg-[#1c1c1c] text-white border-black' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    All
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentStatus === 'pending' ? 'bg-[#1c1c1c] text-white border-black' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    Pending
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentStatus === 'processing' ? 'bg-[#1c1c1c] text-white border-black' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    Processing
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentStatus === 'shipped' ? 'bg-[#1c1c1c] text-white border-black' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    Shipped
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentStatus === 'delivered' ? 'bg-[#1c1c1c] text-white border-black' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    Delivered
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentStatus === 'cancelled' ? 'bg-[#1c1c1c] text-white border-black' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    Cancelled
                </a>
                <span class="text-neutral-300">|</span>
                <a href="{{ route('admin.orders.index', ['courier' => 'pathao']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentCourier === 'pathao' ? 'bg-red-700 text-white border-red-700' : 'bg-white text-red-700 border-red-200 hover:bg-red-50' }}">
                    Pathao Dispatched
                </a>
                <a href="{{ route('admin.orders.index', ['courier' => 'unassigned']) }}" 
                   class="px-3.5 py-1.5 rounded-xs transition border {{ $currentCourier === 'unassigned' ? 'bg-neutral-800 text-white border-neutral-800' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    Not Sent Yet
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden min-h-[420px]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-neutral-700">
                <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6">Order ID</th>
                        <th class="py-3.5 px-6">Customer Name</th>
                        <th class="py-3.5 px-6">Phone</th>
                        <th class="py-3.5 px-6">Zone</th>
                        <th class="py-3.5 px-6">Total (৳)</th>
                        <th class="py-3.5 px-6">Order Status</th>
                        <th class="py-3.5 px-6">Courier (Pathao)</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="py-4 px-6 font-bold text-neutral-900 font-mono">
                                #{{ $order->order_number }}
                            </td>
                            <td class="py-4 px-6 font-semibold">{{ $order->customer_name }}</td>
                            <td class="py-4 px-6">{{ $order->customer_phone }}</td>
                            <td class="py-4 px-6 uppercase text-[11px] font-medium text-neutral-600">
                                {{ $order->city_zone === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}
                            </td>
                            <td class="py-4 px-6 font-extrabold text-neutral-950">৳ {{ number_format($order->total, 0) }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-sm border {{ $order->status_badge_class }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($order->consignment_id)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                            <span class="font-bold text-neutral-900 font-mono text-[11px]">{{ $order->consignment_id }}</span>
                                        </div>
                                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase rounded-xs border {{ $order->courier_status_badge_class }}">
                                            {{ $order->courier_status ?? 'Pending' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-neutral-400 text-[11px] italic">Not Dispatched</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-neutral-500 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-6 text-right">
                                <!-- Actions Dropdown -->
                                <div x-data="{ open: false }" class="relative inline-block text-left" @click.away="open = false">
                                    <button type="button" @click="open = !open" 
                                            class="px-3 py-1.5 bg-[#1c1c1c] hover:bg-black text-white rounded-xs text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                                        <span>Actions</span>
                                        <svg class="w-3.5 h-3.5 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open" x-cloak
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-1.5 w-52 bg-white rounded-xs shadow-2xl border border-neutral-200 py-1.5 z-50 text-left divide-y divide-neutral-100 text-xs">
                                        
                                        <div class="py-1">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="flex items-center gap-2.5 px-3.5 py-2 text-neutral-800 hover:bg-neutral-100 font-semibold transition">
                                                <svg class="w-4 h-4 text-neutral-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                <span>View Order Details</span>
                                            </a>

                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="flex items-center gap-2.5 px-3.5 py-2 text-red-700 hover:bg-red-50 font-bold transition">
                                                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                <span>{{ $order->consignment_id ? 'Track Courier Status' : 'Send to Pathao' }}</span>
                                            </a>

                                            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                                               class="flex items-center gap-2.5 px-3.5 py-2 text-neutral-800 hover:bg-neutral-100 font-semibold transition">
                                                <svg class="w-4 h-4 text-neutral-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                <span>Print Invoice</span>
                                            </a>
                                        </div>

                                        <!-- Quick Status Change Options -->
                                        <div class="py-1">
                                            <span class="block px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-neutral-400">Quick Update Status:</span>
                                            @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $statusKey => $statusLabel)
                                                @if($order->order_status !== $statusKey)
                                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="block">
                                                        @csrf
                                                        <input type="hidden" name="order_status" value="{{ $statusKey }}">
                                                        <button type="submit" class="w-full text-left px-3.5 py-1.5 text-[11px] text-neutral-700 hover:bg-neutral-100 transition flex items-center justify-between">
                                                            <span>Mark as {{ $statusLabel }}</span>
                                                            <span class="text-neutral-400 text-[10px]">→</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-neutral-400">No orders found matching the filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-neutral-200">
            {{ $orders->links() }}
        </div>
    </div>

@endsection

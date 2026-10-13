@extends('admin.layouts.admin')

@section('title', 'Orders Management')
@section('header_title', 'Customer Orders')

@section('content')

    <!-- Status Tabs & Search Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 text-xs uppercase font-bold tracking-wider">
            @php $currentStatus = request('status'); @endphp
            <a href="{{ route('admin.orders.index') }}" 
               class="px-3 py-1.5 rounded-xs transition {{ !$currentStatus ? 'bg-[#1c1c1c] text-white' : 'bg-white text-neutral-600 hover:bg-neutral-200' }}">
                All
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
               class="px-3 py-1.5 rounded-xs transition {{ $currentStatus === 'pending' ? 'bg-[#1c1c1c] text-white' : 'bg-white text-neutral-600 hover:bg-neutral-200' }}">
                Pending
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
               class="px-3 py-1.5 rounded-xs transition {{ $currentStatus === 'processing' ? 'bg-[#1c1c1c] text-white' : 'bg-white text-neutral-600 hover:bg-neutral-200' }}">
                Processing
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
               class="px-3 py-1.5 rounded-xs transition {{ $currentStatus === 'shipped' ? 'bg-[#1c1c1c] text-white' : 'bg-white text-neutral-600 hover:bg-neutral-200' }}">
                Shipped
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
               class="px-3 py-1.5 rounded-xs transition {{ $currentStatus === 'delivered' ? 'bg-[#1c1c1c] text-white' : 'bg-white text-neutral-600 hover:bg-neutral-200' }}">
                Delivered
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
               class="px-3 py-1.5 rounded-xs transition {{ $currentStatus === 'cancelled' ? 'bg-[#1c1c1c] text-white' : 'bg-white text-neutral-600 hover:bg-neutral-200' }}">
                Cancelled
            </a>
        </div>

        <!-- Search input -->
        <form method="GET" class="w-full sm:w-72">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search ID, Name or Phone..." 
                   class="w-full text-xs px-4 py-2 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black">
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden">
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
                            <td class="py-4 px-6 text-neutral-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="px-3 py-1 bg-[#1c1c1c] text-white rounded-xs text-[10px] font-bold uppercase tracking-wider hover:bg-black transition">
                                    View
                                </a>
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                                   class="px-3 py-1 border border-neutral-300 text-neutral-700 rounded-xs text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-100 transition">
                                    Invoice
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-neutral-400">No orders found matching the filter.</td>
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

@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Analytics & Overview')

@section('content')

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Revenue -->
        <div class="bg-white p-6 border border-neutral-200 shadow-2xs rounded-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-neutral-400 block mb-1">TOTAL SALES REVENUE</span>
            <div class="text-2xl font-extrabold text-neutral-900 font-sans">
                ৳ {{ number_format($totalSales, 0) }}
            </div>
            <span class="text-[11px] text-green-600 font-semibold mt-2 block">✓ Active store turnover</span>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-6 border border-neutral-200 shadow-2xs rounded-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-neutral-400 block mb-1">TOTAL ORDERS</span>
            <div class="text-2xl font-extrabold text-neutral-900 font-sans">
                {{ $totalOrders }}
            </div>
            <span class="text-[11px] text-neutral-500 font-medium mt-2 block">Lifetime recorded orders</span>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white p-6 border border-neutral-200 shadow-2xs rounded-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-neutral-400 block mb-1">PENDING ORDERS</span>
            <div class="text-2xl font-extrabold text-amber-600 font-sans">
                {{ $pendingOrders }}
            </div>
            <span class="text-[11px] text-amber-600 font-semibold mt-2 block">Requires call verification</span>
        </div>

        <!-- Total Products -->
        <div class="bg-white p-6 border border-neutral-200 shadow-2xs rounded-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-neutral-400 block mb-1">LIVE PRODUCTS</span>
            <div class="text-2xl font-extrabold text-neutral-900 font-sans">
                {{ $totalProducts }}
            </div>
            <span class="text-[11px] text-neutral-500 font-medium mt-2 block">Catalog bag items</span>
        </div>

    </div>

    <!-- Quick Actions Banner -->
    <div class="bg-[#1c1c1c] text-white p-6 rounded-xs mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold uppercase tracking-wider font-sans">STORE MANAGEMENT SHORTCUTS</h3>
            <p class="text-xs text-neutral-400 mt-0.5">Quickly create new products or process incoming customer orders</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-[#c9a66b] text-white text-xs font-bold uppercase tracking-wider hover:bg-[#b08f57] transition">
                + ADD NEW PRODUCT
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-5 py-2.5 bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider hover:bg-neutral-700 transition">
                VIEW ALL ORDERS
            </a>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-neutral-200 flex justify-between items-center">
            <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">
                RECENT CUSTOMER ORDERS
            </h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#c9a66b] font-bold uppercase hover:underline">
                View All →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-neutral-700">
                <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-500 tracking-wider">
                    <tr>
                        <th class="py-3 px-6">Order ID</th>
                        <th class="py-3 px-6">Customer</th>
                        <th class="py-3 px-6">Phone</th>
                        <th class="py-3 px-6">Total</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="py-4 px-6 font-bold text-neutral-900">#{{ $order->order_number }}</td>
                            <td class="py-4 px-6 font-medium">{{ $order->customer_name }}</td>
                            <td class="py-4 px-6">{{ $order->customer_phone }}</td>
                            <td class="py-4 px-6 font-bold text-neutral-900">৳ {{ number_format($order->total, 0) }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-sm border {{ $order->status_badge_class }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-neutral-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-xs font-bold text-[#1c1c1c] hover:underline uppercase">
                                    Manage
                                </a>
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="text-xs font-bold text-neutral-500 hover:underline uppercase">
                                    Invoice
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-400">No orders recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

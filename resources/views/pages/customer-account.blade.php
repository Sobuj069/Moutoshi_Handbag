@extends('layouts.app')

@section('title', 'My Account | Moutoshi Handbag BD')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-12 border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-1">WELCOME BACK</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                    {{ $user->name }}
                </h1>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $user->email }} • {{ $user->phone }}</p>
            </div>

            <div>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 border border-neutral-300 text-neutral-800 text-xs font-bold uppercase tracking-wider hover:bg-neutral-100 transition rounded-xs">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="space-y-6">
            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200">
                MY ORDER HISTORY ({{ $orders->count() }})
            </h2>

            @if($orders->count() > 0)
                <div class="bg-white border border-neutral-200 overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-neutral-700">
                            <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-500 tracking-wider">
                                <tr>
                                    <th class="py-3.5 px-6">Order ID</th>
                                    <th class="py-3.5 px-6">Date</th>
                                    <th class="py-3.5 px-6">Items</th>
                                    <th class="py-3.5 px-6">Total Amount</th>
                                    <th class="py-3.5 px-6">Order Status</th>
                                    <th class="py-3.5 px-6 text-right">Tracking</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200">
                                @foreach($orders as $ord)
                                    <tr class="hover:bg-neutral-50 transition">
                                        <td class="py-4 px-6 font-bold text-neutral-900">#{{ $ord->order_number }}</td>
                                        <td class="py-4 px-6 text-neutral-500">{{ $ord->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 px-6">
                                            @foreach($ord->items as $it)
                                                <div class="text-[11px] font-medium">{{ $it->product_name }} (x{{ $it->quantity }})</div>
                                            @endforeach
                                        </td>
                                        <td class="py-4 px-6 font-bold text-neutral-900">৳ {{ number_format($ord->total, 0) }}</td>
                                        <td class="py-4 px-6">
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-sm border {{ $ord->status_badge_class }}">
                                                {{ $ord->order_status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <a href="{{ route('order.track', ['order_number' => $ord->order_number, 'phone' => $ord->customer_phone]) }}" 
                                               class="px-3 py-1.5 bg-[#1c1c1c] text-white text-[10px] font-bold uppercase tracking-wider hover:bg-black transition rounded-xs">
                                                Track Parcel
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-16 bg-[#faf8f5] border border-neutral-200">
                    <p class="text-xs text-neutral-500 mb-4">You have not placed any orders yet.</p>
                    <a href="{{ route('shop.index', 'all') }}" class="inline-block bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-[0.2em] py-3 px-6 hover:bg-black transition">
                        Explore Bag Collections
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection

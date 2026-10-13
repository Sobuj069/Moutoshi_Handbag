@extends('admin.layouts.admin')

@section('title', 'Products Management')
@section('header_title', 'Products Management')

@section('content')

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <form method="GET" class="w-full sm:w-80">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title or SKU..." 
                   class="w-full text-xs px-4 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black">
        </form>
        <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition text-center rounded-xs">
            + ADD NEW PRODUCT
        </a>
    </div>

    <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-neutral-700">
                <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-500 tracking-wider">
                    <tr>
                        <th class="py-3 px-6">Product</th>
                        <th class="py-3 px-6">Category</th>
                        <th class="py-3 px-6">Price</th>
                        <th class="py-3 px-6">Stock</th>
                        <th class="py-3 px-6">Badges</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="py-4 px-6 flex items-center gap-3">
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->title }}" 
                                     class="w-12 h-14 object-cover border border-neutral-200 shrink-0">
                                <div>
                                    <h4 class="font-bold text-neutral-900 uppercase">{{ $product->title }}</h4>
                                    <span class="text-[11px] text-neutral-400">SKU: {{ $product->sku ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium">{{ $product->category->name ?? 'Uncategorized' }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-neutral-900">{{ $product->formatted_price }}</span>
                                @if($product->compare_at_price)
                                    <span class="text-[11px] text-neutral-400 line-through block">{{ $product->formatted_compare_price }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold">{{ $product->stock }} in stock</td>
                            <td class="py-4 px-6 space-x-1">
                                @if($product->is_featured)
                                    <span class="px-1.5 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-bold rounded-xs">FEATURED</span>
                                @endif
                                @if($product->is_sale)
                                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-xs">SALE</span>
                                @endif
                                @if($product->is_new)
                                    <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-xs">NEW</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($product->is_active)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-xs uppercase">ACTIVE</span>
                                @else
                                    <span class="px-2 py-0.5 bg-neutral-100 text-neutral-500 text-[10px] font-bold rounded-xs uppercase">DRAFT</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-3">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="font-bold text-[#c9a66b] hover:underline uppercase">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-bold text-red-500 hover:underline uppercase">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-400">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-neutral-200">
            {{ $products->links() }}
        </div>
    </div>

@endsection

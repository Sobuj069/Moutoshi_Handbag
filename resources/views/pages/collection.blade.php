@extends('layouts.app')

@section('title', ($currentCategory ? $currentCategory->name : 'All Collections') . ' | Moutoshi Handbag BD')

@section('content')

    <!-- Collection Header Banner -->
    <div class="bg-[#faf8f5] py-12 sm:py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                COLLECTION
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                {{ $currentCategory ? $currentCategory->name : 'ALL PRODUCTS' }}
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-xl mx-auto font-light leading-relaxed">
                {{ $currentCategory ? $currentCategory->description : 'Explore our complete signature line of stylish tote bags, crossbody side bags, backpacks, and accessories.' }}
            </p>
        </div>
    </div>

    <!-- Collection Content & Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Filter & Sorting Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between pb-6 mb-8 border-b border-neutral-200 gap-4">
            <!-- Categories Pill Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 text-xs uppercase tracking-wider font-semibold">
                <a href="{{ route('shop.index', 'all') }}" 
                   class="px-3.5 py-1.5 transition whitespace-nowrap {{ !$currentCategory ? 'bg-[#1c1c1c] text-white' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' }}">
                    ALL
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop.index', $cat->slug) }}" 
                       class="px-3.5 py-1.5 transition whitespace-nowrap {{ ($currentCategory && $currentCategory->id === $cat->id) ? 'bg-[#1c1c1c] text-white' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Sorting Dropdown -->
            <form method="GET" class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                <span class="text-xs uppercase tracking-wider text-neutral-500 font-medium">SORT:</span>
                <select name="sort" 
                        onchange="this.form.submit()" 
                        class="text-xs border border-neutral-300 py-1.5 px-3 bg-white text-neutral-800 uppercase font-medium focus:outline-none focus:border-black">
                    <option value="featured" {{ $sort === 'featured' ? 'selected' : '' }}>Featured</option>
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="price-asc" {{ $sort === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price-desc" {{ $sort === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="best-selling" {{ $sort === 'best-selling' ? 'selected' : '' }}>Best Selling</option>
                </select>
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-16 h-16 mx-auto mb-4 text-neutral-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-base font-bold uppercase tracking-wider text-neutral-800">No products found</h3>
                <p class="text-xs text-neutral-500 mt-1 mb-6">Try clearing filters or search queries.</p>
                <a href="{{ route('shop.index', 'all') }}" class="inline-block bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-[0.2em] py-3 px-6 hover:bg-neutral-800 transition">
                    VIEW ALL PRODUCTS
                </a>
            </div>
        @endif

    </div>

@endsection

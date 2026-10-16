@props(['product'])

@php
    $secondaryImage = $product->images->skip(1)->first()?->url ?? null;
    $primaryImage = $product->primary_image_url;
@endphp

<div class="group relative flex flex-col h-full bg-white transition duration-300">
    <!-- Image Wrapper -->
    <div class="relative w-full aspect-[4/5] bg-[#f7f7f7] overflow-hidden">
        
        <!-- Badges -->
        <div class="absolute top-2.5 left-2.5 z-10 flex flex-col gap-1">
            @if($product->is_sale && $product->discount_percent > 0)
                <span class="bg-[#c9a66b] text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 shadow-xs">
                    SAVE {{ $product->discount_percent }}%
                </span>
            @endif
            @if($product->is_new)
                <span class="bg-[#1c1c1c] text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5">
                    NEW
                </span>
            @endif
        </div>

        <!-- Product Link with Primary & Secondary Image -->
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full relative">
            <img src="{{ $primaryImage }}" 
                 alt="{{ $product->title }}" 
                 loading="lazy"
                 class="w-full h-full object-cover object-center transition-opacity duration-500 ease-in-out {{ $secondaryImage ? 'group-hover:opacity-0' : 'group-hover:scale-105' }}">
            
            @if($secondaryImage)
                <img src="{{ $secondaryImage }}" 
                     alt="{{ $product->title }}" 
                     loading="lazy"
                     class="w-full h-full object-cover object-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out group-hover:scale-105">
            @endif
        </a>

        <!-- Brand Watermark Logo Badge (Top Right Corner Circular) -->
        <div class="absolute top-2.5 right-2.5 z-10 pointer-events-none select-none transition-all duration-300 group-hover:scale-105">
            <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/95 backdrop-blur-xs shadow-md border-2 border-white flex items-center justify-center p-1.5 sm:p-2">
                <img src="{{ asset('assets/images/logo.png') }}" 
                     alt="Moutoshi Handbag" 
                     class="w-full h-auto max-h-full object-contain">
            </div>
        </div>

        <!-- Quick Action Overlay on Hover -->
        <div class="absolute inset-x-3 bottom-3 z-20 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
            <button type="button"
                    @click="$store.cart.addToCart({{ $product->id }}, 1, '{{ $product->variants->first()?->name ?? 'Default' }}')"
                    class="flex-1 bg-[#1c1c1c] text-white text-[10px] font-bold uppercase tracking-[0.18em] py-2.5 px-3 hover:bg-black transition text-center shadow-md">
                ADD TO BAG
            </button>
            <button type="button"
                    @click="$store.cart.openBuyNow({{ json_encode($product) }})"
                    title="Quick Order COD"
                    class="bg-[#c9a66b] text-white p-2.5 hover:bg-[#b08f57] transition shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </button>
        </div>
    </div>

    <!-- Product Meta -->
    <div class="pt-3 pb-2 flex-1 flex flex-col justify-between text-left">
        <div>
            @if($product->category)
                <span class="text-[10px] tracking-[0.15em] uppercase text-neutral-400 font-semibold block mb-0.5">
                    {{ $product->category->name }}
                </span>
            @endif
            <h3 class="text-xs font-semibold uppercase tracking-wider text-neutral-900 group-hover:text-[#c9a66b] transition line-clamp-1 font-sans">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->title }}
                </a>
            </h3>
        </div>

        <!-- Prices -->
        <div class="mt-2 flex items-center gap-2">
            <span class="text-xs font-bold text-neutral-950">
                {{ $product->formatted_price }}
            </span>
            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                <span class="text-[11px] text-neutral-400 line-through">
                    {{ $product->formatted_compare_price }}
                </span>
            @endif
        </div>

        <!-- Color Swatch Dots -->
        @if($product->variants->count() > 0)
            <div class="mt-2 flex items-center gap-1.5">
                @foreach($product->variants->take(4) as $variant)
                    <span class="w-2.5 h-2.5 rounded-full border border-neutral-300 shadow-2xs" 
                          style="background-color: {{ $variant->color_code ?? '#1c1c1c' }}"
                          title="{{ $variant->name }}"></span>
                @endforeach
                @if($product->variants->count() > 4)
                    <span class="text-[9px] text-neutral-400 font-semibold">+{{ $product->variants->count() - 4 }}</span>
                @endif
            </div>
        @endif
    </div>
</div>

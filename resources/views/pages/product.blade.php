@extends('layouts.app')

@section('title', $product->title . ' | Moutoshi Handbag BD')
@section('meta_description', $product->short_description ?? $product->title)

@section('content')

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs font-medium tracking-wider uppercase text-neutral-500">
        <a href="{{ route('home') }}" class="hover:text-black">Home</a>
        <span class="mx-2">/</span>
        @if($product->category)
            <a href="{{ route('shop.index', $product->category->slug) }}" class="hover:text-black">{{ $product->category->name }}</a>
            <span class="mx-2">/</span>
        @endif
        <span class="text-neutral-900 truncate">{{ $product->title }}</span>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16" 
             x-data="{
                 activeImage: '{{ $product->primary_image_url }}',
                 selectedVariant: '{{ $product->variants->first()?->name ?? 'Default' }}',
                 quantity: 1,
                 activeTab: 'desc'
             }">
            
            @php
                $allImages = collect();
                if ($product->primary_image_url) {
                    $allImages->push((object)[
                        'url' => $product->primary_image_url,
                        'is_primary' => true
                    ]);
                }
                foreach ($product->images as $img) {
                    if ($img->url !== $product->primary_image_url) {
                        $allImages->push((object)[
                            'url' => $img->url,
                            'is_primary' => false
                        ]);
                    }
                }
            @endphp

            <!-- Left: Gallery -->
            <div class="flex flex-col-reverse md:flex-row gap-4">
                <!-- Thumbnails -->
                @if($allImages->count() > 1)
                    <div class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto max-h-[550px] shrink-0 pb-2 md:pb-0 scrollbar-thin">
                        @foreach($allImages as $item)
                            <button type="button" 
                                    @click="activeImage = '{{ $item->url }}'"
                                    class="w-16 h-20 sm:w-20 sm:h-24 shrink-0 border-2 overflow-hidden transition cursor-pointer bg-neutral-100 rounded-xs"
                                    :class="activeImage === '{{ $item->url }}' ? 'border-[#1c1c1c] ring-1 ring-[#1c1c1c]' : 'border-neutral-200 hover:border-neutral-400 opacity-75 hover:opacity-100'">
                                <img src="{{ $item->url }}" alt="{{ $product->title }}" 
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/logo.png') }}';"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Main Active Image -->
                <div class="flex-1 bg-[#f9f9f9] border border-neutral-200 overflow-hidden relative aspect-[4/5] max-h-[620px]">
                    @if($product->is_sale && $product->discount_percent > 0)
                        <span class="absolute top-4 left-4 z-10 bg-[#c9a66b] text-white text-xs font-bold uppercase tracking-wider px-3 py-1 shadow-sm">
                            SAVE {{ $product->discount_percent }}%
                        </span>
                    @endif
                    <img :src="activeImage" 
                         alt="{{ $product->title }}" 
                         class="w-full h-full object-cover object-center transition duration-500 hover:scale-105 cursor-zoom-in">

                    <!-- Brand Watermark Logo Badge (Top Right Corner Circular) -->
                    <div class="absolute top-4 right-4 z-10 pointer-events-none select-none">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white/95 backdrop-blur-xs shadow-md border-2 border-white flex items-center justify-center p-2.5 sm:p-3">
                            <img src="{{ asset('assets/images/logo.png') }}" 
                                 alt="Moutoshi Handbag" 
                                 class="w-full h-auto max-h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Product Info & Buy Controls -->
            <div class="flex flex-col justify-start space-y-6">
                <div>
                    @if($product->category)
                        <span class="text-xs font-bold tracking-[0.2em] uppercase text-[#c9a66b] block mb-1">
                            {{ $product->category->name }}
                        </span>
                    @endif
                    <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wide text-neutral-900 font-sans leading-tight">
                        {{ $product->title }}
                    </h1>
                    <div class="mt-2 flex items-center gap-2 text-xs text-neutral-500 font-medium">
                        <span>SKU: {{ $product->sku ?? 'PTC-8821' }}</span>
                        <span>•</span>
                        <div class="flex items-center text-[#c9a66b]">
                            ★★★★★
                            <span class="ml-1 text-neutral-600 font-bold">(4.9 / 5.0)</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="flex items-baseline gap-3 pb-4 border-b border-neutral-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#1c1c1c]">
                        {{ $product->formatted_price }}
                    </span>
                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                        <span class="text-lg text-neutral-400 line-through">
                            {{ $product->formatted_compare_price }}
                        </span>
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-sm">
                            {{ $product->discount_percent }}% OFF
                        </span>
                    @endif
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                    <p class="text-xs sm:text-sm text-neutral-600 font-light leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                @endif

                <!-- Variant Selection -->
                @if($product->variants->count() > 0)
                    <div class="space-y-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-900">
                            COLOR: <span class="font-normal text-neutral-600" x-text="selectedVariant"></span>
                        </label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($product->variants as $variant)
                                <button type="button"
                                        @click="selectedVariant = '{{ $variant->name }}'"
                                        class="px-4 py-2 text-xs font-semibold uppercase tracking-wider border transition flex items-center gap-2"
                                        :class="selectedVariant === '{{ $variant->name }}' ? 'border-black bg-neutral-900 text-white' : 'border-neutral-300 text-neutral-800 hover:border-black'">
                                    @if($variant->color_code)
                                        <span class="w-2.5 h-2.5 rounded-full border border-neutral-400" style="background-color: {{ $variant->color_code }}"></span>
                                    @endif
                                    <span>{{ $variant->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity & Actions -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-neutral-900">QUANTITY:</label>
                        <div class="flex items-center border border-neutral-300 w-32">
                            <button type="button" @click="if (quantity > 1) quantity--" class="px-3 py-2 text-sm hover:bg-neutral-100 transition">-</button>
                            <input type="number" x-model="quantity" readonly class="w-full text-center text-xs font-bold py-2 border-none focus:outline-none">
                            <button type="button" @click="quantity++" class="px-3 py-2 text-sm hover:bg-neutral-100 transition">+</button>
                        </div>
                    </div>

                    <!-- Dual CTAs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <!-- Add to Cart (Opens Slide Drawer) -->
                        <button type="button"
                                @click="$store.cart.addToCart({{ $product->id }}, quantity, selectedVariant)"
                                class="w-full border-2 border-[#1c1c1c] text-[#1c1c1c] py-4 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-100 transition text-center shadow-xs">
                            ADD TO BAG
                        </button>

                        <!-- Buy It Now / 1-Click Direct to Checkout -->
                        <button type="button"
                                @click="buyNowToCheckout({{ $product->id }}, quantity, selectedVariant)"
                                class="w-full bg-[#1c1c1c] text-white py-4 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-neutral-800 transition text-center shadow-md">
                            BUY IT NOW (ক্যাশ অন ডেলিভারি)
                        </button>
                    </div>
                </div>

                <!-- Trust Points -->
                <div class="border-t border-neutral-200 pt-6 space-y-2.5 text-xs text-neutral-600">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Cash on Delivery available all over Bangladesh</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Delivery: 24-48 hours (Dhaka) | 48-72 hours (Outside Dhaka)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>7 Days hassle-free exchange & replacement guarantee</span>
                    </div>
                </div>

                <!-- Accordion Tabs -->
                <div class="border-t border-neutral-200 pt-6">
                    <div class="border-b border-neutral-200 pb-3">
                        <button type="button" 
                                @click="activeTab = (activeTab === 'desc' ? '' : 'desc')"
                                class="w-full flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900">
                            <span>PRODUCT DETAILS & DESCRIPTION</span>
                            <span x-text="activeTab === 'desc' ? '−' : '+'" class="text-base"></span>
                        </button>
                        <div x-show="activeTab === 'desc'" class="pt-3 text-xs text-neutral-600 font-light leading-relaxed prose prose-sm" x-cloak>
                            {!! $product->description ?? $product->short_description !!}
                        </div>
                    </div>

                    <div class="border-b border-neutral-200 py-3">
                        <button type="button" 
                                @click="activeTab = (activeTab === 'ship' ? '' : 'ship')"
                                class="w-full flex justify-between items-center text-xs font-bold uppercase tracking-wider text-neutral-900">
                            <span>SHIPPING & RETURNS</span>
                            <span x-text="activeTab === 'ship' ? '−' : '+'" class="text-base"></span>
                        </button>
                        <div x-show="activeTab === 'ship'" class="pt-3 text-xs text-neutral-600 font-light leading-relaxed space-y-2" x-cloak>
                            <p>Inside Dhaka: ৳ 80 (Delivered within 24-48 hours via Pathao Courier / Steedfast).</p>
                            <p>Outside Dhaka: ৳ 130 (Delivered within 48-72 hours via Pathao Courier / SA Paribahan / Sundarban).</p>
                            <p>You can inspect the product in front of the rider before making payment.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- You May Also Like (Related Products) -->
        @if($relatedProducts->count() > 0)
            <div class="mt-20 pt-12 border-t border-neutral-200">
                <div class="text-center mb-10">
                    <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-1">PAIR WITH</span>
                    <h2 class="text-2xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                        YOU MAY ALSO LIKE
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function buyNowToCheckout(productId, quantity, variant) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity || 1,
                    variant: variant || ''
                })
            })
            .then(() => {
                window.location.href = "{{ route('checkout.index') }}";
            })
            .catch(() => {
                window.location.href = "{{ route('checkout.index') }}";
            });
        }
    </script>
    @endpush

@endsection

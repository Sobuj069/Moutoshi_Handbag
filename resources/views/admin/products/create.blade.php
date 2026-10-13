@extends('admin.layouts.admin')

@section('title', 'Add New Product')
@section('header_title', 'Add New Bag Product')

@section('content')

    <div class="max-w-4xl bg-white border border-neutral-200 rounded-xs shadow-2xs p-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Product Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Vintage Commuter Shoulder Bag"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="e.g. PTC-SH-099"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Sale Price (৳) *</label>
                    <input type="number" step="0.01" name="price" required value="{{ old('price') }}" placeholder="1850"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Compare Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Compare Price (৳ / Regular Price)</label>
                    <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price') }}" placeholder="2950"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Inventory Stock *</label>
                    <input type="number" name="stock" required value="{{ old('stock', 50) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Variants -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Colors / Variants (comma-separated)</label>
                    <input type="text" name="variant_names" value="{{ old('variant_names', 'Black, Brown, Beige') }}" placeholder="Black, Brown, Beige"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Primary Image URL -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Primary Image URL (Shopify / CDN)</label>
                    <input type="url" name="primary_image" value="{{ old('primary_image') }}" placeholder="https://thepatchee.com/cdn/shop/files/..."
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Primary Image File -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Or Upload Image File</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full text-xs px-4 py-2.5 border border-neutral-300 bg-neutral-50">
                </div>

                <!-- Short Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Short Summary / Highlights</label>
                    <textarea name="short_description" rows="2" placeholder="Brief 1-2 sentence description..."
                              class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">{{ old('short_description') }}</textarea>
                </div>

                <!-- Full Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Detailed Description (HTML / Specs)</label>
                    <textarea name="description" rows="5" placeholder="Dimensions, material quality, strap details..."
                              class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">{{ old('description') }}</textarea>
                </div>

                <!-- Checkbox Flags -->
                <div class="md:col-span-2 flex flex-wrap gap-6 pt-2">
                    <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-700 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="text-black">
                        <span>Featured on Homepage</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-700 cursor-pointer">
                        <input type="checkbox" name="is_sale" value="1" checked class="text-black">
                        <span>Sale Badge</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-700 cursor-pointer">
                        <input type="checkbox" name="is_new" value="1" checked class="text-black">
                        <span>New Arrival Badge</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="text-black">
                        <span>Active for Sale</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-neutral-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-3 border border-neutral-300 text-neutral-700 text-xs font-bold uppercase tracking-wider hover:bg-neutral-100">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition shadow-sm">
                    Create Product
                </button>
            </div>
        </form>
    </div>

@endsection

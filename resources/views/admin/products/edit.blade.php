@extends('admin.layouts.admin')

@section('title', 'Edit Product: ' . $product->title)
@section('header_title', 'Edit Product')

@section('content')

    <div class="max-w-4xl bg-white border border-neutral-200 rounded-xs shadow-2xs p-4 sm:p-6 lg:p-8">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Product Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $product->title) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Sale Price (৳) *</label>
                    <input type="number" step="0.01" name="price" required value="{{ old('price', $product->price) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Compare Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Compare Price (৳)</label>
                    <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Inventory Stock *</label>
                    <input type="number" name="stock" required value="{{ old('stock', $product->stock) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Variants -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Colors / Variants (comma-separated)</label>
                    <input type="text" name="variant_names" 
                           value="{{ old('variant_names', $product->variants->pluck('name')->join(', ')) }}" 
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <!-- Primary Image URL -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Primary Image URL</label>
                    <input type="url" name="primary_image" value="{{ old('primary_image', $product->primary_image) }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                    @if($product->primary_image)
                        <img src="{{ $product->primary_image_url }}" class="w-16 h-16 object-cover border mt-2">
                    @endif
                </div>

                <!-- Primary Image File -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Or Upload New Image</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full text-xs px-4 py-2.5 border border-neutral-300 bg-neutral-50">
                </div>

                <!-- Short Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Short Summary</label>
                    <textarea name="short_description" rows="2"
                              class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <!-- Full Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Detailed Description</label>
                    <textarea name="description" rows="5"
                              class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Section & Badge Cards -->
                <div class="md:col-span-2 pt-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">Sections & Badges (সেকশন ও ব্যাজ নির্বাচন করুন)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                            <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-4 h-4 rounded-xs text-black focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-neutral-900 uppercase">⭐ FEATURED</span>
                                <span class="block text-[10px] text-neutral-500">Show on homepage</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                            <input type="checkbox" name="is_sale" value="1" {{ $product->is_sale ? 'checked' : '' }} class="w-4 h-4 rounded-xs text-black focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-neutral-900 uppercase">🏷️ SALE BADGE</span>
                                <span class="block text-[10px] text-neutral-500">Highlight discount</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                            <input type="checkbox" name="is_new" value="1" {{ $product->is_new ? 'checked' : '' }} class="w-4 h-4 rounded-xs text-black focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-neutral-900 uppercase">✨ NEW ARRIVAL</span>
                                <span class="block text-[10px] text-neutral-500">Mark as new drop</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-4 h-4 rounded-xs text-black focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-neutral-900 uppercase">✅ ACTIVE FOR SALE</span>
                                <span class="block text-[10px] text-neutral-500">Publish in store</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-neutral-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-3 border border-neutral-300 text-neutral-700 text-xs font-bold uppercase tracking-wider hover:bg-neutral-100 text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition shadow-sm text-center">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

@endsection

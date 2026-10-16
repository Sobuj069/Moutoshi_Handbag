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

                <!-- Primary Image URL / Path -->
                <div x-data="{ 
                    previewUrl: '{{ $product->primary_image_url }}',
                    updatePreview(val) {
                        val = (val || '').trim();
                        if (!val) {
                            this.previewUrl = '{{ asset('assets/images/logo.png') }}';
                        } else if (val.startsWith('http://') || val.startsWith('https://')) {
                            this.previewUrl = val;
                        } else if (val.startsWith('assets/') || val.startsWith('images/') || val.startsWith('uploads/')) {
                            this.previewUrl = '{{ asset('') }}' + val;
                        } else {
                            this.previewUrl = '{{ asset('storage') }}/' + val.replace(/^\/+/, '');
                        }
                    }
                }">
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Primary Image URL / Path</label>
                    <input type="text" name="primary_image" value="{{ old('primary_image', $product->primary_image) }}"
                           @input="updatePreview($event.target.value)"
                           placeholder="products/filename.jpg or https://..."
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black font-mono">
                    <div class="mt-2.5 flex items-center gap-3 p-2.5 bg-neutral-50 border border-neutral-200 rounded-xs">
                        <img :src="previewUrl" alt="{{ $product->title }}" 
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/logo.png') }}';"
                             class="w-16 h-16 object-cover border border-neutral-300 rounded-xs bg-white shrink-0 shadow-2xs">
                        <div class="text-[11px] min-w-0">
                            <span class="font-bold text-neutral-800 block">Current Image Preview</span>
                            <span class="text-[10px] text-neutral-500 font-mono truncate block max-w-xs">{{ $product->primary_image ?: 'Default placeholder' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Primary Image File -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Or Upload New Image File</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full text-xs px-4 py-2.5 border border-neutral-300 bg-neutral-50">
                    <p class="text-[10px] text-neutral-400 mt-1">Supported: JPG, PNG, WEBP (Max 3MB). Replaces primary image.</p>
                </div>

                <!-- Gallery Images Management (Existing + Add New) -->
                <div class="md:col-span-2 pt-2 pb-2 border-t border-neutral-100" x-data="{
                    newGalleryPreviews: [],
                    handleNewGallery(e) {
                        const files = Array.from(e.target.files || []);
                        files.forEach(f => {
                            this.newGalleryPreviews.push({
                                name: f.name,
                                url: URL.createObjectURL(f),
                                file: f
                            });
                        });
                        this.syncNewGalleryInput();
                    },
                    removeNewGallery(index) {
                        this.newGalleryPreviews.splice(index, 1);
                        this.syncNewGalleryInput();
                    },
                    syncNewGalleryInput() {
                        const input = document.getElementById('edit_gallery_images_input');
                        if (!input || typeof DataTransfer === 'undefined') return;
                        const dt = new DataTransfer();
                        this.newGalleryPreviews.forEach(p => {
                            if (p.file) dt.items.add(p.file);
                        });
                        input.files = dt.files;
                    },
                    deleteExisting(id, btnEl) {
                        if (!confirm('Are you sure you want to remove this gallery photo?')) return;
                        const container = btnEl.closest('.gallery-item');
                        fetch('{{ url('admin/products/gallery') }}/' + id, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if (container) container.remove();
                            } else {
                                alert(data.message || 'Failed to delete image');
                            }
                        })
                        .catch(err => alert('Network error: ' + err.message));
                    }
                }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-800 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Product Gallery Photos (গ্যালারির একাধিক ছবি)</span>
                        </label>
                        <span class="text-[11px] text-neutral-500">Manage existing photos or upload more</span>
                    </div>

                    <!-- Existing Gallery Images -->
                    @if($product->images && $product->images->count() > 0)
                        <div class="mb-4 p-3 bg-neutral-50 border border-neutral-200 rounded-xs">
                            <p class="text-[11px] font-bold text-neutral-700 uppercase tracking-wider mb-2.5">
                                Current Gallery Photos ({{ $product->images->count() }})
                            </p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->images as $img)
                                    <div class="gallery-item relative group border border-neutral-300 rounded-xs bg-white p-1 shadow-2xs">
                                        <img src="{{ $img->url }}" alt="Gallery Image"
                                             onerror="this.onerror=null; this.src='{{ asset('assets/images/logo.png') }}';"
                                             class="w-20 h-20 object-cover rounded-xs">
                                        <div class="text-[9px] text-neutral-500 font-mono mt-1 max-w-[80px] truncate text-center">
                                            {{ basename($img->image_url) }}
                                        </div>
                                        <button type="button" @click="deleteExisting({{ $img->id }}, $el)"
                                                title="Delete this photo"
                                                class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white w-5 h-5 rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Gallery Images -->
                    <div class="border-2 border-dashed border-neutral-300 hover:border-black rounded-xs p-5 text-center bg-neutral-50/70 hover:bg-white transition cursor-pointer relative group">
                        <input type="file" name="gallery_images[]" id="edit_gallery_images_input" multiple accept="image/*"
                               @change="handleNewGallery($event)"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center pointer-events-none">
                            <div class="w-10 h-10 rounded-full bg-neutral-200/70 group-hover:bg-neutral-900 group-hover:text-white flex items-center justify-center transition mb-2">
                                <svg class="w-5 h-5 text-neutral-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-neutral-800">
                                Click or drag & drop to add more gallery photos
                            </p>
                            <p class="text-[11px] text-neutral-500 mt-0.5">
                                Multiple photos can be selected at once (JPG, PNG, WEBP)
                            </p>
                        </div>
                    </div>

                    <!-- Previews of newly selected files -->
                    <div x-show="newGalleryPreviews.length > 0" class="mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-neutral-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <span>New Photos Ready to Upload (<span x-text="newGalleryPreviews.length"></span>)</span>
                            </span>
                            <button type="button" @click="newGalleryPreviews = []; syncNewGalleryInput()" class="text-[10px] text-red-600 hover:underline">
                                Cancel new uploads
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="(p, idx) in newGalleryPreviews" :key="idx">
                                <div class="relative group border border-emerald-500 rounded-xs overflow-hidden bg-white shadow-2xs">
                                    <img :src="p.url" class="w-20 h-20 object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-black/80 text-[9px] text-white text-center font-mono py-0.5 truncate px-1" x-text="p.name"></span>
                                    <button type="button" @click.stop.prevent="removeNewGallery(idx)"
                                            title="Remove this photo"
                                            class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white w-5 h-5 rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs cursor-pointer">
                                        ✕
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
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

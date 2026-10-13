@extends('admin.layouts.admin')

@section('title', 'Add New Product')
@section('header_title', 'Add New Bag Product')

@section('content')

    <div x-data="aiProductImporter()" class="max-w-4xl space-y-6">

        <!-- ========================================== -->
        <!-- ✨ 1. AI SMART IMPORT CARD (PINTEREST / ANY URL) -->
        <!-- ========================================== -->
        <div class="bg-gradient-to-r from-neutral-900 via-[#1c1c1c] to-neutral-800 text-white border border-neutral-700 rounded-xs shadow-lg p-5 sm:p-6 transition-all duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-neutral-700/80 mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                    </span>
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold tracking-wider uppercase text-white flex items-center gap-1.5">
                            <span>✨ AI Smart Auto-Fill from URL</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-normal border border-indigo-500/30">Pinterest & Any Store</span>
                        </h2>
                        <p class="text-[11px] text-neutral-400 mt-0.5">
                            পিন্টারেস্ট বা যেকোনো শপের লিঙ্ক দিন—Gemini AI হাই-কোয়ালিটি ছবি, নাম, ক্যাটাগরি, ব্র্যান্ড ও দাম অটো-ফিল করবে!
                        </p>
                    </div>
                </div>
                <div class="text-[10px] text-neutral-400 font-mono tracking-wider bg-black/40 px-2.5 py-1 rounded-xs border border-neutral-700 self-start sm:self-auto">
                    Gemini 3.5 AI Engine
                </div>
            </div>

            <!-- URL Input Row -->
            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        </div>
                        <input type="url" x-model="importUrl" @keydown.enter.prevent="fetchAiProduct()"
                               placeholder="e.g. https://www.pinterest.com/pin/... or https://pin.it/... or any shop URL"
                               class="w-full text-xs pl-10 pr-4 py-3 bg-black/50 border border-neutral-600 rounded-xs text-white placeholder-neutral-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition font-mono">
                    </div>
                    <button type="button" @click="fetchAiProduct()" :disabled="isLoading || !importUrl"
                            class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold uppercase tracking-wider rounded-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer shrink-0">
                        <template x-if="isLoading">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <template x-if="!isLoading">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </template>
                        <span x-text="isLoading ? 'Analyzing & Fetching...' : '⚡ Auto-Fetch & Fill'"></span>
                    </button>
                </div>

                <!-- Loading Message Animation -->
                <div x-show="isLoading" x-cloak class="p-3 bg-indigo-950/40 border border-indigo-800/60 rounded-xs text-xs text-indigo-200 flex items-center gap-2.5 animate-pulse">
                    <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>অনুগ্রহ করে অপেক্ষা করুন—প্রোডাক্টের ফুল হাই-কোয়ালিটি ছবি ডাউনলোড হচ্ছে এবং Gemini AI দিয়ে নাম, দাম ও বর্ণনা প্রসেস করা হচ্ছে...</span>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" x-cloak class="p-3 bg-red-950/50 border border-red-800 text-red-200 text-xs rounded-xs flex items-center justify-between">
                    <span x-text="errorMessage"></span>
                    <button type="button" @click="errorMessage = ''" class="text-red-400 hover:text-white">&times;</button>
                </div>

                <!-- Success Info & Preview Bar -->
                <div x-show="fetchedSuccess" x-cloak class="mt-3 p-4 bg-emerald-950/40 border border-emerald-700/60 rounded-xs text-xs space-y-3">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-emerald-300 font-bold">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>✓ ফুল হাই-কোয়ালিটি ছবি এবং সম্পূর্ণ বিবরণ সফলভাবে লোড হয়েছে! নিচের ফর্মে সব অটো-ফিল হয়ে গেছে।</span>
                        </div>

                        <!-- 1-Click Instant Publish Button -->
                        <button type="button" @click="quickPublish()" :disabled="isPublishing"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold uppercase tracking-wider text-[11px] rounded-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer shrink-0">
                            <span x-text="isPublishing ? 'Publishing...' : '🚀 Instant Publish Bag'"></span>
                        </button>
                    </div>

                    <!-- Fetched Image & Specs Preview -->
                    <div class="pt-3 border-t border-emerald-800/40 grid grid-cols-1 sm:grid-cols-4 gap-4 items-center">
                        <div class="sm:col-span-1 relative group">
                            <img :src="formData.primary_image_url" alt="HQ Preview" 
                                 class="w-full h-32 object-cover rounded-xs border-2 border-emerald-500 shadow-md">
                            <span class="absolute bottom-1 left-1 right-1 bg-black/80 text-[10px] text-emerald-300 text-center py-0.5 rounded-xs font-mono">
                                <span x-text="formData.primary_image_width + 'x' + formData.primary_image_height + ' px'"></span>
                            </span>
                        </div>
                        <div class="sm:col-span-3 space-y-1.5 text-neutral-200">
                            <h4 class="font-bold text-white text-xs sm:text-sm line-clamp-1" x-text="formData.title"></h4>
                            <div class="flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="bg-black/40 px-2 py-0.5 rounded-xs border border-neutral-700 text-emerald-300 font-bold" x-text="'৳ ' + formData.price"></span>
                                <span class="text-neutral-400 line-through" x-show="formData.compare_at_price" x-text="'৳ ' + formData.compare_at_price"></span>
                                <span class="bg-indigo-900/60 text-indigo-200 px-2 py-0.5 rounded-xs" x-text="formData.category_name"></span>
                                <span class="bg-neutral-800 text-neutral-300 px-2 py-0.5 rounded-xs" x-text="'SKU: ' + formData.sku"></span>
                                <span class="bg-emerald-900/50 text-emerald-200 px-2 py-0.5 rounded-xs">🌟 High-Res Full Photo Saved</span>
                            </div>
                            <p class="text-[11px] text-neutral-300 line-clamp-2" x-text="formData.short_description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. MAIN PRODUCT FORM -->
        <!-- ========================================== -->
        <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs p-4 sm:p-6 lg:p-8">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Hidden inputs for AI downloaded images -->
                <input type="hidden" name="ai_downloaded_image" :value="formData.primary_image_path">
                <input type="hidden" name="ai_gallery_images" :value="JSON.stringify(formData.gallery_images)">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Product Title *</label>
                        <input type="text" name="title" required x-model="formData.title" placeholder="e.g. Vintage Commuter Shoulder Bag"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black font-medium">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Category *</label>
                        <select name="category_id" required x-model="formData.category_id" class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black bg-white">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" x-model="formData.sku" placeholder="e.g. MH-AI-099"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black uppercase font-mono">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Sale Price (৳) *</label>
                        <input type="number" step="0.01" name="price" required x-model="formData.price" placeholder="850"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black font-bold">
                    </div>

                    <!-- Compare Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Compare Price (৳ / Regular Price)</label>
                        <input type="number" step="0.01" name="compare_at_price" x-model="formData.compare_at_price" placeholder="1250"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Inventory Stock *</label>
                        <input type="number" name="stock" required x-model="formData.stock"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <!-- Variants -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Colors / Variants (comma-separated)</label>
                        <input type="text" name="variant_names" x-model="formData.variant_names" placeholder="Off-White, Black, Beige"
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <!-- Primary Image URL / AI Preview -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                            Primary Image URL
                            <span x-show="formData.primary_image_path" class="text-emerald-700 font-bold ml-1">(AI Image Ready)</span>
                        </label>
                        <input type="text" name="primary_image" x-model="formData.primary_image_url" placeholder="https://..."
                               class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black">
                        <input type="hidden" name="ai_downloaded_image" :value="formData.primary_image_path">
                        <input type="hidden" name="ai_gallery_images" :value="JSON.stringify(formData.gallery_images)">
                    </div>

                    <!-- Primary Image File -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Or Upload Image File Manually</label>
                        <input type="file" name="image_file" accept="image/*"
                               class="w-full text-xs px-4 py-2.5 border border-neutral-300 bg-neutral-50">
                    </div>

                    <!-- Short Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Short Summary / Highlights</label>
                        <textarea name="short_description" rows="2" x-model="formData.short_description" placeholder="Brief 1-2 sentence description..."
                                  class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black"></textarea>
                    </div>

                    <!-- Full Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Detailed Description (HTML / Specs)</label>
                        <textarea name="description" rows="5" x-model="formData.description" placeholder="Dimensions, material quality, strap details..."
                                  class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black"></textarea>
                    </div>

                    <!-- Section & Badge Cards -->
                    <div class="md:col-span-2 pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">Sections & Badges (সেকশন ও ব্যাজ নির্বাচন করুন)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                                <input type="checkbox" name="is_featured" value="1" x-model="formData.is_featured" class="w-4 h-4 rounded-xs text-black focus:ring-0">
                                <div>
                                    <span class="block text-xs font-bold text-neutral-900 uppercase">⭐ FEATURED</span>
                                    <span class="block text-[10px] text-neutral-500">Show on homepage</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                                <input type="checkbox" name="is_sale" value="1" x-model="formData.is_sale" class="w-4 h-4 rounded-xs text-black focus:ring-0">
                                <div>
                                    <span class="block text-xs font-bold text-neutral-900 uppercase">🏷️ SALE BADGE</span>
                                    <span class="block text-[10px] text-neutral-500">Highlight discount</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                                <input type="checkbox" name="is_new" value="1" x-model="formData.is_new" class="w-4 h-4 rounded-xs text-black focus:ring-0">
                                <div>
                                    <span class="block text-xs font-bold text-neutral-900 uppercase">✨ NEW ARRIVAL</span>
                                    <span class="block text-[10px] text-neutral-500">Mark as new drop</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 border border-neutral-300 rounded-xs cursor-pointer hover:border-black transition bg-neutral-50/50">
                                <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="w-4 h-4 rounded-xs text-black focus:ring-0">
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
                    <button type="submit" class="px-8 py-3 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition shadow-sm text-center cursor-pointer">
                        Create & Publish Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 3. ALPINE.JS COMPONENT SCRIPT -->
    <!-- ========================================== -->
    <script>
        function aiProductImporter() {
            return {
                importUrl: '',
                isLoading: false,
                isPublishing: false,
                errorMessage: '',
                fetchedSuccess: false,
                formData: {
                    title: '{{ old("title", "") }}',
                    category_id: '{{ old("category_id", "") }}',
                    category_name: '',
                    sku: '{{ old("sku", "") }}',
                    price: '{{ old("price", "") }}',
                    compare_at_price: '{{ old("compare_at_price", "") }}',
                    stock: '{{ old("stock", 50) }}',
                    variant_names: '{{ old("variant_names", "Black, Brown, Beige") }}',
                    primary_image_path: '',
                    primary_image_url: '{{ old("primary_image", "") }}',
                    primary_image_width: 0,
                    primary_image_height: 0,
                    primary_image_size_kb: 0,
                    gallery_images: [],
                    short_description: '{{ old("short_description", "") }}',
                    description: '{{ old("description", "") }}',
                    is_featured: {{ old('is_featured') ? 'true' : 'false' }},
                    is_sale: {{ old('is_sale', 1) ? 'true' : 'false' }},
                    is_new: {{ old('is_new', 1) ? 'true' : 'false' }},
                    is_active: {{ old('is_active', 1) ? 'true' : 'false' }},
                },

                fetchAiProduct() {
                    if (!this.importUrl) return;

                    this.isLoading = true;
                    this.errorMessage = '';
                    this.fetchedSuccess = false;

                    fetch('{{ route("admin.products.ai-import.analyze") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ url: this.importUrl })
                    })
                    .then(res => res.json())
                    .then(response => {
                        this.isLoading = false;

                        if (response.success && response.data) {
                            const d = response.data;
                            this.formData.title = d.title || '';
                            this.formData.category_id = d.category_id || '';
                            this.formData.category_name = d.category_name || '';
                            this.formData.sku = d.sku || '';
                            this.formData.price = d.price || '';
                            this.formData.compare_at_price = d.compare_at_price || '';
                            this.formData.stock = d.stock || 50;
                            this.formData.variant_names = d.variants || 'Off-White, Black, Beige';
                            this.formData.primary_image_path = d.primary_image_path || '';
                            this.formData.primary_image_url = d.primary_image_url || '';
                            this.formData.primary_image_width = d.primary_image_width || 0;
                            this.formData.primary_image_height = d.primary_image_height || 0;
                            this.formData.primary_image_size_kb = d.primary_image_size_kb || 0;
                            this.formData.gallery_images = d.gallery_images || [];
                            this.formData.short_description = d.short_description || '';
                            this.formData.description = d.description || '';
                            this.formData.is_featured = Boolean(d.is_featured);
                            this.formData.is_sale = Boolean(d.is_sale);
                            this.formData.is_new = Boolean(d.is_new);
                            this.formData.is_active = Boolean(d.is_active);

                            this.fetchedSuccess = true;
                        } else {
                            this.errorMessage = response.message || 'Could not import product data. Please check the URL and try again.';
                        }
                    })
                    .catch(err => {
                        this.isLoading = false;
                        this.errorMessage = 'Network error or invalid server response: ' + err.message;
                    });
                },

                quickPublish() {
                    if (!this.formData.title || !this.formData.price) {
                        alert('Please ensure Title and Price are filled before publishing.');
                        return;
                    }

                    this.isPublishing = true;

                    fetch('{{ route("admin.products.ai-import.quick-create") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.formData)
                    })
                    .then(res => res.json())
                    .then(res => {
                        this.isPublishing = false;
                        if (res.success) {
                            window.location.href = res.redirect_url;
                        } else {
                            alert(res.message || 'Failed to save product.');
                        }
                    })
                    .catch(err => {
                        this.isPublishing = false;
                        alert('Error: ' + err.message);
                    });
                }
            };
        }
    </script>

@endsection

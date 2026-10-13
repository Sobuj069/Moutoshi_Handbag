@extends('admin.layouts.admin')

@section('title', 'Products Management')
@section('header_title', 'Products Management')

@section('content')

    <div x-data="{
        selected: [],
        allIds: {{ json_encode($products->pluck('id')) }},
        allSelected: false,
        toggleAll() {
            if (this.selected.length === this.allIds.length) {
                this.selected = [];
                this.allSelected = false;
            } else {
                this.selected = [...this.allIds];
                this.allSelected = true;
            }
        },
        updateSelectAll() {
            this.allSelected = (this.selected.length === this.allIds.length && this.allIds.length > 0);
        },

        // AI Import Modal State
        aiModalOpen: false,
        aiUrl: '',
        aiLoading: false,
        aiError: '',
        aiData: null,
        aiPublishing: false,

        fetchAi() {
            if (!this.aiUrl) return;
            this.aiLoading = true;
            this.aiError = '';
            this.aiData = null;

            fetch('{{ route("admin.products.ai-import.analyze") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ url: this.aiUrl })
            })
            .then(res => res.json())
            .then(res => {
                this.aiLoading = false;
                if (res.success && res.data) {
                    this.aiData = res.data;
                } else {
                    this.aiError = res.message || 'Could not import product data. Please check the URL.';
                }
            })
            .catch(err => {
                this.aiLoading = false;
                this.aiError = 'Error: ' + err.message;
            });
        },

        publishAi() {
            if (!this.aiData) return;
            this.aiPublishing = true;

            fetch('{{ route("admin.products.ai-import.quick-create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.aiData)
            })
            .then(res => res.json())
            .then(res => {
                this.aiPublishing = false;
                if (res.success) {
                    window.location.reload();
                } else {
                    alert(res.message || 'Failed to publish product.');
                }
            })
            .catch(err => {
                this.aiPublishing = false;
                alert('Error: ' + err.message);
            });
        }
    }">

        <!-- Top Controls: Search, Category Filter, and Add New Button -->
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center mb-5 gap-3">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-1 max-w-2xl">
                <!-- Search input -->
                <form method="GET" class="flex-1">
                    @if(request('section'))
                        <input type="hidden" name="section" value="{{ request('section') }}">
                    @endif
                    @if(request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title or SKU..." 
                               class="w-full text-xs pl-9 pr-4 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black transition">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </form>

                <!-- Category filter dropdown -->
                <form method="GET" class="shrink-0">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('section'))
                        <input type="hidden" name="section" value="{{ request('section') }}">
                    @endif
                    <select name="category_id" onchange="this.form.submit()" 
                            class="w-full sm:w-auto text-xs px-3.5 py-2.5 border border-neutral-300 rounded-xs bg-white focus:outline-none focus:border-black cursor-pointer font-medium">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Action Buttons: AI Import & Add Product -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" @click="aiModalOpen = true"
                        class="px-4 py-2.5 bg-gradient-to-r from-indigo-700 to-indigo-600 hover:from-indigo-600 hover:to-indigo-500 text-white text-xs font-bold uppercase tracking-wider transition rounded-xs shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span>✨ AI Import</span>
                </button>

                <a href="{{ route('admin.products.create') }}" 
                   class="px-5 py-2.5 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition text-center rounded-xs flex items-center justify-center gap-2 shadow-xs">
                    <span>+ ADD NEW PRODUCT</span>
                </a>
            </div>
        </div>

        <!-- Section Filters Strip (Featured, Sale, New Arrival, Active, Draft) -->
        <div class="mb-5 overflow-x-auto pb-1">
            @php $currentSection = request('section'); @endphp
            <div class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap min-w-max">
                <a href="{{ route('admin.products.index', array_filter(['q' => request('q'), 'category_id' => request('category_id')])) }}" 
                   class="px-3.5 py-2 rounded-xs transition border flex items-center gap-2 {{ !$currentSection ? 'bg-[#1c1c1c] text-white border-black shadow-xs' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-neutral-100' }}">
                    <span>All Products</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ !$currentSection ? 'bg-[#c9a66b] text-white font-bold' : 'bg-neutral-200 text-neutral-700' }}">{{ $counts['all'] }}</span>
                </a>

                <a href="{{ route('admin.products.index', array_filter(['section' => 'featured', 'q' => request('q'), 'category_id' => request('category_id')])) }}" 
                   class="px-3.5 py-2 rounded-xs transition border flex items-center gap-1.5 {{ $currentSection === 'featured' ? 'bg-[#1c1c1c] text-white border-black shadow-xs' : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-100' }}">
                    <span>⭐ Featured on Homepage</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentSection === 'featured' ? 'bg-[#c9a66b] text-white font-bold' : 'bg-purple-100 text-purple-800' }}">{{ $counts['featured'] }}</span>
                </a>

                <a href="{{ route('admin.products.index', array_filter(['section' => 'sale', 'q' => request('q'), 'category_id' => request('category_id')])) }}" 
                   class="px-3.5 py-2 rounded-xs transition border flex items-center gap-1.5 {{ $currentSection === 'sale' ? 'bg-[#1c1c1c] text-white border-black shadow-xs' : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-100' }}">
                    <span>🏷️ Sale Badge</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentSection === 'sale' ? 'bg-[#c9a66b] text-white font-bold' : 'bg-amber-100 text-amber-800' }}">{{ $counts['sale'] }}</span>
                </a>

                <a href="{{ route('admin.products.index', array_filter(['section' => 'new', 'q' => request('q'), 'category_id' => request('category_id')])) }}" 
                   class="px-3.5 py-2 rounded-xs transition border flex items-center gap-1.5 {{ $currentSection === 'new' ? 'bg-[#1c1c1c] text-white border-black shadow-xs' : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-100' }}">
                    <span>✨ New Arrival Badge</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentSection === 'new' ? 'bg-[#c9a66b] text-white font-bold' : 'bg-blue-100 text-blue-800' }}">{{ $counts['new'] }}</span>
                </a>

                <a href="{{ route('admin.products.index', array_filter(['section' => 'active', 'q' => request('q'), 'category_id' => request('category_id')])) }}" 
                   class="px-3.5 py-2 rounded-xs transition border flex items-center gap-1.5 {{ $currentSection === 'active' ? 'bg-[#1c1c1c] text-white border-black shadow-xs' : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-100' }}">
                    <span>✅ Active for Sale</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentSection === 'active' ? 'bg-[#c9a66b] text-white font-bold' : 'bg-green-100 text-green-800' }}">{{ $counts['active'] }}</span>
                </a>

                <a href="{{ route('admin.products.index', array_filter(['section' => 'draft', 'q' => request('q'), 'category_id' => request('category_id')])) }}" 
                   class="px-3.5 py-2 rounded-xs transition border flex items-center gap-1.5 {{ $currentSection === 'draft' ? 'bg-[#1c1c1c] text-white border-black shadow-xs' : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-100' }}">
                    <span>⏸️ Draft / Inactive</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentSection === 'draft' ? 'bg-[#c9a66b] text-white font-bold' : 'bg-neutral-200 text-neutral-700' }}">{{ $counts['draft'] }}</span>
                </a>
            </div>
        </div>

        <!-- Floating Bulk Action Toolbar (Appears when 1 or more products are selected) -->
        <div x-show="selected.length > 0" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mb-4 p-3.5 bg-[#1c1c1c] text-white rounded-xs shadow-lg border border-neutral-700 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs">
            
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 bg-[#c9a66b] text-black font-extrabold rounded-xs text-xs tracking-wider" x-text="selected.length + ' SELECTED'"></span>
                <span class="text-neutral-300 font-medium">Select action to apply to all selected products:</span>
            </div>

            <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="flex flex-wrap items-center gap-2">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="product_ids[]" :value="id">
                </template>

                <select name="action" required 
                        class="text-xs px-3 py-2 bg-neutral-900 border border-neutral-700 text-white rounded-xs focus:outline-none focus:border-[#c9a66b] cursor-pointer">
                    <option value="">-- Choose Bulk Action --</option>
                    <optgroup label="Badges & Sections">
                        <option value="mark_featured">⭐ Add to Featured on Homepage</option>
                        <option value="unmark_featured">⚪ Remove from Featured</option>
                        <option value="mark_sale">🏷️ Add Sale Badge</option>
                        <option value="unmark_sale">⚪ Remove Sale Badge</option>
                        <option value="mark_new">✨ Add New Arrival Badge</option>
                        <option value="unmark_new">⚪ Remove New Arrival Badge</option>
                    </optgroup>
                    <optgroup label="Visibility / Status">
                        <option value="set_active">✅ Set as Active (Visible)</option>
                        <option value="set_draft">⏸️ Set as Draft (Hidden)</option>
                    </optgroup>
                    <optgroup label="Danger Zone">
                        <option value="delete">🗑️ Delete Selected Products</option>
                    </optgroup>
                </select>

                <button type="submit" 
                        onclick="return confirm('Are you sure you want to apply this action to all selected products?');"
                        class="px-4 py-2 bg-[#c9a66b] hover:bg-[#b38f54] text-white font-bold uppercase tracking-wider transition rounded-xs shadow-xs">
                    Apply Action
                </button>

                <button type="button" @click="selected = []; allSelected = false" 
                        class="px-3 py-2 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 text-xs font-semibold transition rounded-xs">
                    Clear Selection
                </button>
            </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-700">
                    <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-500 tracking-wider">
                        <tr>
                            <th class="py-3 px-4 w-10 text-center">
                                <input type="checkbox" @change="toggleAll()" :checked="allSelected" 
                                       title="Select all products on page"
                                       class="w-4 h-4 rounded-xs border-neutral-300 text-black focus:ring-0 cursor-pointer">
                            </th>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Stock</th>
                            <th class="py-3 px-4">Badges & Sections</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @forelse($products as $product)
                            <tr :class="selected.includes({{ $product->id }}) ? 'bg-amber-50/70' : 'hover:bg-neutral-50'" class="transition">
                                <td class="py-4 px-4 text-center">
                                    <input type="checkbox" :value="{{ $product->id }}" x-model="selected" @change="updateSelectAll()"
                                           class="w-4 h-4 rounded-xs border-neutral-300 text-black focus:ring-0 cursor-pointer">
                                </td>
                                <td class="py-4 px-4 flex items-center gap-3">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->title }}" 
                                         class="w-12 h-14 object-cover border border-neutral-200 shrink-0">
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-neutral-900 uppercase truncate max-w-xs">{{ $product->title }}</h4>
                                        <span class="text-[11px] text-neutral-400">SKU: {{ $product->sku ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium">{{ $product->category->name ?? 'Uncategorized' }}</td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="font-bold text-neutral-900">{{ $product->formatted_price }}</span>
                                    @if($product->compare_at_price)
                                        <span class="text-[11px] text-neutral-400 line-through block">{{ $product->formatted_compare_price }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-semibold whitespace-nowrap">
                                    <span class="{{ $product->stock <= 5 ? 'text-red-600 font-bold' : '' }}">
                                        {{ $product->stock }} in stock
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($product->is_featured)
                                            <span class="px-1.5 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-bold rounded-xs">⭐ FEATURED</span>
                                        @endif
                                        @if($product->is_sale)
                                            <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-xs">🏷️ SALE</span>
                                        @endif
                                        @if($product->is_new)
                                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-xs">✨ NEW</span>
                                        @endif
                                        @if(!$product->is_featured && !$product->is_sale && !$product->is_new)
                                            <span class="text-[11px] text-neutral-400">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                        <input type="hidden" name="action" value="{{ $product->is_active ? 'set_draft' : 'set_active' }}">
                                        <button type="submit" title="Click to toggle Active/Draft" 
                                                class="px-2.5 py-1 text-[10px] font-bold rounded-xs uppercase cursor-pointer transition border {{ $product->is_active ? 'bg-green-100 text-green-800 border-green-300 hover:bg-green-200' : 'bg-neutral-100 text-neutral-600 border-neutral-300 hover:bg-neutral-200' }}">
                                            {{ $product->is_active ? '● ACTIVE' : '○ DRAFT' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <!-- Actions Dropdown -->
                                    <div x-data="{ open: false }" class="relative inline-block text-left" @click.away="open = false">
                                        <button type="button" @click="open = !open" 
                                                class="px-3 py-1.5 bg-[#1c1c1c] hover:bg-black text-white rounded-xs text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                                            <span>Actions</span>
                                            <svg class="w-3.5 h-3.5 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        <div x-show="open" x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 mt-1.5 w-48 bg-white rounded-xs shadow-2xl border border-neutral-200 py-1.5 z-50 text-left divide-y divide-neutral-100 text-xs">
                                            
                                            <div class="py-1">
                                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                   class="flex items-center gap-2.5 px-3.5 py-2 text-neutral-800 hover:bg-neutral-100 font-semibold transition">
                                                    <svg class="w-4 h-4 text-neutral-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    <span>Edit Product</span>
                                                </a>

                                                <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                                                   class="flex items-center gap-2.5 px-3.5 py-2 text-neutral-800 hover:bg-neutral-100 font-semibold transition">
                                                    <svg class="w-4 h-4 text-neutral-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    <span>View on Store</span>
                                                </a>
                                            </div>

                                            <!-- Quick Badges & Status Toggle -->
                                            <div class="py-1">
                                                <span class="block px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-neutral-400">Toggle Badges:</span>
                                                
                                                <form action="{{ route('admin.products.bulk-action') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                    <input type="hidden" name="action" value="{{ $product->is_featured ? 'unmark_featured' : 'mark_featured' }}">
                                                    <button type="submit" class="w-full text-left px-3.5 py-1.5 text-[11px] text-neutral-700 hover:bg-neutral-100 transition flex items-center justify-between">
                                                        <span>⭐ {{ $product->is_featured ? 'Remove Featured' : 'Mark as Featured' }}</span>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.products.bulk-action') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                    <input type="hidden" name="action" value="{{ $product->is_sale ? 'unmark_sale' : 'mark_sale' }}">
                                                    <button type="submit" class="w-full text-left px-3.5 py-1.5 text-[11px] text-neutral-700 hover:bg-neutral-100 transition flex items-center justify-between">
                                                        <span>🏷️ {{ $product->is_sale ? 'Remove Sale Badge' : 'Add Sale Badge' }}</span>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.products.bulk-action') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                    <input type="hidden" name="action" value="{{ $product->is_new ? 'unmark_new' : 'mark_new' }}">
                                                    <button type="submit" class="w-full text-left px-3.5 py-1.5 text-[11px] text-neutral-700 hover:bg-neutral-100 transition flex items-center justify-between">
                                                        <span>✨ {{ $product->is_new ? 'Remove New Arrival' : 'Add New Arrival' }}</span>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Delete Product -->
                                            <div class="py-1">
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-red-600 hover:bg-red-50 font-bold transition flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        <span>Delete Product</span>
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-neutral-400">
                                    No products found in this section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 sm:p-6 border-t border-neutral-200">
                {{ $products->links() }}
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ✨ AI IMPORT MODAL DIALOG -->
        <!-- ========================================== -->
        <div x-show="aiModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="aiModalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/70 backdrop-blur-xs transition-opacity" @click="aiModalOpen = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="aiModalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-xs bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg border border-neutral-300">
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-neutral-900 to-[#1c1c1c] text-white px-5 py-4 flex items-center justify-between border-b border-neutral-700">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-white">✨ AI Auto-Import from URL</h3>
                        </div>
                        <button type="button" @click="aiModalOpen = false" class="text-neutral-400 hover:text-white text-base font-bold">&times;</button>
                    </div>

                    <!-- Body -->
                    <div class="p-5 space-y-4">
                        <p class="text-xs text-neutral-600">
                            Pinterest, Amazon, Daraz বা যেকোনো শপের প্রোডাক্টের লিঙ্ক পেস্ট করুন—Gemini AI হাই-কোয়ালিটি ছবি, নাম, ক্যাটাগরি, ব্র্যান্ড ও দাম নিয়ে আসবে!
                        </p>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700">Product / Pin URL *</label>
                            <div class="flex gap-2">
                                <input type="url" x-model="aiUrl" @keydown.enter.prevent="fetchAi()"
                                       placeholder="e.g. https://www.pinterest.com/pin/... or https://pin.it/..."
                                       class="flex-1 text-xs px-3 py-2.5 border border-neutral-300 rounded-xs focus:outline-none focus:border-black font-mono">
                                <button type="button" @click="fetchAi()" :disabled="aiLoading || !aiUrl"
                                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white text-xs font-bold uppercase tracking-wider rounded-xs transition flex items-center gap-1.5 shrink-0 cursor-pointer">
                                    <span x-text="aiLoading ? 'Fetching...' : '⚡ Fetch'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Loading indicator -->
                        <div x-show="aiLoading" x-cloak class="p-3 bg-indigo-50 border border-indigo-200 rounded-xs text-xs text-indigo-800 flex items-center gap-2 animate-pulse">
                            <svg class="animate-spin h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>হাই-কোয়ালিটি ছবি ডাউনলোড ও Gemini AI প্রসেসিং হচ্ছে...</span>
                        </div>

                        <!-- Error alert -->
                        <div x-show="aiError" x-cloak class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xs">
                            <span x-text="aiError"></span>
                        </div>

                        <!-- Preview Card -->
                        <div x-show="aiData" x-cloak class="p-3.5 bg-neutral-50 border border-neutral-200 rounded-xs space-y-3">
                            <div class="flex gap-3 items-center">
                                <img :src="aiData?.primary_image_url" alt="HQ Preview" class="w-20 h-20 object-cover rounded-xs border border-neutral-300 shadow-2xs shrink-0">
                                <div class="space-y-1 min-w-0">
                                    <h4 class="font-bold text-neutral-900 text-xs line-clamp-2" x-text="aiData?.title"></h4>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-bold text-neutral-900" x-text="'৳ ' + aiData?.price"></span>
                                        <span class="text-neutral-400 line-through text-[11px]" x-show="aiData?.compare_at_price" x-text="'৳ ' + aiData?.compare_at_price"></span>
                                        <span class="text-[10px] bg-neutral-200 px-1.5 py-0.5 rounded-xs" x-text="aiData?.category_name"></span>
                                    </div>
                                    <span class="inline-block text-[10px] text-emerald-700 font-bold bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-xs">
                                        ✓ High-Res Image Ready (<span x-text="aiData?.primary_image_width + 'x' + aiData?.primary_image_height + ' px'"></span>)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-neutral-100 px-5 py-3 border-t border-neutral-200 flex items-center justify-between gap-2">
                        <a href="{{ route('admin.products.create') }}" class="text-xs text-neutral-600 hover:text-black underline font-medium">
                            Full Editor
                        </a>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="aiModalOpen = false" class="px-4 py-2 text-neutral-600 hover:bg-neutral-200 text-xs font-semibold uppercase tracking-wider rounded-xs">
                                Close
                            </button>
                            <button type="button" x-show="aiData" @click="publishAi()" :disabled="aiPublishing"
                                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span x-text="aiPublishing ? 'Publishing...' : '🚀 Instant Publish'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

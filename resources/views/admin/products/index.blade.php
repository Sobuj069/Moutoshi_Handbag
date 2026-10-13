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

            <!-- Add Product Button -->
            <a href="{{ route('admin.products.create') }}" 
               class="px-5 py-2.5 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition text-center rounded-xs shrink-0 flex items-center justify-center gap-2 shadow-xs">
                <span>+ ADD NEW PRODUCT</span>
            </a>
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
                                    @if($product->is_active)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-xs uppercase">ACTIVE</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-neutral-100 text-neutral-500 text-[10px] font-bold rounded-xs uppercase">DRAFT</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right space-x-3 whitespace-nowrap">
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

    </div>

@endsection

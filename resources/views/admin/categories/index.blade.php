@extends('admin.layouts.admin')

@section('title', 'Categories Management')
@section('header_title', 'Bag Categories')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Add Category Form -->
        <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs p-6 h-fit">
            <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                CREATE NEW CATEGORY
            </h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Category Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Travel Duffle"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Image URL</label>
                    <input type="url" name="image" placeholder="https://..."
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Or Upload Image</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full text-xs px-3.5 py-2 border border-neutral-300 bg-neutral-50">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Description</label>
                    <textarea name="description" rows="2" placeholder="Brief description..."
                              class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="1"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1c1c1c] text-white py-3 text-xs font-bold uppercase tracking-wider hover:bg-black transition">
                        Add Category
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories List Table -->
        <div class="lg:col-span-2 bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden">
            <div class="p-6 border-b border-neutral-200">
                <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">
                    EXISTING CATEGORIES ({{ $categories->count() }})
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-700">
                    <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-500 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Image</th>
                            <th class="py-3 px-6">Name</th>
                            <th class="py-3 px-6">Slug</th>
                            <th class="py-3 px-6">Products</th>
                            <th class="py-3 px-6">Order</th>
                            <th class="py-3 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-neutral-50 transition">
                                <td class="py-3 px-6">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-neutral-200">
                                        <img src="{{ $category->image ?? 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=100' }}" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="py-3 px-6 font-bold text-neutral-900">{{ $category->name }}</td>
                                <td class="py-3 px-6 text-neutral-400 font-mono text-[11px]">{{ $category->slug }}</td>
                                <td class="py-3 px-6 font-semibold">{{ $category->products_count }} items</td>
                                <td class="py-3 px-6 text-neutral-500">{{ $category->sort_order }}</td>
                                <td class="py-3 px-6 text-right">
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-bold text-red-500 hover:underline uppercase text-[11px]">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

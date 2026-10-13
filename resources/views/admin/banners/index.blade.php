@extends('admin.layouts.admin')

@section('title', 'Hero Sliders')
@section('header_title', 'Hero Slideshow Banners')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Add Banner Form -->
        <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs p-6 h-fit">
            <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                ADD HERO SLIDE
            </h3>

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Badge Tag</label>
                    <input type="text" name="badge" placeholder="e.g. NEW SEASON DROP"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Slide Title *</label>
                    <input type="text" name="title" required placeholder="e.g. TIMELESS ELEGANCE"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Subtitle</label>
                    <textarea name="subtitle" rows="2" placeholder="Description under title..."
                              class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Button Text</label>
                    <input type="text" name="button_text" value="SHOP NOW"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Button Link</label>
                    <input type="text" name="button_url" value="/collections/women"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Image URL</label>
                    <input type="url" name="image_url" placeholder="https://..."
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Or Upload Slide Image</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full text-xs px-3.5 py-2 border border-neutral-300 bg-neutral-50">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="1"
                           class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1c1c1c] text-white py-3 text-xs font-bold uppercase tracking-wider hover:bg-black transition">
                        Save Slide
                    </button>
                </div>
            </form>
        </div>

        <!-- Banners List -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($banners as $banner)
                <div class="bg-white border border-neutral-200 rounded-xs shadow-2xs overflow-hidden flex flex-col sm:flex-row">
                    <div class="w-full sm:w-56 h-36 bg-neutral-900 shrink-0 relative">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            @if($banner->badge)
                                <span class="text-[10px] font-bold text-[#c9a66b] uppercase tracking-wider block mb-0.5">{{ $banner->badge }}</span>
                            @endif
                            <h4 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans">{{ $banner->title }}</h4>
                            <p class="text-xs text-neutral-500 mt-1 line-clamp-2">{{ $banner->subtitle }}</p>
                        </div>
                        <div class="mt-4 flex items-center justify-between pt-3 border-t border-neutral-100 text-xs">
                            <span class="text-neutral-400">Order: {{ $banner->sort_order }}</span>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete this banner?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 font-bold uppercase text-[11px] hover:underline">
                                    Delete Slide
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection

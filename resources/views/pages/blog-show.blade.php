@extends('layouts.app')

@section('title', $article['title'] . ' | The Patchee BD Journal')
@section('meta_description', $article['excerpt'])

@section('content')

    <!-- Breadcrumb -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 text-xs font-medium tracking-wider uppercase text-neutral-500">
        <a href="{{ route('home') }}" class="hover:text-black">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('blog.index') }}" class="hover:text-black">Journal</a>
        <span class="mx-2">/</span>
        <span class="text-neutral-900 truncate">{{ $article['title'] }}</span>
    </div>

    <article class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <!-- Meta Header -->
        <div class="text-center space-y-4 mb-8">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b]">
                {{ $article['category'] }} • {{ $article['read_time'] }}
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold uppercase tracking-wide text-neutral-900 font-sans leading-tight">
                {{ $article['title'] }}
            </h1>
            <div class="text-xs text-neutral-400 uppercase tracking-wider">
                Published on {{ $article['date'] }} by The Patchee Studio
            </div>
        </div>

        <!-- Featured Image -->
        <div class="aspect-[16/9] w-full bg-neutral-100 overflow-hidden shadow-lg mb-12">
            <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover">
        </div>

        <!-- Body Content -->
        <div class="prose prose-neutral max-w-none text-xs sm:text-sm text-neutral-700 font-light leading-relaxed space-y-6">
            {!! $article['content'] !!}
        </div>

        <!-- Share / Return -->
        <div class="mt-14 pt-6 border-t border-neutral-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <a href="{{ route('blog.index') }}" class="font-bold uppercase tracking-wider text-neutral-700 hover:text-black">
                ← Back to All Articles
            </a>
            <div class="flex items-center gap-3 text-neutral-400">
                <span class="font-bold uppercase tracking-wider text-black">Shop Bags:</span>
                <a href="{{ route('shop.index', 'all') }}" class="text-[#c9a66b] font-bold uppercase underline">
                    View New Arrivals
                </a>
            </div>
        </div>

        <!-- Related Articles -->
        @if($related->count() > 0)
            <div class="mt-16 pt-12 border-t border-neutral-200">
                <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 font-sans mb-6 text-center">
                    RELATED STORIES
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($related as $rel)
                        <div class="p-4 border border-neutral-200 flex gap-4 items-center bg-[#faf8f5]">
                            <img src="{{ $rel['image'] }}" class="w-20 h-20 object-cover shrink-0">
                            <div>
                                <span class="text-[10px] text-[#c9a66b] font-bold uppercase">{{ $rel['category'] }}</span>
                                <h4 class="text-xs font-bold uppercase text-neutral-900 line-clamp-2 mt-1">
                                    <a href="{{ route('blog.show', $rel['slug']) }}" class="hover:underline">
                                        {{ $rel['title'] }}
                                    </a>
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </article>

@endsection

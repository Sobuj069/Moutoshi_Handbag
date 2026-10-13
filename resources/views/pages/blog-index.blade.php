@extends('layouts.app')

@section('title', 'Bag Talks & Style Stories | The Patchee BD Blog')
@section('meta_description', 'Discover expert bag tips, fashion insights, and Patchee BD news. Stay updated with style trends and smart bag choices.')

@section('content')

    <!-- Header Banner -->
    <div class="bg-[#faf8f5] py-16 border-b border-neutral-200 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">
                EDITORIAL JOURNAL
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                BAG TALKS & STYLE STORIES
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-neutral-500 max-w-lg mx-auto font-light leading-relaxed">
                Expert bag care advice, runway trend forecasts, and styling guides from our creative fashion studio in Dhaka.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10">
            @foreach($articles as $article)
                <article class="bg-white border border-neutral-200 group flex flex-col justify-between overflow-hidden shadow-2xs hover:shadow-md transition duration-300">
                    <div>
                        <a href="{{ route('blog.show', $article['slug']) }}" class="block aspect-[16/10] overflow-hidden bg-neutral-100">
                            <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </a>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-[10px] font-bold tracking-wider uppercase text-neutral-400">
                                <span class="text-[#c9a66b]">{{ $article['category'] }}</span>
                                <span>{{ $article['read_time'] }}</span>
                            </div>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-900 group-hover:text-[#c9a66b] transition line-clamp-2 font-sans">
                                <a href="{{ route('blog.show', $article['slug']) }}">
                                    {{ $article['title'] }}
                                </a>
                            </h2>
                            <p class="text-xs text-neutral-500 font-light line-clamp-3 leading-relaxed">
                                {{ $article['excerpt'] }}
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2 border-t border-neutral-100 flex items-center justify-between text-[11px] font-semibold uppercase text-neutral-400">
                        <span>{{ $article['date'] }}</span>
                        <a href="{{ route('blog.show', $article['slug']) }}" class="text-black font-bold group-hover:text-[#c9a66b] transition flex items-center gap-1">
                            READ ARTICLE →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

@endsection

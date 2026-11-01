@extends('layouts.app')

@section('title', $featuredGame->title . ' – Günün Oyunu')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-12">
    <!-- HERO: Featured Game of the Day -->
    <section class="relative mb-12 rounded-xl overflow-hidden glass">
        <div class="relative h-80 bg-cover bg-center" style="background-image: url('{{ $featuredGame->backdrop_url ?? $featuredGame->cover_url }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-4 left-4 flex flex-col max-w-lg">
                <h1 class="font-display text-4xl md:text-5xl text-white drop-shadow-lg">{{ $featuredGame->title }}</h1>
                <p class="mt-2 text-sm text-gray-200">{{ Str::limit($featuredGame->description, 150) }}</p>
                <a href="{{ route('games.show', $featuredGame) }}" class="mt-4 inline-block rounded bg-emerald-500/80 px-5 py-2 font-medium text-white hover:bg-emerald-600 transition">Oyunu Keşfet</a>
            </div>
        </div>
    </section>

    <!-- SEARCH BAR -->
    <section class="mb-8">
        <form action="{{ route('home') }}" method="GET" class="relative max-w-xl mx-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Oyun, stüdyo, AI vb. ara..." class="w-full rounded-full border border-gray-600 bg-gray-900/50 px-5 py-3 pl-10 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z"></path></svg>
        </form>
    </section>

    <!-- FEATURED VIDEO CAROUSEL -->
    <section class="mb-12">
        <h2 class="font-display text-2xl text-white mb-4">Öne Çıkan Video Analizleri</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($featuredVideos as $video)
                <div class="relative rounded-lg overflow-hidden glass">
                    <img src="{{ $video->thumbnail_url ?? 'https://via.placeholder.com/640x360' }}" alt="{{ $video->title }}" class="w-full h-48 object-cover" />
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition">
                        <button type="button" onclick="openVideoModal('{{ $video->video_url }}', '{{ addslashes($video->title) }}', 'Video Analizi')" class="rounded bg-emerald-500/80 px-4 py-2 text-sm text-white">▶ İzle</button>
                    </div>
                    <div class="p-2">
                        <h3 class="font-display text-sm text-white truncate">{{ $video->title }}</h3>
                        <p class="text-xs text-gray-400">{{ $video->game->title ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- TOP GAMES GRID -->
    <section class="mb-12">
        <h2 class="font-display text-2xl text-white mb-4">Öne Çıkan Oyunlar</h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6">
            @foreach($games->take(12) as $g)
                <a href="{{ route('games.show', $g) }}" class="block glass p-2 hover:scale-105 transform transition">
                    <img src="{{ $g->cover_url ?? 'https://via.placeholder.com/200x300' }}" alt="{{ $g->title }}" class="w-full h-48 object-cover rounded" />
                    <h3 class="mt-2 text-sm font-medium text-white truncate">{{ $g->title }}</h3>
                    <p class="text-xs text-gray-400">{{ $g->release_year }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <!-- AI LAB QUICK CARD -->
    <section class="mb-12">
        <a href="{{ route('ai.index') }}" class="block max-w-xl mx-auto glass p-6 text-center hover:shadow-xl transition">
            <h3 class="font-display text-xl text-emerald-400">AI Laboratuvarı</h3>
            <p class="mt-2 text-gray-200">Oyun teknolojileri ve yapay zekâ arasındaki eşsiz ilişkiyi keşfedin.</p>
        </a>
    </section>

    <!-- RECENT REVIEWS (optional) -->
    @if(isset($reviews) && $reviews->count())
    <section class="mb-12">
        <h2 class="font-display text-2xl text-white mb-4">Son İncelemeler</h2>
        <div class="space-y-6">
            @foreach($reviews->take(5) as $review)
                <div class="p-4 bg-[#0B0F17] rounded glass">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-lg text-white">{{ $review->game->title }}</h3>
                        <span class="font-mono-tech text-sm text-emerald-300">{{ $review->rating }}/10</span>
                    </div>
                    <p class="mt-2 text-gray-200 text-sm line-clamp-3">{{ Str::limit($review->content, 180) }}</p>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection

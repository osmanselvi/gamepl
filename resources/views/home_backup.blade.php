@extends('layouts.app')

@section('title', $featuredGame->title . ' – Günün Oyunu')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-12">
    <!-- Featured Game of the Day -->
    <section class="relative mb-12 rounded-xl overflow-hidden glass">
        <div class="relative h-80 bg-cover bg-center" style="background-image: url('{{ $featuredGame->backdrop_url ?? $featuredGame->cover_url }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-4 left-4 flex flex-col max-w-lg">
                <h1 class="font-display text-4xl md:text-5xl text-white drop-shadow-lg">{{ $featuredGame->title }}</h1>
                <p class="mt-2 text-sm text-gray-200">
                    {{ Str::limit($featuredGame->description, 150) }}
                </p>
                <a href="{{ route('games.show', $featuredGame) }}" class="mt-4 inline-block rounded bg-emerald-500/80 px-5 py-2 font-medium text-white hover:bg-emerald-600 transition">
                    Oyunu Keşfet
                </a>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="mb-8">
        <form action="{{ route('home') }}" method="GET" class="relative max-w-xl mx-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Oyun, stüdyo, AI vb. ara..."
                   class="w-full rounded-full border border-gray-600 bg-gray-900/50 px-5 py-3 pl-10 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z"></path></svg>
        </form>
    </section>

    <!-- Top Games Carousel -->
    <section class="mb-12">
        <h2 class="mb-4 font-display text-2xl text-white">Öne Çıkan Oyunlar</h2>
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

    <!-- AI Lab Quick Card -->
    <section class="mb-12">
        <a href="{{ route('ai.index') }}" class="block max-w-xl mx-auto glass p-6 text-center hover:shadow-xl transition">
            <h3 class="font-display text-xl text-emerald-400">AI Laboratuvarı</h3>
            <p class="mt-2 text-gray-200">Oyun teknolojileri ve yapay zekâ arasındaki eşsiz ilişkiyi keşfedin.</p>
        </a>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('title', $collection->title . ' — Küratör Seçkisi | GamePL')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <header class="rounded-3xl border border-emerald-500/30 bg-[#0B0F17] p-8">
        <span class="font-mono-tech text-xs text-emerald-400">// CURATED GAME & AI COLLECTION</span>
        <h1 class="mt-2 font-display text-3xl font-bold text-white">{{ $collection->title }}</h1>
        <p class="mt-2 text-sm text-slate-300">{{ $collection->description }}</p>
        <p class="mt-2 font-mono-tech text-xs text-slate-400">Küratör: {{ $collection->curator_name }} • {{ $collection->games->count() }} Oyun</p>
    </header>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($collection->games as $game)
            <a href="{{ route('games.show', $game->slug) }}" class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 hover:border-emerald-500/50 transition">
                <div class="flex items-center justify-between font-mono-tech text-xs text-slate-400">
                    <span>{{ $game->release_year }} • {{ $game->developer_studio }}</span>
                    <span class="text-amber-300">★ {{ number_format($game->weightedRating(), 1) }}</span>
                </div>
                <h3 class="mt-2 font-display text-lg font-bold text-white">{{ $game->title }}</h3>
                <p class="mt-2 line-clamp-2 text-xs text-slate-300">{{ $game->synopsis }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $engine->name . ' — Oyun Motoru Mimarisi ve Oyunlar | GamePL')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-10">
    <section class="rounded-3xl border border-amber-500/35 bg-[#0B0F17] p-8 space-y-4">
        <span class="font-mono-tech text-xs text-amber-400">// GAME ENGINE ARCHITECTURE</span>
        <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ $engine->name }}</h1>
        <p class="font-mono-tech text-xs text-slate-400">
            Geliştirici: {{ $engine->developer_company }} • Çıkış: {{ $engine->release_year }} • Lisans: {{ $engine->license_model }}
        </p>
        <p class="text-sm leading-relaxed text-slate-200">{{ $engine->description }}</p>
        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-950/20 p-4 text-xs text-slate-200">
            <strong class="font-mono-tech text-emerald-400 block mb-1">Yapay Zekâ ve Simülasyon Yetenekleri:</strong>
            {{ $engine->ai_capabilities }}
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="font-display text-2xl font-bold text-white">Bu Motorla Geliştirilen Oyunlar ({{ $engine->games->count() }})</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($engine->games as $game)
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
    </section>
</div>
@endsection

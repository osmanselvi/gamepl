@extends('layouts.app')

@section('title', $person->name . ' — ' . $person->roleLabel() . ' | GamePL')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-10">
    <section class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-full border border-emerald-500/40 bg-emerald-500/15 px-3 py-1 font-mono-tech text-xs font-semibold text-emerald-300">
                {{ $person->roleLabel() }}
            </span>
            @if($person->studio_affiliation)
                <span class="rounded-full border border-cyan-500/40 bg-cyan-500/10 px-3 py-1 font-mono-tech text-xs text-cyan-300">
                    Stüdyo / Takım: {{ $person->studio_affiliation }}
                </span>
            @endif
            @if($person->nationality)
                <span class="font-mono-tech text-xs text-slate-400">Ülke: {{ $person->nationality }}</span>
            @endif
        </div>

        <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ $person->name }}</h1>
        <p class="text-sm leading-relaxed text-slate-200">{{ $person->bio }}</p>

        @if($person->signature_style)
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-950/20 p-4 text-xs text-slate-200">
                <strong class="font-mono-tech text-emerald-400 block mb-1">İmza Tasarım & Teknik Üslup:</strong>
                {{ $person->signature_style }}
            </div>
        @endif

        @if(!empty($person->notable_achievements))
            <div class="flex flex-wrap gap-2 pt-2">
                @foreach($person->notable_achievements as $ach)
                    <span class="rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-1 font-mono-tech text-xs text-amber-300">
                        🏆 {{ $ach }}
                    </span>
                @endforeach
            </div>
        @endif
    </section>

    <section class="space-y-4">
        <h2 class="font-display text-2xl font-bold text-white">Yer Aldığı Oyunlar ({{ $person->games->count() }})</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($person->games as $game)
                <a href="{{ route('games.show', $game->slug) }}" class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 hover:border-emerald-500/50 transition">
                    <div class="flex items-center justify-between font-mono-tech text-xs text-slate-400">
                        <span>{{ $game->release_year }} • {{ $game->developer_studio }}</span>
                        <span class="text-amber-300">★ {{ number_format($game->weightedRating(), 1) }}</span>
                    </div>
                    <h3 class="mt-2 font-display text-lg font-bold text-white">{{ $game->title }}</h3>
                    <p class="mt-1 font-mono-tech text-xs text-emerald-400">Rol: {{ $game->pivot->character_name ?: $game->pivot->credit_type }}</p>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('title', $member->name . ' — Oyuncu & Geliştirici Profili | GamePL')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <header class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <span class="rounded-full border border-emerald-500/40 bg-emerald-500/15 px-3 py-1 font-mono-tech text-xs text-emerald-300">
                {{ $member->roleLabel() }}
            </span>
            <h1 class="mt-3 font-display text-3xl font-bold text-white">{{ $member->name }}</h1>
            <p class="mt-1 text-xs text-slate-400">{{ $member->bio }}</p>
        </div>
        @auth
            @if(auth()->id() !== $member->id)
                <form action="{{ route('users.follow', $member->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-xl border border-emerald-500/40 bg-emerald-500/15 px-4 py-2 text-xs font-semibold text-emerald-300">
                        {{ auth()->user()->following->contains($member->id) ? 'Takibi Bırak' : '+ Takip Et' }}
                    </button>
                </form>
            @endif
        @endauth
    </header>

    @if($member->favoriteGames->isNotEmpty())
    <section class="space-y-4">
        <h2 class="font-display text-xl font-bold text-white">🌟 Favori 4 Oyunu</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($member->favoriteGames as $fav)
                <a href="{{ route('games.show', $fav->slug) }}" class="rounded-2xl border border-amber-500/30 bg-[#0B0F17] p-4">
                    <span class="font-mono-tech text-[10px] text-amber-300">#{{ $fav->pivot->rank }}</span>
                    <h3 class="mt-1 font-display text-base font-bold text-white">{{ $fav->title }}</h3>
                </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection

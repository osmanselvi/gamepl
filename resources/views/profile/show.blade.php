@extends('layouts.app')

@section('title', 'Oyuncu Belleği & Favori 4 Oyun Vitrini | GamePL')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-10">
    <header class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8">
        <span class="font-mono-tech text-xs text-emerald-400">// PLAYER MEMORY & FAVORITE 4 GAMES</span>
        <h1 class="mt-2 font-display text-3xl font-bold text-white">{{ $user->name }} — Oyuncu Belleği</h1>
        <p class="mt-1 text-xs text-slate-400">{{ $user->roleLabel() }} • Toplam Kayıtlı Oyun Günlüğü: {{ $user->gameLogs->count() }}</p>
    </header>

    <!-- Favori 4 Oyun Vitrini -->
    <section class="space-y-4">
        <h2 class="font-display text-xl font-bold text-white">🌟 Favori 4 Oyun Vitrinim</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($user->favoriteGames as $fav)
                <a href="{{ route('games.show', $fav->slug) }}" class="rounded-2xl border border-amber-500/30 bg-[#0B0F17] p-4 hover:border-amber-400 transition">
                    <span class="font-mono-tech text-[10px] text-amber-300">SIRA #{{ $fav->pivot->rank }}</span>
                    <h3 class="mt-1 font-display text-base font-bold text-white">{{ $fav->title }}</h3>
                    <p class="text-xs text-slate-400">{{ $fav->release_year }} • {{ $fav->developer_studio }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Oynama Günlüğü -->
    <section class="space-y-4">
        <h2 class="font-display text-xl font-bold text-white">🕹️ Oynama Günlüğüm (Playing / Completed / Mastered / Backlog)</h2>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($user->gameLogs as $log)
                <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 flex items-center justify-between">
                    <div>
                        <span class="rounded bg-emerald-500/15 px-2 py-0.5 font-mono-tech text-[10px] text-emerald-300">{{ $log->statusLabel() }}</span>
                        <a href="{{ route('games.show', $log->game->slug) }}" class="mt-1.5 block font-display text-lg font-bold text-white hover:text-emerald-400">
                            {{ $log->game->title }}
                        </a>
                        <p class="text-xs text-slate-400">{{ $log->platform }} • {{ $log->hours_played }} saat oynandı</p>
                    </div>
                    @if($log->personal_rating)
                        <span class="font-mono-tech text-sm font-bold text-amber-300">★ {{ $log->personal_rating }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

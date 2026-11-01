@extends('layouts.app')

@section('title', $analysis->title . ' — Oyun & AI Vaka Analizi | GamePL')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <article class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <a href="{{ route('games.show', $analysis->game->slug) }}" class="font-mono-tech text-xs font-semibold text-cyan-400 hover:underline">
                ← {{ $analysis->game->title }} ({{ $analysis->game->release_year }})
            </a>
            <div class="flex gap-2 font-mono-tech text-xs">
                <span class="rounded bg-emerald-500/15 px-3 py-1 text-emerald-300">İnovasyon: {{ $analysis->innovation_score }}/100</span>
                <span class="rounded bg-violet-500/15 px-3 py-1 text-violet-300">Karmaşıklık: {{ $analysis->complexity_score }}/100</span>
            </div>
        </div>

        <h1 class="font-display text-3xl font-bold text-white">{{ $analysis->title }}</h1>
        <p class="font-mono-tech text-xs text-amber-300">Oyun İçi AI Rolü: {{ $analysis->ai_role_in_game }} • Mimari: {{ $analysis->aiSystem->name }}</p>

        <div class="space-y-4 text-sm leading-relaxed text-slate-200">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                <h3 class="font-mono-tech text-xs font-semibold uppercase text-emerald-400 mb-2">1. Mimari Çözümleme (Architecture Breakdown)</h3>
                <p>{{ $analysis->architecture_breakdown }}</p>
            </div>
            @if($analysis->perception_and_sensors)
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                <h3 class="font-mono-tech text-xs font-semibold uppercase text-cyan-400 mb-2">2. Algılayıcılar ve Duyusal Girdiler (Sensors & Perception)</h3>
                <p>{{ $analysis->perception_and_sensors }}</p>
            </div>
            @endif
            @if($analysis->decision_loop_details)
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                <h3 class="font-mono-tech text-xs font-semibold uppercase text-violet-400 mb-2">3. Karar Döngüsü ve Öğrenme Davranışı</h3>
                <p>{{ $analysis->decision_loop_details }}</p>
            </div>
            @endif
            @if($analysis->player_experience_impact)
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                <h3 class="font-mono-tech text-xs font-semibold uppercase text-amber-400 mb-2">4. Oyuncu Deneyimine Etkisi ve Optimizasyon Hileleri</h3>
                <p>{{ $analysis->player_experience_impact }} {{ $analysis->limitations_and_tricks }}</p>
            </div>
            @endif
        </div>

        @if($analysis->embedVideoUrl())
        <div class="pt-2">
            <button type="button"
                onclick="openVideoModal('{{ $analysis->embedVideoUrl() }}', '{{ addslashes($analysis->title) }}', 'VAKA KANIT VİDEOSU')"
                class="rounded-xl bg-cyan-500 px-5 py-2.5 text-xs font-bold text-slate-950 hover:bg-cyan-400">
                ▶ Örnek Kanıt Videosunu Sinema Modunda Aç
            </button>
        </div>
        @endif
    </article>
</div>
@endsection

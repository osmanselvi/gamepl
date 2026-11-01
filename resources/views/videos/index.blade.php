@extends('layouts.app')

@section('title', 'Örnek Oyun & Yapay Zekâ Analiz Videoları Kütüphanesi | GamePL')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col justify-between gap-4 border-b border-slate-800 pb-6 sm:flex-row sm:items-end">
        <div>
            <span class="font-mono-tech text-xs uppercase tracking-widest text-cyan-400">// SAMPLE GAMEPLAY & AI VIDEO ARCHIVE</span>
            <h1 class="mt-1 font-display text-3xl font-bold text-white">Örnek Oyun ve Yapay Zekâ Videoları</h1>
            <p class="mt-1 text-sm text-slate-400">Oynanış kesitlerini, düşman yapay zekâsı analizlerini ve oyun motoru karşılaştırmalarını filtreleyin.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @php
                $types = [
                    '' => 'Tümü',
                    'gameplay_sample' => 'Oynanış Kesiti',
                    'ai_showcase' => 'Yapay Zekâ Analizi',
                    'tech_breakdown' => 'Motor & Grafik İncelemesi',
                ];
            @endphp
            @foreach($types as $key => $label)
                <a href="{{ route('videos.index', $key ? ['type' => $key] : []) }}"
                    class="rounded-xl border px-3.5 py-1.5 font-mono-tech text-xs transition {{ $type === $key ? 'border-cyan-500 bg-cyan-500/20 text-cyan-300' : 'border-slate-800 bg-slate-900 text-slate-300 hover:border-slate-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($videos as $video)
            <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="relative aspect-video bg-black">
                        <img src="{{ $video->thumbnail_url ?: $video->game->backdrop_url }}" alt="{{ $video->title }}" class="h-full w-full object-cover opacity-80">
                        <div class="absolute top-3 left-3 rounded bg-slate-950/90 px-2.5 py-0.5 font-mono-tech text-[10px] text-cyan-300">
                            {{ $video->typeLabel() }}
                        </div>
                        <div class="absolute bottom-3 right-3 rounded bg-black/80 px-2 py-0.5 font-mono-tech text-[11px] text-white">
                            {{ $video->duration_label }} • {{ $video->resolution_tag }}
                        </div>
                        <button type="button"
                            onclick="openVideoModal('{{ $video->embedVideoUrl() }}', '{{ addslashes($video->title) }}', '{{ addslashes($video->typeLabel()) }}')"
                            class="absolute inset-0 flex items-center justify-center">
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-slate-950 font-bold shadow-xl">▶</span>
                        </button>
                    </div>
                    <div class="p-5">
                        <a href="{{ route('games.show', $video->game->slug) }}" class="font-mono-tech text-xs text-emerald-400 hover:underline">
                            {{ $video->game->title }}
                        </a>
                        <h3 class="mt-1 font-display text-base font-bold text-white">{{ $video->title }}</h3>
                        <p class="mt-2 text-xs text-slate-300">{{ $video->description }}</p>
                    </div>
                </div>
                <div class="border-t border-slate-800 px-5 py-3 flex items-center justify-between">
                    <button type="button"
                        onclick="openVideoModal('{{ $video->embedVideoUrl() }}', '{{ addslashes($video->title) }}', '{{ addslashes($video->typeLabel()) }}')"
                        class="text-xs font-semibold text-cyan-400">
                        ▶ Sinema Modunda Oynat
                    </button>
                    <a href="{{ route('games.show', $video->game->slug) }}" class="text-xs text-slate-400 hover:text-white">Oyun →</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Oyun Teknolojileri ve Yapay Zekâ İlişkisi Laboratuvarı (/ai-lab) | GamePL')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-12">

    <header class="rounded-3xl border border-emerald-500/35 bg-gradient-to-br from-[#0A161E] via-[#090E18] to-[#070A10] p-8">
        <span class="font-mono-tech text-xs font-semibold uppercase tracking-widest text-emerald-400">
            // GAMEPL ARTIFICIAL INTELLIGENCE & ENGINE ARCHITECTURE LAB
        </span>
        <h1 class="mt-2 font-display text-3xl font-bold text-white sm:text-5xl">
            Oyun Teknolojileri ve Yapay Zekâ İlişkisi
        </h1>
        <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-300 sm:text-base">
            Oyun dünyasında yapay zekâ iki büyük kol üzerinden ilerler: <strong class="text-emerald-300">1) Bilişsel Karar ve Planlama Mimarileri</strong> (GOAP, Behavior Trees, Tactical Utility AI, Nemesis Sosyal Hafıza, AI Director) ve <strong class="text-cyan-300">2) Nöral Grafik, Fizik ve Üretken Sistemler</strong> (DLSS 3.5 Ray Reconstruction, Euphoria CNS, JALI Facial AI).
        </p>
    </header>

    <!-- 1. Yapay Zekâ Paradigmaları ve Algoritmik Pseudocode Kartları -->
    <section class="space-y-6">
        <h2 class="font-display text-2xl font-bold text-white">🧠 Temel Oyun Yapay Zekâsı Mimarileri ({{ $aiSystems->count() }})</h2>
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach($aiSystems as $sys)
                <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-4 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="rounded-md border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-0.5 font-mono-tech text-[11px] text-emerald-300">
                                {{ $sys->paradigmLabel() }}
                            </span>
                            <span class="font-mono-tech text-xs text-slate-400">{{ $sys->first_year }} • {{ $sys->complexity_level }}</span>
                        </div>
                        <a href="{{ route('ai.systems.show', $sys->slug) }}" class="block font-display text-xl font-bold text-white hover:text-emerald-400 transition">
                            {{ $sys->name }}
                        </a>
                        <p class="font-mono-tech text-xs text-cyan-400">
                            Yaratıcı / Köken: {{ $sys->invented_by_or_origin }} • İlk Başyapıt: {{ $sys->first_notable_game }}
                        </p>
                        <p class="text-xs leading-relaxed text-slate-300">{{ $sys->concept_summary }}</p>
                        <div class="rounded-xl border border-slate-800 bg-slate-950 p-3.5 font-mono-tech text-[11px] text-emerald-300 whitespace-pre-wrap overflow-x-auto">{{ $sys->pseudocode_or_diagram }}</div>
                    </div>
                    <div class="border-t border-slate-800 pt-3 flex items-center justify-between">
                        <span class="text-xs text-slate-400">{{ $sys->analyses->count() }} Oyun Vaka Analizi</span>
                        <a href="{{ route('ai.systems.show', $sys->slug) }}" class="text-xs font-semibold text-emerald-400 hover:underline">
                            Detaylı Çalışma Prensibi →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 2. Oyun & AI Vaka Analizleri (Case Studies) -->
    <section class="space-y-6">
        <h2 class="font-display text-2xl font-bold text-white">🔬 Oyun İçi Yapay Zekâ Vaka Analizleri ({{ $analyses->count() }})</h2>
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($analyses as $analysis)
                <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-3 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between font-mono-tech text-xs">
                            <a href="{{ route('games.show', $analysis->game->slug) }}" class="text-cyan-400 hover:underline">
                                {{ $analysis->game->title }} ({{ $analysis->game->release_year }})
                            </a>
                            <span class="text-emerald-400">İnovasyon: {{ $analysis->innovation_score }}/100</span>
                        </div>
                        <a href="{{ route('ai.analyses.show', $analysis->slug) }}" class="mt-2 block font-display text-lg font-bold text-white hover:text-emerald-400">
                            {{ $analysis->title }}
                        </a>
                        <p class="mt-2 text-xs leading-relaxed text-slate-300">{{ $analysis->architecture_breakdown }}</p>
                    </div>
                    <div class="border-t border-slate-800 pt-3 flex items-center justify-between">
                        <a href="{{ route('ai.analyses.show', $analysis->slug) }}" class="text-xs font-semibold text-emerald-400 hover:underline">
                            Tam Vaka Raporu →
                        </a>
                        @if($analysis->embedVideoUrl())
                            <button type="button"
                                onclick="openVideoModal('{{ $analysis->embedVideoUrl() }}', '{{ addslashes($analysis->title) }}', 'AI ANALİZ VİDEOSU')"
                                class="rounded-lg border border-cyan-500/40 bg-cyan-500/10 px-3 py-1 font-mono-tech text-[11px] text-cyan-300">
                                ▶ Kanıt Videosu
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 3. Oyun Teknolojileri & Nöral Grafik Sistemleri -->
    <section class="space-y-6">
        <h2 class="font-display text-2xl font-bold text-white">⚡ Oyun Teknolojileri & Nöral Grafik Boru Hatları</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($technologies as $tech)
                <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 space-y-2.5">
                    <span class="font-mono-tech text-[10px] uppercase text-amber-400">{{ $tech->categoryLabel() }} • {{ $tech->introduced_year }}</span>
                    <h3 class="font-display text-base font-bold text-white">{{ $tech->name }}</h3>
                    <p class="text-xs text-slate-300">{{ $tech->summary }}</p>
                    @if($tech->ai_integration_notes)
                        <div class="rounded-lg border border-emerald-500/20 bg-emerald-950/20 p-2.5 text-[11px] text-emerald-200">
                            <strong class="font-mono-tech text-emerald-400">AI Etkisi:</strong> {{ $tech->ai_integration_notes }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

</div>
@endsection

@extends('layouts.app')

@section('title', $aiSystem->name . ' — Oyun Yapay Zekâsı Mimarisi | GamePL')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <div class="rounded-3xl border border-emerald-500/35 bg-[#0B0F17] p-8 space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <span class="rounded-full border border-emerald-500/40 bg-emerald-500/15 px-3 py-1 font-mono-tech text-xs text-emerald-300">
                {{ $aiSystem->paradigmLabel() }}
            </span>
            <span class="font-mono-tech text-xs text-slate-400">İlk Çıkış: {{ $aiSystem->first_year }} • {{ $aiSystem->complexity_level }}</span>
        </div>
        <h1 class="font-display text-3xl font-bold text-white">{{ $aiSystem->name }}</h1>
        <p class="font-mono-tech text-xs text-cyan-400">Geliştiren: {{ $aiSystem->invented_by_or_origin }} • Öncü Oyun: {{ $aiSystem->first_notable_game }}</p>
        <p class="text-sm leading-relaxed text-slate-200">{{ $aiSystem->concept_summary }}</p>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5 space-y-2">
            <h3 class="font-mono-tech text-xs font-semibold uppercase text-emerald-400">Nasıl Çalışır? (Algoritmik Akış)</h3>
            <p class="whitespace-pre-line text-xs leading-relaxed text-slate-200">{{ $aiSystem->how_it_works }}</p>
        </div>

        @if($aiSystem->pseudocode_or_diagram)
        <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
            <h3 class="font-mono-tech text-xs font-semibold uppercase text-cyan-400 mb-2">Pseudocode & Karar Grafiği</h3>
            <pre class="font-mono-tech text-xs text-emerald-300 overflow-x-auto">{{ $aiSystem->pseudocode_or_diagram }}</pre>
        </div>
        @endif

        @if($aiSystem->pros_and_cons)
        <div class="rounded-2xl border border-amber-500/30 bg-amber-950/15 p-4 text-xs text-slate-200">
            <strong class="font-mono-tech text-amber-400 block mb-1">Mühendislik Avantajları ve Sınırları:</strong>
            {{ $aiSystem->pros_and_cons }}
        </div>
        @endif
    </div>
</div>
@endsection

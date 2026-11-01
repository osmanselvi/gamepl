@extends('layouts.app')

@section('title', $featuredGame->title . ' – Günün Oyunu')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-12">
    <!-- HERO: Featured Game of the Day -->
    <section class="relative mb-12 rounded-xl overflow-hidden glass">
        <div class="relative h-80 bg-cover bg-center"
            style="background-image: url('{{ $featuredGame->backdrop_url ?? $featuredGame->cover_url }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-4 left-4 flex flex-col max-w-lg">
                <h1 class="font-display text-4xl md:text-5xl text-white drop-shadow-lg">{{ $featuredGame->title }}</h1>
                <p class="mt-2 text-sm text-gray-200">{{ Str::limit($featuredGame->description, 150) }}</p>
                <a href="{{ route('games.show', $featuredGame) }}" class="mt-4 inline-block rounded bg-emerald-500/80 px-5 py-2 font-medium text-white hover:bg-emerald-600 transition">Oyunu Keşfet</a>
            </div>
        </div>
    </section>

    <!-- SEARCH BAR -->
    <section class="mb-8">
        <form action="{{ route('home') }}" method="GET" class="relative max-w-xl mx-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Oyun, stüdyo, AI vb. ara..." class="w-full rounded-full border border-gray-600 bg-gray-900/50 px-5 py-3 pl-10 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z"></path></svg>
        </form>
    </section>

    <!-- DISCOVERY FILTERS -->
    <section class="mb-12">
        <h2 class="font-display text-2xl text-white mb-4">Keşif Motoru</h2>
        <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <select name="engine" class="rounded bg-gray-800 text-gray-200 border border-gray-600"><option value="">Motor Seç</option>@foreach($engines as $engine)<option value="{{ $engine->slug }}" {{ request('engine') == $engine->slug ? 'selected' : '' }}>{{ $engine->name }}</option>@endforeach</select>
            <select name="ai" class="rounded bg-gray-800 text-gray-200 border border-gray-600"><option value="">AI Tekniği Seç</option>@foreach($aiSystems as $sys)<option value="{{ $sys->slug }}" {{ request('ai') == $sys->slug ? 'selected' : '' }}>{{ $sys->name }}</option>@endforeach</select>
            <select name="tech" class="rounded bg-gray-800 text-gray-200 border border-gray-600"><option value="">Teknoloji Seç</option>@foreach($technologies as $tech)<option value="{{ $tech->slug }}" {{ request('tech') == $tech->slug ? 'selected' : '' }}>{{ $tech->name }}</option>@endforeach</select>
            <select name="decade" class="rounded bg-gray-800 text-gray-200 border border-gray-600"><option value="">Dönem Seç</option><option value="2020s" {{ request('decade') == '2020s' ? 'selected' : '' }}>2020'lar</option><option value="2010s" {{ request('decade') == '2010s' ? 'selected' : '' }}>2010'lar</option><option value="2000s" {{ request('decade') == '2000s' ? 'selected' : '' }}>2000'lar</option></select>
            <select name="sort" class="rounded bg-gray-800 text-gray-200 border border-gray-600"><option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Puan (Yüksek → Düşük)</option><option value="rating_asc" {{ request('sort') == 'rating_asc' ? 'selected' : '' }}>Puan (Düşük → Yüksek)</option><option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Yıl (Yeni → Eski)</option><option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Yıl (Eski → Yeni)</option></select>
            <button type="submit" class="col-span-1 md:col-span-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2 px-4 rounded transition">Filtrele</button>
        </form>
    </section>

    <!-- AI LAB QUICK CARD -->
    <section class="mb-12"><a href="{{ route('ai.index') }}" class="block max-w-xl mx-auto glass p-6 text-center hover:shadow-xl transition"><h3 class="font-display text-xl text-emerald-400">AI Laboratuvarı</h3><p class="mt-2 text-gray-200">Oyun teknolojileri ve yapay zekâ arasındaki eşsiz ilişkiyi keşfedin.</p></a></section>

    <!-- AI SYSTEM CARD SECTION -->
    <section class="mb-12">
        <h2 class="font-display text-2xl text-white mb-4">Oyun Teknolojileri & AI Sistemleri</h2>
        <div class="grid gap-6 lg:grid-cols-2">@foreach($aiSystems->take(4) as $sys)<div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 flex flex-col justify-between"><div><span class="rounded-md border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-0.5 font-mono-tech text-[11px] text-emerald-300">{{ $sys->paradigmLabel() }}</span><h3 class="mt-2 font-display text-xl text-white"><a href="{{ route('ai.systems.show', $sys->slug) }}" class="hover:text-emerald-400 transition">{{ $sys->name }}</a></h3><p class="mt-2 text-sm text-slate-300">{{ Str::limit($sys->summary, 120) }}</p></div><div class="mt-4 text-xs text-slate-400">{{ $sys->first_year }} • {{ $sys->complexity_level }}</div></div>@endforeach</div>
    </section>

    <!-- FEATURED VIDEO CAROUSEL -->
    <section class="mb-12"><h2 class="font-display text-2xl text-white mb-4">Öne Çıkan Video Analizleri</h2><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">@foreach($featuredVideos as $video)<div class="relative rounded-lg overflow-hidden glass"><img src="{{ $video->thumbnail_url ?? 'https://via.placeholder.com/640x360' }}" alt="{{ $video->title }}" class="w-full h-48 object-cover" /><div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition"><button type="button" onclick="openVideoModal('{{ $video->video_url }}', '{{ addslashes($video->title) }}', 'Video Analizi')" class="rounded bg-emerald-500/80 px-4 py-2 text-sm text-white">▶ İzle</button></div><div class="p-2"><h3 class="font-display text-sm text-white truncate">{{ $video->title }}</h3><p class="text-xs text-gray-400">{{ $video->game->title ?? '' }}</p></div></div>@endforeach</div></section>

    <!-- TOP GAMES GRID -->
    <section class="mb-12"><h2 class="font-display text-2xl text-white mb-4">Öne Çıkan Oyunlar</h2><div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6">@foreach($games->take(12) as $g)<a href="{{ route('games.show', $g) }}" class="block glass p-2 hover:scale-105 transform transition"><img src="{{ $g->poster_url ?: 'https://via.placeholder.com/200x300' }}" alt="{{ $g->title }}" class="w-full h-48 object-cover rounded" /><h3 class="mt-2 text-sm font-medium text-white truncate">{{ $g->title }}</h3><p class="text-xs text-gray-400">{{ $g->release_year }}</p></a>@endforeach</div></section>

    <!-- FEATURED AI ANALYSES -->
    @if($featuredAiAnalyses->count())
    <section class="mb-12"><h2 class="font-display text-2xl text-white mb-4">Öne Çıkan AI Analizleri</h2><div class="grid gap-6 lg:grid-cols-2">@foreach($featuredAiAnalyses as $analysis)<div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6"><h3 class="font-display text-xl text-white"><a href="{{ route('ai.analyses.show', $analysis->slug) }}" class="hover:text-emerald-400 transition">{{ $analysis->title }}</a></h3><p class="mt-2 text-sm text-slate-300">{{ Str::limit($analysis->concept_summary, 120) }}</p><div class="mt-4 text-xs text-slate-400">{{ $analysis->innovation_score }}/100 Puan – {{ $analysis->game->title ?? '' }}</div></div>@endforeach</div></section>
    @endif

    <!-- FEATURED HARDWARE -->
    @if($featuredHardware->count())
    <section class="mb-12"><h2 class="font-display text-2xl text-white mb-4">Öne Çıkan Donanım</h2><div class="grid gap-6 lg:grid-cols-2">@foreach($featuredHardware as $hw)<div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 flex flex-col"><h3 class="font-display text-xl text-white">{{ $hw->name }}</h3><p class="mt-2 text-sm text-slate-300">{{ $hw->short_intro }}</p><div class="mt-4 text-xs text-slate-400">Rating: {{ $hw->rating }}/10</div></div>@endforeach</div></section>
    @endif

    <!-- RECENT REVIEWS -->
    @if(isset($reviews) && $reviews->count())
    <section class="mb-12"><h2 class="font-display text-2xl text-white mb-4">Son İncelemeler</h2><div class="space-y-6">@foreach($reviews->take(5) as $review)<div class="p-4 bg-[#0B0F17] rounded glass"><div class="flex items-center justify-between"><h3 class="font-display text-lg text-white">{{ $review->game->title }}</h3><span class="font-mono-tech text-sm text-emerald-300">{{ $review->rating }}/10</span></div><p class="mt-2 text-gray-200 text-sm line-clamp-3">{{ Str::limit($review->content, 180) }}</p></div>@endforeach</div></section>
    @endif

</div>
@endsection

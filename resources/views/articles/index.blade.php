@extends('layouts.app')

@section('title', 'Oyun Teknolojileri & Yapay Zekâ Makaleleri | GamePL')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <div>
        <span class="font-mono-tech text-xs text-cyan-400">// GAME TECH & AI ESSAYS</span>
        <h1 class="mt-1 font-display text-3xl font-bold text-white">Oyun Teknolojileri ve Yapay Zekâ Makaleleri</h1>
    </div>

    <div class="grid gap-6">
        @foreach($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-3 hover:border-cyan-500/40 transition block">
                <div class="flex items-center justify-between font-mono-tech text-xs text-cyan-400">
                    <span>{{ $article->categoryLabel() }} • {{ $article->author_name }}</span>
                    <span>{{ $article->reading_time_minutes }} dk okuma</span>
                </div>
                <h2 class="font-display text-xl font-bold text-white">{{ $article->title }}</h2>
                <p class="text-xs text-slate-300">{{ $article->excerpt }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $article->title . ' | GamePL')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <article class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 space-y-6">
        <div class="flex items-center justify-between font-mono-tech text-xs text-cyan-400">
            <span>{{ $article->categoryLabel() }} • {{ $article->author_name }} ({{ $article->author_title }})</span>
            <span>{{ $article->reading_time_minutes }} dk okuma</span>
        </div>
        <h1 class="font-display text-3xl font-bold text-white">{{ $article->title }}</h1>
        <p class="text-sm text-slate-300">{{ $article->subtitle }}</p>
        @if($article->pull_quote)
            <blockquote class="rounded-2xl border border-cyan-500/30 bg-cyan-950/20 p-5 text-sm italic text-cyan-200">
                "{{ $article->pull_quote }}"
            </blockquote>
        @endif
        <div class="space-y-4 text-sm leading-relaxed text-slate-200 whitespace-pre-line">{{ $article->body }}</div>
    </article>
</div>
@endsection

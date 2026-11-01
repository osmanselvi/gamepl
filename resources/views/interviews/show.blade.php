@extends('layouts.app')

@section('title', $interview->title . ' | GamePL')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <article class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 space-y-6">
        <span class="font-mono-tech text-xs text-emerald-400">{{ $interview->interviewee_name }} • {{ $interview->roleLabel() }} ({{ $interview->interview_year }})</span>
        <h1 class="font-display text-3xl font-bold text-white">{{ $interview->title }}</h1>
        <p class="text-sm text-slate-300">{{ $interview->subtitle }}</p>
        <blockquote class="rounded-2xl border border-emerald-500/30 bg-emerald-950/20 p-5 text-sm italic text-emerald-200">
            "{{ $interview->featured_quote }}"
        </blockquote>
        <div class="space-y-4 text-sm leading-relaxed text-slate-200 whitespace-pre-line">{{ $interview->body }}</div>
    </article>
</div>
@endsection

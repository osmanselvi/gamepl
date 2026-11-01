@extends('layouts.app')

@section('title', 'Geliştirici & AI Mimarı Söyleşileri | GamePL')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <div>
        <span class="font-mono-tech text-xs text-emerald-400">// DEVELOPER & AI ARCHITECT INTERVIEWS</span>
        <h1 class="mt-1 font-display text-3xl font-bold text-white">Geliştirici ve Yapay Zekâ Mimarı Söyleşileri</h1>
    </div>

    <div class="grid gap-6">
        @foreach($interviews as $interview)
            <a href="{{ route('interviews.show', $interview->slug) }}" class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-3 hover:border-emerald-500/40 transition block">
                <div class="flex items-center justify-between font-mono-tech text-xs text-emerald-400">
                    <span>{{ $interview->interviewee_name }} ({{ $interview->roleLabel() }})</span>
                    <span>{{ $interview->interview_year }} • {{ $interview->source_publication }}</span>
                </div>
                <h2 class="font-display text-xl font-bold text-white">{{ $interview->title }}</h2>
                <p class="text-xs text-slate-300">{{ $interview->subtitle }}</p>
                <blockquote class="border-l-2 border-emerald-500 pl-3 text-xs italic text-slate-200">
                    "{{ $interview->featured_quote }}"
                </blockquote>
            </a>
        @endforeach
    </div>
</div>
@endsection

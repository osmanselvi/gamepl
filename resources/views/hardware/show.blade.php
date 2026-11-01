@extends('layouts.app')

@section('title', $hardware->name . ' — Teknik Donanım İncelemesi | GamePL')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-10">

    <article class="rounded-3xl border border-amber-500/35 bg-[#0B0F17] overflow-hidden shadow-2xl">
        <div class="relative h-72 bg-slate-950">
            <img src="{{ $hardware->image_url }}" alt="{{ $hardware->name }}" class="h-full w-full object-cover opacity-75">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-[#0B0F17]/50 to-transparent"></div>
            <div class="absolute top-5 left-6 flex flex-wrap items-center gap-2">
                <a href="{{ route('hardware.index', ['category' => $hardware->category]) }}" class="rounded-full border border-amber-500/40 bg-slate-950/90 px-3.5 py-1 font-mono-tech text-xs text-amber-300">
                    ← {{ $hardware->categoryLabel() }}
                </a>
                @if($hardware->badge_label)
                    <span class="rounded-full border border-emerald-500/40 bg-emerald-500/15 px-3.5 py-1 font-mono-tech text-xs font-semibold text-emerald-300">
                        {{ $hardware->badge_label }}
                    </span>
                @endif
            </div>
            <div class="absolute bottom-5 left-6 right-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="font-mono-tech text-xs text-cyan-300">{{ $hardware->manufacturer }} • {{ $hardware->release_year }} • {{ $hardware->price_tier }}</span>
                    <h1 class="mt-1 font-display text-3xl font-bold text-white sm:text-4xl">{{ $hardware->name }}</h1>
                </div>
                <div class="rounded-2xl border border-amber-500/40 bg-slate-950/90 px-4 py-2 font-mono-tech text-lg font-bold text-amber-300">
                    ★ {{ number_format($hardware->rating, 1) }} / 10
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-10 space-y-8">
            <p class="text-base leading-relaxed text-slate-200">{{ $hardware->short_intro }}</p>

            <!-- Teknik Özellikler Tablosu -->
            @if(!empty($hardware->key_specs))
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 space-y-3">
                    <h2 class="font-mono-tech text-xs font-semibold uppercase tracking-wider text-amber-400">
                        ⚙ Teknik Donanım Spesifikasyonları
                    </h2>
                    <dl class="divide-y divide-slate-800 text-xs sm:text-sm">
                        @foreach($hardware->key_specs as $specKey => $specVal)
                            <div class="py-2.5 flex flex-col sm:flex-row sm:justify-between gap-1">
                                <dt class="font-mono-tech text-slate-400">{{ $specKey }}</dt>
                                <dd class="font-semibold text-white sm:text-right">{{ $specVal }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif

            <!-- Mühendislik ve Mimari İnceleme -->
            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5 space-y-2">
                    <h3 class="font-mono-tech text-xs font-semibold uppercase text-cyan-400">1. Mühendislik ve Donanım Mimarisi</h3>
                    <p class="text-xs leading-relaxed text-slate-200 sm:text-sm">{{ $hardware->technical_deep_dive }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-500/30 bg-emerald-950/20 p-5 space-y-2">
                    <h3 class="font-mono-tech text-xs font-semibold uppercase text-emerald-400">2. Oyun Motorları & Yapay Zekâ İlişkisi</h3>
                    <p class="text-xs leading-relaxed text-slate-200 sm:text-sm">{{ $hardware->ai_and_game_synergy }}</p>
                </div>
            </div>

            <!-- Artılar, Eksiler ve Kimler İçin İdeal -->
            <div class="grid gap-4 md:grid-cols-3 text-xs">
                <div class="rounded-2xl border border-emerald-500/30 bg-emerald-950/15 p-4">
                    <strong class="font-mono-tech text-emerald-400 block mb-1.5">✓ Güçlü Yönleri (Artılar)</strong>
                    <p class="text-slate-200 leading-relaxed">{{ $hardware->pros }}</p>
                </div>
                <div class="rounded-2xl border border-rose-500/30 bg-rose-950/15 p-4">
                    <strong class="font-mono-tech text-rose-400 block mb-1.5">✗ Dikkat Edilmesi Gerekenler</strong>
                    <p class="text-slate-200 leading-relaxed">{{ $hardware->cons }}</p>
                </div>
                <div class="rounded-2xl border border-amber-500/30 bg-amber-950/15 p-4">
                    <strong class="font-mono-tech text-amber-400 block mb-1.5">🎯 Hangi Oyuncular İçin İdeal?</strong>
                    <p class="text-slate-200 leading-relaxed">{{ $hardware->recommended_for }}</p>
                </div>
            </div>

            @if($hardware->embedVideoUrl())
                <div>
                    <button type="button"
                        onclick="openVideoModal('{{ $hardware->embedVideoUrl() }}', '{{ addslashes($hardware->name) }} — Performans & Görüntü Testi', 'DONANIM VİDEOSU')"
                        class="rounded-xl bg-amber-400 px-5 py-2.5 text-xs font-bold text-slate-950 hover:bg-amber-300 transition">
                        ▶ Bu Donanımın Oyun Performans Videosunu Aç (Sinema Modu)
                    </button>
                </div>
            @endif
        </div>
    </article>

    <!-- Bu Donanımın Potansiyelini Gösteren Oyunlar -->
    @if($hardware->games->isNotEmpty())
        <section class="space-y-4">
            <h2 class="font-display text-2xl font-bold text-white">🎮 Bu Donanımı Test Etmek İçin En İyi Oyunlar</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($hardware->games as $game)
                    <a href="{{ route('games.show', $game->slug) }}" class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 hover:border-amber-500/50 transition space-y-2">
                        <div class="flex items-center justify-between font-mono-tech text-xs text-slate-400">
                            <span>{{ $game->release_year }} • {{ $game->developer_studio }}</span>
                            <span class="text-amber-300">★ {{ number_format($game->weightedRating(), 1) }}</span>
                        </div>
                        <h3 class="font-display text-lg font-bold text-white">{{ $game->title }}</h3>
                        @if($game->pivot->experience_note)
                            <p class="text-xs text-emerald-300">{{ $game->pivot->experience_note }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection

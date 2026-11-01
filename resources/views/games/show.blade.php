@extends('layouts.app')

@section('title', $game->title . ' — Teknik Künye, Yapay Zekâ Mimarisi & Örnek Videolar | GamePL')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-12">

    <!-- Oyun Hero & Teknik Künye Özeti -->
    <section class="relative overflow-hidden rounded-3xl border border-slate-800 bg-[#0B0F17] p-6 sm:p-10 shadow-2xl">
        <div class="grid gap-8 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <img src="{{ $game->poster_url }}" alt="{{ $game->title }}" class="w-full rounded-2xl border border-slate-700/80 object-cover shadow-xl">

                <!-- Oyuncu Belleği & Backlog Kayıt Kutusu -->
                @auth
                <div class="mt-4 rounded-2xl border border-slate-800 bg-slate-900/80 p-4 space-y-3">
                    <h4 class="font-mono-tech text-xs font-semibold uppercase text-emerald-400">🎮 Oyuncu Belleğine Kaydet</h4>
                    <form action="{{ route('games.log', $game->slug) }}" method="POST" class="space-y-2.5">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <select name="play_status" class="rounded-lg border border-slate-700 bg-slate-950 px-2.5 py-1.5 text-xs text-white">
                                <option value="playing" @selected($userLog?->play_status === 'playing')>Şu An Oynuyor</option>
                                <option value="completed" @selected($userLog?->play_status === 'completed')>Ana Hikâye Bitti</option>
                                <option value="mastered" @selected($userLog?->play_status === 'mastered')>%100 Tamamlandı</option>
                                <option value="backlog" @selected($userLog?->play_status === 'backlog')>Sırada (Backlog)</option>
                            </select>
                            <input type="number" name="hours_played" value="{{ $userLog?->hours_played ?? $game->main_story_hours }}" placeholder="Saat"
                                class="rounded-lg border border-slate-700 bg-slate-950 px-2.5 py-1.5 text-xs text-white">
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-emerald-500 py-1.5 text-xs font-semibold text-slate-950 hover:bg-emerald-400">
                            Günlüğü Güncelle
                        </button>
                    </form>
                    <form action="{{ route('games.watchlist', $game->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-slate-700 py-1.5 text-xs text-slate-300 hover:border-cyan-500/40 hover:text-cyan-300">
                            {{ $inWatchlist ? '✓ İstek Listesinde (Çıkar)' : '+ İstek Listesine Ekle' }}
                        </button>
                    </form>
                </div>
                @endauth
            </div>

            <div class="lg:col-span-8 space-y-6">
                <div class="flex flex-wrap items-center gap-2.5">
                    @if($game->engine)
                        <a href="{{ route('engines.show', $game->engine->slug) }}" class="rounded-full border border-cyan-500/40 bg-cyan-500/15 px-3 py-1 font-mono-tech text-xs text-cyan-300">
                            ⚙ Motor: {{ $game->engine->name }}
                        </a>
                    @endif
                    <span class="rounded-full border border-amber-500/40 bg-amber-500/15 px-3 py-1 font-mono-tech text-xs font-bold text-amber-300">
                        ★ Bayesian Puan: {{ number_format($game->weightedRating(), 1) }} / 10
                    </span>
                    @if($game->metacritic_score)
                        <span class="rounded-full border border-emerald-500/40 bg-emerald-500/15 px-3 py-1 font-mono-tech text-xs text-emerald-300">
                            Metacritic: {{ $game->metacritic_score }}
                        </span>
                    @endif
                    <span class="rounded-full border border-slate-700 bg-slate-900 px-3 py-1 font-mono-tech text-xs text-slate-300">
                        {{ $game->perspective }}
                    </span>
                </div>

                <div>
                    <h1 class="font-display text-3xl font-bold text-white sm:text-5xl">{{ $game->title }}</h1>
                    <p class="mt-2 font-mono-tech text-xs text-slate-400">
                        {{ $game->release_year }} • Stüdyo: <strong class="text-slate-200">{{ $game->developer_studio }}</strong> • Yayıncı: {{ $game->publisher }} • Ülke: {{ $game->country }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-3">
                        <span class="block font-mono-tech text-[10px] uppercase text-slate-400">Oyun Yönetmeni</span>
                        <span class="mt-1 block text-xs font-semibold text-white">{{ $game->game_director }}</span>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-3">
                        <span class="block font-mono-tech text-[10px] uppercase text-slate-400">Baş AI Mimarı / Ekibi</span>
                        <span class="mt-1 block text-xs font-semibold text-emerald-400">{{ $game->lead_ai_programmer ?: 'Stüdyo AI Ekibi' }}</span>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-3">
                        <span class="block font-mono-tech text-[10px] uppercase text-slate-400">Ana Hikâye / %100</span>
                        <span class="mt-1 block font-mono-tech text-xs font-semibold text-cyan-300">{{ $game->main_story_hours }}s / {{ $game->completionist_hours }}s</span>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-3">
                        <span class="block font-mono-tech text-[10px] uppercase text-slate-400">Müzik / Besteci</span>
                        <span class="mt-1 block text-xs font-semibold text-white">{{ $game->composer ?: 'Orijinal Ses Ekibi' }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="font-mono-tech text-xs font-semibold uppercase tracking-wider text-slate-400">// Öykü & Evren Özeti</h3>
                    <p class="text-sm leading-relaxed text-slate-200">{{ $game->synopsis }}</p>
                </div>

                @if($game->technical_overview)
                <div class="rounded-2xl border border-cyan-500/30 bg-cyan-950/20 p-4">
                    <h3 class="font-mono-tech text-xs font-semibold uppercase tracking-wider text-cyan-400">⚙ Oyun Motoru & Mühendislik Mimarisi</h3>
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-200 sm:text-sm">{{ $game->technical_overview }}</p>
                </div>
                @endif

                @if($game->ai_architecture_summary)
                <div class="rounded-2xl border border-emerald-500/35 bg-emerald-950/25 p-4">
                    <h3 class="font-mono-tech text-xs font-semibold uppercase tracking-wider text-emerald-400">🧠 Yapay Zekâ ve NPC Karar Mimarisi</h3>
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-200 sm:text-sm">{{ $game->ai_architecture_summary }}</p>
                </div>
                @endif

                @if($game->embedTrailerUrl())
                <div class="pt-2">
                    <button type="button"
                        onclick="openVideoModal('{{ $game->embedTrailerUrl() }}', '{{ addslashes($game->title) }} — Resmi Fragman & Oynanış', 'SİNEMA MODU')"
                        class="rounded-xl bg-cyan-500 px-5 py-2.5 text-xs font-bold text-slate-950 hover:bg-cyan-400 transition">
                        ▶ Resmi Oynanış / Fragman Videosunu Aç
                    </button>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Bu Oyuna Ait Yapay Zekâ Vaka Analizleri (Game AI Case Studies) -->
    @if($game->approvedAiAnalyses->isNotEmpty())
    <section class="space-y-4">
        <h2 class="font-display text-2xl font-bold text-white">🔬 Bu Oyunun Yapay Zekâ Vaka Analizi (AI Deep-Dive)</h2>
        <div class="grid gap-6">
            @foreach($game->approvedAiAnalyses as $analysis)
                <div class="rounded-2xl border border-emerald-500/35 bg-[#0A1219] p-6 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="rounded-md border border-emerald-500/40 bg-emerald-500/15 px-3 py-1 font-mono-tech text-xs text-emerald-300">
                            AI Sistemi: {{ $analysis->aiSystem->name }}
                        </span>
                        <div class="flex gap-2 font-mono-tech text-xs">
                            <span class="rounded bg-emerald-500/20 px-2.5 py-1 text-emerald-300">İnovasyon: {{ $analysis->innovation_score }}/100</span>
                            <span class="rounded bg-violet-500/20 px-2.5 py-1 text-violet-300">Karmaşıklık: {{ $analysis->complexity_score }}/100</span>
                        </div>
                    </div>
                    <h3 class="font-display text-xl font-bold text-white">{{ $analysis->title }}</h3>
                    <div class="grid gap-4 md:grid-cols-2 text-xs leading-relaxed text-slate-200">
                        <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-4">
                            <strong class="block font-mono-tech text-emerald-400 mb-1">1. Mimari Çözümleme</strong>
                            {{ $analysis->architecture_breakdown }}
                        </div>
                        <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-4">
                            <strong class="block font-mono-tech text-cyan-400 mb-1">2. Algılayıcılar (Sensors) & Karar Döngüsü</strong>
                            {{ $analysis->perception_and_sensors }} {{ $analysis->decision_loop_details }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('ai.analyses.show', $analysis->slug) }}" class="text-xs font-semibold text-emerald-400 hover:underline">
                            Tam Vaka Raporunu ve Pseudocode Diyagramını Gör →
                        </a>
                        @if($analysis->embedVideoUrl())
                            <button type="button"
                                onclick="openVideoModal('{{ $analysis->embedVideoUrl() }}', '{{ addslashes($analysis->title) }}', 'AI VAKA VİDEOSU')"
                                class="rounded-lg border border-cyan-500/40 bg-cyan-500/15 px-3 py-1.5 font-mono-tech text-xs text-cyan-300">
                                ▶ Kanıt Videosunu Oynat
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Örnek Oynanış & Teknik İnceleme Videoları -->
    @if($game->approvedVideos->isNotEmpty())
    <section class="space-y-4">
        <h2 class="font-display text-2xl font-bold text-white">🎬 Örnek Oynanış ve Teknik Analiz Videoları</h2>
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($game->approvedVideos as $video)
                <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 flex items-center justify-between gap-4">
                    <div>
                        <span class="font-mono-tech text-[11px] text-cyan-400">{{ $video->typeLabel() }} • {{ $video->duration_label }} ({{ $video->resolution_tag }})</span>
                        <h3 class="mt-1 font-display text-base font-bold text-white">{{ $video->title }}</h3>
                        <p class="mt-1 text-xs text-slate-400">{{ $video->description }}</p>
                    </div>
                    <button type="button"
                        onclick="openVideoModal('{{ $video->embedVideoUrl() }}', '{{ addslashes($video->title) }}', '{{ addslashes($video->typeLabel()) }}')"
                        class="shrink-0 rounded-xl bg-emerald-500 px-4 py-2.5 font-mono-tech text-xs font-bold text-slate-950 hover:bg-emerald-400">
                        ▶ İzle
                    </button>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Bu Oyun İçin Önerilen Monitör, Konsol & GPU Donanımları + PC Sistem Gereksinimleri -->
    <section class="grid gap-8 lg:grid-cols-2">
        @if(!empty($game->pc_min_specs) || !empty($game->pc_rec_specs))
        <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-4">
            <h2 class="font-display text-xl font-bold text-white">💻 PC Sistem Gereksinimleri</h2>
            <div class="grid gap-4 sm:grid-cols-2 text-xs">
                @if(!empty($game->pc_min_specs))
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 space-y-2">
                    <span class="font-mono-tech text-[11px] font-semibold uppercase text-slate-400">Minimum</span>
                    @foreach($game->pc_min_specs as $k => $v)
                        <div class="flex justify-between border-b border-slate-800/60 pb-1 last:border-0">
                            <span class="font-mono-tech text-slate-400">{{ $k }}:</span>
                            <span class="text-slate-200 font-medium text-right">{{ $v }}</span>
                        </div>
                    @endforeach
                </div>
                @endif
                @if(!empty($game->pc_rec_specs))
                <div class="rounded-xl border border-emerald-500/30 bg-emerald-950/15 p-4 space-y-2">
                    <span class="font-mono-tech text-[11px] font-semibold uppercase text-emerald-400">Önerilen (Yüksek / RT)</span>
                    @foreach($game->pc_rec_specs as $k => $v)
                        <div class="flex justify-between border-b border-slate-800/60 pb-1 last:border-0">
                            <span class="font-mono-tech text-emerald-300/80">{{ $k }}:</span>
                            <span class="text-white font-semibold text-right">{{ $v }}</span>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($game->hardware->isNotEmpty())
        <div class="rounded-2xl border border-amber-500/30 bg-[#0B0F17] p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-xl font-bold text-amber-300">🖥️ Bu Oyun İçin Önerilen Teknik Donanımlar</h2>
                <a href="{{ route('hardware.index') }}" class="font-mono-tech text-xs text-amber-400 hover:underline">Tüm Donanımlar →</a>
            </div>
            <div class="space-y-3">
                @foreach($game->hardware as $hw)
                    <a href="{{ route('hardware.show', $hw->slug) }}" class="block rounded-xl border border-slate-800 bg-slate-900/80 p-4 hover:border-amber-500/50 transition">
                        <div class="flex items-center justify-between">
                            <span class="font-display text-sm font-bold text-white">{{ $hw->categoryIcon() }} {{ $hw->name }}</span>
                            <span class="font-mono-tech text-xs text-emerald-400">★ {{ number_format($hw->rating, 1) }}</span>
                        </div>
                        @if($hw->pivot->experience_note)
                            <p class="mt-1 text-xs text-slate-300">{{ $hw->pivot->experience_note }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </section>

    <!-- Yaratıcı Kadro (Oyun Yönetmeni, AI Mimarı, Besteci, Seslendirme) & Ödüller -->
    @if($game->people->isNotEmpty() || $game->awards->isNotEmpty())
    <section class="grid gap-8 lg:grid-cols-2">
        @if($game->people->isNotEmpty())
        <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-4">
            <h2 class="font-display text-xl font-bold text-white">👥 Yaratıcı Kadro & Performans Sanatçıları</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach($game->people as $person)
                    <a href="{{ route('people.show', $person->slug) }}" class="rounded-xl border border-slate-800 bg-slate-900/70 p-3.5 hover:border-emerald-500/50 transition block">
                        <div class="font-display text-sm font-bold text-white">{{ $person->name }}</div>
                        <div class="font-mono-tech text-[11px] text-emerald-400">{{ $person->pivot->character_name ?: $person->roleLabel() }}</div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($game->awards->isNotEmpty())
        <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-4">
            <h2 class="font-display text-xl font-bold text-amber-300">🏆 Kazanılan Küresel Oyun Ödülleri</h2>
            <div class="space-y-3">
                @foreach($game->awards as $award)
                    <div class="rounded-xl border border-amber-500/25 bg-amber-950/15 p-3.5">
                        <div class="flex items-center justify-between font-mono-tech text-xs text-amber-300">
                            <span>{{ $award->ceremony_name }} ({{ $award->year }})</span>
                            <span class="uppercase">{{ $award->result === 'winner' ? '★ KAZANAN' : 'ADAY' }}</span>
                        </div>
                        <div class="mt-1 font-display text-sm font-bold text-white">{{ $award->category }}</div>
                        @if($award->notes)
                            <p class="mt-1 text-xs text-slate-300">{{ $award->notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>
    @endif

    <!-- Oyuncu & Geliştirici İncelemeleri -->
    <section class="space-y-6">
        <h2 class="font-display text-2xl font-bold text-white">💬 Teknik & Oynanış İncelemeleri ({{ $game->reviews->count() }})</h2>

        @auth
        <form action="{{ route('games.reviews.store', $game->slug) }}" method="POST" class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-6 space-y-4">
            @csrf
            <h3 class="font-display text-base font-bold text-white">Yeni İnceleme & AI Kalite Puanı Yaz</h3>
            <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-6">
                <div>
                    <label class="block font-mono-tech text-[10px] text-slate-400">Genel Puan (1-10)</label>
                    <input type="number" step="0.1" min="1" max="10" name="rating" value="9.5" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block font-mono-tech text-[10px] text-slate-400">Oynanış (1-10)</label>
                    <input type="number" step="0.1" min="1" max="10" name="gameplay_rating" value="9.5" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block font-mono-tech text-[10px] text-emerald-400">AI Kalitesi (1-10)</label>
                    <input type="number" step="0.1" min="1" max="10" name="ai_quality_rating" value="9.5" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block font-mono-tech text-[10px] text-cyan-400">Optimizasyon (1-10)</label>
                    <input type="number" step="0.1" min="1" max="10" name="optimization_rating" value="9.0" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block font-mono-tech text-[10px] text-slate-400">Platform</label>
                    <input type="text" name="played_platform" value="PC" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block font-mono-tech text-[10px] text-slate-400">Oynama Süresi (Saat)</label>
                    <input type="number" name="hours_played" value="{{ $game->main_story_hours }}" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs text-white">
                </div>
            </div>
            <input type="text" name="title" placeholder="İnceleme başlığınız..." required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3.5 py-2 text-xs text-white">
            <textarea name="body" rows="3" placeholder="Oyunun mekanikleri, düşman yapay zekâsı ve teknik performansı hakkındaki değerlendirmeniz..." required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3.5 py-2 text-xs text-white"></textarea>
            <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-xs font-semibold text-slate-950 hover:bg-emerald-400">
                İncelemeyi Yayınla
            </button>
        </form>
        @endauth

        <div class="grid gap-4">
            @foreach($game->reviews as $review)
                <div class="rounded-2xl border border-slate-800 bg-[#0B0F17] p-5 space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="font-display text-sm font-bold text-white">{{ $review->author_name }}</span>
                            <span class="ml-2 font-mono-tech text-[11px] text-slate-400">{{ $review->author_role }} • {{ $review->played_platform }} ({{ $review->hours_played }} saat)</span>
                        </div>
                        <div class="flex flex-wrap gap-2 font-mono-tech text-[11px]">
                            <span class="rounded bg-amber-500/15 px-2 py-0.5 text-amber-300">Genel: {{ $review->rating }}</span>
                            <span class="rounded bg-emerald-500/15 px-2 py-0.5 text-emerald-300">AI: {{ $review->ai_quality_rating }}</span>
                            <span class="rounded bg-cyan-500/15 px-2 py-0.5 text-cyan-300">Optimizasyon: {{ $review->optimization_rating }}</span>
                        </div>
                    </div>
                    <h4 class="font-display text-base font-bold text-white">{{ $review->title }}</h4>
                    <p class="text-xs leading-relaxed text-slate-300">{{ $review->body }}</p>
                </div>
            @endforeach
        </div>
    </section>

</div>
@endsection

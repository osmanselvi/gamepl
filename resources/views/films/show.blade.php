@extends('layouts.app')

@section('title', $film->title . ($film->year ? " ({$film->year})" : '') . ' | Cinema Culture')
@section('meta_description', Str::limit($film->description ?: ($film->title . ' film detayları, yönetmen, oyuncu kadrosu ve eleştiriler.'), 160))

@section('meta')
    <meta property="og:title" content="{{ $film->title }}">
    <meta property="og:type" content="video.movie">
    <meta property="og:description" content="{{ Str::limit($film->description ?: $film->title, 160) }}">
    @if($film->poster_url)
        <meta property="og:image" content="{{ $film->poster_url }}">
    @endif
    @php
        $avgRating = $film->reviews->avg('rating');
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Movie',
            'name' => $film->title,
            'description' => $film->description,
            'dateCreated' => $film->year ? (string) $film->year : null,
            'genre' => $film->genre,
            'image' => $film->poster_url,
            'director' => $film->director ? [
                '@type' => 'Person',
                'name' => $film->director,
            ] : null,
            'actor' => $actors->map(fn ($actor) => [
                '@type' => 'Person',
                'name' => $actor,
            ])->all(),
        ];
        if ($avgRating && $film->reviews->count() > 0) {
            $jsonLd['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($avgRating, 1),
                'bestRating' => 10,
                'worstRating' => 1,
                'ratingCount' => $film->reviews->count(),
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode(array_filter($jsonLd), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('content')
<div class="min-h-screen bg-background text-text">

    <!-- Header -->
    <header class="border-b border-gray-700 bg-card/50 backdrop-blur py-4 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors">
                        <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                            <line x1="7" y1="2" x2="7" y2="22"></line>
                            <line x1="17" y1="2" x2="17" y2="22"></line>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <line x1="2" y1="7" x2="7" y2="7"></line>
                            <line x1="2" y1="17" x2="7" y2="17"></line>
                            <line x1="17" y1="17" x2="22" y2="17"></line>
                            <line x1="17" y1="7" x2="22" y2="7"></line>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Cinema Culture</span>
                </a>
                <span class="text-gray-600">/</span>
                <a href="{{ route('home') }}" class="text-sm text-gray-300 hover:text-primary transition-colors">← Tüm Filmler</a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline">Sinema Yazıları</a>
                    <div id="guestActions" class="flex items-center gap-2 @auth hidden @endauth">
                        <button type="button" class="btn btn-sm btn-outline" onclick="showLoginModal()">Giriş</button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="showRegisterModal()">Kayıt Ol</button>
                    </div>
                    <div id="userActions" class="flex items-center gap-2 @guest hidden @endguest">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">Dashboard</a>
                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline">Arşivim</a>
                        <span class="text-sm text-gray-300"><strong id="userNameDisplay" class="text-white">{{ Auth::user()?->name }}</strong></span>
                        <button type="button" class="btn btn-sm btn-outline" onclick="logout()">Çıkış</button>
                    </div>
                </div>
                <button type="button" class="md:hidden btn btn-sm btn-primary" onclick="toggleMenu()" aria-label="Menü">☰</button>
            </div>
        </div>
    </header>

    <!-- Main Detail Content -->
    <main class="py-8 max-w-7xl mx-auto px-4 space-y-10">

        <!-- Film Hero Section -->
        <section class="bg-card border border-gray-800 rounded-2xl p-6 md:p-8 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                
                <!-- Poster & Actions -->
                <div class="md:col-span-4 lg:col-span-3 space-y-3">
                    <div class="aspect-[2/3] rounded-xl overflow-hidden bg-gray-900 border border-gray-700 shadow-lg">
                        <img
                            src="{{ $film->poster_url ?: 'https://placehold.co/400x600/1e293b/f1f5f9?text=' . urlencode($film->title) }}"
                            alt="{{ $film->title }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/400x600/1e293b/f1f5f9?text=Cinema';"
                            class="w-full h-full object-cover"
                        >
                    </div>

                    @if($film->trailer_url)
                        @php
                            $embedTrailer = $film->embedTrailerUrl();
                        @endphp
                        @if($embedTrailer)
                            <button
                                type="button"
                                onclick="openTrailerLightbox('{{ $embedTrailer }}')"
                                class="w-full btn btn-primary flex items-center justify-center gap-2 py-2.5 cursor-pointer"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                                Fragmanı Sinema Modunda İzle
                            </button>
                        @endif
                        <a
                            href="{{ $film->trailer_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-full btn btn-sm btn-outline flex items-center justify-center gap-2 py-2 text-xs"
                        >
                            Fragmanı YouTube'da Aç ↗
                        </a>
                    @endif

                    @auth
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <button
                                type="button"
                                id="watchlistBtn"
                                onclick="toggleWatchlist({{ $film->id }})"
                                class="btn btn-sm {{ $inWatchlist ? 'bg-primary text-white' : 'btn-outline' }} w-full text-xs"
                            >
                                <span id="watchlistBtnText">{{ $inWatchlist ? '✓ Listemde' : '+ İzleme Listesi' }}</span>
                            </button>
                            <button
                                type="button"
                                id="watchedBtn"
                                onclick="toggleWatchedLog({{ $film->id }})"
                                class="btn btn-sm {{ $watchedLog ? 'bg-success text-white' : 'btn-outline' }} w-full text-xs"
                            >
                                <span id="watchedBtnText">{{ $watchedLog ? '✓ İzlendi' : '👁 İzledim' }}</span>
                            </button>
                        </div>
                    @endauth
                </div>

                <!-- Metadata -->
                <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if($film->genre)
                                <a
                                    href="{{ route('home', ['genre' => $film->genre]) }}"
                                    class="text-xs font-semibold uppercase tracking-wider bg-primary/20 text-primary hover:bg-primary/30 px-3 py-1 rounded-full transition-colors"
                                >
                                    {{ $film->genre }}
                                </a>
                            @endif

                            @if($film->year)
                                <a
                                    href="{{ route('home', ['year' => $film->year]) }}"
                                    class="text-xs font-medium bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-1 rounded-full border border-gray-700 transition-colors"
                                >
                                    {{ $film->year }}
                                </a>
                            @endif

                            @if($film->runtime)
                                <span class="text-xs font-medium bg-gray-800 text-gray-300 px-3 py-1 rounded-full border border-gray-700">
                                    ⏱ {{ $film->runtime }} dk
                                </span>
                            @endif

                            @if($film->country)
                                <a
                                    href="{{ route('home', ['country' => $film->country]) }}"
                                    class="text-xs font-medium bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-1 rounded-full border border-gray-700 transition-colors"
                                >
                                    {{ $film->country }}
                                </a>
                            @endif

                            @if($avgRating)
                                @php
                                    $weightedScore = $film->weightedRating();
                                @endphp
                                <span class="text-xs font-semibold bg-secondary/20 text-secondary px-3 py-1 rounded-full" title="Aritmetik Ortalama">
                                    ★ {{ number_format($avgRating, 1) }} / 10 ({{ $film->reviews->count() }} eleştiri)
                                </span>
                                @if($weightedScore)
                                    <span class="text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-3 py-1 rounded-full" title="Bayesian Ağırlıklı Sinefil Puanı">
                                        ⚖️ Ağırlıklı Skor: {{ number_format($weightedScore, 2) }}
                                    </span>
                                @endif
                            @endif
                        </div>

                        <h1 id="filmMainTitle" class="text-2xl sm:text-4xl font-bold text-white tracking-tight">{{ $film->title }}</h1>
                        @if($film->original_title && $film->original_title !== $film->title)
                            <p class="text-sm text-gray-400 italic mt-1">Orijinal Adı: <span class="text-gray-200 font-medium">{{ $film->original_title }}</span></p>
                        @endif

                        <!-- International Titles Showcase -->
                        @if(!empty($film->translations))
                            <div class="mt-4 bg-gray-900/60 border border-gray-800 rounded-xl p-3.5 space-y-2">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">🌐 Farklı Dillerde Film İsimleri & Özet Çevirisi</span>
                                    <span class="text-[11px] text-primary">Dile tıklayarak başlık ve özeti çevirin</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $langFlags = [
                                            'tr' => 'Türkçe',
                                            'en' => 'English',
                                            'fr' => 'Français',
                                            'de' => 'Deutsch',
                                            'es' => 'Español',
                                            'it' => 'Italiano',
                                            'ja' => '日本語',
                                        ];
                                    @endphp
                                    @foreach($langFlags as $code => $label)
                                        @if(!empty($film->translations[$code]['title']))
                                            <button
                                                type="button"
                                                onclick="window.setSiteLanguage('{{ $code }}')"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-800 hover:bg-primary/20 border border-gray-700 hover:border-primary/50 text-xs transition-all cursor-pointer"
                                            >
                                                <span class="text-gray-400">{{ $label }}:</span>
                                                <strong class="text-white">{{ $film->translations[$code]['title'] }}</strong>
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Synopsis -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Film Özeti</h2>
                            <span id="synopsisLangBadge" class="text-[11px] bg-primary/20 text-primary px-2 py-0.5 rounded font-medium uppercase">TR</span>
                        </div>
                        <p id="filmMainDescription" class="text-gray-200 leading-relaxed text-base">
                            {{ $film->description ?: 'Bu film için henüz özet bilgisi girilmemiştir.' }}
                        </p>
                    </div>

                    <!-- Festival & Award History -->
                    @if($film->awards->isNotEmpty())
                        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 space-y-2.5">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-amber-300 flex items-center gap-1.5">
                                🏆 Festival Seçkileri ve Ödül Arşivi ({{ $film->awards->count() }})
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($film->awards as $award)
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-900/90 border border-amber-500/40 text-xs">
                                        <span class="font-bold text-amber-300">{{ $award->festival_name }} ({{ $award->year }})</span>
                                        <span class="text-gray-500">•</span>
                                        <span class="text-white font-medium">{{ $award->award_name }}</span>
                                        @if($award->person)
                                            <a href="{{ route('people.show', $award->person->slug) }}" class="text-primary hover:underline font-medium">({{ $award->person->name }})</a>
                                        @endif
                                        <span class="text-[10px] uppercase px-1.5 py-0.5 rounded font-semibold {{ $award->result === 'winner' ? 'bg-amber-500/20 text-amber-300' : 'bg-gray-800 text-gray-400' }}">
                                            {{ $award->result === 'winner' ? 'Kazandı' : 'Aday / Seçki' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Creative Crew & Cast Links -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-800">
                        <!-- Director & Creative Crew -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Yönetmen</h3>
                                @if($film->director)
                                    @php
                                        $directorPerson = $film->directors->first();
                                    @endphp
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a
                                            href="{{ $directorPerson ? route('people.show', $directorPerson->slug) : route('home', ['director' => $film->director]) }}"
                                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-800/90 hover:bg-primary/20 border border-gray-700 hover:border-primary/50 text-white hover:text-primary transition-all font-medium text-sm"
                                        >
                                            <svg class="w-4 h-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M23 7l-7 5 7 5V7z"></path>
                                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                            </svg>
                                            <span>{{ $film->director }}</span>
                                        </a>
                                        <a
                                            href="{{ route('home', ['director' => $film->director]) }}"
                                            class="text-xs text-gray-400 hover:text-primary underline"
                                        >
                                            Filtrele
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Belirtilmemiş</span>
                                @endif
                            </div>

                            @if($film->writers->isNotEmpty() || $film->cinematographers->isNotEmpty() || $film->composers->isNotEmpty() || $film->writer || $film->cinematographer || $film->composer)
                                <div class="grid grid-cols-1 gap-2.5 pt-2 border-t border-gray-800/70 text-xs">
                                    @if($film->writers->isNotEmpty() || $film->writer)
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-gray-400 font-semibold uppercase w-36 shrink-0">✒️ Senaryo:</span>
                                            @forelse($film->writers as $writerPerson)
                                                <a href="{{ route('people.show', $writerPerson->slug) }}" class="text-gray-200 hover:text-primary font-medium underline decoration-gray-700">{{ $writerPerson->name }}</a>
                                            @empty
                                                <span class="text-gray-300">{{ $film->writer }}</span>
                                            @endforelse
                                        </div>
                                    @endif

                                    @if($film->cinematographers->isNotEmpty() || $film->cinematographer)
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-gray-400 font-semibold uppercase w-36 shrink-0">🎥 Görüntü Yön.:</span>
                                            @forelse($film->cinematographers as $dopPerson)
                                                <a href="{{ route('people.show', $dopPerson->slug) }}" class="text-gray-200 hover:text-primary font-medium underline decoration-gray-700">{{ $dopPerson->name }}</a>
                                            @empty
                                                <span class="text-gray-300">{{ $film->cinematographer }}</span>
                                            @endforelse
                                        </div>
                                    @endif

                                    @if($film->composers->isNotEmpty() || $film->composer)
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-gray-400 font-semibold uppercase w-36 shrink-0">🎼 Özgün Müzik:</span>
                                            @forelse($film->composers as $compPerson)
                                                <a href="{{ route('people.show', $compPerson->slug) }}" class="text-gray-200 hover:text-primary font-medium underline decoration-gray-700">{{ $compPerson->name }}</a>
                                            @empty
                                                <span class="text-gray-300">{{ $film->composer }}</span>
                                            @endforelse
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Cast & Character Names -->
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Oyuncu Kadrosu & Roller</h3>
                            @if($actors->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($actors as $actor)
                                        @php
                                            $actorPerson = $film->actors->firstWhere('name', $actor);
                                            $charName = $actorPerson?->pivot?->character_name;
                                        @endphp
                                        <a
                                            href="{{ $actorPerson ? route('people.show', $actorPerson->slug) : route('home', ['actor' => $actor]) }}"
                                            data-filter-url="{{ route('home', ['actor' => $actor]) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-800/90 hover:bg-primary/20 border border-gray-700 hover:border-primary/50 text-gray-200 hover:text-primary transition-all text-sm"
                                        >
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            <span>{{ $actor }}</span>
                                            @if($charName)
                                                <span class="text-xs text-secondary font-medium border-l border-gray-700 pl-1.5">rol: {{ $charName }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Belirtilmemiş</span>
                            @endif
                        </div>
                    </div>

                    <!-- Rating Distribution Histogram -->
                    @if($film->reviews->isNotEmpty())
                        <div class="pt-4 border-t border-gray-800">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Puan Dağılımı (1 - 10)</h3>
                            <div class="grid grid-cols-10 gap-1.5 items-end h-20 bg-gray-900/50 p-3 rounded-xl border border-gray-800">
                                @foreach(range(1, 10) as $score)
                                    @php
                                        $bar = $ratingHistogram[$score] ?? ['count' => 0, 'percentage' => 0];
                                    @endphp
                                    <div class="flex flex-col items-center gap-1 h-full justify-end" title="{{ $score }} Puan: {{ $bar['count'] }} oy (%{{ $bar['percentage'] }})">
                                        <div class="w-full bg-gray-800 rounded-t overflow-hidden flex items-end h-12">
                                            <div
                                                class="w-full bg-secondary transition-all"
                                                style="height: {{ max($bar['percentage'], $bar['count'] > 0 ? 15 : 4) }}%"
                                            ></div>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-mono">{{ $score }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </section>

        <!-- Director & Cast Interviews for this Film -->
        <section class="bg-card border border-gray-800 rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-lg font-semibold text-white">🎙️ Yönetmen ve Oyuncu Röportajları ({{ $film->interviews->count() }})</h2>
                    <p class="text-xs text-gray-400">Bu filmin yönetmeni ve oyuncu kadrosuyla gerçekleştirilen söyleşiler</p>
                </div>
                @auth
                    @if(Auth::user()->canSubmitInterviews())
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline text-xs">+ Röportaj Ekle</a>
                    @endif
                @endauth
            </div>

            @if($film->interviews->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($film->interviews as $interview)
                        <a
                            href="{{ route('interviews.show', $interview->slug) }}"
                            class="bg-gray-800/60 border border-gray-700/70 hover:border-secondary/60 rounded-xl p-5 flex flex-col justify-between gap-3 transition-all"
                        >
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2 text-xs">
                                    <span class="bg-secondary/20 text-secondary font-semibold px-2.5 py-0.5 rounded-full uppercase">
                                        {{ $interview->interviewee_role }}: {{ $interview->interviewee_name }}
                                    </span>
                                    <span class="text-gray-400">Söyleşi</span>
                                </div>
                                <h3 class="text-base font-bold text-white hover:text-secondary transition-colors">{{ $interview->title }}</h3>
                                <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">{{ $interview->excerpt }}</p>
                            </div>
                            <span class="text-xs text-secondary font-medium">Röportajın Tamamını Oku →</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">Bu film için henüz yönetmen veya oyuncu röportajı eklenmemiş.</p>
            @endif
        </section>

        <!-- Cinema Articles about this Film -->
        @if($film->articles->isNotEmpty())
            <section class="bg-card border border-gray-800 rounded-2xl p-6 space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-white">✒️ Bu Film Üzerine Sinema Yazıları ({{ $film->articles->count() }})</h2>
                    <p class="text-xs text-gray-400">Film üzerine kaleme alınan kuramsal analizler ve inceleme yazıları</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($film->articles as $article)
                        <a
                            href="{{ route('articles.show', $article->slug) }}"
                            class="bg-gray-800/60 border border-gray-700/70 hover:border-primary/60 rounded-xl p-5 flex flex-col justify-between gap-3 transition-all"
                        >
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="bg-primary/20 text-primary font-semibold px-2.5 py-0.5 rounded-full">{{ $article->category }}</span>
                                    <span class="text-gray-400">{{ $article->reading_time }} dk okuma</span>
                                </div>
                                <h3 class="text-base font-bold text-white hover:text-primary">{{ $article->title }}</h3>
                                <p class="text-xs text-gray-300 line-clamp-2">{{ $article->excerpt }}</p>
                            </div>
                            <span class="text-xs text-primary font-medium">Yazıyı Oku ({{ $article->author?->name }}) →</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Same Director's Other Films -->
        @if($film->director)
            <section class="bg-card border border-gray-800 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="text-lg font-semibold text-white">
                            Yönetmenin Diğer Filmleri: <span class="text-primary">{{ $film->director }}</span>
                        </h2>
                        <p class="text-xs text-gray-400">Aynı yönetmenin yönettiği diğer yapımlar</p>
                    </div>
                    <a
                        href="{{ route('home', ['director' => $film->director]) }}"
                        class="text-sm text-primary hover:underline font-medium"
                    >
                        Tümünü Listele →
                    </a>
                </div>

                @if($directorFilms->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($directorFilms as $relFilm)
                            <a
                                href="{{ route('films.show', $relFilm->slug) }}"
                                class="group bg-gray-800/60 border border-gray-700/60 rounded-xl overflow-hidden hover:-translate-y-1 transition-all duration-200 flex flex-col"
                            >
                                <div class="aspect-[2/3] bg-gray-900 overflow-hidden relative">
                                    <img
                                        src="{{ $relFilm->poster_url ?: 'https://placehold.co/300x450/1e293b/f1f5f9?text=' . urlencode($relFilm->title) }}"
                                        alt="{{ $relFilm->title }}"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='https://placehold.co/300x450/1e293b/f1f5f9?text=Cinema';"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    >
                                    @if($relFilm->year)
                                        <span class="absolute top-2 right-2 bg-black/75 text-xs px-2 py-0.5 rounded text-gray-200">{{ $relFilm->year }}</span>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h3 class="font-medium text-sm text-white group-hover:text-primary transition-colors line-clamp-1">{{ $relFilm->title }}</h3>
                                    <p class="text-gray-400 text-xs mt-1">{{ $relFilm->genre }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">Bu yönetmene ait başka film kaydı bulunamadı.</p>
                @endif
            </section>
        @endif

        <!-- Same Actors' Other Films -->
        @if(!empty($filmsByActor))
            <section class="bg-card border border-gray-800 rounded-2xl p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-white">Oyuncuların Rol Aldığı Diğer Filmler</h2>
                    <p class="text-xs text-gray-400">Bu filmdeki oyuncuların yer aldığı diğer yapımlar</p>
                </div>

                <div class="space-y-8">
                    @foreach($filmsByActor as $actorName => $actorFilms)
                        <div class="space-y-3 pt-4 first:pt-0 border-t first:border-t-0 border-gray-800">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-semibold text-gray-200">
                                    Oyuncu: <a href="{{ route('home', ['actor' => $actorName]) }}" class="text-primary hover:underline">{{ $actorName }}</a>
                                </h3>
                                <a
                                    href="{{ route('home', ['actor' => $actorName]) }}"
                                    class="text-xs text-primary hover:underline"
                                >
                                    {{ $actorName }} filmleri →
                                </a>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                @foreach($actorFilms as $actFilm)
                                    <a
                                        href="{{ route('films.show', $actFilm->slug) }}"
                                        class="group bg-gray-800/60 border border-gray-700/60 rounded-xl overflow-hidden hover:-translate-y-1 transition-all duration-200 flex flex-col"
                                    >
                                        <div class="aspect-[2/3] bg-gray-900 overflow-hidden relative">
                                            <img
                                                src="{{ $actFilm->poster_url ?: 'https://placehold.co/300x450/1e293b/f1f5f9?text=' . urlencode($actFilm->title) }}"
                                                alt="{{ $actFilm->title }}"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='https://placehold.co/300x450/1e293b/f1f5f9?text=Cinema';"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            >
                                            @if($actFilm->year)
                                                <span class="absolute top-2 right-2 bg-black/75 text-xs px-2 py-0.5 rounded text-gray-200">{{ $actFilm->year }}</span>
                                            @endif
                                        </div>
                                        <div class="p-2.5">
                                            <h4 class="font-medium text-xs text-white group-hover:text-primary transition-colors line-clamp-1">{{ $actFilm->title }}</h4>
                                            <p class="text-gray-400 text-[11px] mt-0.5 truncate">{{ $actFilm->director }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Film Reviews & Write Review -->
        <section class="bg-card border border-gray-800 rounded-2xl p-6 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Film Eleştirileri ({{ $film->reviews->count() }})</h2>
            </div>

            @auth
                <form id="filmReviewForm" class="bg-gray-800/50 border border-gray-700 rounded-xl p-4 space-y-4">
                    <input type="hidden" name="film_id" value="{{ $film->id }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <label class="text-sm font-medium text-gray-200">Bu Film İçin Eleştirinizi Yazın</label>
                        <div class="flex items-center gap-4">
                            <label class="inline-flex items-center gap-2 text-xs text-gray-300 cursor-pointer">
                                <input type="checkbox" name="contains_spoiler" value="1" class="rounded border-gray-600 text-primary focus:ring-primary">
                                <span>Spoiler İçeriyor</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <label for="ratingSelect" class="text-xs text-gray-400">Puanınız:</label>
                                <select
                                    id="ratingSelect"
                                    name="rating"
                                    class="bg-gray-900 border border-gray-600 rounded-lg px-3 py-1.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-primary"
                                    required
                                >
                                    @for($r = 10; $r >= 1; $r--)
                                        <option value="{{ $r }}" @selected($r === 8)>★ {{ $r }} / 10</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <textarea
                        name="content"
                        rows="3"
                        placeholder="Film hakkındaki düşüncelerinizi, sinematografi ve oyunculuk analizlerinizi paylaşın..."
                        class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    ></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-sm btn-primary">Eleştiriyi Gönder</button>
                    </div>
                </form>
            @else
                <div class="bg-gray-800/40 border border-gray-700/60 rounded-xl p-4 flex items-center justify-between flex-wrap gap-4">
                    <p class="text-sm text-gray-300">Bu filme eleştiri yazmak ve puan vermek için giriş yapın.</p>
                    <button type="button" class="btn btn-sm btn-primary" onclick="showLoginModal()">Giriş Yap</button>
                </div>
            @endauth

            <div class="space-y-4" id="filmReviewsList">
                @php
                    $langBadges = [
                        'tr' => 'Türkçe',
                        'en' => 'English',
                        'fr' => 'Français',
                        'de' => 'Deutsch',
                        'es' => 'Español',
                        'it' => 'Italiano',
                        'ja' => '日本語',
                    ];
                @endphp
                @forelse($film->reviews as $review)
                    <article
                        class="bg-gray-800/60 border border-gray-700/70 rounded-xl p-4 space-y-3"
                        data-review-id="{{ $review->id }}"
                        data-original-lang="{{ $review->language ?? 'tr' }}"
                        data-original-content="{{ $review->content }}"
                        data-translations="{{ json_encode($review->translations ?? [], JSON_UNESCAPED_UNICODE) }}"
                    >
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-sm text-white">{{ $review->user?->name ?? 'Anonim' }}</span>
                                <span class="text-[11px] bg-gray-900 border border-gray-700 text-gray-300 px-2 py-0.5 rounded">
                                    Orijinal Dil: {{ $langBadges[$review->language ?? 'tr'] ?? strtoupper($review->language ?? 'tr') }}
                                </span>
                                <span class="text-xs text-gray-400">• {{ $review->created_at?->diffForHumans() }}</span>
                                @if($review->contains_spoiler)
                                    <span class="text-[11px] bg-danger/20 text-danger px-2 py-0.5 rounded font-semibold uppercase">Spoiler</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @auth
                                    <button
                                        type="button"
                                        onclick="toggleReviewLike({{ $review->id }}, this)"
                                        class="text-xs px-2.5 py-1 rounded-full border border-gray-700 hover:border-primary text-gray-300 hover:text-primary transition-colors cursor-pointer"
                                    >
                                        ♥ <span class="like-count">{{ $review->likes_count ?? 0 }}</span>
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">♥ {{ $review->likes_count ?? 0 }}</span>
                                @endauth
                                <span class="bg-secondary/20 text-secondary text-xs font-semibold px-2.5 py-1 rounded-full">
                                    ★ {{ $review->rating }} / 10
                                </span>
                            </div>
                        </div>

                        @if($review->contains_spoiler)
                            <div class="relative">
                                <p id="spoiler-text-{{ $review->id }}" class="review-content-text text-gray-200 text-sm leading-relaxed blur-sm select-none transition-all">
                                    {{ $review->content }}
                                </p>
                                <button
                                    type="button"
                                    onclick="document.getElementById('spoiler-text-{{ $review->id }}').classList.remove('blur-sm', 'select-none'); this.remove();"
                                    class="mt-2 text-xs bg-danger/20 text-danger hover:bg-danger/30 px-3 py-1 rounded-lg font-medium cursor-pointer"
                                >
                                    Spoiler İçerir — Okumak İçin Tıklayın
                                </button>
                            </div>
                        @else
                            <p class="review-content-text text-gray-200 text-sm leading-relaxed">{{ $review->content }}</p>
                        @endif

                        <!-- Per-review multi-language translation buttons -->
                        @if(!empty($review->translations))
                            <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                <span class="text-[11px] text-gray-400">🌐 Yorumu Çevir:</span>
                                <button
                                    type="button"
                                    onclick="translateReviewCard({{ $review->id }}, 'orig')"
                                    class="text-[11px] px-2 py-0.5 rounded bg-gray-900 hover:bg-gray-700 text-gray-300 border border-gray-700 cursor-pointer"
                                >
                                    Orijinal ({{ strtoupper($review->language ?? 'tr') }})
                                </button>
                                @foreach(['tr' => 'TR', 'en' => 'EN', 'fr' => 'FR', 'de' => 'DE', 'es' => 'ES', 'it' => 'IT', 'ja' => 'JA'] as $lCode => $lLabel)
                                    @if(!empty($review->translations[$lCode]))
                                        <button
                                            type="button"
                                            onclick="translateReviewCard({{ $review->id }}, '{{ $lCode }}')"
                                            class="text-[11px] px-2 py-0.5 rounded bg-gray-900 hover:bg-primary/20 text-gray-300 hover:text-primary border border-gray-700 cursor-pointer"
                                        >
                                            {{ $lLabel }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if($review->comments->isNotEmpty())
                            <div class="pl-4 border-l-2 border-gray-700 space-y-2 pt-2">
                                @foreach($review->comments as $comment)
                                    <div
                                        class="text-xs bg-gray-900/60 rounded-lg p-2.5 space-y-1"
                                        data-comment-id="{{ $comment->id }}"
                                        data-original-content="{{ $comment->content }}"
                                        data-translations="{{ json_encode($comment->translations ?? [], JSON_UNESCAPED_UNICODE) }}"
                                    >
                                        <div class="flex items-center justify-between gap-2">
                                            <div>
                                                <span class="font-medium text-primary">{{ $comment->user?->name ?? 'Kullanıcı' }}</span>
                                                <span class="text-[10px] text-gray-400 ml-1">({{ $langBadges[$comment->language ?? 'tr'] ?? strtoupper($comment->language ?? 'tr') }}):</span>
                                            </div>
                                        </div>
                                        <p class="comment-content-text text-gray-200">{{ $comment->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-gray-400 py-4">Bu film için henüz eleştiri yazılmamış. İlk eleştiriyi siz yazın!</p>
                @endforelse
            </div>
        </section>

    </main>

    <!-- Cinema Mode Trailer Lightbox Modal -->
    <div id="trailerLightboxModal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 hidden items-center justify-center p-4" onclick="closeTrailerLightbox()">
        <div class="relative w-full max-w-4xl bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
                <span class="text-xs font-bold uppercase tracking-wider text-primary">🎬 Sinema Modu — {{ $film->title }} Fragman</span>
                <button type="button" onclick="closeTrailerLightbox()" class="text-gray-400 hover:text-white text-xl leading-none cursor-pointer">&times;</button>
            </div>
            <div class="aspect-video w-full bg-black">
                <iframe
                    id="trailerLightboxIframe"
                    src=""
                    title="{{ $film->title }} Fragman"
                    class="w-full h-full"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openTrailerLightbox(embedUrl) {
    const modal = document.getElementById('trailerLightboxModal');
    const iframe = document.getElementById('trailerLightboxIframe');
    if (modal && iframe) {
        iframe.src = embedUrl + (embedUrl.includes('?') ? '&autoplay=1' : '?autoplay=1');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeTrailerLightbox() {
    const modal = document.getElementById('trailerLightboxModal');
    const iframe = document.getElementById('trailerLightboxIframe');
    if (modal && iframe) {
        iframe.src = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
window.openTrailerLightbox = openTrailerLightbox;
window.closeTrailerLightbox = closeTrailerLightbox;
const FILM_TRANSLATIONS = {!! json_encode($film->translations ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
const FILM_DEFAULT_TITLE = {!! json_encode($film->title, JSON_UNESCAPED_UNICODE) !!};
const FILM_DEFAULT_DESC = {!! json_encode($film->description ?: 'Bu film için henüz özet bilgisi girilmemiştir.', JSON_UNESCAPED_UNICODE) !!};

function translateReviewCard(reviewId, lang) {
    const article = document.querySelector(`article[data-review-id="${reviewId}"]`);
    if (!article) return;

    const textEl = article.querySelector('.review-content-text');
    if (!textEl) return;

    if (lang === 'orig') {
        textEl.textContent = article.getAttribute('data-original-content') || '';
        return;
    }

    try {
        const translations = JSON.parse(article.getAttribute('data-translations') || '{}');
        if (translations[lang]) {
            textEl.textContent = translations[lang];
        }
    } catch (e) {
        console.error(e);
    }
}

function applyFilmPageLanguage(lang) {
    const titleEl = document.getElementById('filmMainTitle');
    const descEl = document.getElementById('filmMainDescription');
    const badgeEl = document.getElementById('synopsisLangBadge');

    if (titleEl) {
        titleEl.textContent = FILM_TRANSLATIONS[lang]?.title || FILM_DEFAULT_TITLE;
    }
    if (descEl) {
        descEl.textContent = FILM_TRANSLATIONS[lang]?.description || FILM_DEFAULT_DESC;
    }
    if (badgeEl) {
        badgeEl.textContent = lang.toUpperCase();
    }

    document.querySelectorAll('article[data-review-id]').forEach(article => {
        const reviewId = article.getAttribute('data-review-id');
        translateReviewCard(reviewId, lang);
    });

    document.querySelectorAll('[data-comment-id]').forEach(commentEl => {
        const textEl = commentEl.querySelector('.comment-content-text');
        if (!textEl) return;
        try {
            const translations = JSON.parse(commentEl.getAttribute('data-translations') || '{}');
            textEl.textContent = translations[lang] || commentEl.getAttribute('data-original-content') || '';
        } catch (e) {}
    });
}

window.addEventListener('siteLanguageChanged', (e) => {
    if (e.detail?.lang) {
        applyFilmPageLanguage(e.detail.lang);
    }
});

function getAuthHeaders() {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
    const token = localStorage.getItem('authToken');
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }
    return headers;
}

async function toggleWatchlist(filmId) {
    try {
        const response = await fetch(`/api/films/${filmId}/watchlist`, {
            method: 'POST',
            headers: getAuthHeaders(),
        });
        const data = await response.json();
        if (response.ok && data.success) {
            const btn = document.getElementById('watchlistBtn');
            const text = document.getElementById('watchlistBtnText');
            if (text) text.textContent = data.in_watchlist ? '✓ Listemde' : '+ İzleme Listesi';
            if (btn) {
                btn.classList.toggle('bg-primary', data.in_watchlist);
                btn.classList.toggle('text-white', data.in_watchlist);
                btn.classList.toggle('btn-outline', !data.in_watchlist);
            }
        }
    } catch (error) {
        console.error('Watchlist error:', error);
    }
}

async function toggleWatchedLog(filmId) {
    try {
        const response = await fetch(`/api/films/${filmId}/log`, {
            method: 'POST',
            headers: getAuthHeaders(),
        });
        const data = await response.json();
        if (response.ok && data.success) {
            const btn = document.getElementById('watchedBtn');
            const text = document.getElementById('watchedBtnText');
            if (text) text.textContent = data.watched ? '✓ İzlendi' : '👁 İzledim';
            if (btn) {
                btn.classList.toggle('bg-success', data.watched);
                btn.classList.toggle('text-white', data.watched);
                btn.classList.toggle('btn-outline', !data.watched);
            }
        }
    } catch (error) {
        console.error('Watched log error:', error);
    }
}

async function toggleReviewLike(reviewId, btnElement) {
    try {
        const response = await fetch(`/api/reviews/${reviewId}/like`, {
            method: 'POST',
            headers: getAuthHeaders(),
        });
        const data = await response.json();
        if (response.ok && data.success) {
            const countSpan = btnElement.querySelector('.like-count');
            if (countSpan) countSpan.textContent = data.likes_count;
        }
    } catch (error) {
        console.error('Like error:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('siteLang');
    if (savedLang) {
        applyFilmPageLanguage(savedLang);
    }

    const reviewForm = document.getElementById('filmReviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const payload = {
                film_id: Number(formData.get('film_id')),
                rating: Number(formData.get('rating')),
                content: formData.get('content'),
                contains_spoiler: formData.get('contains_spoiler') === '1',
            };

            try {
                const response = await fetch('/api/reviews', {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify(payload),
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Eleştiri gönderilemedi.');
                }
            } catch (error) {
                console.error('Review submit error:', error);
                alert('Bir hata oluştu.');
            }
        });
    }
});
</script>
@endpush
@endsection

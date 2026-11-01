@extends('layouts.app')

@section('title', 'Teknik Oyun Araçları: En İyi Monitörler, Oyun Konsolları ve AI Donanımları | GamePL')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-10">

    <!-- Üst Başlık & Kategori Filtreleri -->
    <header class="rounded-3xl border border-amber-500/35 bg-gradient-to-br from-[#14110B] via-[#0B0F17] to-[#081316] p-8 space-y-6">
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
            <div>
                <span class="font-mono-tech text-xs font-semibold uppercase tracking-widest text-amber-400">
                    // GAMING HARDWARE, DISPLAYS, CONSOLES & NEURAL ACCELERATORS
                </span>
                <h1 class="mt-2 font-display text-3xl font-bold text-white sm:text-5xl">
                    Teknik Oyun Araçları & Donanım Rehberi
                </h1>
                <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-300">
                    Oyun motorlarının ve yapay zekâ destekli görüntü işleme (DLSS 3.5, PSSR, Path Tracing) teknolojilerinin gerçek potansiyelini ortaya çıkaran <strong class="text-amber-300">en iyi QD-OLED / 540Hz oyuncu monitörleri</strong>, <strong class="text-emerald-300">yeni nesil oyun konsolları</strong>, <strong class="text-cyan-300">taşınabilir PC'ler</strong> ve <strong class="text-violet-300">düşük gecikmeli e-spor ekipmanları</strong>.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 pt-2">
            @php
                $categories = [
                    '' => 'Tüm Donanımlar (' . \App\Models\GamingHardware::where('status', 'approved')->count() . ')',
                    'monitor' => '🖥️ En İyi Monitörler (OLED & 540Hz)',
                    'console' => '🎮 En İyi Oyun Konsolları & El PC\'leri',
                    'gpu_ai' => '⚡ Grafik Kartları & Nöral AI (GPU)',
                    'vr_headset' => '🥽 Sanal Gerçeklik (VR / XR)',
                    'peripherals' => '⌨️ Kontrolcü & E-Spor Ekipmanları',
                ];
            @endphp
            @foreach($categories as $catKey => $catLabel)
                <a href="{{ route('hardware.index', $catKey ? ['category' => $catKey] : []) }}"
                    class="rounded-xl border px-3.5 py-2 font-mono-tech text-xs font-medium transition {{ $category === $catKey ? 'border-amber-400 bg-amber-500/20 text-amber-200 shadow-lg shadow-amber-500/10' : 'border-slate-800 bg-slate-900/90 text-slate-300 hover:border-slate-700 hover:text-white' }}">
                    {{ $catLabel }}
                </a>
            @endforeach
        </div>
    </header>

    <!-- Donanım Tanıtım Kartları -->
    <div class="grid gap-8 lg:grid-cols-2">
        @foreach($hardwareItems as $hw)
            <article class="rounded-3xl border border-slate-800 bg-[#0B0F17] overflow-hidden flex flex-col justify-between hover:border-amber-500/50 transition shadow-xl">
                <div>
                    <div class="relative h-56 overflow-hidden bg-slate-950">
                        <img src="{{ $hw->image_url }}" alt="{{ $hw->name }}" class="h-full w-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-[#0B0F17]/40 to-transparent"></div>
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="rounded-full border border-amber-500/40 bg-slate-950/90 px-3 py-1 font-mono-tech text-xs font-semibold text-amber-300">
                                {{ $hw->badge_label ?: $hw->categoryLabel() }}
                            </span>
                        </div>
                        <div class="absolute top-4 right-4 rounded-xl border border-emerald-500/40 bg-slate-950/90 px-3 py-1 font-mono-tech text-sm font-bold text-emerald-300">
                            ★ {{ number_format($hw->rating, 1) }} / 10
                        </div>
                        <div class="absolute bottom-3 left-5 right-5">
                            <span class="font-mono-tech text-[11px] text-cyan-300">{{ $hw->manufacturer }} • {{ $hw->release_year }} • {{ $hw->price_tier }}</span>
                            <a href="{{ route('hardware.show', $hw->slug) }}" class="mt-0.5 block font-display text-xl font-bold text-white hover:text-amber-300 transition">
                                {{ $hw->name }}
                            </a>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <p class="text-xs leading-relaxed text-slate-200 sm:text-sm">
                            {{ $hw->short_intro }}
                        </p>

                        <!-- Öne Çıkan Teknik Özellikler Tablosu -->
                        @if(!empty($hw->key_specs))
                            <div class="rounded-2xl border border-slate-800/90 bg-slate-900/70 p-4 space-y-2">
                                <div class="font-mono-tech text-[10px] font-semibold uppercase tracking-wider text-amber-400">
                                    ⚙ Teknik Donanım Özellikleri
                                </div>
                                <dl class="grid gap-1.5 text-xs">
                                    @foreach(array_slice($hw->key_specs, 0, 4, true) as $specKey => $specVal)
                                        <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-800/60 pb-1 last:border-0 last:pb-0">
                                            <dt class="font-mono-tech text-[11px] text-slate-400">{{ $specKey }}:</dt>
                                            <dd class="font-medium text-slate-200 sm:text-right">{{ $specVal }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                        @endif

                        @if($hw->ai_and_game_synergy)
                            <div class="rounded-2xl border border-emerald-500/25 bg-emerald-950/20 p-3.5 text-xs text-slate-200">
                                <strong class="font-mono-tech text-emerald-400 block mb-1">🧠 Oyun Teknolojileri & AI Sinerjisi:</strong>
                                {{ $hw->ai_and_game_synergy }}
                            </div>
                        @endif

                        @if($hw->games->isNotEmpty())
                            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                <span class="font-mono-tech text-[10px] text-slate-400">Önerilen Oyunlar:</span>
                                @foreach($hw->games as $linkedGame)
                                    <a href="{{ route('games.show', $linkedGame->slug) }}" class="rounded-md border border-slate-700 bg-slate-900 px-2 py-0.5 font-mono-tech text-[10px] text-cyan-300 hover:border-cyan-400">
                                        {{ $linkedGame->title }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-slate-800/80 px-6 py-4 flex items-center justify-between bg-[#080B12]">
                    <a href="{{ route('hardware.show', $hw->slug) }}" class="text-xs font-semibold text-amber-300 hover:underline">
                        Detaylı Donanım İncelemesi & Artı/Eksiler →
                    </a>
                    @if($hw->embedVideoUrl())
                        <button type="button"
                            onclick="openVideoModal('{{ $hw->embedVideoUrl() }}', '{{ addslashes($hw->name) }} — Donanım & Oyun Testi', 'TEKNİK DONANIM VİDEOSU')"
                            class="rounded-xl border border-cyan-500/40 bg-cyan-500/10 px-3 py-1.5 font-mono-tech text-[11px] text-cyan-300 hover:bg-cyan-500/20">
                            ▶ Test Videosu
                        </button>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

</div>
@endsection

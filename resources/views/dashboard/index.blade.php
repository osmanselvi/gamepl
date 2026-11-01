@extends('layouts.app')

@section('title', '5 Rol Dashboard (' . $user->roleLabel() . ') | GamePL')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-10">

    <!-- Aktif Rol Başlığı -->
    <header class="rounded-3xl border border-emerald-500/35 bg-[#0B0F17] p-6 sm:p-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <span class="rounded-full border border-emerald-500/40 bg-emerald-500/15 px-3 py-1 font-mono-tech text-xs font-semibold text-emerald-300">
                AKTİF ROL: {{ strtoupper($user->role->value) }} — {{ $user->roleLabel() }}
            </span>
            <h1 class="mt-3 font-display text-2xl font-bold text-white sm:text-3xl">
                Hoş Geldiniz, {{ $user->name }}
            </h1>
            <p class="mt-1 text-xs text-slate-400">
                {{ $user->bio ?: 'GamePL 5 rollü oyun, teknoloji, yapay zekâ analizi ve örnek video yönetim merkezi.' }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('profile') }}" class="rounded-xl border border-slate-700 bg-slate-900 px-4 py-2 text-xs font-semibold text-slate-200 hover:border-emerald-500/40">
                Oyuncu Belleğim & Favori 4 Oyun →
            </a>
        </div>
    </header>

    <!-- 1. MODERASYON KUYRUĞU (Admin & WebAdmin) -->
    @if($user->canApproveContent())
    <section class="rounded-2xl border border-amber-500/35 bg-[#0B0F17] p-6 space-y-6">
        <h2 class="font-display text-xl font-bold text-amber-300">🛡️ Editör & Sistem Moderasyon Kuyruğu (Admin / WebAdmin)</h2>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 space-y-3">
                <h3 class="font-mono-tech text-xs font-semibold text-white">Onay Bekleyen Oyunlar ({{ $pendingGames->count() }})</h3>
                @forelse($pendingGames as $pg)
                    <div class="flex items-center justify-between border-t border-slate-800 pt-2 text-xs">
                        <span>{{ $pg->title }} ({{ $pg->release_year }})</span>
                        <form action="{{ route('dashboard.games.approve', $pg->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded bg-emerald-500 px-2.5 py-1 font-semibold text-slate-950">Onayla</button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Bekleyen oyun kaydı yok.</p>
                @endforelse
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 space-y-3">
                <h3 class="font-mono-tech text-xs font-semibold text-white">Onay Bekleyen AI Analizleri ({{ $pendingAnalyses->count() }})</h3>
                @forelse($pendingAnalyses as $pa)
                    <div class="flex items-center justify-between border-t border-slate-800 pt-2 text-xs">
                        <span>{{ $pa->title }}</span>
                        <form action="{{ route('dashboard.ai.approve', $pa->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded bg-emerald-500 px-2.5 py-1 font-semibold text-slate-950">Onayla</button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Bekleyen AI analizi yok.</p>
                @endforelse
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 space-y-3">
                <h3 class="font-mono-tech text-xs font-semibold text-white">Onay Bekleyen Örnek Videolar ({{ $pendingVideos->count() }})</h3>
                @forelse($pendingVideos as $pv)
                    <div class="flex items-center justify-between border-t border-slate-800 pt-2 text-xs">
                        <span>{{ $pv->title }}</span>
                        <form action="{{ route('dashboard.videos.approve', $pv->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded bg-emerald-500 px-2.5 py-1 font-semibold text-slate-950">Onayla</button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Bekleyen örnek video yok.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    <!-- 2. YENİ OYUN, AI ANALİZİ VE ÖRNEK VİDEO EKLEME FORMLARI -->
    <div class="grid gap-8 lg:grid-cols-2">

        @if($user->canSubmitAiAnalyses())
        <section class="rounded-2xl border border-emerald-500/30 bg-[#0B0F17] p-6 space-y-4">
            <h2 class="font-display text-lg font-bold text-emerald-400">🧠 Yeni Oyun & Yapay Zekâ Vaka Analizi Ekle</h2>
            <form action="{{ route('dashboard.ai.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-slate-400 mb-1">Oyun Seçin</label>
                        <select name="game_id" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                            @foreach($approvedGames as $g)
                                <option value="{{ $g->id }}">{{ $g->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Yapay Zekâ Mimarisi</label>
                        <select name="ai_system_id" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                            @foreach($aiSystems as $sys)
                                <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="text" name="title" placeholder="Analiz Başlığı (Örn: Elden Ring — Girdi Okuma ve Gecikmeli Saldırı Ağaçları)" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="text" name="ai_role_in_game" placeholder="Oyun İçi AI Rolü (Örn: Boss Davranış Ağacı & Taktiksel Konumlanma)" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <textarea name="architecture_breakdown" rows="3" placeholder="Mimari çözümleme ve algoritmik detaylar..." required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white"></textarea>
                <div class="grid grid-cols-3 gap-2">
                    <input type="number" name="innovation_score" value="94" min="50" max="100" placeholder="İnovasyon (50-100)" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                    <input type="number" name="complexity_score" value="92" min="50" max="100" placeholder="Karmaşıklık (50-100)" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                    <input type="url" name="video_evidence_url" placeholder="YouTube Kanıt Videosu URL" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                </div>
                <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2.5 font-semibold text-slate-950 hover:bg-emerald-400">
                    AI Vaka Analizini Gönder
                </button>
            </form>
        </section>
        @endif

        @if($user->canSubmitVideos())
        <section class="rounded-2xl border border-cyan-500/30 bg-[#0B0F17] p-6 space-y-4">
            <h2 class="font-display text-lg font-bold text-cyan-400">🎬 Yeni Örnek Oynanış / AI Analiz Videosu Ekle</h2>
            <form action="{{ route('dashboard.videos.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-slate-400 mb-1">İlgili Oyun</label>
                        <select name="game_id" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                            @foreach($approvedGames as $g)
                                <option value="{{ $g->id }}">{{ $g->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Video Türü</label>
                        <select name="video_type" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                            <option value="gameplay_sample">Örnek Oynanış Kesiti (Gameplay)</option>
                            <option value="ai_showcase">Yapay Zekâ & NPC Analiz Videosu</option>
                            <option value="tech_breakdown">Oyun Motoru & Grafik İncelemesi</option>
                            <option value="trailer">Resmi Fragman / Sinematik</option>
                        </select>
                    </div>
                </div>
                <input type="text" name="title" placeholder="Video Başlığı..." required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="url" name="video_url" placeholder="YouTube Video URL (https://www.youtube.com/watch?v=...)" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="duration_label" value="08:45" placeholder="Süre (08:45)" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                    <input type="text" name="resolution_tag" value="4K 60FPS" placeholder="Çözünürlük (4K 60FPS)" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                </div>
                <textarea name="description" rows="2" placeholder="Videodaki teknik ve oynanış vurguları..." required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white"></textarea>
                <button type="submit" class="rounded-xl bg-cyan-500 px-5 py-2.5 font-semibold text-slate-950 hover:bg-cyan-400">
                    Örnek Videoyu Kaydet
                </button>
            </form>
        </section>
        @endif

        @if($user->canSubmitGames() || $user->canApproveContent())
        <section class="rounded-2xl border border-amber-500/30 bg-[#0B0F17] p-6 space-y-4 lg:col-span-2">
            <h2 class="font-display text-lg font-bold text-amber-300">🖥️ Yeni Teknik Oyun Donanımı (Monitör / Konsol / GPU / Ekipman) Tanıtımı Ekle</h2>
            <form action="{{ route('dashboard.hardware.store') }}" method="POST" class="grid gap-3 sm:grid-cols-3 text-xs">
                @csrf
                <input type="text" name="name" placeholder="Donanım Adı (Örn: LG UltraGear 32GS95UE Dual-Mode OLED)" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <select name="category" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                    <option value="monitor">🖥️ Oyuncu Monitörü (OLED / Yüksek Hz)</option>
                    <option value="console">🎮 Oyun Konsolu & El Bilgisayarı</option>
                    <option value="gpu_ai">⚡ Grafik Kartı & Nöral AI Hızlandırıcı</option>
                    <option value="vr_headset">🥽 Sanal Gerçeklik (VR / XR) Başlığı</option>
                    <option value="peripherals">⌨️ Kontrolcü & E-Spor Ekipmanı</option>
                </select>
                <input type="text" name="manufacturer" placeholder="Üretici (Örn: LG / Sony / NVIDIA)" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="number" name="release_year" value="2024" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="text" name="price_tier" value="Amiral Gemisi (Flagship)" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="number" step="0.1" min="1" max="10" name="rating" value="9.6" required class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="text" name="badge_label" placeholder="Rozet (Örn: 🏆 En İyi 4K 240Hz / FHD 480Hz Monitör)" class="sm:col-span-2 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <input type="url" name="showcase_video_url" placeholder="Tanıtım Videosu URL (YouTube)" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white">
                <textarea name="short_intro" rows="2" placeholder="Kısa tanıtım özeti..." required class="sm:col-span-3 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white"></textarea>
                <textarea name="technical_deep_dive" rows="2" placeholder="Teknik mimari ve mühendislik detayları..." required class="sm:col-span-2 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white"></textarea>
                <textarea name="ai_and_game_synergy" rows="2" placeholder="Oyun teknolojileri ve yapay zekâ ile sinerjisi..." class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white"></textarea>
                <div class="sm:col-span-3">
                    <button type="submit" class="rounded-xl bg-amber-400 px-5 py-2.5 font-semibold text-slate-950 hover:bg-amber-300">
                        Donanım Tanıtımını Yayınla
                    </button>
                </div>
            </form>
        </section>
        @endif

    </div>

    <!-- 3. KULLANICI ROL YÖNETİMİ (Sadece Admin) -->
    @if($user->canManageUsers())
    <section class="rounded-2xl border border-rose-500/30 bg-[#0B0F17] p-6 space-y-4">
        <h2 class="font-display text-lg font-bold text-rose-300">👑 Kullanıcı ve 5 Rol Yönetimi (Sadece Sistem Yöneticisi)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="border-b border-slate-800 font-mono-tech text-[11px] uppercase text-slate-400">
                    <tr>
                        <th class="py-2">Ad Soyad</th>
                        <th class="py-2">E-Posta</th>
                        <th class="py-2">Mevcut Rol</th>
                        <th class="py-2">Rol Değiştir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @foreach($allUsers as $member)
                        <tr>
                            <td class="py-2.5 font-semibold text-white">{{ $member->name }}</td>
                            <td class="py-2.5 font-mono-tech">{{ $member->email }}</td>
                            <td class="py-2.5">{{ $member->roleLabel() }}</td>
                            <td class="py-2.5">
                                <form action="{{ route('dashboard.users.role', $member->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="rounded border border-slate-700 bg-slate-900 px-2 py-1 text-xs text-white">
                                        @foreach(\App\Enums\UserRole::cases() as $roleCase)
                                            <option value="{{ $roleCase->value }}" @selected($member->role === $roleCase)>{{ $roleCase->label() }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded bg-rose-500/20 border border-rose-500/40 px-2.5 py-1 text-rose-300 hover:bg-rose-500/30">
                                        Güncelle
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

</div>
@endsection

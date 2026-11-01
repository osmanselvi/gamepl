<!DOCTYPE html>
<html lang="tr" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GamePL — Dijital Oyun Kültürü, Oyun Teknolojileri ve Yapay Zekâ Platformu')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #070A0F;
            color: #E2E8F0;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .font-mono-tech {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="min-h-screen bg-[#070A0F] text-slate-100 antialiased selection:bg-emerald-500 selection:text-slate-950 flex flex-col">

    <!-- Üst Bilgi & Hızlı Rol Geçiş Çubuğu -->
    <div class="border-b border-slate-800/80 bg-[#05070B] text-xs text-slate-400">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2 px-4 py-2 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-0.5 font-mono-tech text-[11px] font-medium text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    GAMEPL ENGINE & AI LAB v1.0
                </span>
                <span class="hidden md:inline text-slate-400">Dijital Oyunlar • Oyun Motorları • Yapay Zekâ Mimarileri • Örnek Oynanış Videoları</span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[11px] text-slate-400 hidden sm:inline">5 Rol Hızlı Test:</span>
                @php
                    $quickRoles = [
                        ['label' => 'Admin', 'email' => 'admin@gamepl.local', 'color' => 'border-rose-500/40 text-rose-300 hover:bg-rose-500/15'],
                        ['label' => 'WebAdmin', 'email' => 'webadmin@gamepl.local', 'color' => 'border-amber-500/40 text-amber-300 hover:bg-amber-500/15'],
                        ['label' => 'Stüdyo', 'email' => 'studyo@gamepl.local', 'color' => 'border-sky-500/40 text-sky-300 hover:bg-sky-500/15'],
                        ['label' => 'Geliştirici/AI', 'email' => 'gelistirici@gamepl.local', 'color' => 'border-emerald-500/40 text-emerald-300 hover:bg-emerald-500/15'],
                        ['label' => 'Oyuncu', 'email' => 'oyuncu@gamepl.local', 'color' => 'border-violet-500/40 text-violet-300 hover:bg-violet-500/15'],
                    ];
                @endphp
                @foreach($quickRoles as $qr)
                    <form action="{{ route('login.quick-role') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $qr['email'] }}">
                        <button type="submit" class="rounded border px-2 py-0.5 font-mono-tech text-[10px] transition {{ $qr['color'] }}">
                            {{ $qr['label'] }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Ana Navigasyon -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 bg-[#070A0F]/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-500/40 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 font-display text-lg font-bold text-emerald-400 shadow-lg shadow-emerald-500/10 group-hover:border-emerald-400 transition">
                        G<span class="text-cyan-400">P</span>
                    </div>
                    <div>
                        <span class="font-display text-xl font-bold tracking-tight text-white">Game<span class="text-emerald-400">PL</span></span>
                        <span class="block font-mono-tech text-[10px] uppercase tracking-widest text-slate-400">Games • Tech • AI Lab</span>
                    </div>
                </a>

                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('home') || request()->routeIs('games.*') ? 'bg-slate-800/90 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        Oyun Kataloğu
                    </a>
                    <a href="{{ route('ai.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium transition flex items-center gap-1.5 {{ request()->routeIs('ai.*') ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Teknoloji & AI İlişkisi
                    </a>
                    <a href="{{ route('videos.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('videos.*') ? 'bg-slate-800/90 text-cyan-400' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        Örnek Videolar
                    </a>
                    <a href="{{ route('hardware.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium transition flex items-center gap-1.5 {{ request()->routeIs('hardware.*') ? 'bg-amber-500/15 text-amber-300 border border-amber-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        <span>🖥️</span>
                        Donanım & Konsollar
                    </a>
                    <a href="{{ route('interviews.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('interviews.*') ? 'bg-slate-800/90 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        Geliştirici Söyleşileri
                    </a>
                    <a href="{{ route('articles.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('articles.*') ? 'bg-slate-800/90 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        Teknik Makaleler
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('profile') }}" class="rounded-lg border border-slate-700/80 bg-slate-900/80 px-3 py-1.5 text-xs font-medium text-slate-200 hover:border-slate-600 hover:text-white transition">
                        Oyuncu Belleği
                    </a>
                    <a href="{{ route('dashboard') }}" class="rounded-lg border border-emerald-500/40 bg-emerald-500/15 px-3.5 py-1.5 text-xs font-semibold text-emerald-300 hover:bg-emerald-500/25 transition">
                        Dashboard ({{ auth()->user()->role->value }})
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-800 px-2.5 py-1.5 text-xs text-slate-400 hover:border-rose-500/40 hover:text-rose-300 transition">
                            Çıkış
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg border border-slate-700 px-3.5 py-1.5 text-xs font-medium text-slate-200 hover:border-emerald-500/50 hover:text-white transition">
                        Giriş Yap
                    </a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-emerald-500 px-3.5 py-1.5 text-xs font-semibold text-slate-950 hover:bg-emerald-400 transition">
                        Kayıt Ol
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('status'))
        <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Evrensel Sinema Modu Video & AI Analiz Oynatıcı Modalı -->
    <div id="videoLightboxModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/85 p-4 backdrop-blur-md">
        <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-slate-700/80 bg-[#0B0F17] shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-800 px-5 py-3.5">
                <div>
                    <span id="videoModalBadge" class="font-mono-tech text-[11px] uppercase tracking-wider text-emerald-400">ÖRNEK OYNANIŞ & AI VİDEOSU</span>
                    <h3 id="videoModalTitle" class="font-display text-base font-bold text-white">Video Oynatıcı</h3>
                </div>
                <button type="button" onclick="closeVideoModal()" class="rounded-lg border border-slate-700 px-3 py-1 text-xs font-medium text-slate-300 hover:border-rose-500/50 hover:text-rose-300">
                    Kapat [ESC]
                </button>
            </div>
            <div class="aspect-video w-full bg-black">
                <iframe id="videoModalIframe" class="h-full w-full" src="" title="GamePL Video Player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <footer class="mt-20 border-t border-slate-800/80 bg-[#05070B] py-12 text-sm text-slate-400">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-4">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5">
                        <span class="font-display text-lg font-bold text-white">Game<span class="text-emerald-400">PL</span></span>
                        <span class="rounded border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 font-mono-tech text-[10px] text-emerald-400">MYSQL: gamepl_db</span>
                    </div>
                    <p class="mt-3 max-w-md text-xs leading-relaxed text-slate-400">
                        Dijital oyun kültürü, oyun motorları mühendisliği, oyun teknolojileri ile yapay zekâ (GOAP, Behavior Trees, Utility AI, Neural Rendering) arasındaki ilişkiyi ve örnek oynanış videolarını arşivleyen yeni nesil bilgi platformu.
                    </p>
                </div>
                <div>
                    <h4 class="font-mono-tech text-xs font-semibold uppercase tracking-wider text-slate-200">Modüller</h4>
                    <ul class="mt-3 space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400">Oyun Kataloğu & Keşif Motoru</a></li>
                        <li><a href="{{ route('ai.index') }}" class="hover:text-emerald-400">Oyun Teknolojileri ve AI Laboratuvarı</a></li>
                        <li><a href="{{ route('videos.index') }}" class="hover:text-emerald-400">Örnek Oynanış & AI Videoları</a></li>
                        <li><a href="{{ route('interviews.index') }}" class="hover:text-emerald-400">Geliştirici & AI Mimarı Söyleşileri</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-mono-tech text-xs font-semibold uppercase tracking-wider text-slate-200">5 Rol Mimarisi</h4>
                    <ul class="mt-3 space-y-1.5 text-xs text-slate-400">
                        <li><span class="text-rose-400">•</span> Admin (Sistem Yöneticisi)</li>
                        <li><span class="text-amber-400">•</span> WebAdmin (Teknoloji Editörü)</li>
                        <li><span class="text-sky-400">•</span> Stüdyo & Yayıncı Temsilcisi</li>
                        <li><span class="text-emerald-400">•</span> Oyun Geliştiricisi & AI Mühendisi</li>
                        <li><span class="text-violet-400">•</span> Oyuncu & Eleştirmen</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function openVideoModal(embedUrl, title, badge) {
            const modal = document.getElementById('videoLightboxModal');
            const iframe = document.getElementById('videoModalIframe');
            const titleEl = document.getElementById('videoModalTitle');
            const badgeEl = document.getElementById('videoModalBadge');

            if (!modal || !iframe) return;
            titleEl.textContent = title || 'Oyun Videosu';
            badgeEl.textContent = badge || 'ÖRNEK OYNANIŞ & AI VİDEOSU';
            iframe.src = embedUrl + (embedUrl.includes('?') ? '&autoplay=1' : '?autoplay=1');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoLightboxModal');
            const iframe = document.getElementById('videoModalIframe');
            if (!modal || !iframe) return;
            iframe.src = '';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeVideoModal();
        });
    </script>
</body>
</html>

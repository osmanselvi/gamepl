@extends('layouts.app')

@section('title', 'Giriş Yap & 5 Rol Hızlı Test | GamePL')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8 grid gap-8 md:grid-cols-2">
    <div class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 space-y-5">
        <h1 class="font-display text-2xl font-bold text-white">Giriş Yap</h1>
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-400 mb-1">E-Posta</label>
                <input type="email" name="email" value="admin@gamepl.local" required class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-slate-400 mb-1">Şifre (Demo: password)</label>
                <input type="password" name="password" value="password" required class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            </div>
            <button type="submit" class="w-full rounded-xl bg-emerald-500 py-2.5 font-semibold text-slate-950 hover:bg-emerald-400">
                Oturum Aç
            </button>
        </form>
    </div>

    <div class="rounded-3xl border border-emerald-500/30 bg-[#0B0F17] p-8 space-y-4">
        <h2 class="font-display text-xl font-bold text-emerald-400">⚡ Tek Tıkla 5 Rol Demo Girişi</h2>
        <p class="text-xs text-slate-400">Platformdaki 5 farklı yetki seviyesini tek tıkla test edebilirsiniz:</p>
        <div class="space-y-2.5">
            @foreach($demoUsers as $du)
                <form action="{{ route('login.quick-role') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $du->email }}">
                    <button type="submit" class="w-full rounded-xl border border-slate-800 bg-slate-900/90 p-3 text-left hover:border-emerald-500/50 transition flex items-center justify-between">
                        <div>
                            <div class="font-display text-xs font-bold text-white">{{ $du->name }}</div>
                            <div class="font-mono-tech text-[10px] text-slate-400">{{ $du->email }}</div>
                        </div>
                        <span class="rounded border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-1 font-mono-tech text-[10px] text-emerald-300">
                            {{ $du->roleLabel() }} →
                        </span>
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Kayıt Ol | GamePL')

@section('content')
<div class="mx-auto max-w-md px-4 py-12">
    <div class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-8 space-y-5">
        <h1 class="font-display text-2xl font-bold text-white">GamePL Topluluğuna Katıl</h1>
        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="text" name="name" placeholder="Adınız Soyadınız" required class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            <input type="email" name="email" placeholder="E-Posta Adresiniz" required class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            <input type="text" name="studio_name" placeholder="Stüdyo / Ekip Adı (Opsiyonel)" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            <input type="password" name="password" placeholder="Şifre" required class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            <input type="password" name="password_confirmation" placeholder="Şifre Tekrar" required class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3.5 py-2.5 text-white">
            <button type="submit" class="w-full rounded-xl bg-emerald-500 py-2.5 font-semibold text-slate-950 hover:bg-emerald-400">
                Hesap Oluştur
            </button>
        </form>
    </div>
</div>
@endsection

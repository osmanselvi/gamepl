<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case WebAdmin = 'webadmin';
    case Studyo = 'studyo';
    case Gelistirici = 'gelistirici';
    case Oyuncu = 'oyuncu';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Sistem Yöneticisi',
            self::WebAdmin => 'Web & Teknoloji Editörü',
            self::Studyo => 'Stüdyo & Yayıncı Temsilcisi',
            self::Gelistirici => 'Oyun Geliştiricisi & AI Mühendisi',
            self::Oyuncu => 'Oyuncu & Eleştirmen',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Admin => 'red',
            self::WebAdmin => 'amber',
            self::Studyo => 'blue',
            self::Gelistirici => 'emerald',
            self::Oyuncu => 'violet',
        };
    }
}

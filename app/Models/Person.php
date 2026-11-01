<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'primary_role',
        'birth_year',
        'nationality',
        'studio_affiliation',
        'bio',
        'photo_url',
        'signature_style',
        'notable_achievements',
    ];

    protected function casts(): array
    {
        return [
            'birth_year' => 'integer',
            'notable_achievements' => 'array',
        ];
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_person')
            ->withPivot(['credit_type', 'character_name', 'credit_order'])
            ->withTimestamps()
            ->orderByDesc('release_year');
    }

    public function directedGames(): BelongsToMany
    {
        return $this->games()->wherePivot('credit_type', 'director');
    }

    public function actedGames(): BelongsToMany
    {
        return $this->games()->wherePivot('credit_type', 'actor');
    }

    public function aiArchitectedGames(): BelongsToMany
    {
        return $this->games()->wherePivot('credit_type', 'ai_architect');
    }

    public function composedGames(): BelongsToMany
    {
        return $this->games()->wherePivot('credit_type', 'composer');
    }

    public function awards(): HasMany
    {
        return $this->hasMany(GameAward::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function roleLabel(): string
    {
        return match ($this->primary_role) {
            'director' => 'Oyun Yönetmeni & Tasarımcı',
            'actor' => 'Performans & Seslendirme Sanatçısı',
            'ai_architect' => 'Yapay Zekâ & Sistem Mimarı',
            'composer' => 'Oyun Müzikleri Bestecisi',
            'esports_player' => 'Profesyonel Oyuncu / E-Sporcu',
            default => 'Yaratıcı & Oyuncu',
        };
    }
}

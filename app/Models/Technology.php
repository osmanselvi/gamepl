<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'vendor',
        'introduced_year',
        'summary',
        'technical_deep_dive',
        'ai_integration_notes',
    ];

    protected function casts(): array
    {
        return [
            'introduced_year' => 'integer',
        ];
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_technology')
            ->withPivot('implementation_notes')
            ->withTimestamps();
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'rendering' => 'Gerçek Zamanlı Görselleştirme (Rendering)',
            'upscaling_ai' => 'Nöral Upscaling & Kare Üretimi (AI)',
            'physics' => 'Fizik & Yıkım Simülasyonu',
            'procedural' => 'Prosedürel Dünya & Doku Üretimi',
            'audio' => 'Uzamsal Ses & Akustik Motoru',
            'networking' => 'Ağ & Sunucu Mimarisi',
            'animation' => 'İskelet & Hareket Sentezi (Motion Matching)',
            default => 'Oyun Teknolojisi',
        };
    }
}

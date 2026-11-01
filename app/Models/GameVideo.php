<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'title',
        'slug',
        'video_type',
        'video_url',
        'thumbnail_url',
        'duration_label',
        'resolution_tag',
        'description',
        'technical_highlights',
        'is_featured',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function embedVideoUrl(): string
    {
        return Game::convertYoutubeToEmbed($this->video_url);
    }

    public function typeLabel(): string
    {
        return match ($this->video_type) {
            'gameplay_sample' => 'Örnek Oynanış Kesiti (Gameplay)',
            'ai_showcase' => 'Yapay Zekâ & NPC Analiz Videosu',
            'tech_breakdown' => 'Oyun Motoru & Grafik İncelemesi',
            'speedrun_pro' => 'E-Spor / Speedrun & Mekanik Gösterimi',
            'trailer' => 'Resmi Fragman / Sinematik Tanıtım',
            default => 'Oyun Videosu',
        };
    }

    public function typeBadgeColor(): string
    {
        return match ($this->video_type) {
            'gameplay_sample' => 'emerald',
            'ai_showcase' => 'violet',
            'tech_breakdown' => 'cyan',
            'speedrun_pro' => 'amber',
            'trailer' => 'rose',
            default => 'stone',
        };
    }
}

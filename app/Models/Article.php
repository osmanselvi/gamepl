<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'category',
        'author_id',
        'author_name',
        'author_title',
        'game_id',
        'person_id',
        'reading_time_minutes',
        'excerpt',
        'pull_quote',
        'body',
        'cover_url',
        'is_featured',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'reading_time_minutes' => 'integer',
            'is_featured' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'ai_deep_dive' => 'Yapay Zekâ & NPC Mimarisi',
            'engine_architecture' => 'Oyun Motoru & Grafik Mühendisliği',
            'game_design' => 'Oyun Tasarımı & Anlatı Teorisi',
            'esports_culture' => 'Rekabetçi Oyun & Oyuncu Kültürü',
            'industry_history' => 'Oyun Tarihi & Endüstri Analizi',
            default => 'Oyun Teknolojisi Dosyası',
        };
    }
}

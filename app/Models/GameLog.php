<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'play_status',
        'platform',
        'hours_played',
        'completed_on',
        'personal_rating',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hours_played' => 'integer',
            'completed_on' => 'date',
            'personal_rating' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function statusLabel(): string
    {
        return match ($this->play_status) {
            'playing' => 'Şu An Oynuyor',
            'completed' => 'Ana Hikâye Bitti',
            'mastered' => '%100 Tamamlandı (Platinum)',
            'backlog' => 'Sırada (Backlog)',
            'dropped' => 'Yarıda Bırakıldı',
            default => 'Oyun Günlüğü',
        };
    }
}

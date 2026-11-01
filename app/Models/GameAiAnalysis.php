<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameAiAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'ai_system_id',
        'author_id',
        'title',
        'slug',
        'ai_role_in_game',
        'architecture_breakdown',
        'perception_and_sensors',
        'decision_loop_details',
        'player_experience_impact',
        'limitations_and_tricks',
        'innovation_score',
        'complexity_score',
        'video_evidence_url',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'innovation_score' => 'integer',
            'complexity_score' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function aiSystem(): BelongsTo
    {
        return $this->belongsTo(AiSystem::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function embedVideoUrl(): ?string
    {
        if (empty($this->video_evidence_url)) {
            return null;
        }

        return Game::convertYoutubeToEmbed($this->video_evidence_url);
    }
}

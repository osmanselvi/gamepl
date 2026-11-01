<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'author_name',
        'author_role',
        'rating',
        'gameplay_rating',
        'ai_quality_rating',
        'optimization_rating',
        'played_platform',
        'hours_played',
        'title',
        'body',
        'contains_spoilers',
        'is_editorial',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'gameplay_rating' => 'float',
            'ai_quality_rating' => 'float',
            'optimization_rating' => 'float',
            'hours_played' => 'integer',
            'contains_spoilers' => 'boolean',
            'is_editorial' => 'boolean',
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

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'review_likes')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }
}

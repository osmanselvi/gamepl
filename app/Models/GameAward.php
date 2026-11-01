<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'person_id',
        'ceremony_name',
        'ceremony_slug',
        'year',
        'category',
        'recipient_name',
        'result',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}

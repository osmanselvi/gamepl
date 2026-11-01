<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameEngine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'developer_company',
        'release_year',
        'license_model',
        'primary_languages',
        'key_technologies',
        'ai_capabilities',
        'description',
        'official_url',
        'logo_url',
    ];

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'primary_languages' => 'array',
            'key_technologies' => 'array',
        ];
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}

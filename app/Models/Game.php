<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'original_title',
        'slug',
        'release_year',
        'developer_studio',
        'publisher',
        'game_director',
        'lead_ai_programmer',
        'composer',
        'country',
        'game_engine_id',
        'platforms',
        'genres',
        'moods',
        'perspective',
        'multiplayer_modes',
        'main_story_hours',
        'completionist_hours',
        'metacritic_score',
        'rating',
        'synopsis',
        'technical_overview',
        'ai_architecture_summary',
        'cultural_impact',
        'why_play',
        'poster_url',
        'backdrop_url',
        'trailer_url',
        'pc_min_specs',
        'pc_rec_specs',
        'is_featured_daily',
        'status',
        'submitted_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'platforms' => 'array',
            'genres' => 'array',
            'moods' => 'array',
            'multiplayer_modes' => 'array',
            'main_story_hours' => 'integer',
            'completionist_hours' => 'integer',
            'metacritic_score' => 'integer',
            'rating' => 'float',
            'pc_min_specs' => 'array',
            'pc_rec_specs' => 'array',
            'is_featured_daily' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function engine(): BelongsTo
    {
        return $this->belongsTo(GameEngine::class, 'game_engine_id');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'game_technology')
            ->withPivot('implementation_notes')
            ->withTimestamps();
    }

    public function hardware(): BelongsToMany
    {
        return $this->belongsToMany(GamingHardware::class, 'game_gaming_hardware')
            ->withPivot('experience_note')
            ->withTimestamps();
    }

    public function aiAnalyses(): HasMany
    {
        return $this->hasMany(GameAiAnalysis::class);
    }

    public function approvedAiAnalyses(): HasMany
    {
        return $this->hasMany(GameAiAnalysis::class)->where('status', 'approved');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(GameVideo::class);
    }

    public function approvedVideos(): HasMany
    {
        return $this->hasMany(GameVideo::class)->where('status', 'approved');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_game')->withTimestamps();
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'game_person')
            ->withPivot(['credit_type', 'character_name', 'credit_order'])
            ->withTimestamps()
            ->orderByPivot('credit_order');
    }

    public function directors(): BelongsToMany
    {
        return $this->people()->wherePivot('credit_type', 'director');
    }

    public function actors(): BelongsToMany
    {
        return $this->people()->wherePivot('credit_type', 'actor');
    }

    public function composers(): BelongsToMany
    {
        return $this->people()->wherePivot('credit_type', 'composer');
    }

    public function awards(): HasMany
    {
        return $this->hasMany(GameAward::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Bayesian Weighted Rating (WR) calculation on 10-point scale:
     * WR = (v / (v + m)) * R + (m / (v + m)) * C
     * where R = game's review average (or base rating), v = review count, m = minimum threshold (3), C = platform mean (8.8).
     */
    public function weightedRating(): float
    {
        $v = (int) ($this->reviews_count ?? $this->reviews()->count());
        $m = 3;
        $c = 8.8;

        if ($v === 0) {
            return round((float) $this->rating, 1);
        }

        $r = (float) ($this->reviews_avg_rating ?? $this->reviews()->avg('rating') ?? $this->rating);
        $wr = (($v / ($v + $m)) * $r) + (($m / ($v + $m)) * $c);

        return round($wr, 1);
    }

    public function embedTrailerUrl(): ?string
    {
        if (empty($this->trailer_url)) {
            return null;
        }

        return self::convertYoutubeToEmbed($this->trailer_url);
    }

    public static function convertYoutubeToEmbed(string $url): string
    {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([A-Za-z0-9_-]{6,})/', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return $url;
    }

    public function syncPeopleFromStrings(): void
    {
        $syncRows = [];

        if (! empty($this->game_director)) {
            $directorNames = array_filter(array_map('trim', preg_split('/[,&\/]+|\bve\b/u', $this->game_director) ?: []));
            foreach (array_values($directorNames) as $index => $directorName) {
                $person = Person::firstOrCreate(
                    ['slug' => Str::slug($directorName)],
                    [
                        'name' => $directorName,
                        'primary_role' => 'director',
                        'nationality' => $this->country,
                        'bio' => "{$directorName}, dijital oyun kültürü ve etkileşimli tasarım dünyasında öne çıkan yaratıcı oyun yönetmenlerinden biridir.",
                    ]
                );
                $syncRows[$person->id.'_director'] = [
                    'person_id' => $person->id,
                    'credit_type' => 'director',
                    'character_name' => 'Game Director',
                    'credit_order' => $index + 1,
                ];
            }
        }

        if (! empty($this->lead_ai_programmer)) {
            $aiNames = array_filter(array_map('trim', preg_split('/[,&\/]+|\bve\b/u', $this->lead_ai_programmer) ?: []));
            foreach (array_values($aiNames) as $index => $aiName) {
                $person = Person::firstOrCreate(
                    ['slug' => Str::slug($aiName)],
                    [
                        'name' => $aiName,
                        'primary_role' => 'ai_architect',
                        'nationality' => $this->country,
                        'bio' => "{$aiName}, oyun yapay zekâsı, karar ağaçları ve otonom NPC mimarileri alanında uzmanlaşmış baş mühendislerdendir.",
                    ]
                );
                $syncRows[$person->id.'_ai'] = [
                    'person_id' => $person->id,
                    'credit_type' => 'ai_architect',
                    'character_name' => 'Lead AI Architect',
                    'credit_order' => $index + 1,
                ];
            }
        }

        if (! empty($this->composer)) {
            $composerNames = array_filter(array_map('trim', preg_split('/[,&\/]+|\bve\b/u', $this->composer) ?: []));
            foreach (array_values($composerNames) as $index => $composerName) {
                $person = Person::firstOrCreate(
                    ['slug' => Str::slug($composerName)],
                    [
                        'name' => $composerName,
                        'primary_role' => 'composer',
                        'nationality' => $this->country,
                        'bio' => "{$composerName}, dinamik oyun müzikleri ve etkileşimli ses mimarileriyle tanınan bestecidir.",
                    ]
                );
                $syncRows[$person->id.'_composer'] = [
                    'person_id' => $person->id,
                    'credit_type' => 'composer',
                    'character_name' => 'Original Score Composer',
                    'credit_order' => $index + 1,
                ];
            }
        }

        if ($syncRows !== []) {
            $this->people()->detach();
            foreach ($syncRows as $row) {
                $this->people()->attach($row['person_id'], [
                    'credit_type' => $row['credit_type'],
                    'character_name' => $row['character_name'],
                    'credit_order' => $row['credit_order'],
                ]);
            }
        }
    }
}

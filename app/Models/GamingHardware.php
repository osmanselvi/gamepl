<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GamingHardware extends Model
{
    use HasFactory;

    protected $table = 'gaming_hardware';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'manufacturer',
        'release_year',
        'price_tier',
        'badge_label',
        'rating',
        'key_specs',
        'short_intro',
        'technical_deep_dive',
        'ai_and_game_synergy',
        'pros',
        'cons',
        'recommended_for',
        'image_url',
        'showcase_video_url',
        'is_featured',
        'status',
        'submitted_by',
    ];

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'rating' => 'float',
            'key_specs' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_gaming_hardware')
            ->withPivot('experience_note')
            ->withTimestamps();
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function embedVideoUrl(): ?string
    {
        if (empty($this->showcase_video_url)) {
            return null;
        }

        return Game::convertYoutubeToEmbed($this->showcase_video_url);
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'monitor' => 'En İyi Oyuncu Monitörü (OLED / Yüksek Hz)',
            'console' => 'Oyun Konsolu & El Bilgisayarı (Handheld)',
            'gpu_ai' => 'Grafik Kartı & Nöral AI Hızlandırıcı (GPU)',
            'vr_headset' => 'Sanal & Karma Gerçeklik Başlığı (VR / XR)',
            'peripherals' => 'Düşük Gecikmeli Kontrolcü & E-Spor Ekipmanı',
            default => 'Teknik Oyun Donanımı',
        };
    }

    public function categoryIcon(): string
    {
        return match ($this->category) {
            'monitor' => '🖥️',
            'console' => '🎮',
            'gpu_ai' => '⚡',
            'vr_headset' => '🥽',
            'peripherals' => '⌨️',
            default => '🔧',
        };
    }
}

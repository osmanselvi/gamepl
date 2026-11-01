<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'paradigm',
        'invented_by_or_origin',
        'first_notable_game',
        'first_year',
        'complexity_level',
        'concept_summary',
        'how_it_works',
        'pros_and_cons',
        'pseudocode_or_diagram',
    ];

    protected function casts(): array
    {
        return [
            'first_year' => 'integer',
        ];
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(GameAiAnalysis::class);
    }

    public function paradigmLabel(): string
    {
        return match ($this->paradigm) {
            'decision_making' => 'Karar Alma & Planlama Mimarisi',
            'director_pacing' => 'Yapay Zekâ Yönetmeni (AI Director & Pacing)',
            'emergent_social' => 'Emergent Sosyal Hafıza & İlişki Ağı',
            'neural_rendering' => 'Nöral Rendering & DLSS / Frame Gen',
            'generative_npc' => 'Üretken Yapay Zekâ & LLM Tabanlı NPC',
            'reinforcement_learning' => 'Pekiştirmeli Öğrenme (RL) Ajanları',
            'procedural_ai' => 'Yapay Zekâ Destekli Prosedürel Üretim',
            default => 'Oyun Yapay Zekâsı',
        };
    }
}

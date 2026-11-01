<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'interviewee_name',
        'interviewee_role',
        'person_id',
        'game_id',
        'interviewer_name',
        'source_publication',
        'interview_year',
        'featured_quote',
        'excerpt',
        'body',
        'cover_url',
        'status',
        'submitted_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'interview_year' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function roleLabel(): string
    {
        return match ($this->interviewee_role) {
            'director' => 'Oyun Yönetmeni',
            'ai_architect' => 'Yapay Zekâ Mimarı',
            'engine_programmer' => 'Oyun Motoru Mühendisi',
            'actor' => 'Performans Sanatçısı',
            'composer' => 'Oyun Müzikleri Bestecisi',
            default => 'Yaratıcı & Geliştirici',
        };
    }
}

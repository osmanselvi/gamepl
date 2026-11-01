<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'bio', 'studio_name', 'github_url', 'steam_handle'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isWebAdmin(): bool
    {
        return $this->role === UserRole::WebAdmin;
    }

    public function isStudyo(): bool
    {
        return $this->role === UserRole::Studyo;
    }

    public function isGelistirici(): bool
    {
        return $this->role === UserRole::Gelistirici;
    }

    public function isOyuncu(): bool
    {
        return $this->role === UserRole::Oyuncu;
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canApproveContent(): bool
    {
        return in_array($this->role, [UserRole::Admin, UserRole::WebAdmin], true);
    }

    public function canSubmitGames(): bool
    {
        return in_array($this->role, [
            UserRole::Admin,
            UserRole::WebAdmin,
            UserRole::Studyo,
            UserRole::Gelistirici,
        ], true);
    }

    public function canSubmitAiAnalyses(): bool
    {
        return in_array($this->role, [
            UserRole::Admin,
            UserRole::WebAdmin,
            UserRole::Gelistirici,
            UserRole::Studyo,
        ], true);
    }

    public function canSubmitVideos(): bool
    {
        return in_array($this->role, [
            UserRole::Admin,
            UserRole::WebAdmin,
            UserRole::Studyo,
            UserRole::Gelistirici,
            UserRole::Oyuncu,
        ], true);
    }

    public function canSubmitInterviews(): bool
    {
        return in_array($this->role, [
            UserRole::Admin,
            UserRole::WebAdmin,
            UserRole::Studyo,
            UserRole::Gelistirici,
        ], true);
    }

    public function canSubmitArticles(): bool
    {
        return in_array($this->role, [
            UserRole::Admin,
            UserRole::WebAdmin,
            UserRole::Gelistirici,
            UserRole::Oyuncu,
        ], true);
    }

    public function roleLabel(): string
    {
        return ($this->role ?? UserRole::Oyuncu)->label();
    }

    public function roleBadgeColor(): string
    {
        return ($this->role ?? UserRole::Oyuncu)->badgeColor();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function likedReviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'review_likes')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function gameLogs(): HasMany
    {
        return $this->hasMany(GameLog::class);
    }

    public function watchlist(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'watchlists')->withTimestamps();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function submittedGames(): HasMany
    {
        return $this->hasMany(Game::class, 'submitted_by');
    }

    public function submittedAiAnalyses(): HasMany
    {
        return $this->hasMany(GameAiAnalysis::class, 'author_id');
    }

    public function submittedVideos(): HasMany
    {
        return $this->hasMany(GameVideo::class, 'user_id');
    }

    public function submittedInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'submitted_by');
    }

    public function authoredArticles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function favoriteGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'user_favorite_games')
            ->withPivot('rank')
            ->orderByPivot('rank')
            ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id')->withTimestamps();
    }
}

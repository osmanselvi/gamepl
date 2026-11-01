<?php

use App\Http\Controllers\GamePlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GamePlatformController::class, 'index'])->name('home');
Route::get('/games/{game:slug}', [GamePlatformController::class, 'showGame'])->name('games.show');

// Oyun Teknolojileri & Yapay Zekâ Laboratuvarı (/ai-lab)
Route::get('/ai-lab', [GamePlatformController::class, 'aiLabIndex'])->name('ai.index');
Route::get('/ai-lab/systems/{aiSystem:slug}', [GamePlatformController::class, 'showAiSystem'])->name('ai.systems.show');
Route::get('/ai-lab/analyses/{analysis:slug}', [GamePlatformController::class, 'showAiAnalysis'])->name('ai.analyses.show');

// Oyun Motorları (/engines/{slug})
Route::get('/engines/{engine:slug}', [GamePlatformController::class, 'showEngine'])->name('engines.show');

// Teknik Oyun Araçları: En İyi Monitörler, Konsollar ve AI Donanımları (/hardware)
Route::get('/hardware', [GamePlatformController::class, 'hardwareIndex'])->name('hardware.index');
Route::get('/hardware/{hardware:slug}', [GamePlatformController::class, 'showHardware'])->name('hardware.show');

// Örnek Oyun & AI Analiz Videoları (/videos)
Route::get('/videos', [GamePlatformController::class, 'videosIndex'])->name('videos.index');

// Yaratıcılar, Söyleşiler, Makaleler ve Koleksiyonlar
Route::get('/people/{person:slug}', [GamePlatformController::class, 'showPerson'])->name('people.show');
Route::get('/interviews', [GamePlatformController::class, 'interviewsIndex'])->name('interviews.index');
Route::get('/interviews/{interview:slug}', [GamePlatformController::class, 'showInterview'])->name('interviews.show');
Route::get('/articles', [GamePlatformController::class, 'articlesIndex'])->name('articles.index');
Route::get('/articles/{article:slug}', [GamePlatformController::class, 'showArticle'])->name('articles.show');
Route::get('/collections/{collection:slug}', [GamePlatformController::class, 'showCollection'])->name('collections.show');
Route::get('/users/{member}', [GamePlatformController::class, 'showUser'])->name('users.show');

// Kimlik Doğrulama & 5 Rol Hızlı Geçiş
Route::get('/login', [GamePlatformController::class, 'showLogin'])->name('login');
Route::post('/login', [GamePlatformController::class, 'login'])->name('login.submit');
Route::post('/login/quick-role', [GamePlatformController::class, 'quickRoleLogin'])->name('login.quick-role');
Route::get('/register', [GamePlatformController::class, 'showRegister'])->name('register');
Route::post('/register', [GamePlatformController::class, 'register'])->name('register.submit');
Route::post('/logout', [GamePlatformController::class, 'logout'])->name('logout');

// Oturum Açmış Kullanıcı Rotaları (5 Rol Dashboard & Oyuncu Belleği)
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [GamePlatformController::class, 'dashboard'])->name('dashboard');
    Route::post('/dashboard/games', [GamePlatformController::class, 'submitGame'])->name('dashboard.games.store');
    Route::post('/dashboard/ai-analyses', [GamePlatformController::class, 'submitAiAnalysis'])->name('dashboard.ai.store');
    Route::post('/dashboard/videos', [GamePlatformController::class, 'submitVideo'])->name('dashboard.videos.store');
    Route::post('/dashboard/hardware', [GamePlatformController::class, 'submitHardware'])->name('dashboard.hardware.store');

    Route::post('/dashboard/games/{game}/approve', [GamePlatformController::class, 'approveGame'])->name('dashboard.games.approve');
    Route::post('/dashboard/ai-analyses/{analysis}/approve', [GamePlatformController::class, 'approveAiAnalysis'])->name('dashboard.ai.approve');
    Route::post('/dashboard/videos/{video}/approve', [GamePlatformController::class, 'approveVideo'])->name('dashboard.videos.approve');
    Route::post('/dashboard/games/{game}/feature', [GamePlatformController::class, 'featureDailyGame'])->name('dashboard.games.feature');
    Route::patch('/dashboard/users/{member}/role', [GamePlatformController::class, 'updateUserRole'])->name('dashboard.users.role');

    Route::get('/profile', [GamePlatformController::class, 'profile'])->name('profile');
    Route::post('/profile/favorites', [GamePlatformController::class, 'updateFavorites'])->name('profile.favorites');
    Route::post('/users/{member}/follow', [GamePlatformController::class, 'toggleFollow'])->name('users.follow');

    Route::post('/games/{game:slug}/reviews', [GamePlatformController::class, 'storeReview'])->name('games.reviews.store');
    Route::post('/reviews/{review}/like', [GamePlatformController::class, 'toggleReviewLike'])->name('reviews.like');
    Route::post('/reviews/{review}/comments', [GamePlatformController::class, 'storeComment'])->name('reviews.comments.store');
    Route::post('/games/{game:slug}/log', [GamePlatformController::class, 'logGame'])->name('games.log');
    Route::post('/games/{game:slug}/watchlist', [GamePlatformController::class, 'toggleWatchlist'])->name('games.watchlist');
});

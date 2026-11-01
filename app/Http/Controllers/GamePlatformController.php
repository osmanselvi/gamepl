<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\AiSystem;
use App\Models\Article;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Game;
use App\Models\GameAiAnalysis;
use App\Models\GameEngine;
use App\Models\GameLog;
use App\Models\GameVideo;
use App\Models\GamingHardware;
use App\Models\Interview;
use App\Models\Person;
use App\Models\Review;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GamePlatformController extends Controller
{
    public function index(Request $request): View
    {
        $approvedQuery = Game::query()
            ->where('status', 'approved')
            ->with(['engine', 'technologies', 'directors', 'approvedAiAnalyses.aiSystem', 'approvedVideos'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        $featuredGame = (clone $approvedQuery)->where('is_featured_daily', true)->first()
            ?? (clone $approvedQuery)->orderByDesc('rating')->first();

        $gamesQuery = clone $approvedQuery;

        if ($search = trim((string) $request->string('q'))) {
            $gamesQuery->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('original_title', 'like', "%{$search}%")
                    ->orWhere('developer_studio', 'like', "%{$search}%")
                    ->orWhere('game_director', 'like', "%{$search}%")
                    ->orWhere('lead_ai_programmer', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%")
                    ->orWhere('ai_architecture_summary', 'like', "%{$search}%");
            });
        }

        if ($genre = trim((string) $request->string('genre'))) {
            $gamesQuery->whereJsonContains('genres', $genre);
        }

        if ($engineSlug = trim((string) $request->string('engine'))) {
            $gamesQuery->whereHas('engine', fn ($q) => $q->where('slug', $engineSlug));
        }

        if ($era = trim((string) $request->string('era'))) {
            if ($era === '2020s') {
                $gamesQuery->whereBetween('release_year', [2020, 2029]);
            } elseif ($era === '2010s') {
                $gamesQuery->whereBetween('release_year', [2010, 2019]);
            } elseif ($era === '2000s') {
                $gamesQuery->whereBetween('release_year', [2000, 2009]);
            }
        }

        $sort = $request->string('sort', 'weighted')->toString();
        $games = $gamesQuery->get();

        if ($sort === 'newest') {
            $games = $games->sortByDesc('release_year')->values();
        } elseif ($sort === 'metacritic') {
            $games = $games->sortByDesc('metacritic_score')->values();
        } else {
            $games = $games->sortByDesc(fn (Game $game): float => $game->weightedRating())->values();
        }

        $engines = GameEngine::query()->withCount('games')->orderBy('name')->get();
        $technologies = Technology::query()->withCount('games')->orderBy('introduced_year', 'desc')->get();
        $aiSystems = AiSystem::query()->withCount('analyses')->orderBy('first_year')->get();
        $featuredAiAnalyses = GameAiAnalysis::query()
            ->where('status', 'approved')
            ->with(['game', 'aiSystem', 'author'])
            ->orderByDesc('innovation_score')
            ->take(4)
            ->get();
        $featuredVideos = GameVideo::query()
            ->where('status', 'approved')
            ->with(['game', 'user'])
            ->orderByDesc('is_featured')
            ->latest()
            ->take(6)
            ->get();
        $interviews = Interview::query()
            ->where('status', 'approved')
            ->with(['game', 'person'])
            ->latest()
            ->take(2)
            ->get();
        $articles = Article::query()
            ->where('status', 'approved')
            ->with(['game', 'author'])
            ->latest()
            ->take(2)
            ->get();
        $collections = Collection::query()
            ->where('is_public', true)
            ->with(['games'])
            ->latest()
            ->take(3)
            ->get();
        $recentReviews = Review::query()
            ->with(['game', 'user', 'comments.user'])
            ->withCount('likedByUsers')
            ->latest()
            ->take(4)
            ->get();

        $featuredHardware = GamingHardware::query()
            ->where('status', 'approved')
            ->with('games')
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->take(6)
            ->get();

        $allGenres = Game::query()
            ->where('status', 'approved')
            ->pluck('genres')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('home', compact(
            'featuredGame',
            'games',
            'engines',
            'technologies',
            'aiSystems',
            'featuredAiAnalyses',
            'featuredVideos',
            'featuredHardware',
            'interviews',
            'articles',
            'collections',
            'recentReviews',
            'allGenres'
        ));
    }

    public function showGame(Game $game): View
    {
        abort_unless(
            $game->status === 'approved' || (Auth::check() && (Auth::user()->canApproveContent() || Auth::id() === $game->submitted_by)),
            404
        );

        $game->load([
            'engine',
            'technologies',
            'hardware',
            'approvedAiAnalyses.aiSystem',
            'approvedAiAnalyses.author',
            'approvedVideos.user',
            'people',
            'awards',
            'interviews' => fn ($q) => $q->where('status', 'approved'),
            'articles' => fn ($q) => $q->where('status', 'approved'),
            'reviews' => fn ($q) => $q->with(['user', 'comments.user'])->withCount('likedByUsers')->latest(),
        ])->loadCount('reviews')->loadAvg('reviews', 'rating');

        $userLog = Auth::check()
            ? GameLog::where('user_id', Auth::id())->where('game_id', $game->id)->first()
            : null;
        $inWatchlist = Auth::check() && Auth::user()->watchlist()->where('game_id', $game->id)->exists();

        return view('games.show', compact('game', 'userLog', 'inWatchlist'));
    }

    public function aiLabIndex(): View
    {
        $aiSystems = AiSystem::query()
            ->with(['analyses' => fn ($q) => $q->where('status', 'approved')->with('game')])
            ->orderBy('first_year')
            ->get();

        $analyses = GameAiAnalysis::query()
            ->where('status', 'approved')
            ->with(['game.engine', 'aiSystem', 'author'])
            ->orderByDesc('innovation_score')
            ->get();

        $technologies = Technology::query()
            ->with('games')
            ->orderByDesc('introduced_year')
            ->get();

        return view('ai-lab.index', compact('aiSystems', 'analyses', 'technologies'));
    }

    public function showAiSystem(AiSystem $aiSystem): View
    {
        $aiSystem->load([
            'analyses' => fn ($q) => $q->where('status', 'approved')->with(['game.engine', 'author']),
        ]);

        return view('ai-lab.system', compact('aiSystem'));
    }

    public function showAiAnalysis(GameAiAnalysis $analysis): View
    {
        abort_unless(
            $analysis->status === 'approved' || (Auth::check() && (Auth::user()->canApproveContent() || Auth::id() === $analysis->author_id)),
            404
        );

        $analysis->load(['game.engine', 'game.technologies', 'aiSystem', 'author']);

        return view('ai-lab.show', compact('analysis'));
    }

    public function showEngine(GameEngine $engine): View
    {
        $engine->load([
            'games' => fn ($q) => $q->where('status', 'approved')
                ->with(['technologies', 'approvedAiAnalyses.aiSystem'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating'),
        ]);

        return view('engines.show', compact('engine'));
    }

    public function hardwareIndex(Request $request): View
    {
        $category = trim((string) $request->string('category'));

        $hardwareQuery = GamingHardware::query()
            ->where('status', 'approved')
            ->with('games')
            ->orderByDesc('rating');

        if ($category !== '') {
            $hardwareQuery->where('category', $category);
        }

        $hardwareItems = $hardwareQuery->get();

        return view('hardware.index', compact('hardwareItems', 'category'));
    }

    public function showHardware(GamingHardware $hardware): View
    {
        $hardware->load([
            'games' => fn ($q) => $q->where('status', 'approved')->with('engine'),
            'submitter',
        ]);

        return view('hardware.show', compact('hardware'));
    }

    public function submitHardware(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canSubmitGames() || $user->canApproveContent(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'category' => ['required', Rule::in(['monitor', 'console', 'gpu_ai', 'vr_headset', 'peripherals'])],
            'manufacturer' => ['required', 'string', 'max:120'],
            'release_year' => ['required', 'integer', 'min:2010', 'max:2030'],
            'price_tier' => ['required', 'string', 'max:100'],
            'badge_label' => ['nullable', 'string', 'max:140'],
            'rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'short_intro' => ['required', 'string', 'min:20'],
            'technical_deep_dive' => ['required', 'string', 'min:20'],
            'ai_and_game_synergy' => ['nullable', 'string'],
            'pros' => ['nullable', 'string'],
            'cons' => ['nullable', 'string'],
            'recommended_for' => ['nullable', 'string', 'max:250'],
            'showcase_video_url' => ['nullable', 'url'],
        ]);

        $hw = GamingHardware::create([
            ...$validated,
            'slug' => Str::slug($validated['name']).'-'.Str::lower(Str::random(4)),
            'key_specs' => [
                'Üretici' => $validated['manufacturer'],
                'Çıkış Yılı' => (string) $validated['release_year'],
                'Segment' => $validated['price_tier'],
            ],
            'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=1200&q=80',
            'is_featured' => true,
            'status' => 'approved',
            'submitted_by' => $user->id,
        ]);

        return back()->with('status', "\"{$hw->name}\" teknik oyun donanımları kataloğuna eklendi.");
    }

    public function videosIndex(Request $request): View
    {
        $type = trim((string) $request->string('type'));

        $videosQuery = GameVideo::query()
            ->where('status', 'approved')
            ->with(['game.engine', 'user'])
            ->orderByDesc('is_featured')
            ->latest();

        if ($type !== '') {
            $videosQuery->where('video_type', $type);
        }

        $videos = $videosQuery->get();

        return view('videos.index', compact('videos', 'type'));
    }

    public function showPerson(Person $person): View
    {
        $person->load([
            'games' => fn ($q) => $q->where('status', 'approved')->with('engine'),
            'awards.game',
            'interviews' => fn ($q) => $q->where('status', 'approved')->with('game'),
        ]);

        return view('people.show', compact('person'));
    }

    public function interviewsIndex(): View
    {
        $interviews = Interview::query()
            ->where('status', 'approved')
            ->with(['game', 'person'])
            ->orderByDesc('interview_year')
            ->get();

        return view('interviews.index', compact('interviews'));
    }

    public function showInterview(Interview $interview): View
    {
        abort_unless($interview->status === 'approved' || (Auth::check() && Auth::user()->canApproveContent()), 404);
        $interview->load(['game', 'person', 'submitter']);

        return view('interviews.show', compact('interview'));
    }

    public function articlesIndex(): View
    {
        $articles = Article::query()
            ->where('status', 'approved')
            ->with(['game', 'author'])
            ->latest()
            ->get();

        return view('articles.index', compact('articles'));
    }

    public function showArticle(Article $article): View
    {
        abort_unless($article->status === 'approved' || (Auth::check() && Auth::user()->canApproveContent()), 404);
        $article->load(['game', 'author', 'person']);

        return view('articles.show', compact('article'));
    }

    public function showCollection(Collection $collection): View
    {
        $collection->load(['games.engine', 'user']);

        return view('collections.show', compact('collection'));
    }

    public function showLogin(): View
    {
        $demoUsers = User::query()
            ->whereIn('email', [
                'admin@gamepl.local',
                'webadmin@gamepl.local',
                'studyo@gamepl.local',
                'gelistirici@gamepl.local',
                'oyuncu@gamepl.local',
            ])
            ->get();

        return view('auth.login', compact('demoUsers'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('status', 'Hoş geldiniz, '.Auth::user()->name.' ('.Auth::user()->roleLabel().').');
        }

        return back()->withErrors([
            'email' => 'Giriş bilgileri doğrulanamadı.',
        ])->onlyInput('email');
    }

    public function quickRoleLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('status', "{$user->name} ({$user->roleLabel()}) hesabıyla hızlı geçiş yapıldı.");
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'studio_name' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string', 'max:600'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Oyuncu,
            'studio_name' => $validated['studio_name'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('status', 'GamePL oyuncu ve teknoloji topluluğuna hoş geldiniz!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Oturumunuz kapatıldı.');
    }

    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $engines = GameEngine::orderBy('name')->get();
        $aiSystems = AiSystem::orderBy('name')->get();
        $approvedGames = Game::where('status', 'approved')->orderBy('title')->get();
        $people = Person::orderBy('name')->get();

        $pendingGames = $user->canApproveContent()
            ? Game::where('status', 'pending')->with(['submitter', 'engine'])->latest()->get()
            : collect();

        $pendingAnalyses = $user->canApproveContent()
            ? GameAiAnalysis::where('status', 'pending')->with(['game', 'aiSystem', 'author'])->latest()->get()
            : collect();

        $pendingVideos = $user->canApproveContent()
            ? GameVideo::where('status', 'pending')->with(['game', 'user'])->latest()->get()
            : collect();

        $pendingInterviews = $user->canApproveContent()
            ? Interview::where('status', 'pending')->with(['game', 'submitter'])->latest()->get()
            : collect();

        $pendingArticles = $user->canApproveContent()
            ? Article::where('status', 'pending')->with(['game', 'author'])->latest()->get()
            : collect();

        $allUsers = $user->canManageUsers()
            ? User::orderBy('id')->get()
            : collect();

        $mySubmittedGames = Game::where('submitted_by', $user->id)->with('engine')->latest()->get();
        $mySubmittedAnalyses = GameAiAnalysis::where('author_id', $user->id)->with(['game', 'aiSystem'])->latest()->get();
        $mySubmittedVideos = GameVideo::where('user_id', $user->id)->with('game')->latest()->get();

        return view('dashboard.index', compact(
            'user',
            'engines',
            'aiSystems',
            'approvedGames',
            'people',
            'pendingGames',
            'pendingAnalyses',
            'pendingVideos',
            'pendingInterviews',
            'pendingArticles',
            'allUsers',
            'mySubmittedGames',
            'mySubmittedAnalyses',
            'mySubmittedVideos'
        ));
    }

    public function submitGame(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canSubmitGames(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'original_title' => ['nullable', 'string', 'max:180'],
            'release_year' => ['required', 'integer', 'min:1972', 'max:2030'],
            'developer_studio' => ['required', 'string', 'max:180'],
            'publisher' => ['nullable', 'string', 'max:180'],
            'game_director' => ['required', 'string', 'max:180'],
            'lead_ai_programmer' => ['nullable', 'string', 'max:180'],
            'composer' => ['nullable', 'string', 'max:180'],
            'country' => ['required', 'string', 'max:100'],
            'game_engine_id' => ['nullable', 'exists:game_engines,id'],
            'platforms_input' => ['required', 'string', 'max:250'],
            'genres_input' => ['required', 'string', 'max:250'],
            'moods_input' => ['nullable', 'string', 'max:250'],
            'perspective' => ['nullable', 'string', 'max:120'],
            'main_story_hours' => ['required', 'integer', 'min:1', 'max:1000'],
            'completionist_hours' => ['nullable', 'integer', 'min:1', 'max:2000'],
            'metacritic_score' => ['nullable', 'integer', 'min:1', 'max:100'],
            'rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'synopsis' => ['required', 'string', 'min:20'],
            'technical_overview' => ['nullable', 'string'],
            'ai_architecture_summary' => ['nullable', 'string'],
            'why_play' => ['nullable', 'string'],
            'poster_url' => ['nullable', 'url'],
            'backdrop_url' => ['nullable', 'url'],
            'trailer_url' => ['nullable', 'url'],
        ]);

        $autoApproved = $user->canApproveContent();
        $slug = Str::slug($validated['title']).'-'.Str::lower(Str::random(4));

        $game = Game::create([
            'title' => $validated['title'],
            'original_title' => $validated['original_title'] ?: $validated['title'],
            'slug' => $slug,
            'release_year' => $validated['release_year'],
            'developer_studio' => $validated['developer_studio'],
            'publisher' => $validated['publisher'] ?: $validated['developer_studio'],
            'game_director' => $validated['game_director'],
            'lead_ai_programmer' => $validated['lead_ai_programmer'] ?? null,
            'composer' => $validated['composer'] ?? null,
            'country' => $validated['country'],
            'game_engine_id' => $validated['game_engine_id'] ?? null,
            'platforms' => array_values(array_filter(array_map('trim', explode(',', $validated['platforms_input'])))),
            'genres' => array_values(array_filter(array_map('trim', explode(',', $validated['genres_input'])))),
            'moods' => ! empty($validated['moods_input'])
                ? array_values(array_filter(array_map('trim', explode(',', $validated['moods_input']))))
                : ['Sinematik', 'Taktiksel'],
            'perspective' => $validated['perspective'] ?: 'Üçüncü Şahıs (TPS)',
            'multiplayer_modes' => ['Tek Oyunculu'],
            'main_story_hours' => $validated['main_story_hours'],
            'completionist_hours' => $validated['completionist_hours'] ?: ($validated['main_story_hours'] * 2),
            'metacritic_score' => $validated['metacritic_score'] ?? 88,
            'rating' => $validated['rating'],
            'synopsis' => $validated['synopsis'],
            'technical_overview' => $validated['technical_overview'] ?? null,
            'ai_architecture_summary' => $validated['ai_architecture_summary'] ?? null,
            'why_play' => $validated['why_play'] ?? null,
            'poster_url' => $validated['poster_url'] ?: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80',
            'backdrop_url' => $validated['backdrop_url'] ?: 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1600&q=80',
            'trailer_url' => $validated['trailer_url'] ?? null,
            'status' => $autoApproved ? 'approved' : 'pending',
            'submitted_by' => $user->id,
            'approved_by' => $autoApproved ? $user->id : null,
            'approved_at' => $autoApproved ? now() : null,
        ]);

        $game->syncPeopleFromStrings();

        return back()->with(
            'status',
            $autoApproved
                ? "\"{$game->title}\" doğrudan katalogda yayınlandı."
                : "\"{$game->title}\" editör onay kuyruğuna eklendi."
        );
    }

    public function submitAiAnalysis(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canSubmitAiAnalyses(), 403);

        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'ai_system_id' => ['required', 'exists:ai_systems,id'],
            'title' => ['required', 'string', 'max:200'],
            'ai_role_in_game' => ['required', 'string', 'max:200'],
            'architecture_breakdown' => ['required', 'string', 'min:30'],
            'perception_and_sensors' => ['nullable', 'string'],
            'decision_loop_details' => ['nullable', 'string'],
            'player_experience_impact' => ['nullable', 'string'],
            'innovation_score' => ['required', 'integer', 'min:50', 'max:100'],
            'complexity_score' => ['required', 'integer', 'min:50', 'max:100'],
            'video_evidence_url' => ['nullable', 'url'],
        ]);

        $autoApproved = $user->canApproveContent();

        $analysis = GameAiAnalysis::create([
            ...$validated,
            'author_id' => $user->id,
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(4)),
            'status' => $autoApproved ? 'approved' : 'pending',
            'approved_by' => $autoApproved ? $user->id : null,
            'approved_at' => $autoApproved ? now() : null,
        ]);

        return back()->with(
            'status',
            $autoApproved
                ? "\"{$analysis->title}\" Oyun & AI Laboratuvarında yayınlandı."
                : "\"{$analysis->title}\" AI analizi moderasyon kuyruğuna iletildi."
        );
    }

    public function submitVideo(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canSubmitVideos(), 403);

        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'title' => ['required', 'string', 'max:180'],
            'video_type' => ['required', Rule::in(['gameplay_sample', 'ai_showcase', 'tech_breakdown', 'speedrun_pro', 'trailer'])],
            'video_url' => ['required', 'url'],
            'duration_label' => ['nullable', 'string', 'max:30'],
            'resolution_tag' => ['nullable', 'string', 'max:40'],
            'description' => ['required', 'string', 'min:10'],
            'technical_highlights' => ['nullable', 'string', 'max:250'],
        ]);

        $autoApproved = $user->canApproveContent();

        $video = GameVideo::create([
            'game_id' => $validated['game_id'],
            'user_id' => $user->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(4)),
            'video_type' => $validated['video_type'],
            'video_url' => $validated['video_url'],
            'thumbnail_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80',
            'duration_label' => $validated['duration_label'] ?: '08:00',
            'resolution_tag' => $validated['resolution_tag'] ?: '4K 60FPS',
            'description' => $validated['description'],
            'technical_highlights' => $validated['technical_highlights'] ?? null,
            'is_featured' => $autoApproved,
            'status' => $autoApproved ? 'approved' : 'pending',
        ]);

        return back()->with(
            'status',
            $autoApproved
                ? "\"{$video->title}\" örnek video kütüphanesinde yayınlandı."
                : "\"{$video->title}\" video kaydı editör onayına gönderildi."
        );
    }

    public function approveGame(Game $game): RedirectResponse
    {
        abort_unless(Auth::user()->canApproveContent(), 403);
        $game->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('status', "\"{$game->title}\" onaylandı ve yayına alındı.");
    }

    public function approveAiAnalysis(GameAiAnalysis $analysis): RedirectResponse
    {
        abort_unless(Auth::user()->canApproveContent(), 403);
        $analysis->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('status', "\"{$analysis->title}\" AI analizi onaylandı.");
    }

    public function approveVideo(GameVideo $video): RedirectResponse
    {
        abort_unless(Auth::user()->canApproveContent(), 403);
        $video->update(['status' => 'approved']);

        return back()->with('status', "\"{$video->title}\" videosu onaylandı.");
    }

    public function featureDailyGame(Game $game): RedirectResponse
    {
        abort_unless(Auth::user()->canApproveContent(), 403);
        Game::query()->update(['is_featured_daily' => false]);
        $game->update(['is_featured_daily' => true]);

        return back()->with('status', "\"{$game->title}\" Günün Oyunu olarak vitrine taşındı.");
    }

    public function updateUserRole(Request $request, User $member): RedirectResponse
    {
        abort_unless(Auth::user()->canManageUsers(), 403);

        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $member->update(['role' => $validated['role']]);

        return back()->with('status', "{$member->name} kullanıcısının rolü {$member->roleLabel()} olarak güncellendi.");
    }

    public function storeReview(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'gameplay_rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'ai_quality_rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'optimization_rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'played_platform' => ['required', 'string', 'max:80'],
            'hours_played' => ['required', 'integer', 'min:1', 'max:5000'],
            'body' => ['required', 'string', 'min:20'],
            'contains_spoilers' => ['nullable', 'boolean'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        Review::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'author_role' => $user->roleLabel(),
            'rating' => $validated['rating'],
            'gameplay_rating' => $validated['gameplay_rating'],
            'ai_quality_rating' => $validated['ai_quality_rating'],
            'optimization_rating' => $validated['optimization_rating'],
            'played_platform' => $validated['played_platform'],
            'hours_played' => $validated['hours_played'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'contains_spoilers' => $request->boolean('contains_spoilers'),
            'is_editorial' => $user->canApproveContent() || $user->isGelistirici(),
        ]);

        return back()->with('status', 'Teknik ve oynanış incelemeniz yayınlandı.');
    }

    public function toggleReviewLike(Review $review): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->likedReviews()->toggle($review->id);

        return back();
    }

    public function storeComment(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        Comment::create([
            'review_id' => $review->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        return back()->with('status', 'Yorumunuz eklendi.');
    }

    public function logGame(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'play_status' => ['required', Rule::in(['playing', 'completed', 'mastered', 'backlog', 'dropped'])],
            'platform' => ['nullable', 'string', 'max:80'],
            'hours_played' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'personal_rating' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        GameLog::updateOrCreate(
            ['user_id' => Auth::id(), 'game_id' => $game->id],
            [
                'play_status' => $validated['play_status'],
                'platform' => $validated['platform'] ?: 'PC',
                'hours_played' => $validated['hours_played'] ?? 10,
                'completed_on' => in_array($validated['play_status'], ['completed', 'mastered'], true) ? now() : null,
                'personal_rating' => $validated['personal_rating'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return back()->with('status', "\"{$game->title}\" oyuncu günlüğünüze kaydedildi.");
    }

    public function toggleWatchlist(Game $game): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->watchlist()->toggle($game->id);

        return back()->with('status', 'Oynanacaklar (Backlog) listeniz güncellendi.');
    }

    public function profile(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load([
            'gameLogs.game.engine',
            'watchlist.engine',
            'favoriteGames.engine',
            'reviews.game',
            'collections.games',
            'following',
            'followers',
        ]);

        $allApprovedGames = Game::where('status', 'approved')->orderBy('title')->get();

        return view('profile.show', compact('user', 'allApprovedGames'));
    }

    public function updateFavorites(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'favorite_game_ids' => ['required', 'array', 'min:1', 'max:4'],
            'favorite_game_ids.*' => ['exists:games,id'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $sync = [];
        foreach (array_values(array_unique($validated['favorite_game_ids'])) as $idx => $gameId) {
            $sync[$gameId] = ['rank' => $idx + 1];
        }
        $user->favoriteGames()->sync($sync);

        return back()->with('status', 'Favori 4 Oyun vitrininiz güncellendi.');
    }

    public function showUser(User $member): View
    {
        $member->load([
            'gameLogs.game.engine',
            'favoriteGames.engine',
            'reviews.game',
            'submittedAiAnalyses.game',
            'submittedVideos.game',
            'followers',
            'following',
        ]);

        return view('users.show', compact('member'));
    }

    public function toggleFollow(User $member): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->id !== $member->id) {
            $user->following()->toggle($member->id);
        }

        return back();
    }
}

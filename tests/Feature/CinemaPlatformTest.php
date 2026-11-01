<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Article;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Film;
use App\Models\FilmAward;
use App\Models\Interview;
use App\Models\Person;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CinemaPlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_with_vite_assets_and_auth_modals(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Cinema Culture');
        $response->assertSee('id="loginModal"', false);
        $response->assertSee('id="registerModal"', false);
        $response->assertSee('showLoginModal()', false);
        $response->assertSee('showRegisterModal()', false);
    }

    public function test_films_api_lists_and_filters_films_by_search_query(): void
    {
        Film::create([
            'title' => 'Inception',
            'slug' => 'inception',
            'director' => 'Christopher Nolan',
            'genre' => 'Bilim Kurgu',
            'year' => 2010,
        ]);

        Film::create([
            'title' => 'Pulp Fiction',
            'slug' => 'pulp-fiction',
            'director' => 'Quentin Tarantino',
            'genre' => 'Crime',
            'year' => 1994,
        ]);

        $response = $this->getJson('/api/films?search=Nolan');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.title', 'Inception');
    }

    public function test_user_can_register_and_login_with_token(): void
    {
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'Ayşe Yılmaz',
            'email' => 'ayse@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'ayse@example.com')
            ->assertJsonStructure(['token']);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'ayse@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['token']);
    }

    public function test_authenticated_user_can_create_review_and_threaded_comment(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'title' => 'Kış Uykusu',
            'slug' => 'kis-uykusu',
            'year' => 2014,
            'genre' => 'Drama',
        ]);

        $reviewResponse = $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'film_id' => $film->id,
            'content' => 'Muhteşem bir sinematografi ve diyalog derinliği.',
            'rating' => 9,
        ]);

        $reviewResponse->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.rating', 9);

        $reviewId = $reviewResponse->json('data.id');

        $commentResponse = $this->actingAs($user, 'sanctum')->postJson('/api/comments', [
            'review_id' => $reviewId,
            'content' => 'Kesinlikle katılıyorum.',
            'parent_id' => 0,
        ]);

        $commentResponse->assertCreated()
            ->assertJsonPath('success', true);

        $rootCommentId = $commentResponse->json('data.id');

        $replyResponse = $this->actingAs($user, 'sanctum')->postJson('/api/comments', [
            'review_id' => $reviewId,
            'content' => 'Özellikle ikinci yarıdaki sahne çok etkileyiciydi.',
            'parent_id' => $rootCommentId,
        ]);

        $replyResponse->assertCreated()
            ->assertJsonPath('data.parent_id', $rootCommentId);

        $this->assertCount(1, $user->reviews);
        $this->assertCount(2, $film->comments);
        $this->assertCount(1, Comment::rootComments()->get());
    }

    public function test_film_detail_page_displays_metadata_and_related_director_and_actor_films(): void
    {
        $mainFilm = Film::create([
            'title' => 'Inception',
            'slug' => 'inception',
            'description' => 'Rüya içinde rüya katmanları.',
            'director' => 'Christopher Nolan',
            'cast' => 'Leonardo DiCaprio, Cillian Murphy',
            'genre' => 'Bilim Kurgu',
            'year' => 2010,
            'country' => 'USA',
        ]);

        $sameDirectorFilm = Film::create([
            'title' => 'Interstellar',
            'slug' => 'interstellar',
            'director' => 'Christopher Nolan',
            'cast' => 'Matthew McConaughey',
            'genre' => 'Bilim Kurgu',
            'year' => 2014,
        ]);

        $sameActorFilm = Film::create([
            'title' => 'Shutter Island',
            'slug' => 'shutter-island',
            'director' => 'Martin Scorsese',
            'cast' => 'Leonardo DiCaprio, Mark Ruffalo',
            'genre' => 'Gerilim',
            'year' => 2010,
        ]);

        $response = $this->get(route('films.show', $mainFilm->slug));

        $response->assertOk()
            ->assertSee('Inception')
            ->assertSee('Rüya içinde rüya katmanları.')
            ->assertSee('Christopher Nolan')
            ->assertSee('Leonardo DiCaprio')
            ->assertSee('Cillian Murphy')
            ->assertSee('Interstellar')
            ->assertSee('Shutter Island')
            ->assertSee(route('home', ['director' => 'Christopher Nolan']), false)
            ->assertSee(route('home', ['actor' => 'Leonardo DiCaprio']), false);
    }

    public function test_films_api_filters_by_director_and_actor(): void
    {
        Film::create([
            'title' => 'Inception',
            'slug' => 'inception',
            'director' => 'Christopher Nolan',
            'cast' => 'Leonardo DiCaprio, Tom Hardy',
        ]);

        Film::create([
            'title' => 'The Departed',
            'slug' => 'the-departed',
            'director' => 'Martin Scorsese',
            'cast' => 'Leonardo DiCaprio, Matt Damon',
        ]);

        $directorResponse = $this->getJson('/api/films?director='.urlencode('Christopher Nolan'));
        $directorResponse->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.slug', 'inception');

        $actorResponse = $this->getJson('/api/films?actor='.urlencode('Leonardo DiCaprio'));
        $actorResponse->assertOk()
            ->assertJsonCount(2, 'data.data');
    }

    public function test_person_filmography_page_and_normalized_credits_work(): void
    {
        $film = Film::create([
            'title' => 'Once Upon a Time in Anatolia',
            'slug' => 'once-upon-a-time-in-anatolia',
            'director' => 'Nuri Bilge Ceylan',
            'cast' => 'Muhammet Uzuner, Yılmaz Erdoğan',
            'year' => 2011,
            'genre' => 'Drama',
        ]);

        $director = Person::where('slug', 'nuri-bilge-ceylan')->first();
        $actor = Person::where('slug', 'yilmaz-erdogan')->first();

        $this->assertNotNull($director);
        $this->assertNotNull($actor);
        $this->assertTrue($film->directors->contains($director));
        $this->assertTrue($film->actors->contains($actor));

        $personPage = $this->get(route('people.show', $director->slug));
        $personPage->assertOk()
            ->assertSee('Nuri Bilge Ceylan')
            ->assertSee('Once Upon a Time in Anatolia');
    }

    public function test_watchlist_diary_log_spoiler_and_review_like_features(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $film = Film::create([
            'title' => 'Drive My Car',
            'slug' => 'drive-my-car',
            'director' => 'Ryusuke Hamaguchi',
            'year' => 2021,
        ]);

        // Toggle watchlist
        $watchlistRes = $this->actingAs($user, 'sanctum')
            ->postJson("/api/films/{$film->slug}/watchlist");
        $watchlistRes->assertOk()->assertJsonPath('in_watchlist', true);

        // Log watched film in diary
        $diaryRes = $this->actingAs($user, 'sanctum')
            ->postJson("/api/films/{$film->slug}/log", ['liked' => true]);
        $diaryRes->assertCreated()->assertJsonPath('watched', true);

        // Create review with spoiler
        $reviewRes = $this->actingAs($user, 'sanctum')
            ->postJson('/api/reviews', [
                'film_id' => $film->id,
                'rating' => 10,
                'content' => 'Son sahnedeki yüzleşme unutulmazdı.',
                'contains_spoiler' => true,
            ]);
        $reviewRes->assertCreated()->assertJsonPath('data.contains_spoiler', true);
        $reviewId = $reviewRes->json('data.id');

        // Other user likes the review
        $likeRes = $this->actingAs($otherUser, 'sanctum')
            ->postJson("/api/reviews/{$reviewId}/like");
        $likeRes->assertOk()
            ->assertJsonPath('liked', true)
            ->assertJsonPath('likes_count', 1);

        // Policy check: other user cannot delete author's review
        $forbiddenDelete = $this->actingAs($otherUser, 'sanctum')
            ->deleteJson("/api/reviews/{$reviewId}");
        $forbiddenDelete->assertForbidden();

        // Profile archive page shows watchlist and diary
        $profilePage = $this->actingAs($user)->get(route('profile.show'));
        $profilePage->assertOk()
            ->assertSee('Drive My Car')
            ->assertSee('İzleme Listem')
            ->assertSee('Seyir Günlüğüm');
    }

    public function test_curated_collection_page_and_schema_org_json_ld(): void
    {
        $film = Film::create([
            'title' => 'Stalker',
            'slug' => 'stalker',
            'director' => 'Andrei Tarkovsky',
            'year' => 1979,
        ]);

        $collection = Collection::create([
            'title' => 'Şiirsel Sinema Başyapıtları',
            'slug' => 'siirsel-sinema-basyapitlari',
            'description' => 'Zaman, bellek ve metafizik üzerine filmler.',
            'is_editorial' => true,
        ]);

        $collection->films()->attach($film->id, [
            'position' => 1,
            'curator_note' => 'Bilim kurguyu felsefi bir yolculuğa dönüştüren başyapıt.',
        ]);

        $collectionResponse = $this->get(route('collections.show', $collection->slug));
        $collectionResponse->assertOk()
            ->assertSee('Şiirsel Sinema Başyapıtları')
            ->assertSee('Stalker')
            ->assertSee('Bilim kurguyu felsefi bir yolculuğa dönüştüren başyapıt.');

        $filmDetailResponse = $this->get(route('films.show', $film->slug));
        $filmDetailResponse->assertOk()
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type":"Movie"', false);
    }

    public function test_multilingual_film_titles_and_review_translations_are_rendered(): void
    {
        $user = User::factory()->create(['name' => 'Claire Moreau']);
        $film = Film::create([
            'tmdb_id' => 27205,
            'title' => 'Başlangıç',
            'original_title' => 'Inception',
            'original_language' => 'en',
            'slug' => 'inception',
            'director' => 'Christopher Nolan',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/xn0Kcg4e6p0mLxVS3nAWhNmW2Ni.jpg',
            'translations' => [
                'tr' => ['title' => 'Başlangıç', 'description' => 'Bilinçaltına sızma sanatı.'],
                'en' => ['title' => 'Inception', 'description' => 'The art of infiltrating the subconscious.'],
                'es' => ['title' => 'Origen', 'description' => 'El arte de infiltrarse en el subconsciente.'],
                'ja' => ['title' => 'インセプション', 'description' => '潜在意識に潜入する技術。'],
            ],
        ]);

        Review::create([
            'user_id' => $user->id,
            'film_id' => $film->id,
            'rating' => 10,
            'language' => 'fr',
            'content' => 'Un chef-d\'œuvre absolu.',
            'translations' => [
                'tr' => 'Mutlak bir başyapıt.',
                'en' => 'An absolute masterpiece.',
                'fr' => 'Un chef-d\'œuvre absolu.',
            ],
        ]);

        $response = $this->get(route('films.show', $film->slug));
        $response->assertOk()
            ->assertSee('Başlangıç')
            ->assertSee('Inception')
            ->assertSee('Origen')
            ->assertSee('インセプション')
            ->assertSee('Un chef-d\'œuvre absolu.')
            ->assertSee('google_translate_element', false);

        // Search by translated title (Spanish "Origen")
        $searchRes = $this->getJson('/api/films?search=Origen');
        $searchRes->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.slug', 'inception');
    }

    public function test_role_based_dashboard_workflows_interviews_and_cinema_articles(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'is_admin' => true]);
        $webAdmin = User::factory()->create(['role' => UserRole::WebAdmin, 'is_admin' => true]);
        $yapimci = User::factory()->create(['role' => UserRole::Yapimci]);
        $yonetmen = User::factory()->create(['role' => UserRole::Yonetmen]);
        $izleyici = User::factory()->create(['role' => UserRole::Izleyici]);

        // 1. Izleyici cannot submit a film (403)
        $this->actingAs($izleyici)->postJson(route('dashboard.films.store'), [
            'title' => 'Yetkisiz Film',
            'year' => 2024,
            'country' => 'Türkiye',
            'genre' => 'Drama',
            'director' => 'Test Yönetmen',
            'description' => 'Test açıklama',
        ])->assertForbidden();

        // 2. Yapimci submits a new film -> status is pending
        $filmRes = $this->actingAs($yapimci)->postJson(route('dashboard.films.store'), [
            'title' => 'Kuru Otlar Üstüne',
            'original_title' => 'About Dry Grasses',
            'year' => 2023,
            'country' => 'Türkiye',
            'genre' => 'Drama',
            'director' => 'Nuri Bilge Ceylan',
            'cast' => 'Deniz Celiloğlu, Merve Dizdar',
            'description' => 'Doğu Anadolu\'da zorunlu hizmetini yapan bir öğretmenin hikayesi.',
        ]);
        $filmRes->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $filmId = $filmRes->json('data.id');
        $filmSlug = $filmRes->json('data.slug');

        // 3. Yonetmen submits an interview for the film -> status is pending
        $interviewRes = $this->actingAs($yonetmen)->postJson(route('dashboard.interviews.store'), [
            'film_id' => $filmId,
            'title' => 'Merve Dizdar ile Nuray Karakteri ve Direniş Üzerine Söyleşi',
            'interviewee_name' => 'Merve Dizdar',
            'interviewee_role' => 'oyuncu',
            'excerpt' => 'Cannes En İyi Kadın Oyuncu ödüllü performansın kamera arkası.',
            'content' => 'Soru: Nuray karakterine nasıl hazırlandınız? Cevap: Uzun provalar ve karakterin iç dünyasını anlamak belirleyici oldu.',
        ]);
        $interviewRes->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $interviewId = $interviewRes->json('data.id');
        $interviewSlug = $interviewRes->json('data.slug');

        // 4. Izleyici submits a cinema article -> status is pending
        $articleRes = $this->actingAs($izleyici)->postJson(route('dashboard.articles.store'), [
            'title' => 'Taşrada Zamanın Ağırlığı ve Kış Metaforu',
            'category' => 'Film Analizi',
            'film_id' => $filmId,
            'excerpt' => 'Kuru Otlar Üstüne filminde doğa ve yabancılaşma.',
            'content' => 'Anadolu bozkırında kar altında kalan kasabalar, karakterlerin içsel sıkışmışlığını görselleştiren güçlü bir sinematografik alana dönüşüyor.',
        ]);
        $articleRes->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $articleId = $articleRes->json('data.id');
        $articleSlug = $articleRes->json('data.slug');

        // 5. WebAdmin approves the pending film, interview, and article
        $this->actingAs($webAdmin)->postJson(route('dashboard.moderate'), [
            'type' => 'film',
            'id' => $filmId,
            'action' => 'approve',
        ])->assertOk()->assertJsonPath('status', 'approved');

        $this->actingAs($webAdmin)->postJson(route('dashboard.moderate'), [
            'type' => 'interview',
            'id' => $interviewId,
            'action' => 'approve',
        ])->assertOk()->assertJsonPath('status', 'approved');

        $this->actingAs($webAdmin)->postJson(route('dashboard.moderate'), [
            'type' => 'article',
            'id' => $articleId,
            'action' => 'approve',
        ])->assertOk()->assertJsonPath('status', 'approved');

        // 6. Approved interview and article appear on Film Detail Page, Interview Page, and Articles Page
        $this->get(route('films.show', $filmSlug))
            ->assertOk()
            ->assertSee('Merve Dizdar ile Nuray Karakteri ve Direniş Üzerine Söyleşi')
            ->assertSee('Taşrada Zamanın Ağırlığı ve Kış Metaforu');

        $this->get(route('interviews.show', $interviewSlug))
            ->assertOk()
            ->assertSee('Merve Dizdar')
            ->assertSee('Kuru Otlar Üstüne');

        $this->get(route('articles.index'))
            ->assertOk()
            ->assertSee('Sinema Yazıları')
            ->assertSee('Taşrada Zamanın Ağırlığı ve Kış Metaforu');

        $this->get(route('articles.show', $articleSlug))
            ->assertOk()
            ->assertSee('Taşrada Zamanın Ağırlığı ve Kış Metaforu');

        // 7. Only Admin can change user roles (WebAdmin gets 403)
        $this->actingAs($webAdmin)->putJson(route('dashboard.users.role', $izleyici), [
            'role' => UserRole::Yonetmen->value,
        ])->assertForbidden();

        $this->actingAs($admin)->putJson(route('dashboard.users.role', $izleyici), [
            'role' => UserRole::Yonetmen->value,
        ])->assertOk()->assertJsonPath('data.role', UserRole::Yonetmen->value);
    }

    public function test_api_login_persists_web_session_for_dashboard_access(): void
    {
        User::factory()->create([
            'name' => 'Zeynep Özbatur Atakan',
            'email' => 'yapimci@cine.local',
            'password' => bcrypt('password123'),
            'role' => UserRole::Yapimci,
        ]);

        // Unauthenticated visit to /dashboard redirects to home with ?login=1
        $this->get('/dashboard')->assertRedirect(route('home', ['login' => 1]));

        // Login via /api/login sets web session cookie
        $loginRes = $this->postJson('/api/login', [
            'email' => 'yapimci@cine.local',
            'password' => 'password123',
        ]);
        $loginRes->assertOk()->assertJsonPath('data.role', 'yapimci');

        // Subsequent GET /dashboard opens the dashboard page with 200 OK
        $dashboardRes = $this->get('/dashboard');
        $dashboardRes->assertOk()
            ->assertSee('Yönetim & Üretim Paneli')
            ->assertSee('Yeni Film Bilgisi Gir');
    }

    public function test_phase_e_api_security_moderation_protection_and_discovery_filters(): void
    {
        $yapimci = User::factory()->create(['role' => UserRole::Yapimci]);
        $webAdmin = User::factory()->create(['role' => UserRole::WebAdmin, 'is_admin' => true]);
        $izleyici = User::factory()->create(['role' => UserRole::Izleyici]);

        // 1. Anonymous user cannot POST /api/films (401) and Izleyici gets 403
        $this->postJson('/api/films', [
            'title' => 'Anonim Film',
            'slug' => 'anonim-film',
        ])->assertUnauthorized();

        $this->actingAs($izleyici, 'sanctum')->postJson('/api/films', [
            'title' => 'İzleyici Filmi',
            'slug' => 'izleyici-filmi',
        ])->assertForbidden();

        // 2. Yapimci creates a film via API -> status is pending
        $pendingFilmRes = $this->actingAs($yapimci, 'sanctum')->postJson('/api/films', [
            'title' => 'Taslak Festival Filmi',
            'slug' => 'taslak-festival-filmi',
            'year' => 2024,
            'director' => 'Nuri Bilge Ceylan',
        ]);
        $pendingFilmRes->assertCreated()->assertJsonPath('data.status', 'pending');

        // 3. Pending film is hidden from public /films/{slug} (404) and other viewers,
        //    but visible to its creator ($yapimci) and $webAdmin
        auth()->forgetGuards();
        $this->get(route('films.show', 'taslak-festival-filmi'))->assertNotFound();
        $this->actingAs($izleyici)->get(route('films.show', 'taslak-festival-filmi'))->assertNotFound();
        $this->actingAs($yapimci)->get(route('films.show', 'taslak-festival-filmi'))->assertOk();
        $this->actingAs($webAdmin)->get(route('films.show', 'taslak-festival-filmi'))->assertOk();

        // 4. Discovery Engine: decade filter & rating_desc / year_asc sorting
        $film1994 = Film::create([
            'title' => 'Ucuz Roman',
            'slug' => 'pulp-fiction-1994',
            'year' => 1994,
            'genre' => 'Suç',
            'status' => 'approved',
        ]);
        $film2014 = Film::create([
            'title' => 'Kış Uykusu',
            'slug' => 'winter-sleep-2014',
            'year' => 2014,
            'genre' => 'Drama',
            'status' => 'approved',
        ]);

        Review::create([
            'user_id' => $izleyici->id,
            'film_id' => $film1994->id,
            'rating' => 8,
            'content' => 'Kült eser.',
        ]);
        Review::create([
            'user_id' => $izleyici->id,
            'film_id' => $film2014->id,
            'rating' => 10,
            'content' => 'Altın Palmiye ödüllü başyapıt.',
        ]);

        // Filter by 1990s decade -> only 1994 film (pending 2024 and approved 2014 excluded)
        $decadeRes = $this->getJson('/api/films?decade=1990');
        $decadeRes->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.slug', 'pulp-fiction-1994');

        // Sort by rating_desc -> 2014 film (rating 10) comes first
        $sortRes = $this->getJson('/api/films?sort=rating_desc');
        $sortRes->assertOk()
            ->assertJsonCount(2, 'data.data')
            ->assertJsonPath('data.data.0.slug', 'winter-sleep-2014')
            ->assertJsonPath('data.data.1.slug', 'pulp-fiction-1994');
    }

    public function test_phase_f_creative_crew_character_names_biographies_and_festival_awards(): void
    {
        $film = Film::create([
            'title' => 'Kış Uykusu',
            'slug' => 'winter-sleep',
            'year' => 2014,
            'runtime' => 196,
            'country' => 'Türkiye',
            'genre' => 'Drama',
            'director' => 'Nuri Bilge Ceylan',
            'writer' => 'Nuri Bilge Ceylan, Ebru Ceylan',
            'cinematographer' => 'Gökhan Tiryaki',
            'composer' => 'Franz Schubert',
            'cast' => 'Haluk Bilginer (Aydın), Melisa Sözen (Nihal)',
            'status' => 'approved',
        ]);

        $director = Person::where('slug', 'nuri-bilge-ceylan')->first();
        $dop = Person::where('slug', 'gokhan-tiryaki')->first();
        $actor = Person::where('slug', 'haluk-bilginer')->first();

        $this->assertNotNull($director);
        $this->assertNotNull($dop);
        $this->assertNotNull($actor);

        $director->update([
            'country' => 'Türkiye',
            'bio' => 'Altın Palmiye ödüllü auteur yönetmen.',
        ]);

        FilmAward::create([
            'film_id' => $film->id,
            'person_id' => $director->id,
            'festival_name' => 'Cannes Film Festivali',
            'award_name' => 'Altın Palmiye (Palme d\'Or)',
            'year' => 2014,
            'result' => 'winner',
        ]);

        // Verify film detail page renders runtime, crew, character names, and awards
        $this->get(route('films.show', $film->slug))
            ->assertOk()
            ->assertSee('196 dk')
            ->assertSee('Ebru Ceylan')
            ->assertSee('Gökhan Tiryaki')
            ->assertSee('Franz Schubert')
            ->assertSee('Haluk Bilginer')
            ->assertSee('rol: Aydın')
            ->assertSee('Cannes Film Festivali (2014)')
            ->assertSee('Altın Palmiye');

        // Verify person filmography page renders biography, awards, and technical crew credits
        $this->get(route('people.show', $director->slug))
            ->assertOk()
            ->assertSee('Altın Palmiye ödüllü auteur yönetmen.')
            ->assertSee('Senaryosunu Yazdığı Filmler')
            ->assertSee('Cannes Film Festivali (2014)');

        $this->get(route('people.show', $dop->slug))
            ->assertOk()
            ->assertSee('Görüntü Yönetmenliğini Yaptığı Filmler')
            ->assertSee('Kış Uykusu');

        $this->get(route('people.show', $actor->slug))
            ->assertOk()
            ->assertSee('Rol: Aydın');
    }

    public function test_phase_g_film_of_the_day_public_profiles_four_favorites_and_collection_builder(): void
    {
        $webAdmin = User::factory()->create(['role' => UserRole::WebAdmin, 'is_admin' => true]);
        $yonetmen = User::factory()->create(['name' => 'Céline Sciamma', 'role' => UserRole::Yonetmen]);
        $izleyici = User::factory()->create(['name' => 'Sinema Okuru', 'role' => UserRole::Izleyici]);

        $film1 = Film::create([
            'title' => 'Alev Almış Bir Genç Kızın Portresi',
            'slug' => 'portrait-of-a-lady-on-fire',
            'year' => 2019,
            'director' => 'Céline Sciamma',
            'status' => 'approved',
        ]);
        $film2 = Film::create([
            'title' => 'Aşk Zamanı',
            'slug' => 'in-the-mood-for-love',
            'year' => 2000,
            'director' => 'Wong Kar-wai',
            'status' => 'approved',
        ]);

        // 1. WebAdmin sets Film of the Day
        $this->actingAs($webAdmin)->postJson(route('dashboard.film-of-the-day'), [
            'film_id' => $film1->id,
            'curator_pitch' => 'Bakışın ve belleğin sinemadaki en şiirsel karşılığı.',
        ])->assertOk();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Günün Filmi')
            ->assertSee('Bakışın ve belleğin sinemadaki en şiirsel karşılığı.');

        // 2. Yonetmen creates a curated film collection from the dashboard
        $colRes = $this->actingAs($yonetmen)->postJson(route('dashboard.collections.store'), [
            'title' => 'Bellek ve Bakış Seçkisi',
            'description' => 'Zamanı ve bakışı merkeze alan iki başyapıt.',
            'film_ids' => [$film1->id, $film2->id],
            'curator_note' => 'Küratör seçkisi.',
        ]);
        $colRes->assertCreated()->assertJsonCount(2, 'data.films');

        // 3. Yonetmen updates bio & Favorite 4 Films showcase
        $this->actingAs($yonetmen)->postJson(route('profile.showcase.update'), [
            'bio' => 'Fransız bağımsız sinemasında kimlik ve bellek üzerine çalışan yönetmen.',
            'favorite_film_ids' => [$film1->id, $film2->id],
        ])->assertOk();

        // 4. Izleyici follows Yonetmen and visits public profile (/users/{member})
        $this->actingAs($izleyici)->postJson(route('users.follow', $yonetmen))
            ->assertOk()
            ->assertJsonPath('following', true)
            ->assertJsonPath('followers_count', 1);

        $this->get(route('users.show', $yonetmen))
            ->assertOk()
            ->assertSee('Céline Sciamma')
            ->assertSee('Fransız bağımsız sinemasında kimlik ve bellek üzerine çalışan yönetmen.')
            ->assertSee('Favori 4 Film Vitrini')
            ->assertSee('Alev Almış Bir Genç Kızın Portresi')
            ->assertSee('Bellek ve Bakış Seçkisi');
    }

    public function test_phase_h_bayesian_weighted_rating_trailer_lightbox_and_editorial_qa_blocks(): void
    {
        $user = User::factory()->create(['name' => 'Sinema Editörü']);
        $film = Film::create([
            'title' => 'Başlangıç',
            'slug' => 'inception-phase-h',
            'year' => 2010,
            'director' => 'Christopher Nolan',
            'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
            'status' => 'approved',
        ]);

        Review::create([
            'user_id' => $user->id,
            'film_id' => $film->id,
            'rating' => 10,
            'content' => 'Zaman kurgusu kusursuz.',
        ]);

        $this->assertSame('https://www.youtube.com/embed/YoHD9XEInc0?rel=0', $film->embedTrailerUrl());
        // With 1 vote of 10 and prior mean 8.4 (minVotes 3): (1/4)*10 + (3/4)*8.4 = 2.5 + 6.3 = 8.8
        $this->assertSame(8.8, $film->weightedRating(8.4, 3));

        $interview = Interview::create([
            'film_id' => $film->id,
            'user_id' => $user->id,
            'title' => 'Christopher Nolan ile Rüya Katmanları Üzerine',
            'slug' => 'nolan-ruya-katmanlari',
            'interviewee_name' => 'Christopher Nolan',
            'interviewee_role' => 'yonetmen',
            'status' => 'approved',
            'content' => "Soru: Rüya katmanlarını nasıl kurguladınız?\n\nChristopher Nolan: Zamanın her katmanda farklı akması kurgunun ritmini belirledi.",
        ]);

        $blocks = $interview->formattedBlocks();
        $this->assertCount(2, $blocks);
        $this->assertSame('question', $blocks[0]['type']);
        $this->assertSame('answer', $blocks[1]['type']);
        $this->assertSame('Christopher Nolan', $blocks[1]['speaker']);

        $this->get(route('films.show', $film->slug))
            ->assertOk()
            ->assertSee('Fragmanı Sinema Modunda İzle')
            ->assertSee('trailerLightboxModal', false)
            ->assertSee('Ağırlıklı Skor: 8.80');

        $this->get(route('interviews.show', $interview->slug))
            ->assertOk()
            ->assertSee('Soru (Sinema Editörü)')
            ->assertSee('💬 Christopher Nolan');
    }
}
